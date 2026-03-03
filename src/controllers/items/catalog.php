<?php

/**
 * ITEMS CATALOG HANDLER
 * Weergeeft alle beschikbare items
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';

// Require login
if (!isLoggedIn()) {
    $_SESSION['redirect'] = BASE_URL . '?page=items';
    header('Location: ' . BASE_URL . '?page=login');
    exit();
}

// Get query parameters
$page = isset($_GET['page_num']) ? max(1, intval($_GET['page_num'])) : 1;
$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$filter_type = isset($_GET['type']) ? sanitizeInput($_GET['type']) : '';
$filter_rarity = isset($_GET['rarity']) ? sanitizeInput($_GET['rarity']) : '';
$sort = isset($_GET['sort']) ? sanitizeInput($_GET['sort']) : 'newest';

// Items per page
$per_page = 12;
$offset = ($page - 1) * $per_page;

// Build query
$conn = getDatabaseConnection();
$where_clauses = [];
$params = [];
$types = '';

// Search filter
if (!empty($search)) {
    $where_clauses[] = '(Naam LIKE ? OR Beschrijving LIKE ?)';
    $search_param = '%' . $search . '%';
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= 'ss';
}

// Type filter
if (!empty($filter_type)) {
    $where_clauses[] = 'Type = ?';
    $params[] = $filter_type;
    $types .= 's';
}

// Rarity filter
if (!empty($filter_rarity)) {
    $where_clauses[] = 'Zeldzaamheid = ?';
    $params[] = $filter_rarity;
    $types .= 's';
}

$where_sql = !empty($where_clauses) ? 'WHERE ' . implode(' AND ', $where_clauses) : '';

// Sort options
$order_sql = 'ItemID DESC'; // default: newest
switch ($sort) {
    case 'oldest':
        $order_sql = 'ItemID ASC';
        break;
    case 'name_asc':
        $order_sql = 'Naam ASC';
        break;
    case 'name_desc':
        $order_sql = 'Naam DESC';
        break;
    case 'rarity':
        $order_sql = 'FIELD(Zeldzaamheid, "Mythisch", "Legendarisch", "Epic", "Zeldzaam", "Ongewoon", "Gewoon")';
        break;
}

// Get total count for pagination
$count_sql = 'SELECT COUNT(*) as total FROM ITEM ' . $where_sql;
$count_stmt = $conn->prepare($count_sql);
if (!empty($types)) {
    $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total / $per_page);

// Get items
$sql = 'SELECT ItemID, Naam, Beschrijving, Type, Zeldzaamheid, Kracht, Snelheid, Duurzaamheid, MagischeEigenschappen 
        FROM ITEM 
        ' . $where_sql . '
        ORDER BY ' . $order_sql . '
        LIMIT ? OFFSET ?';

$stmt = $conn->prepare($sql);

// Add pagination params
$params[] = $per_page;
$params[] = $offset;
$types_final = $types . 'ii';

if (!empty($types)) {
    $stmt->bind_param($types_final, ...$params);
} else {
    $stmt->bind_param('ii', $per_page, $offset);
}
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get available types for filter
$types_sql = 'SELECT DISTINCT Type FROM ITEM ORDER BY Type';
$types_result = $conn->query($types_sql);
$available_types = $types_result->fetch_all(MYSQLI_ASSOC);

// Get available rarities
$rarities_sql = 'SELECT DISTINCT Zeldzaamheid FROM ITEM ORDER BY FIELD(Zeldzaamheid, "Mythisch", "Legendarisch", "Epic", "Zeldzaam", "Ongewoon", "Gewoon")';
$rarities_result = $conn->query($rarities_sql);
$available_rarities = $rarities_result->fetch_all(MYSQLI_ASSOC);

// Prepare data for view
$data = [
    'items' => $items,
    'total' => $total,
    'page' => $page,
    'per_page' => $per_page,
    'total_pages' => $total_pages,
    'search' => $search,
    'filter_type' => $filter_type,
    'filter_rarity' => $filter_rarity,
    'sort' => $sort,
    'available_types' => $available_types,
    'available_rarities' => $available_rarities
];

// Render view
renderWithLayout('items/catalog', $data);

?>
