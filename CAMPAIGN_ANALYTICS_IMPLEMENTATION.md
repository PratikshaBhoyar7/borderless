# Campaign Type Dynamic Field Visibility & Analytics Filter Implementation

## Overview
This document summarizes the complete implementation of campaign-based dynamic field visibility for patient forms and the addition of campaign type filtering to the analytics dashboard.

## Implementation Summary

### Phase 1: Dynamic Field Visibility by Campaign Type

#### Patient Form Updates
**Files Modified:**
- `resources/views/admin/patients/create.blade.php`
- `resources/views/admin/patients/edit.blade.php`

**Features:**
- Added JavaScript constants for campaign type IDs and names
- Implemented 3 campaign type visibility configurations:
  
  **1. Swatch Bharat Campaign (ID: 2)**
  - Visible Fields: Patient Name, Age, Sex, Country, State, District, Village, Mobile, Aadhar
  - Hidden Sections: Vital Signs, Clinical Information, Treatment, Lab & Referral, Additional Notes
  - Hidden Count: 20 fields set to null
  
  **2. Special HC. Beneficiary Campaign (ID: 3)**
  - Visible Fields: 15 fields including Patient details, Location, Complaints, Investigation, Diagnosis, Treatment, Dosage, Referral details, Advice
  - Hidden Count: 12 fields set to null (Vital Signs, BMI, Known Conditions, Topic Covered, Lab Tests, Sample Collected, Notes)
  
  **3. Awareness Camp Campaign (ID: 4)**
  - Visible Fields: 12 fields including Patient details, Location, Topic Covered, Height, Weight, BMI, Investigation, Advice
  - Hidden Count: 14 fields set to null (Complaints, Known Conditions, Diagnosis, Vital Signs, Treatment, Dosage, Lab Tests, Referral details, Notes)

**JavaScript Implementation:**
- `toggleSwatchBharatFields()` - Manages visibility for Swatch Bharat campaign
- `toggleSpecialHCFields()` - Manages visibility for Special HC. Beneficiary campaign
- `toggleAwarenesscamp()` - Manages visibility for Awareness Camp campaign
- Functions hide/show field containers and manage required attribute validation
- All functions called on DOMContentLoaded and on campaign type change

#### Backend Logic
**File Modified:**
- `app/Http/Controllers/Admin/PatientController.php`

**Changes:**
- Updated `store()` method with campaign-based field validation
- Updated `update()` method with campaign-based field validation
- Detects campaign type and sets hidden fields to null before database save
- Uses string matching for flexible campaign name detection

### Phase 2: Database Migration

**File Created:**
- `database/migrations/2026_01_02_193204_make_patient_fields_nullable_for_swatch_bharat.php`

**Changes:**
- Made 4 fields nullable: `complaints`, `diagnosis`, `treatment`, `dosage`
- Allows Swatch Bharat campaign to store null values for these required fields
- Reversible migration for rollback capability

### Phase 3: Campaign Type Protection

**File Modified:**
- `app/Http/Controllers/Admin/CampaignTypeController.php`

**Features:**
- Added delete protection for 4 core campaign types:
  - OPD
  - Swatch bharat
  - Special HC. Beneficiary
  - Awareness camp
- Shows error message: "This is a protected campaign type and cannot be deleted."
- Whitelist-based protection in `destroy()` method

### Phase 4: Analytics Filter Integration

#### Backend Implementation
**File Modified:**
- `app/Http/Controllers/Admin/AnalyticsController.php`

**Changes:**
1. Added import: `use App\Models\CampaignType;`
2. Updated `index()` method:
   ```php
   $campaignTypes = CampaignType::active()->orderBy('name')->get(['id', 'name']);
   return view('admin.analytics.index', compact('countries', 'campaignTypes'));
   ```
3. Updated `applyFilters()` method:
   ```php
   if ($request->filled('campaign_type_id') && $request->input('campaign_type_id') !== '') {
       $query->where('campaign_type_id', $request->campaign_type_id);
   }
   ```

#### Frontend Implementation
**Files Modified:**
- `resources/views/admin/analytics/index.blade.php`
- `public/assets/js/analytics.js`

**Blade Template Changes:**
- Added campaign type filter dropdown in filter form (line 130-139)
- Positioned between Age Group Filter and Country Filter
- Iterates through $campaignTypes variable to populate options
- Includes "All Campaign Types" default option

**JavaScript Changes:**
1. Updated `applyFilters()` function to include campaign_type_id
2. Updated `resetFilters()` function to reset campaign type filter

#### API Integration
- All analytics endpoints automatically support campaign type filtering
- Filters work across all analytics queries:
  - Patient Statistics (KPIs)
  - Registration Trend
  - Demographics Analysis
  - Health Metrics
  - Lab Diagnostics
  - Treatment Analytics
  - Patient Records Table

## Data Flow

### Patient Creation/Update Flow
1. User selects campaign type from dropdown
2. JavaScript `toggleXxxFields()` functions hide/show relevant fields
3. Form validation adjusted based on visible fields
4. User submits form
5. Backend controller detects campaign type
6. Hidden fields are set to null
7. Patient record saved with appropriate field values

### Analytics Filtering Flow
1. User selects campaign type from analytics filter dropdown
2. User clicks "Apply Filters"
3. JavaScript `applyFilters()` function collects campaign_type_id
4. AJAX calls made to API endpoints with campaign_type_id parameter
5. Backend `applyFilters()` applies WHERE clause for campaign_type_id
6. Charts and statistics update with filtered data

## File Structure

```
borderless/
├── app/Http/Controllers/Admin/
│   ├── AnalyticsController.php          [Modified]
│   ├── CampaignTypeController.php       [Modified]
│   └── PatientController.php            [Modified]
├── database/migrations/
│   └── 2026_01_02_193204_...           [New]
├── public/assets/js/
│   └── analytics.js                     [Modified]
├── resources/views/admin/
│   ├── analytics/
│   │   └── index.blade.php              [Modified]
│   └── patients/
│       ├── create.blade.php             [Modified]
│       └── edit.blade.php               [Modified]
└── routes/
    └── web.php                          [Unchanged]
```

## Testing Checklist

- [x] PHP syntax validation (all controllers and migration)
- [x] Campaign type selection changes form field visibility
- [x] Form submission saves null values for hidden fields
- [x] Database stores null values correctly
- [x] Campaign type deletion protection works (4 core types cannot be deleted)
- [x] Analytics filter dropdown populated with campaign types
- [x] Filter applies correctly to all analytics endpoints
- [x] Filter resets properly with reset button
- [x] Multiple filter combinations work together
- [x] All charts update with filtered campaign data

## Compatibility

- **Laravel Version:** 10+
- **Database:** MySQL/MariaDB
- **Frontend:** Bootstrap 5, Chart.js 3.9.1
- **Browser Support:** Modern browsers with ES6 support

## Notes

- All hidden fields are set to null (not deleted from database)
- Campaign type is a required foreign key relationship with Patient model
- Protection of core campaign types prevents data loss from accidental deletion
- Analytics filters are non-destructive (data is always preserved)
- Form field visibility is purely frontend-driven for better UX

