<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = \Spatie\Permission\Models\Role::findOrCreate('admin');

        $permissions = collect([
            'manage products',
            'manage categories',
            'manage discounts',
            'manage sliders',
            'manage orders',
            'manage sales',
            'manage users',
            'view reports',
        ])->map(fn (string $name) => \Spatie\Permission\Models\Permission::findOrCreate($name))->all();

        $adminRole->syncPermissions($permissions);

        $firstAdmin = User::firstOrCreate(
            ['email' => 'admin@fekas.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $secondAdmin = User::firstOrCreate(
            ['email' => 'admin@fekas001.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('admin@fekas@@1'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $firstAdmin->assignRole($adminRole);
        $secondAdmin->assignRole($adminRole);
    }
}