<?php

namespace App\Models;

use App\Enums\Rbac\UserType;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasMedia
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, InteractsWithMedia, LogsActivity, Notifiable, SoftDeletes;

    protected $fillable = [
        'username',
        'name',
        'full_name',
        'email',
        'password',
        'user_type',
        'external_id',
        'is_active',
        'mfa_enabled',
        'mfa_secret',
        'last_login_at',
        'last_login_ip',
        'password_expires_at',
        'failed_login_attempts',
        'locked_until',
        'avatar',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'mfa_secret',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'user_type' => UserType::class,
            'is_active' => 'boolean',
            'mfa_enabled' => 'boolean',
            'last_login_at' => 'datetime',
            'password_expires_at' => 'datetime',
            'locked_until' => 'datetime',
        ];
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function lecturer(): HasOne
    {
        return $this->hasOne(Lecturer::class);
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'full_name', 'email', 'username', 'user_type', 'external_id', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('user');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->getFirstMediaUrl('avatar') ?: null;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if (! $this->is_active) {
            return false;
        }

        return match ($panel->getId()) {
            'student' => $this->user_type === UserType::Student,
            'lecturer' => $this->user_type === UserType::Lecturer,
            'admin' => in_array($this->user_type, [UserType::Admin, UserType::Staff], true),
            default => false,
        };
    }

    /**
     * Derive user_type dari nama role (untuk filter/query cepat).
     */
    public static function deriveUserType(array $roleNames): ?UserType
    {
        if (in_array('Mahasiswa', $roleNames, true)) {
            return UserType::Student;
        }
        if (in_array('Dosen', $roleNames, true)) {
            return UserType::Lecturer;
        }

        $adminRoles = ['Super Admin', 'Rektor', 'Wakil Rektor', 'Dekan', 'Wakil Dekan', 'Kaprodi', 'Sekprodi', 'IT Admin'];
        foreach ($adminRoles as $r) {
            if (in_array($r, $roleNames, true)) {
                return UserType::Admin;
            }
        }

        return UserType::Staff;
    }

    public function isLocked(): bool
    {
        return $this->locked_until !== null && $this->locked_until->isFuture();
    }

    public function passwordExpired(): bool
    {
        return $this->password_expires_at !== null && $this->password_expires_at->isPast();
    }
}
