<?php

namespace Database\Seeders;

use App\Enums\Academic\RoomType;
use App\Models\Classroom;
use Illuminate\Database\Seeder;

/**
 * Demo: 8 Ruangan (kelas, lab, auditorium).
 *
 * Cara pakai: php artisan db:seed --class=DemoClassroomSeeder
 */
class DemoClassroomSeeder extends Seeder
{

    public function run(): void
    {
        $rows = [
            ['code' => 'R301',    'name' => 'Ruang 301',                'building' => 'Gedung A', 'floor' => '3', 'capacity' => 40,  'type' => RoomType::Kelas],
            ['code' => 'R302',    'name' => 'Ruang 302',                'building' => 'Gedung A', 'floor' => '3', 'capacity' => 40,  'type' => RoomType::Kelas],
            ['code' => 'R303',    'name' => 'Ruang 303',                'building' => 'Gedung A', 'floor' => '3', 'capacity' => 40,  'type' => RoomType::Kelas],
            ['code' => 'R401',    'name' => 'Ruang 401',                'building' => 'Gedung A', 'floor' => '4', 'capacity' => 40,  'type' => RoomType::Kelas],
            ['code' => 'LAB-IF1', 'name' => 'Lab Pemrograman 1',        'building' => 'Gedung B', 'floor' => '2', 'capacity' => 30,  'type' => RoomType::Lab],
            ['code' => 'LAB-IF2', 'name' => 'Lab Jaringan',             'building' => 'Gedung B', 'floor' => '2', 'capacity' => 30,  'type' => RoomType::Lab],
            ['code' => 'STD-1',   'name' => 'Studio Multimedia',        'building' => 'Gedung B', 'floor' => '3', 'capacity' => 25,  'type' => RoomType::Studio],
            ['code' => 'AUD-1',   'name' => 'Auditorium Utama',         'building' => 'Gedung C', 'floor' => '1', 'capacity' => 200, 'type' => RoomType::Auditorium],
        ];

        foreach ($rows as $row) {
            Classroom::firstOrCreate(
                ['code' => $row['code']],
                [
                    'name' => $row['name'],
                    'building' => $row['building'],
                    'floor' => $row['floor'],
                    'capacity' => $row['capacity'],
                    'room_type' => $row['type']->value,
                    'is_active' => true,
                ],
            );
        }

        $this->command->info('✓ '.Classroom::count().' Ruangan dibuat');
    }
}
