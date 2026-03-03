<?php

/**
 * ============================================================================
 * DREAMSPACE INVENTORY MANAGEMENT SYSTEM
 * Main Entry Point / Router
 * ============================================================================
 * 
 * This is the main index.php file that handles all page routing
 * All requests are directed through this file
 * 
 * URL Structure:
 *   index.php?page=home
 *   index.php?page=login
 *   index.php?page=inventory
 *   index.php?page=admin (admin only)
 * 
 */

// Load configuration
require_once __DIR__ . '/config/config.php';

// Get the requested page
$page = getRequestedPage();

// Route the request based on page parameter
switch ($page) {
    
    // =========== AUTHENTICATION PAGES ===========
    
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/controllers/auth/login.php';
        } else {
            renderWithLayout('auth/login');
        }
        break;

    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/controllers/auth/register.php';
        } else {
            renderWithLayout('auth/register');
        }
        break;

    case 'logout':
        require_once __DIR__ . '/controllers/auth/logout.php';
        break;

    case 'profile':
        requireLogin();
        renderWithLayout('auth/profile');
        break;

    // =========== MAIN PAGES (Player) ===========
    
    case 'home':
    case 'dashboard':
        if (isLoggedIn()) {
            renderWithLayout('dashboard/index');
        } else {
            redirect('login');
        }
        break;

    case 'items':
        require_once __DIR__ . '/controllers/items/catalog.php';
        break;

    case 'item-detail':
        require_once __DIR__ . '/controllers/items/detail.php';
        break;

    case 'inventory':
        require_once __DIR__ . '/controllers/inventory/index.php';
        break;

    case 'trades':
        requireLogin();
        renderWithLayout('trades/index');
        break;

    case 'notifications':
        requireLogin();
        renderWithLayout('notifications/index');
        break;

    // =========== ADMIN PAGES ===========
    
    case 'admin':
    case 'admin-dashboard':
        requireAdmin();
        renderWithLayout('admin/dashboard');
        break;

    case 'admin-users':
        requireAdmin();
        renderWithLayout('admin/users');
        break;

    case 'admin-items':
        requireAdmin();
        renderWithLayout('admin/items');
        break;

    case 'admin-trades':
        requireAdmin();
        renderWithLayout('admin/trades');
        break;

    case 'admin-statistics':
        requireAdmin();
        renderWithLayout('admin/statistics');
        break;

    // =========== DEFAULT / 404 ===========
    
    default:
        http_response_code(404);
        renderWithLayout('errors/404');
        break;
}

?>
