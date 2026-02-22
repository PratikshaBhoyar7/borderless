<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define all permissions
        $permissions = [
            // Dashboard
            ['name' => 'view_dashboard', 'display_name' => 'View Dashboard', 'module' => 'dashboard', 'action' => 'read', 'description' => 'Can view admin dashboard'],

            // Users Module
            ['name' => 'users_view', 'display_name' => 'View Users', 'module' => 'users', 'action' => 'read', 'description' => 'Can view user list'],
            ['name' => 'users_create', 'display_name' => 'Create User', 'module' => 'users', 'action' => 'create', 'description' => 'Can create new users'],
            ['name' => 'users_edit', 'display_name' => 'Edit User', 'module' => 'users', 'action' => 'update', 'description' => 'Can edit user details'],
            ['name' => 'users_delete', 'display_name' => 'Delete User', 'module' => 'users', 'action' => 'delete', 'description' => 'Can delete users'],

            // Roles Module
            ['name' => 'roles_view', 'display_name' => 'View Roles', 'module' => 'roles', 'action' => 'read', 'description' => 'Can view roles list'],
            ['name' => 'roles_create', 'display_name' => 'Create Role', 'module' => 'roles', 'action' => 'create', 'description' => 'Can create new roles'],
            ['name' => 'roles_edit', 'display_name' => 'Edit Role', 'module' => 'roles', 'action' => 'update', 'description' => 'Can edit role details'],
            ['name' => 'roles_delete', 'display_name' => 'Delete Role', 'module' => 'roles', 'action' => 'delete', 'description' => 'Can delete roles'],

            // Activity Logs Module
            ['name' => 'activity_logs_view', 'display_name' => 'View Activity Logs', 'module' => 'activity_logs', 'action' => 'read', 'description' => 'Can view activity logs'],

            // Countries Module
            ['name' => 'countries_view', 'display_name' => 'View Countries', 'module' => 'countries', 'action' => 'read', 'description' => 'Can view countries list'],
            ['name' => 'countries_create', 'display_name' => 'Create Country', 'module' => 'countries', 'action' => 'create', 'description' => 'Can create new countries'],
            ['name' => 'countries_edit', 'display_name' => 'Edit Country', 'module' => 'countries', 'action' => 'update', 'description' => 'Can edit country details'],
            ['name' => 'countries_delete', 'display_name' => 'Delete Country', 'module' => 'countries', 'action' => 'delete', 'description' => 'Can delete countries'],

            // States Module
            ['name' => 'states_view', 'display_name' => 'View States', 'module' => 'states', 'action' => 'read', 'description' => 'Can view states list'],
            ['name' => 'states_create', 'display_name' => 'Create State', 'module' => 'states', 'action' => 'create', 'description' => 'Can create new states'],
            ['name' => 'states_edit', 'display_name' => 'Edit State', 'module' => 'states', 'action' => 'update', 'description' => 'Can edit state details'],
            ['name' => 'states_delete', 'display_name' => 'Delete State', 'module' => 'states', 'action' => 'delete', 'description' => 'Can delete states'],

            // Districts Module
            ['name' => 'districts_view', 'display_name' => 'View Districts', 'module' => 'districts', 'action' => 'read', 'description' => 'Can view districts list'],
            ['name' => 'districts_create', 'display_name' => 'Create District', 'module' => 'districts', 'action' => 'create', 'description' => 'Can create new districts'],
            ['name' => 'districts_edit', 'display_name' => 'Edit District', 'module' => 'districts', 'action' => 'update', 'description' => 'Can edit district details'],
            ['name' => 'districts_delete', 'display_name' => 'Delete District', 'module' => 'districts', 'action' => 'delete', 'description' => 'Can delete districts'],

            // Talukas Module
            ['name' => 'talukas_view', 'display_name' => 'View Talukas', 'module' => 'talukas', 'action' => 'read', 'description' => 'Can view talukas list'],
            ['name' => 'talukas_create', 'display_name' => 'Create Taluka', 'module' => 'talukas', 'action' => 'create', 'description' => 'Can create new talukas'],
            ['name' => 'talukas_edit', 'display_name' => 'Edit Taluka', 'module' => 'talukas', 'action' => 'update', 'description' => 'Can edit taluka details'],
            ['name' => 'talukas_delete', 'display_name' => 'Delete Taluka', 'module' => 'talukas', 'action' => 'delete', 'description' => 'Can delete talukas'],

            // Patients Module
            ['name' => 'patients_view', 'display_name' => 'View Patients', 'module' => 'patients', 'action' => 'read', 'description' => 'Can view patients list'],
            ['name' => 'patients_create', 'display_name' => 'Create Patient', 'module' => 'patients', 'action' => 'create', 'description' => 'Can create new patient records'],
            ['name' => 'patients_edit', 'display_name' => 'Edit Patient', 'module' => 'patients', 'action' => 'update', 'description' => 'Can edit patient details'],
            ['name' => 'patients_delete', 'display_name' => 'Delete Patient', 'module' => 'patients', 'action' => 'delete', 'description' => 'Can delete patient records'],

            // Analytics Module
            ['name' => 'analytics_view', 'display_name' => 'View Analytics Dashboard', 'module' => 'analytics', 'action' => 'read', 'description' => 'Can view patient health analytics and reports'],
            ['name' => 'analytics_export', 'display_name' => 'Export Analytics Data', 'module' => 'analytics', 'action' => 'export', 'description' => 'Can export analytics data and reports'],
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        // Assign permissions to roles
        $adminRole = Role::where('name', 'admin')->first();
        $dataEntryRole = Role::where('name', 'data_entry')->first();
        $userRole = Role::where('name', 'user')->first();

        if ($adminRole) {
            // Admin gets all permissions
            $allPermissions = Permission::pluck('id')->toArray();
            $adminRole->permissions()->sync($allPermissions);
        }

        if ($dataEntryRole) {
            // Data Entry gets permissions for patients and location views
            $dataEntryPermissions = Permission::whereIn('name', [
                'view_dashboard',
                'patients_view',
                'patients_create',
                'patients_edit',
                'patients_delete',
                'countries_view',
                'states_view',
                'districts_view',
                'talukas_view',
            ])->pluck('id')->toArray();
            $dataEntryRole->permissions()->sync($dataEntryPermissions);
        }

        if ($userRole) {
            // Regular user gets limited permissions
            $userPermissions = Permission::whereIn('name', [
                'view_dashboard',
            ])->pluck('id')->toArray();
            $userRole->permissions()->sync($userPermissions);
        }
    }
}
