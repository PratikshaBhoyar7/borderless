# Quick Reference Card - Patient Bulk Import

## 🎯 TL;DR - Get Started in 60 Seconds

### 1. Access Import
```
Admin Panel → Patient Management → Patients ▼ → Bulk Import
```

### 2. Download Template
```
Click "Download Sample Template"
Opens: patient_template.xlsx
```

### 3. Fill & Upload
```
Row 1: Headers (pre-filled, don't change)
Row 2: Example (delete this)
Row 3+: Your data
Save → Upload file
```

### 4. Done!
```
See success count & any failures
Click "View All Patients" to see results
```

---

## 📋 Essential Data Format

### Date Format (CRITICAL!)
```
✅ Correct: 15/12/2025 (DD/MM/YYYY)
❌ Wrong:   2025-12-15, 12/15/2025, 15-12-2025
```

### Mobile Format
```
✅ Correct: 9876543210 (10 digits, no spaces)
❌ Wrong:   98 765 43210, 98-765-43210
```

### Aadhar Format (if used)
```
✅ Correct: 123456789012 (12 digits, no spaces)
❌ Wrong:   1234-5678-9012
```

### Lab Tests
```
✅ Correct: Blood Test, Urine Test (comma-separated)
❌ Wrong:   Blood Test; Urine Test
```

---

## 🔍 Duplicate Detection

### What Gets Checked?
- Patient Name (exact match)
- Date (exact match)
- Aadhar (if provided)

### Examples
```
Database: John Doe | 15/12/2025 | 123456789012
Import:   John Doe | 15/12/2025 | 123456789012
Result:   ❌ SKIP (duplicate)

Import:   John Doe | 20/12/2025 | 123456789012
Result:   ✅ IMPORT (different date)
```

---

## ✅ Required Fields (Must Have)

```
1. Patient Name     (String)
2. Age              (0-150)
3. Sex              (Male/Female/Other)
4. Date             (DD/MM/YYYY)
5. Campaign Type ID (Valid ID from system)
6. Taluka ID        (Valid ID from system)
7. Mobile           (10 digits)
```

### Optional Fields
```
Village, Aadhar, Height, Weight, BP, RBS, BSL, HB,
Complaints, Known Conditions, Diagnosis, Treatment,
Dosage, Lab Tests, Sample Collected, Referral Type,
Referral Details, Notes
```

---

## 🆔 Where to Find IDs

### Campaign Type IDs
```
Admin → Campaign Management → Campaign Types
See list with IDs
Example:
  ID 1: General Screening
  ID 2: COVID Vaccination
  ID 3: Health Camp
```

### Taluka IDs
```
Admin → Location Management → Talukas
Search for your taluka, note the ID
Example:
  ID 1: Mumbai
  ID 5: Pune
  ID 10: Belgaum
```

---

## 📊 File Requirements

### Supported Formats
```
✅ Excel (.xlsx)        ← Recommended
✅ Excel (.xls)
✅ CSV (.csv)
❌ Google Sheets (export as .xlsx first)
❌ Numbers (.numbers)   (convert to .xlsx)
```

### File Size
```
Maximum: 10 MB
Recommended: < 5 MB
For 10000+ records: Split into 2-3 files
```

---

## 🚀 Import Speed

```
Typical Speed: 100-200 records/second
1000 records:  5-10 seconds
5000 records:  25-50 seconds
10000 records: 50-100 seconds
```

---

## ❌ Common Mistakes

### 1. Wrong Date Format
```
❌ 2025-12-15    (ISO format)
❌ 12/15/2025    (American format)
✅ 15/12/2025    (Correct: DD/MM/YYYY)
```

### 2. Mobile Has Spaces/Dashes
```
❌ 98765 43210
❌ 98765-43210
✅ 9876543210
```

### 3. Campaign/Taluka ID Doesn't Exist
```
Verify in Admin Panel first!
Don't guess IDs
```

### 4. Special Characters in Names
```
❌ John@Doe
❌ Mary#Smith
✅ John Doe
✅ Mary-Smith
```

### 5. Aadhar Wrong Length
```
❌ 12345678901   (11 digits)
❌ 1234567890123 (13 digits)
✅ 123456789012  (12 digits)
```

---

## 🐛 If Something Goes Wrong

### 404 Error on Import Page
```
Solution: Clear browser cache (Ctrl+Shift+R)
          Check you're logged in
          Check permissions
```

### File Won't Upload
```
Check: File size < 10 MB
       File type is .xlsx, .xls, or .csv
       No special characters in filename
```

### Records Rejected as Duplicates
```
Reason: Same Name + Date + Aadhar exists
Fix: Check existing records for duplicates
    Or: Change date/aadhar slightly
```

### Records Look Wrong After Import
```
Likely: Date was formatted as text
        Numbers interpreted as text
Solution: Check file in Excel first
          Test with smaller batch
```

---

## 🔒 Permissions Required

### Must Have Both:
```
✅ patients_view   (see import form)
✅ patients_create (create records)
```

### Who Has Access?
```
Admin User:    ✅ All permissions
Data Entry:    ✅ Patient management only
Regular User:  ❌ No access
```

---

## 📱 After Import

### View Results
```
1. Success message shows count
2. Failed records listed (if any)
3. Click "View All Patients"
4. New records appear at TOP (latest first)
```

### Fix Failed Records
```
1. Note the error reason
2. Correct data in Excel
3. Re-upload fixed file
4. System imports now (no duplicates)
```

### Check Patient Details
```
1. Go to Patient List
2. Click "View" on patient
3. See all imported fields
4. Can edit if needed
```

---

## 🎓 Important Notes

### Auto-Populated Fields
```
When you specify Taluka ID, these auto-fill:
  → District (from Taluka)
  → State (from District)
  → Country (from State)

DON'T include these in import file!
```

### Serial Numbers
```
Auto-generated format: PAT-2025-0001, PAT-2025-0002, etc.
Not specified by you
System handles automatically
```

### User Attribution
```
All records credited to logged-in user
Shows in "Created By" field
Automatic - you don't set this
```

### Date Fields
```
Date of patient visit/record
Must be DD/MM/YYYY format
Not the current date - actual visit date
```

---

## 📞 Need Help?

### Read This First
```
1. PATIENT_IMPORT_GUIDE.md          (Comprehensive guide)
2. TROUBLESHOOTING.md               (Common issues & fixes)
3. README_IMPORT_FEATURE.md          (Complete docs)
```

### Check This
```
1. Admin Panel → Patient Management (see existing format)
2. Download template (see example data)
3. Browser console (F12 → Console for JS errors)
```

### Common Resources
```
Sample Template:    /admin/patients/download-template
Import Form:        /admin/patients/import-form
Patient List:       /admin/patients
```

---

## ⚡ Pro Tips

### Tip 1: Validate Before Upload
```
Excel: Use data validation on columns
       Catches format errors before upload
```

### Tip 2: Import in Batches
```
Don't: Upload 50,000 records at once
Do: Split into 5x10,000 or 10x5,000
    Faster processing
    Easier to debug
```

### Tip 3: Keep Original File
```
Do: Keep copy of original Excel file
    For reference/audit trail
    In case of re-import
```

### Tip 4: Test First
```
Do: Create test file with 10 records
    Verify import works
    Then do bulk import
```

### Tip 5: Check Duplicates
```
Before: Sort by Name, Date, Aadhar
        Remove obvious duplicates
        Reduces failed imports
```

---

## ✨ Feature Highlights

```
✅ Professional UI        (forms, guidelines)
✅ Error Reporting        (detailed reasons)
✅ Duplicate Prevention    (no duplicates)
✅ Permission Control     (admin + data entry)
✅ Responsive Design      (mobile-friendly)
✅ Fast Processing        (100+ records/sec)
✅ Auto Serial Numbers    (PAT-YYYY-NNNN)
✅ User Attribution       (who imported it)
✅ Location Hierarchy     (auto-fills)
✅ Sample Template        (download anytime)
```

---

## 🎯 One-Liner Summary

**Download template → Fill Excel → Upload → See Results → Done!**

---

## 📋 Checklist Before Uploading

- [ ] Date format is DD/MM/YYYY
- [ ] Mobile is exactly 10 digits
- [ ] Aadhar is 12 digits (if used)
- [ ] Campaign Type ID exists
- [ ] Taluka ID exists
- [ ] No special characters in names
- [ ] No spaces in phone numbers
- [ ] Age is 0-150
- [ ] Sex is Male/Female/Other
- [ ] File size < 10 MB
- [ ] File type is .xlsx, .xls, or .csv

---

## 🚨 Red Flags

```
🚩 "Field validation failed"
   → Check field format (date, mobile, aadhar)

🚩 "Campaign Type ID not found"
   → Verify ID in Admin Panel

🚩 "Taluka ID not found"
   → Verify ID in Admin Panel

🚩 "Patient already exists"
   → Same name + date + aadhar (duplicate)

🚩 "File too large"
   → File > 10 MB (split file)

🚩 "Unsupported file type"
   → File not .xlsx, .xls, or .csv
```

---

## 💡 Remember

1. **Date = DD/MM/YYYY** (not YYYY-MM-DD)
2. **Mobile = 10 digits** (no spaces)
3. **Duplicates = Name + Date + Aadhar** (all 3)
4. **IDs must exist** (check in Admin Panel first)
5. **Test small** before large bulk import

---

**🎉 That's All You Need to Know!**

For more details, see `PATIENT_IMPORT_GUIDE.md`
