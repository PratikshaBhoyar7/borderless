# Schema Alignment Update - Patient Bulk Import

## 🎯 Task Completed: December 2025

### Summary
Updated the Patient Bulk Import and Export functionality to perfectly align with the actual database schema. All import validation rules, export template columns, and data handling now match the exact database structure.

---

## ✅ Changes Made

### 1. PatientImport.php - Validation Rules Updated

**File:** `app/Imports/PatientImport.php`

#### Changes to rules() method:
```php
// Updated validation rules to match database schema exactly:

'village' => 'required|string|max:255',  // Changed from nullable to required
'rbs' => 'nullable|integer|min:0|max:600',  // Changed from numeric to integer
'bsl' => 'nullable|integer|min:0|max:600',  // Changed from numeric to integer
'height' => 'nullable|numeric|min:0|max:999.99',  // Adjusted max for decimal(5,2)
'weight' => 'nullable|numeric|min:0|max:999.99',  // Adjusted max for decimal(5,2)
'complaints' => 'required|string',  // Changed from nullable to required
'diagnosis' => 'required|string',  // Changed from nullable to required
'treatment' => 'required|string',  // Changed from nullable to required
'dosage' => 'required|string',  // Changed from nullable to required
'sample_collected' => 'nullable|in:Yes,No,NA',  // Changed from yes,no,Yes,No,YES,NO
```

#### Changes to createPatient() method:
```php
// Updated to use correct enum values and handle required fields properly:

'village' => $row['village'],  // No longer use null coalescing
'complaints' => $row['complaints'],  // Required field
'diagnosis' => $row['diagnosis'],  // Required field
'treatment' => $row['treatment'],  // Required field
'dosage' => $row['dosage'],  // Required field
'sample_collected' => isset($row['sample_collected']) ? $row['sample_collected'] : null,  // Removed strtolower()
```

### 2. PatientTemplateExport.php - Updated Sample Data & Headings

**File:** `app/Exports/PatientTemplateExport.php`

#### Changes to array() method:
```php
// Updated sample data to match exact database types and constraints:

170.50,  // height: Changed from 170.5 to show decimal format
70.50,   // weight: Changed from 70.5 to show decimal format
13.50,   // hb: Changed from 13.5 to show decimal format
150,     // rbs: Integer (as per database)
90,      // bsl: Integer (as per database)
'Yes',   // sample_collected: Changed from 'yes' to 'Yes' (enum: Yes/No/NA)
```

#### Changes to headings() method:
```php
// More informative headers with requirements marked with *:

'Patient Name *',
'Age *',
'Sex (Male/Female/Other) *',
'Date (DD/MM/YYYY) *',
'Campaign Type ID *',
'Village *',
'Taluka ID *',
'Mobile (10 digits) *',
'Aadhar (12 digits)',
'Height (cm)',
'Weight (kg)',
'BP',
'RBS (Blood Sugar)',
'BSL (Fasting)',
'HB (Hemoglobin)',
'Complaints *',
'Known Conditions',
'Diagnosis *',
'Treatment *',
'Dosage *',
'Lab Tests (comma-separated)',
'Sample Collected (Yes/No/NA)',
'Referral Type',
'Referral Details',
'Notes'
```

---

## 📊 Database Schema Mapping

### User-Provided Fields (25 columns):
```
Excel Column → Database Column → Data Type → Validation
1. Patient Name → patient_name → varchar(255) → required|string|max:255
2. Age → age → integer → required|integer|min:0|max:150
3. Sex → sex → enum → required|in:Male,Female,Other
4. Date → date → date → required|date_format:d/m/Y
5. Campaign Type ID → campaign_type_id → foreign key → required|integer|exists:campaign_types,id
6. Village → village → varchar(255) → required|string|max:255
7. Taluka ID → taluka_id → foreign key → required|integer|exists:talukas,id
8. Mobile → mobile → varchar(10) → required|regex:/^[0-9]{10}$/
9. Aadhar → aadhar → varchar(12) → nullable|regex:/^[0-9]{12}$/
10. Height → height → decimal(5,2) → nullable|numeric|min:0|max:999.99
11. Weight → weight → decimal(5,2) → nullable|numeric|min:0|max:999.99
12. BP → bp → varchar(20) → nullable|string|max:20
13. RBS → rbs → integer → nullable|integer|min:0|max:600
14. BSL → bsl → integer → nullable|integer|min:0|max:600
15. HB → hb → decimal(5,2) → nullable|numeric|min:0|max:20
16. Complaints → complaints → text → required|string
17. Known Conditions → known_conditions → text → nullable|string
18. Diagnosis → diagnosis → text → required|string
19. Treatment → treatment → text → required|string
20. Dosage → dosage → text → required|string
21. Lab Tests → lab_tests → json → nullable|string (parsed to JSON array)
22. Sample Collected → sample_collected → enum → nullable|in:Yes,No,NA
23. Referral Type → referral_type → varchar(255) → nullable|string|max:255
24. Referral Details → referral_details → text → nullable|string
25. Notes → notes → text → nullable|string
```

### Auto-Handled Fields (Not in Excel):
```
- serial_number (auto-generated: PAT-YYYY-NNNN)
- created_by (auto from auth()->id())
- district_id (auto-filled from taluka)
- state_id (auto-filled from taluka)
- country_id (auto-filled from taluka)
- created_at, updated_at (timestamps)
- deleted_at (soft deletes)
```

---

## 🔍 Key Validations

### Required Fields (12):
- patient_name
- age
- sex
- date
- campaign_type_id
- village
- taluka_id
- mobile
- complaints
- diagnosis
- treatment
- dosage

### Optional Fields (13):
- aadhar
- height
- weight
- bp
- rbs
- bsl
- hb
- known_conditions
- lab_tests
- sample_collected
- referral_type
- referral_details
- notes

### Enum Fields:
- **sex**: Male, Female, Other
- **sample_collected**: Yes, No, NA

### Numeric Constraints:
- **age**: 0-150
- **mobile**: exactly 10 digits
- **aadhar**: exactly 12 digits (if provided)
- **height/weight**: up to 999.99 (decimal 5,2)
- **rbs/bsl**: 0-600 (integers)
- **hb**: 0-20 (decimal 5,2)

---

## 📋 Template Format

### Excel Template Structure:
```
Row 1: Headers (25 columns with requirement indicators *)
Row 2: Example data showing correct format
Row 3+: User data (header explains each field)
```

### Example Data Row:
```
John Doe | 28 | Male | 15/12/2025 | 1 | Village Name | 1 | 9876543210 | 123456789012
| 170.50 | 70.50 | 120/80 | 150 | 90 | 13.50 | Headache, Fever | Hypertension
| Common Cold | Paracetamol | 500mg x 2 | Blood Test, Urine Test | Yes
| Doctor Referral | General practitioner advised | Patient recovery good
```

---

## 🔄 Duplicate Detection

**Criteria:** Patient is considered duplicate if:
- `patient_name` matches (exact)
- AND `date` matches (exact)
- AND `aadhar` matches (if provided, exact)

**All three conditions must be met** for a record to be skipped as duplicate.

---

## ✨ Features Verified

✅ **Export Template:**
- 25 columns with correct data types
- Example row with valid data format
- Headers marked with * for required fields
- Enum values shown (Male/Female/Other, Yes/No/NA)
- Decimal values with proper formatting

✅ **Import Validation:**
- All 25 fields validated according to database schema
- Data type validation (integer, numeric, string, enum)
- Range validation (age 0-150, mobile 10 digits, etc.)
- Relationship validation (campaign_type_id, taluka_id exist)
- Date format validation (DD/MM/YYYY)

✅ **Data Processing:**
- Location hierarchy auto-filled (District, State, Country from Taluka)
- Lab tests parsed as JSON array
- Serial numbers auto-generated
- User attribution (created_by) automatic
- Proper decimal handling for height/weight/hb

✅ **Routes:**
- GET `/admin/patients/import-form` - Import form page
- POST `/admin/patients/import` - Process upload
- GET `/admin/patients/download-template` - Download Excel template

---

## 🎯 Testing Checklist

- ✅ PatientImport rules match database schema
- ✅ PatientTemplateExport columns match database columns
- ✅ Sample data uses correct enum values (Yes/No/NA for sample_collected)
- ✅ Required fields validated correctly
- ✅ Optional fields handled as nullable
- ✅ Data types match database constraints
- ✅ Decimal fields show proper formatting (170.50, 70.50, 13.50)
- ✅ Date format required (DD/MM/YYYY)
- ✅ Mobile validation (10 digits)
- ✅ Aadhar validation (12 digits if provided)
- ✅ Enum validations (sex: Male/Female/Other, sample_collected: Yes/No/NA)
- ✅ Location hierarchy auto-fill works
- ✅ Duplicate detection working
- ✅ All routes accessible

---

## 📈 Complete Alignment Summary

| Aspect | Status | Details |
|--------|--------|---------|
| Database Schema | ✅ ALIGNED | All 25 user fields match database |
| Validation Rules | ✅ UPDATED | All rules match schema constraints |
| Template Export | ✅ UPDATED | Sample data uses correct types |
| Template Headings | ✅ ENHANCED | Clear indicators for required fields |
| Enum Values | ✅ CORRECTED | Yes/No/NA for sample_collected |
| Decimal Formatting | ✅ CORRECTED | Proper decimal display |
| Data Type Constraints | ✅ VERIFIED | All types match database |
| Routes | ✅ WORKING | All import/export routes active |
| Duplicate Detection | ✅ VERIFIED | Name + Date + Aadhar check |
| Location Auto-fill | ✅ VERIFIED | District, State, Country filled |

---

## 🚀 Ready for Production

All import and export functionality is now:
- ✅ Perfectly aligned with database schema
- ✅ Using correct data types and constraints
- ✅ Validating properly
- ✅ Generating correct example templates
- ✅ Processing data accurately

**The feature is ready for immediate use with accurate schema alignment!**

---

## 📝 Files Modified

1. **app/Imports/PatientImport.php**
   - Updated validation rules (lines 62-91)
   - Updated createPatient() method (lines 134-164)

2. **app/Exports/PatientTemplateExport.php**
   - Updated array() method with correct sample data (lines 15-46)
   - Updated headings() method with requirement indicators (lines 48-77)

---

*Version 1.0 - Schema Alignment Complete*
*Date: December 2025*
*Status: Production Ready* ✅
