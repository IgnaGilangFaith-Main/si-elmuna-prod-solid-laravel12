<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);

        Permission::create(['name' => 'admin-user']);
        Permission::create(['name' => 'admin-keuangan']);

        $roleAdmin = Role::findByName('admin');

        $roleAdmin->givePermissionTo('admin-user');
        $roleAdmin->givePermissionTo('admin-keuangan');
    }
}
