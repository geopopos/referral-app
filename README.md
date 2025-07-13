# Volume Up Agency - Referral Management System

A comprehensive referral management web application built with Laravel 11, Tailwind CSS, and Laravel Breeze.

## Features

### 🔐 Authentication & Authorization
- User registration and login with Laravel Breeze
- Email verification
- Role-based access control (Partner/Admin)
- Admin middleware protection

### 👥 Partner Management
- Automatic referral code generation on registration
- Partner dashboard with performance metrics
- Referral link sharing with copy functionality
- Payout method configuration (ACH/PayPal)

### 📊 Lead Management
- Public referral form with lead capture
- Lead status tracking (New, Qualified, Closed)
- Admin lead management interface
- Lead assignment to referral partners

### 💰 Commission System
- Automatic commission creation for referred leads
- Multiple commission types (Revenue Share, Bonuses)
- Commission status workflow (Pending, Approved, Paid)
- Admin commission management

### 📈 Admin Dashboard
- Complete system overview
- Partner performance analytics
- Lead and commission management
- CSV export functionality

## Installation

1. **Clone and setup the project:**
   ```bash
   cd referral-app
   composer install
   npm install && npm run build
   ```

2. **Configure environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Setup database:**
   ```bash
   # Configure your database in .env file
   php artisan migrate
   php artisan db:seed --class=ReferralSystemSeeder
   ```

4. **Start the development server:**
   ```bash
   php artisan serve
   ```

## Demo Accounts

### Admin Access
- **Email:** admin@volumeupagency.com
- **Password:** password
- **Access:** Full admin dashboard, lead management, commission management

### Partner Accounts
- **John Smith:** john@example.com / password (Code: john123)
- **Sarah Johnson:** sarah@example.com / password (Code: sarah456)
- **Mike Davis:** mike@example.com / password (Code: mike789)

## Key Routes

### Public Routes
- `/` - Welcome page
- `/register` - Partner registration
- `/login` - User login
- `/referral` - Public referral form (accepts ?ref= parameter)

### Partner Routes (Authenticated)
- `/dashboard` - Partner dashboard with metrics and referral link

### Admin Routes (Admin only)
- `/admin` - Admin dashboard
- `/admin/leads` - Lead management
- `/admin/commissions` - Commission management
- `/admin/partners/{id}` - Partner details
- `/admin/export/leads` - CSV export

## Database Schema

### Users Table
- Standard Laravel auth fields
- `role` (partner/admin)
- `referral_code` (unique identifier)
- `payout_method` (ach/paypal)

### Leads Table
- Contact information (name, email, phone, company)
- `referral_code` (links to referring partner)
- `status` (new/qualified/closed)
- `how_heard_about_us` (optional feedback)

### Commissions Table
- Links user and lead
- `type` (rev_share/bonus)
- `amount` and `status` (pending/approved/paid)
- `paid_at` timestamp

## Features Implemented

✅ **Partner Registration & Authentication**
✅ **Unique Referral Code Generation**
✅ **Referral Link Sharing**
✅ **Lead Capture Form**
✅ **Partner Dashboard with Metrics**
✅ **Admin Dashboard**
✅ **Lead Status Management**
✅ **Commission Tracking**
✅ **Role-based Access Control**
✅ **Responsive Design with Tailwind CSS**
✅ **Sample Data Seeding**
✅ **CSV Export Functionality**

## Technology Stack

- **Backend:** Laravel 11
- **Frontend:** Blade Templates, Tailwind CSS
- **Authentication:** Laravel Breeze
- **Database:** SQLite (default) / MySQL / PostgreSQL
- **Styling:** Tailwind CSS with responsive design

## File Structure

```
referral-app/
├── app/
│   ├── Http/Controllers/
│   │   ├── AdminController.php
│   │   ├── DashboardController.php
│   │   └── ReferralController.php
│   ├── Http/Middleware/
│   │   └── AdminMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Lead.php
│       └── Commission.php
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
├── resources/views/
│   ├── admin/
│   ├── referral/
│   └── dashboard.blade.php
└── routes/web.php
```

## Next Steps

For production deployment, consider:
- Email configuration for notifications
- Payment gateway integration (Stripe/PayPal)
- Advanced reporting and analytics
- API endpoints for mobile apps
- Automated commission calculations
- Multi-level referral tracking

## Support

For questions or issues, contact the development team or refer to the Laravel documentation.
