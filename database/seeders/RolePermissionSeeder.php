<?php

namespace Database\Seeders;

use App\Enums\Rbac\PermissionAction;
use App\Enums\Rbac\PermissionModule;
use App\Enums\Rbac\Role as RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (PermissionModule::cases() as $module) {
            foreach (PermissionAction::cases() as $action) {
                Permission::firstOrCreate([
                    'name' => "{$module->value}.{$action->value}",
                    'guard_name' => 'web',
                ]);
            }
        }

        foreach (RoleEnum::cases() as $roleEnum) {
            Role::firstOrCreate([
                'name' => $roleEnum->value,
                'guard_name' => 'web',
            ]);
        }

        $this->grantSuperAdmin();
        $this->grantRektorat();
        $this->grantDekan();
        $this->grantKaprodi();
        $this->grantDosen();
        $this->grantAdminAkademik();
        $this->grantAdminKeuangan();
        $this->grantAdminSdm();
        $this->grantItAdmin();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function grantSuperAdmin(): void
    {
        Role::firstWhere('name', RoleEnum::SuperAdmin->value)
            ?->syncPermissions(Permission::all());
    }

    private function grantRektorat(): void
    {
        $perms = $this->permissionsFor([
            [PermissionModule::Reporting, [PermissionAction::View, PermissionAction::Export]],
            [PermissionModule::Academic, [PermissionAction::View]],
            [PermissionModule::Finance, [PermissionAction::View]],
            [PermissionModule::Hris, [PermissionAction::View]],
            [PermissionModule::EOffice, [PermissionAction::View, PermissionAction::Approve]],
        ]);

        Role::firstWhere('name', RoleEnum::Rektor->value)?->syncPermissions($perms);
        Role::firstWhere('name', RoleEnum::WakilRektor->value)?->syncPermissions($perms);
    }

    private function grantDekan(): void
    {
        $perms = $this->permissionsFor([
            [PermissionModule::Reporting, [PermissionAction::View, PermissionAction::Export]],
            [PermissionModule::Academic, [PermissionAction::View]],
            [PermissionModule::EOffice, [PermissionAction::View, PermissionAction::Approve]],
            [PermissionModule::Thesis, [PermissionAction::View, PermissionAction::Approve]],
        ]);

        Role::firstWhere('name', RoleEnum::Dekan->value)?->syncPermissions($perms);
    }

    private function grantKaprodi(): void
    {
        $perms = $this->permissionsFor([
            [PermissionModule::Academic, PermissionAction::cases()],
            [PermissionModule::Scheduling, PermissionAction::cases()],
            [PermissionModule::Krs, [PermissionAction::View, PermissionAction::Approve, PermissionAction::Edit]],
            [PermissionModule::EOffice, [PermissionAction::View, PermissionAction::Approve]],
            [PermissionModule::Thesis, PermissionAction::cases()],
            [PermissionModule::Reporting, [PermissionAction::View, PermissionAction::Export]],
        ]);

        Role::firstWhere('name', RoleEnum::Kaprodi->value)?->syncPermissions($perms);
    }

    private function grantDosen(): void
    {
        $perms = $this->permissionsFor([
            [PermissionModule::Academic, [PermissionAction::View]],
            [PermissionModule::Scheduling, [PermissionAction::View]],
            [PermissionModule::Krs, [PermissionAction::View, PermissionAction::Edit, PermissionAction::Approve]],
            [PermissionModule::Thesis, [PermissionAction::View, PermissionAction::Edit]],
            [PermissionModule::EOffice, [PermissionAction::View, PermissionAction::Approve]],
        ]);

        Role::firstWhere('name', RoleEnum::Dosen->value)?->syncPermissions($perms);
    }

    private function grantAdminAkademik(): void
    {
        $perms = $this->permissionsFor([
            [PermissionModule::Academic, PermissionAction::cases()],
            [PermissionModule::Scheduling, PermissionAction::cases()],
            [PermissionModule::Krs, PermissionAction::cases()],
            [PermissionModule::EOffice, PermissionAction::cases()],
            [PermissionModule::Communication, [PermissionAction::View, PermissionAction::Create]],
        ]);

        Role::firstWhere('name', RoleEnum::AdminAkademik->value)?->syncPermissions($perms);
    }

    private function grantAdminKeuangan(): void
    {
        $perms = $this->permissionsFor([
            [PermissionModule::Finance, PermissionAction::cases()],
            [PermissionModule::Reporting, [PermissionAction::View, PermissionAction::Export]],
            [PermissionModule::Communication, [PermissionAction::View, PermissionAction::Create]],
        ]);

        Role::firstWhere('name', RoleEnum::AdminKeuangan->value)?->syncPermissions($perms);
    }

    private function grantAdminSdm(): void
    {
        $perms = $this->permissionsFor([
            [PermissionModule::Hris, PermissionAction::cases()],
            [PermissionModule::Reporting, [PermissionAction::View, PermissionAction::Export]],
        ]);

        Role::firstWhere('name', RoleEnum::AdminSdm->value)?->syncPermissions($perms);
    }

    private function grantItAdmin(): void
    {
        $perms = $this->permissionsFor([
            [PermissionModule::Rbac, PermissionAction::cases()],
            [PermissionModule::Communication, PermissionAction::cases()],
            [PermissionModule::Reporting, PermissionAction::cases()],
        ]);

        Role::firstWhere('name', RoleEnum::ItAdmin->value)?->syncPermissions($perms);
    }

    /**
     * @param  array<int, array{0: PermissionModule, 1: array<int, PermissionAction>}>  $matrix
     * @return array<int, string>
     */
    private function permissionsFor(array $matrix): array
    {
        $names = [];
        foreach ($matrix as [$module, $actions]) {
            foreach ($actions as $action) {
                $names[] = "{$module->value}.{$action->value}";
            }
        }

        return $names;
    }
}
