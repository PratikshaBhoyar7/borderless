# Troubleshooting Guide - Patient Bulk Import

## Common Issues & Solutions

---

## 1. Routes Returning 404 Error

### Issue:
When trying to access `/admin/patients/import-form` or `/admin/patients/download-template`, you get a 404 error.

### Root Cause:
Custom routes were placed after the resource route, which catches all `/admin/patients/*` routes.

### Solution:
**Already Fixed in Latest Version!**

Routes are now in correct order:
```php
// Custom routes FIRST
Route::get('/patients/import-form', [PatientController::class, 'importForm']);
Route::post('/patients/import', [PatientController::class, 'import']);
Route::get('/patients/download-template', [PatientController::class, 'downloadTemplate']);

// Resource routes LAST (catches remaining routes)
Route::resource('patients', PatientController::class);
```

### How to Verify:
```bash
php artisan route:list | grep patients
```

Expected output should show:
- `GET|HEAD admin/patients/download-template`
- `POST admin/patients/import`
- `GET|HEAD admin/patients/import-form`

---

## 2. Sidebar Menu Not Showing Bulk Import

### Issue:
The "Bulk Import" option doesn't appear in the Patient Management dropdown menu.

### Root Cause:
Sidebar hasn't been updated with submenu structure.

### Solution:
Ensure you have the latest version of `resources/views/layouts/admin.blade.php`

Check the Patient Management section contains:
```blade
<li class="sidebar-submenu-container">
    <a href="#patientSubmenu" class="sidebar-submenu-toggle" data-bs-toggle="collapse">
        <i class="bi bi-heart-pulse"></i>
        <span>Patients</span>
        <i class="bi bi-chevron-down sidebar-submenu-chevron"></i>
    </a>
    <ul class="sidebar-submenu collapse show" id="patientSubmenu">
        <li><a href="{{ route('admin.patients.index') }}">...</a></li>
        <li><a href="{{ route('admin.patients.create') }}">...</a></li>
        <li><a href="{{ route('admin.patients.import-form') }}">...</a></li>
    </ul>
</li>
```

### How to Verify:
1. Inspect browser element
2. Look for `.sidebar-submenu` class
3. Should contain 3 links with routes

---

## 3. Import Form Not Loading or Styling Issues

### Issue:
Import form page loads but styling looks broken or incomplete.

### Root Cause:
CSS classes for submenu might not be loaded.

### Solution:
Verify CSS is loaded:
1. Open browser DevTools (F12)
2. Go to Network tab
3. Refresh page
4. Check that `admin.css` loads successfully (200 status)

If CSS doesn't load:
```bash
# Ensure CSS file exists
ls -la public/assets/css/admin.css

# Clear browser cache
# Ctrl+Shift+R (Windows/Linux) or Cmd+Shift+R (Mac)
```

### CSS Classes to Check:
```css
/* Should exist in admin.css */
.sidebar-submenu-container { }
.sidebar-submenu-toggle { }
.sidebar-submenu { }
.sidebar-submenu a { }
.sidebar-submenu-chevron { }
```

---

## 4. File Upload Not Working

### Issue:
File upload form doesn't accept files or upload fails.

### Root Cause:
Multiple possible causes - check step by step.

### Solution Steps:

**Step 1: Verify File Input**
```html
<!-- Check import form has proper enctype -->
<form enctype="multipart/form-data" method="POST">
```

**Step 2: Check File Size**
- Maximum file size: 10 MB
- Check file is under limit

**Step 3: Check File Type**
Supported types:
- `.xlsx` (Excel 2007+)
- `.xls` (Excel 97-2003)
- `.csv` (Comma-separated)

**Step 4: Server Logs**
Check Laravel error logs:
```bash
tail -f storage/logs/laravel.log
```

**Step 5: Verify Permissions**
Check storage directory is writable:
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

---

## 5. Duplicate Detection Not Working

### Issue:
Records that should be detected as duplicates are being imported anyway.

### Root Cause:
Duplicate check might be case-sensitive or aadhar might be empty.

### Solution:

**Check Duplicate Logic:**
Duplicates are detected when ALL three match:
1. **Patient Name** (exact case match)
2. **Date** (exact date match)
3. **Aadhar** (if provided)

**Example:**
```
Database: John Doe | 15/12/2025 | 123456789012
Import:   John Doe | 15/12/2025 | 123456789012
Result:   ✅ DUPLICATE (all three match)

Import:   john doe | 15/12/2025 | 123456789012
Result:   ❌ NOT DUPLICATE (name case differs)

Import:   John Doe | 20/12/2025 | 123456789012
Result:   ❌ NOT DUPLICATE (date differs)

Import:   John Doe | 15/12/2025 | 999999999999
Result:   ❌ NOT DUPLICATE (aadhar differs)
```

**Debug Duplicate Check:**
Check the import log shows duplicate entries:
```
Failed Records
Patient Name | Date | Aadhar | Reason
John Doe | 15/12/2025 | 123456789012 | Patient with same name, date, and aadhar already exists
```

---

## 6. Validation Errors on Import

### Issue:
Some or all records fail validation during import.

### Root Cause:
Data doesn't match validation rules.

### Solution:
Check each field against validation rules:

```
Field              | Rule                    | Example
patient_name       | Required, String        | "John Doe"
age                | Required, 0-150         | 28
sex                | Male, Female, Other     | "Male"
date               | DD/MM/YYYY format       | "15/12/2025"
campaign_type_id   | Valid ID in system      | 1
taluka_id          | Valid ID in system      | 5
mobile             | Exactly 10 digits       | "9876543210"
aadhar             | 12 digits if provided   | "123456789012"
height             | Positive number         | 170.5
weight             | Positive number         | 70.5
```

**Common Date Format Issues:**
- ❌ Wrong: 2025-12-15, 12/15/2025, 15-12-2025
- ✅ Correct: 15/12/2025

**Common Mobile Issues:**
- ❌ Wrong: 98765 43210, 98765-43210, +919876543210
- ✅ Correct: 9876543210

---

## 7. Import Success But Records Not Visible

### Issue:
Import reports success but new records don't appear in patient list.

### Root Cause:
Records might be filtered, paginated, or database issue.

### Solution:

**Step 1: Check Patient Count**
- Go to Patient List
- Check total records at bottom
- Should increase by number imported

**Step 2: Clear Filters**
- Remove any search filters
- Reset campaign type filter
- Show "All Campaigns"

**Step 3: Check Database Directly**
```bash
php artisan tinker
>>> Patient::latest()->limit(5)->get();
```

**Step 4: Check User Permissions**
- Logged-in user must have `patients_view` permission
- Check user's role has this permission

**Step 5: Check Pagination**
- Newly imported records appear at top (latest first)
- Might be on page 1
- Try page 2 or 3 if viewing old records

---

## 8. Download Template Not Working

### Issue:
Download template button doesn't download file.

### Root Cause:
Route not accessible or export class has issue.

### Solution:

**Step 1: Verify Route**
```bash
php artisan route:list | grep download-template
```

Should show:
```
GET|HEAD admin/patients/download-template admin.patients.download-template
```

**Step 2: Test Route Directly**
Visit in browser:
```
http://your-domain/admin/patients/download-template
```

Should trigger download automatically.

**Step 3: Check Exports Class**
Verify `app/Exports/PatientTemplateExport.php` exists and is properly formatted.

**Step 4: Check Browser Settings**
- Allow downloads from this site
- Check Downloads folder
- Check browser popup blockers

**Step 5: Server Logs**
```bash
tail -f storage/logs/laravel.log
```

Look for errors related to file generation.

---

## 9. Permission Denied Errors

### Issue:
Getting "Permission denied" or 403 Forbidden error.

### Root Cause:
User doesn't have required permissions.

### Solution:

**Required Permissions:**
- `patients_view` - To access import form
- `patients_create` - To create records during import
- `patients_edit` - To edit imported records (if needed)

**Check User Permissions:**
1. Go to Admin → Users
2. Click on user
3. Check their role
4. Go to Admin → Roles → Role Permissions
5. Verify role has required permissions

**Grant Permission:**
1. Go to Admin → Role Permissions
2. Select the user's role
3. Enable `patients_view` and `patients_create`
4. Save changes

**Test Permission:**
- Log out and log back in
- Try accessing import form
- Should work now

---

## 10. Laravel Excel Library Issues

### Issue:
Getting errors related to Excel/Maatwebsite.

### Root Cause:
Library might not be installed correctly.

### Solution:

**Step 1: Verify Installation**
```bash
composer show maatwebsite/excel
```

Should show installed version (v3.1.67).

**Step 2: Reinstall if Needed**
```bash
composer require maatwebsite/excel
```

**Step 3: Clear Cache**
```bash
php artisan cache:clear
php artisan config:clear
php artisan optimize:clear
```

**Step 4: Check Dependencies**
```bash
composer install
php artisan package:discover
```

**Step 5: Verify Config**
Check `config/excel.php` exists and is valid.

---

## 11. Browser Console Errors

### Issue:
JavaScript errors in browser console (F12).

### Root Cause:
Bootstrap or JavaScript issue.

### Solution:

**Check Console for These Errors:**

**Error:** "Bootstrap is not defined"
```
Solution: Ensure Bootstrap JS is loaded
Check: resources/views/layouts/admin.blade.php line 219
Should have: <script src="...bootstrap.bundle.min.js"></script>
```

**Error:** "data-bs-toggle not recognized"
```
Solution: Update Bootstrap to 5.0+
Current version should be 5.3.0
```

**Error:** "collapse is not a function"
```
Solution: Ensure Bootstrap JavaScript is loaded
Clear browser cache: Ctrl+Shift+R
```

---

## 12. Performance Issues

### Issue:
Import is slow or hangs.

### Root Cause:
Large file or server limitations.

### Solution:

**For Large Files:**
- Split file into smaller batches
- Import 1000-5000 records at a time
- Processing speed: ~100-200 records/second

**Server Settings:**
Check `php.ini`:
```ini
post_max_size = 10M          # Match max file size
upload_max_filesize = 10M    # Match max file size
max_execution_time = 300     # 5 minutes
memory_limit = 256M          # Increase if needed
```

**Browser Timeout:**
If upload times out:
1. Use smaller file
2. Close other tabs
3. Use wired internet instead of WiFi
4. Check server resources

---

## 13. Data Integrity Issues

### Issue:
Imported data looks wrong or corrupted.

### Root Cause:
File encoding or Excel formula interpretation.

### Solution:

**Excel Formulas:**
- Use plain text, not formulas
- Date field should be text formatted
- Numbers should not have currency symbols

**File Encoding:**
- Save as CSV with UTF-8 encoding
- Avoid Excel special characters
- Test with sample file first

**Data Validation:**
- Before import, spot check file in Excel
- Verify date formats are correct
- Verify numeric fields are valid

---

## 14. Session/Login Issues

### Issue:
Getting logged out during import or permission errors.

### Root Cause:
Session timeout or authentication issue.

### Solution:

**Check Session Configuration:**
```bash
cat config/session.php | grep lifetime
```

**Increase Session Timeout:**
In `.env`:
```
SESSION_LIFETIME=120  # 120 minutes
```

**Clear Sessions:**
```bash
php artisan session:flush
```

**Test Login:**
1. Log out completely
2. Clear browser cookies
3. Log back in
4. Try import again

---

## Quick Diagnostics Checklist

When something isn't working, go through this checklist:

- [ ] Routes registered correctly (`php artisan route:list`)
- [ ] CSS file loaded (DevTools → Network)
- [ ] JavaScript errors in console (DevTools → Console)
- [ ] File size under 10 MB
- [ ] File type supported (.xlsx, .xls, .csv)
- [ ] User has permissions (`patients_view`, `patients_create`)
- [ ] Date format is DD/MM/YYYY
- [ ] Mobile is 10 digits
- [ ] Aadhar is 12 digits (if provided)
- [ ] Laravel logs checked (`storage/logs/laravel.log`)
- [ ] Database accessible
- [ ] Server disk space available
- [ ] PHP extensions installed (mbstring, gd, etc.)

---

## Need More Help?

### Resources:
1. **Full Documentation:** See `PATIENT_IMPORT_GUIDE.md`
2. **Implementation Guide:** See `IMPLEMENTATION_SUMMARY.md`
3. **Sidebar Guide:** See `SIDEBAR_STRUCTURE.md`
4. **Laravel Excel Docs:** https://docs.laravel-excel.com/
5. **Bootstrap Docs:** https://getbootstrap.com/

### Support:
- Check Laravel error logs: `storage/logs/laravel.log`
- Enable debug mode in `.env`: `APP_DEBUG=true`
- Check database connection
- Review file permissions

---

## Still Having Issues?

If you've gone through all troubleshooting steps and still have issues:

1. **Provide Error Message:** Share the exact error message
2. **Check Logs:** Include relevant log entries from `storage/logs/laravel.log`
3. **Provide Context:**
   - What were you trying to do?
   - What happened instead?
   - What step failed?
4. **System Info:**
   - PHP version
   - Laravel version
   - Browser and version
   - Operating system

---
