# Admin Panel Setup & Documentation

## Project Overview

A comprehensive **Laravel 12 Admin Panel** with modern Bootstrap 5.3 design, featuring:
- User authentication (login, registration)
- Role-based access control (Admin & User roles)
- User management (CRUD operations)
- Role management
- Activity logs/audit trail
- Dashboard with statistics and charts
- Responsive, modern UI

---

## Getting Started

### Prerequisites
- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js & npm

### Installation Steps

1. **Database Setup**
   ```bash
   # Migrations have already been run
   # Database: borderless_report
   # Tables created: users, roles, role_user, activity_logs, password_reset_tokens, sessions
   ```

2. **Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Configuration**
   - The `.env` file is already configured with MySQL
   - Database: `borderless_report`
   - Session & Cache drivers: database

4. **Run Migrations & Seeders** (if needed to reset)
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Start Development Server**
   ```bash
   composer run dev
   ```

---

## Default Credentials

### Admin Account
- **Email:** `admin@admin.com`
- **Password:** `password`
- **Role:** Admin

Access the admin panel at: `http://localhost/admin/dashboard`

---

## Features & Routes

### Public Routes
| Route | Purpose |
|-------|---------|
| `/` | Welcome page |
| `/login` | User login |
| `/register` | User registration |
| `/logout` | Logout (POST) |

### Admin Routes (Protected)
All admin routes require authentication and admin role. Prefix: `/admin`

| Route | Method | Purpose |
|-------|--------|---------|
| `/admin/dashboard` | GET | Admin dashboard with statistics |
| `/admin/users` | GET | List all users |
| `/admin/users/create` | GET | Create new user form |
| `/admin/users` | POST | Store new user |
| `/admin/users/{id}/edit` | GET | Edit user form |
| `/admin/users/{id}` | PUT | Update user |
| `/admin/users/{id}` | DELETE | Delete user |
| `/admin/roles` | GET | View roles and user counts |
| `/admin/activity-logs` | GET | View activity logs |

---

## Database Schema

### Users Table
```
id (primary key)
name
email (unique)
email_verified_at
password (hashed)
remember_token
created_at
updated_at
```

### Roles Table
```
id (primary key)
name (unique) - 'admin' or 'user'
description
created_at
updated_at
```

### Role_User Pivot Table
```
id (primary key)
user_id (foreign key → users)
role_id (foreign key → roles)
created_at
updated_at
```

### Activity_Logs Table
```
id (primary key)
user_id (foreign key → users)
action - login, logout, registered, user_created, user_updated, user_deleted
description
ip_address
user_agent
created_at
updated_at
```

---

## File Structure

### Controllers
- `app/Http/Controllers/AuthController.php` - Authentication (login, register, logout)
- `app/Http/Controllers/Admin/DashboardController.php` - Admin dashboard
- `app/Http/Controllers/Admin/UserController.php` - User CRUD operations
- `app/Http/Controllers/Admin/RoleController.php` - Role management
- `app/Http/Controllers/Admin/ActivityLogController.php` - Activity logs

### Models
- `app/Models/User.php` - User with relationships
- `app/Models/Role.php` - Role model with user relationship
- `app/Models/ActivityLog.php` - Activity log model with logging utility

### Views
- `resources/views/layouts/app.blade.php` - Main layout with navbar
- `resources/views/layouts/admin.blade.php` - Admin layout with sidebar
- `resources/views/auth/login.blade.php` - Login page
- `resources/views/auth/register.blade.php` - Registration page
- `resources/views/admin/dashboard.blade.php` - Dashboard with charts
- `resources/views/admin/users/index.blade.php` - Users list
- `resources/views/admin/users/create.blade.php` - Create user form
- `resources/views/admin/users/edit.blade.php` - Edit user form
- `resources/views/admin/roles/index.blade.php` - Roles list
- `resources/views/admin/activity-logs/index.blade.php` - Activity logs

### Middleware
- `app/Http/Middleware/AdminMiddleware.php` - Check admin role

### Request Classes
- `app/Http/Requests/LoginRequest.php` - Login validation
- `app/Http/Requests/RegisterRequest.php` - Registration validation
- `app/Http/Requests/UserStoreRequest.php` - Create user validation
- `app/Http/Requests/UserUpdateRequest.php` - Update user validation

### Migrations
- `database/migrations/2024_12_20_000001_create_roles_table.php`
- `database/migrations/2024_12_20_000002_create_role_user_table.php`
- `database/migrations/2024_12_20_000003_create_activity_logs_table.php`

### Seeders
- `database/seeders/RoleSeeder.php` - Seeds admin and user roles
- `database/seeders/AdminUserSeeder.php` - Creates default admin user

---

## User Roles & Permissions

### Admin Role
- Access to admin panel
- View all users
- Create new users
- Edit users
- Delete users
- Manage roles
- View activity logs

### User Role
- Standard user account
- Cannot access admin panel
- Cannot modify other users

---

## Model Relationships

### User Model
```php
// Get all roles for a user
$user->roles();

// Get activity logs for a user
$user->activityLogs();

// Check if user has specific role
$user->hasRole('admin');

// Check if user is admin
$user->isAdmin();

// Check if user is regular user
$user->isUser();
```

### Role Model
```php
// Get all users with this role
$role->users();
```

### ActivityLog Model
```php
// Get the user who performed the activity
$log->user();

// Log an activity
ActivityLog::log($userId, 'action', 'description', $request);
```

---

## Activity Logging

Activities are automatically logged for:
- User login
- User logout
- User registration
- User creation (by admin)
- User update (by admin)
- User deletion (by admin)

### Manual Activity Logging
```php
use App\Models\ActivityLog;

ActivityLog::log(
    auth()->id(),           // User ID
    'custom_action',        // Action name
    'Description here',     // Description
    $request                // Request object (for IP & user agent)
);
```

---

## Styling & Design

### Color Scheme
- **Primary:** #4e73df (Blue)
- **Success:** #1cc88a (Green)
- **Info:** #36b9cc (Cyan)
- **Warning:** #f6c23e (Yellow)
- **Danger:** #e74a3b (Red)
- **Dark:** #2c3e50 (Dark Blue-Gray)

### Framework
- **Bootstrap 5.3** - Responsive UI framework
- **Bootstrap Icons** - Icon library
- **Chart.js** - Charts and graphs
- **TailwindCSS** - Utility CSS (optional)

### Responsive Breakpoints
- Mobile: < 768px (collapsed sidebar)
- Tablet: 768px - 992px
- Desktop: > 992px

---

## Features Explained

### Dashboard
- **Statistics Cards:** Total users, admin count, regular user count, new users this week
- **User Growth Chart:** Line chart showing user growth over last 30 days (Chart.js)
- **Recent Activity:** Table showing last 10 activity logs

### Users Management
- **List Users:** Paginated (15 per page), searchable by name/email
- **Create User:** Admin can create new users with name, email, password, and role
- **Edit User:** Admin can update user details and role
- **Delete User:** Admin can delete users with confirmation modal
- **Roles Display:** Show which role(s) each user has

### Roles Management
- **View Roles:** Display all available roles
- **User Count:** Show how many users have each role

### Activity Logs
- **Filter by User:** Select a user to view their activities
- **Filter by Action:** Search for specific action types
- **Pagination:** 20 logs per page
- **Detailed Information:** User name/email, action type, description, IP address, timestamp

---

## Security Features

✅ **CSRF Protection** - Built-in Laravel CSRF tokens
✅ **Password Hashing** - bcrypt with 12 rounds
✅ **Role-Based Access Control** - Middleware protects admin routes
✅ **Session Management** - Database-driven sessions
✅ **Input Validation** - Request classes validate all inputs
✅ **Activity Logging** - Audit trail for admin actions
✅ **Unique Constraints** - Email uniqueness, role-user unique combination

---

## Common Tasks

### Create a New User Programmatically
```php
use App\Models\User;
use App\Models\Role;

$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => bcrypt('password'),
]);

$role = Role::where('name', 'user')->first();
$user->roles()->attach($role);
```

### Check User Permissions
```php
// Check if user has admin role
if (auth()->user()->isAdmin()) {
    // Allow access
}

// Check specific role
if (auth()->user()->hasRole('admin')) {
    // Allow access
}

// Check if user is regular user
if (auth()->user()->isUser()) {
    // Regular user
}
```

### Query Users by Role
```php
use App\Models\Role;

$adminRole = Role::where('name', 'admin')->first();
$admins = $adminRole->users()->get();

// Or using where clause
$admins = \App\Models\User::whereHas('roles', function($q) {
    $q->where('name', 'admin');
})->get();
```

### View Activity Logs
```php
use App\Models\ActivityLog;

// Get all activities
$activities = ActivityLog::with('user')->get();

// Get user's activities
$userActivities = ActivityLog::where('user_id', $userId)->get();

// Get activities in date range
$activities = ActivityLog::whereBetween('created_at', [$start, $end])->get();
```

---

## Troubleshooting

### Issue: "Access denied. Admin privileges required."
**Solution:** User is logged in but doesn't have admin role. Ensure user has admin role assigned.

### Issue: "Call to undefined method roles()"
**Solution:** Ensure User model has the relationships defined. Run migrations and check models.

### Issue: Views not loading/CSS not applying
**Solution:** Rebuild assets:
```bash
npm run build
# or for development
npm run dev
```

### Issue: Database migration errors
**Solution:** Ensure MySQL is running and `.env` database credentials are correct:
```bash
php artisan migrate:reset
php artisan migrate
php artisan db:seed
```

---

## Next Steps & Enhancements

### Possible Future Enhancements
- [ ] Fine-grained permissions system (ACL)
- [ ] Soft deletes for users (archive instead of delete)
- [ ] Two-factor authentication (2FA)
- [ ] Password reset functionality
- [ ] Email verification for registration
- [ ] User profile page
- [ ] Admin settings/preferences
- [ ] Export user data (CSV/PDF)
- [ ] Advanced filtering in users list
- [ ] User activity reports
- [ ] Dark mode toggle
- [ ] Multi-language support
- [ ] API authentication (Sanctum/Passport)

---

## Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Bootstrap Documentation](https://getbootstrap.com/docs)
- [Chart.js Documentation](https://www.chartjs.org/docs/latest/)
- [Blade Template Engine](https://laravel.com/docs/blade)

---

## Support

For issues or questions:
1. Check the troubleshooting section
2. Review Laravel and Bootstrap documentation
3. Check recent error logs in `storage/logs/`

---

**Last Updated:** December 20, 2024
**Admin Panel Version:** 1.0.0
**Laravel Version:** 12.0
