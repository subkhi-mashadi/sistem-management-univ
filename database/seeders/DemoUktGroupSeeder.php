<?php

namespace Database\Seeders;

use App\Models\UktGroup;
use Illuminate\Database\Seeder;

/**
 * Demo: 8 Golongan UKT standar PTN Indonesia (GL-001 s/d GL-008).
 *
 * Cara pakai: php artisan db:seed --class=DemoUktGroupSeeder
 */
class DemoUktGroupSeeder extends Seeder
{

    public function run(): void
    {
        $rows = [
            ['name' => 'Golongan I',    'min' => 0,         'max' => 500_000,    'desc' => 'Penghasilan ortu ≤ Rp 500 rb (paling rendah)'],
            ['name' => 'Golongan II',   'min' => 500_001,   'max' => 2_000_000,  'desc' => 'Penghasilan ortu Rp 500 rb – 2 jt'],
            ['name' => 'Golongan III',  'min' => 2_000_001, 'max' => 4_000_000,  'desc' => 'Penghasilan ortu Rp 2 jt – 4 jt'],
            ['name' => 'Golongan IV',   'min' => 4_000_001, 'max' => 6_000_000,  'desc' => 'Penghasilan ortu Rp 4 jt – 6 jt'],
            ['name' => 'Golongan V',    'min' => 6_000_001, 'max' => 10_000_000, 'desc' => 'Penghasilan ortu Rp 6 jt – 10 jt'],
            ['name' => 'Golongan VI',   'min' => 10_000_001,'max' => 20_000_000, 'desc' => 'Penghasilan ortu Rp 10 jt – 20 jt'],
            ['name' => 'Golongan VII',  'min' => 20_000_001,'max' => null,       'desc' => 'Penghasilan ortu > Rp 20 jt'],
            ['name' => 'Golongan VIII', 'min' => null,      'max' => null,       'desc' => 'KIP-Kuliah / Beasiswa Penuh'],
        ];

        foreach ($rows as $row) {
            UktGroup::firstOrCreate(
                ['name' => $row['name']],
                [
                    'min_income' => $row['min'],
                    'max_income' => $row['max'],
                    'description' => $row['desc'],
                    'is_active' => true,
                ],
            );
        }

        $this->command->info('✓ '.UktGroup::count().' Golongan UKT dibuat (kode auto: GL-001 s/d GL-008)');
    }
}
