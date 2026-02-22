# 🚀 Quick Start Guide - Patient Health Analytics Dashboard

## Access the Dashboard

### URL
```
http://yourapp.com/admin/analytics
```

### Requirements
- You must be logged in to the admin panel
- Your account must have the `analytics_view` permission
- Your account must have one of these roles:
  - **Admin** (has all permissions)
  - **Analytics Viewer** (read-only access to analytics data)

---

## 🎯 Using the Dashboard

### Step 1: Navigate to Analytics
1. Log in to admin panel
2. Look for **"Analytics & Reports"** in the sidebar
3. Click **"Health Dashboard"** under Analytics

### Step 2: Apply Filters (Optional)
The dashboard loads with default data. You can filter by:

**Date Range:**
- Select "From Date" and "To Date"

**Demographics:**
- Choose Gender: All/Male/Female/Other
- Choose Age Group: All/0-17/18-30/31-45/46-60/60+

**Location (Cascading):**
1. Select **Country** first
2. Select **State** (from the selected country)
3. Select **District** (from the selected state)
4. Select **Taluka** (from the selected district)

**Apply Filters:**
- Click **"Apply Filters"** button (blue)
- All charts will update instantly

**Reset Filters:**
- Click **"Reset"** button (gray)
- All filters will be cleared

### Step 3: View Analytics Tabs

#### Overview Tab
- **KPI Cards**: Show 6 key metrics
  - Total Patients
  - Male Patients
  - Female Patients
  - Average Age
  - Samples Collected
  - Referrals Made
- **Registration Trend Chart**: Shows patient registration over the last 30 days

#### Demographics Tab
- **Gender Distribution**: Pie chart showing male/female/other split
- **Age Group Distribution**: Bar chart showing patients by age group
- **Top 10 Villages**: Bar chart of most active villages
- **Age by Gender**: Grouped bar chart comparing age groups by gender

#### Health Metrics Tab
- **Blood Pressure Status**: Doughnut chart showing Normal/Prehypertension/Hypertension
- **RBS Levels**: Bar chart showing Normal/Prediabetic/Diabetic categories
- **BMI Analysis**: Bar chart showing Underweight/Normal/Overweight/Obese categories

#### Lab & Diagnostics Tab
- **Common Diagnoses**: Top 10 diagnoses doughnut chart
- **Lab Tests Advised**: Top 10 lab tests doughnut chart
- **Sample Collection Status**: Pie chart showing Yes/No for sample collection

#### Treatment Tab
- **Common Medications**: Top 10 medications horizontal bar chart
- **Treatment Duration**: Distribution of treatment duration periods

#### Patients Tab
- **Patient Records**: Paginated table showing patient details (20 per page)
- **Columns**: Serial No., Name, Age, Gender, Village, Location, Date, BP, RBS, Diagnosis
- **Pagination**: Use Previous/Next buttons to navigate between pages

---

## 📊 Understanding the Health Status Categories

### Blood Pressure Status
- **Normal**: Systolic < 120 AND Diastolic < 80
- **Prehypertension**: Systolic 120-139 OR Diastolic 80-89
- **Hypertension**: Systolic ≥ 140 OR Diastolic ≥ 90

Example: If BP is 140/90, it's categorized as **Hypertension**

### RBS Levels (mg/dL)
- **Normal**: < 140
- **Prediabetic**: 140-199
- **Diabetic**: ≥ 200

### BMI Categories
- **Underweight**: BMI < 18.5
- **Normal**: BMI 18.5-24.9
- **Overweight**: BMI 25-29.9
- **Obese**: BMI ≥ 30

BMI is calculated as: `Weight (kg) / (Height (m))²`

---

## 🔄 Filter Examples

### Example 1: View Female Patients Aged 31-45 in Maharashtra
```
1. Select "Female" from Gender
2. Select "31-45" from Age Group
3. Select "India" from Country
4. Select "Maharashtra" from State
5. Click "Apply Filters"
```

### Example 2: View Patients from January 2025
```
1. Select "2025-01-01" as From Date
2. Select "2025-01-31" as To Date
3. Click "Apply Filters"
```

### Example 3: View Specific District Data
```
1. Select Country → State → District
2. Don't select Taluka (to see all talukas in district)
3. Click "Apply Filters"
```

---

## 📱 Mobile Access

The dashboard is fully responsive:
- **Desktop**: Full grid layouts (2-3 columns)
- **Tablet**: Adjusted layouts (2 columns)
- **Mobile**: Single column layout (stacked)

All charts, filters, and tables adapt to screen size automatically.

---

## ⚡ Performance Tips

1. **Limit Date Range**: Use specific date ranges for faster loading
2. **Use Filters**: Filter by location or demographics reduces data
3. **Tab Selection**: Switch tabs as needed (data loads on demand)
4. **Clear Filters**: Use "Reset" to see all data without filters
5. **Wait for Data**: Loading spinner shows while fetching data

---

## 🔍 Troubleshooting

### "Permission Denied" or Menu Not Showing
- **Issue**: Your account doesn't have `analytics_view` permission
- **Solution**: Contact admin to assign Analytics Viewer role or analytics_view permission

### Dashboard Not Loading
- **Issue**: JavaScript not loading or network error
- **Solution**:
  - Refresh the page (Ctrl+R or Cmd+R)
  - Check browser console (F12) for errors
  - Clear browser cache

### Charts Not Showing Data
- **Issue**: Filters applied but no results
- **Solution**:
  - Click "Reset" to clear filters
  - Try a broader date range
  - Check if patient data exists for selected filters

### Cascading Dropdowns Not Working
- **Issue**: State/District/Taluka dropdowns disabled
- **Solution**:
  - Select Country first
  - State will enable automatically
  - Select State to enable District
  - Select District to enable Taluka

### Slow Performance
- **Issue**: Charts taking long to load
- **Solution**:
  - Use more specific filters
  - Reduce date range
  - Try a different browser
  - Check internet connection

---

## 🔐 Data Access & Privacy

- **Dashboard Data**: Only shows patients in your database
- **Permissions**: Based on your assigned role
- **Data Accuracy**: Based on patient records entered in the system
- **Real-Time**: Updates instantly when filters are applied

---

## 📞 Getting Help

For more detailed information, refer to:
- **Quick Reference**: [API_README.md](API_README.md)
- **Complete Documentation**: [ANALYTICS_API_DOCUMENTATION.md](ANALYTICS_API_DOCUMENTATION.md)
- **Testing Guide**: [API_TESTING_GUIDE.md](API_TESTING_GUIDE.md)
- **Full Index**: [API_DOCUMENTATION_INDEX.md](API_DOCUMENTATION_INDEX.md)

---

## ✨ Key Features Recap

✅ 14 interactive charts
✅ Real-time data filtering
✅ Cascading location selection
✅ Responsive mobile design
✅ Role-based access control
✅ Beautiful data visualization
✅ Paginated patient records
✅ Health status categorization
✅ Fast performance
✅ Easy to use interface

---

**Happy analyzing! 📊**

For developers: See [ANALYTICS_API_DOCUMENTATION.md](ANALYTICS_API_DOCUMENTATION.md) for API details and integration examples.
