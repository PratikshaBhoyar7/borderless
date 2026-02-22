# 📚 Patient Health Analytics API - Documentation Index

## Quick Navigation

### 🚀 Start Here
1. **[API_README.md](API_README.md)** - Overview and quick start guide
   - 5-minute quick start
   - Authentication overview
   - All endpoints at a glance
   - Common use cases

### 📖 Full Documentation
2. **[ANALYTICS_API_DOCUMENTATION.md](ANALYTICS_API_DOCUMENTATION.md)** - Complete API Reference
   - Every endpoint with full details
   - Request/response examples
   - Filter parameter reference
   - Error codes and solutions
   - Rate limiting and CORS
   - ~800 lines of comprehensive docs

### 🧪 Testing & QA
3. **[API_TESTING_GUIDE.md](API_TESTING_GUIDE.md)** - How to Test the API
   - Postman setup instructions
   - cURL testing examples
   - Browser console testing
   - 10 complete test cases
   - Error troubleshooting
   - Performance testing

### 💻 Command Line Examples
4. **[CURL_EXAMPLES.md](CURL_EXAMPLES.md)** - 25+ Ready-to-Use cURL Commands
   - Login and authentication
   - Every endpoint with examples
   - Single and multiple filters
   - Location cascade workflow
   - Advanced usage patterns
   - Error handling examples
   - Performance monitoring

### 🔧 Postman Collection
5. **[analytics-api.postman_collection.json](analytics-api.postman_collection.json)** - Pre-built Postman Collection
   - 30+ ready-to-use requests
   - All endpoints configured
   - Authentication setup
   - Filter examples
   - One-click testing
   - Import to Postman

---

## 📊 Documentation Overview

| Document | Size | Best For | Key Features |
|----------|------|----------|--------------|
| API_README.md | 12 KB | Quick start | Overview, examples, integration |
| ANALYTICS_API_DOCUMENTATION.md | 18 KB | Reference | Complete specs, all endpoints |
| API_TESTING_GUIDE.md | 10 KB | QA/Testing | Test cases, troubleshooting |
| CURL_EXAMPLES.md | 13 KB | Developers | Ready-to-run commands |
| Postman Collection | 27 KB | Postman users | Pre-configured requests |

**Total Documentation:** ~80 KB of comprehensive guides

---

## 🎯 Choose Your Path

### Path 1: I Want to Use the API Quickly
1. Read **API_README.md** (5 min)
2. Try examples in **CURL_EXAMPLES.md** (10 min)
3. Start integrating (ongoing)

### Path 2: I Want Complete Reference Material
1. Read **API_README.md** overview (5 min)
2. Deep dive into **ANALYTICS_API_DOCUMENTATION.md** (30 min)
3. Reference as needed

### Path 3: I Want to Test the API
1. Follow **API_TESTING_GUIDE.md** → Postman setup (10 min)
2. Import **analytics-api.postman_collection.json** (2 min)
3. Run test cases from guide (20 min)

### Path 4: I'm a Command-Line Developer
1. Read **API_README.md** intro (5 min)
2. Copy-paste examples from **CURL_EXAMPLES.md** (ongoing)
3. Customize for your needs

---

## 📋 What's in Each Document

### 1. API_README.md
**Purpose:** Quick start and overview

**Contents:**
- 📚 Documentation files summary
- 🚀 Three quick start options (Postman, cURL, JavaScript)
- 🔐 Authentication requirements
- 📊 11 API endpoints summary table
- 🔗 Available filters reference
- 📈 Response format examples
- 💡 5 usage examples
- 🛠️ Integration code examples (JavaScript, PHP, Python)
- ⚠️ Error codes table
- 📝 Rate limiting info
- 🎯 Common use cases

**Best For:** Everyone - start here first!

---

### 2. ANALYTICS_API_DOCUMENTATION.md
**Purpose:** Complete API reference

**Contents:**
- 🔐 Authentication details
- 📡 Base URL and endpoint structure
- 📊 11 API endpoints with:
  - Method (GET/POST)
  - Full path
  - Authentication requirements
  - Query parameters
  - Request examples
  - Response format
  - cURL examples
- 🔗 Location cascade endpoints (3 endpoints)
- ❌ Error responses (401, 403, 404, 422, 500)
- 🎯 Filter reference table
- 📜 Pagination details
- 💻 Complete example requests
- 📈 Performance tips
- 🔄 Rate limiting options
- 🌐 CORS configuration

**Best For:** Developers who need complete specs

**Key Sections:**
- Pages 1-5: Overview and authentication
- Pages 6-25: 11 API endpoints
- Pages 26-30: Location cascade endpoints
- Pages 31-35: Errors, filters, pagination
- Pages 36-40: Examples and performance

---

### 3. API_TESTING_GUIDE.md
**Purpose:** How to test the API

**Contents:**
- 🚀 Postman quick start (5 steps)
- 💻 cURL testing without Postman
- 🌐 Browser console JavaScript examples
- 🧪 10 complete test cases:
  1. Basic statistics
  2. Gender filter
  3. Location cascade
  4. Date range filter
  5. Demographics
  6. Health metrics
  7. Patient list pagination
  8. Permission denied
  9. Treatment analytics
  10. Lab diagnostics
- ❌ Error handling & solutions
- ⚡ Performance testing
- 🔍 Database optimization
- 📋 Comprehensive checklist

**Best For:** QA engineers and testers

**Sections:**
- Postman setup: Pages 1-3
- cURL testing: Pages 4-5
- Browser testing: Pages 6-7
- Test cases: Pages 8-18
- Errors: Pages 19-20
- Performance: Pages 21-22

---

### 4. CURL_EXAMPLES.md
**Purpose:** Ready-to-use cURL commands

**Contents:**
- 🔑 Authentication setup
- 📊 6 basic examples
- 🌍 6 demographics examples
- ❤️ 4 health metrics examples
- 🏥 3 lab & diagnostics examples
- 💊 3 treatment analytics examples
- 👥 4 patient list examples
- 🗺️ 3 location cascade examples
- 🚀 8 advanced examples
- ⚠️ Error handling
- 🛠️ Useful tools
- 💡 Tips & tricks

**Best For:** Copy-paste developers

**Examples Include:**
- Login and session management
- Filtering by single parameter
- Filtering by multiple parameters
- Saving responses to files
- Parsing JSON output
- Error handling and retries
- Performance monitoring
- Bash scripting with loops

---

### 5. analytics-api.postman_collection.json
**Purpose:** Pre-built Postman collection

**Contents:**
- 🔐 Authentication & Setup
  - Login request (Get Session)
- 📊 KPI & Statistics (5 requests)
- 📈 Registration Trends (4 requests)
- 🌍 Demographics (4 requests)
- ❤️ Health Metrics (4 requests)
- 🏥 Lab & Diagnostics (2 requests)
- 💊 Treatment Analytics (2 requests)
- 👥 Patient List (4 requests)
- 🗺️ Location Cascade (3 requests)

**Total:** 32 pre-configured requests

**Best For:** Postman users

**How to Use:**
1. Open Postman
2. File → Import → Select JSON file
3. Set `base_url` variable to your domain
4. Run requests directly

---

## 🔐 Authentication in All Documents

### Quick Reference
```
Login First → Get Session → Use in API Calls
```

### In Postman
- Use "Login (Get Session)" request
- Cookies automatically included

### In cURL
- Use `-c cookies.txt` to save session
- Use `-b cookies.txt` to use session

### In JavaScript
- Fetch automatically includes cookies
- Just add `headers: { 'Accept': 'application/json' }`

---

## 🔗 All Endpoints Quick List

### Statistics
- `GET /api/stats` - KPI metrics
- `GET /api/registration-trend` - Registration trend

### Demographics
- `GET /api/demographics` - All demographic data

### Health
- `GET /api/health-metrics` - BP, RBS, BMI analysis

### Lab & Diagnostics
- `GET /api/lab-diagnostics` - Diagnoses, tests, samples

### Treatment
- `GET /api/treatment-analytics` - Medications, duration

### Patients
- `GET /api/patients` - Paginated patient list

### Location
- `GET /api/states/{id}` - States by country
- `GET /api/districts/{id}` - Districts by state
- `GET /api/talukas/{id}` - Talukas by district

---

## 📊 Filtering Quick Reference

### Available Filters
```
date_from, date_to          (YYYY-MM-DD format)
gender                      (Male, Female, Other)
age_group                   (0-17, 18-30, 31-45, 46-60, 60+)
country_id, state_id        (Location IDs)
district_id, taluka_id      (Location IDs)
campaign_type_id            (Campaign Type ID)
period                      (day, week, month, year - for trends only)
page, per_page              (Pagination - patients endpoint only)
```

### Example Filters
```
?gender=Male
?age_group=31-45
?country_id=1&state_id=1
?date_from=2025-01-01&date_to=2025-12-31
?gender=Female&age_group=31-45&country_id=1
```

---

## 🛠️ Integration Checklist

- [ ] Read API_README.md for overview
- [ ] Choose your method (Postman, cURL, code)
- [ ] Set up authentication
- [ ] Test one endpoint
- [ ] Review filter options
- [ ] Implement in your application
- [ ] Handle errors gracefully
- [ ] Cache responses if needed
- [ ] Monitor performance
- [ ] Document your implementation

---

## 📱 For Different Users

### For Product Managers
→ Read **API_README.md** sections on:
- Quick Start
- Available Filters
- Common Use Cases

### For Backend Developers
→ Read **ANALYTICS_API_DOCUMENTATION.md** for:
- Complete endpoint specifications
- Request/response formats
- Filter parameters
- Error handling

### For Frontend Developers
→ Read **CURL_EXAMPLES.md** and **Postman Collection** for:
- Real-world examples
- Integration patterns
- Testing approaches

### For QA Engineers
→ Read **API_TESTING_GUIDE.md** for:
- Test cases
- Testing tools
- Error scenarios
- Performance metrics

### For DevOps/SRE
→ Check both **ANALYTICS_API_DOCUMENTATION.md** and **API_TESTING_GUIDE.md** for:
- Rate limiting options
- Performance optimization
- Error codes
- Monitoring

---

## 🔄 Document Relationships

```
API_README.md (START HERE)
    ├── API_DOCUMENTATION_INDEX.md (you are here)
    ├── ANALYTICS_API_DOCUMENTATION.md (for specs)
    ├── API_TESTING_GUIDE.md (for testing)
    ├── CURL_EXAMPLES.md (for examples)
    └── analytics-api.postman_collection.json (for Postman)
```

---

## 📞 Need Help?

1. **Quick question?** → Check **API_README.md**
2. **Need specs?** → See **ANALYTICS_API_DOCUMENTATION.md**
3. **Want to test?** → Follow **API_TESTING_GUIDE.md**
4. **Need examples?** → Use **CURL_EXAMPLES.md**
5. **Using Postman?** → Import **analytics-api.postman_collection.json**

---

## ✨ What You Get

✅ **5 comprehensive documents**
✅ **80+ KB of guides and references**
✅ **30+ ready-to-run code examples**
✅ **10+ complete test cases**
✅ **Pre-built Postman collection**
✅ **25+ cURL command examples**
✅ **Integration code (JavaScript, PHP, Python)**
✅ **Error handling guides**
✅ **Performance tips**
✅ **Authentication explained**

---

## 🎯 Success Path

1. **Read API_README.md** → 5 minutes
2. **Choose your tool** → Postman/cURL/Code
3. **Follow setup guide** → 5 minutes
4. **Try first example** → 5 minutes
5. **Run test cases** → 20 minutes
6. **Integrate in your app** → Ongoing

**Total time to first working request: ~30 minutes**

---

**Happy exploring! 🚀**

For detailed information on any endpoint, filter, or use case, refer to the appropriate document above.

