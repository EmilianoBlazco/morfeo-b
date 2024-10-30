<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles
        $director = Role::create(['name' => 'Director']);
        $supervisor = Role::create(['name' => 'Supervisor']);
        $operario = Role::create(['name' => 'Operario']);

        // Crear permisos
        $viewReports = Permission::create(['name' => 'view reports']);
        $editUsers = Permission::create(['name' => 'edit users']);
        $createTasks = Permission::create(['name' => 'create tasks']);
        $deleteTasks = Permission::create(['name' => 'delete tasks']);
        $manageRoles = Permission::create(['name' => 'manage roles and permissions']);

        // Asignar permisos a roles
        $director->givePermissionTo([$viewReports, $editUsers, $createTasks, $deleteTasks, $manageRoles]);
        $supervisor->givePermissionTo([$viewReports, $createTasks]);
        $operario->givePermissionTo([$createTasks]);
    }
}
