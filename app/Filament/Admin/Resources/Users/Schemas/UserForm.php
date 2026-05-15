<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use App\Enums\Rbac\UserType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        TextInput::make('username')
                            ->label('Username')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('name')
                            ->label('Display Name')
                            ->required(),
                        TextInput::make('full_name')
                            ->label('Nama Lengkap'),
                        Select::make('user_type')
                            ->label('Tipe User')
                            ->options(UserType::options())
                            ->native(false),
                        TextInput::make('external_id')
                            ->label('NIM/NIDN/NIP'),
                        SpatieMediaLibraryFileUpload::make('avatar')
                            ->label('Foto Profil')
                            ->collection('avatar')
                            ->image()
                            ->avatar()
                            ->imageEditor()
                            ->columnSpanFull(),
                    ]),

                Section::make('Roles & Permissions')
                    ->columnSpanFull()
                    ->components([
                        Select::make('roles')
                            ->label('Role')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->options(fn () => Role::pluck('name', 'name'))
                            ->searchable(),
                    ]),

                Section::make('Keamanan')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create'),
                        DateTimePicker::make('password_expires_at')
                            ->label('Password Expires At'),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->inline(false),
                        Toggle::make('mfa_enabled')
                            ->label('MFA Enabled')
                            ->inline(false),
                        DateTimePicker::make('locked_until')
                            ->label('Locked Until'),
                    ]),
            ]);
    }
}
