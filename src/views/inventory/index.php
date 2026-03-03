<div class="inventory-page">
    <div class="inventory-header">
        <h1>Mijn Inventaris</h1>
        <p>Beheer je verzamelde items</p>
    </div>

    <!-- Statistics -->
    <div class="inventory-stats">
        <div class="stat-card">
            <div class="stat-value"><?php echo $stats['total_items'] ?? 0; ?></div>
            <div class="stat-label">Totaal Items</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?php echo $stats['unique_items'] ?? 0; ?></div>
            <div class="stat-label">Unieke Items</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?php echo count($rarity_distribution); ?></div>
            <div class="stat-label">Rariteittypen</div>
        </div>
    </div>

    <!-- Rarity Distribution -->
    <?php if (!empty($rarity_distribution)): ?>
        <div class="rarity-section">
            <h2>Rariteitsverdeling</h2>
            <div class="rarity-list">
                <?php foreach ($rarity_distribution as $rarity): 
                    $rarity_class = strtolower(str_replace(' ', '_', $rarity['Zeldzaamheid']));
                ?>
                    <div class="rarity-row">
                        <span class="rarity-name">
                            <span class="rarity-badge rarity-<?php echo $rarity_class; ?>">
                                <?php echo htmlspecialchars($rarity['Zeldzaamheid']); ?>
                            </span>
                        </span>
                        <div class="rarity-bar">
                            <div class="rarity-bar-fill" style="width: <?php echo ($rarity['count'] / max(1, $stats['total_items'])) * 100; ?>%"></div>
                        </div>
                        <span class="rarity-count"><?php echo $rarity['count']; ?> items</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Search & Filter -->
    <div class="inventory-search">
        <form method="GET" class="search-form">
            <input type="hidden" name="page" value="inventory">
            <input type="text" 
                   name="search" 
                   class="form-control" 
                   placeholder="Zoeken in inventaris..." 
                   value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-primary">Zoeken</button>
            <?php if (!empty($search)): ?>
                <a href="<?php echo BASE_URL; ?>?page=inventory" class="btn btn-secondary">Wissen</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Items -->
    <?php if (!empty($inventory_items)): ?>
        <div class="inventory-items">
            <div class="inventory-table-header">
                <h2><?php echo $total; ?> items in inventaris</h2>
                <div class="view-options">
                    <button class="view-btn active" data-view="grid">⊞ Grid</button>
                    <button class="view-btn" data-view="table"><i class="fas fa-list"></i> Lijst</button>
                </div>
            </div>

            <!-- Grid View (Default) -->
            <div class="items-grid active" data-view="grid">
                <?php foreach ($inventory_items as $item): 
                    $rarity_class = strtolower(str_replace(' ', '_', $item['Zeldzaamheid']));
                ?>
                    <div class="inventory-card">
                        <div class="card-image">
                            <img src="https://via.placeholder.com/300x300?text=<?php echo urlencode($item['Naam']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['Naam']); ?>">
                            <span class="rarity-badge rarity-<?php echo $rarity_class; ?>">
                                <?php echo htmlspecialchars($item['Zeldzaamheid']); ?>
                            </span>
                        </div>
                        <div class="card-content">
                            <h3><?php echo htmlspecialchars($item['Naam']); ?></h3>
                            <p class="item-type"><?php echo htmlspecialchars($item['Type']); ?></p>
                            
                            <!-- Stats -->
                            <div class="item-stats">
                                <?php if ($item['Kracht'] > 0): ?>
                                    <div class="stat"><span class="stat-icon"><i class="fas fa-sword"></i></span> <?php echo $item['Kracht']; ?> STR</div>
                                <?php endif; ?>
                                <?php if ($item['Snelheid'] > 0): ?>
                                    <div class="stat"><span class="stat-icon"><i class="fas fa-bolt"></i></span> <?php echo $item['Snelheid']; ?> SPD</div>
                                <?php endif; ?>
                                <?php if ($item['Duurzaamheid'] > 0): ?>
                                    <div class="stat"><span class="stat-icon"><i class="fas fa-shield"></i></span> <?php echo $item['Duurzaamheid']; ?> DUR</div>
                                <?php endif; ?>
                            </div>

                            <p class="item-description"><?php echo htmlspecialchars($item['Beschrijving']); ?></p>
                            
                            <div class="card-actions">
                                <small>Toegevoegd: <?php echo date('d-m-Y', strtotime($item['DatumAangeschaft'])); ?></small>
                                <button class="btn btn-danger btn-sm" onclick="removeFromInventory(<?php echo $item['InventarisID']; ?>)">Verwijderen</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Table View -->
            <div class="items-table" data-view="table" style="display: none;">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Type</th>
                            <th>Zeldzaamheid</th>
                            <th>Stats</th>
                            <th>Toegevoegd</th>
                            <th>Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inventory_items as $item): 
                            $rarity_class = strtolower(str_replace(' ', '_', $item['Zeldzaamheid']));
                        ?>
                            <tr>
                                <td>
                                    <div class="item-row">
                                        <img src="https://via.placeholder.com/50x50?text=<?php echo urlencode($item['Naam']); ?>" 
                                             alt="<?php echo htmlspecialchars($item['Naam']); ?>" 
                                             class="item-thumb">
                                        <div>
                                            <strong><?php echo htmlspecialchars($item['Naam']); ?></strong>
                                            <small><?php echo htmlspecialchars($item['Beschrijving']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($item['Type']); ?></td>
                                <td>
                                    <span class="rarity-badge rarity-<?php echo $rarity_class; ?>">
                                        <?php echo htmlspecialchars($item['Zeldzaamheid']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="stats-compact">
                                        <?php if ($item['Kracht'] > 0): ?>STR<?php echo $item['Kracht']; ?><?php endif; ?>
                                        <?php if ($item['Snelheid'] > 0): ?>SPD<?php echo $item['Snelheid']; ?><?php endif; ?>
                                        <?php if ($item['Duurzaamheid'] > 0): ?>DUR<?php echo $item['Duurzaamheid']; ?><?php endif; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d-m-Y', strtotime($item['DatumAangeschaft'])); ?></td>
                                <td>
                                    <button class="btn btn-danger btn-sm" onclick="removeFromInventory(<?php echo $item['InventarisID']; ?>)">Verwijderen</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="<?php echo BASE_URL; ?>?page=inventory&page_num=1<?php if (!empty($search)) echo '&search=' . urlencode($search); ?>" class="btn btn-secondary">« Eerste</a>
                        <a href="<?php echo BASE_URL; ?>?page=inventory&page_num=<?php echo $page - 1; ?><?php if (!empty($search)) echo '&search=' . urlencode($search); ?>" class="btn btn-secondary">‹ Vorig</a>
                    <?php endif; ?>

                    <div class="page-info">
                        Pagina <?php echo $page; ?> van <?php echo $total_pages; ?>
                    </div>

                    <?php if ($page < $total_pages): ?>
                        <a href="<?php echo BASE_URL; ?>?page=inventory&page_num=<?php echo $page + 1; ?><?php if (!empty($search)) echo '&search=' . urlencode($search); ?>" class="btn btn-secondary">Volgen ›</a>
                        <a href="<?php echo BASE_URL; ?>?page=inventory&page_num=<?php echo $total_pages; ?><?php if (!empty($search)) echo '&search=' . urlencode($search); ?>" class="btn btn-secondary">Laatste »</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

    <?php else: ?>
        <div class="alert alert-info">
            <h3>Inventaris is leeg</h3>
            <p>Je hebt nog geen items in je inventaris. Ga naar <a href="<?php echo BASE_URL; ?>?page=items">Items Katalogus</a> om items toe te voegen.</p>
        </div>
    <?php endif; ?>
</div>

<script>
function removeFromInventory(itemId) {
    if (confirm('Wil je dit item echt verwijderen uit je inventaris?')) {
        // TODO: Implement AJAX delete
        showNotification('Item verwijderd!', 'success');
    }
}

// View toggle
document.querySelectorAll('.view-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const view = this.dataset.view;
        document.querySelectorAll('[data-view]').forEach(el => {
            el.style.display = el.dataset.view === view ? 'block' : 'none';
        });
        document.querySelectorAll('.view-btn').forEach(b => {
            b.classList.toggle('active', b === this);
        });
    });
});
</script>

<style>
.inventory-page {
    padding: 20px;
}

.inventory-header {
    text-align: center;
    margin-bottom: 30px;
    padding: 30px 0;
    border-bottom: 2px solid #e0e0e0;
}

.inventory-header h1 {
    margin: 0 0 10px;
    font-size: 2.5em;
}

.inventory-header p {
    color: #666;
    font-size: 1.1em;
}

/* Statistics */
.inventory-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.stat-value {
    font-size: 2.5em;
    font-weight: bold;
    margin-bottom: 10px;
}

.stat-label {
    font-size: 0.95em;
    opacity: 0.9;
}

/* Rarity Section */
.rarity-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
}

.rarity-section h2 {
    margin-top: 0;
    margin-bottom: 20px;
}

.rarity-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.rarity-row {
    display: grid;
    grid-template-columns: 120px 1fr 80px;
    gap: 15px;
    align-items: center;
}

.rarity-name {
    font-weight: bold;
}

.rarity-bar {
    height: 20px;
    background: #e0e0e0;
    border-radius: 10px;
    overflow: hidden;
}

.rarity-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #667eea, #764ba2);
    transition: width 0.3s;
}

.rarity-count {
    text-align: right;
    font-size: 0.9em;
}

/* Search */
.inventory-search {
    margin-bottom: 30px;
}

.search-form {
    display: grid;
    grid-template-columns: 1fr auto auto;
    gap: 10px;
}

.search-form > * {
    padding: 10px;
    border-radius: 4px;
    border: 1px solid #ddd;
}

.search-form button {
    padding: 10px 20px;
}

/* Items Grid */
.inventory-items {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.inventory-table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.inventory-table-header h2 {
    margin: 0;
}

.view-options {
    display: flex;
    gap: 10px;
}

.view-btn {
    padding: 8px 16px;
    border: 2px solid #ddd;
    background: white;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
}

.view-btn.active {
    background: #007bff;
    color: white;
    border-color: #007bff;
}

.items-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 20px;
}

.inventory-card {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s;
}

.inventory-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: translateY(-4px);
}

.card-image {
    position: relative;
    width: 100%;
    height: 200px;
    background: linear-gradient(135deg, #e0e0e0, #f5f5f5);
    overflow: hidden;
}

.card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.card-image .rarity-badge {
    position: absolute;
    top: 10px;
    right: 10px;
}

.card-content {
    padding: 15px;
}

.card-content h3 {
    margin: 0 0 8px;
    font-size: 1.1em;
    color: #333;
}

.item-type {
    margin: 0 0 10px;
    color: #666;
    font-size: 0.9em;
    text-transform: uppercase;
}

.item-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    margin-bottom: 10px;
    padding: 10px;
    background: #f5f5f5;
    border-radius: 4px;
    font-size: 0.85em;
}

.stat {
    display: flex;
    align-items: center;
    gap: 4px;
}

.stat-icon {
    font-size: 1.1em;
}

.item-description {
    margin: 0 0 10px;
    color: #555;
    font-size: 0.9em;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.card-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    padding-top: 10px;
    border-top: 1px solid #e0e0e0;
    font-size: 0.85em;
}

/* Table View */
.items-table {
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

.table th {
    background: #f8f9fa;
    padding: 12px;
    font-weight: bold;
    border-bottom: 2px solid #ddd;
    text-align: left;
}

.table td {
    padding: 12px;
    border-bottom: 1px solid #e0e0e0;
}

.table tr:hover {
    background: #f8f9fa;
}

.item-row {
    display: flex;
    align-items: center;
    gap: 12px;
}

.item-thumb {
    width: 50px;
    height: 50px;
    border-radius: 4px;
    object-fit: cover;
}

.item-row div {
    flex: 1;
}

.item-row small {
    display: block;
    color: #666;
}

.stats-compact {
    display: flex;
    gap: 8px;
    font-size: 0.85em;
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    flex-wrap: wrap;
}

.page-info {
    padding: 0 20px;
    font-weight: bold;
}

/* Alert */
.alert {
    padding: 20px;
    border-radius: 8px;
    margin-top: 20px;
}

.alert-info {
    background: #d1ecf1;
    border: 1px solid #bee5eb;
    color: #0c5460;
}

.alert-info h3 {
    margin-top: 0;
}

.alert-info a {
    color: #0c5460;
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 992px) {
    .items-grid {
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    }

    .rarity-row {
        grid-template-columns: 100px 1fr 70px;
    }
}

@media (max-width: 768px) {
    .items-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }

    .inventory-table-header {
        flex-direction: column;
        gap: 15px;
    }

    .search-form {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .items-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .inventory-stats {
        grid-template-columns: 1fr;
    }

    .rarity-row {
        grid-template-columns: 80px 1fr 60px;
        font-size: 0.9em;
    }
}
</style>
