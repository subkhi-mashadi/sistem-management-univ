<?php

namespace App\Enums\Rbac;

use App\Enums\Concerns\HasValues;

enum Role: string
{
    use HasValues;

    case SuperAdmin = 'Super Admin';
    case Rektor = 'Rektor';
    case WakilRektor = 'Wakil Rektor';
    case Dekan = 'Dekan';
    case WakilDekan = 'Wakil Dekan';
    case Kaprodi = 'Kaprodi';
    case Sekprodi = 'Sekprodi';
    case Dosen = 'Dosen';
    case Mahasiswa = 'Mahasiswa';
    case AdminAkademik = 'Admin Akademik';
    case AdminKeuangan = 'Admin Keuangan';
    case AdminSdm = 'Admin SDM';
    case ItAdmin = 'IT Admin';

    public function requiresMfa(): bool
    {
        return match ($this) {
            self::SuperAdmin, self::Rektor, self::Dekan, self::ItAdmin => true,
            default => false,
        };
    }

    public function label(): string
    {
        return $this->value;
    }
}
