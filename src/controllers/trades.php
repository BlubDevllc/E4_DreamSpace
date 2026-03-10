<?php

/**
 * TRADES CONTROLLER
 * Weergeeft alle ruilvoorstellen van de gebruiker
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

// Require login
if (!isLoggedIn()) {
    $_SESSION['redirect'] = BASE_URL . '?page=trades';
    header('Location: ' . BASE_URL . '?page=login');
    exit();
}

$user_id = getCurrentUserID();
$conn = getDatabaseConnection();

// Get active trades
$active_sql = 'SELECT h.TradeID, h.VerzenderID, h.OntvangerID, 
                      h.Status, h.CreatedAt, h.UpdatedAt,
                      g.Gebruikersnaam as other_user_name,
                      i1.Naam as item1_name, i1.Zeldzaamheid as item1_rarity,
                      i2.Naam as item2_name, i2.Zeldzaamheid as item2_rarity
              FROM HANDELSVOORSTEL h
              LEFT JOIN GEBRUIKER g ON (
                  CASE 
                      WHEN h.VerzenderID = ? THEN h.OntvangerID = g.UserID
                      ELSE h.VerzenderID = g.UserID
                  END
              )
              LEFT JOIN ITEM i1 ON h.VerzendItemID = i1.ItemID
              LEFT JOIN ITEM i2 ON h.AangevraagdeItemID = i2.ItemID
              WHERE (h.VerzenderID = ? OR h.OntvangerID = ?)
              AND h.Status = "In Afwachting"
              ORDER BY h.CreatedAt DESC';

$active_stmt = $conn->prepare($active_sql);
$active_stmt->bind_param('iii', $user_id, $user_id, $user_id);
$active_stmt->execute();
$active_trades = $active_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get trade history
$history_sql = 'SELECT h.TradeID, h.VerzenderID, h.OntvangerID, 
                       h.Status, h.CreatedAt, h.UpdatedAt,
                       g.Gebruikersnaam as other_user_name,
                       i1.Naam as item1_name, i1.Zeldzaamheid as item1_rarity,
                       i2.Naam as item2_name, i2.Zeldzaamheid as item2_rarity
               FROM HANDELSVOORSTEL h
               LEFT JOIN GEBRUIKER g ON (
                   CASE 
                       WHEN h.VerzenderID = ? THEN h.OntvangerID = g.UserID
                       ELSE h.VerzenderID = g.UserID
                   END
               )
               LEFT JOIN ITEM i1 ON h.VerzendItemID = i1.ItemID
               LEFT JOIN ITEM i2 ON h.AangevraagdeItemID = i2.ItemID
               WHERE (h.VerzenderID = ? OR h.OntvangerID = ?)
               AND h.Status IN ("Geaccepteerd", "Afgewezen")
               ORDER BY h.UpdatedAt DESC
               LIMIT 10';

$history_stmt = $conn->prepare($history_sql);
$history_stmt->bind_param('iii', $user_id, $user_id, $user_id);
$history_stmt->execute();
$history_trades = $history_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get pending trades (awaiting response)
$pending_sql = 'SELECT h.TradeID, h.VerzenderID, h.OntvangerID, 
                       h.Status, h.CreatedAt,
                       g.Gebruikersnaam as other_user_name,
                       i1.Naam as item1_name, i1.Zeldzaamheid as item1_rarity,
                       i2.Naam as item2_name, i2.Zeldzaamheid as item2_rarity
               FROM HANDELSVOORSTEL h
               LEFT JOIN GEBRUIKER g ON (
                   CASE 
                       WHEN h.VerzenderID = ? THEN h.OntvangerID = g.UserID
                       ELSE h.VerzenderID = g.UserID
                   END
               )
               LEFT JOIN ITEM i1 ON h.VerzendItemID = i1.ItemID
               LEFT JOIN ITEM i2 ON h.AangevraagdeItemID = i2.ItemID
               WHERE h.OntvangerID = ? AND h.Status = "In Afwachting"
               ORDER BY h.CreatedAt DESC';

$pending_stmt = $conn->prepare($pending_sql);
$pending_stmt->bind_param('ii', $user_id, $user_id);
$pending_stmt->execute();
$pending_trades = $pending_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Prepare data for view
$data = [
    'active_trades' => $active_trades,
    'history_trades' => $history_trades,
    'pending_trades' => $pending_trades
];

// Render view
renderWithLayout('trades/index', $data);
?>
