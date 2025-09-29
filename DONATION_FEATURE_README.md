# Donation Feature - BOOK_SOCIAL_NETWORK

## Overview
A comprehensive donation feature has been added to the Book Social Network application, allowing users to donate books and administrators to manage these donations through approval/rejection workflows.

## Features Implemented

### 🔧 Backend Architecture
- **Model**: `Donation` with relationships to `User` (donor and approver)
- **Migration**: Complete database schema with status tracking, metadata, and admin notes
- **Controller**: `DonationController` with full CRUD operations for both users and admins
- **Routes**: Protected routes with proper middleware and role-based access

### 👤 User Functionality
1. **Donation Creation**
   - Form to submit book donations with image upload
   - Fields: title, author, genre, condition, description, book image
   - Real-time image preview
   - Comprehensive validation

2. **Donation Management**
   - View all personal donations with status indicators
   - Edit pending donations
   - Delete pending donations
   - Detailed view of each donation with admin feedback

3. **Status Tracking**
   - Pending: Yellow badge with clock icon
   - Approved: Green badge with checkmark
   - Rejected: Red badge with X, includes admin rejection reason

### 🔐 Admin Functionality
1. **Donation Dashboard**
   - Statistics cards showing total, pending, approved, and rejected donations
   - Filter tabs to view donations by status
   - Grid layout with donation cards

2. **Donation Review**
   - Detailed view with book information, donor details, and submission timeline
   - Quick approval/rejection actions with modal confirmations
   - Optional admin notes for approvals
   - Required rejection reasons

3. **Batch Management**
   - Filter by status (all, pending, approved, rejected)
   - Pagination for large datasets
   - Quick action buttons on each card

## Database Schema

### `donations` Table
```sql
id                  - Primary key
user_id            - Foreign key to users (donor)
book_title         - String, book title
author             - String, author name
description        - Text, optional book description
genre              - String, optional genre
condition          - Enum: excellent, good, fair, poor
book_image         - String, optional image path
status             - Enum: pending, approved, rejected
admin_notes        - Text, optional admin feedback
approved_at        - Timestamp, when approved/rejected
approved_by        - Foreign key to users (admin)
created_at         - Timestamp
updated_at         - Timestamp
```

## File Structure

### Controllers
- `app/Http/Controllers/DonationController.php` - Main controller with CRUD operations

### Models
- `app/Models/Donation.php` - Donation model with relationships and scopes
- `app/Models/User.php` - Updated with donation relationships

### Views
#### User Views
- `resources/views/user/donations/index.blade.php` - User donation list
- `resources/views/user/donations/create.blade.php` - Donation creation form
- `resources/views/user/donations/show.blade.php` - Individual donation details
- `resources/views/user/donations/edit.blade.php` - Edit donation form

#### Admin Views
- `resources/views/admin/donations/index.blade.php` - Admin donation management
- `resources/views/admin/donations/show.blade.php` - Admin donation details

### Database
- `database/migrations/2025_09_28_165423_create_donations_table.php`

## Routes

### User Routes (Authenticated)
```php
GET    /donations              - List user's donations
GET    /donations/create       - Show donation form
POST   /donations              - Store new donation
GET    /donations/{id}         - Show donation details
GET    /donations/{id}/edit    - Edit donation form
PUT    /donations/{id}         - Update donation
DELETE /donations/{id}         - Delete donation
```

### Admin Routes (Admin Role Required)
```php
GET    /admin/donations                    - List all donations
GET    /admin/donations/{id}               - Show donation details
PATCH  /admin/donations/{id}/approve       - Approve donation
PATCH  /admin/donations/{id}/reject        - Reject donation
```

## Security Features
- **Authentication**: All donation routes require login
- **Authorization**: Admin routes require 'admin' role
- **Ownership**: Users can only view/edit their own donations
- **File Upload**: Image validation and secure storage
- **Status Protection**: Only pending donations can be edited/deleted

## UI/UX Features
- **Responsive Design**: Mobile-friendly layouts
- **Interactive Elements**: Image preview, modal confirmations
- **Status Indicators**: Color-coded badges and icons
- **Navigation**: Integrated into existing menu systems
- **Feedback**: Success/error messages and admin notes

## Installation & Setup

1. **Migration**: Already run during implementation
   ```bash
   php artisan migrate
   ```

2. **Admin User**: Already seeded
   ```bash
   php artisan db:seed --class=AdminUserSeeder
   ```
   - Email: `admin@socialbook.net`
   - Password: `Admin123!`

3. **Storage Link**: Already created
   ```bash
   php artisan storage:link
   ```

## Usage Instructions

### For Users
1. Navigate to "Donations" in the main menu or user dropdown
2. Click "Donner un Livre" to create a new donation
3. Fill out the form with book details and upload an image (optional)
4. Submit for admin review
5. Track status in the donations list
6. Edit or delete pending donations as needed

### For Administrators
1. Access admin panel via user dropdown (admin role required)
2. Navigate to "Donations" in admin sidebar
3. View all donations with filtering options
4. Click "Détails" to review individual donations
5. Use "Approuver" or "Rejeter" buttons to process donations
6. Add optional notes for approvals or required reasons for rejections

## Technical Highlights
- **Role-based Access Control** using Spatie Laravel Permission
- **File Upload Management** with image validation and preview
- **Status Workflow** with proper state management
- **Responsive Grid Layouts** with CSS Grid
- **Modal Interactions** for admin actions
- **Form Validation** with error handling and user feedback
- **Database Relationships** with proper foreign keys and constraints

The donation feature is fully functional and integrated into the existing application architecture, maintaining consistency with the project's design patterns and user experience standards.