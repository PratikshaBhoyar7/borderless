# Permission & Role-Based Access Control System

## Overview

The Borderless application implements a comprehensive role-based access control (RBAC) system with permissions. All modules are now protected through permissions which are assigned to roles, and roles are assigned to users.

## System Architecture

```
User → Role → Permission → Module Access
```

### Components

1. **Users** - Application users with assigned roles
2. **Roles** - Groupings of permissions (admin, data_entry, user)
3. **Permissions** - Granular access controls for specific actions (view, create, edit, delete)

## Available Roles

### 1. Admin Role
- **Description**: Administrator with full access
- **Permissions**: All 34 permissions across all modules
- **Access**: Complete control over all features

### 2. Data Entry Role
- **Description**: Patient data entry and management user
- **Permissions**: 10 specific permissions
  - View Dashboard
  - Patients (View, Create, Edit, Delete)
  - Campaign Types (View)
  - Countries, States, Districts, Talukas (View)
- **Use Case**: Medical staff entering patient data

### 3. User Role
- **Description**: Regular user with minimal access
- **Permissions**: Only dashboard view
- **Use Case**: Limited system access

## Permission Structure

### Permission Naming Convention

Permissions follow the pattern: `{module}_{action}`

**Examples:**
- `patients_view` - View patient list
- `patients_create` - Create new patient
- `users_edit` - Edit user details
- `campaign_types_delete` - Delete campaign type

### Module Permissions

Each module has 4 action-based permissions:

#### Users Module
- `users_view` - View user list
- `users_create` - Create new user
- `users_edit` - Edit user details
- `users_delete` - Delete users

#### Roles Module
- `roles_view` - View roles
- `roles_create` - Create new role
- `roles_edit` - Edit role details
- `roles_delete` - Delete roles

#### Activity Logs Module
- `activity_logs_view` - View activity logs

#### Location Modules
**Countries:**
- `countries_view`, `countries_create`, `countries_edit`, `countries_delete`

**States:**
- `states_view`, `states_create`, `states_edit`, `states_delete`

**Districts:**
- `districts_view`, `districts_create`, `districts_edit`, `districts_delete`

**Talukas:**
- `talukas_view`, `talukas_create`, `talukas_edit`, `talukas_delete`

#### Campaign Types Module
- `campaign_types_view` - View campaign types
- `campaign_types_create` - Create new campaign type
- `campaign_types_edit` - Edit campaign type
- `campaign_types_delete` - Delete campaign type

#### Patients Module
- `patients_view` - View patient list
- `patients_create` - Create new patient
- `patients_edit` - Edit patient details
- `patients_delete` - Delete patient records

## Using the Permission System in Code

### Check User Permissions

#### Single Permission
```php
// Check if user has specific permission
if (auth()->user()->hasPermission('patients_create')) {
    // Allow patient creation
}
```

#### Multiple Permissions (ANY)
```php
// Check if user has ANY of the permissions
if (auth()->user()->hasAnyPermission(['patients_view', 'patients_create'])) {
    // Allow access
}
```

#### Multiple Permissions (ALL)
```php
// Check if user has ALL permissions
if (auth()->user()->hasAllPermissions(['patients_create', 'patients_edit'])) {
    // Allow full patient management
}
```

#### Role Check
```php
// Check user role
if (auth()->user()->hasRole('data_entry')) {
    // Data entry user
}

if (auth()->user()->isAdmin()) {
    // Admin user
}
```

### Get User Permissions
```php
// Get all permission names for the user
$permissions = auth()->user()->getPermissionNames();
// Returns: ['patients_view', 'patients_create', 'patients_edit', ...]
```

### Role Permissions

#### Check Role Permission
```php
$role = Role::find($roleId);
if ($role->hasPermission('patients_create')) {
    // Role has permission
}
```

#### Get Role Permissions
```php
$permissions = $role->getPermissionNames();
// Returns: ['patients_view', 'patients_create', ...]
```

## Protecting Routes

### Using Permission Middleware

Protect routes with specific permissions:

```php
// Single permission
Route::post('/patients', [PatientController::class, 'store'])
    ->middleware('permission:patients_create');

// Multiple permissions (user needs at least one)
Route::get('/patients', [PatientController::class, 'index'])
    ->middleware('permission:patients_view,patients_create');

// Multiple middlewares
Route::get('/patients/{patient}', [PatientController::class, 'show'])
    ->middleware(['auth', 'permission:patients_view']);
```

### Admin-Only Routes
```php
// Only admin users can access
Route::middleware('admin')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
});
```

### Data Entry Routes
```php
// Admin and data_entry users can access
Route::middleware('data_entry')->group(function () {
    Route::resource('patients', PatientController::class);
});
```

## Protecting Views

### Show Content Based on Permissions

```blade
<!-- Show only if user has permission -->
@if(auth()->user()->hasPermission('patients_create'))
    <a href="{{ route('admin.patients.create') }}" class="btn btn-primary">
        Add New Patient
    </a>
@endif

<!-- Show for multiple permissions -->
@if(auth()->user()->hasAnyPermission(['users_edit', 'users_delete']))
    <div class="admin-actions">
        <!-- Admin actions -->
    </div>
@endif
```

### Hide Sidebar Menus Based on Permissions
```blade
<!-- Admin-only menu -->
@if(auth()->user()->hasPermission('users_view'))
    <li>
        <a href="{{ route('admin.users.index') }}">
            <i class="bi bi-people-fill"></i>
            <span>Users</span>
        </a>
    </li>
@endif

<!-- Data Entry menu -->
@if(auth()->user()->hasPermission('patients_view'))
    <li>
        <a href="{{ route('admin.patients.index') }}">
            <i class="bi bi-heart-pulse"></i>
            <span>Patients</span>
        </a>
    </li>
@endif
```

## Admin Behavior

**Special Note**: Admin users have automatic access to all permissions. The system checks:

```php
// Admin has all permissions automatically
if (auth()->user()->isAdmin()) {
    return true; // Can do anything
}
```

This means:
- Admin doesn't need explicit permission assignments
- Admin sees all menu options
- Admin can access all routes
- Admin bypasses all permission checks

## Managing Permissions

### Create New Permission

```php
use App\Models\Permission;

Permission::create([
    'name' => 'reports_view',
    'display_name' => 'View Reports',
    'module' => 'reports',
    'action' => 'read',
    'description' => 'Can view system reports',
    'is_active' => true,
]);
```

### Assign Permission to Role

```php
$role = Role::find($roleId);
$permission = Permission::where('name', 'patients_create')->first();

// Add single permission
$role->permissions()->attach($permission);

// Add multiple permissions
$role->permissions()->sync([
    $permission1->id,
    $permission2->id,
]);
```

### Remove Permission from Role

```php
$role->permissions()->detach($permission);
$role->permissions()->detach([$permission1, $permission2]);
```

### Get Role Permissions

```php
$permissions = $role->permissions()->get();
// Returns all permissions assigned to role

$permissionNames = $role->getPermissionNames();
// Returns: ['patients_view', 'patients_create', ...]
```

## Current Permission Distribution

### Admin Role (34 permissions)
- All system permissions
- View and manage: Users, Roles, Activity Logs
- Full location management: Countries, States, Districts, Talukas
- Full campaign management: Campaign Types
- Full patient management: Patients

### Data Entry Role (10 permissions)
- `view_dashboard`
- `patients_view`, `patients_create`, `patients_edit`, `patients_delete`
- `campaign_types_view`
- `countries_view`, `states_view`, `districts_view`, `talukas_view`

**Cannot Access:**
- User management
- Role management
- Activity logs
- Location creation/editing/deletion

### User Role (1 permission)
- `view_dashboard` only

## Database Tables

### permissions
- `id` (Primary Key)
- `name` (Unique) - e.g., 'patients_create'
- `display_name` - e.g., 'Create Patient'
- `module` - e.g., 'patients'
- `action` - e.g., 'create'
- `description` - Detailed description
- `is_active` - Boolean flag
- `created_at`, `updated_at`

### role_permission
- `id` (Primary Key)
- `role_id` (Foreign Key)
- `permission_id` (Foreign Key)
- Unique constraint on (role_id, permission_id)
- `created_at`, `updated_at`

## Best Practices

1. **Granular Permissions**: Use specific permissions (e.g., `patients_create`) not broad ones
2. **Consistent Naming**: Follow `{module}_{action}` pattern
3. **Admin Override**: Remember admin users bypass all checks
4. **View Protection**: Always check permissions in views for UX consistency
5. **Route Protection**: Use middleware for actual route protection
6. **Permission Documentation**: Document all permissions in your application
7. **Regular Audits**: Periodically review role-permission assignments

## Troubleshooting

### User Can't Access a Feature

1. Check user has correct role: `user->roles`
2. Check role has permission: `role->permissions()`
3. Check permission is active: `permission->is_active`
4. Verify route middleware: Check route definition
5. Verify view conditions: Check `@if` statements

### Permission Not Working

```php
// Debug: Check permission exists
Permission::where('name', 'patients_create')->first();

// Debug: Check user's roles
auth()->user()->roles()->get();

// Debug: Check role's permissions
auth()->user()->roles()->with('permissions')->get();

// Debug: Test permission check
auth()->user()->hasPermission('patients_create');
```

### Admin User Has Issues

Clear application cache:
```bash
php artisan cache:clear
php artisan config:clear
```

## Migration to Permission System

### For Existing Routes

Update existing routes that use simple role middleware:

**Before:**
```php
Route::middleware('data_entry')->group(function () {
    Route::resource('patients', PatientController::class);
});
```

**After:**
```php
Route::middleware('permission:patients_view,patients_create,patients_edit,patients_delete')->group(function () {
    Route::resource('patients', PatientController::class);
});
```

Or use combined middleware:
```php
Route::middleware(['auth', 'data_entry'])->group(function () {
    Route::resource('patients', PatientController::class);
});
```

## Next Steps

1. Verify permission assignments in your application
2. Test access with different user roles
3. Customize permissions as needed for your business logic
4. Document custom permissions
5. Train users on the RBAC system

---

**Last Updated**: December 20, 2025
**System Version**: 1.0
