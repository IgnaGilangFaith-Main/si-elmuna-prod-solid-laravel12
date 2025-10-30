<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SuperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'name' => 'Gilang',
            'email' => 'gilang@gilang.com',
            'password' => bcrypt('olevmrtsgvou'),
            'email_verified_at' => now(),
            'blocked_at' => null,
        ];

        $permissions = [
            'admin-user',
            'admin-keuangan',
        ];

        // Buat permission jika belum ada
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $newUser = User::create($data);
        $newUser->syncPermissions($permissions);
    }
}
