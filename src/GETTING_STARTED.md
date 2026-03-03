# 🌐 DreamSpace Web Application - Setup Guide

Welkom! Je DreamSpace web applicatie is nu opgezet en gereed voor ontwikkeling.

---

## 📂 Project Structuur

```
E4_DreamSpace/
│
├── database/
│   ├── dreamspace_schema.sql          ✅ Database schema
│   ├── SETUP_INSTRUCTIONS.md          ✅ Database setup gids
│   └── README.md                      ✅ Database documentatie
│
└── src/                               ✅ Application Root
    │
    ├── index.php                      ✅ Main entry point / Router
    ├── .htaccess                      ✅ Apache configuration
    │
    ├── config/
    │   ├── config.php                 ✅ Application configuration
    │   └── database.php               ✅ Database connection
    │
    ├── app/
    │   └── (Core application logic)   📝 TODO
    │
    ├── controllers/
    │   ├── auth/                      📝 TODO
    │   ├── items/                     📝 TODO
    │   ├── inventory/                 📝 TODO
    │   ├── trades/                    📝 TODO
    │   └── admin/                     📝 TODO
    │
    ├── models/
    │   ├── User.php                   📝 TODO
    │   ├── Item.php                   📝 TODO
    │   ├── Inventory.php              📝 TODO
    │   ├── Trade.php                  📝 TODO
    │   └── Notification.php           📝 TODO
    │
    ├── middleware/
    │   ├── auth.php                   📝 TODO
    │   └── admin.php                  📝 TODO
    │
    ├── utils/
    │   └── auth.php                   ✅ Authentication utilities
    │
    ├── views/
    │   ├── layout/
    │   │   └── main.php               ✅ Master layout template
    │   │
    │   ├── auth/
    │   │   ├── login.php              ✅ Login page
    │   │   ├── register.php           ✅ Registration page
    │   │   ├── profile.php            📝 TODO
    │   │   └── logout.php             📝 TODO
    │   │
    │   ├── dashboard/
    │   │   └── index.php              ✅ Dashboard homepage
    │   │
    │   ├── items/
    │   │   ├── catalog.php            📝 TODO
    │   │   └── detail.php             📝 TODO
    │   │
    │   ├── inventory/
    │   │   └── index.php              📝 TODO
    │   │
    │   ├── trades/
    │   │   └── index.php              📝 TODO
    │   │
    │   ├── notifications/
    │   │   └── index.php              📝 TODO
    │   │
    │   ├── admin/
    │   │   ├── dashboard.php          📝 TODO
    │   │   ├── users.php              📝 TODO
    │   │   ├── items.php              📝 TODO
    │   │   ├── trades.php             📝 TODO
    │   │   └── statistics.php         📝 TODO
    │   │
    │   └── errors/
    │       └── 404.php                📝 TODO
    │
    └── assets/
        ├── css/
        │   ├── main.css               ✅ Global styles
        │   ├── layout.css             ✅ Navigation & layout
        │   └── responsive.css         ✅ Mobile responsive
        │
        ├── js/
        │   ├── main.js                ✅ Core interactions
        │   └── interaction.js         ✅ Page-specific JS
        │
        └── images/
            └── (placeholder folder)   📁 For images
```

---

## 🚀 Getting Started

### 1. Start Laragon
```
1. Open Laragon
2. Click "Start All"
3. Wait for MySQL/Apache to start (green indicators)
```

### 2. Access the Application
```
Open browser:
http://localhost/persoonlijk/school%20naar%20persnenal/E4_DreamSpace/src/
```

### 3. Test Pages
```
✓ Home/Dashboard: http://localhost/...src/?page=home
✓ Login: http://localhost/...src/?page=login
✓ Register: http://localhost/...src/?page=register
```

---

## 📋 What's Been Created (Completed ✅)

### Configuration Files
- **config/config.php** - Main application settings, routes, helper functions
- **config/database.php** - Database connection, prepared statements
- **.htaccess** - Apache rewrite rules for clean URLs

### Utilities
- **utils/auth.php** - Password hashing, validation, login/logout functions

### Views (Templates)
- **layout/main.php** - Master layout with navigation & footer
- **auth/login.php** - Login page with styling
- **auth/register.php** - Registration page with validation UI
- **dashboard/index.php** - Home dashboard for logged-in users

### Stylesheets
- **assets/css/main.css** - Global styles (buttons, forms, tables, etc)
- **assets/css/layout.css** - Navigation bar & footer styling
- **assets/css/responsive.css** - Mobile responsive design

### JavaScript
- **assets/js/main.js** - Core utilities (notifications, storage, etc)
- **assets/js/interaction.js** - Page-specific interactions

---

## 📝 What's Next (TODO)

### 1. Complete Controllers (6 folders)
```
controllers/
├── auth/
│   ├── login.php              - Handle login form submission
│   ├── register.php           - Handle registration form submission
│   ├── logout.php             - Clear session & redirect
│   └── profile.php            - Update user profile
│
├── items/
│   ├── list.php               - Get items for catalog
│   └── detail.php             - Get single item details
│
├── inventory/
│   ├── list.php               - Get user's inventory
│   └── manage.php             - Add/remove items
│
├── trades/
│   ├── create.php             - Create trade proposal
│   ├── accept.php             - Accept trade
│   ├── reject.php             - Reject trade
│   └── cancel.php             - Cancel trade
│
└── admin/
    ├── users.php              - User management
    ├── items.php              - Item management
    └── statistics.php         - Generate reports
```

### 2. Complete Models (5 files)
```
models/
├── User.php                   - User database operations
├── Item.php                   - Item database operations
├── Inventory.php              - Inventory database operations
├── Trade.php                  - Trade database operations
└── Notification.php           - Notification database operations
```

### 3. Complete Views (9+ files)
```
views/
├── auth/profile.php, logout.php
├── items/catalog.php, detail.php
├── inventory/index.php
├── trades/index.php
├── notifications/index.php
├── admin/* (5 pages)
└── errors/404.php
```

### 4. Middleware
```
middleware/
├── auth.php                   - Check if user is logged in
└── admin.php                  - Check if user is admin
```

---

## 🔐 Security Features Included

✅ **Already Implemented:**
- Password hashing utilities (Bcrypt preparation)
- Input sanitization functions
- Session management
- CSRF protection setup
- SQL injection prevention (parameterized queries)
- Authentication utilities

✅ **In Database:**
- Unique constraints on username/email
- Bcrypt password hashes (test data)
- Foreign key constraints
- Role-based access control (Speler/Beheerder)

📝 **Still Need:**
- Full authentication system implementation
- Admin middleware enforcement
- Rate limiting
- HTTPS setup
- Secure session configuration

---

## 🗄️ Database Connection

The application is configured to connect to `dreamspace_db` database.

### Connection Details (Laragon defaults):
```
Host: localhost
User: root
Password: (empty)
Database: dreamspace_db
Table: 5 (GEBRUIKER, ITEM, INVENTARIS, HANDELSVOORSTEL, NOTIFICATIE)
Test Data: 5 users, 7 items, 7 inventory links
```

### Test Credentials:
```
Username: ShadowSlayer
Password: Test123! (bcrypt hashed in DB)
Role: Speler

Username: AdminMaster
Password: Admin007! (bcrypt hashed in DB)
Role: Beheerder
```

---

## 🎨 Styling & Responsive Design

### Features:
- ✅ Blue/Purple theme (#667eea)
- ✅ Gradient backgrounds
- ✅ Mobile responsive (breakpoints: 1200px, 992px, 768px, 576px)
- ✅ Navigation bar with dropdowns
- ✅ Dashboard cards & widgets
- ✅ Form styling with validation
- ✅ Alert/notification system
- ✅ Print styles

### Color Scheme:
```
Primary: #667eea (Blue-Purple)
Secondary: #764ba2 (Purple)
Success: #28a745 (Green)
Danger: #dc3545 (Red)
Warning: #ffc107 (Orange)
Info: #17a2b8 (Cyan)
```

---

## 📚 File Descriptions

### Entry Point
- **index.php** - Routes all requests based on `?page=` parameter

### Configuration
- **config.php** - Constants, route definitions, helper functions
- **database.php** - MySQL connection, prepared statement helpers

### Utilities
- **auth.php** - Authentication: login, logout, password validation, roles

### Views
All views use **layout/main.php** as wrapper, providing:
- Header with navigation
- Footer with links
- Consistent styling across pages

---

## 🧪 Testing the Application

### Test Routes:
```
✓ Login page:          index.php?page=login
✓ Register page:       index.php?page=register
✓ Dashboard:           index.php?page=home (requires login)
✓ Items catalog:       index.php?page=items
✓ Inventory:           index.php?page=inventory (requires login)
✓ Trades:              index.php?page=trades (requires login)
✓ Notifications:       index.php?page=notifications (requires login)
✓ Admin dashboard:     index.php?page=admin (admin only)
✓ 404 error:           index.php?page=unknown
```

---

## 📖 Development Workflow

### To add a new page:
1. Create view file in `views/[section]/[name].php`
2. Add route in `config/config.php`
3. Add case in `index.php` switch statement
4. Create controller if needed in `controllers/[section]/[name].php`
5. Create model if needed in `models/[Name].php`

### Example (Adding a new Feedback page):
```php
// 1. Create views/feedback/index.php
// 2. Add to config.php: 'feedback' => 'feedback/index'
// 3. Add to index.php: case 'feedback': renderWithLayout('feedback/index'); break;
// 4. Create controllers/feedback/submit.php
// 5. Final URL: index.php?page=feedback
```

---

## 🔧 Key Functions Available

### In index.php (Global):
- `getRequestedPage()` - Get current page from URL
- `redirect($page)` - Redirect to page
- `renderView($name, $data)` - Load a view
- `renderWithLayout($name, $data)` - Load view with layout

### From auth.php (Security):
- `hashPassword($password)` - Hash password with Bcrypt
- `verifyPassword($plain, $hash)` - Check password
- `validatePasswordStrength($password)` - Check password meets requirements
- `loginUser($id, $name, $role)` - Create session
- `logoutUser()` - Destroy session
- `isLoggedIn()` - Check if user logged in
- `isAdmin()` - Check if user is admin
- `getCurrentUserID()` - Get logged in user ID
- `getCurrentUsername()` - Get username
- `sanitizeInput($data)` - Prevent XSS

### From database.php:
- `getDatabaseConnection()` - Get MySQL connection
- `executeQuery($conn, $query, $params)` - Run prepared statement
- `fetchAll($result)` - Get all rows
- `fetchOne($result)` - Get single row

### From main.js (Client-side):
- `showNotification(msg, type, duration)` - Toast notification
- `confirmAction(msg, callback)` - Confirmation dialog
- `formatDate(dateString)` - Format date
- `Storage.set/get/remove/clear()` - Local storage helpers

---

## 📞 Next Steps

1. **Implement Controllers** - Handle form submissions
2. **Create Models** - Database CRUD operations
3. **Complete Views** - All remaining pages
4. **Add Middleware** - Authentication checks
5. **Test Thoroughly** - All features
6. **Security Audit** - Review all inputs
7. **Deploy** - To production server

---

## 📚 Resources

- Database: [DATABASE_DESIGN.md](../../database/README.md)
- Design: [ONTWERP_DOCUMENT.md](../../ONTWERP_DOCUMENT.md)
- Setup: [SETUP_INSTRUCTIONS.md](../../database/SETUP_INSTRUCTIONS.md)
- Security: [SECURITY_PRIVACY.md](../../SECURITY_PRIVACY.md)

---

## ✨ Status Summary

| Component | Status | Progress |
|-----------|--------|----------|
| Database  | ✅ Complete | 100% |
| Config & Setup | ✅ Complete | 100% |
| Views (Basic) | ✅ Complete | 40% |
| Controllers | 📝 TODO | 0% |
| Models | 📝 TODO | 0% |
| Middleware | 📝 TODO | 0% |
| Styling | ✅ Complete | 100% |
| JS Interactions | ✅ Partial | 50% |
| Testing | 📝 TODO | 0% |
| Deployment | 📝 TODO | 0% |

**Overall: 35% Complete** ✨

---

**Created:** March 3, 2026  
**Version:** 1.0 - Foundation Complete  
**Status:** Ready for feature development
