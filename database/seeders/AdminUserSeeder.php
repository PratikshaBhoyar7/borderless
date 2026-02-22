<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'], // check by email
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );

        $adminRole = Role::where('name', 'admin')->first();

        // attach role only if not already attached
        if (!$admin->roles->contains($adminRole->id)) {
            $admin->roles()->attach($adminRole);
        }
    }
}