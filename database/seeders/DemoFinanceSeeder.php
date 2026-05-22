<?php

namespace Database\Seeders;

use App\Enums\Finance\BillingComponentType;
use App\Enums\Finance\InstallmentPlan;
use App\Enums\Finance\InvoiceStatus;
use App\Models\BillingComponent;
use App\Models\BillingRate;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Semester;
use App\Models\Student;
use App\Models\UktGroup;
use Illuminate\Database\Seeder;

/**
 * Demo Layer 8: Komponen Tagihan + Tarif + Invoice masal.
 *
 * Otomatis:
 *  - Komponen: UKT, SPP, Pengembangan, Praktikum, KKN, Wisuda
 *  - Tarif UKT untuk 8 Golongan (matrix Prodi × Angkatan × Golongan)
 *  - Tarif fixed (SPP, Pengembangan, dll)
 *  - Invoice untuk tiap mahasiswa aktif di semester aktif
 *
 * Cara pakai: php artisan db:seed --class=DemoFinanceSeeder
 * Prasyarat: DemoFacultySeeder, DemoUktGroupSeeder, DemoStudentSeeder, DemoAcademicCalendarSeeder.
 */
class DemoFinanceSeeder extends Seeder
{
    public function run(): void
    {
        $semester = Semester::where('is_active', true)->first();
        if (! $semester) {
            $this->command->error('Belum ada Semester aktif. Jalankan DemoAcademicCalendarSeeder dulu.');

            return;
        }

        $components = $this->seedComponents();
        $this->seedRates($components);
        $this->generateInvoices($semester);

        $this->command->info('');
        $this->command->info('✓ Demo Keuangan berhasil dibuat:');
        $this->command->table(
            ['Item', 'Jumlah'],
            [
                ['Komponen Tagihan', BillingComponent::count()],
                ['Tarif (BillingRate)', BillingRate::count()],
                ['Invoice (Semester aktif)', Invoice::where('semester_id', $semester->id)->count()],
                ['Item Invoice', InvoiceItem::count()],
            ],
        );
    }

    /** @return array<string, BillingComponent> */
    private function seedComponents(): array
    {
        $rows = [
            ['code' => 'UKT', 'name' => 'Uang Kuliah Tunggal',    'type' => BillingComponentType::UKT,          'recurring' => true,  'desc' => 'Tarif berdasarkan Golongan UKT.'],
            ['code' => 'SPP', 'name' => 'SPP',                    'type' => BillingComponentType::SPP,          'recurring' => true,  'desc' => 'Sumbangan Pembinaan Pendidikan.'],
            ['code' => 'PEN', 'name' => 'Uang Pengembangan',      'type' => BillingComponentType::Pengembangan, 'recurring' => false, 'desc' => 'Sekali bayar saat masuk.'],
            ['code' => 'PRA', 'name' => 'Biaya Praktikum',        'type' => BillingComponentType::Praktikum,    'recurring' => true,  'desc' => 'Untuk MK berpraktikum.'],
            ['code' => 'KKN', 'name' => 'Biaya KKN',              'type' => BillingComponentType::KKN,          'recurring' => false, 'desc' => 'Sekali, saat semester 7.'],
            ['code' => 'WIS', 'name' => 'Biaya Wisuda',           'type' => BillingComponentType::Wisuda,       'recurring' => false, 'desc' => 'Sekali, menjelang lulus.'],
        ];

        $components = [];
        foreach ($rows as $row) {
            $components[$row['code']] = BillingComponent::firstOrCreate(
                ['code' => $row['code']],
                [
                    'name' => $row['name'],
                    'type' => $row['type']->value,
                    'is_recurring' => $row['recurring'],
                    'description' => $row['desc'],
                    'is_active' => true,
                ],
            );
        }

        return $components;
    }

    /** @param array<string, BillingComponent> $components */
    private function seedRates(array $components): void
    {
        $year = (int) now()->format('Y');

        // Tarif UKT per Golongan (berlaku semua prodi, semua angkatan).
        $uktTariffs = [
            'GL-001' => 2_500_000,
            'GL-002' => 4_000_000,
            'GL-003' => 6_000_000,
            'GL-004' => 8_500_000,
            'GL-005' => 11_000_000,
            'GL-006' => 14_000_000,
            'GL-007' => 18_000_000,
            'GL-008' => 0, // KIP-K
        ];

        foreach ($uktTariffs as $uktCode => $amount) {
            if (! UktGroup::where('code', $uktCode)->exists()) {
                continue;
            }
            BillingRate::firstOrCreate(
                [
                    'billing_component_id' => $components['UKT']->id,
                    'ukt_group' => $uktCode,
                    'enrollment_year' => $year,
                    'study_program_id' => null,
                ],
                [
                    'amount' => $amount,
                    'effective_from' => "{$year}-01-01",
                    'is_active' => true,
                ],
            );
        }

        // Tarif fixed (semua prodi, semua angkatan, semua golongan).
        $fixed = [
            'SPP' => 500_000,
            'PEN' => 5_000_000,
            'PRA' => 300_000,
        ];
        foreach ($fixed as $code => $amount) {
            BillingRate::firstOrCreate(
                [
                    'billing_component_id' => $components[$code]->id,
                    'ukt_group' => null,
                    'enrollment_year' => null,
                    'study_program_id' => null,
                ],
                [
                    'amount' => $amount,
                    'effective_from' => "{$year}-01-01",
                    'is_active' => true,
                ],
            );
        }
    }

    private function generateInvoices(Semester $semester): void
    {
        $students = Student::with('studyProgram')->whereHas('user', fn ($q) => $q->where('is_active', true))->get();

        foreach ($students as $student) {
            if (Invoice::where('student_id', $student->id)->where('semester_id', $semester->id)->exists()) {
                continue;
            }

            $rates = BillingRate::query()
                ->where('is_active', true)
                ->where(function ($q) use ($student) {
                    $q->whereNull('study_program_id')->orWhere('study_program_id', $student->study_program_id);
                })
                ->where(function ($q) use ($student) {
                    $q->whereNull('enrollment_year')->orWhere('enrollment_year', $student->enrollment_year);
                })
                ->where(function ($q) use ($student) {
                    $q->whereNull('ukt_group')->orWhere('ukt_group', $student->ukt_group);
                })
                ->with('component')
                ->get();

            if ($rates->isEmpty()) {
                continue;
            }

            // Invoice — invoice_number, virtual_account, subtotal/total auto-fill via Model boot + Observer.
            $invoice = Invoice::create([
                'student_id' => $student->id,
                'semester_id' => $semester->id,
                'bank_code' => 'BNI',
                'issue_date' => $semester->start_date,
                'due_date' => $semester->krs_end ?? now()->addDays(21),
                'status' => InvoiceStatus::Unpaid->value,
                'installment_plan' => InstallmentPlan::Full->value,
            ]);

            foreach ($rates as $rate) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'billing_component_id' => $rate->billing_component_id,
                    'description' => $rate->component->name.' — '.$semester->name,
                    'amount' => $rate->amount,
                    'quantity' => 1,
                    'total' => $rate->amount,
                ]);
            }
        }
    }
}
