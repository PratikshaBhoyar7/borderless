# Visual Reference Guide - Campaign Management System

## 🗺️ Site Map

```
Admin Dashboard
│
├── Admin Panel Home
│   └── Campaign Management ← NEW SECTION
│       │
│       ├── Campaign Types
│       ├── Campaign Configuration ← NEW
│       │   ├── Index (List all campaigns)
│       │   ├── Create (New campaign form)
│       │   ├── Edit (Edit campaign)
│       │   ├── Show (Campaign details)
│       │   └── Field Configuration (Field visibility details)
│       │
│       └── Pending Verifications
│
├── Patient Management
│   └── Patients
│       └── Campaign Entries
│           ├── Create (Uses CampaignFieldsConfig)
│           ├── Edit (Uses CampaignFieldsConfig)
│           └── Show
│
└── Other Admin Sections
```

---

## 📺 UI Layout - Campaign List Page

```
┌─────────────────────────────────────────────────────────┐
│  Campaign Configuration                                 │
│  Manage campaign types and field configurations          │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  Quick Stats                                            │
├─────────────────────────────────────────────────────────┤
│ ┌─────────────┐  ┌─────────────┐  ┌─────────────┐      │
│ │  Total: 4   │  │ Active: 3   │  │Config'd: 4  │      │
│ │ Campaigns   │  │ Campaigns   │  │    Types    │      │
│ └─────────────┘  └─────────────┘  └─────────────┘      │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  [+ New Campaign]                                       │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  Campaign List                                          │
├────┬──────────────┬──────────────┬──────┬─────────┬────┤
│Name│Code          │Type          │Fields│ Status  │ Act│
├────┼──────────────┼──────────────┼──────┼─────────┼────┤
│S.  │SWACH_BHARAT  │Swach Bharat  │ 9v   │ Active  │▼ ✎ ✘│
│    │              │              │ 5r   │         │    │
├────┼──────────────┼──────────────┼──────┼─────────┼────┤
│S.  │SPECIAL_HC    │Special HC    │17v   │ Active  │▼ ✎ ✘│
│    │              │              │ 8r   │         │    │
├────┼──────────────┼──────────────┼──────┼─────────┼────┤
│A.  │AWARENESS     │Awareness     │15v   │ Active  │▼ ✎ ✘│
│    │              │              │ 5r   │         │    │
├────┼──────────────┼──────────────┼──────┼─────────┼────┤
│O.  │OPD           │OPD           │25v   │ Active  │▼ ✎ ✘│
│    │              │              │ 8r   │         │    │
└────┴──────────────┴──────────────┴──────┴─────────┴────┘
* v=visible, r=required

┌─────────────────────────────────────────────────────────┐
│  Campaign Types Reference                               │
├─────────────────────────────────────────────────────────┤
│ ▶ Swach Bharat                                          │
│   Basic participant information collection              │
│   [9 Fields] [5 Required]                               │
├─────────────────────────────────────────────────────────┤
│ ▶ Special HC Beneficiary                                │
│   Clinical consultation with diagnosis and treatment    │
│   [17 Fields] [8 Required]                              │
├─────────────────────────────────────────────────────────┤
│ ▶ Awareness Camp                                        │
│   Health screening and awareness activities             │
│   [15 Fields] [5 Required]                              │
├─────────────────────────────────────────────────────────┤
│ ▶ OPD (Out Patient Department)                          │
│   Complete patient consultation with all parameters     │
│   [25 Fields] [8 Required]                              │
└─────────────────────────────────────────────────────────┘
```

---

## 📱 UI Layout - Create Campaign Page

```
┌─────────────────────────────────────────────────────────┐
│  Create New Campaign                                    │
│  Add a new campaign type to the system                  │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  Form                                                   │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  Campaign Name *           Campaign Code *             │
│  ┌─────────────────────┐  ┌──────────────────────┐     │
│  │Special Health Camp  │  │SPECIAL_HC            │     │
│  └─────────────────────┘  └──────────────────────┘     │
│                                                         │
│  Campaign Type *                                        │
│  ┌──────────────────────────────────────────┐          │
│  │ Awareness Camp                          ▼│          │
│  └──────────────────────────────────────────┘          │
│                                                         │
│  ☑ Active (allow data entry)                           │
│                                                         │
│  Description                                            │
│  ┌────────────────────────────────────────────────┐    │
│  │ Community health screening and awareness      │    │
│  │ program for preventive health measures        │    │
│  │                                                │    │
│  └────────────────────────────────────────────────┘    │
│                                                         │
├─────────────────────────────────────────────────────────┤
│  Campaign Type Information                              │
├─────────────────────────────────────────────────────────┤
│  Awareness Camp                                         │
│  Health screening and awareness activities with        │
│  measurements                                           │
│  [15 total fields] [5 required fields]                 │
│                                                         │
├─────────────────────────────────────────────────────────┤
│  Available Fields for Selected Campaign                 │
├─────────────────────────────────────────────────────────┤
│  ┌─────────────────────────┐  ┌──────────────────────┐ │
│  │ Patient Name [Required] │  │ Age [Required]      │ │
│  └─────────────────────────┘  └──────────────────────┘ │
│  ┌─────────────────────────┐  ┌──────────────────────┐ │
│  │ Sex [Required]          │  │ Country [Optional]   │ │
│  └─────────────────────────┘  └──────────────────────┘ │
│  ┌─────────────────────────┐  ┌──────────────────────┐ │
│  │ Height (cm) [Optional]  │  │ Weight (kg)[Optional]│ │
│  └─────────────────────────┘  └──────────────────────┘ │
│  ... more fields                                        │
│                                                         │
└─────────────────────────────────────────────────────────┘

[Create Campaign] [Cancel]
```

---

## 🔍 UI Layout - Campaign Show Page

```
┌─────────────────────────────────────────────────────────┐
│  Campaign Details                                       │
│  Swach Bharat                                           │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  Quick Stats                                            │
├────────────────┬────────────────┬────────────────┬─────┤
│ Total Entries  │ Verified       │ Visible Fields │ Act │
│      45        │      38        │       9        │ ✓   │
└────────────────┴────────────────┴────────────────┴─────┘

┌─────────────────────────────────────────────────────────┐
│  Campaign Information                                   │
├─────────────────────────────────────────────────────────┤
│  Campaign Name: Swach Bharat                            │
│  Code: SWACH_BHARAT                                     │
│  Type: Swach Bharat                                     │
│  Status: ✓ Active                                       │
│  Description:                                           │
│  Basic participant information collection              │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  Field Configuration                                    │
├─────────────────────────────────────────────────────────┤
│  ▼ Basic Information                                    │
│    ├─ Patient Name (text) [Required]                   │
│    ├─ Age (number) [Required]                          │
│    └─ Sex (select) [Required]                          │
│                                                         │
│  ▼ Location Details                                    │
│    ├─ Country (select) [Optional]                      │
│    ├─ State (select) [Optional]                        │
│    ├─ District (select) [Optional]                     │
│    └─ Village (text) [Required]                        │
│                                                         │
│  ▼ Contact Information                                 │
│    ├─ Mobile Number (phone) [Required]                │
│    └─ Aadhar Number (text) [Optional]                 │
│                                                         │
└─────────────────────────────────────────────────────────┘

[Edit] [View Field Configuration] [Back]
```

---

## 🎨 UI Layout - Field Configuration Page

```
┌─────────────────────────────────────────────────────────┐
│  Field Configuration                                    │
│  Swach Bharat - Detailed field visibility settings     │
└─────────────────────────────────────────────────────────┘

┌────────┬──────────┬──────────┬──────────┐
│Visible │Required  │Optional  │ Field    │
│  9     │    5     │    4     │ Types: 3 │
└────────┴──────────┴──────────┴──────────┘

┌─────────────────────────────────────────────────────────┐
│  Visible Fields (9)                                     │
├─────────────────────────────────────────────────────────┤
│ Field Name    │ Label         │Type  │Required│ Section│
│ patient_name  │ Patient Name  │text  │ ✓      │ Basic  │
│ age           │ Age           │num   │ ✓      │ Basic  │
│ sex           │ Sex           │sel   │ ✓      │ Basic  │
│ country_id    │ Country       │sel   │ ✗      │ Loc    │
│ state_id      │ State         │sel   │ ✗      │ Loc    │
│ district_id   │ District      │sel   │ ✗      │ Loc    │
│ village       │ Village       │text  │ ✓      │ Loc    │
│ mobile        │ Mobile Number │tel   │ ✓      │ Contact│
│ aadhar        │ Aadhar Number │text  │ ✗      │ Contact│
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  Required Fields (5)                                    │
├─────────────────────────────────────────────────────────┤
│ ■ Patient Name (patient_name)                          │
│   Type: text | Placeholder: Enter name                 │
│                                                         │
│ ■ Age (age)                                            │
│   Type: number | Placeholder: Enter age                │
│                                                         │
│ ■ Sex (sex)                                            │
│   Type: select | Options: M/F/Other                    │
│                                                         │
│ ■ Village (village)                                    │
│   Type: text | Placeholder: Enter village              │
│                                                         │
│ ■ Mobile Number (mobile)                              │
│   Type: phone | Pattern: [0-9]{10}                     │
│                                                         │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  Optional Fields (4)                                    │
├─────────────────────────────────────────────────────────┤
│ □ Country (country_id) - Type: select                  │
│ □ State (state_id) - Type: select                      │
│ □ District (district_id) - Type: select                │
│ □ Aadhar Number (aadhar) - Type: text                  │
│                                                         │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  Hidden Fields (21)                                     │
├─────────────────────────────────────────────────────────┤
│ Field Name    │ Label         │Type  │ Section          │
│ height        │ Height (cm)   │num   │ Vital Signs      │
│ weight        │ Weight (kg)   │num   │ Vital Signs      │
│ bp            │ Blood Press.  │text  │ Vital Signs      │
│ hb            │ Hemoglobin    │num   │ Vital Signs      │
│ ... 16 more   │ ...           │...   │ ...              │
│                                                         │
└─────────────────────────────────────────────────────────┘

[Back to Campaign] [Back to List]
```

---

## 🔄 Data Flow Diagram

```
┌─────────────────────────────────────────────────────────┐
│  Patient Entry Form Creation                            │
└─────────────────────────────────────────────────────────┘
                        │
                        ▼
        ┌───────────────────────────────┐
        │ URL contains campaign_id       │
        │ e.g., /patients/1/campaigns/2 │
        └───────────┬───────────────────┘
                    │
                    ▼
        ┌───────────────────────────────┐
        │ CampaignEntryController        │
        │ @create method                │
        └───────────┬───────────────────┘
                    │
                    ▼
        ┌───────────────────────────────┐
        │ Get campaign from database    │
        │ Extract campaign.code         │
        │ e.g., "swach_bharat"         │
        └───────────┬───────────────────┘
                    │
                    ▼
        ┌───────────────────────────────┐
        │ Call CampaignFieldsConfig     │
        │ ::getVisibleFields('swach...') │
        └───────────┬───────────────────┘
                    │
                    ▼
        ┌───────────────────────────────┐
        │ Returns array:                │
        │ [                             │
        │   'patient_name',             │
        │   'age',                      │
        │   'sex',                      │
        │   'country_id',               │
        │   'state_id',                 │
        │   'district_id',              │
        │   'village',                  │
        │   'mobile',                   │
        │   'aadhar'                    │
        │ ]                             │
        └───────────┬───────────────────┘
                    │
                    ▼
        ┌───────────────────────────────┐
        │ View loops through fields     │
        │ Renders only visible fields   │
        │ in HTML form                  │
        └───────────┬───────────────────┘
                    │
                    ▼
        ┌───────────────────────────────┐
        │ User sees form with 9 fields  │
        │ 5 marked as required (red *)  │
        │ 4 optional                    │
        │                               │
        │ Other 16+ fields are HIDDEN   │
        └───────────────────────────────┘
```

---

## 🗂️ File Organization

```
borderless/
│
├── app/
│   ├── Config/
│   │   └── CampaignFieldsConfig.php ✨ NEW (300+ lines)
│   │       ├── $campaignFields (4 campaigns)
│   │       ├── $fieldDefinitions (30+ fields)
│   │       └── Helper methods
│   │
│   ├── Http/
│   │   └── Controllers/
│   │       └── Admin/
│   │           ├── CampaignController.php ✨ NEW (8 methods)
│   │           └── CampaignEntryController.php (updated)
│   │
│   └── Models/
│       ├── Campaign.php (existing)
│       └── PatientCampaignEntry.php (updated)
│
├── resources/
│   └── views/
│       ├── admin/
│       │   ├── campaigns/
│       │   │   ├── index.blade.php ✨ NEW
│       │   │   ├── create.blade.php ✨ NEW
│       │   │   ├── edit.blade.php ✨ NEW
│       │   │   ├── show.blade.php ✨ NEW
│       │   │   └── field-configuration.blade.php ✨ NEW
│       │   │
│       │   └── campaign-entries/
│       │       ├── create.blade.php (updated)
│       │       └── edit.blade.php (updated)
│       │
│       └── layouts/
│           └── admin.blade.php (sidebar updated)
│
├── routes/
│   └── web.php (routes added)
│
└── Documentation/ ✨ NEW
    ├── CAMPAIGN_SETUP_QUICK_START.md (343 lines)
    ├── CAMPAIGN_MANAGEMENT_UI_COMPLETE.md (400+ lines)
    ├── CAMPAIGN_FIELD_VISIBILITY.md (425+ lines)
    ├── CAMPAIGN_FIELDS_IMPLEMENTATION.md (400+ lines)
    ├── IMPLEMENTATION_COMPLETE_SUMMARY.md (592 lines)
    └── VISUAL_REFERENCE.md (this file)
```

---

## 🎓 Learning Path

```
New to System?
    │
    ▼
Read CAMPAIGN_SETUP_QUICK_START.md (15 min)
    │
    ├─→ Want to use the system?
    │   └─→ Go to Admin Panel → Campaign Management
    │
    ├─→ Want to modify fields?
    │   └─→ Read "Common Tasks" section
    │
    └─→ Want deep technical knowledge?
        └─→ Read CAMPAIGN_MANAGEMENT_UI_COMPLETE.md

Advanced Knowledge?
    │
    ▼
Read IMPLEMENTATION_COMPLETE_SUMMARY.md (20 min)
    │
    ├─→ Architecture and design patterns
    │   └─→ See "Architecture Overview" section
    │
    ├─→ Technical implementation details
    │   └─→ See "How It Works" section
    │
    └─→ Integration points
        └─→ See "System Integration" section

Developer?
    │
    ▼
Read CAMPAIGN_FIELDS_IMPLEMENTATION.md (30 min)
    │
    ├─→ Field definitions format
    ├─→ Campaign configuration structure
    ├─→ Helper methods and their usage
    └─→ Adding new fields or campaigns
```

---

## 🎯 Quick Navigation

| Need | Go To | File |
|------|-------|------|
| **Access Campaign Management** | Admin Sidebar → Campaign Management → Campaign Configuration | N/A |
| **View all campaigns** | Campaign Configuration Index | `/admin/campaigns` |
| **Create new campaign** | Click "New Campaign" button | `/admin/campaigns/create` |
| **Edit campaign** | Click campaign name → Edit button | `/admin/campaigns/{id}/edit` |
| **View field configuration** | Campaign details → Field Configuration button | `/admin/campaigns/{id}/field-configuration` |
| **Modify field visibility** | Edit file | `app/Config/CampaignFieldsConfig.php` |
| **Check user permissions** | User role management | `admin/role-permissions` |
| **View campaign entry forms** | Patient details → Campaign Entries | `patients/{id}/campaigns/{campaign_id}` |

---

**Visual Reference Guide Complete** ✅

Use this as a visual aid while working with the campaign management system!

