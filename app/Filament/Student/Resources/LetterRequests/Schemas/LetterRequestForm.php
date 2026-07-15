<?php

namespace App\Filament\Student\Resources\LetterRequests\Schemas;

use App\Models\LetterTemplate;
use App\Support\EOffice\LetterSystemFields;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class LetterRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ajukan Surat')
                    ->columnSpanFull()
                    ->columns(1)
                    ->components([
                        Select::make('letter_template_id')
                            ->label('Jenis Surat')
                            ->options(LetterTemplate::query()->where('is_active', true)->pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->required(),
                        ...static::autoDataPreview(),
                        ...static::customFields(),
                        Textarea::make('notes')
                            ->label('Catatan Tambahan')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    /** Preview read-only data yang bakal otomatis masuk ke surat — mahasiswa tidak perlu isi apapun di sini. */
    protected static function autoDataPreview(): array
    {
        return [
            Section::make('Data yang Otomatis Terisi')
                ->columnSpanFull()
                ->columns(2)
                ->visible(fn (Get $get) => filled($get('letter_template_id')) && static::template($get)?->data_fields)
                ->components(function (Get $get) {
                    $template = static::template($get);

                    if (! $template || empty($template->data_fields) || ! auth()->user()?->student) {
                        return [];
                    }

                    $resolved = LetterSystemFields::resolve($template->data_fields, auth()->user()->student);

                    return collect($resolved)
                        ->map(fn (string $value, string $key) => Placeholder::make("preview.{$key}")
                            ->label(LetterSystemFields::label($key))
                            ->content($value))
                        ->values()
                        ->all();
                }),
        ];
    }

    /** Field yang memang harus diisi manual mahasiswa (didefinisikan admin di Template Surat). */
    protected static function customFields(): array
    {
        return [
            Section::make('Data Tambahan')
                ->columnSpanFull()
                ->columns(2)
                ->visible(fn (Get $get) => filled(static::template($get)?->custom_fields))
                ->components(function (Get $get) {
                    $template = static::template($get);

                    if (! $template || empty($template->custom_fields)) {
                        return [];
                    }

                    return collect($template->custom_fields)
                        ->filter(fn ($field) => ! empty($field['key']))
                        ->map(fn ($field) => TextInput::make("form_data.{$field['key']}")
                            ->label($field['label'] ?? $field['key'])
                            ->required())
                        ->values()
                        ->all();
                }),
        ];
    }

    protected static function template(Get $get): ?LetterTemplate
    {
        $templateId = $get('letter_template_id');

        return $templateId ? LetterTemplate::find($templateId) : null;
    }
}
