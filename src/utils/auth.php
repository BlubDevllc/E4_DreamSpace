<?php

/**
 * AUTHENTICATION & SECURITY UTILITIES
 */

// Configure session settings BEFORE starting session
if (session_status() === PHP_SESSION_NONE) {
    // Session security settings must be set BEFORE session_start()
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 0);  // Set to 1 in production with HTTPS
    ini_set('session.use_only_cookies', 1);
    
    // Now start the session
    session_start();
}

/**
 * Hash password using Bcrypt (PHP's password_hash)
 * More secure than bcrypt() function for modern PHP
 * 
 * @param string $password Plain text password
 * @return string Hashed password
 */
function hashPassword($password)
{
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
}

/**
 * Verify password against hash
 * 
 * @param string $password Plain text password
 * @param string $hash Hashed password
 * @return bool True if password matches hash
 */
function verifyPassword($password, $hash)
{
    return password_verify($password, $hash);
}

/**
 * Validate password strength
 * Requirements: 8+ chars, uppercase, lowercase, number, special char
 * 
 * @param string $password Password to validate
 * @return array ['valid' => bool, 'errors' => array]
 */
function validatePasswordStrength($password)
{
    $errors = [];

    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long';
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Password must contain at least one uppercase letter';
    }
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = 'Password must contain at least one lowercase letter';
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password must contain at least one number';
    }
    if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};:\'",.<>?\/\\|`~]/', $password)) {
        $errors[] = 'Password must contain at least one special character (!@#$%^&* etc)';
    }

    return [
        'valid' => empty($errors),
        'errors' => $errors
    ];
}

/**
 * Log in a user
 * 
 * @param int $userid User ID
 * @param string $username Username
 * @param string $rol User role (Speler/Beheerder)
 */
function loginUser($userid, $username, $rol)
{
    $_SESSION['userid'] = $userid;
    $_SESSION['username'] = $username;
    $_SESSION['rol'] = $rol;
    $_SESSION['login_time'] = time();
}

/**
 * Log out current user
 */
function logoutUser()
{
    session_destroy();
    header('Location: index.php');
    exit();
}

/**
 * Check if user is logged in
 * 
 * @return bool
 */
function isLoggedIn()
{
    return isset($_SESSION['userid']) && isset($_SESSION['username']);
}

/**
 * Get current user ID
 * 
 * @return int|null
 */
function getCurrentUserID()
{
    return $_SESSION['userid'] ?? null;
}

/**
 * Get current username
 * 
 * @return string|null
 */
function getCurrentUsername()
{
    return $_SESSION['username'] ?? null;
}

/**
 * Get current user role
 * 
 * @return string|null (Speler or Beheerder)
 */
function getCurrentRole()
{
    return $_SESSION['rol'] ?? null;
}

/**
 * Check if user is admin
 * 
 * @return bool
 */
function isAdmin()
{
    return isLoggedIn() && $_SESSION['rol'] === 'Beheerder';
}

/**
 * Require login (redirects if not logged in)
 */
function requireLogin()
{
    if (!isLoggedIn()) {
        $_SESSION['redirect'] = $_SERVER['REQUEST_URI'];
        header('Location: index.php?page=login');
        exit();
    }
}

/**
 * Require admin role (redirects if not admin)
 */
function requireAdmin()
{
    requireLogin();
    if (!isAdmin()) {
        header('HTTP/1.0 403 Forbidden');
        die('Access denied. Admin privileges required.');
    }
}

/**
 * Sanitize input data
 * 
 * @param string $data Raw input data
 * @return string Sanitized data
 */
function sanitizeInput($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email format
 * 
 * @param string $email Email to validate
 * @return bool
 */
function validateEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate username (alphanumeric, 3-50 chars)
 * 
 * @param string $username Username to validate
 * @return array ['valid' => bool, 'errors' => array]
 */
function validateUsername($username)
{
    $errors = [];

    if (strlen($username) < 3) {
        $errors[] = 'Username must be at least 3 characters long';
    }
    if (strlen($username) > 50) {
        $errors[] = 'Username must not exceed 50 characters';
    }
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
        $errors[] = 'Username can only contain letters, numbers, underscores, and hyphens';
    }

    return [
        'valid' => empty($errors),
        'errors' => $errors
    ];
}

?>
