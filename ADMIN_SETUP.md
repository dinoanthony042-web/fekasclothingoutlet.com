# Admin Dashboard Setup

## Overview
The application now has separate client and admin dashboards with proper role-based access control.

## Features

### Client Dashboard
- Regular user dashboard at `/dashboard`
- Shows user orders and wishlist
- Accessible to authenticated users

### Admin Dashboard
- Admin panel at `/admin` (development) or `admin.yourdomain.com` (production)
- Shows store analytics, recent orders, and products
- Only accessible to users with `admin` role

## Setup Instructions

### 1. Database Migration
Run the migration to add the role field to users table:
```bash
php artisan migrate
```

### 2. Create Admin User
Use the seeder to create an admin user:
```bash
php artisan db:seed --class=AdminUserSeeder
```

Or manually promote a user to admin:
```bash
php artisan make:admin user@example.com
```

### 3. Admin Credentials
Default admin account:
- Email: `admin@feka.com`
- Password: `password`

### 4. Access Admin Dashboard
- **Development**: Visit `http://127.0.0.1:8000/admin`
- **Production**: Configure subdomain `admin.yourdomain.com` pointing to your app

## Role Management

### User Roles
- `user`: Regular customers (default)
- `admin`: Store administrators

### Helper Methods
```php
$user->isAdmin()  // Check if user is admin
$user->isUser()   // Check if user is regular user
```

## Middleware
- `admin` middleware protects admin routes
- Automatically redirects non-admin users with 403 error

## Routes
- Client routes: Standard web routes
- Admin routes: Prefixed with `/admin` in development, subdomain in production

## Views
- Client layout: `resources/views/layouts/app.blade.php`
- Admin layout: `resources/views/layouts/admin.blade.php`
- Admin dashboard: `resources/views/admin/dashboard.blade.php`

## Security
- Admin routes require authentication + admin role
- CSRF protection enabled
- Session-based authentication

## Production Deployment
1. Set up subdomain `admin.yourdomain.com`
2. Update `config/app.php` with your domain
3. Ensure web server routes subdomain to same app
4. Run `php artisan config:cache` after domain changes