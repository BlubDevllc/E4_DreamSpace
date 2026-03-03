<?php

/**
 * ITEM DETAIL HANDLER
 * Weergeeft gedetailleerde informatie over een specifiek item
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';

// Require login
if (!isLoggedIn()) {
    $_SESSION['redirect'] = BASE_URL . '?page=item-detail' . (isset($_GET['id']) ? '&id=' . $_GET['id'] : '');
    header('Location: ' . BASE_URL . '?page=login');
    exit();
}

// Get item ID
$item_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($item_id <= 0) {
    http_response_code(404);
    renderWithLayout('errors/404');
    exit();
}

$conn = getDatabaseConnection();
$user_id = getCurrentUserID();

// Get item details
$stmt = $conn->prepare(
    'SELECT ItemID, Naam, Beschrijving, Type, Zeldzaamheid, Kracht, Snelheid, Duurzaamheid, MagischeEigenschappen
     FROM ITEM
     WHERE ItemID = ?'
);

$stmt->bind_param('i', $item_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    renderWithLayout('errors/404');
    exit();
}

$item = $result->fetch_assoc();

// Check if user has this item in inventory
$inv_stmt = $conn->prepare(
    'SELECT COUNT(*) as count FROM INVENTARIS WHERE UserID = ? AND ItemID = ?'
);

$inv_stmt->bind_param('ii', $user_id, $item_id);
$inv_stmt->execute();
$inv_result = $inv_stmt->get_result();
$inventory_count = $inv_result->fetch_assoc()['count'];

// Get all users who have this item (for trading)
$owners_stmt = $conn->prepare(
    'SELECT DISTINCT g.UserID, g.Gebruikersnaam
     FROM INVENTARIS i
     JOIN GEBRUIKER g ON i.UserID = g.UserID
     WHERE i.ItemID = ? AND g.UserID != ?
     LIMIT 5'
);

$owners_stmt->bind_param('ii', $item_id, $user_id);
$owners_stmt->execute();
$other_owners = $owners_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get similar items (same type or rarity)
$similar_stmt = $conn->prepare(
    'SELECT ItemID, Naam, Zeldzaamheid
     FROM ITEM
     WHERE (Type = ? OR Zeldzaamheid = ?)
     AND ItemID != ?
     LIMIT 4'
);

$similar_stmt->bind_param('ssi', $item['Type'], $item['Zeldzaamheid'], $item_id);
$similar_stmt->execute();
$similar_items = $similar_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Prepare data for view
$data = [
    'item' => $item,
    'inventory_count' => $inventory_count,
    'other_owners' => $other_owners,
    'similar_items' => $similar_items
];

// Render view
renderWithLayout('items/detail', $data);

?>
