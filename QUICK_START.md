# Quick Start Guide - Admin Panel

## Setup (One-Time)

### 1. Install Dependencies
```bash
cd /Users/avinashvidyanand/Documents/projects/borderless/borderless
composer install
npm install
```

### 2. Database Configuration
Your `.env` is already configured for MySQL:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=borderless_report
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Run Migrations & Seeds
```bash
php artisan migrate
php artisan db:seed
```

---

## Running the Application

### Development Mode
```bash
composer run dev
```

This will start:
- PHP Laravel server (http://localhost:8000)
- Vite dev server
- Queue listener
- Log viewer

---

## Accessing the Application

### 1. **Homepage**
- URL: `http://localhost:8000`
- Shows welcome page with login/register links

### 2. **Login Page**
- URL: `http://localhost:8000/login`
- **Admin Credentials:**
  - Email: `admin@admin.com`
  - Password: `password`

### 3. **Registration Page**
- URL: `http://localhost:8000/register`
- Users can create new accounts (assigned 'user' role)

### 4. **Admin Dashboard**
- URL: `http://localhost:8000/admin/dashboard`
- Only accessible to admin users
- Shows statistics, user growth chart, and recent activity

### 5. **Users Management**
- URL: `http://localhost:8000/admin/users`
- Create, edit, delete users
- Assign roles to users

### 6. **Roles**
- URL: `http://localhost:8000/admin/roles`
- View all roles and user counts

### 7. **Activity Logs**
- URL: `http://localhost:8000/admin/activity-logs`
- View user activities with filtering

---

## Key Features

### Authentication
- ✅ Login with email/password
- ✅ User registration
- ✅ Logout
- ✅ Activity logging

### User Management (Admin Only)
- ✅ List all users with search
- ✅ Create new users
- ✅ Edit user details & roles
- ✅ Delete users
- ✅ Assign admin or user role

### Dashboard
- ✅ Total user count
- ✅ Admin/Regular user counts
- ✅ New users this week
- ✅ User growth chart (30 days)
- ✅ Recent activity logs

### Roles
- ✅ **Admin** - Full access to admin panel
- ✅ **User** - Regular user (no admin access)

### Activity Logs
- ✅ Log all user actions
- ✅ Filter by user or action
- ✅ View IP address and timestamp
- ✅ Pagination support

---

## Database Tables

| Table | Purpose |
|-------|---------|
| `users` | User accounts |
| `roles` | Available roles (admin, user) |
| `role_user` | User-role relationships |
| `activity_logs` | User activity audit trail |
| `sessions` | Session management |
| `password_reset_tokens` | Password reset tokens |

---

## Useful Commands

### Database
```bash
# Run migrations
php artisan migrate

# Seed database with roles and admin user
php artisan db:seed

# Rollback migrations
php artisan migrate:rollback

# Reset database (drops all tables and migrates fresh)
php artisan migrate:reset
php artisan migrate
php artisan db:seed
```

### Tinker (Interactive Shell)
```bash
php artisan tinker

# Example queries:
>>> $admin = \App\Models\User::first()
>>> $admin->roles->pluck('name')
>>> \App\Models\ActivityLog::all()
>>> \App\Models\Role::all()
```

### Queue
```bash
# Listen to queued jobs
php artisan queue:listen
```

### Cache
```bash
# Clear all caches
php artisan cache:clear

# Clear views
php artisan view:clear
```

### Assets
```bash
# Build for production
npm run build

# Watch for changes (development)
npm run dev

# Compile once
npm run dev
```

---

## Common Scenarios

### Scenario 1: Login as Admin
1. Go to `http://localhost:8000/login`
2. Enter:
   - Email: `admin@admin.com`
   - Password: `password`
3. Check "Remember me" (optional)
4. Click "Login"
5. You're redirected to `/admin/dashboard`

### Scenario 2: Create a New User (as Admin)
1. Login as admin
2. Navigate to "Users" in sidebar
3. Click "Add New User"
4. Fill in:
   - Full Name
   - Email
   - Password (min 8 chars)
   - Confirm Password
   - Role (Admin or User)
5. Click "Create User"

### Scenario 3: Register as Regular User
1. Go to `http://localhost:8000/register`
2. Fill in all fields
3. Click "Register"
4. You're logged in and redirected to admin dashboard
5. Regular users can't access admin features (will see "Access denied")

### Scenario 4: View User Activity Logs
1. Login as admin
2. Click "Activity Logs" in sidebar
3. Optionally filter by:
   - User (dropdown)
   - Action (text search)
4. View detailed activity records

---

## File Modifications Made

### New Files Created: 45+
- 3 Migration files
- 2 Model files (Role, ActivityLog)
- 5 Controller files
- 4 Request validation classes
- 1 Middleware file
- 15+ Blade template files
- 2 Seeder files

### Modified Files: 3
- `routes/web.php` - Added all routes
- `app/Models/User.php` - Added relationships
- `bootstrap/app.php` - Registered middleware
- `database/seeders/DatabaseSeeder.php` - Added seeders

---

## Troubleshooting

### "Port 8000 already in use"
```bash
# Use different port
php artisan serve --port=8001
```

### "MySQL connection refused"
```bash
# Check MySQL is running
# macOS with Homebrew:
brew services start mysql

# Check credentials in .env match your MySQL setup
```

### "Class not found" errors
```bash
# Clear autoloader cache
composer dump-autoload

# Clear application cache
php artisan cache:clear
php artisan config:cache
```

### Assets not loading / Vite errors
```bash
# Reinstall Node modules
rm -rf node_modules package-lock.json
npm install

# Rebuild
npm run dev
```

---

## Next Steps

1. ✅ Access the admin panel at `http://localhost:8000/admin/dashboard`
2. ✅ Test login/logout functionality
3. ✅ Create some test users
4. ✅ Try filtering and searching users
5. ✅ Check activity logs
6. ✅ Review the ADMIN_PANEL_SETUP.md for detailed documentation

---

## Support Files

- **ADMIN_PANEL_SETUP.md** - Complete documentation
- **Plan File** - `/Users/avinashvidyanand/.claude/plans/enumerated-snuggling-pond.md`

---

**Ready to use!** 🚀
