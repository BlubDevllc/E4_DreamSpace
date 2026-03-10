<?php

/**
 * NOTIFICATIONS CONTROLLER
 * Weergeeft alle notificaties van de gebruiker
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

// Require login
if (!isLoggedIn()) {
    $_SESSION['redirect'] = BASE_URL . '?page=notifications';
    header('Location: ' . BASE_URL . '?page=login');
    exit();
}

$user_id = getCurrentUserID();
$conn = getDatabaseConnection();

// Get all notifications
$notif_sql = 'SELECT n.NotificatieID, n.UserID, n.Type, n.Bericht, 
                     n.Gelezen, n.CreatedAt, n.RelatedTradeID,
                     h.Status as trade_status
              FROM NOTIFICATIE n
              LEFT JOIN HANDELSVOORSTEL h ON n.RelatedTradeID = h.TradeID
              WHERE n.UserID = ?
              ORDER BY n.Gelezen ASC, n.CreatedAt DESC
              LIMIT 50';

$notif_stmt = $conn->prepare($notif_sql);
$notif_stmt->bind_param('i', $user_id);
$notif_stmt->execute();
$notifications = $notif_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get unread count by type
$count_sql = 'SELECT Type, COUNT(*) as count
             FROM NOTIFICATIE
             WHERE UserID = ? AND Gelezen = 0
             GROUP BY Type';

$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param('i', $user_id);
$count_stmt->execute();
$count_result = $count_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Create array of counts by type
$counts_by_type = [];
foreach ($count_result as $row) {
    $counts_by_type[$row['Type']] = $row['count'];
}

// Calculate total unread
$total_unread = array_sum(array_values($counts_by_type));

// Prepare data for view
$data = [
    'notifications' => $notifications,
    'counts_by_type' => $counts_by_type,
    'total_unread' => $total_unread
];

// Render view
renderWithLayout('notifications/index', $data);
?>
