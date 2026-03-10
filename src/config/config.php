<?php

/**
 * DREAMSPACE APPLICATION CONFIGURATION
 */

// Error reporting (enable in development, disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/errors.log');

// Timezone
date_default_timezone_set('Europe/Amsterdam');

// Application constants
define('APP_NAME', 'DreamSpace');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development');  // development or production
define('BASE_URL', 'http://localhost/persoonlijk/school%20naar%20persnenal/E4_DreamSpace/src/');
define('ROOT_PATH', dirname(__DIR__));

// Include configuration files
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/utils/auth.php';

// Routes mapping
$routes = [
    // Authentication pages
    'login' => 'auth/login',
    'register' => 'auth/register',
    'logout' => 'auth/logout',
    'profile' => 'auth/profile',

    // Player pages
    'home' => 'dashboard/index',
    'dashboard' => 'dashboard/index',
    'items' => 'items/catalog',
    'item-detail' => 'items/detail',
    'inventory' => 'inventory/index',
    'trades' => 'trades/index',
    'notifications' => 'notifications/index',

    // Admin pages
    'admin' => 'admin/dashboard',
    'admin-users' => 'admin/users',
    'admin-items' => 'admin/items',
    'admin-trades' => 'admin/trades',
    'admin-statistics' => 'admin/statistics',
];

/**
 * Get the requested page/action
 * 
 * @return string Page name
 */
function getRequestedPage()
{
    return isset($_GET['page']) ? sanitizeInput($_GET['page']) : 'home';
}

/**
 * Redirect to a page
 * 
 * @param string $page Page name or URL
 */
function redirect($page)
{
    header('Location: ' . BASE_URL . '?page=' . $page);
    exit();
}

/**
 * Get view file path
 * 
 * @param string $viewName View name/path
 * @return string Full path to view file
 */
function getViewPath($viewName)
{
    return ROOT_PATH . '/views/' . $viewName . '.php';
}

/**
 * Load and render a view
 * 
 * @param string $viewName View name/path
 * @param array $data Data to pass to view
 */
function renderView($viewName, $data = [])
{
    $viewPath = getViewPath($viewName);
    
    if (!file_exists($viewPath)) {
        die('View not found: ' . $viewName);
    }

    // Extract data array into variables
    extract($data);

    // Include the view
    include $viewPath;
}

/**
 * Load a layout wrapper with content
 * 
 * @param string $viewName View to render inside layout
 * @param array $data Data to pass to view
 */
function renderWithLayout($viewName, $data = [])
{
    ob_start();
    renderView($viewName, $data);
    $content = ob_get_clean();

    // Get unread notification count for layout
    if (isLoggedIn()) {
        $conn = getDatabaseConnection();
        $user_id = getCurrentUserID();
        $notif_sql = 'SELECT COUNT(*) as count FROM NOTIFICATIE WHERE UserID = ? AND Gelezen = 0';
        $notif_stmt = $conn->prepare($notif_sql);
        $notif_stmt->bind_param('i', $user_id);
        $notif_stmt->execute();
        $notif_result = $notif_stmt->get_result()->fetch_assoc();
        $unread_notifications = $notif_result['count'] ?? 0;
    } else {
        $unread_notifications = 0;
    }

    // Merge content with layout data
    $layoutData = array_merge($data, [
        'content' => $content,
        'unread_notifications' => $unread_notifications
    ]);

    renderView('layout/main', $layoutData);
}

?>
