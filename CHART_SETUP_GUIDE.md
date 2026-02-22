# Chart.HTML Analytics Dashboard - Setup Guide

## Overview
The `chart.html` file is a standalone, independent analytics dashboard that connects to your Laravel Borderless API without requiring CORS configuration or any backend modifications.

## Key Features Fixed

### 1. Authentication Handling ✅
- **Auto-Login**: The dashboard automatically logs in using credentials:
  - Email: `admin@admin.com`
  - Password: `password`
- **CSRF Token**: Automatically fetches and includes CSRF token from login page
- **Session Management**: Maintains authenticated session using cookies

### 2. Proper API Integration ✅
- **Correct URLs**: Fixed double `/admin/` path issue
  - Old (broken): `http://localhost:8000/admin//admin/analytics/api/stats`
  - New (fixed): `http://localhost:8000/admin/analytics/api/stats`
- **Authenticated Requests**: All API calls include:
  - `credentials: 'include'` - Sends cookies with requests
  - `X-Requested-With: XMLHttpRequest` - Indicates AJAX request
  - `Accept: application/json` - Requests JSON response

### 3. CORS Bypass ✅
- **No CORS errors**: By using authenticated requests with proper session cookies, the browser accepts responses
- **No server-side CORS config needed**: Uses standard Laravel session authentication

## How It Works

### Step 1: Initialization
1. Page loads
2. JavaScript detects DOMContentLoaded event
3. Calls `loginAndLoadDashboard()`

### Step 2: Authentication
1. Fetches `/login` page to extract CSRF token
2. Posts login credentials with CSRF token
3. Browser stores session cookie automatically
4. Sets `isAuthenticated = true`

### Step 3: Load Dashboard
1. Fetches all data in parallel:
   - `/admin/analytics/api/stats` - KPI metrics
   - `/admin/analytics/api/demographics` - Gender, age, villages
   - `/admin/analytics/api/health-metrics` - BP, RBS, BMI
   - `/admin/analytics/api/patients` - Patient list with pagination

2. All requests include session cookie (automatically sent by browser)
3. Data is rendered into charts and KPI cards

## Usage

### Open the Dashboard
```bash
# Simply open in browser
http://localhost:8000/chart.html
```

OR

### Using Live Server (from any location)
```bash
# If running from a different port (e.g., VS Code Live Server on 5500+)
# The dashboard will still work because it maintains a session with the API
```

## Features

### KPI Cards (6 cards)
- Total Patients
- Male Patients
- Female Patients
- Average Age
- Samples Collected
- Referrals Made

### Interactive Charts (5 charts)
1. **Gender Distribution** - Doughnut chart
2. **Age Group Distribution** - Bar chart
3. **Blood Pressure Status** - Pie chart (Normal, Prehypertension, Hypertension)
4. **RBS Levels** - Pie chart (Normal, Prediabetic, Diabetic)
5. **BMI Analysis** - Doughnut chart (Underweight, Normal, Overweight, Obese)
6. **Top Villages** - Horizontal bar chart (top 10 villages)

### Patient List Table
- Paginated display (10 records per page)
- Shows: Patient ID, Name, Gender, Age, Village, Date
- Navigation: First, Previous, Next, Last buttons

### Advanced Filters
- **Gender**: All, Male, Female, Other
- **Age Group**: All, 0-17, 18-30, 31-45, 46-60, 60+
- **Date Range**: From date and To date pickers
- **Apply/Reset**: Apply filters or reset to defaults

## Troubleshooting

### Issue: "Failed to authenticate"
**Solution**:
- Verify credentials are correct (`admin@admin.com` / `password`)
- Check Laravel app is running on `http://localhost:8000`
- Ensure database has the admin user

### Issue: "Dashboard loaded but no data"
**Solution**:
- Check browser console for specific API errors (F12)
- Verify patient data exists in database
- Try applying filters to see if data loads

### Issue: Still getting CORS errors
**Solution**:
- Clear browser cookies (DevTools > Application > Cookies > Clear)
- Refresh the page completely (Ctrl+Shift+R or Cmd+Shift+R)
- Check that session.php driver is 'cookie' in Laravel config

### Issue: Charts not rendering
**Solution**:
- Wait a few seconds for data to load
- Check browser console for JavaScript errors
- Verify Chart.js CDN is accessible

## API Endpoints Used

All endpoints require admin authentication:

| Endpoint | Method | Purpose |
|----------|--------|---------|
| `/admin/analytics/api/stats` | GET | KPI metrics (total, gender, age, samples, referrals) |
| `/admin/analytics/api/demographics` | GET | Demographics (gender, age groups, villages) |
| `/admin/analytics/api/health-metrics` | GET | Health metrics (BP, RBS, BMI status) |
| `/admin/analytics/api/patients` | GET | Patient list with pagination |

### Query Parameters
All endpoints support:
- `gender` - Filter by gender (Male, Female, Other)
- `age_group` - Filter by age group (0-17, 18-30, etc.)
- `date_from` - Filter by start date (YYYY-MM-DD)
- `date_to` - Filter by end date (YYYY-MM-DD)
- `page` - Pagination page number
- `per_page` - Records per page

## Security Notes

⚠️ **Important**: This dashboard embeds credentials in the HTML file:
```javascript
const LOGIN_EMAIL = 'admin@admin.com';
const LOGIN_PASSWORD = 'password';
```

### For Development Only
- This is suitable for development/testing environments
- For production, implement:
  - Server-side API proxy (never expose credentials)
  - OAuth 2.0 or similar authentication
  - Token-based auth with secure storage

## Performance Optimization

The dashboard loads all data in parallel:
```javascript
await Promise.all([
    loadKPIs(),
    loadDemographics(),
    loadHealthMetrics(),
    loadPatients()
]);
```

This means all 4 API calls happen simultaneously, reducing load time.

## Responsive Design

The dashboard is fully responsive:
- **Desktop**: 2-column chart grid, full filters
- **Tablet**: 1-column charts, stacked filters
- **Mobile**: Single column, touch-friendly buttons

## Customization

### Change Login Credentials
Edit lines 479-480:
```javascript
const LOGIN_EMAIL = 'your.email@example.com';
const LOGIN_PASSWORD = 'your.password';
```

### Change API Base URL
Edit line 478:
```javascript
const API_BASE_URL = 'http://your.domain.com';
```

### Add More Charts
1. Add new container in HTML:
```html
<div class="chart-card">
    <h3>Your Chart Title</h3>
    <div class="chart-container" id="yourChartContainer">
        <div class="loading"><div class="spinner"></div>Loading...</div>
    </div>
</div>
```

2. Add fetch function in JavaScript:
```javascript
async function loadYourChart() {
    const data = await makeAuthenticatedRequest(`${API_BASE_URL}/admin/analytics/api/your-endpoint`);
    createChart('yourChartContainer', 'bar', {
        labels: data.labels,
        datasets: [{...}]
    });
}
```

3. Call from `loadDashboard()`:
```javascript
await Promise.all([
    loadKPIs(),
    loadDemographics(),
    loadHealthMetrics(),
    loadPatients(),
    loadYourChart()  // Add this
]);
```

## Browser Compatibility

- Chrome/Edge: ✅ Full support
- Firefox: ✅ Full support
- Safari: ✅ Full support
- IE 11: ❌ Not supported (uses modern JavaScript features)

## File Size

- HTML + CSS + JS inline: ~40KB
- External dependencies: Chart.js (~10KB), CDN hosted

## Next Steps

1. ✅ Open `http://localhost:8000/chart.html`
2. ✅ Dashboard auto-authenticates and loads
3. ✅ Use filters to explore data
4. ✅ View detailed analytics and trends

Enjoy your analytics dashboard!
