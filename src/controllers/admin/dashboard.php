<?php

/**
 * ADMIN DASHBOARD CONTROLLER
 * Weergeeft admin dashboard met systeemstatistieken
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

// Require admin access
requireAdmin();

$conn = getDatabaseConnection();

// Get user count
$users_sql = 'SELECT COUNT(*) as total FROM GEBRUIKER';
$users_stmt = $conn->prepare($users_sql);
$users_stmt->execute();
$users_count = $users_stmt->get_result()->fetch_assoc();

// Get items count
$items_sql = 'SELECT COUNT(*) as total FROM ITEM';
$items_stmt = $conn->prepare($items_sql);
$items_stmt->execute();
$items_count = $items_stmt->get_result()->fetch_assoc();

// Get trades count
$trades_sql = 'SELECT COUNT(*) as total FROM HANDELSVOORSTEL';
$trades_stmt = $conn->prepare($trades_sql);
$trades_stmt->execute();
$trades_count = $trades_stmt->get_result()->fetch_assoc();

// Get notifications count
$notif_sql = 'SELECT COUNT(*) as total FROM NOTIFICATIE WHERE Gelezen = 0';
$notif_stmt = $conn->prepare($notif_sql);
$notif_stmt->execute();
$notif_count = $notif_stmt->get_result()->fetch_assoc();

// Get recent users
$recent_users_sql = 'SELECT UserID, Gebruikersnaam, Email, CreatedAt 
                    FROM GEBRUIKER 
                    ORDER BY CreatedAt DESC 
                    LIMIT 5';
$recent_users_stmt = $conn->prepare($recent_users_sql);
$recent_users_stmt->execute();
$recent_users = $recent_users_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get recent trades
$recent_trades_sql = 'SELECT h.TradeID, g1.Gebruikersnaam as creator, 
                            g2.Gebruikersnaam as recipient, h.Status, h.CreatedAt
                    FROM HANDELSVOORSTEL h
                    LEFT JOIN GEBRUIKER g1 ON h.VerzenderID = g1.UserID
                    LEFT JOIN GEBRUIKER g2 ON h.OntvangerID = g2.UserID
                    ORDER BY h.CreatedAt DESC
                    LIMIT 5';
$recent_trades_stmt = $conn->prepare($recent_trades_sql);
$recent_trades_stmt->execute();
$recent_trades = $recent_trades_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get trade status breakdown
$trade_status_sql = 'SELECT Status, COUNT(*) as count FROM HANDELSVOORSTEL GROUP BY Status';
$trade_status_stmt = $conn->prepare($trade_status_sql);
$trade_status_stmt->execute();
$trade_status = $trade_status_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Prepare data for view
$data = [
    'users_count' => $users_count['total'] ?? 0,
    'items_count' => $items_count['total'] ?? 0,
    'trades_count' => $trades_count['total'] ?? 0,
    'notif_count' => $notif_count['total'] ?? 0,
    'recent_users' => $recent_users,
    'recent_trades' => $recent_trades,
    'trade_status' => $trade_status
];

// Render view
renderWithLayout('admin/dashboard', $data);
?>
