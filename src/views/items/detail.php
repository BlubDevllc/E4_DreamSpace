<div class="item-detail-page">
    <div class="detail-container">
        <!-- Back Button -->
        <a href="<?php echo BASE_URL; ?>?page=items" class="back-button">← Terug naar catalogus</a>

        <!-- Main Detail -->
        <div class="detail-main">
            <!-- Item Image -->
            <div class="item-image-section">
                <div class="item-image-wrapper">
                    <img src="https://via.placeholder.com/400x400?text=<?php echo urlencode($item['Naam']); ?>" 
                         alt="<?php echo htmlspecialchars($item['Naam']); ?>"
                         class="item-image-large">
                    <span class="rarity-badge rarity-<?php echo strtolower(str_replace(' ', '_', $item['Zeldzaamheid'])); ?>">
                        <?php echo htmlspecialchars($item['Zeldzaamheid']); ?>
                    </span>
                </div>

                <?php if ($inventory_count > 0): ?>
                    <div class="alert alert-success">
                        ✓ Je hebt dit item (<?php echo $inventory_count; ?>x in inventaris)
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        Dit item is niet in je inventaris
                    </div>
                <?php endif; ?>
            </div>

            <!-- Item Info -->
            <div class="item-info-section">
                <h1><?php echo htmlspecialchars($item['Naam']); ?></h1>
                <p class="item-type-tag"><?php echo htmlspecialchars($item['Type']); ?></p>

                <!-- Description -->
                <div class="description-box">
                    <h3>Beschrijving</h3>
                    <p><?php echo htmlspecialchars($item['Beschrijving']); ?></p>
                </div>

                <!-- Stats -->
                <div class="stats-box">
                    <h3>Statistieken</h3>
                    <div class="stats-grid">
                        <?php if ($item['Kracht'] >= 0): ?>
                            <div class="stat-item">
                                <div class="stat-bar">
                                    <div class="stat-fill attack" style="width: <?php echo min(100, ($item['Kracht'] / 100) * 100); ?>%"></div>
                                </div>
                                <div class="stat-label">Kracht: <span><?php echo $item['Kracht']; ?></span></div>
                            </div>
                        <?php endif; ?>

                        <?php if ($item['Snelheid'] >= 0): ?>
                            <div class="stat-item">
                                <div class="stat-bar">
                                    <div class="stat-fill speed" style="width: <?php echo min(100, ($item['Snelheid'] / 100) * 100); ?>%"></div>
                                </div>
                                <div class="stat-label">Snelheid: <span><?php echo $item['Snelheid']; ?></span></div>
                            </div>
                        <?php endif; ?>

                        <?php if ($item['Duurzaamheid'] >= 0): ?>
                            <div class="stat-item">
                                <div class="stat-bar">
                                    <div class="stat-fill defense" style="width: <?php echo min(100, ($item['Duurzaamheid'] / 100) * 100); ?>%"></div>
                                </div>
                                <div class="stat-label">Duurzaamheid: <span><?php echo $item['Duurzaamheid']; ?></span></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Actions -->
                <div class="actions-box">
                    <button class="btn btn-primary btn-large" onclick="addToTrade()">Aanbieden in Ruilvoorstel</button>
                    <button class="btn btn-secondary btn-large" onclick="shareItem()">Delen</button>
                </div>
            </div>
        </div>

        <!-- Other Owners -->
        <div class="other-owners-section">
            <h2>Andere spelers met dit item</h2>
            <?php if (!empty($other_owners)): ?>
                <div class="owners-grid">
                    <?php foreach ($other_owners as $owner): ?>
                        <div class="owner-card">
                            <div class="owner-avatar">
                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($owner['Gebruikersnaam']); ?>&background=667eea&color=fff" 
                                     alt="<?php echo htmlspecialchars($owner['Gebruikersnaam']); ?>">
                            </div>
                            <div class="owner-info">
                                <h4><?php echo htmlspecialchars($owner['Gebruikersnaam']); ?></h4>
                                <button class="btn btn-primary btn-sm" onclick="startTrade(<?php echo $owner['UserID']; ?>)">
                                    Voorstel doen
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center text-muted">Geen andere spelers hebben dit item</p>
            <?php endif; ?>
        </div>

        <!-- Similar Items -->
        <div class="similar-items-section">
            <h2>Vergelijkbare Items</h2>
            <?php if (!empty($similar_items)): ?>
                <div class="similar-items-grid">
                    <?php foreach ($similar_items as $similar): 
                        $rarity_class = strtolower(str_replace(' ', '_', $similar['Zeldzaamheid']));
                    ?>
                        <div class="similar-card">
                            <a href="<?php echo BASE_URL; ?>?page=item-detail&id=<?php echo $similar['ItemID']; ?>">
                                <div class="similar-image">
                                    <img src="https://via.placeholder.com/150x150?text=<?php echo urlencode($similar['Naam']); ?>" 
                                         alt="<?php echo htmlspecialchars($similar['Naam']); ?>">
                                    <span class="rarity-badge rarity-<?php echo $rarity_class; ?>">
                                        <?php echo htmlspecialchars($similar['Zeldzaamheid']); ?>
                                    </span>
                                </div>
                                <div class="similar-name"><?php echo htmlspecialchars($similar['Naam']); ?></div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center text-muted">Geen vergelijkbare items gevonden</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function addToTrade() {
    showNotification('Dit item is aan je ruilvoorstel toegevoegd!', 'success');
}

function shareItem() {
    const text = 'Bekijk dit item: <?php echo htmlspecialchars($item['Naam']); ?>';
    const url = window.location.href;
    
    if (navigator.share) {
        navigator.share({
            title: '<?php echo htmlspecialchars($item['Naam']); ?>',
            text: text,
            url: url
        });
    } else {
        // Fallback
        const textarea = document.createElement('textarea');
        textarea.value = url;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showNotification('Link gekopieerd!', 'success');
    }
}

function startTrade(userId) {
    showNotification('Ruilvoorstel gestart met deze speler!', 'info');
    // TODO: Redirect to trade creation
}
</script>

<style>
.item-detail-page {
    padding: 20px;
}

.detail-container {
    max-width: 1200px;
    margin: 0 auto;
}

.back-button {
    display: inline-block;
    padding: 8px 16px;
    background: #f0f0f0;
    border-radius: 4px;
    text-decoration: none;
    color: #333;
    margin-bottom: 20px;
    transition: all 0.2s;
}

.back-button:hover {
    background: #e0e0e0;
}

/* Detail Main */
.detail-main {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    background: white;
    padding: 30px;
    border-radius: 8px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Item Image Section */
.item-image-section {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.item-image-wrapper {
    position: relative;
    width: 100%;
    aspect-ratio: 1 / 1;
    background: linear-gradient(135deg, #e0e0e0, #f5f5f5);
    border-radius: 8px;
    overflow: hidden;
}

.item-image-large {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-image-wrapper .rarity-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 8px 16px;
    font-size: 1em;
}

/* Item Info Section */
.item-info-section {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.item-info-section h1 {
    margin: 0;
    font-size: 2.5em;
    color: #333;
}

.item-type-tag {
    margin: 0;
    display: inline-block;
    background: #f0f0f0;
    padding: 6px 12px;
    border-radius: 4px;
    width: fit-content;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 0.85em;
}

/* Description Box */
.description-box {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 6px;
}

.description-box h3 {
    margin-top: 0;
    margin-bottom: 10px;
    color: #333;
}

.description-box p {
    margin: 0;
    line-height: 1.6;
    color: #555;
}

/* Stats Box */
.stats-box {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 6px;
}

.stats-box h3 {
    margin-top: 0;
    margin-bottom: 15px;
    color: #333;
}

.stats-grid {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.stat-bar {
    flex: 1;
    height: 24px;
    background: #e0e0e0;
    border-radius: 4px;
    overflow: hidden;
}

.stat-fill {
    height: 100%;
    transition: width 0.3s;
}

.stat-fill.hp {
    background: #27ae60;
}

.stat-fill.attack {
    background: #e74c3c;
}

.stat-fill.defense {
    background: #3498db;
}

.stat-fill.magic {
    background: #9b59b6;
}

.stat-fill.speed {
    background: #f39c12;
}

.stat-label {
    width: 80px;
    font-weight: bold;
    text-align: right;
    font-size: 0.9em;
}

.stat-label span {
    color: #666;
}

/* Actions Box */
.actions-box {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding-top: 15px;
}

.btn-large {
    padding: 12px 20px;
    font-size: 1em;
}

/* Other Owners Section */
.other-owners-section {
    background: white;
    padding: 30px;
    border-radius: 8px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.other-owners-section h2 {
    margin-top: 0;
    margin-bottom: 20px;
    color: #333;
}

.owners-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
}

.owner-card {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 6px;
    text-align: center;
}

.owner-avatar {
    margin-bottom: 15px;
}

.owner-avatar img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 3px solid #667eea;
}

.owner-info h4 {
    margin: 10px 0;
    color: #333;
}

.owner-info .btn-sm {
    padding: 6px 12px;
    font-size: 0.9em;
}

/* Similar Items Section */
.similar-items-section {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.similar-items-section h2 {
    margin-top: 0;
    margin-bottom: 20px;
    color: #333;
}

.similar-items-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 20px;
}

.similar-card {
    border-radius: 6px;
    overflow: hidden;
    background: white;
    border: 1px solid #ddd;
    transition: all 0.3s;
}

.similar-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.similar-card a {
    text-decoration: none;
    color: inherit;
}

.similar-image {
    position: relative;
    width: 100%;
    aspect-ratio: 1 / 1;
    background: linear-gradient(135deg, #e0e0e0, #f5f5f5);
    overflow: hidden;
}

.similar-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.similar-image .rarity-badge {
    position: absolute;
    top: 5px;
    right: 5px;
    padding: 4px 8px;
    font-size: 0.75em;
}

.similar-name {
    padding: 10px;
    text-align: center;
    font-weight: bold;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    color: #333;
}

/* Alert */
.alert {
    padding: 15px;
    border-radius: 6px;
    margin: 0;
}

.alert-success {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.alert-info {
    background: #d1ecf1;
    border: 1px solid #bee5eb;
    color: #0c5460;
}

.text-center {
    text-align: center;
}

.text-muted {
    color: #999;
}

/* Responsive */
@media (max-width: 992px) {
    .detail-main {
        grid-template-columns: 1fr;
    }

    .similar-items-grid {
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    }
}

@media (max-width: 576px) {
    .item-detail-page {
        padding: 10px;
    }

    .detail-main {
        padding: 15px;
        gap: 20px;
    }

    .item-info-section h1 {
        font-size: 1.8em;
    }

    .stats-grid {
        gap: 8px;
    }

    .stat-bar {
        height: 20px;
    }

    .owners-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }

    .similar-items-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
