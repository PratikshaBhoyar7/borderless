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
    $user = User::updateOrCreate(
        ['email' => 'dataentry@borderless.local'],
        [
            'name' => 'Data Entry User',
            'password' => bcrypt('password'),
        ]
    );

    $role = \App\Models\Role::where('name', 'data_entry')->first();

    if ($role) {
        $user->roles()->syncWithoutDetaching([$role->id]);
    }
}

        
}

