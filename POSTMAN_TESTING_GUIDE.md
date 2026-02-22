# Postman Testing Guide - Borderless Analytics API

## Quick Start

### Prerequisites
- Postman installed (download from https://www.postman.com/downloads/)
- Laravel server running on http://localhost:8001
- Admin user: admin@admin.com / password

### Step 1: Import Collection

1. **Open Postman**
2. **Click**: Import → Select Files
3. **Choose**: `Borderless_Analytics_API.postman_collection.json`
4. **Confirm**: Collection imported as "Borderless Analytics API"

### Step 2: Set Environment Variable

1. **Create Environment**:
   - Gear icon (top-right) → Environments
   - Click "+" or "Create"
   - Name: "Local"

2. **Add Variable**:
   - Variable: `base_url`
   - Initial value: `http://localhost:8001`
   - Current value: `http://localhost:8001`

3. **Save and Select**:
   - Click "Save"
   - Select "Local" from environment dropdown

### Step 3: Login First

**Before testing other endpoints, you MUST login:**

1. **Open**: Authentication → Login
2. **Check**: Headers show Content-Type and Accept
3. **Check**: Body shows email and password
4. **Click**: Send
5. **Verify**: Response shows `"success": true`

This establishes the session cookie for all subsequent requests.

---

## API Endpoints - Complete Guide

### 🔐 Authentication Endpoints

#### 1. Login
- **URL**: `POST {{base_url}}/api/auth/login`
- **Body**: JSON with email and password
- **Response**: User object with success flag
- **Purpose**: Establish authenticated session

**Test**:
1. Open: Authentication → Login
2. Click: Send
3. Verify: `"success": true` in response

---

#### 2. Check Authentication
- **URL**: `GET {{base_url}}/api/auth/check`
- **Body**: None
- **Response**: `authenticated` boolean and user object
- **Purpose**: Check if currently authenticated

**Test**:
1. After Login, open: Authentication → Check Authentication Status
2. Click: Send
3. Verify: `"authenticated": true`

---

#### 3. Get Current User
- **URL**: `GET {{base_url}}/api/auth/me`
- **Body**: None
- **Response**: Current user details
- **Purpose**: Get logged-in user info

**Test**:
1. After Login, open: Authentication → Get Current User
2. Click: Send
3. Verify: Returns user ID, name, email

---

#### 4. Logout
- **URL**: `POST {{base_url}}/api/auth/logout`
- **Body**: None
- **Response**: Logout confirmation
- **Purpose**: End authenticated session

**Test**:
1. After Login, open: Authentication → Logout
2. Click: Send
3. Verify: `"success": true`

---

### 📊 Statistics Endpoints

#### 5. Get Stats (No Filters)
- **URL**: `GET {{base_url}}/admin/analytics/api/stats`
- **Response**: KPI metrics (total patients, gender breakdown, average age, etc.)

**Test**:
1. Open: Analytics - Statistics → Get Stats (No Filters)
2. Click: Send
3. Verify: Response contains total_patients, male_patients, female_patients

---

#### 6. Get Stats with Gender Filter
- **URL**: `GET {{base_url}}/admin/analytics/api/stats?gender=Male`
- **Query Params**: gender=Male

**Test**:
1. Open: Analytics - Statistics → Get Stats (Gender Filter)
2. Click: Send
3. Verify: male_patients = total_patients, female_patients = 0

---

#### 7. Get Stats with Age Filter
- **URL**: `GET {{base_url}}/admin/analytics/api/stats?age_group=31-45`
- **Query Params**: age_group=31-45

**Test**:
1. Open: Analytics - Statistics → Get Stats (Age Group Filter)
2. Click: Send
3. Verify: Returns data for 31-45 age group

---

#### 8. Get Stats with Date Range
- **URL**: `GET {{base_url}}/admin/analytics/api/stats?date_from=2025-01-01&date_to=2025-03-31`
- **Query Params**: date_from, date_to

**Test**:
1. Open: Analytics - Statistics → Get Stats (Date Range Filter)
2. Click: Send
3. Verify: Returns data within date range

---

### 👥 Demographics Endpoints

#### 9. Get Demographics
- **URL**: `GET {{base_url}}/admin/analytics/api/demographics`
- **Response**: Gender distribution, age groups, top villages, age by gender

**Test**:
1. Open: Analytics - Demographics → Get Demographics
2. Click: Send
3. Verify: Response includes gender, age_groups, top_villages arrays

---

#### 10. Get Demographics with Filter
- **URL**: `GET {{base_url}}/admin/analytics/api/demographics?gender=Female`
- **Query Params**: gender=Female

**Test**:
1. Open: Analytics - Demographics → Get Demographics (Gender Filter)
2. Click: Send
3. Verify: Returns female-specific demographic data

---

### 🏥 Health Metrics Endpoints

#### 11. Get Health Metrics
- **URL**: `GET {{base_url}}/admin/analytics/api/health-metrics`
- **Response**: BP status, RBS levels, BMI analysis

**Test**:
1. Open: Analytics - Health Metrics → Get Health Metrics
2. Click: Send
3. Verify: Response includes bp_status, rbs_levels, bmi_analysis

---

#### 12. Get Health Metrics by Age
- **URL**: `GET {{base_url}}/admin/analytics/api/health-metrics?age_group=60+`
- **Query Params**: age_group=60%2B (URL encoded)

**Test**:
1. Open: Analytics - Health Metrics → Get Health Metrics (Age 60+)
2. Click: Send
3. Verify: Returns health metrics for 60+ age group

---

### 👤 Patient Endpoints

#### 13. Get Patients - Page 1
- **URL**: `GET {{base_url}}/admin/analytics/api/patients?page=1&per_page=10`
- **Query Params**: page, per_page

**Test**:
1. Open: Analytics - Patients → Get Patients (Page 1)
2. Click: Send
3. Verify: Returns 10 patient records, total count, pagination info

---

#### 14. Get Patients - Page 2
- **URL**: `GET {{base_url}}/admin/analytics/api/patients?page=2&per_page=10`
- **Query Params**: page=2, per_page=10

**Test**:
1. Open: Analytics - Patients → Get Patients (Page 2)
2. Click: Send
3. Verify: Returns next page of patients

---

#### 15. Get Patients with Filter
- **URL**: `GET {{base_url}}/admin/analytics/api/patients?gender=Female&page=1&per_page=10`
- **Query Params**: gender, page, per_page

**Test**:
1. Open: Analytics - Patients → Get Patients (Gender Filter)
2. Click: Send
3. Verify: Returns only female patients

---

### 🗺️ Location Cascade Endpoints

#### 16. Get States
- **URL**: `GET {{base_url}}/admin/analytics/api/states/1`
- **Path**: state/{country_id}

**Test**:
1. Open: Analytics - Location Cascade → Get States
2. Click: Send
3. Verify: Returns array of states

---

#### 17. Get Districts
- **URL**: `GET {{base_url}}/admin/analytics/api/districts/1`
- **Path**: districts/{state_id}

**Test**:
1. Open: Analytics - Location Cascade → Get Districts
2. Click: Send
3. Verify: Returns array of districts

---

#### 18. Get Talukas
- **URL**: `GET {{base_url}}/admin/analytics/api/talukas/1`
- **Path**: talukas/{district_id}

**Test**:
1. Open: Analytics - Location Cascade → Get Talukas
2. Click: Send
3. Verify: Returns array of talukas

---

## Complete Testing Workflow

### Step-by-Step Test Plan

1. **Authentication Flow**
   - [ ] Login endpoint → Successful
   - [ ] Check authentication → Returns true
   - [ ] Get current user → Returns user details
   - [ ] Logout → Successful

2. **Statistics Endpoints**
   - [ ] Get stats (no filter) → Returns all metrics
   - [ ] Get stats (gender filter) → Gender-specific data
   - [ ] Get stats (age filter) → Age-specific data
   - [ ] Get stats (date range) → Date-filtered data

3. **Demographics**
   - [ ] Get demographics → Returns all demographics
   - [ ] Get demographics (gender) → Gender-specific demographics

4. **Health Metrics**
   - [ ] Get health metrics → Returns BP, RBS, BMI
   - [ ] Get health metrics (age) → Age-specific health metrics

5. **Patients**
   - [ ] Get patients page 1 → Returns 10 records
   - [ ] Get patients page 2 → Returns next 10 records
   - [ ] Get patients (gender filter) → Gender-filtered list

6. **Location Cascade**
   - [ ] Get states → Returns state list
   - [ ] Get districts → Returns district list
   - [ ] Get talukas → Returns taluka list

---

## Using Pre-request Scripts

### Add Authentication Cookie Automatically

In Postman Pre-request Script tab (for protected endpoints):

```javascript
// This is done automatically by Postman
// After login, cookies are saved and sent with all requests
// No additional configuration needed
```

**How it works**:
1. Login creates session cookie
2. Postman automatically includes cookie in subsequent requests
3. All protected endpoints receive authenticated requests

---

## Response Status Codes

| Code | Meaning | Action |
|------|---------|--------|
| 200 | Success | Request successful |
| 401 | Unauthorized | Need to login first |
| 422 | Validation Error | Check request parameters |
| 500 | Server Error | Check Laravel logs |

---

## Troubleshooting

### Issue: 401 Unauthorized on Protected Endpoints
**Solution**:
1. Make sure you've run Login request first
2. Check Cookies in Postman (left sidebar)
3. Verify session cookie exists
4. Try logout and login again

### Issue: No Data Returned
**Solution**:
1. Verify database has patient data
2. Try different filter combinations
3. Check page number is valid
4. Review response for error messages

### Issue: Invalid Credentials on Login
**Solution**:
1. Verify email: admin@admin.com
2. Verify password: password
3. Check database user exists:
   ```bash
   php artisan tinker
   >>> App\Models\User::where('email', 'admin@admin.com')->first()
   ```

### Issue: CORS Error
**Solution**:
1. This should NOT happen with Postman (local testing)
2. If it does, restart Laravel server
3. Check CORS middleware configuration

---

## Tips & Tricks

### Tip 1: Use Environment Variables
Instead of hardcoding URLs, use {{base_url}} variable:
- Easy to switch between environments
- Update one place, affects all requests

### Tip 2: Save Responses
Right-click response → Save response → Save as Example
- Useful for documentation
- Share with team members

### Tip 3: Use Collections for Testing
- Run entire collection to test all endpoints
- Set up pre-request scripts for setup
- Set up post-request scripts for teardown

### Tip 4: Monitor Performance
- Check response times in Postman
- Slow responses indicate performance issues
- Expected time: <1 second per request

---

## Sample Test Sequence

### Complete API Test (Approx 2 minutes)

1. **Setup** (30 seconds)
   - Login → Send
   - Wait for response
   - Verify success

2. **Test Stats** (20 seconds)
   - Get Stats (No Filter) → Send
   - Get Stats (Gender Filter) → Send
   - Get Stats (Date Range) → Send

3. **Test Demographics** (20 seconds)
   - Get Demographics → Send
   - Get Demographics (Filter) → Send

4. **Test Health** (20 seconds)
   - Get Health Metrics → Send
   - Get Health Metrics (Age) → Send

5. **Test Patients** (30 seconds)
   - Get Patients (Page 1) → Send
   - Get Patients (Page 2) → Send
   - Get Patients (Filter) → Send

6. **Test Location** (20 seconds)
   - Get States → Send
   - Get Districts → Send
   - Get Talukas → Send

7. **Cleanup** (10 seconds)
   - Logout → Send

**Total Time**: ~2-3 minutes for complete API validation

---

## Documentation Links

- API Documentation: `COMPLETE_API_DOCUMENTATION.md`
- Chart.html (v2): `chart-v2.html`
- Testing Guide: `TESTING_GUIDE.md`
- Authentication Fix: `AUTHENTICATION_FIX.md`

---

## Support

### Common Questions

**Q: Do I need to login for every request?**
A: No, only once. Postman saves the session cookie automatically.

**Q: Can I modify the collection?**
A: Yes, all requests can be customized. Make changes as needed.

**Q: How do I test with different credentials?**
A: Modify the Login request body with different email/password.

**Q: Can I export test results?**
A: Yes, use Runner or export responses as examples.

---

**Status**: ✅ Complete and Ready for Testing

Last Updated: December 29, 2025
