<?php

/**
 * LOGIN HANDLER
 * Verwerkt inlogformulieren
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';

// Alleen POST requests verwerken
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL);
    exit();
}

// Get form data
$username = isset($_POST['username']) ? sanitizeInput($_POST['username']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$remember = isset($_POST['remember']);

// Validation
$errors = [];

if (empty($username)) {
    $errors[] = 'Voer je gebruikersnaam in';
}

if (empty($password)) {
    $errors[] = 'Voer je wachtwoord in';
}

if (!empty($errors)) {
    $_SESSION['login_errors'] = $errors;
    $_SESSION['login_form'] = ['username' => $username];
    header('Location: ' . BASE_URL . '?page=login');
    exit();
}

// Get user from database
$conn = getDatabaseConnection();
$stmt = $conn->prepare(
    'SELECT UserID, Gebruikersnaam, Wachtwoord, Rol, IsActief 
     FROM GEBRUIKER 
     WHERE Gebruikersnaam = ?'
);

$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['login_errors'] = ['Gebruikersnaam of wachtwoord incorrect'];
    $_SESSION['login_form'] = ['username' => $username];
    header('Location: ' . BASE_URL . '?page=login');
    exit();
}

$user = $result->fetch_assoc();

// Check if account is active
if (!$user['IsActief']) {
    $_SESSION['login_errors'] = ['Dit account is uitgeschakeld'];
    $_SESSION['login_form'] = ['username' => $username];
    header('Location: ' . BASE_URL . '?page=login');
    exit();
}

// Verify password
if (!verifyPassword($password, $user['Wachtwoord'])) {
    $_SESSION['login_errors'] = ['Gebruikersnaam of wachtwoord incorrect'];
    $_SESSION['login_form'] = ['username' => $username];
    header('Location: ' . BASE_URL . '?page=login');
    exit();
}

// Login successful
loginUser($user['UserID'], $user['Gebruikersnaam'], $user['Rol']);

// Remember me functionality (7 days)
if ($remember) {
    setcookie('remember_user', $user['UserID'], time() + (7 * 24 * 60 * 60), '/');
}

$_SESSION['success_message'] = 'Welkom terug, ' . $user['Gebruikersnaam'] . '!';

// Redirect to requested page or home
$redirect = isset($_SESSION['redirect']) ? $_SESSION['redirect'] : BASE_URL . '?page=home';
unset($_SESSION['redirect']);

header('Location: ' . $redirect);
exit();

?>
