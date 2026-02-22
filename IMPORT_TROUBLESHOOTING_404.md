# Import Upload 404 Error - Troubleshooting Guide

## Problem
Import form shows 404 error when clicking "Upload & Import" button on `/admin/patients/import`

## Solution

### Step 1: Clear Laravel Caches

This is the most common cause of 404 errors after route changes:

```bash
# Option 1: Clear everything
php artisan optimize:clear

# Option 2: Just clear routes and cache
php artisan route:clear
php artisan cache:clear
```

Then rebuild cache:
```bash
php artisan route:cache
```

### Step 2: Verify Routes Are Registered

Check that all patient routes exist:

```bash
php artisan route:list | grep "admin/patients"
```

You should see:
```
GET|HEAD        admin/patients                    admin.patients.index
POST            admin/patients                    admin.patients.store
GET|HEAD        admin/patients/create             admin.patients.create
GET|HEAD        admin/patients/download-template  admin.patients.download-te…
POST            admin/patients/import             admin.patients.import ← MUST EXIST!
GET|HEAD        admin/patients/import-form        admin.patients.import-form
GET|HEAD        admin/patients/{patient}          admin.patients.show
PUT|PATCH       admin/patients/{patient}          admin.patients.update
DELETE          admin/patients/{patient}          admin.patients.destroy
GET|HEAD        admin/patients/{patient}/edit     admin.patients.edit
```

### Step 3: Verify Form Is Correct

The form should post to `{{ route('admin.patients.import') }}`:

Check file: `resources/views/admin/patients/import.blade.php` line 91

Should be:
```blade
<form action="{{ route('admin.patients.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    ...
</form>
```

### Step 4: Check Permissions

Ensure you have both required permissions:

```bash
# In Laravel Tinker
php artisan tinker
>>> $user = auth()->user()
>>> $user->hasPermission('patients_view')  # Should be true
>>> $user->hasPermission('patients_create') # Should be true
```

Both should return `true`. If not, add permissions to user's role.

### Step 5: Check Browser Console

Open browser DevTools (F12):
1. Go to "Network" tab
2. Try uploading again
3. Look at the POST request to `/admin/patients/import`
4. Check response status and error message

## Common Error Messages

### 404 Not Found
**Cause:** Route not registered or cached incorrectly
**Solution:** Run `php artisan optimize:clear`

### 405 Method Not Allowed
**Cause:** Wrong HTTP method or CSRF token missing
**Solution:** Check form has `method="POST"` and `@csrf` token

### 403 Forbidden
**Cause:** Missing permissions
**Solution:** Ensure user has `patients_view` and `patients_create` permissions

### 422 Unprocessable Entity
**Cause:** Validation error (e.g., missing file)
**Solution:** Select a valid Excel file before uploading

## Complete Cache Clear Script

Run this to ensure everything is fresh:

```bash
#!/bin/bash
cd /path/to/borderless

# Clear all caches
php artisan optimize:clear

# Rebuild caches
php artisan cache:clear
php artisan route:clear
php artisan config:cache
php artisan route:cache

# Verify routes
php artisan route:list | grep "admin/patients/import"

echo "✅ All caches cleared and rebuilt"
```

## Manual Cache Clear for Users

If you don't have CLI access, you can clear by deleting files:

1. **Routes cache:** Delete `bootstrap/cache/routes-v7.php`
2. **Config cache:** Delete `bootstrap/cache/config.php`
3. **View cache:** Delete all files in `storage/framework/views/`
4. **General cache:** Delete all files in `storage/framework/cache/`

Then visit the site - Laravel will rebuild caches automatically.

## Verify Fix

After applying solution:

1. Clear cache as shown above
2. Go to: `Admin Panel → Patient Management → Patients → Bulk Import`
3. Click "Download Sample Template" (should work)
4. Try uploading an empty file or test Excel file
5. If you see validation error about file content, that's SUCCESS (means route is reached)
6. If you see 404, apply troubleshooting steps again

## Quick Verification Commands

```bash
# Check route exists
php artisan route:list | grep "admin.patients.import"

# Check permission middleware
grep -n "permission:patients" routes/web.php

# Check controller method exists
grep -n "public function import" app/Http/Controllers/Admin/PatientController.php

# Check form uses correct route
grep -n "route('admin.patients.import')" resources/views/admin/patients/import.blade.php
```

All should return results showing everything is configured correctly.

## Still Not Working?

Try these additional steps:

1. **Check APP_DEBUG=true** in `.env` to see detailed errors
2. **Check browser console** for JavaScript errors
3. **Check network tab** to see actual request/response
4. **Check Laravel logs** in `storage/logs/laravel.log`
5. **Restart PHP** if using built-in server
6. **Check .htaccess** if using Apache (mod_rewrite must be enabled)
7. **Check permissions** on upload directory: `storage/uploads/`

## Reference

- Routes defined in: `routes/web.php` lines 91-99
- Controller: `app/Http/Controllers/Admin/PatientController.php`
- View: `resources/views/admin/patients/import.blade.php` line 91
- Required permissions: `patients_view`, `patients_create`

---

*If issues persist, ensure you've run `php artisan optimize:clear` and restarted your server.*
