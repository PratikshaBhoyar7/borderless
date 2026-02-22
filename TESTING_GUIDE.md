# Comprehensive Testing Guide - Borderless Analytics APIs

## Overview

This guide provides three methods to test all API endpoints used by the chart.html dashboard.

---

## 🧪 Method 1: Interactive Test Dashboard (Recommended)

### What It Is
A visual, interactive testing interface that tests all APIs with real-time feedback.

### How to Use

1. **Start Laravel**
   ```bash
   cd borderless
   php artisan serve
   ```

2. **Open Test Dashboard**
   ```
   http://localhost:8000/test-dashboard.html
   ```

3. **Run Tests**
   - **Quick Test**: Tests authentication + 1 endpoint (~2 seconds)
   - **Run All Tests**: Tests all 14 endpoints (~10 seconds)
   - **Clear Results**: Reset test results

### Features
✅ Real-time test log
✅ Visual pass/fail indicators
✅ API response details
✅ Test summary with pass rate
✅ Color-coded status indicators

### Test Results Display
- **Green checkmark (✓)**: Test passed
- **Red X (✗)**: Test failed
- **Orange clock (⏳)**: Test pending
- **Status cards**: Show endpoint name, URL, and result
- **Log section**: Real-time output of all operations

---

## 🔧 Method 2: Browser Console Script

### What It Is
A JavaScript test suite that runs in your browser console.

### How to Use

1. **Open chart.html or any Laravel page**
   ```
   http://localhost:8000/chart.html
   ```

2. **Open Developer Tools**
   - Press `F12` or `Cmd+Option+I` (Mac)
   - Click **Console** tab

3. **Paste and Run Script**
   ```javascript
   // Copy entire contents of test-apis.js into console
   // Then run:
   runAllTests();
   ```

4. **View Results**
   - Output appears in console with color-coded messages
   - Results saved to `window.testResults` for inspection

### Console Output Example
```
[10:30:45] ℹ Testing API at: http://localhost:8000
[10:30:45] ℹ Using credentials: admin@admin.com

[10:30:45] TEST Testing basic connectivity...
[10:30:46] ✓ Server accessible at http://localhost:8000

[10:30:46] TEST Testing authentication...
[10:30:46] ℹ Extracting CSRF token from login page...
[10:30:46] ✓ CSRF token extracted: eyJpdiI6IjJ...
[10:30:47] ✓ Login successful (Status: 302)

[10:30:47] TEST Testing all API endpoints...
...
```

### Accessing Results Programmatically
```javascript
// After running tests:
console.log(window.testResults);

// Access specific endpoint result:
console.log(window.testResults.endpoints['Stats (No Filter)']);

// Check if all passed:
const allPassed = Object.values(window.testResults.endpoints)
    .every(r => r.status === 'success');
```

---

## 📝 Method 3: Manual cURL Testing

### What It Is
Command-line testing using curl to verify each endpoint individually.

### Prerequisites
```bash
# Install curl (usually pre-installed on Mac/Linux)
which curl
```

### Step 1: Get CSRF Token and Login
```bash
# Save cookies and extract CSRF token
curl -c cookies.txt http://localhost:8000/login | grep -o '_token" value="[^"]*"' | grep -o '[^"]*$' > token.txt

# Login (manually insert CSRF token)
CSRF_TOKEN=$(cat token.txt)
curl -c cookies.txt -X POST http://localhost:8000/login \
  -d "email=admin@admin.com&password=password&_token=$CSRF_TOKEN"
```

### Step 2: Test Each Endpoint

**Test 1: Basic Stats**
```bash
curl -b cookies.txt \
  -H "Accept: application/json" \
  http://localhost:8000/admin/analytics/api/stats
```

**Test 2: Stats by Gender**
```bash
curl -b cookies.txt \
  -H "Accept: application/json" \
  "http://localhost:8000/admin/analytics/api/stats?gender=Male"
```

**Test 3: Demographics**
```bash
curl -b cookies.txt \
  -H "Accept: application/json" \
  http://localhost:8000/admin/analytics/api/demographics
```

**Test 4: Health Metrics**
```bash
curl -b cookies.txt \
  -H "Accept: application/json" \
  http://localhost:8000/admin/analytics/api/health-metrics
```

**Test 5: Patients List**
```bash
curl -b cookies.txt \
  -H "Accept: application/json" \
  "http://localhost:8000/admin/analytics/api/patients?page=1&per_page=10"
```

**Test 6: States**
```bash
curl -b cookies.txt \
  -H "Accept: application/json" \
  http://localhost:8000/admin/analytics/api/states/1
```

### Expected Response Format

**Success Response (200 OK)**
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

**Error Response (401 Unauthorized)**
```json
{
  "message": "Unauthenticated."
}
```

**Error Response (404 Not Found)**
```json
{
  "message": "Not Found"
}
```

---

## 📊 Test Cases

### Test Case 1: Basic Authentication
**Purpose**: Verify login works correctly

**Steps**:
1. Visit `/login`
2. Extract CSRF token from form
3. POST login credentials
4. Verify 302 redirect or 200 response

**Expected Result**: ✓ Session established

### Test Case 2: Stats Endpoint (No Filters)
**Purpose**: Verify KPI metrics are returned

**Endpoint**: `/admin/analytics/api/stats`

**Expected Response**:
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

**Validation**:
- ✓ All fields present
- ✓ Numbers are positive
- ✓ male_patients + female_patients ≤ total_patients

### Test Case 3: Stats with Gender Filter
**Purpose**: Verify filtering by gender works

**Endpoint**: `/admin/analytics/api/stats?gender=Male`

**Expected Result**:
- ✓ female_patients = 0
- ✓ male_patients ≥ 0
- ✓ total_patients = male_patients

### Test Case 4: Demographics Endpoint
**Purpose**: Verify demographics data structure

**Endpoint**: `/admin/analytics/api/demographics`

**Expected Response**:
```json
{
  "gender": [
    { "sex": "Male", "count": 85 },
    { "sex": "Female", "count": 65 }
  ],
  "age_groups": [
    { "age_group": "0-17", "count": 10 },
    { "age_group": "18-30", "count": 35 },
    ...
  ],
  "top_villages": [...],
  "age_by_gender": [...]
}
```

### Test Case 5: Health Metrics Endpoint
**Purpose**: Verify health metrics data

**Endpoint**: `/admin/analytics/api/health-metrics`

**Expected Response**:
```json
{
  "bp_status": [
    { "bp_status": "Normal", "count": 100 },
    { "bp_status": "Prehypertension", "count": 30 },
    { "bp_status": "Hypertension", "count": 20 }
  ],
  "rbs_levels": [...],
  "bmi_analysis": [...]
}
```

### Test Case 6: Patients List with Pagination
**Purpose**: Verify pagination works

**Endpoint**: `/admin/analytics/api/patients?page=1&per_page=10`

**Expected Response**:
```json
{
  "current_page": 1,
  "data": [...],
  "last_page": 15,
  "total": 150,
  "per_page": 10,
  "next_page_url": "...",
  "prev_page_url": null
}
```

### Test Case 7: Date Range Filter
**Purpose**: Verify date filtering

**Endpoint**: `/admin/analytics/api/stats?date_from=2025-01-01&date_to=2025-03-31`

**Expected Result**: ✓ Data filtered to specified date range

### Test Case 8: Combined Filters
**Purpose**: Verify multiple filters work together

**Endpoint**: `/admin/analytics/api/stats?gender=Female&age_group=31-45&date_from=2025-01-01`

**Expected Result**: ✓ Data filtered by all parameters

### Test Case 9: States Endpoint
**Purpose**: Verify location cascade

**Endpoint**: `/admin/analytics/api/states/1`

**Expected Response**:
```json
[
  { "id": 1, "name": "State 1" },
  { "id": 2, "name": "State 2" },
  ...
]
```

### Test Case 10: Districts Endpoint
**Purpose**: Verify district retrieval

**Endpoint**: `/admin/analytics/api/districts/1`

**Expected Response**: Array of districts

---

## ✅ Verification Checklist

After running tests, verify:

- [ ] Server is running on http://localhost:8000
- [ ] Authentication succeeds
- [ ] All 14 endpoints return 200 OK
- [ ] Response data is not empty
- [ ] JSON format is valid
- [ ] All required fields present
- [ ] Numeric values are correct
- [ ] Filters work correctly
- [ ] Pagination works correctly
- [ ] Date filters work correctly
- [ ] Multiple filters work together
- [ ] Error messages are clear
- [ ] Response times are reasonable (<1 second each)
- [ ] chart.html dashboard loads data successfully

---

## 🐛 Troubleshooting

### Issue: "Cannot reach server"
**Solution**:
```bash
# Check if Laravel is running
ps aux | grep "php artisan serve"

# Start Laravel if not running
php artisan serve
```

### Issue: "Authentication failed"
**Solution**:
1. Verify credentials are correct: `admin@admin.com` / `password`
2. Check if CSRF token extraction works
3. Clear cookies and try again

### Issue: "Endpoints return empty data"
**Solution**:
1. Check if patient data exists in database:
   ```bash
   php artisan tinker
   >>> App\Models\Patient::count()
   ```
2. Run seeders to add test data:
   ```bash
   php artisan db:seed
   ```

### Issue: "401 Unauthorized" errors
**Solution**:
1. Verify session is established
2. Check if cookies are enabled in browser
3. Try clearing cookies and retesting
4. Check Laravel logs: `tail -f storage/logs/laravel.log`

### Issue: "404 Not Found"
**Solution**:
1. Verify endpoint URL spelling
2. Check route list: `php artisan route:list | grep analytics`
3. Clear route cache: `php artisan route:cache`

---

## 📈 Performance Benchmarks

### Expected Response Times
- **Authentication**: ~1-2 seconds (first request)
- **Stats endpoint**: ~200ms
- **Demographics**: ~300ms
- **Health metrics**: ~250ms
- **Patients list**: ~300ms
- **Total dashboard load**: ~3-4 seconds

### If Slower Than Expected
1. Check database query performance
2. Add database indexes if needed
3. Check server resources (CPU, memory)
4. Review Laravel logs for slow queries

---

## 🔍 Debugging Tips

### Enable Query Logging
```php
// In chart.html or your app:
DB::enableQueryLog();

// After requests:
dd(DB::getQueryLog());
```

### Check Laravel Logs
```bash
tail -f storage/logs/laravel.log

# Or view specific errors:
grep -i error storage/logs/laravel.log
```

### Browser DevTools
1. **Network tab**: View all requests and responses
2. **Console tab**: View JavaScript errors
3. **Application tab**: View cookies and local storage

### Test Individual Functions
```javascript
// In browser console:

// Test fetch function
fetch('http://localhost:8000/admin/analytics/api/stats', {
    credentials: 'include',
    headers: { 'Accept': 'application/json' }
})
.then(r => r.json())
.then(console.log)
.catch(console.error);
```

---

## 📋 Test Summary Template

Use this template to record test results:

```
Test Suite: Borderless Analytics API
Date: 2025-12-29
Tester: [Your Name]
Environment: Local (localhost:8000)

Authentication:     ✓ Passed
Stats (No Filter):  ✓ Passed
Stats (Gender):     ✓ Passed
Stats (Age):        ✓ Passed
Demographics:       ✓ Passed
Health Metrics:     ✓ Passed
Patients (Page 1):  ✓ Passed
Patients (Page 2):  ✓ Passed
Patients (Filtered):✓ Passed
States:             ✓ Passed
Districts:          ✓ Passed
Talukas:            ✓ Passed

Total Passed: 14/14
Total Failed: 0/14
Pass Rate: 100%

Status: ✓ ALL TESTS PASSED
```

---

## 📚 Related Files

- `chart.html` - Main analytics dashboard
- `test-dashboard.html` - Interactive testing UI
- `test-apis.js` - JavaScript test script
- `API_TESTING_GUIDE.md` - API documentation
- `CHART_FIXES_APPLIED.md` - Technical fixes

---

## 🎓 Learning Resources

### Understanding API Responses
- Each endpoint returns JSON data
- Successful requests return 200 OK
- Failed requests return 4xx or 5xx errors
- All endpoints require authentication

### Testing Best Practices
- Test happy path first (no filters)
- Test with different parameter combinations
- Test error cases (invalid parameters)
- Test pagination thoroughly
- Check response times

### Common Issues and Solutions
See **Troubleshooting** section above for common problems and solutions.

---

## ✨ Next Steps

1. ✅ Run quick test from test-dashboard.html
2. ✅ Verify all 14 endpoints pass
3. ✅ Check response data accuracy
4. ✅ Test filters in chart.html
5. ✅ Verify dashboard loads all data
6. ✅ Test on different browsers
7. ✅ Deploy to production

---

**Happy Testing! 🧪**

Last Updated: December 29, 2025
Status: ✅ All Tests Ready
