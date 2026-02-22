# Patient Health Analytics API Documentation

## Overview

Complete REST API documentation for the Patient Health Analytics Dashboard. All endpoints are protected by Laravel's permission middleware and require user authentication.

---

## Authentication

### Requirements
- **Authentication Type**: Bearer Token (Laravel Session-based or Token-based)
- **Headers Required**:
  ```
  Accept: application/json
  Content-Type: application/json
  ```
- **Permission Required**: `analytics_view` for all endpoints

### Session-Based Authentication (Default)
If using Laravel session authentication (cookie-based):
```
Cookie: XSRF-TOKEN=<token>
Cookie: laravel_session=<session_id>
```

### Token-Based Authentication (Optional)
If implementing API token authentication:
```
Authorization: Bearer <your-api-token>
X-CSRF-TOKEN: <csrf-token>
```

### Example Authentication Header
```bash
curl -H "Authorization: Bearer your-token-here" \
     -H "Accept: application/json" \
     https://yourdomain.com/admin/analytics/api/stats
```

---

## Base URL
```
https://yourdomain.com/admin/analytics
```

---

## API Endpoints

### 1. Dashboard View (HTML)
Get the main analytics dashboard page.

**Endpoint:**
```
GET /admin/analytics
```

**Authentication:** Required (Permission: `analytics_view`)

**Response:** HTML page with dashboard UI

**Example:**
```bash
curl -H "Cookie: laravel_session=xyz" \
     https://yourdomain.com/admin/analytics
```

---

## API Data Endpoints (JSON)

### 2. Patient Statistics (KPI Cards)

**Endpoint:**
```
GET /admin/analytics/api/stats
```

**Method:** `GET`

**Authentication:** Required (Permission: `analytics_view`)

**Query Parameters:**
```json
{
  "date_from": "2025-01-01",
  "date_to": "2025-12-31",
  "gender": "Male",
  "age_group": "18-30",
  "country_id": 1,
  "state_id": 1,
  "district_id": 1,
  "taluka_id": 1,
  "campaign_type_id": 1
}
```

**All parameters are optional. Leave blank to include all data.**

**Response:**
```json
{
  "total_patients": 150,
  "male_patients": 85,
  "female_patients": 65,
  "avg_age": 42.5,
  "samples_collected": 120,
  "referrals_made": 35
}
```

**Example Request:**
```bash
curl -X GET "https://yourdomain.com/admin/analytics/api/stats?gender=Male&age_group=18-30" \
     -H "Accept: application/json" \
     -H "Cookie: laravel_session=xyz"
```

**Example Response (200 OK):**
```json
{
  "total_patients": 42,
  "male_patients": 25,
  "female_patients": 17,
  "avg_age": 35.2,
  "samples_collected": 38,
  "referrals_made": 12
}
```

---

### 3. Patient Registration Trend

**Endpoint:**
```
GET /admin/analytics/api/registration-trend
```

**Method:** `GET`

**Authentication:** Required (Permission: `analytics_view`)

**Query Parameters:**
```json
{
  "period": "month",
  "date_from": "2025-01-01",
  "date_to": "2025-12-31",
  "gender": "Female",
  "age_group": "31-45",
  "country_id": 1,
  "state_id": 1,
  "district_id": 1,
  "taluka_id": 1
}
```

**Period Options:**
- `day` - Daily breakdown (last 30 days)
- `week` - Weekly breakdown (last 12 weeks)
- `month` - Monthly breakdown (last 12 months) [DEFAULT]
- `year` - Yearly breakdown (last 3 years)

**Response:**
```json
[
  {
    "period": "2025-01",
    "count": 15
  },
  {
    "period": "2025-02",
    "count": 22
  },
  {
    "period": "2025-03",
    "count": 18
  }
]
```

**Example Request:**
```bash
curl -X GET "https://yourdomain.com/admin/analytics/api/registration-trend?period=month&gender=Male" \
     -H "Accept: application/json" \
     -H "Cookie: laravel_session=xyz"
```

---

### 4. Demographics Data

**Endpoint:**
```
GET /admin/analytics/api/demographics
```

**Method:** `GET`

**Authentication:** Required (Permission: `analytics_view`)

**Query Parameters:**
```json
{
  "date_from": "2025-01-01",
  "date_to": "2025-12-31",
  "gender": "",
  "age_group": "",
  "country_id": 1,
  "state_id": 1,
  "district_id": 1,
  "taluka_id": 1
}
```

**Response:**
```json
{
  "gender": [
    {
      "sex": "Male",
      "count": 85
    },
    {
      "sex": "Female",
      "count": 65
    }
  ],
  "age_groups": [
    {
      "age_group": "0-17",
      "count": 12
    },
    {
      "age_group": "18-30",
      "count": 35
    },
    {
      "age_group": "31-45",
      "count": 48
    },
    {
      "age_group": "46-60",
      "count": 35
    },
    {
      "age_group": "60+",
      "count": 20
    }
  ],
  "top_villages": [
    {
      "village": "LANGDA TAANDA",
      "count": 25
    },
    {
      "village": "VILLAGE B",
      "count": 18
    },
    {
      "village": "VILLAGE C",
      "count": 15
    }
  ],
  "age_by_gender": [
    {
      "sex": "Male",
      "age_group": "18-30",
      "count": 20
    },
    {
      "sex": "Female",
      "age_group": "18-30",
      "count": 15
    }
  ]
}
```

**Example Request:**
```bash
curl -X GET "https://yourdomain.com/admin/analytics/api/demographics?country_id=1&state_id=1" \
     -H "Accept: application/json" \
     -H "Cookie: laravel_session=xyz"
```

---

### 5. Health Metrics Analysis

**Endpoint:**
```
GET /admin/analytics/api/health-metrics
```

**Method:** `GET`

**Authentication:** Required (Permission: `analytics_view`)

**Query Parameters:**
```json
{
  "date_from": "2025-01-01",
  "date_to": "2025-12-31",
  "gender": "Male",
  "age_group": "46-60",
  "country_id": 1,
  "state_id": 1,
  "district_id": 1,
  "taluka_id": 1
}
```

**Response:**
```json
{
  "bp_status": [
    {
      "status": "Normal",
      "count": 85
    },
    {
      "status": "Prehypertension",
      "count": 35
    },
    {
      "status": "Hypertension",
      "count": 25
    },
    {
      "status": "Invalid",
      "count": 5
    }
  ],
  "rbs_levels": [
    {
      "level": "Normal (<140)",
      "count": 95
    },
    {
      "level": "Prediabetic (140-199)",
      "count": 35
    },
    {
      "level": "Diabetic (≥200)",
      "count": 20
    }
  ],
  "bmi_analysis": [
    {
      "category": "Underweight",
      "count": 8
    },
    {
      "category": "Normal",
      "count": 65
    },
    {
      "category": "Overweight",
      "count": 52
    },
    {
      "category": "Obese",
      "count": 25
    }
  ]
}
```

**BP Status Categories:**
- `Normal`: Systolic < 120 AND Diastolic < 80
- `Prehypertension`: Systolic 120-139 OR Diastolic 80-89
- `Hypertension`: Systolic ≥ 140 OR Diastolic ≥ 90
- `Invalid`: BP format not recognized

**RBS Level Categories:**
- `Normal (<140)`: RBS < 140 mg/dL
- `Prediabetic (140-199)`: RBS 140-199 mg/dL
- `Diabetic (≥200)`: RBS ≥ 200 mg/dL

**BMI Categories:**
- `Underweight`: BMI < 18.5
- `Normal`: BMI 18.5-24.9
- `Overweight`: BMI 25-29.9
- `Obese`: BMI ≥ 30

**Example Request:**
```bash
curl -X GET "https://yourdomain.com/admin/analytics/api/health-metrics?age_group=46-60" \
     -H "Accept: application/json" \
     -H "Cookie: laravel_session=xyz"
```

---

### 6. Lab & Diagnostics Data

**Endpoint:**
```
GET /admin/analytics/api/lab-diagnostics
```

**Method:** `GET`

**Authentication:** Required (Permission: `analytics_view`)

**Query Parameters:**
```json
{
  "date_from": "2025-01-01",
  "date_to": "2025-12-31",
  "gender": "",
  "age_group": "",
  "country_id": 1,
  "state_id": 1,
  "district_id": 1,
  "taluka_id": 1
}
```

**Response:**
```json
{
  "diagnoses": [
    {
      "diagnosis": "JOINT PAIN 3DAYS",
      "count": 45
    },
    {
      "diagnosis": "MAUTH ULSER 3DAY",
      "count": 38
    },
    {
      "diagnosis": "ANOREXIA WITH ANEMIA 1MANTH",
      "count": 32
    }
  ],
  "lab_tests": [
    {
      "name": "Blood Test",
      "count": 85
    },
    {
      "name": "Urine Test",
      "count": 65
    },
    {
      "name": "X-Ray",
      "count": 28
    }
  ],
  "sample_status": [
    {
      "sample_collected": "Yes",
      "count": 120
    },
    {
      "sample_collected": "No",
      "count": 25
    },
    {
      "sample_collected": "NA",
      "count": 5
    }
  ]
}
```

**Example Request:**
```bash
curl -X GET "https://yourdomain.com/admin/analytics/api/lab-diagnostics" \
     -H "Accept: application/json" \
     -H "Cookie: laravel_session=xyz"
```

---

### 7. Treatment Analytics

**Endpoint:**
```
GET /admin/analytics/api/treatment-analytics
```

**Method:** `GET`

**Authentication:** Required (Permission: `analytics_view`)

**Query Parameters:**
```json
{
  "date_from": "2025-01-01",
  "date_to": "2025-12-31",
  "gender": "Female",
  "age_group": "31-45",
  "country_id": 1,
  "state_id": 1,
  "district_id": 1,
  "taluka_id": 1
}
```

**Response:**
```json
{
  "medications": [
    {
      "treatment": "Paracetamol 500mg",
      "count": 85
    },
    {
      "treatment": "Aspirin 300mg",
      "count": 62
    },
    {
      "treatment": "Metformin 500mg",
      "count": 48
    }
  ],
  "treatment_duration": [
    {
      "duration": "1 week",
      "count": 35
    },
    {
      "duration": "2 weeks",
      "count": 48
    },
    {
      "duration": "1 month",
      "count": 52
    },
    {
      "duration": "3 months",
      "count": 15
    }
  ]
}
```

**Example Request:**
```bash
curl -X GET "https://yourdomain.com/admin/analytics/api/treatment-analytics?gender=Female" \
     -H "Accept: application/json" \
     -H "Cookie: laravel_session=xyz"
```

---

### 8. Paginated Patient List

**Endpoint:**
```
GET /admin/analytics/api/patients
```

**Method:** `GET`

**Authentication:** Required (Permission: `analytics_view`)

**Query Parameters:**
```json
{
  "page": 1,
  "per_page": 20,
  "date_from": "2025-01-01",
  "date_to": "2025-12-31",
  "gender": "Male",
  "age_group": "18-30",
  "country_id": 1,
  "state_id": 1,
  "district_id": 1,
  "taluka_id": 1
}
```

**Response:**
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "serial_number": "PAT-2025-0001",
      "patient_name": "NILABAI RATHOD",
      "age": 55,
      "sex": "Female",
      "village": "LANGDA TAANDA",
      "date": "2025-01-01",
      "bp": "110/70",
      "rbs": null,
      "diagnosis": "JOINT PAIN 3DAYS",
      "treatment": "Rest and medication",
      "dosage": "2 times daily for 1 week",
      "complaints": "Joint pain",
      "known_conditions": null,
      "height": "160.50",
      "weight": "65.00",
      "mobile": "9876543210",
      "aadhar": "123456789012",
      "lab_tests": ["Blood Test", "X-Ray"],
      "sample_collected": "Yes",
      "referral_type": null,
      "referral_details": null,
      "notes": null,
      "country": {
        "id": 1,
        "name": "India"
      },
      "state": {
        "id": 1,
        "name": "Maharashtra"
      },
      "district": {
        "id": 1,
        "name": "Pune"
      },
      "taluka": {
        "id": 1,
        "name": "Haveli"
      },
      "campaignType": {
        "id": 1,
        "name": "Health Screening Camp"
      },
      "createdBy": {
        "id": 2,
        "name": "John Doe",
        "email": "john@example.com"
      },
      "created_at": "2025-01-01T10:30:00Z",
      "updated_at": "2025-01-01T10:30:00Z"
    }
  ],
  "first_page_url": "https://yourdomain.com/admin/analytics/api/patients?page=1",
  "from": 1,
  "last_page": 8,
  "last_page_url": "https://yourdomain.com/admin/analytics/api/patients?page=8",
  "next_page_url": "https://yourdomain.com/admin/analytics/api/patients?page=2",
  "path": "https://yourdomain.com/admin/analytics/api/patients",
  "per_page": 20,
  "prev_page_url": null,
  "to": 20,
  "total": 150
}
```

**Example Request:**
```bash
curl -X GET "https://yourdomain.com/admin/analytics/api/patients?page=1&per_page=20&gender=Male" \
     -H "Accept: application/json" \
     -H "Cookie: laravel_session=xyz"
```

---

## Location Cascade Endpoints

### 9. Get States by Country

**Endpoint:**
```
GET /admin/analytics/api/states/{country_id}
```

**Method:** `GET`

**Authentication:** Required (Permission: `analytics_view`)

**Path Parameters:**
```json
{
  "country_id": 1
}
```

**Response:**
```json
[
  {
    "id": 1,
    "name": "Maharashtra"
  },
  {
    "id": 2,
    "name": "Gujarat"
  },
  {
    "id": 3,
    "name": "Rajasthan"
  }
]
```

**Example Request:**
```bash
curl -X GET "https://yourdomain.com/admin/analytics/api/states/1" \
     -H "Accept: application/json" \
     -H "Cookie: laravel_session=xyz"
```

---

### 10. Get Districts by State

**Endpoint:**
```
GET /admin/analytics/api/districts/{state_id}
```

**Method:** `GET`

**Authentication:** Required (Permission: `analytics_view`)

**Path Parameters:**
```json
{
  "state_id": 1
}
```

**Response:**
```json
[
  {
    "id": 1,
    "name": "Pune"
  },
  {
    "id": 2,
    "name": "Mumbai"
  },
  {
    "id": 3,
    "name": "Nagpur"
  }
]
```

**Example Request:**
```bash
curl -X GET "https://yourdomain.com/admin/analytics/api/districts/1" \
     -H "Accept: application/json" \
     -H "Cookie: laravel_session=xyz"
```

---

### 11. Get Talukas by District

**Endpoint:**
```
GET /admin/analytics/api/talukas/{district_id}
```

**Method:** `GET`

**Authentication:** Required (Permission: `analytics_view`)

**Path Parameters:**
```json
{
  "district_id": 1
}
```

**Response:**
```json
[
  {
    "id": 1,
    "name": "Haveli"
  },
  {
    "id": 2,
    "name": "Purandar"
  },
  {
    "id": 3,
    "name": "Bhor"
  }
]
```

**Example Request:**
```bash
curl -X GET "https://yourdomain.com/admin/analytics/api/talukas/1" \
     -H "Accept: application/json" \
     -H "Cookie: laravel_session=xyz"
```

---

## Error Responses

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 403 Forbidden (Permission Denied)
```json
{
  "message": "Unauthorized action."
}
```

### 404 Not Found
```json
{
  "message": "Route not found."
}
```

### 422 Unprocessable Entity
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": [
      "Error message"
    ]
  }
}
```

### 500 Server Error
```json
{
  "message": "Server Error",
  "exception": "Exception message"
}
```

---

## Filter Query Parameters Reference

### Available Filter Parameters

| Parameter | Type | Options | Example |
|-----------|------|---------|---------|
| `date_from` | string (YYYY-MM-DD) | Any valid date | `2025-01-01` |
| `date_to` | string (YYYY-MM-DD) | Any valid date | `2025-12-31` |
| `year` | integer | 2024, 2025, etc. | `2025` |
| `month` | integer | 1-12 | `3` |
| `gender` | string | `Male`, `Female`, `Other` | `Male` |
| `age_group` | string | `0-17`, `18-30`, `31-45`, `46-60`, `60+` | `31-45` |
| `country_id` | integer | Country ID | `1` |
| `state_id` | integer | State ID | `1` |
| `district_id` | integer | District ID | `1` |
| `taluka_id` | integer | Taluka ID | `1` |
| `campaign_type_id` | integer | Campaign Type ID | `1` |
| `period` | string | `day`, `week`, `month`, `year` | `month` |
| `page` | integer | >= 1 | `1` |
| `per_page` | integer | 1-100 | `20` |

---

## Complete Example Requests

### Example 1: Get All Statistics
```bash
curl -X GET "https://yourdomain.com/admin/analytics/api/stats" \
     -H "Accept: application/json" \
     -H "Cookie: laravel_session=abc123xyz"
```

### Example 2: Filtered Demographics
```bash
curl -X GET "https://yourdomain.com/admin/analytics/api/demographics?country_id=1&state_id=1&gender=Male" \
     -H "Accept: application/json" \
     -H "Cookie: laravel_session=abc123xyz"
```

### Example 3: Complex Date Range Filter
```bash
curl -X GET "https://yourdomain.com/admin/analytics/api/patients?page=1&per_page=20&date_from=2025-01-01&date_to=2025-03-31&age_group=31-45&gender=Female" \
     -H "Accept: application/json" \
     -H "Cookie: laravel_session=abc123xyz"
```

### Example 4: Cascade Location Selection
```bash
# Step 1: Get states for country 1
curl -X GET "https://yourdomain.com/admin/analytics/api/states/1" \
     -H "Accept: application/json" \
     -H "Cookie: laravel_session=abc123xyz"

# Step 2: Get districts for state 1
curl -X GET "https://yourdomain.com/admin/analytics/api/districts/1" \
     -H "Accept: application/json" \
     -H "Cookie: laravel_session=abc123xyz"

# Step 3: Get talukas for district 1
curl -X GET "https://yourdomain.com/admin/analytics/api/talukas/1" \
     -H "Accept: application/json" \
     -H "Cookie: laravel_session=abc123xyz"

# Step 4: Get filtered data
curl -X GET "https://yourdomain.com/admin/analytics/api/demographics?country_id=1&state_id=1&district_id=1&taluka_id=1" \
     -H "Accept: application/json" \
     -H "Cookie: laravel_session=abc123xyz"
```

---

## Rate Limiting

Currently, no rate limiting is enforced. Implement rate limiting if needed:

```php
// In routes/web.php
Route::middleware(['auth', 'throttle:60,1'])->group(function () {
    // API routes with rate limit of 60 requests per minute
});
```

---

## CORS Support

If consuming this API from a different domain, enable CORS in `config/cors.php`:

```php
'allowed_origins' => ['https://yourdomain.com'],
'allowed_methods' => ['GET'],
'allowed_headers' => ['Content-Type', 'Authorization', 'Accept'],
'exposed_headers' => [],
'max_age' => 0,
'supports_credentials' => true,
```

---

## Pagination Details

All paginated endpoints return:
```json
{
  "current_page": 1,
  "data": [...],
  "first_page_url": "...",
  "from": 1,
  "last_page": 5,
  "last_page_url": "...",
  "next_page_url": "...",
  "path": "...",
  "per_page": 20,
  "prev_page_url": null,
  "to": 20,
  "total": 100
}
```

---

## Testing the API with Postman

1. **Create a Postman Collection**
2. **Set Base URL**: `https://yourdomain.com/admin/analytics`
3. **Add Cookie**: Use Postman's cookie manager to add Laravel session
4. **Create Requests**:
   - GET `/api/stats`
   - GET `/api/demographics`
   - GET `/api/health-metrics`
   - GET `/api/lab-diagnostics`
   - GET `/api/treatment-analytics`
   - GET `/api/patients?page=1`
   - GET `/api/states/{country_id}`
   - GET `/api/districts/{state_id}`
   - GET `/api/talukas/{district_id}`

---

## Implementation Notes

### Middleware Used
- `auth` - Requires user to be authenticated
- `permission:analytics_view` - Requires specific permission

### Database Queries Optimized
- Indexes on date, sex, country_id, state_id, district_id, taluka_id
- Eager loading of relationships
- Query result caching (optional)

### Performance Tips
- Use date filters to limit data size
- Implement pagination for large datasets
- Cache API responses (5 minutes recommended)
- Use CDN for static assets

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0 | 2025-01-28 | Initial release |

---

## Support

For issues or questions about the API, contact the development team or check the admin panel logs.

