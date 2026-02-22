# Analytics API Testing Guide

## Quick Start

### 1. Using Postman (Recommended)

#### Step 1: Import Collection
1. Download/Copy `analytics-api.postman_collection.json`
2. Open Postman
3. Click **Import** → Select the JSON file
4. Collection **"Patient Health Analytics API"** is imported

#### Step 2: Set Base URL
1. Click the **Variables** tab
2. Set `base_url` = `https://yourdomain.com` (or `http://localhost:8000`)
3. Save

#### Step 3: Authenticate
1. Before testing any API, you must login first
2. Use the **Authentication & Setup** folder
3. Run **"Login (Get Session)"** with valid admin credentials
4. This creates a session cookie that's used for all subsequent requests

#### Step 4: Test Endpoints
Now you can test any endpoint in the collection. Postman will automatically include the session cookie.

---

## Testing Without Postman

### Using cURL (Command Line)

#### Step 1: Login and Get Session
```bash
# Login to get session cookie
curl -c cookies.txt \
  -d "email=admin@example.com&password=password123" \
  https://yourdomain.com/login
```

This saves the session cookie to `cookies.txt`

#### Step 2: Use Cookie for API Requests
```bash
# Use the saved cookie for API calls
curl -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/stats
```

#### Complete Example: Get Stats
```bash
# 1. Login
curl -c cookies.txt \
  -d "email=admin@example.com&password=password123" \
  https://yourdomain.com/login

# 2. Get all patient statistics
curl -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/stats

# 3. Get stats filtered by gender
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/stats?gender=Male"

# 4. Get stats with location filter
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/stats?country_id=1&state_id=1"
```

---

## Testing from Browser Console

### JavaScript Fetch API

```javascript
// First, login (usually already done in browser)
// Then test API endpoints:

// 1. Get all stats
fetch('/admin/analytics/api/stats', {
  headers: { 'Accept': 'application/json' }
})
.then(response => response.json())
.then(data => console.log('Stats:', data))
.catch(error => console.error('Error:', error));

// 2. Get stats with filters
const params = new URLSearchParams({
  gender: 'Male',
  age_group: '31-45'
});

fetch(`/admin/analytics/api/stats?${params}`, {
  headers: { 'Accept': 'application/json' }
})
.then(response => response.json())
.then(data => console.log('Filtered Stats:', data))
.catch(error => console.error('Error:', error));

// 3. Get demographics
fetch('/admin/analytics/api/demographics', {
  headers: { 'Accept': 'application/json' }
})
.then(response => response.json())
.then(data => console.log('Demographics:', data))
.catch(error => console.error('Error:', error));

// 4. Get paginated patients
fetch('/admin/analytics/api/patients?page=1&per_page=20', {
  headers: { 'Accept': 'application/json' }
})
.then(response => response.json())
.then(data => console.log('Patients:', data))
.catch(error => console.error('Error:', error));

// 5. Get location cascade
fetch('/admin/analytics/api/states/1', {
  headers: { 'Accept': 'application/json' }
})
.then(response => response.json())
.then(data => console.log('States:', data))
.catch(error => console.error('Error:', error));
```

---

## Test Cases

### Test Case 1: Basic Statistics
**Objective:** Verify KPI endpoint returns correct metrics

**Request:**
```
GET /admin/analytics/api/stats
```

**Expected Response (200 OK):**
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

**Validation:**
- Status code is 200
- All fields are present
- Numbers are positive integers (except avg_age which can be decimal)

---

### Test Case 2: Filter by Gender
**Objective:** Verify gender filter works correctly

**Request:**
```
GET /admin/analytics/api/stats?gender=Female
```

**Expected Response:**
- Returns only female patient statistics
- `female_patients` should be equal to `total_patients`
- `male_patients` should be 0

---

### Test Case 3: Location Cascade
**Objective:** Verify location cascade works end-to-end

**Step 1: Get States**
```
GET /admin/analytics/api/states/1
```
Expected: Array of states

**Step 2: Get Districts from first state (ID=1)**
```
GET /admin/analytics/api/districts/1
```
Expected: Array of districts

**Step 3: Get Talukas from first district (ID=1)**
```
GET /admin/analytics/api/talukas/1
```
Expected: Array of talukas

**Step 4: Get filtered demographics**
```
GET /admin/analytics/api/demographics?country_id=1&state_id=1&district_id=1&taluka_id=1
```
Expected: Demographics filtered to that taluka

---

### Test Case 4: Date Range Filter
**Objective:** Verify date filtering works

**Request:**
```
GET /admin/analytics/api/stats?date_from=2025-01-01&date_to=2025-03-31
```

**Expected Response:**
- Should return only patients from Jan-Mar 2025
- Total count should be less than or equal to unfiltered count

---

### Test Case 5: Demographics Data
**Objective:** Verify demographics endpoint returns all distributions

**Request:**
```
GET /admin/analytics/api/demographics
```

**Expected Response (200 OK):**
```json
{
  "gender": [
    { "sex": "Male", "count": ... },
    { "sex": "Female", "count": ... }
  ],
  "age_groups": [
    { "age_group": "0-17", "count": ... },
    ...
  ],
  "top_villages": [ ... ],
  "age_by_gender": [ ... ]
}
```

**Validation:**
- All 4 distributions are present
- Each has proper structure
- Counts are logical

---

### Test Case 6: Health Metrics
**Objective:** Verify health metrics returns BP, RBS, and BMI data

**Request:**
```
GET /admin/analytics/api/health-metrics
```

**Expected Response:**
```json
{
  "bp_status": [ ... ],
  "rbs_levels": [ ... ],
  "bmi_analysis": [ ... ]
}
```

**Validation:**
- All 3 metrics are present
- BP has "Normal", "Prehypertension", "Hypertension"
- RBS has "Normal (<140)", "Prediabetic (140-199)", "Diabetic (≥200)"
- BMI has "Underweight", "Normal", "Overweight", "Obese"

---

### Test Case 7: Patient List Pagination
**Objective:** Verify pagination works correctly

**Request:**
```
GET /admin/analytics/api/patients?page=1&per_page=20
```

**Expected Response:**
```json
{
  "current_page": 1,
  "data": [ ... ],
  "last_page": 8,
  "total": 150,
  ...
}
```

**Validation:**
- `current_page` = 1
- `data` array has up to 20 items
- `total` shows total records
- Has `next_page_url` and `prev_page_url` fields

**Test Pagination:**
```
GET /admin/analytics/api/patients?page=2&per_page=20
```

---

### Test Case 8: Permission Denied
**Objective:** Verify non-authenticated users get 403 error

**Steps:**
1. Logout from browser
2. Try to access any API endpoint
3. Expected: 403 Forbidden or redirect to login

---

### Test Case 9: Treatment Analytics
**Objective:** Verify treatment data includes medications and duration

**Request:**
```
GET /admin/analytics/api/treatment-analytics
```

**Expected Response:**
```json
{
  "medications": [
    { "treatment": "...", "count": ... }
  ],
  "treatment_duration": [
    { "duration": "1 week", "count": ... }
  ]
}
```

---

### Test Case 10: Lab Diagnostics
**Objective:** Verify lab data includes diagnoses, tests, and samples

**Request:**
```
GET /admin/analytics/api/lab-diagnostics
```

**Expected Response:**
```json
{
  "diagnoses": [ ... ],
  "lab_tests": [ ... ],
  "sample_status": [ ... ]
}
```

---

## Common Errors & Solutions

### Error: 401 Unauthorized
**Cause:** Not logged in
**Solution:**
1. Login first using `/login` endpoint
2. Ensure cookies are enabled
3. Check XSRF token if using token auth

### Error: 403 Forbidden
**Cause:** User doesn't have `analytics_view` permission
**Solution:**
1. Login as Admin user
2. Or assign `analytics_view` permission to user's role
3. Run seeder: `php artisan db:seed --class=PermissionSeeder`

### Error: 404 Not Found
**Cause:** Wrong API endpoint
**Solution:**
1. Check endpoint URL spelling
2. Verify using route list: `php artisan route:list | grep analytics`
3. Clear route cache: `php artisan route:cache`

### Error: 422 Validation Error
**Cause:** Invalid filter values
**Solution:**
1. Check filter values match expected options
2. Gender: Male, Female, Other
3. Age group: 0-17, 18-30, 31-45, 46-60, 60+
4. Ensure IDs exist in database

### Empty Response Array
**Cause:** No data matches filters
**Solution:**
1. Try removing some filters
2. Check patient data exists in database
3. Verify date range is correct

---

## Performance Testing

### Load Test Example
Test how many concurrent requests the API can handle:

```bash
# Using Apache Bench (ab)
ab -n 100 -c 10 \
  -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/stats

# Using wrk (advanced)
wrk -t4 -c100 -d30s \
  --script=analytics-api-test.lua \
  https://yourdomain.com/admin/analytics/api/stats
```

---

## Database Query Performance

### Check Slow Queries
Enable MySQL slow query log and check:

```sql
SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 0.5;

-- Check slow queries
SHOW VARIABLES WHERE variable_name LIKE '%slow%';
```

### Optimize Analytics Queries
```sql
-- Add indexes (already done in migration)
ALTER TABLE patients ADD INDEX idx_date_sex (date, sex);
ALTER TABLE patients ADD INDEX idx_locations (country_id, state_id, district_id, taluka_id);

-- Check index usage
EXPLAIN SELECT * FROM patients WHERE date >= '2025-01-01';
```

---

## API Documentation

Comprehensive API documentation available in: **ANALYTICS_API_DOCUMENTATION.md**

Includes:
- Authentication requirements
- All 11 API endpoints
- Request/response examples
- Filter parameter reference
- Error codes and messages
- Complete cURL examples

---

## Next Steps

1. ✅ Import Postman collection
2. ✅ Set base URL variable
3. ✅ Run login request
4. ✅ Test each endpoint
5. ✅ Verify response data
6. ✅ Test with different filters
7. ✅ Check pagination
8. ✅ Validate permissions

---

## Support

For issues:
1. Check error message
2. Verify authentication
3. Check permissions are assigned
4. Review API documentation
5. Check browser console for JS errors
6. Review Laravel logs in `storage/logs/`

