<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DataEntryUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a data entry user
        $user = User::create([
            'name' => 'Data Entry User',
            'email' => 'dataentry@borderless.local',
            'password' => Hash::make('password123'),
        ]);

        // Attach data_entry role
        $user->roles()->attach(\App\Models\Role::where('name', 'data_entry')->first());
    }
}
