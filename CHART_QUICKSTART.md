# Chart.HTML - Quick Start Guide

## 🚀 Getting Started in 3 Steps

### Step 1: Ensure Laravel App is Running
```bash
# Terminal 1: Start Laravel dev server
cd borderless
php artisan serve

# Output should show:
# Laravel development server started: http://127.0.0.1:8000
```

### Step 2: Open Chart Dashboard
```
Open in browser: http://localhost:8000/chart.html
```

### Step 3: Wait for Dashboard to Load
```
You should see:
- "Authenticating..." message (1-2 seconds)
- "Dashboard loaded successfully!" message
- 6 KPI cards with numbers
- 5 interactive charts with data
- Patient table with pagination
```

That's it! 🎉

---

## ✅ Verification Checklist

When dashboard loads successfully:

- [ ] KPI Cards show data (Total Patients, Gender breakdown, etc.)
- [ ] Gender Distribution chart displays
- [ ] Age Group Distribution chart displays
- [ ] Blood Pressure Status chart displays
- [ ] RBS Levels chart displays
- [ ] BMI Analysis chart displays
- [ ] Top Villages chart displays
- [ ] Patient table shows at least 1 patient
- [ ] Browser console has NO errors (F12 > Console)
- [ ] Network tab shows all API calls as 200 OK (F12 > Network)

---

## 🎯 Using the Dashboard

### View Statistics
All KPI cards update automatically based on database data:
- **Total Patients**: Count of all patients
- **Male/Female Patients**: Gender breakdown
- **Avg Age**: Average patient age
- **Samples Collected**: Count of blood samples
- **Referrals Made**: Count of referrals

### Apply Filters
1. Select filters from dropdown at top:
   - Gender (Male, Female, Other, or All)
   - Age Group (0-17, 18-30, 31-45, 46-60, 60+, or All)
   - Date Range (From date, To date)

2. Click **Apply Filters** button
3. All charts and tables update instantly
4. KPI cards show filtered data

### Reset Filters
Click **Reset Filters** button to clear all selections and view all data

### Browse Patients
- Table shows 10 patients per page
- Use pagination buttons: First, Previous, Next, Last
- Current page displayed at bottom

---

## 🔧 Troubleshooting

### Problem: "Failed to authenticate"
```
Error: Login failed
```

**Check**:
1. Is Laravel running on http://localhost:8000?
   ```bash
   curl http://localhost:8000
   # Should return HTML, not connection refused
   ```

2. Is admin user created in database?
   ```bash
   php artisan tinker
   >>> App\Models\User::where('email', 'admin@admin.com')->first()
   # Should show admin user
   ```

3. Is password correct? Check .env or:
   ```bash
   php artisan tinker
   >>> $user = App\Models\User::where('email', 'admin@admin.com')->first();
   >>> Hash::make('password')  # Compare with user's password_hash
   ```

**Solution**: Update credentials in chart.html or reset user password:
```bash
php artisan tinker
>>> $user = App\Models\User::where('email', 'admin@admin.com')->first();
>>> $user->update(['password' => Hash::make('password')]);
```

### Problem: "Dashboard loaded but no charts/data"
```
KPI cards show 0, charts are empty
```

**Causes**:
- No patient data in database
- Filters too restrictive (no matching data)

**Solutions**:
1. Verify patient data exists:
   ```bash
   php artisan tinker
   >>> App\Models\Patient::count()
   # Should be > 0
   ```

2. Click **Reset Filters** to remove any filters
3. Run seeders to add test data:
   ```bash
   php artisan db:seed --class=PatientSeeder
   ```

4. Refresh page (F5)

### Problem: "Patient table shows 'No patients found'"
Same as above - check patient data and filters.

### Problem: "Blank page, nothing loads"
```
Empty white page, no error message
```

**Check**:
1. Open DevTools (F12) > Console tab
2. Look for JavaScript errors
3. Common errors:
   - `Chart is not defined` → Chart.js CDN failed to load
   - `Cannot read property 'map'` → API returned wrong data format
   - Other API errors → Check Network tab

**Solutions**:
- Refresh page (Ctrl+Shift+R to hard refresh)
- Check internet connection (CDN might be down)
- Check browser console for specific errors

### Problem: "Charts showing but no data inside"
```
Chart containers visible but empty/blank
```

**Causes**:
- API returned empty data
- Filter parameters incorrect
- Database fields named differently

**Check**:
1. Open DevTools (F12) > Network tab
2. Look for `/admin/analytics/api/stats` call
3. Click on it and view Response
4. Should show JSON like:
   ```json
   {
       "total_patients": 150,
       "male_patients": 85,
       "female_patients": 65,
       ...
   }
   ```

**Solution**:
- If response is empty: Add patient data to database
- If response shows error: Check Laravel logs
  ```bash
  tail -f storage/logs/laravel.log
  ```

---

## 📊 Understanding the Charts

### Gender Distribution
- **Chart Type**: Doughnut
- **Colors**: Purple, Pink, Blue
- **Shows**: Number of Male, Female, Other patients
- **Use**: Quick overview of gender balance

### Age Group Distribution
- **Chart Type**: Bar
- **Colors**: Green
- **Shows**: Number of patients in each age group
- **Use**: Identify age demographics

### Blood Pressure Status
- **Chart Type**: Pie
- **Colors**: Green (Normal), Orange (Prehypertension), Red (Hypertension)
- **Shows**: BP health status distribution
- **Use**: Monitor hypertension cases

### RBS Levels
- **Chart Type**: Pie
- **Colors**: Blue (Normal), Purple (Prediabetic), Pink (Diabetic)
- **Shows**: Blood sugar levels distribution
- **Use**: Track diabetes prevalence

### BMI Analysis
- **Chart Type**: Doughnut
- **Colors**: Green, Blue, Orange, Red
- **Shows**: Weight categories (Underweight, Normal, Overweight, Obese)
- **Use**: Monitor obesity rates

### Top Villages
- **Chart Type**: Horizontal Bar
- **Colors**: Orange
- **Shows**: Top 10 villages by patient count
- **Use**: Geographic patient distribution

---

## 💡 Tips & Tricks

### Tip 1: Export Data
Click on charts to see exact numbers. Use browser's Print function (Ctrl+P) to save as PDF.

### Tip 2: Date Range Filtering
Use date filters to analyze trends over time:
1. Select "Date From": 2025-01-01
2. Select "Date To": 2025-03-31
3. Click Apply Filters
4. See Q1 statistics

### Tip 3: Gender-Specific Analytics
Filter by gender to compare male vs female statistics:
1. Select Gender: Male
2. Click Apply Filters
3. Analyze male-specific health metrics
4. Repeat for Female

### Tip 4: Age Group Analysis
Track health metrics by age:
1. Select Age Group: 60+
2. Click Apply Filters
3. Analyze senior population health
4. Repeat for other age groups

### Tip 5: Real-time Monitoring
Dashboard reloads when you apply filters - use for live monitoring during patient intake.

---

## 🎓 Learning Path

1. **Understand the Data**
   - View all data first (no filters)
   - Understand what each KPI means

2. **Explore Filters**
   - Apply one filter at a time
   - See how data changes

3. **Analyze Trends**
   - Use date range filters
   - Compare male vs female data
   - Analyze by age group

4. **Export Reports**
   - Take screenshots
   - Print to PDF
   - Share with stakeholders

---

## 📞 Support

### File Location
```
/Users/avinashvidyanand/Documents/projects/borderless/borderless/chart.html
```

### Related Files
- `CHART_SETUP_GUIDE.md` - Detailed setup and customization
- `CHART_FIXES_APPLIED.md` - Technical details of fixes
- `API_TESTING_GUIDE.md` - API documentation

### Browser DevTools
- **F12** - Open DevTools
- **Console** - View error messages
- **Network** - View API calls and responses
- **Application** - View cookies/sessions

### Laravel Logs
```bash
tail -f storage/logs/laravel.log
```

---

## �� Features Summary

| Feature | Status | Details |
|---------|--------|---------|
| Auto-Login | ✅ | Automatically authenticates with admin account |
| KPI Cards | ✅ | 6 real-time metrics |
| Charts | ✅ | 5 interactive data visualizations |
| Patient Table | ✅ | Paginated list with filtering |
| Filters | ✅ | Gender, Age Group, Date Range |
| Responsive Design | ✅ | Works on desktop, tablet, mobile |
| Export | ✅ | Print/PDF via browser |
| Real-time Updates | ✅ | Data refreshes on filter change |
| No CORS Issues | ✅ | Works from any origin |
| Standalone File | ✅ | No build/compile needed |

---

## 🎉 You're Ready!

1. ✅ Laravel running on localhost:8000
2. ✅ Patient data in database
3. ✅ chart.html ready to use
4. ✅ Open `http://localhost:8000/chart.html`
5. ✅ Enjoy your analytics dashboard!

Happy analyzing! 📈

---

**Last Updated**: 2025-12-29
**Status**: ✅ Fully functional, all CORS issues resolved
