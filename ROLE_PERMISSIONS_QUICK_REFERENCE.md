# Role & Permissions Management - Quick Reference Guide

## Quick Access

### URLs
```
Admin Panel: /admin
Dashboard:   /admin/dashboard
Roles:       /admin/role-permissions
Edit Role:   /admin/role-permissions/{role-id}
```

### Test Credentials
```
Admin User:
  Email:    admin@admin.com
  Password: password
  Role:     admin

Data Entry User:
  Email:    dataentry@example.com
  Password: password
  Role:     data_entry

Regular User:
  Email:    user@example.com
  Password: password
  Role:     user
```

---

## Roles Overview

### Admin Role
```
ID: 1
Permissions: 34 (ALL)
Users: 1
Capabilities:
  ✓ Manage all users and roles
  ✓ Access all modules
  ✓ View activity logs
  ✓ Manage locations
  ✓ Manage campaigns
  ✓ Manage patients
```

### Data Entry Role
```
ID: 2
Permissions: 10
Users: 1
Capabilities:
  ✓ Create/edit/view/delete patients
  ✓ View locations (for form dropdowns)
  ✓ View campaign types (for patient form)
  ✓ View dashboard
  ✗ Cannot manage users, roles, or locations
```

### User Role
```
ID: 3
Permissions: 1
Users: 0
Capabilities:
  ✓ View dashboard only
  ✗ No other access
```

---

## Permissions Quick List

### Dashboard (1)
- `view_dashboard` - View main dashboard

### Users (4)
- `users_view` - View users
- `users_create` - Create new users
- `users_edit` - Edit existing users
- `users_delete` - Delete users

### Roles (4)
- `roles_view` - View roles (REQUIRED for role permissions access)
- `roles_create` - Create new roles
- `roles_edit` - Edit existing roles
- `roles_delete` - Delete roles

### Activity Logs (1)
- `activity_logs_view` - View activity logs

### Locations (12)
- `countries_view`, `countries_create`, `countries_edit`, `countries_delete`
- `states_view`, `states_create`, `states_edit`, `states_delete`
- `districts_view`, `districts_create`, `districts_edit`, `districts_delete`
- `talukas_view`, `talukas_create`, `talukas_edit`, `talukas_delete`

### Campaign Types (4)
- `campaign_types_view` - View campaign types
- `campaign_types_create` - Create new types
- `campaign_types_edit` - Edit existing types
- `campaign_types_delete` - Delete types

### Patients (4)
- `patients_view` - View patients
- `patients_create` - Create new patients
- `patients_edit` - Edit existing patients
- `patients_delete` - Delete patients

---

## Code Usage

### Check Permissions in Controllers
```php
// Single permission
if (auth()->user()->hasPermission('patients_create')) {
    // Allow action
}

// Multiple permissions (ANY)
if (auth()->user()->hasAnyPermission(['patients_view', 'patients_create'])) {
    // User has at least one
}

// Multiple permissions (ALL)
if (auth()->user()->hasAllPermissions(['patients_create', 'patients_edit'])) {
    // User has all of them
}

// Get all permission names
$permissions = auth()->user()->getPermissionNames();
// Returns: ['patients_view', 'patients_create', ...]
```

### Check Permissions in Routes
```php
Route::post('/patients', [PatientController::class, 'store'])
    ->middleware('permission:patients_create');

Route::get('/patients/{patient}', [PatientController::class, 'show'])
    ->middleware('permission:patients_view');
```

### Check Permissions in Blade Templates
```blade
@if(auth()->user()->hasPermission('patients_create'))
    <a href="{{ route('admin.patients.create') }}" class="btn btn-primary">
        Add Patient
    </a>
@endif

@if(auth()->user()->hasAnyPermission(['patients_edit', 'patients_delete']))
    <div class="admin-actions">
        <!-- Admin features -->
    </div>
@endif
```

### Check Role Permissions
```php
$role = Role::find(1);

if ($role->hasPermission('users_create')) {
    // Role can create users
}

$permissionNames = $role->getPermissionNames();
```

---

## Database Schema Summary

### Tables
```
roles (id, name, description, timestamps)
permissions (id, name, display_name, module, action, is_active, timestamps)
role_permission (role_id, permission_id)
users (id, name, email, password, timestamps)
model_has_roles (model_id, role_id, model_type)
model_has_permissions (model_id, permission_id, model_type)
activity_log (id, log_name, description, subject_id, causer_id, properties, timestamps)
```

### Key Fields
```
Role:
  - id: Primary key
  - name: Unique identifier (admin, data_entry, user)
  - description: Human-readable description

Permission:
  - id: Primary key
  - name: Unique identifier (patients_create)
  - display_name: Human-readable name
  - module: Grouping (patients, users, roles, etc.)
  - action: CRUD action (view, create, edit, delete)
  - is_active: Boolean flag
```

---

## API Endpoints

### Admin Routes (All Protected with `roles_view` Permission)
```
GET    /admin/role-permissions
       Display all roles and permissions

GET    /admin/role-permissions/{role}
       Display role permission editor

PUT    /admin/role-permissions/{role}
       Update role permissions
       Body: { permissions: [1, 2, 3, ...] }

PUT    /admin/role-permissions/{role}/reset
       Reset role to default permissions

POST   /admin/role-permissions/bulk-update
       Update multiple roles
       Body: { roles: [1, 2], permissions: [1, 5, 10] }

GET    /admin/role-permissions/api/module/{module}
       Get permissions for module (AJAX)
       Response: [{ id, name, display_name, ... }]

GET    /admin/role-permissions/api/role/{role}
       Get role's current permissions (AJAX)
       Response: { permissions: [1, 2, 3, ...] }
```

---

## Common Tasks

### Task: Add New Permission to Database
```php
// In console or migration
$permission = Permission::create([
    'name' => 'reports_export',
    'display_name' => 'Export Reports',
    'module' => 'reports',
    'action' => 'export',
    'is_active' => true,
]);

// Then assign to roles via UI or code:
$role = Role::find(1); // Admin
$role->permissions()->attach($permission->id);
```

### Task: Add New Role
```php
$role = Role::create([
    'name' => 'manager',
    'description' => 'Department Manager',
]);

// Assign permissions
$permissions = Permission::whereIn('name', [
    'patients_view',
    'patients_create',
    'patients_edit',
])->pluck('id');

$role->permissions()->attach($permissions);
```

### Task: Add User to Role
```php
$user = User::find(1);
$role = Role::find(2); // Data Entry role

$user->assignRole($role);
// Or: $user->assignRole('data_entry');
```

### Task: Remove Permission from Role
```php
$role = Role::find(1);
$role->revokePermissionTo('patients_delete');
// Or remove specific ID:
$role->permissions()->detach(15);
```

### Task: Check Audit Log
```php
// View recent permission changes
$logs = Activity::where('event', 'like', '%permission%')
    ->latest()
    ->limit(10)
    ->get();

foreach($logs as $log) {
    echo $log->description; // "Updated role permissions"
    echo $log->causer->email; // Admin who made change
    echo $log->created_at; // When it happened
}
```

---

## File Structure

### Key Files
```
app/
  Models/
    Role.php              - Role model with relationships
    Permission.php        - Permission model
    User.php             - User model with permission methods

  Http/
    Controllers/Admin/
      RolePermissionController.php - Main controller
    Middleware/
      CheckPermission.php - Route middleware

database/
  migrations/
    *_create_roles_table.php
    *_create_permissions_table.php
    *_create_role_permission_table.php
  seeders/
    RoleSeeder.php
    PermissionSeeder.php

resources/views/admin/role-permissions/
  index.blade.php         - Dashboard view
  show.blade.php         - Editor view

routes/
  web.php                 - Role permission routes

config/
  permission.php          - Permission configuration (if using package)
```

---

## Testing

### Manual Testing Checklist
```
UI Display:
  [ ] Index page loads with 3 role cards
  [ ] Cards show correct permission counts
  [ ] Statistics show correct totals
  [ ] Show page loads with permission editor
  [ ] Checkboxes are visible and clickable

Functionality:
  [ ] Can check/uncheck permissions
  [ ] Can save permission changes
  [ ] Can reset to defaults
  [ ] Can click "Manage" button
  [ ] Can click "Reset" button
  [ ] Can click "Back" button
  [ ] Success messages appear

Data:
  [ ] Admin shows 34 permissions
  [ ] Data Entry shows 10 permissions
  [ ] User shows 1 permission
  [ ] All 10 modules displayed
  [ ] Permission coverage shows correct %

Performance:
  [ ] Page loads in < 2 seconds
  [ ] No console errors
  [ ] Responsive on mobile
  [ ] CSS loads correctly
  [ ] Icons display correctly
```

### Database Verification
```bash
# Check roles exist
php artisan tinker
>>> Role::all();

# Check permissions exist
>>> Permission::count(); // Should be 34

# Check role permissions mapped
>>> Role::find(1)->permissions()->count(); // Admin: 34

# Check user has role
>>> User::find(1)->roles()->first()->name; // admin
```

---

## Troubleshooting Quick Fixes

### Blank Page / No Data
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Check database connection
php artisan db:show
```

### CSS Not Loading / Styling Missing
```
Check:
1. Bootstrap CDN is accessible
2. Admin layout extends properly
3. Browser console for CSS errors
4. Asset URL in browser matches file location
```

### Permissions Not Saving
```
Check:
1. Form method is POST with _method=PUT
2. CSRF token in form (@csrf)
3. Permission IDs are valid in database
4. User has roles_view permission
5. Server logs for validation errors
```

### Permission Checkboxes Disabled
```
Reason: Permission is marked as inactive (is_active = false)
Fix: Set is_active = true in permissions table
    UPDATE permissions SET is_active = 1 WHERE id = X;
```

---

## Key Models & Methods

### Role Model
```php
class Role extends Model {
    public function permissions() // HasMany relationship
    public function users() // BelongsToMany relationship
    public function hasPermission($permission) // Check if has permission
    public function getPermissionNames() // Get all permission names
}
```

### User Model
```php
class User extends Model {
    public function hasPermission($permission) // Check permission
    public function hasAnyPermission($permissions) // Check any
    public function hasAllPermissions($permissions) // Check all
    public function getPermissionNames() // Get all permission names
    public function assignRole($role) // Assign role
    public function revokeRole($role) // Remove role
}
```

### Permission Model
```php
class Permission extends Model {
    // Fillable: name, display_name, module, action, is_active
    public function roles() // BelongsToMany relationship
    public function scopeActive() // Query scope for active permissions
}
```

---

## Security Notes

### Protected Access
- Role permissions only accessible to users with `roles_view` permission
- All database queries use Eloquent ORM (SQL injection protection)
- CSRF tokens on all forms
- Activity logging for audit trail
- Input validation on all requests

### Best Practices
- Always check permissions in controllers before actions
- Use middleware to protect routes
- Log important actions
- Regularly audit permission assignments
- Keep admin role secure
- Don't grant unnecessary permissions

---

## Configuration

### Permission Middleware
```php
// Route protection
'permission:permission_name'
'permission:permission1,permission2'

// Works with OR logic (any of the permissions)
// Multiple instances use AND logic (all required)
```

### Default Permissions
```php
// Set in RolePermissionController::reset()

Admin:
  All 34 permissions

Data Entry:
  patients_view, patients_create, patients_edit, patients_delete
  countries_view, states_view, districts_view, talukas_view
  campaign_types_view
  view_dashboard

User:
  view_dashboard
```

---

## Performance Tips

1. **Use Eager Loading**
   ```php
   $roles = Role::with('permissions', 'users')->get();
   ```

2. **Cache Permission Data**
   ```php
   $permissions = Cache::remember('permissions', 3600, function() {
       return Permission::all()->groupBy('module');
   });
   ```

3. **Index Database Columns**
   - Already done on: role_id, permission_id

4. **Paginate Large Lists**
   ```php
   $roles = Role::paginate(15);
   ```

---

## Summary

The Role & Permissions Management system provides:
- ✅ 3 pre-configured roles
- ✅ 34 granular permissions across 10 modules
- ✅ Visual permission management interface
- ✅ Activity logging and audit trail
- ✅ Easy-to-use API for code-based checks
- ✅ Role-based route protection
- ✅ Production-ready implementation

**For Questions or Issues**: Check ROLE_PERMISSIONS_GUIDE.md or ROLE_PERMISSIONS_IMPLEMENTATION.md

---

**Last Updated**: December 20, 2025
**Status**: ✅ Complete & Ready to Use
