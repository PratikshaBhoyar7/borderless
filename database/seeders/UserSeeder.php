<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // Assign Admin Role
        $adminRole = \App\Models\Role::where('name', 'Admin')->first();
        if ($adminRole && !$admin->roles->contains($adminRole)) {
            $admin->roles()->attach($adminRole);
        }

        // Create Data Entry User
        $dataEntry = \App\Models\User::firstOrCreate(
            ['email' => 'dataentry@example.com'],
            [
                'name' => 'Data Entry User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // Assign Data Entry Role
        $dataEntryRole = \App\Models\Role::where('name', 'data_entry')->first();
        if ($dataEntryRole && !$dataEntry->roles->contains($dataEntryRole)) {
            $dataEntry->roles()->attach($dataEntryRole);
        }

        // Create Viewer User
        $viewer = \App\Models\User::firstOrCreate(
            ['email' => 'viewer@example.com'],
            [
                'name' => 'Viewer User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // Assign User Role
        $userRole = \App\Models\Role::where('name', 'User')->first();
        if ($userRole && !$viewer->roles->contains($userRole)) {
            $viewer->roles()->attach($userRole);
        }

        $this->command->info('Users created successfully!');
        $this->command->info('Admin: admin@admin.com / password');
        $this->command->info('Data Entry: dataentry@example.com / password');
        $this->command->info('Viewer: viewer@example.com / password');
    }
}
