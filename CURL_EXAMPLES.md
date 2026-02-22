# Analytics API - cURL Command Examples

## Setup

### Step 1: Save Credentials
Create a file `credentials.txt`:
```
email=admin@example.com
password=password123
```

### Step 2: Login and Get Session
```bash
curl -c cookies.txt \
  -d @credentials.txt \
  https://yourdomain.com/login
```

This saves the session cookie to `cookies.txt` for use in subsequent requests.

---

## Basic Examples

### 1. Get All Patient Statistics
```bash
curl -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/stats
```

**Pretty print JSON:**
```bash
curl -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/stats | jq '.'
```

---

### 2. Get Statistics with One Filter
```bash
# Filter by gender
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/stats?gender=Male"

# Filter by age group
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/stats?age_group=31-45"
```

---

### 3. Get Statistics with Multiple Filters
```bash
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/stats?gender=Female&age_group=18-30&country_id=1"
```

---

### 4. Get Registration Trend
```bash
# Monthly trend
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/registration-trend?period=month"

# Weekly trend
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/registration-trend?period=week"

# Daily trend
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/registration-trend?period=day"

# Yearly trend
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/registration-trend?period=year"
```

---

## Demographics Examples

### 5. Get All Demographics Data
```bash
curl -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/demographics | jq '.'
```

### 6. Demographics - Location Specific
```bash
# By country only
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/demographics?country_id=1"

# By country and state
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/demographics?country_id=1&state_id=1"

# By country, state, and district
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/demographics?country_id=1&state_id=1&district_id=1"

# By all locations (full cascade)
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/demographics?country_id=1&state_id=1&district_id=1&taluka_id=1"
```

---

## Health Metrics Examples

### 7. Get All Health Metrics
```bash
curl -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/health-metrics | jq '.'
```

### 8. Health Metrics - Various Filters
```bash
# By age group 46-60
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/health-metrics?age_group=46-60"

# By gender Female
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/health-metrics?gender=Female"

# By gender and age
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/health-metrics?gender=Male&age_group=60+"

# By date range
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/health-metrics?date_from=2025-01-01&date_to=2025-12-31"

# Complex filter
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/health-metrics?gender=Female&age_group=31-45&country_id=1&state_id=1"
```

---

## Lab & Diagnostics Examples

### 9. Get All Lab Data
```bash
curl -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/lab-diagnostics | jq '.'
```

### 10. Lab Data - Filtered
```bash
# By gender
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/lab-diagnostics?gender=Female"

# By age group
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/lab-diagnostics?age_group=46-60"

# By location
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/lab-diagnostics?country_id=1&state_id=1"
```

---

## Treatment Analytics Examples

### 11. Get All Treatment Data
```bash
curl -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/treatment-analytics | jq '.'
```

### 12. Treatment Data - Various Filters
```bash
# By age group 60+
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/treatment-analytics?age_group=60+"

# By gender
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/treatment-analytics?gender=Female"

# By date range
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/treatment-analytics?date_from=2025-01-01&date_to=2025-06-30"
```

---

## Patient List Examples

### 13. Get Paginated Patients
```bash
# First page
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/patients?page=1&per_page=20"

# Second page
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/patients?page=2&per_page=20"

# More records per page
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/patients?page=1&per_page=50"
```

### 14. Patients - With Filters
```bash
# Male patients only
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/patients?page=1&gender=Male"

# Age group 31-45
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/patients?page=1&age_group=31-45"

# Female, age 18-30, from specific location
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/patients?page=1&gender=Female&age_group=18-30&country_id=1&state_id=1"

# Date range
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/patients?page=1&date_from=2025-01-01&date_to=2025-03-31"
```

---

## Location Cascade Examples

### 15. Get States for Country
```bash
# States for country ID 1
curl -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/states/1

# States for country ID 2
curl -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/states/2
```

### 16. Get Districts for State
```bash
# Districts for state ID 1
curl -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/districts/1

# Districts for state ID 5
curl -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/districts/5
```

### 17. Get Talukas for District
```bash
# Talukas for district ID 1
curl -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/talukas/1

# Talukas for district ID 10
curl -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/talukas/10
```

---

## Cascade Workflow Example

Complete flow for building a cascading dropdown:

```bash
#!/bin/bash

# Step 1: Get all countries (from database)
echo "=== Step 1: Select Country ==="
curl -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/stats?country_id=1 | jq '.total_patients'

COUNTRY_ID=1

# Step 2: Get states for selected country
echo "=== Step 2: Get States for Country $COUNTRY_ID ==="
STATES=$(curl -s -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/states/$COUNTRY_ID")

echo "$STATES" | jq '.[] | {id, name}'

STATE_ID=$(echo "$STATES" | jq -r '.[0].id')
echo "Selected State ID: $STATE_ID"

# Step 3: Get districts for selected state
echo "=== Step 3: Get Districts for State $STATE_ID ==="
DISTRICTS=$(curl -s -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/districts/$STATE_ID")

echo "$DISTRICTS" | jq '.[] | {id, name}'

DISTRICT_ID=$(echo "$DISTRICTS" | jq -r '.[0].id')
echo "Selected District ID: $DISTRICT_ID"

# Step 4: Get talukas for selected district
echo "=== Step 4: Get Talukas for District $DISTRICT_ID ==="
TALUKAS=$(curl -s -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/talukas/$DISTRICT_ID")

echo "$TALUKAS" | jq '.[] | {id, name}'

TALUKA_ID=$(echo "$TALUKAS" | jq -r '.[0].id')
echo "Selected Taluka ID: $TALUKA_ID"

# Step 5: Get filtered data
echo "=== Step 5: Get Filtered Demographics ==="
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/demographics?country_id=$COUNTRY_ID&state_id=$STATE_ID&district_id=$DISTRICT_ID&taluka_id=$TALUKA_ID" | jq '.'
```

---

## Advanced Examples

### 18. Save Response to File
```bash
curl -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/demographics > demographics.json

# View file
cat demographics.json | jq '.'
```

### 19. Filter JSON Response
```bash
# Get only gender distribution
curl -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/demographics | jq '.gender'

# Get only age groups
curl -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/demographics | jq '.age_groups'

# Get only top village names
curl -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/demographics | jq '.top_villages[].village'
```

### 20. Count Total Records
```bash
# Count male patients
curl -b cookies.txt \
  -H "Accept: application/json" \
  "https://yourdomain.com/admin/analytics/api/stats?gender=Male" | jq '.total_patients'

# Count all patients
curl -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/stats | jq '.total_patients'
```

### 21. Loop Through Pagination
```bash
#!/bin/bash

# Get all patients across all pages
for page in {1..5}; do
  echo "=== Fetching page $page ==="
  curl -b cookies.txt \
    -H "Accept: application/json" \
    "https://yourdomain.com/admin/analytics/api/patients?page=$page&per_page=20" | jq '.data | length'
done
```

### 22. Calculate Statistics from Response
```bash
# Get average age and total patients
curl -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/stats | jq '{
    total: .total_patients,
    avg_age: .avg_age,
    male_percentage: ((.male_patients / .total_patients) * 100 | round),
    female_percentage: ((.female_patients / .total_patients) * 100 | round)
  }'
```

---

## Error Handling Examples

### 23. Check HTTP Status Code
```bash
# Get status code
curl -s -o /dev/null -w "%{http_code}" \
  -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/stats

# Expected: 200
# 401 = Unauthorized
# 403 = Forbidden
# 404 = Not Found
# 500 = Server Error
```

### 24. Check Response with Headers
```bash
# Include headers in response
curl -i -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/stats
```

### 25. Handle Errors
```bash
# Retry on failure
MAX_ATTEMPTS=3
ATTEMPT=1

while [ $ATTEMPT -le $MAX_ATTEMPTS ]; do
  RESPONSE=$(curl -s -w "\n%{http_code}" \
    -b cookies.txt \
    -H "Accept: application/json" \
    https://yourdomain.com/admin/analytics/api/stats)

  HTTP_CODE=$(echo "$RESPONSE" | tail -n1)
  BODY=$(echo "$RESPONSE" | head -n-1)

  if [ "$HTTP_CODE" = "200" ]; then
    echo "Success!"
    echo "$BODY" | jq '.'
    break
  else
    echo "Attempt $ATTEMPT failed with status $HTTP_CODE"
    ATTEMPT=$((ATTEMPT + 1))
    sleep 2
  fi
done
```

---

## Useful Tools

### Install jq for JSON parsing
```bash
# macOS
brew install jq

# Ubuntu/Debian
sudo apt-get install jq

# CentOS/RHEL
sudo yum install jq
```

### Pretty print any curl response
```bash
curl -b cookies.txt \
  -H "Accept: application/json" \
  https://yourdomain.com/admin/analytics/api/stats | jq '.' | less
```

---

## Tips & Tricks

1. **Use variables for IDs:**
   ```bash
   COUNTRY_ID=1
   STATE_ID=5
   curl -b cookies.txt ... "?country_id=$COUNTRY_ID&state_id=$STATE_ID"
   ```

2. **Save commonly used commands:**
   ```bash
   alias analytics_stats='curl -b cookies.txt -H "Accept: application/json" https://yourdomain.com/admin/analytics/api/stats'
   analytics_stats | jq '.'
   ```

3. **Combine multiple filters:**
   ```bash
   curl -b cookies.txt \
     -H "Accept: application/json" \
     "https://yourdomain.com/admin/analytics/api/stats?gender=Male&age_group=31-45&date_from=2025-01-01&date_to=2025-12-31"
   ```

4. **Monitor API performance:**
   ```bash
   time curl -b cookies.txt \
     -H "Accept: application/json" \
     https://yourdomain.com/admin/analytics/api/stats > /dev/null
   ```

---

## See Also

- `ANALYTICS_API_DOCUMENTATION.md` - Complete API reference
- `API_TESTING_GUIDE.md` - Comprehensive testing guide
- `analytics-api.postman_collection.json` - Postman collection

