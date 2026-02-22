# Patient Health Analytics API - Complete Documentation

## 📋 Overview

The Patient Health Analytics Dashboard comes with a comprehensive REST API that provides real-time access to all patient health data, statistics, and analytics. All endpoints are protected by Laravel's authentication and permission system.

---

## 📚 Documentation Files

### 1. **ANALYTICS_API_DOCUMENTATION.md** ⭐ START HERE
Complete API reference with:
- All 11 endpoints with detailed specifications
- Request/response examples for every endpoint
- Filter parameter reference
- Authentication requirements
- Error codes and responses
- Performance tips

**Size:** ~800 lines | **Best for:** API developers, backend integration

### 2. **API_TESTING_GUIDE.md** 🧪 FOR QA/TESTING
Step-by-step guide for testing the API:
- How to use Postman
- How to use cURL
- How to use browser console
- 10 complete test cases
- Error handling and solutions
- Load testing examples

**Size:** ~400 lines | **Best for:** QA engineers, testing, verification

### 3. **CURL_EXAMPLES.md** 💻 FOR DEVELOPERS
25+ ready-to-use cURL command examples:
- Login and session setup
- Every endpoint with examples
- Single and multiple filters
- Location cascade workflow
- Advanced usage and piping
- Error handling
- Performance monitoring

**Size:** ~400 lines | **Best for:** Backend developers, command-line users

### 4. **analytics-api.postman_collection.json** 🔧 FOR POSTMAN
Pre-built Postman collection with:
- All 11 endpoints configured
- 30+ ready-to-use requests
- Filter examples for each endpoint
- Environment variables
- Authentication setup
- One-click testing

**Import:** Postman → File → Import → Select JSON file

---

## 🚀 Quick Start

### Option 1: Using Postman (Recommended)
1. Open Postman
2. Import `analytics-api.postman_collection.json`
3. Set `base_url` variable to your domain
4. Run "Login" request under "Authentication & Setup"
5. Start testing any endpoint

### Option 2: Using cURL
```bash
# Login
curl -c cookies.txt \
  -d "email=admin@example.com&password=password123" \
  https://yourdomain.com/login

# Get statistics
curl -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/stats
```

### Option 3: Using JavaScript (Browser Console)
```javascript
fetch('/admin/analytics/api/stats', {
  headers: { 'Accept': 'application/json' }
})
.then(r => r.json())
.then(data => console.log(data));
```

---

## 🔐 Authentication

### Required
- User must be logged in (Laravel session)
- User must have `analytics_view` permission
- CSRF token (automatically handled by Laravel)

### Who Can Access?
- ✅ Admin users
- ✅ Users with "Analytics Viewer" role
- ❌ Data Entry users
- ❌ Regular users

### Assign Permission to User
```php
$user->roles()->attach(Role::where('name', 'analytics_viewer')->first());
// OR
$user->givePermission('analytics_view');
```

---

## 📊 API Endpoints Summary

| Endpoint | Method | Purpose | Returns |
|----------|--------|---------|---------|
| `/api/stats` | GET | KPI metrics (totals, averages) | JSON object |
| `/api/registration-trend` | GET | Patient registration over time | Array of periods |
| `/api/demographics` | GET | Gender, age, village distributions | JSON with 4 distributions |
| `/api/health-metrics` | GET | BP, RBS, BMI analysis | JSON with 3 health data |
| `/api/lab-diagnostics` | GET | Diagnoses, lab tests, samples | JSON with 3 categories |
| `/api/treatment-analytics` | GET | Medications, treatment duration | JSON with medications & duration |
| `/api/patients` | GET | Paginated patient list | Paginated JSON |
| `/api/states/{id}` | GET | States for country | JSON array |
| `/api/districts/{id}` | GET | Districts for state | JSON array |
| `/api/talukas/{id}` | GET | Talukas for district | JSON array |

**Base URL:** `https://yourdomain.com/admin/analytics`

---

## 🔗 Filters Available

All analytics endpoints support these optional query parameters:

| Filter | Type | Values | Example |
|--------|------|--------|---------|
| `date_from` | Date | YYYY-MM-DD | `2025-01-01` |
| `date_to` | Date | YYYY-MM-DD | `2025-12-31` |
| `gender` | String | Male, Female, Other | `Male` |
| `age_group` | String | 0-17, 18-30, 31-45, 46-60, 60+ | `31-45` |
| `country_id` | Integer | Country ID | `1` |
| `state_id` | Integer | State ID | `1` |
| `district_id` | Integer | District ID | `1` |
| `taluka_id` | Integer | Taluka ID | `1` |
| `campaign_type_id` | Integer | Campaign Type ID | `1` |
| `period` | String | day, week, month, year | `month` |
| `page` | Integer | >= 1 | `1` |
| `per_page` | Integer | 1-100 | `20` |

**All filters are optional and can be combined.**

---

## 📈 Response Format

### Success Response (200 OK)
```json
{
  "data_field_1": "value",
  "data_field_2": 123,
  "nested_object": {
    "key": "value"
  }
}
```

### Error Response (4xx, 5xx)
```json
{
  "message": "Error description",
  "errors": {
    "field": ["Error message"]
  }
}
```

### Paginated Response
```json
{
  "current_page": 1,
  "data": [...],
  "last_page": 10,
  "total": 200,
  "per_page": 20
}
```

---

## 💡 Usage Examples

### Example 1: Get Overall Statistics
```
GET /api/stats
→ Returns: total patients, male/female count, average age, samples, referrals
```

### Example 2: Get Male Patients Only
```
GET /api/stats?gender=Male
→ Returns: Statistics filtered to male patients only
```

### Example 3: Get Data for Date Range
```
GET /api/stats?date_from=2025-01-01&date_to=2025-03-31
→ Returns: Q1 2025 statistics only
```

### Example 4: Get Demographics by Location
```
GET /api/demographics?country_id=1&state_id=1&district_id=1
→ Returns: Demographics for specific district
```

### Example 5: Get Patient List with Filters
```
GET /api/patients?page=1&per_page=20&gender=Female&age_group=31-45
→ Returns: 20 female patients aged 31-45, page 1
```

### Example 6: Location Cascade
```
Step 1: GET /api/states/1 → Get states for country 1
Step 2: GET /api/districts/1 → Get districts for state 1
Step 3: GET /api/talukas/1 → Get talukas for district 1
```

---

## 🛠️ Integration Examples

### Frontend Framework (JavaScript/React)
```javascript
async function getAnalyticsData(filters = {}) {
  const params = new URLSearchParams(filters);
  const response = await fetch(
    `/admin/analytics/api/stats?${params}`,
    { headers: { 'Accept': 'application/json' } }
  );
  return response.json();
}

// Usage
const stats = await getAnalyticsData({
  gender: 'Male',
  age_group: '31-45'
});
console.log(stats.total_patients);
```

### PHP Backend
```php
$client = new \GuzzleHttp\Client([
  'base_uri' => 'https://yourdomain.com',
  'cookies' => true
]);

// First login
$client->post('/login', [
  'form_params' => [
    'email' => 'admin@example.com',
    'password' => 'password'
  ]
]);

// Then get data
$response = $client->get('/admin/analytics/api/stats?gender=Male');
$data = json_decode($response->getBody(), true);
```

### Python Backend
```python
import requests

session = requests.Session()

# Login
session.post('https://yourdomain.com/login', data={
    'email': 'admin@example.com',
    'password': 'password'
})

# Get data
response = session.get(
    'https://yourdomain.com/admin/analytics/api/stats',
    params={'gender': 'Male'},
    headers={'Accept': 'application/json'}
)
data = response.json()
```

---

## ⚠️ Error Codes

| Code | Meaning | Cause | Solution |
|------|---------|-------|----------|
| 200 | OK | Request successful | None |
| 301 | Redirect | Follow redirect | Check URL |
| 401 | Unauthorized | Not logged in | Login first |
| 403 | Forbidden | No permission | Check user role |
| 404 | Not Found | Endpoint doesn't exist | Check URL spelling |
| 422 | Validation Error | Invalid filter value | Check filter options |
| 500 | Server Error | Internal error | Check logs |

---

## 📝 Rate Limiting

Currently **no rate limiting** is enforced. You can implement it if needed:

```php
// In routes/web.php
Route::middleware(['auth', 'throttle:60,1'])->group(function () {
    // API routes limited to 60 requests per minute
});
```

---

## 🔄 Pagination

All list endpoints support pagination:

```
GET /api/patients?page=1&per_page=20
```

Response includes:
- `current_page` - Current page number
- `data` - Array of records
- `last_page` - Total number of pages
- `total` - Total number of records
- `per_page` - Records per page
- `next_page_url` - URL to next page (null if last page)
- `prev_page_url` - URL to previous page (null if first page)

---

## 🔍 Filtering Examples

### Single Filter
```
/api/stats?gender=Male
```

### Multiple Filters
```
/api/stats?gender=Female&age_group=31-45&country_id=1
```

### Date Range
```
/api/stats?date_from=2025-01-01&date_to=2025-12-31
```

### Location Cascade
```
/api/demographics?country_id=1&state_id=1&district_id=1&taluka_id=1
```

### Combined Complex Filter
```
/api/patients?page=1&gender=Female&age_group=31-45&date_from=2025-01-01&country_id=1&state_id=1
```

---

## 📊 Data Categories

### Health Status Categories
- **BP Status:** Normal | Prehypertension | Hypertension
- **RBS Levels:** Normal (<140) | Prediabetic (140-199) | Diabetic (≥200)
- **BMI:** Underweight | Normal | Overweight | Obese

### Demographics
- **Gender:** Male, Female, Other
- **Age Groups:** 0-17, 18-30, 31-45, 46-60, 60+
- **Sample Status:** Yes, No, NA

---

## 🎯 Common Use Cases

### 1. Dashboard Summary
```
GET /api/stats
```
Use to display KPI cards on dashboard.

### 2. Time-Based Chart
```
GET /api/registration-trend?period=month
```
Use to show patient registration trends over time.

### 3. Gender Breakdown
```
GET /api/demographics
→ Use gender distribution for pie chart
```

### 4. Health Risk Analysis
```
GET /api/health-metrics
→ Use BP and RBS for risk categorization
```

### 5. Treatment Patterns
```
GET /api/treatment-analytics
→ Analyze common medications and durations
```

### 6. Geographic Distribution
```
GET /api/demographics?country_id=1&state_id=1
→ Analyze patients by location
```

---

## 🔐 Security Notes

1. **Always validate filters** on backend before querying
2. **Use HTTPS** in production
3. **Implement rate limiting** to prevent abuse
4. **Cache responses** with 5-10 minute TTL
5. **Audit access** to sensitive data
6. **Use API tokens** instead of session auth for third-party integrations

---

## 📞 Support & Troubleshooting

### API Not Working?
1. ✅ Verify you're logged in
2. ✅ Check user has `analytics_view` permission
3. ✅ Verify endpoint URL is correct
4. ✅ Check browser console for errors
5. ✅ Review Laravel logs in `storage/logs/`

### Getting 403 Forbidden?
- Login as Admin user
- Or assign `analytics_viewer` role to user
- Run seeder: `php artisan db:seed`

### Getting 404 Not Found?
- Clear route cache: `php artisan route:cache`
- Verify endpoint spelling
- Check routes are registered: `php artisan route:list | grep analytics`

---

## 📚 Additional Resources

- **Official Documentation:** See `ANALYTICS_API_DOCUMENTATION.md`
- **Testing Guide:** See `API_TESTING_GUIDE.md`
- **cURL Examples:** See `CURL_EXAMPLES.md`
- **Postman Collection:** Import `analytics-api.postman_collection.json`

---

## 🔄 Update & Versioning

**Current Version:** 1.0
**Release Date:** January 28, 2025

### Future Enhancements
- [ ] Export to PDF/Excel
- [ ] Scheduled reports
- [ ] Advanced filtering UI
- [ ] Custom date range picker
- [ ] Real-time data refresh
- [ ] GraphQL support
- [ ] Webhook notifications

---

## ✨ Features

✅ 11 comprehensive API endpoints
✅ Real-time data aggregation
✅ Flexible filtering with 12+ parameters
✅ Location-based cascading dropdowns
✅ Pagination support for large datasets
✅ Health metric categorization
✅ Treatment analytics
✅ Demographic breakdowns
✅ Lab and diagnostics data
✅ Permission-based access control
✅ Session-based authentication
✅ JSON response format

---

## 📧 Contact

For API issues, bugs, or feature requests, contact the development team.

---

**Happy Analyzing! 📊**

