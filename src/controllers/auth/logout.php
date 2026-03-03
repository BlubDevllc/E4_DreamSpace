<?php

/**
 * LOGOUT HANDLER
 * Beëindigt de sessie van de gebruiker
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: ' . BASE_URL . '?page=login');
    exit();
}

// Get username for farewell message
$username = $_SESSION['username'] ?? 'User';

// Call logout function from auth.php
logoutUser();

// Clear remember me cookie
if (isset($_COOKIE['remember_user'])) {
    setcookie('remember_user', '', time() - 3600, '/');
}

// Set farewell message and redirect
$_SESSION['success_message'] = 'Tot ziens, ' . $username . '! Je bent uitgelogd.';

header('Location: ' . BASE_URL . '?page=login');
exit();

?>
