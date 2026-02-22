# Patient Data Bulk Import Guide

## Overview

The Patient Bulk Import feature allows administrators to import multiple patient records at once using Excel or CSV files. This guide provides detailed instructions on how to use this feature effectively.

## Quick Start

1. Navigate to **Admin Panel → Patient Management**
2. Click the **"Bulk Import"** button
3. Download the sample template to understand the required format
4. Fill in your patient data following the template format
5. Upload your file and click **"Upload & Import"**
6. Review the results - successfully imported records and any failures/duplicates

## Accessing the Import Feature

### In the Admin Panel:
- **Path:** Admin → Patient Management
- **Button:** "Bulk Import" (green button in the header)
- **Direct URL:** `/admin/patients/import-form`

### Download Sample Template:
- **Button:** "Download Sample Template" (green button on import page)
- **Direct URL:** `/admin/patients/download-template`
- **File name:** `patient_template.xlsx`

## File Format Requirements

### Supported File Types
- Microsoft Excel (.xlsx) - **Recommended**
- Excel 97-2003 (.xls)
- CSV (.csv)
- **Maximum file size:** 10 MB

### Required Column Headers (in exact order):

| # | Column Name | Data Type | Example |
|---|---|---|---|
| 1 | Patient Name | String | John Doe |
| 2 | Age | Integer | 28 |
| 3 | Sex | String | Male |
| 4 | Date (DD/MM/YYYY) | Date | 15/12/2025 |
| 5 | Campaign Type ID | Integer | 1 |
| 6 | Village | String | Village Name |
| 7 | Taluka ID | Integer | 1 |
| 8 | Mobile (10 digits) | String | 9876543210 |
| 9 | Aadhar (12 digits) | String | 123456789012 |
| 10 | Height (cm) | Decimal | 170.5 |
| 11 | Weight (kg) | Decimal | 70.5 |
| 12 | BP | String | 120/80 |
| 13 | RBS | Integer | 150 |
| 14 | BSL | Integer | 90 |
| 15 | HB | Decimal | 13.5 |
| 16 | Complaints | String | Headache, Fever |
| 17 | Known Conditions | String | Hypertension |
| 18 | Diagnosis | String | Common Cold |
| 19 | Treatment | String | Paracetamol |
| 20 | Dosage | String | 500mg x 2 |
| 21 | Lab Tests (comma-separated) | String | Blood Test, Urine Test |
| 22 | Sample Collected (yes/no) | String | yes |
| 23 | Referral Type | String | Doctor Referral |
| 24 | Referral Details | String | General practitioner advised |
| 25 | Notes | String | Patient recovery good |

## Field Validation Rules

### Mandatory Fields
The following fields **must** be filled for each patient:
- **Patient Name** - Cannot be empty
- **Age** - Must be between 0 and 150
- **Sex** - Must be one of: Male, Female, Other
- **Date** - Must be in DD/MM/YYYY format (e.g., 25/12/2025)
- **Campaign Type ID** - Must exist in the system
- **Taluka ID** - Must exist in the system
- **Mobile** - Must be exactly 10 digits

### Optional Fields
- **Aadhar** - If provided, must be exactly 12 digits
- **Village** - Can be blank
- **All other fields** - Can be left blank if not applicable

### Field-Specific Validation

| Field | Validation Rule | Valid Example | Invalid Example |
|---|---|---|---|
| **Mobile** | Exactly 10 digits, numeric only | 9876543210 | 98765-4321 (has dash) |
| **Aadhar** | Exactly 12 digits, numeric only, optional | 123456789012 | 1234-5678-9012 |
| **Age** | 0-150, numeric only | 45 | 250 (too high) |
| **Sex** | Male, Female, or Other | Male | M (invalid) |
| **Date** | DD/MM/YYYY format | 25/12/2025 | 2025-12-25 (wrong format) |
| **Height/Weight** | Positive decimal numbers | 170.5 | -170 (negative) |
| **BP** | Any string format | 120/80 | - (can be any text) |
| **Lab Tests** | Comma-separated values | Blood Test, Urine Test | (must separate with comma) |
| **Sample Collected** | yes, no, Yes, No, YES, NO | yes | true (invalid) |

## Duplicate Detection

### How Duplicates Are Detected

A patient record is considered a **duplicate** if:
1. **Patient Name** matches an existing record AND
2. **Date** matches an existing record AND
3. **Aadhar** matches an existing record (if provided in the import)

### Example Scenarios

**Scenario 1 - DUPLICATE (Will NOT be imported):**
```
Existing Record:  John Doe | 15/12/2025 | 123456789012
Import Record:    John Doe | 15/12/2025 | 123456789012
Result:           ❌ DUPLICATE - Record skipped
```

**Scenario 2 - NOT a Duplicate (Will be imported):**
```
Existing Record:  John Doe | 15/12/2025 | 123456789012
Import Record:    John Doe | 20/12/2025 | 123456789012
Result:           ✅ IMPORT - Different date
```

**Scenario 3 - NOT a Duplicate (Will be imported):**
```
Existing Record:  John Doe | 15/12/2025 | 123456789012
Import Record:    John Doe | 15/12/2025 | 210987654321
Result:           ✅ IMPORT - Different aadhar
```

**Scenario 4 - NOT a Duplicate (Will be imported):**
```
Existing Record:  John Doe  | 15/12/2025 | 123456789012
Import Record:    Jane Doe  | 15/12/2025 | 123456789012
Result:           ✅ IMPORT - Different name
```

## Getting Required IDs

### Campaign Type IDs

To find Campaign Type IDs:
1. Go to **Admin → Campaign Management → Campaign Types**
2. View the list of all available campaigns
3. Note the ID number for each campaign
4. Use these IDs in your import file

### Taluka IDs

To find Taluka IDs:
1. Go to **Admin → Location Management → Talukas**
2. Search for your desired taluka
3. Note the ID number
4. Use these IDs in your import file

**Note:** Taluka ID automatically determines:
- District
- State
- Country

So you only need to specify the Taluka ID.

## Step-by-Step Import Process

### Step 1: Prepare Your Data

1. Download the sample template from the import page
2. Open the template in Excel
3. Keep the header row as is (first row)
4. Start entering data from row 2 onwards
5. Ensure date format is **DD/MM/YYYY**

### Step 2: Validate Your Data

Before uploading, verify:
- [ ] All mandatory fields are filled
- [ ] Date format is DD/MM/YYYY
- [ ] Mobile numbers are exactly 10 digits
- [ ] Campaign Type IDs exist in the system
- [ ] Taluka IDs exist in the system
- [ ] Patient names are not duplicates of existing records (same name + date + aadhar)

### Step 3: Upload the File

1. Click the **"Bulk Import"** button from Patient Management page
2. Click **"Choose File"** and select your Excel/CSV file
3. Click **"Upload & Import"** button
4. Wait for the process to complete (may take a few moments for large files)

### Step 4: Review Results

After import completes, you will see:

#### Success Message
Shows: "Successfully imported **X** patients."

#### Failure Summary (if any)
Shows a table with:
- **Patient Name** - Name of the patient record
- **Date** - Date from the record
- **Aadhar** - Aadhar number (if provided)
- **Reason** - Why the record was not imported

#### Common Failure Reasons
- "Invalid date format" - Date not in DD/MM/YYYY format
- "Patient with same name, date, and aadhar already exists" - Duplicate record
- "Invalid Taluka ID" - Taluka doesn't exist in system
- "Invalid Campaign Type ID" - Campaign type doesn't exist
- "[Field name] is invalid" - Field validation failed
- Missing required field values

### Step 5: View Imported Records

1. Click **"View All Patients"** button (shown in success message)
2. Or navigate to **Admin → Patient Management**
3. Your newly imported patients will appear at the top of the list

## Common Issues and Solutions

### Issue 1: "Date format is invalid"

**Problem:** Dates are not in DD/MM/YYYY format

**Solution:**
- Ensure all dates follow DD/MM/YYYY format
- Example: December 25, 2025 should be **25/12/2025**, not 2025-12-25
- Check for any text in date cells that should be removed
- Use Excel's date formatting to ensure consistency

### Issue 2: "Mobile validation failed"

**Problem:** Mobile number is not exactly 10 digits

**Solution:**
- Ensure mobile numbers are exactly 10 digits
- Remove any spaces, dashes, or special characters
- Example: ✅ 9876543210, ❌ 98-765-43210
- Check that the cell is formatted as Text, not Number

### Issue 3: "Aadhar validation failed"

**Problem:** Aadhar number is not exactly 12 digits

**Solution:**
- If providing aadhar, ensure it's exactly 12 digits
- Remove any spaces or dashes
- Leave empty if aadhar is not available
- Example: ✅ 123456789012, ❌ 1234-5678-9012

### Issue 4: "Campaign Type ID not found"

**Problem:** Campaign type ID doesn't exist in system

**Solution:**
1. Go to **Admin → Campaign Management → Campaign Types**
2. Check available campaign type IDs
3. Update your import file with valid IDs
4. Re-upload the file

### Issue 5: "Taluka ID not found"

**Problem:** Taluka ID doesn't exist in system

**Solution:**
1. Go to **Admin → Location Management → Talukas**
2. Find the correct Taluka ID
3. Update your import file with valid ID
4. Re-upload the file

### Issue 6: "Records appear as duplicates but shouldn't be"

**Problem:** Valid records are being skipped as duplicates

**Solution:**
- Verify the exact spelling of patient names (case-sensitive)
- Confirm the dates are identical to existing records
- Check if aadhar numbers match
- If records are truly different, they should import successfully

## Best Practices

### Before Importing

1. **Download the template first** - Always use the official template to ensure correct format
2. **Validate data externally** - Use Excel formulas to check for common issues:
   ```
   For Date Format: =IF(ISDATE(A2), "Valid", "Invalid")
   For Mobile Length: =IF(LEN(B2)=10, "Valid", "Invalid")
   For Aadhar Length: =IF(LEN(C2)=12, "Valid", "Invalid")
   ```
3. **Deduplicate locally** - Sort by Patient Name, Date, and Aadhar to find duplicates before import
4. **Test with small batch first** - Import 10-20 records first to verify format works
5. **Keep backup** - Keep a copy of your original file

### During Importing

1. **Don't close the browser** - Wait for the process to complete
2. **Check for errors** - Review the failure report after import
3. **Note failed records** - Keep track of why records failed for correction

### After Importing

1. **Verify count** - Check the patient count increased by the expected number
2. **Spot check records** - View a few imported records to verify data integrity
3. **Document failures** - Record any failed imports for follow-up
4. **Correct and re-import** - Fix failed records and re-import them

## Security Considerations

- **File size limit:** 10 MB maximum
- **Supported formats only:** .xlsx, .xls, .csv
- **Permission required:** Only users with "patients_create" permission can import
- **User attribution:** All imported records are credited to the logged-in user
- **Data validation:** All fields are validated server-side before insertion

## Performance

### Import Speed
- Typical speed: 100-200 records per second
- For 1000 records: ~5-10 seconds
- For 10000 records: ~50-100 seconds

### Batch Processing
- Current version processes records sequentially
- Large files (>5000 records) may take some time
- Patient serial numbers are auto-generated for each record

## Troubleshooting

### The import page shows a blank screen
- Clear browser cache and reload
- Try a different browser
- Check browser console for JavaScript errors

### File upload fails with "Invalid file type"
- Ensure your file is .xlsx, .xls, or .csv
- Don't upload .txt or other formats
- If renaming file type, actually convert the file format

### Error: "The file is too large"
- Maximum file size is 10 MB
- Split your import into smaller batches
- Remove unnecessary columns or data

### Records imported but data looks wrong
- Check the original file for formatting issues
- Verify dates are in DD/MM/YYYY format
- Check that numeric fields don't have text values
- Review the original and imported records side by side

## API Reference (for developers)

### Controller Methods

```php
// Show import form
GET /admin/patients/import-form

// Download template
GET /admin/patients/download-template

// Process import
POST /admin/patients/import
```

### Import Class

```php
namespace App\Imports;

class PatientImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    public function rules(): array
    public function getSuccessCount(): int
    public function getFailureCount(): int
    public function getDuplicates(): array
}
```

### Export Class

```php
namespace App\Exports;

class PatientTemplateExport implements FromArray, WithHeadings, WithStyles
```

## Support

For additional help:
1. Review the inline guidelines on the import page
2. Download and review the sample template
3. Check this documentation
4. Contact your administrator

## Version History

- **v1.0** (December 2025)
  - Initial release
  - Bulk import with duplicate detection
  - Sample template download
  - Field validation
  - Error reporting
