<?php

/**
 * REGISTRATION HANDLER
 * Verwerkt registratieformulieren
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
$email = isset($_POST['email']) ? sanitizeInput($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$password_confirm = isset($_POST['password_confirm']) ? $_POST['password_confirm'] : '';

// Validation
$errors = [];

// Username validation
if (empty($username)) {
    $errors[] = 'Voer een gebruikersnaam in';
} else {
    $validation = validateUsername($username);
    if (!$validation['valid']) {
        $errors = array_merge($errors, $validation['errors']);
    }
}

// Email validation
if (empty($email)) {
    $errors[] = 'Voer een e-mailadres in';
} elseif (!validateEmail($email)) {
    $errors[] = 'Ongeldig e-mailadres';
}

// Password validation
if (empty($password)) {
    $errors[] = 'Voer een wachtwoord in';
} else {
    $validation = validatePasswordStrength($password);
    if (!$validation['valid']) {
        $errors = array_merge($errors, $validation['errors']);
    }
}

// Password confirm
if ($password !== $password_confirm) {
    $errors[] = 'Wachtwoorden komen niet overeen';
}

// If there are errors, go back to register page with errors
if (!empty($errors)) {
    $_SESSION['register_errors'] = $errors;
    $_SESSION['register_form'] = [
        'username' => $username,
        'email' => $email
    ];
    header('Location: ' . BASE_URL . '?page=register');
    exit();
}

// Check if username already exists
$conn = getDatabaseConnection();
$stmt = $conn->prepare('SELECT UserID FROM GEBRUIKER WHERE Gebruikersnaam = ?');
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['register_errors'] = ['Gebruikersnaam is al in gebruik'];
    $_SESSION['register_form'] = ['username' => $username, 'email' => $email];
    header('Location: ' . BASE_URL . '?page=register');
    exit();
}

// Check if email already exists
$stmt = $conn->prepare('SELECT UserID FROM GEBRUIKER WHERE Email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['register_errors'] = ['E-mailadres is al in gebruik'];
    $_SESSION['register_form'] = ['username' => $username, 'email' => $email];
    header('Location: ' . BASE_URL . '?page=register');
    exit();
}

// Hash password
$password_hash = hashPassword($password);

// Insert new user
$stmt = $conn->prepare(
    'INSERT INTO GEBRUIKER (Gebruikersnaam, Email, Wachtwoord, Rol, IsActief) 
     VALUES (?, ?, ?, ?, ?)'
);

$default_role = 'Speler';
$is_active = 1;

$stmt->bind_param('ssssi', $username, $email, $password_hash, $default_role, $is_active);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    // Get the new user ID
    $user_id = $stmt->insert_id;
    
    // Log them in automatically
    loginUser($user_id, $username, $default_role);
    
    $_SESSION['success_message'] = 'Welkom ' . $username . '! Je account is aangemaakt.';
    header('Location: ' . BASE_URL . '?page=home');
} else {
    $_SESSION['register_errors'] = ['Er is een fout opgetreden bij het aanmaken van het account'];
    header('Location: ' . BASE_URL . '?page=register');
}

$stmt->close();
$conn->close();
exit();

?>
