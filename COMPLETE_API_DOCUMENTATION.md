# Complete API Documentation - Borderless Analytics

**Base URL**: `http://localhost:8001` (or your server URL)
**Default Credentials**:
- Email: `admin@admin.com`
- Password: `password`

---

## 🔐 Authentication Endpoints

### 1. Login - Get Session Token

**Endpoint**: `POST /api/auth/login`

**Request**:
```json
{
  "email": "admin@admin.com",
  "password": "password"
}
```

**Headers**:
```
Content-Type: application/json
Accept: application/json
```

**Response (200 OK)**:
```json
{
  "success": true,
  "message": "Authenticated successfully",
  "user": {
    "id": 1,
    "name": "Admin",
    "email": "admin@admin.com",
    "role": "admin"
  }
}
```

**Response (401 Unauthorized)**:
```json
{
  "success": false,
  "message": "Invalid credentials",
  "errors": {
    "email": ["The provided credentials are invalid."]
  }
}
```

**cURL Example**:
```bash
curl -X POST http://localhost:8001/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "admin@admin.com",
    "password": "password"
  }'
```

**Postman**:
- Method: POST
- URL: http://localhost:8001/api/auth/login
- Body (raw, JSON):
  ```json
  {
    "email": "admin@admin.com",
    "password": "password"
  }
  ```
- Headers: Content-Type: application/json

---

### 2. Check Authentication Status

**Endpoint**: `GET /api/auth/check`

**Headers**:
```
Accept: application/json
```

**Response (Authenticated - 200)**:
```json
{
  "authenticated": true,
  "user": {
    "id": 1,
    "name": "Admin",
    "email": "admin@admin.com"
  }
}
```

**Response (Not Authenticated - 401)**:
```json
{
  "authenticated": false,
  "message": "Not authenticated"
}
```

**cURL Example**:
```bash
curl -X GET http://localhost:8001/api/auth/check \
  -H "Accept: application/json" \
  -b "cookies.txt"
```

---

### 3. Get Current User Info (Protected)

**Endpoint**: `GET /api/auth/me`

**Headers**:
```
Accept: application/json
Cookie: XSRF-TOKEN=...; laravel_session=...
```

**Response (200 OK)**:
```json
{
  "success": true,
  "user": {
    "id": 1,
    "name": "Admin",
    "email": "admin@admin.com",
    "role": "admin"
  }
}
```

**Response (401 Unauthorized)**:
```json
{
  "success": false,
  "message": "Not authenticated"
}
```

---

### 4. Logout (Protected)

**Endpoint**: `POST /api/auth/logout`

**Headers**:
```
Accept: application/json
Cookie: XSRF-TOKEN=...; laravel_session=...
```

**Response (200 OK)**:
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

---

## 📊 Analytics - Statistics Endpoints

### 5. Get Patient Statistics

**Endpoint**: `GET /admin/analytics/api/stats`

**Query Parameters** (Optional):
| Parameter | Type | Values |
|-----------|------|--------|
| gender | string | Male, Female, Other |
| age_group | string | 0-17, 18-30, 31-45, 46-60, 60+ |
| date_from | date | YYYY-MM-DD |
| date_to | date | YYYY-MM-DD |

**Headers**:
```
Accept: application/json
Cookie: laravel_session=...
```

**Response (200 OK)**:
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

**cURL Examples**:
```bash
# No filters
curl -X GET http://localhost:8001/admin/analytics/api/stats \
  -H "Accept: application/json" \
  -b "cookies.txt"

# With gender filter
curl -X GET "http://localhost:8001/admin/analytics/api/stats?gender=Male" \
  -H "Accept: application/json" \
  -b "cookies.txt"

# With multiple filters
curl -X GET "http://localhost:8001/admin/analytics/api/stats?gender=Female&age_group=31-45" \
  -H "Accept: application/json" \
  -b "cookies.txt"

# With date range
curl -X GET "http://localhost:8001/admin/analytics/api/stats?date_from=2025-01-01&date_to=2025-03-31" \
  -H "Accept: application/json" \
  -b "cookies.txt"
```

---

## 👥 Analytics - Demographics Endpoints

### 6. Get Demographics Data

**Endpoint**: `GET /admin/analytics/api/demographics`

**Query Parameters** (Optional):
| Parameter | Type | Values |
|-----------|------|--------|
| gender | string | Male, Female, Other |
| age_group | string | 0-17, 18-30, 31-45, 46-60, 60+ |
| date_from | date | YYYY-MM-DD |
| date_to | date | YYYY-MM-DD |

**Response (200 OK)**:
```json
{
  "gender": [
    { "sex": "Male", "count": 85 },
    { "sex": "Female", "count": 65 }
  ],
  "age_groups": [
    { "age_group": "0-17", "count": 10 },
    { "age_group": "18-30", "count": 35 },
    { "age_group": "31-45", "count": 50 },
    { "age_group": "46-60", "count": 35 },
    { "age_group": "60+", "count": 20 }
  ],
  "top_villages": [
    { "village": "Village A", "count": 25 },
    { "village": "Village B", "count": 20 },
    ...
  ],
  "age_by_gender": [
    { "sex": "Male", "age_group": "18-30", "count": 20 },
    ...
  ]
}
```

**cURL Example**:
```bash
curl -X GET http://localhost:8001/admin/analytics/api/demographics \
  -H "Accept: application/json" \
  -b "cookies.txt"
```

---

## 🏥 Analytics - Health Metrics Endpoints

### 7. Get Health Metrics

**Endpoint**: `GET /admin/analytics/api/health-metrics`

**Query Parameters** (Optional):
| Parameter | Type | Values |
|-----------|------|--------|
| gender | string | Male, Female, Other |
| age_group | string | 0-17, 18-30, 31-45, 46-60, 60+ |
| date_from | date | YYYY-MM-DD |
| date_to | date | YYYY-MM-DD |

**Response (200 OK)**:
```json
{
  "bp_status": [
    { "bp_status": "Normal", "count": 100 },
    { "bp_status": "Prehypertension", "count": 30 },
    { "bp_status": "Hypertension", "count": 20 }
  ],
  "rbs_levels": [
    { "rbs_status": "Normal (<140)", "count": 80 },
    { "rbs_status": "Prediabetic (140-199)", "count": 50 },
    { "rbs_status": "Diabetic (≥200)", "count": 20 }
  ],
  "bmi_analysis": [
    { "bmi_status": "Underweight", "count": 5 },
    { "bmi_status": "Normal", "count": 85 },
    { "bmi_status": "Overweight", "count": 40 },
    { "bmi_status": "Obese", "count": 20 }
  ]
}
```

**cURL Example**:
```bash
curl -X GET http://localhost:8001/admin/analytics/api/health-metrics \
  -H "Accept: application/json" \
  -b "cookies.txt"
```

---

## 👤 Analytics - Patient List Endpoints

### 8. Get Patients List (Paginated)

**Endpoint**: `GET /admin/analytics/api/patients`

**Query Parameters**:
| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| page | integer | 1 | Page number |
| per_page | integer | 15 | Records per page |
| gender | string | - | Filter by gender |
| age_group | string | - | Filter by age group |
| date_from | date | - | Filter from date |
| date_to | date | - | Filter to date |

**Response (200 OK)**:
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "patient_id": "PAT-2025-0001",
      "name": "John Doe",
      "sex": "Male",
      "age": 35,
      "village": "Village A",
      "date": "2025-01-15T10:30:00Z"
    },
    ...
  ],
  "first_page_url": "http://localhost:8001/admin/analytics/api/patients?page=1",
  "from": 1,
  "last_page": 10,
  "last_page_url": "http://localhost:8001/admin/analytics/api/patients?page=10",
  "links": [...],
  "next_page_url": "http://localhost:8001/admin/analytics/api/patients?page=2",
  "path": "http://localhost:8001/admin/analytics/api/patients",
  "per_page": 15,
  "prev_page_url": null,
  "to": 15,
  "total": 150
}
```

**cURL Examples**:
```bash
# First page
curl -X GET "http://localhost:8001/admin/analytics/api/patients?page=1&per_page=10" \
  -H "Accept: application/json" \
  -b "cookies.txt"

# With filters
curl -X GET "http://localhost:8001/admin/analytics/api/patients?gender=Male&age_group=31-45&page=1&per_page=10" \
  -H "Accept: application/json" \
  -b "cookies.txt"
```

---

## 🗺️ Analytics - Location Cascade Endpoints

### 9. Get States for Country

**Endpoint**: `GET /admin/analytics/api/states/{country}`

**Path Parameters**:
- `country` (integer): Country ID (usually 1 for India)

**Response (200 OK)**:
```json
[
  { "id": 1, "name": "State 1" },
  { "id": 2, "name": "State 2" },
  ...
]
```

**cURL Example**:
```bash
curl -X GET http://localhost:8001/admin/analytics/api/states/1 \
  -H "Accept: application/json" \
  -b "cookies.txt"
```

---

### 10. Get Districts for State

**Endpoint**: `GET /admin/analytics/api/districts/{state}`

**Path Parameters**:
- `state` (integer): State ID

**Response (200 OK)**:
```json
[
  { "id": 1, "name": "District 1" },
  { "id": 2, "name": "District 2" },
  ...
]
```

**cURL Example**:
```bash
curl -X GET http://localhost:8001/admin/analytics/api/districts/1 \
  -H "Accept: application/json" \
  -b "cookies.txt"
```

---

### 11. Get Talukas for District

**Endpoint**: `GET /admin/analytics/api/talukas/{district}`

**Path Parameters**:
- `district` (integer): District ID

**Response (200 OK)**:
```json
[
  { "id": 1, "name": "Taluka 1" },
  { "id": 2, "name": "Taluka 2" },
  ...
]
```

**cURL Example**:
```bash
curl -X GET http://localhost:8001/admin/analytics/api/talukas/1 \
  -H "Accept: application/json" \
  -b "cookies.txt"
```

---

## 🧪 Testing with Postman

### Step 1: Import Collection
1. Create new collection: "Borderless Analytics API"
2. Create folder: "Authentication"
3. Create folder: "Analytics"

### Step 2: Set Environment Variable
1. New Environment: "Local"
2. Add variable:
   - Name: `base_url`
   - Value: `http://localhost:8001`

### Step 3: Create Requests

**Request 1: Login**
```
Method: POST
URL: {{base_url}}/api/auth/login
Body (raw, JSON):
{
  "email": "admin@admin.com",
  "password": "password"
}
Headers:
  Content-Type: application/json
  Accept: application/json
```

**Request 2: Check Auth**
```
Method: GET
URL: {{base_url}}/api/auth/check
Headers:
  Accept: application/json
```

**Request 3: Stats**
```
Method: GET
URL: {{base_url}}/admin/analytics/api/stats
Headers:
  Accept: application/json
```

**Request 4: Demographics**
```
Method: GET
URL: {{base_url}}/admin/analytics/api/demographics
Headers:
  Accept: application/json
```

**Request 5: Health Metrics**
```
Method: GET
URL: {{base_url}}/admin/analytics/api/health-metrics
Headers:
  Accept: application/json
```

**Request 6: Patients**
```
Method: GET
URL: {{base_url}}/admin/analytics/api/patients?page=1&per_page=10
Headers:
  Accept: application/json
```

**Request 7: States**
```
Method: GET
URL: {{base_url}}/admin/analytics/api/states/1
Headers:
  Accept: application/json
```

**Request 8: Districts**
```
Method: GET
URL: {{base_url}}/admin/analytics/api/districts/1
Headers:
  Accept: application/json
```

**Request 9: Talukas**
```
Method: GET
URL: {{base_url}}/admin/analytics/api/talukas/1
Headers:
  Accept: application/json
```

---

## 📋 Complete Endpoint Summary

| # | Endpoint | Method | Auth | Purpose |
|---|----------|--------|------|---------|
| 1 | `/api/auth/login` | POST | No | Login with credentials |
| 2 | `/api/auth/check` | GET | No | Check if authenticated |
| 3 | `/api/auth/me` | GET | Yes | Get current user |
| 4 | `/api/auth/logout` | POST | Yes | Logout |
| 5 | `/admin/analytics/api/stats` | GET | Yes | Get KPI statistics |
| 6 | `/admin/analytics/api/demographics` | GET | Yes | Get demographics |
| 7 | `/admin/analytics/api/health-metrics` | GET | Yes | Get health metrics |
| 8 | `/admin/analytics/api/patients` | GET | Yes | Get patient list |
| 9 | `/admin/analytics/api/states/{id}` | GET | Yes | Get states |
| 10 | `/admin/analytics/api/districts/{id}` | GET | Yes | Get districts |
| 11 | `/admin/analytics/api/talukas/{id}` | GET | Yes | Get talukas |

---

## 🔧 Common Query Filters

Available for: `/api/stats`, `/api/demographics`, `/api/health-metrics`, `/api/patients`

```
?gender=Male
?gender=Female
?gender=Other
?age_group=0-17
?age_group=18-30
?age_group=31-45
?age_group=46-60
?age_group=60+
?date_from=2025-01-01
?date_to=2025-03-31
?page=2
?per_page=20
```

---

## ✅ Testing Checklist

- [ ] Login endpoint returns success
- [ ] Check endpoint confirms authentication
- [ ] Stats endpoint returns KPI data
- [ ] Demographics endpoint returns demographic data
- [ ] Health metrics endpoint returns health data
- [ ] Patients endpoint returns paginated list
- [ ] All endpoints work with filters
- [ ] All endpoints require authentication (except login/check)
- [ ] Error responses are clear
- [ ] Pagination works correctly

---

Last Updated: December 29, 2025
