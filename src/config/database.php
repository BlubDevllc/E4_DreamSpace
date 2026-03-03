<?php

/**
 * DATABASE CONFIGURATION
 * 
 * Database connection settings
 * For use with Laragon / MySQL
 */

// Database credentials (Laragon defaults)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dreamspace_db');
define('DB_PORT', 3306);
define('DB_CHARSET', 'utf8mb4');

/**
 * Database Connection Function
 * Creates and returns a MySQLi database connection
 * 
 * @return mysqli|false The database connection or false if connection fails
 */
function getDatabaseConnection()
{
    // Create connection
    $conn = new mysqli(
        DB_HOST,
        DB_USER,
        DB_PASS,
        DB_NAME,
        DB_PORT
    );

    // Check connection
    if ($conn->connect_error) {
        error_log('Database Connection Failed: ' . $conn->connect_error);
        die('Connection failed. Please contact administrator.');
    }

    // Set charset to utf8mb4
    $conn->set_charset(DB_CHARSET);

    return $conn;
}

/**
 * Execute a parameterized query safely
 * Prevents SQL injection attacks
 * 
 * @param mysqli $conn Database connection
 * @param string $query SQL query with ? placeholders
 * @param array $params Parameters to bind
 * @param string $types Type specification (s=string, i=int, d=double, b=blob)
 * @return mysqli_result|bool Query result or false if failed
 */
function executeQuery($conn, $query, $params = [], $types = '')
{
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        error_log('Query Preparation Failed: ' . $conn->error);
        return false;
    }

    // Bind parameters if provided
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    if (!$stmt->execute()) {
        error_log('Query Execution Failed: ' . $stmt->error);
        $stmt->close();
        return false;
    }

    return $stmt->get_result();
}

/**
 * Fetch all rows from a result set
 */
function fetchAll($result)
{
    if (!$result) return [];
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Fetch single row from a result set
 */
function fetchOne($result)
{
    if (!$result) return null;
    return $result->fetch_assoc();
}

?>
