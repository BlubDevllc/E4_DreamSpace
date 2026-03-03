<?php

/**
 * INVENTORY HANDLER
 * Weergeeft de inventaris van de ingelogde gebruiker
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';

// Require login
if (!isLoggedIn()) {
    $_SESSION['redirect'] = BASE_URL . '?page=inventory';
    header('Location: ' . BASE_URL . '?page=login');
    exit();
}

$user_id = getCurrentUserID();
$conn = getDatabaseConnection();

// Get page for pagination
$page = isset($_GET['page_num']) ? max(1, intval($_GET['page_num'])) : 1;
$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$per_page = 12;
$offset = ($page - 1) * $per_page;

// Build query
$where = 'WHERE i.UserID = ?';
$params = [$user_id];
$types = 'i';

if (!empty($search)) {
    $where .= ' AND (it.Naam LIKE ? OR it.Beschrijving LIKE ?)';
    $search_param = '%' . $search . '%';
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= 'ss';
}

// Get total count
$count_sql = 'SELECT COUNT(*) as total 
             FROM INVENTARIS i
             JOIN ITEM it ON i.ItemID = it.ItemID
             ' . $where;

$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param($types, ...$params);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total / $per_page);

// Get inventory items
$sql = 'SELECT i.InventarisID, i.ItemID, i.DatumToegevoegd,
               it.Naam, it.Beschrijving, it.Type, it.Zeldzaamheid, it.Afbeelding,
               it.Attack, it.Defense, it.Magic, it.Speed, it.HP
        FROM INVENTARIS i
        JOIN ITEM it ON i.ItemID = it.ItemID
        ' . $where . '
        ORDER BY i.DatumToegevoegd DESC
        LIMIT ? OFFSET ?';

$stmt = $conn->prepare($sql);
$stmt->bind_param($types . 'ii', ...$params, $per_page, $offset);
$stmt->execute();
$inventory_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get inventory statistics
$stats_sql = 'SELECT 
              COUNT(*) as total_items,
              COUNT(DISTINCT ItemID) as unique_items,
              GROUP_CONCAT(DISTINCT it.Zeldzaamheid) as rarities
             FROM INVENTARIS i
             JOIN ITEM it ON i.ItemID = it.ItemID
             WHERE i.UserID = ?';

$stats_stmt = $conn->prepare($stats_sql);
$stats_stmt->bind_param('i', $user_id);
$stats_stmt->execute();
$stats = $stats_stmt->get_result()->fetch_assoc();

// Get rarity distribution
$rarity_sql = 'SELECT it.Zeldzaamheid, COUNT(*) as count
              FROM INVENTARIS i
              JOIN ITEM it ON i.ItemID = it.ItemID
              WHERE i.UserID = ?
              GROUP BY it.Zeldzaamheid
              ORDER BY FIELD(it.Zeldzaamheid, "Mythisch", "Legendarisch", "Epic", "Zeldzaam", "Ongewoon", "Gewoon")';

$rarity_stmt = $conn->prepare($rarity_sql);
$rarity_stmt->bind_param('i', $user_id);
$rarity_stmt->execute();
$rarity_distribution = $rarity_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Prepare data for view
$data = [
    'inventory_items' => $inventory_items,
    'total' => $total,
    'page' => $page,
    'per_page' => $per_page,
    'total_pages' => $total_pages,
    'search' => $search,
    'stats' => $stats,
    'rarity_distribution' => $rarity_distribution
];

// Render view
renderWithLayout('inventory/index', $data);

?>
