<?php

namespace Database\Seeders;

use App\Enums\Rbac\Role as RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Assign Shield resource permissions (ViewAny:X, Create:X, etc.) per role.
 * SuperAdmin sudah dapat semua via RolePermissionSeeder — jangan disentuh di sini.
 *
 * Jalankan SETELAH RolePermissionSeeder (permissions harus sudah ada di DB).
 */
class RoleShieldSeeder extends Seeder
{
    // Actions yang tersedia di Shield
    private const FULL    = ['ViewAny', 'View', 'Create', 'Update', 'Delete', 'DeleteAny', 'Restore', 'RestoreAny'];
    private const CRUD    = ['ViewAny', 'View', 'Create', 'Update', 'Delete', 'DeleteAny'];
    private const EDIT    = ['ViewAny', 'View', 'Update'];
    private const VIEW    = ['ViewAny', 'View'];
    private const SUBMIT  = ['ViewAny', 'View', 'Create', 'Update']; // buat + edit sendiri, tidak delete

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Reset Shield permissions pada semua role kecuali SuperAdmin
        $skipRoles = [RoleEnum::SuperAdmin->value];
        Role::whereNotIn('name', $skipRoles)->each(function (Role $role) {
            $shieldPerms = $role->permissions->filter(fn ($p) => str_contains($p->name, ':'));
            $role->revokePermissionTo($shieldPerms);
        });

        $this->grantRektor();
        $this->grantDekan();
        $this->grantKaprodi();
        $this->grantDosen();
        $this->grantMahasiswa();
        $this->grantAdminAkademik();
        $this->grantAdminKeuangan();
        $this->grantAdminSdm();
        $this->grantItAdmin();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->command->info('Shield permissions assigned for all roles.');
    }

    // -----------------------------------------------------------------------
    // REKTOR & WAKIL REKTOR — view-only semua domain untuk keperluan reporting
    // -----------------------------------------------------------------------
    private function grantRektor(): void
    {
        $resources = [
            // Institusi
            'Faculty', 'StudyProgram', 'Curriculum', 'Course', 'Prerequisite', 'GradeSchema',
            // Periode & Fasilitas
            'AcademicCalendar', 'Semester', 'Classroom',
            // SDM
            'Lecturer', 'Employee', 'User', 'Student',
            // Pre-Semester
            'CourseOffering', 'Schedule', 'LecturerWorkload',
            // Keuangan
            'BillingComponent', 'BillingRate', 'Invoice', 'InvoiceItem', 'Payment',
            'Discount', 'Fine', 'Refund', 'UktGroup', 'Scholarship', 'ScholarshipRecipient',
            // KRS
            'Enrollment', 'KrsItem',
            // Perkuliahan
            'ClassSession', 'Attendance', 'Grade', 'GradeAppeal', 'GradeSchema', 'Transcript', 'ExamSchedule',
            // Layanan
            'LetterTemplate', 'LetterRequest', 'LetterArchive', 'Approval',
            'ThesisTopic', 'ThesisAdvisor', 'Logbook', 'ThesisDefense',
            'MbkmProgram', 'MbkmEnrollment', 'SksConversion',
            'Announcement', 'ErpNotification',
            // HRIS
            'AttendanceRecord', 'LeaveRequest', 'EmploymentHistory',
            'PayrollPeriod', 'Salary', 'SalaryDetail', 'SalaryComponent', 'TeachingHonor', 'TaxCalculation',
        ];

        $perms = $this->build($resources, self::VIEW);
        $this->assignTo([RoleEnum::Rektor, RoleEnum::WakilRektor], $perms);
    }

    // -----------------------------------------------------------------------
    // DEKAN & WAKIL DEKAN — view akademik, approve e-office & thesis
    // -----------------------------------------------------------------------
    private function grantDekan(): void
    {
        $perms = array_merge(
            $this->build(['Faculty', 'StudyProgram', 'Curriculum', 'Course', 'Prerequisite',
                'AcademicCalendar', 'Semester', 'Lecturer', 'Student',
                'CourseOffering', 'Schedule', 'Enrollment', 'KrsItem',
                'Grade', 'Transcript', 'Attendance',
                'Scholarship', 'ScholarshipRecipient'], self::VIEW),
            $this->build(['LetterRequest', 'LetterArchive', 'Approval', 'Workflow', 'WorkflowStep'], self::EDIT),
            $this->build(['ThesisTopic', 'ThesisAdvisor', 'ThesisDefense'], self::EDIT),
            $this->build(['Announcement'], self::VIEW),
        );

        $this->assignTo([RoleEnum::Dekan, RoleEnum::WakilDekan], $perms);
    }

    // -----------------------------------------------------------------------
    // KAPRODI & SEKPRODI — kelola kurikulum, approve KRS, lihat nilai
    // -----------------------------------------------------------------------
    private function grantKaprodi(): void
    {
        $perms = array_merge(
            $this->build(['Curriculum', 'Course', 'Prerequisite', 'GradeSchema'], self::FULL),
            $this->build(['AcademicCalendar', 'Semester', 'Classroom'], self::VIEW),
            $this->build(['Lecturer', 'Student'], self::VIEW),
            $this->build(['CourseOffering', 'Schedule', 'LecturerWorkload'], self::CRUD),
            $this->build(['Enrollment', 'KrsItem'], self::EDIT),
            $this->build(['Grade', 'GradeAppeal', 'Transcript', 'ClassSession', 'Attendance', 'ExamSchedule'], self::VIEW),
            $this->build(['ThesisTopic', 'ThesisAdvisor', 'ThesisDefense', 'Logbook'], self::FULL),
            $this->build(['MbkmProgram', 'MbkmEnrollment', 'SksConversion'], self::CRUD),
            $this->build(['Scholarship', 'ScholarshipRecipient'], self::VIEW),
            $this->build(['Announcement'], self::VIEW),
            $this->build(['LetterRequest', 'Approval'], self::EDIT),
        );

        $this->assignTo([RoleEnum::Kaprodi, RoleEnum::Sekprodi], $perms);
    }

    // -----------------------------------------------------------------------
    // DOSEN — approve KRS wali, input nilai, presensi, lihat jadwal
    // -----------------------------------------------------------------------
    private function grantDosen(): void
    {
        $perms = array_merge(
            $this->build(['Enrollment', 'KrsItem'], self::EDIT),
            $this->build(['CourseOffering', 'Schedule'], self::VIEW),
            $this->build(['Student', 'Transcript'], self::VIEW),
            $this->build(['ClassSession'], self::EDIT),
            $this->build(['Attendance'], self::CRUD),
            $this->build(['Grade', 'GradeAppeal'], self::CRUD),
            $this->build(['LecturerWorkload'], self::VIEW),
            $this->build(['ThesisAdvisor', 'ThesisTopic', 'Logbook', 'ThesisDefense'], self::EDIT),
            $this->build(['LetterRequest', 'Approval'], self::EDIT),
            $this->build(['Announcement', 'ErpNotification'], self::VIEW),
        );

        $this->assignTo([RoleEnum::Dosen], $perms);
    }

    // -----------------------------------------------------------------------
    // MAHASISWA — KRS sendiri, lihat tagihan, ajukan surat/skripsi
    // -----------------------------------------------------------------------
    private function grantMahasiswa(): void
    {
        $perms = array_merge(
            $this->build(['Enrollment', 'KrsItem'], self::SUBMIT),
            $this->build(['Invoice', 'InvoiceItem', 'Payment'], self::VIEW),
            $this->build(['CourseOffering', 'Schedule'], self::VIEW),
            $this->build(['Grade', 'Transcript', 'Attendance', 'ClassSession'], self::VIEW),
            $this->build(['LetterRequest'], self::SUBMIT),
            $this->build(['LetterArchive', 'LetterTemplate'], self::VIEW),
            $this->build(['ThesisTopic', 'Logbook', 'ThesisAdvisor', 'ThesisDefense'], self::SUBMIT),
            $this->build(['MbkmEnrollment', 'SksConversion'], self::SUBMIT),
            $this->build(['GradeAppeal'], self::SUBMIT),
            $this->build(['Scholarship', 'ScholarshipRecipient'], self::VIEW),
            $this->build(['Announcement', 'ErpNotification'], self::VIEW),
        );

        $this->assignTo([RoleEnum::Mahasiswa], $perms);
    }

    // -----------------------------------------------------------------------
    // ADMIN AKADEMIK — full akademik, jadwal, KRS, nilai, komunikasi
    // -----------------------------------------------------------------------
    private function grantAdminAkademik(): void
    {
        $perms = array_merge(
            $this->build(['AcademicCalendar', 'Semester', 'Classroom'], self::FULL),
            $this->build(['Faculty', 'StudyProgram', 'Curriculum', 'Course', 'Prerequisite', 'GradeSchema'], self::FULL),
            $this->build(['Student'], self::FULL),
            $this->build(['Lecturer'], self::VIEW),
            $this->build(['CourseOffering', 'Schedule', 'LecturerWorkload'], self::FULL),
            $this->build(['Enrollment', 'KrsItem'], self::FULL),
            $this->build(['ClassSession', 'Attendance', 'ExamSchedule'], self::FULL),
            $this->build(['Grade', 'GradeAppeal', 'GradeSchema', 'Transcript'], self::FULL),
            $this->build(['LetterTemplate', 'LetterRequest', 'LetterArchive',
                'Approval', 'Workflow', 'WorkflowStep', 'ESignature'], self::FULL),
            $this->build(['ThesisTopic', 'ThesisAdvisor', 'ThesisDefense', 'Logbook'], self::FULL),
            $this->build(['MbkmProgram', 'MbkmEnrollment', 'SksConversion'], self::FULL),
            $this->build(['Scholarship', 'ScholarshipRecipient'], self::FULL),
            $this->build(['NotificationTemplate', 'Audience', 'Announcement',
                'ErpNotification', 'DeliveryLog'], self::CRUD),
            $this->build(['ResearchRepository'], self::FULL),
            $this->build(['UktGroup'], self::VIEW),
            $this->build(['Invoice', 'InvoiceItem'], self::VIEW),
        );

        $this->assignTo([RoleEnum::AdminAkademik], $perms);
    }

    // -----------------------------------------------------------------------
    // ADMIN KEUANGAN — full keuangan, lihat mahasiswa & semester
    // -----------------------------------------------------------------------
    private function grantAdminKeuangan(): void
    {
        $perms = array_merge(
            $this->build(['BillingComponent', 'BillingRate', 'UktGroup'], self::FULL),
            $this->build(['Invoice', 'InvoiceItem', 'Payment', 'Discount', 'Fine', 'Refund'], self::FULL),
            $this->build(['Scholarship', 'ScholarshipRecipient'], self::FULL),
            $this->build(['Student'], self::VIEW),
            $this->build(['Semester'], self::VIEW),
            $this->build(['Enrollment'], self::VIEW),
            $this->build(['Announcement', 'ErpNotification'], self::CRUD),
        );

        $this->assignTo([RoleEnum::AdminKeuangan], $perms);
    }

    // -----------------------------------------------------------------------
    // ADMIN SDM — full HRIS, kelola user/dosen/karyawan
    // -----------------------------------------------------------------------
    private function grantAdminSdm(): void
    {
        $perms = array_merge(
            $this->build(['User', 'Lecturer', 'Employee'], self::FULL),
            $this->build(['LecturerWorkload'], self::FULL),
            $this->build(['AttendanceRecord', 'LeaveRequest', 'EmploymentHistory'], self::FULL),
            $this->build(['PayrollPeriod', 'SalaryComponent', 'Salary', 'SalaryDetail',
                'TeachingHonor', 'TaxCalculation'], self::FULL),
            $this->build(['Faculty', 'StudyProgram'], self::VIEW),
            $this->build(['ClassSession'], self::VIEW), // untuk hitung TeachingHonor
        );

        $this->assignTo([RoleEnum::AdminSdm], $perms);
    }

    // -----------------------------------------------------------------------
    // IT ADMIN — RBAC, user, audit log
    // -----------------------------------------------------------------------
    private function grantItAdmin(): void
    {
        $perms = array_merge(
            $this->build(['Role', 'User'], self::FULL),
            $this->build(['Activity'], self::VIEW),
            $this->build(['NotificationTemplate', 'Audience', 'Announcement',
                'ErpNotification', 'DeliveryLog'], self::FULL),
        );

        $this->assignTo([RoleEnum::ItAdmin], $perms);
    }

    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------

    /**
     * Bangun array nama permission dari daftar resource × actions.
     *
     * @param  string[]  $resources
     * @param  string[]  $actions
     * @return string[]
     */
    private function build(array $resources, array $actions): array
    {
        $names = [];
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $names[] = "{$action}:{$resource}";
            }
        }

        return $names;
    }

    /**
     * Assign permission array ke satu atau lebih role, skip jika permission tidak ada di DB.
     *
     * @param  RoleEnum[]  $roleEnums
     * @param  string[]    $permNames
     */
    private function assignTo(array $roleEnums, array $permNames): void
    {
        $existing = Permission::whereIn('name', array_unique($permNames))
            ->pluck('name')
            ->all();

        foreach ($roleEnums as $roleEnum) {
            $role = Role::firstWhere('name', $roleEnum->value);
            if (! $role) {
                continue;
            }
            $role->givePermissionTo($existing);
        }
    }
}
