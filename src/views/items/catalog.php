<div class="items-catalog">
    <div class="catalog-header">
        <h1>Items Katalogus</h1>
        <p>Ontdek alle beschikbare items voor je inventaris</p>
    </div>

    <!-- Filters Sidebar -->
    <div class="catalog-container">
        <aside class="filters-sidebar">
            <form method="GET" class="filters-form">
                <input type="hidden" name="page" value="items">

                <!-- Search -->
                <div class="filter-section">
                    <h3>Zoeken</h3>
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Item naam..." 
                           value="<?php echo htmlspecialchars($search); ?>">
                </div>

                <!-- Type Filter -->
                <div class="filter-section">
                    <h3>Type</h3>
                    <div class="filter-options">
                        <label>
                            <input type="radio" 
                                   name="type" 
                                   value="" 
                                   <?php echo empty($filter_type) ? 'checked' : ''; ?>>
                            Alle types
                        </label>
                        <?php foreach ($available_types as $type): ?>
                            <label>
                                <input type="radio" 
                                       name="type" 
                                       value="<?php echo htmlspecialchars($type['Type']); ?>"
                                       <?php echo $filter_type === $type['Type'] ? 'checked' : ''; ?>>
                                <?php echo htmlspecialchars($type['Type']); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Rarity Filter -->
                <div class="filter-section">
                    <h3>Zeldzaamheid</h3>
                    <div class="filter-options">
                        <label>
                            <input type="radio" 
                                   name="rarity" 
                                   value="" 
                                   <?php echo empty($filter_rarity) ? 'checked' : ''; ?>>
                            Alle rariteiten
                        </label>
                        <?php foreach ($available_rarities as $rarity): ?>
                            <label>
                                <input type="radio" 
                                       name="rarity" 
                                       value="<?php echo htmlspecialchars($rarity['Zeldzaamheid']); ?>"
                                       <?php echo $filter_rarity === $rarity['Zeldzaamheid'] ? 'checked' : ''; ?>>
                                <span class="rarity-badge rarity-<?php echo strtolower(str_replace(' ', '_', $rarity['Zeldzaamheid'])); ?>">
                                    <?php echo htmlspecialchars($rarity['Zeldzaamheid']); ?>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Sort -->
                <div class="filter-section">
                    <h3>Sorteren</h3>
                    <select name="sort" class="form-control">
                        <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Nieuwste</option>
                        <option value="oldest" <?php echo $sort === 'oldest' ? 'selected' : ''; ?>>Oudste</option>
                        <option value="name_asc" <?php echo $sort === 'name_asc' ? 'selected' : ''; ?>>Naam (A-Z)</option>
                        <option value="name_desc" <?php echo $sort === 'name_desc' ? 'selected' : ''; ?>>Naam (Z-A)</option>
                        <option value="rarity" <?php echo $sort === 'rarity' ? 'selected' : ''; ?>>Zeldzaamheid</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Filter toepassen</button>
                
                <?php if (!empty($search) || !empty($filter_type) || !empty($filter_rarity)): ?>
                    <a href="<?php echo BASE_URL; ?>?page=items" class="btn btn-secondary btn-block">Filter wissen</a>
                <?php endif; ?>
            </form>
        </aside>

        <!-- Items Grid -->
        <main class="items-main">
            <!-- Results Summary -->
            <div class="results-header">
                <h2>Resultaten: <?php echo $total; ?> items gevonden</h2>
                <div class="view-options">
                    <button class="view-btn active" data-view="grid">⊞ Grid</button>
                    <button class="view-btn" data-view="list">☰ Lijst</button>
                </div>
            </div>

            <!-- Items Grid -->
            <?php if (!empty($items)): ?>
                <div class="items-grid">
                    <?php foreach ($items as $item): 
                        $rarity_class = strtolower(str_replace(' ', '_', $item['Zeldzaamheid']));
                    ?>
                        <div class="item-card">
                            <div class="item-image">
                                <img src="<?php echo htmlspecialchars($item['Afbeelding']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['Naam']); ?>">
                                <span class="rarity-badge rarity-<?php echo $rarity_class; ?>">
                                    <?php echo htmlspecialchars($item['Zeldzaamheid']); ?>
                                </span>
                            </div>
                            <div class="item-info">
                                <h3><?php echo htmlspecialchars($item['Naam']); ?></h3>
                                <p class="item-type"><?php echo htmlspecialchars($item['Type']); ?></p>
                                <p class="item-description"><?php echo htmlspecialchars($item['Beschrijving']); ?></p>
                                <div class="item-actions">
                                    <a href="<?php echo BASE_URL; ?>?page=item-detail&id=<?php echo $item['ItemID']; ?>" 
                                       class="btn btn-primary">Details</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="<?php echo BASE_URL; ?>?page=items&page_num=1<?php if (!empty($search)) echo '&search=' . urlencode($search); ?><?php if (!empty($filter_type)) echo '&type=' . urlencode($filter_type); ?><?php if (!empty($filter_rarity)) echo '&rarity=' . urlencode($filter_rarity); ?>" class="btn btn-secondary">« Eerste</a>
                            <a href="<?php echo BASE_URL; ?>?page=items&page_num=<?php echo $page - 1; ?><?php if (!empty($search)) echo '&search=' . urlencode($search); ?><?php if (!empty($filter_type)) echo '&type=' . urlencode($filter_type); ?><?php if (!empty($filter_rarity)) echo '&rarity=' . urlencode($filter_rarity); ?>" class="btn btn-secondary">‹ Vorig</a>
                        <?php endif; ?>

                        <div class="page-info">
                            Pagina <?php echo $page; ?> van <?php echo $total_pages; ?>
                        </div>

                        <?php if ($page < $total_pages): ?>
                            <a href="<?php echo BASE_URL; ?>?page=items&page_num=<?php echo $page + 1; ?><?php if (!empty($search)) echo '&search=' . urlencode($search); ?><?php if (!empty($filter_type)) echo '&type=' . urlencode($filter_type); ?><?php if (!empty($filter_rarity)) echo '&rarity=' . urlencode($filter_rarity); ?>" class="btn btn-secondary">Volgen ›</a>
                            <a href="<?php echo BASE_URL; ?>?page=items&page_num=<?php echo $total_pages; ?><?php if (!empty($search)) echo '&search=' . urlencode($search); ?><?php if (!empty($filter_type)) echo '&type=' . urlencode($filter_type); ?><?php if (!empty($filter_rarity)) echo '&rarity=' . urlencode($filter_rarity); ?>" class="btn btn-secondary">Laatste »</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="alert alert-info">
                    <h3>Geen items gevonden</h3>
                    <p>Probeer je zoekfilters aan te passen of <a href="<?php echo BASE_URL; ?>?page=items">alle items te bekijken</a>.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<style>
.items-catalog {
    padding: 20px;
}

.catalog-header {
    text-align: center;
    margin-bottom: 30px;
    padding: 30px 0;
    border-bottom: 2px solid #e0e0e0;
}

.catalog-header h1 {
    margin: 0 0 10px;
    font-size: 2.5em;
}

.catalog-header p {
    color: #666;
    font-size: 1.1em;
}

.catalog-container {
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: 30px;
}

/* Filters Sidebar */
.filters-sidebar {
    position: sticky;
    top: 100px;
    height: fit-content;
}

.filters-form {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.filter-section h3 {
    font-size: 1.1em;
    margin-bottom: 12px;
    color: #333;
}

.filter-options {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.filter-options label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    padding: 8px;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.filter-options label:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

.filter-options input[type="radio"] {
    cursor: pointer;
}

.rarity-badge {
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.85em;
    font-weight: bold;
    display: inline-block;
}

.rarity-mythisch {
    background: linear-gradient(135deg, #9400d3, #ff1493);
    color: white;
}

.rarity-legendarisch {
    background: linear-gradient(135deg, #ffd700, #ff8c00);
    color: white;
}

.rarity-epic {
    background: linear-gradient(135deg, #4169e1, #1e90ff);
    color: white;
}

.rarity-zeldzaam {
    background: linear-gradient(135deg, #32cd32, #00fa9a);
    color: white;
}

.rarity-ongewoon {
    background: #87ceeb;
    color: white;
}

.rarity-gewoon {
    background: #d3d3d3;
    color: #333;
}

/* Items Main */
.items-main {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.results-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.results-header h2 {
    margin: 0;
    font-size: 1.5em;
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
    font-size: 0.9em;
}

.view-btn.active {
    background: #007bff;
    color: white;
    border-color: #007bff;
}

.items-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
}

.item-card {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s;
    cursor: pointer;
}

.item-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: translateY(-4px);
}

.item-image {
    position: relative;
    width: 100%;
    height: 200px;
    overflow: hidden;
    background: linear-gradient(135deg, #e0e0e0, #f5f5f5);
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-image .rarity-badge {
    position: absolute;
    top: 10px;
    right: 10px;
}

.item-info {
    padding: 15px;
}

.item-info h3 {
    margin: 0 0 8px;
    font-size: 1.1em;
    color: #333;
}

.item-type {
    margin: 0 0 8px;
    color: #666;
    font-size: 0.9em;
    text-transform: uppercase;
}

.item-description {
    margin: 0 0 12px;
    color: #555;
    font-size: 0.9em;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.item-actions {
    display: flex;
    gap: 8px;
}

.item-actions .btn {
    flex: 1;
    padding: 8px;
    font-size: 0.9em;
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
}

.page-info {
    padding: 0 20px;
    font-weight: bold;
    color: #333;
}

/* Responsive */
@media (max-width: 992px) {
    .catalog-container {
        grid-template-columns: 1fr;
    }

    .filters-sidebar {
        position: relative;
        top: auto;
    }

    .items-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }
}

@media (max-width: 576px) {
    .catalog-header {
        padding: 20px 0;
    }

    .catalog-header h1 {
        font-size: 1.8em;
    }

    .results-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }

    .items-grid {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 12px;
    }

    .pagination {
        flex-wrap: wrap;
    }
}
</style>
