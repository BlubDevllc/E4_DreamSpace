<?php

/**
 * DASHBOARD CONTROLLER
 * Weergeeft de home/dashboard pagina met gebruikersstats
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

// Require login
if (!isLoggedIn()) {
    $_SESSION['redirect'] = BASE_URL . '?page=home';
    header('Location: ' . BASE_URL . '?page=login');
    exit();
}

$user_id = getCurrentUserID();
$conn = getDatabaseConnection();

// Get inventory stats
$inventory_sql = 'SELECT 
                  COUNT(*) as total_items,
                  COUNT(DISTINCT ItemID) as unique_items
                 FROM INVENTARIS
                 WHERE UserID = ?';

$inv_stmt = $conn->prepare($inventory_sql);
$inv_stmt->bind_param('i', $user_id);
$inv_stmt->execute();
$inventory_stats = $inv_stmt->get_result()->fetch_assoc();

// Get active trades count
$trades_sql = 'SELECT COUNT(*) as active_trades
              FROM HANDELSVOORSTEL
              WHERE (VerzenderID = ? OR OntvangerID = ?) 
              AND Status = "In Afwachting"';

$trades_stmt = $conn->prepare($trades_sql);
$trades_stmt->bind_param('ii', $user_id, $user_id);
$trades_stmt->execute();
$trades_stats = $trades_stmt->get_result()->fetch_assoc();

// Get notifications count
$notif_sql = 'SELECT COUNT(*) as new_notifications
             FROM NOTIFICATIE
             WHERE UserID = ? AND Gelezen = 0';;

$notif_stmt = $conn->prepare($notif_sql);
$notif_stmt->bind_param('i', $user_id);
$notif_stmt->execute();
$notif_stats = $notif_stmt->get_result()->fetch_assoc();

// Get legendary items count
$legendary_sql = 'SELECT COUNT(*) as legendary_items
                 FROM INVENTARIS i
                 JOIN ITEM it ON i.ItemID = it.ItemID
                 WHERE i.UserID = ? AND it.Zeldzaamheid = "Legendarisch"';

$legendary_stmt = $conn->prepare($legendary_sql);
$legendary_stmt->bind_param('i', $user_id);
$legendary_stmt->execute();
$legendary_stats = $legendary_stmt->get_result()->fetch_assoc();

// Get recent inventory items (last 3)
$recent_items_sql = 'SELECT i.InventarisID, i.ItemID, i.DatumAangeschaft,
                           it.Naam, it.Beschrijving, it.Type, it.Zeldzaamheid, 
                           it.Kracht, it.Snelheid, it.Duurzaamheid
                    FROM INVENTARIS i
                    JOIN ITEM it ON i.ItemID = it.ItemID
                    WHERE i.UserID = ?
                    ORDER BY i.DatumAangeschaft DESC
                    LIMIT 3';

$recent_stmt = $conn->prepare($recent_items_sql);
$recent_stmt->bind_param('i', $user_id);
$recent_stmt->execute();
$recent_items = $recent_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get recent trades (last 3)
$recent_trades_sql = 'SELECT h.TradeID, h.VerzenderID, h.OntvangerID, 
                            h.Status, h.CreatedAt,
                            g1.Gebruikersnaam as creator_name,
                            g2.Gebruikersnaam as recipient_name,
                            i1.Naam as item1_name,
                            i2.Naam as item2_name
                    FROM HANDELSVOORSTEL h
                    LEFT JOIN GEBRUIKER g1 ON h.VerzenderID = g1.UserID
                    LEFT JOIN GEBRUIKER g2 ON h.OntvangerID = g2.UserID
                    LEFT JOIN ITEM i1 ON h.VerzendItemID = i1.ItemID
                    LEFT JOIN ITEM i2 ON h.AangevraagdeItemID = i2.ItemID
                    WHERE (h.VerzenderID = ? OR h.OntvangerID = ?)
                    ORDER BY h.CreatedAt DESC
                    LIMIT 3';

$recent_trades_stmt = $conn->prepare($recent_trades_sql);
$recent_trades_stmt->bind_param('ii', $user_id, $user_id);
$recent_trades_stmt->execute();
$recent_trades = $recent_trades_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Prepare data for view
$data = [
    'inventory_stats' => $inventory_stats,
    'trades_stats' => $trades_stats,
    'notif_stats' => $notif_stats,
    'legendary_stats' => $legendary_stats,
    'recent_items' => $recent_items,
    'recent_trades' => $recent_trades
];

// Render view
renderWithLayout('dashboard/index', $data);
?>
