<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class AnalyticsRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Analytics Viewer role
        $role = Role::updateOrCreate(
            ['name' => 'analytics_viewer'],
            [
                'name' => 'analytics_viewer',
                'description' => 'Can view analytics dashboard and reports (read-only access to patient data)',
            ]
        );

        // Assign permissions to Analytics Viewer role
        $permissions = Permission::whereIn('name', [
            'view_dashboard',
            'analytics_view',
            'analytics_export',
            'patients_view', // Read-only patient access
            'countries_view',
            'states_view',
            'districts_view',
            'talukas_view',
        ])->pluck('id')->toArray();

        $role->permissions()->sync($permissions);

        // Ensure admin role has analytics permissions
        $adminRole = Role::where('name', Role::ADMIN)->first();
        if ($adminRole) {
            $analyticsPermissions = Permission::whereIn('name', ['analytics_view', 'analytics_export'])
                ->pluck('id')
                ->toArray();
            $adminRole->permissions()->syncWithoutDetaching($analyticsPermissions);
        }
    }
}
