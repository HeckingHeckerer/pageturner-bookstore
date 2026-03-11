# TODO - PageTurner Bookstore Updates

## Completed Tasks

### 1. User Profile Dropdown in Navigation
- Updated navigation.blade.php with dropdown component
- User's name is now clickable
- Dropdown displays: name, email, shipping address (if available), profile settings link, and logout option

### 2. Admin Login Security Bug Fix
- Added hidden `login_type` input fields to login forms
- Added server-side validation to check admin role
- Returns error "You are not authorized to access the admin area." for non-admin users

### 3. Notification System

#### Order Notifications
- **OrderPlacedNotification**: Notifies customers when an order is placed
- **OrderStatusChangedNotification**: Notifies customers when order status changes
- **NewOrderAdminNotification**: Notifies administrators when a new order is created

#### Review Notifications
- **NewReviewAdminNotification**: Notifies administrators when a new review is submitted

#### Files Created:
- `app/Notifications/OrderPlacedNotification.php`
- `app/Notifications/OrderStatusChangedNotification.php`
- `app/Notifications/NewOrderAdminNotification.php`
- `app/Notifications/NewReviewAdminNotification.php`

#### Controllers Updated:
- `app/Http/Controllers/OrderController.php` - Added notification calls in `store()` and `updateStatus()` methods
- `app/Http/Controllers/ReviewController.php` - Added notification call in `store()` method

