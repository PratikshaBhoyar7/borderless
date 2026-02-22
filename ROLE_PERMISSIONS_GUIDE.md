# Role Permission Management Guide

## Overview

The Role Permission Management module is a comprehensive admin panel feature that allows administrators to visually manage role permissions in the Borderless application. Instead of managing permissions through code, admins can now use the UI to grant or revoke permissions for each role.

## Accessing the Module

### Navigation Steps
1. Log in as an admin user (`admin@admin.com`)
2. Click on the Admin Dashboard
3. In the left sidebar, locate the **"Roles"** section
4. Click on **"Role Permissions"** menu item
5. You'll see the Role & Permissions Management page

### Direct URL
```
/admin/role-permissions
```

## Features

### 1. Role Overview Dashboard

The main page displays an overview of all roles in your system:

**What You See:**
- **Role Cards** - One card for each role (Admin, Data Entry, User)
- **Permission Count** - Badge showing how many permissions each role has
- **User Count** - Number of users assigned to each role
- **Permission Preview** - First 3 permissions with a "+X more" indicator
- **Action Buttons** - "Manage" and "Reset" buttons for quick actions
- **Statistics** - Cards showing total roles, permissions, modules, and users

**What You Can Do:**
- Click **"Manage"** to edit that role's permissions
- Click **"Reset"** to restore role to default permissions
- View module-based permission breakdown

### 2. Permission Editor

When you click "Manage" on a role, you enter the detailed permission editor:

**Layout:**
- **Role Information** - Shows role name, description, number of users
- **Statistics** - Current permissions count and coverage percentage
- **Modules** - Permissions organized by module (Users, Roles, Patients, etc.)
- **Checkboxes** - Check/uncheck to grant/revoke permissions
- **Status Indicators** - Shows if permissions are active or inactive
- **Action Buttons** - Save, Reset, Cancel

**Permission Coverage Progress Bar:**
- Shows what percentage of available permissions this role has
- Green (100%) - Role has all permissions
- Blue (50-99%) - Role has majority of permissions
- Yellow (<50%) - Role has limited permissions

### 3. Module-Based Organization

Permissions are grouped by module:

```
Dashboard
├─ view_dashboard

Users
├─ users_view
├─ users_create
├─ users_edit
└─ users_delete

Roles
├─ roles_view
├─ roles_create
├─ roles_edit
└─ roles_delete

Patients
├─ patients_view
├─ patients_create
├─ patients_edit
└─ patients_delete

Countries
├─ countries_view
├─ countries_create
├─ countries_edit
└─ countries_delete

(And more modules...)
```

## How to Manage Permissions

### Edit a Role's Permissions

1. **Go to Role Permissions** - Click on "Role Permissions" in sidebar
2. **Select Role** - Click "Manage" button on the role you want to edit
3. **View Permissions** - See all available permissions organized by module
4. **Select Permissions** - Check boxes to grant permissions
5. **Deselect Permissions** - Uncheck boxes to revoke permissions
6. **Save Changes** - Click "Save Permissions" button
7. **Confirm** - You'll see a success message

### Reset a Role to Defaults

If you want to restore a role to its default permissions:

1. **Go to Role Permissions** - From the overview page
2. **Click Reset** - Click the "Reset to Default" button on the role card
   OR
3. **While Editing** - Click "Reset to Default" button in the editor
4. **Confirm** - A confirmation dialog will appear
5. **Done** - Role permissions are reset to defaults

**Default Permissions:**

Admin Role:
- All 34 permissions across all modules

Data Entry Role:
- patients_view, patients_create, patients_edit, patients_delete
- countries_view, states_view, districts_view, talukas_view
- campaign_types_view
- view_dashboard

User Role:
- view_dashboard only

### Bulk Update Multiple Roles

For advanced users, you can update permissions for multiple roles at once:

**Via API (POST request):**
```
POST /admin/role-permissions/bulk-update

Body:
{
  "roles": [1, 2],           // Role IDs
  "permissions": [1, 5, 10]  // Permission IDs
}
```

## Understanding Permissions

### Permission Naming Convention

All permissions follow the pattern: `{module}_{action}`

**Examples:**
- `patients_view` - View patients (read)
- `patients_create` - Create new patients
- `patients_edit` - Edit existing patients
- `patients_delete` - Delete patients
- `users_view` - View users
- `roles_view` - View roles

### Actions

- **view/read** - View/read records
- **create** - Create new records
- **edit/update** - Modify existing records
- **delete** - Remove records

## Default Roles

### 1. Admin Role

**Permissions:** All 34 permissions

**Can Do:**
- Manage users and roles
- Access activity logs
- Create/edit locations
- Manage campaigns
- Full patient management
- Everything

**Users:** System administrators

---

### 2. Data Entry Role

**Permissions:** 10 permissions
- Patients (view, create, edit, delete)
- Locations (view only - for form dropdowns)
- Campaign Types (view only)
- Dashboard (view only)

**Can Do:**
- Create and manage patient records
- View locations and campaigns for forms
- Access dashboard
- View patients
- Cannot manage users, roles, or locations

**Users:** Medical staff, data entry clerks

---

### 3. User Role

**Permissions:** 1 permission (view_dashboard)

**Can Do:**
- View dashboard only
- Limited system access

**Users:** Basic system users

## Managing Permissions in Code

### Check User Permissions

In your PHP code, you can check if a user has permission:

```php
// Single permission
if (auth()->user()->hasPermission('patients_create')) {
    // User can create patients
}

// Multiple permissions (ANY)
if (auth()->user()->hasAnyPermission(['patients_view', 'patients_create'])) {
    // User has at least one
}

// Multiple permissions (ALL)
if (auth()->user()->hasAllPermissions(['patients_create', 'patients_edit'])) {
    // User has all of them
}

// Get all permissions
$permissions = auth()->user()->getPermissionNames();
// Returns: ['patients_view', 'patients_create', ...]
```

### Check Role Permissions

```php
$role = Role::find($roleId);

if ($role->hasPermission('users_create')) {
    // Role can create users
}

$permissions = $role->getPermissionNames();
// Returns: ['users_view', 'users_create', ...]
```

### In Blade Templates

```blade
@if(auth()->user()->hasPermission('patients_create'))
    <a href="{{ route('admin.patients.create') }}" class="btn btn-primary">
        Add Patient
    </a>
@endif

@if(auth()->user()->hasAnyPermission(['users_edit', 'users_delete']))
    <div class="admin-actions">
        <!-- Admin features -->
    </div>
@endif
```

## Protecting Routes

All routes in the system are protected with permission middleware:

```php
Route::post('/patients', [PatientController::class, 'store'])
    ->middleware('permission:patients_create');
```

Users must have the specified permission to access the route, otherwise they get a 403 error.

## Activity Logging

All permission changes are logged to the activity log:

**Events Logged:**
- "Updated role permissions" - When permissions are saved
- "Reset role permissions to default" - When reset is used
- "Bulk updated role permissions" - When bulk update is performed

View logs in: Admin → Activity Logs

## Best Practices

### 1. Use Meaningful Permission Groups
- Don't spread related permissions across roles
- Group similar permissions together
- Admin should have all permissions
- Data Entry should have focused permissions

### 2. Regular Audits
- Review role permissions periodically
- Check if users have appropriate roles
- Audit activity logs for changes
- Remove unnecessary permissions

### 3. Document Custom Permissions
- If you add custom permissions, document them
- Update this guide with new modules
- Explain why the permission exists

### 4. Use Sensible Defaults
- Keep the default role permissions sensible
- Reset to defaults if role gets messy
- Use reset instead of manually unchecking many

### 5. Consistent Naming
- Always use `{module}_{action}` format
- Use lowercase with underscores
- Be consistent across modules

## Troubleshooting

### Problem: Can't access Role Permissions

**Cause:** You don't have the `roles_view` permission

**Solution:**
- Log in as admin user
- Or have your admin grant you `roles_view` permission

### Problem: Changes not saving

**Cause:** Database error or permission validation failed

**Solution:**
- Check browser console for errors
- Verify permission IDs are correct
- Try saving again

### Problem: Permission checkbox won't check

**Cause:** Permission is inactive or there's a validation error

**Solution:**
- Check if permission is marked as inactive
- Refresh the page
- Try again

## FAQ

**Q: Can I create new permissions?**
A: Permissions are managed in code via PermissionSeeder. Contact your developer to add new permissions.

**Q: Will resetting a role affect users with that role?**
A: Yes, resetting a role's permissions immediately affects all users with that role. They'll lose access to revoked features.

**Q: Can I edit the Admin role permissions?**
A: Yes, but it's not recommended. Admin should always have all permissions.

**Q: What happens if I deselect all permissions for a role?**
A: Users with that role will only be able to access the login page and logout.

**Q: Is there an undo option?**
A: Yes, use the "Reset to Default" button to restore original permissions.

**Q: Can I see who made permission changes?**
A: Yes, check Activity Logs. It shows who changed permissions and when.

## Advanced Features

### AJAX Endpoints

For developers, there are AJAX endpoints available:

```
GET /admin/role-permissions/api/module/{module}
- Get all permissions for a module
- Returns JSON array of permissions

GET /admin/role-permissions/api/role/{role}
- Get current permissions for a role
- Returns JSON array of permission IDs
```

### API Response Format

```json
{
  "permissions": [
    {
      "id": 1,
      "name": "patients_view",
      "display_name": "View Patients",
      "module": "patients",
      "action": "view",
      "is_active": true
    }
  ]
}
```

## Summary

The Role Permission Management module provides:

✅ **Visual Interface** - Manage permissions without code
✅ **Easy to Use** - Simple checkbox interface
✅ **Organized** - Permissions grouped by module
✅ **Secure** - All changes logged and authenticated
✅ **Flexible** - Grant/revoke permissions instantly
✅ **Auditable** - Activity logging for compliance
✅ **Powerful** - Control every aspect of your system

Use this module to manage access control for your Borderless application effectively!

---

**Last Updated:** December 20, 2025
**Version:** 1.0
