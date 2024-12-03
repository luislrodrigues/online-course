<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definir permisos
        $permissionsAdm = [
            'actions courses',
            'actions videos',
            'actions studens'
        ];

        // Crear permisos
        foreach ($permissionsAdm as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crear roles y asignar permisos
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->syncPermissions($permissionsAdm);


        $permissionsUser= [
            'actions studens'
        ];


        $userRole = Role::create(['name' => 'user']);
        $userRole->syncPermissions($permissionsUser);
    }
}
