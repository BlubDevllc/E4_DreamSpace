# DreamSpace - Web Application Source Code

This folder contains all the source code for the DreamSpace web application.

## Quick Access

- **[GETTING_STARTED.md](./GETTING_STARTED.md)** - Start here! Complete setup and development guide
- **[index.php](./index.php)** - Main application entry point and router
- **config/config.php** - Application configuration and global functions
- **config/database.php** - Database connection and utilities

## Directory Structure

```
src/
├── index.php                 Main router
├── .htaccess                 Apache configuration
├── .env.example              Environment template
├── GETTING_STARTED.md        🚀 Start here!
│
├── config/                   Application configuration
├── app/                      Core application logic
├── controllers/              Request handlers
├── models/                   Database models
├── middleware/               Request middleware
├── utils/                    Helper utilities
├── views/                    HTML templates
└── assets/                   CSS, JS, images
```

## Key Files

| File | Purpose |
|------|---------|
| index.php | Routes all requests, main entry point |
| config/config.php | Global settings and helper functions |
| config/database.php | MySQL connection and query helpers |
| utils/auth.php | Authentication and security utilities |
| views/layout/main.php | Master layout template |
| assets/css/main.css | Global styles |

## Getting Started

1. **Read:** [GETTING_STARTED.md](./GETTING_STARTED.md)
2. **Test:** http://localhost/persoonlijk/school%20naar%20persnenal/E4_DreamSpace/src/
3. **Try:** ?page=login, ?page=register, ?page=home

## Available Routes

```
// Public pages
?page=login          - Login form
?page=register       - Registration form
?page=items          - Item catalog

// Protected pages (require login)
?page=home           - Dashboard
?page=inventory      - User inventory
?page=trades         - Trading system
?page=notifications  - Notifications
?page=profile        - User profile

// Admin pages (admin only)
?page=admin          - Admin dashboard
?page=admin-users    - User management
?page=admin-items    - Item management
```

## Security

✅ Implemented:
- Password hashing utilities
- Input sanitization
- Session management
- SQL injection prevention (prepared statements)

📝 In Development:
- Authentication system
- Admin middleware
- Rate limiting
- CSRF protection

## Database

Connected to `dreamspace_db` with 5 tables:
- GEBRUIKER (Users)
- ITEM (Items)
- INVENTARIS (Inventory)
- HANDELSVOORSTEL (Trades)
- NOTIFICATIE (Notifications)

Test credentials:
```
Username: ShadowSlayer
Password: Test123! (hashed)
Role: Speler

Username: AdminMaster
Password: Admin007! (hashed)
Role: Beheerder
```

## Development

### To add a new page:
1. Create view in `views/section/name.php`
2. Add route in `config/config.php`
3. Add case in `index.php`
4. Access via `index.php?page=section-name`

### To add database functionality:
1. Create model in `models/Model.php`
2. Create controller in `controllers/section/action.php`
3. Call from view or index.php

## Dependencies

- PHP 7.4+
- MySQL 5.7+ or MariaDB
- Apache 2.4+ (with mod_rewrite)

## Status

✅ Foundation Complete (35%)
- Configuration and setup
- Views and styling
- Basic routing

📝 Features In Development (65%)
- Authentication system
- Controllers and models
- Admin panel
- Testing and deployment

## Support

See parent documentation:
- [ONTWERP_DOCUMENT.md](../ONTWERP_DOCUMENT.md) - Design spec
- [DATABASE_DESIGN.md](../DATABASE_DESIGN.md) - Database schema
- [SECURITY_PRIVACY.md](../SECURITY_PRIVACY.md) - Security measures
- [IMPLEMENTATIE_PLAN.md](../IMPLEMENTATIE_PLAN.md) - Development roadmap

---

**Version:** 1.0  
**Status:** Foundation - Ready for development  
**Last Updated:** March 3, 2026
