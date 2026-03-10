<!-- Home / Dashboard Page -->

<div class="dashboard-hero">
    <div class="hero-content">
        <h1>Welkom in DreamSpace, <?php echo getCurrentUsername(); ?>! <i class="fas fa-wave-hand"></i></h1>
        <p>Beheer je inventaris, handel items en bouw je legendaire collectie</p>
    </div>
</div>

<div class="dashboard-container">
    <!-- Quick Stats -->
    <section class="dashboard-stats">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-box"></i></div>
            <div class="stat-info">
                <h3>Inventaris</h3>
                <p class="stat-number"><?php echo $inventory_stats['total_items'] ?? 0; ?> items</p>
                <a href="<?php echo BASE_URL; ?>?page=inventory" class="stat-link">Bekijk →</a>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-handshake"></i></div>
            <div class="stat-info">
                <h3>Actieve Trades</h3>
                <p class="stat-number"><?php echo $trades_stats['active_trades'] ?? 0; ?></p>
                <a href="<?php echo BASE_URL; ?>?page=trades" class="stat-link">Beheer →</a>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-bell"></i></div>
            <div class="stat-info">
                <h3>Notificaties</h3>
                <p class="stat-number"><?php echo $notif_stats['new_notifications'] ?? 0; ?> nieuw</p>
                <a href="<?php echo BASE_URL; ?>?page=notifications" class="stat-link">Lezen →</a>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-star"></i></div>
            <div class="stat-info">
                <h3>Best Items</h3>
                <p class="stat-number"><?php echo $legendary_stats['legendary_items'] ?? 0; ?> Legendarisch</p>
                <a href="<?php echo BASE_URL; ?>?page=inventory" class="stat-link">Tonen →</a>
            </div>
        </div>
    </section>

    <!-- Main Content Grid -->
    <div class="dashboard-grid">
        <!-- Recent Items -->
        <section class="widget">
            <div class="widget-header">
                <h2>Recente Items</h2>
                <a href="<?php echo BASE_URL; ?>?page=inventory" class="btn-small">Meer</a>
            </div>
            <div class="items-list">
                <?php if (!empty($recent_items)): ?>
                    <?php foreach ($recent_items as $item): ?>
                        <div class="item-row">
                            <div class="item-thumb">
                                <?php if ($item['Type'] === 'Wapen'): ?>
                                    <i class="fas fa-sword"></i>
                                <?php elseif ($item['Type'] === 'Armor'): ?>
                                    <i class="fas fa-shield"></i>
                                <?php else: ?>
                                    <i class="fas fa-ring"></i>
                                <?php endif; ?>
                            </div>
                            <div class="item-info">
                                <h4><?php echo htmlspecialchars($item['Naam']); ?></h4>
                                <p><?php echo htmlspecialchars($item['Zeldzaamheid']); ?> <?php echo htmlspecialchars($item['Type']); ?> • 
                                <?php if ($item['Kracht'] > 0): ?>Sterkte: <?php echo $item['Kracht']; ?><?php endif; ?>
                                <?php if ($item['Snelheid'] > 0): ?>Snelheid: <?php echo $item['Snelheid']; ?><?php endif; ?>
                                <?php if ($item['Duurzaamheid'] > 0): ?>Duurzaamheid: <?php echo $item['Duurzaamheid']; ?><?php endif; ?></p>
                            </div>
                            <div class="item-action">
                                <a href="<?php echo BASE_URL; ?>?page=item-detail&id=<?php echo $item['ItemID']; ?>" class="btn-small">Details</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center; color: #999; padding: 20px;">Geen items in inventaris</p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Recent Trades -->
        <section class="widget">
            <div class="widget-header">
                <h2>Handel Activiteit</h2>
                <a href="<?php echo BASE_URL; ?>?page=trades" class="btn-small">Meer</a>
            </div>
            <div class="trades-list">
                <?php if (!empty($recent_trades)): ?>
                    <?php foreach ($recent_trades as $trade): ?>
                        <div class="trade-row <?php echo strtolower($trade['Status']); ?>">
                            <div class="trade-status">
                                <?php if ($trade['Status'] === 'In Afwachting'): ?>
                                    <i class="fas fa-hourglass-end"></i>
                                <?php else: ?>
                                    <i class="fas fa-check"></i>
                                <?php endif; ?>
                            </div>
                            <div class="trade-info">
                                <h4>Trade <?php echo $trade['Status'] === 'In Afwachting' ? 'met' : 'voltooid met'; ?> <?php echo htmlspecialchars($trade['creator_name'] ?? $trade['recipient_name']); ?></h4>
                                <p><?php echo htmlspecialchars($trade['item1_name'] ?? 'Item'); ?> ↔ <?php echo htmlspecialchars($trade['item2_name'] ?? 'Item'); ?> • <?php 
                                if ($trade['Status'] === 'In Afwachting') echo 'In afwachting'; 
                                else echo 'Voltooid'; ?></p>
                            </div>
                            <?php if ($trade['Status'] === 'In Afwachting'): ?>
                                <div class="trade-action">
                                    <a href="<?php echo BASE_URL; ?>?page=trades" class="btn-small">Beheer</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center; color: #999; padding: 20px;">Geen ruilvoorstellen</p>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <!-- Action Buttons -->
    <section class="dashboard-actions">
        <h2>Wat wil je doen?</h2>
        <div class="action-grid">
            <a href="<?php echo BASE_URL; ?>?page=items" class="action-card">
                <span class="action-icon"><i class="fas fa-magnifying-glass"></i></span>
                <h3>Items Ontdekken</h3>
                <p>Blader door onze complete itemcatalogus</p>
            </a>
            <a href="<?php echo BASE_URL; ?>?page=inventory" class="action-card">
                <span class="action-icon"><i class="fas fa-box"></i></span>
                <h3>Mijn Inventaris</h3>
                <p>Beheer je eigen verzameling</p>
            </a>
            <a href="<?php echo BASE_URL; ?>?page=trades" class="action-card">
                <span class="action-icon"><i class="fas fa-handshake"></i></span>
                <h3>Nieuw Trade</h3>
                <p>Zend een handelsvoorstel</p>
            </a>
            <a href="<?php echo BASE_URL; ?>?page=profile" class="action-card">
                <span class="action-icon"><i class="fas fa-user"></i></span>
                <h3>Mijn Profiel</h3>
                <p>Wijzig je accountgegevens</p>
            </a>
        </div>
    </section>
</div>

<style>
    .dashboard-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 60px 20px;
        text-align: center;
        margin-bottom: 40px;
    }

    .dashboard-hero h1 {
        font-size: 36px;
        margin-bottom: 10px;
    }

    .dashboard-hero p {
        font-size: 18px;
        opacity: 0.9;
    }

    .dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Stats Section */
    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        display: flex;
        gap: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }

    .stat-icon {
        font-size: 32px;
        min-width: 50px;
        text-align: center;
    }

    .stat-info h3 {
        margin: 0 0 5px 0;
        color: #666;
        font-size: 14px;
        font-weight: 500;
    }

    .stat-number {
        margin: 0 0 10px 0;
        font-size: 28px;
        font-weight: bold;
        color: #333;
    }

    .stat-link {
        color: #667eea;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
    }

    .stat-link:hover {
        text-decoration: underline;
    }

    /* Dashboard Grid */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    /* Widget */
    .widget {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .widget-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }

    .widget-header h2 {
        margin: 0;
        font-size: 20px;
    }

    /* Items List */
    .items-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .item-row {
        display: flex;
        gap: 15px;
        align-items: center;
        padding: 15px;
        background: #f9f9f9;
        border-radius: 6px;
        border-left: 3px solid #667eea;
    }

    .item-thumb {
        font-size: 32px;
        min-width: 50px;
        text-align: center;
    }

    .item-info {
        flex: 1;
    }

    .item-info h4 {
        margin: 0 0 5px 0;
        color: #333;
    }

    .item-info p {
        margin: 0;
        font-size: 13px;
        color: #999;
    }

    .item-action {
        text-align: right;
    }

    /* Trades List */
    .trades-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .trade-row {
        display: flex;
        gap: 15px;
        align-items: center;
        padding: 15px;
        background: #f9f9f9;
        border-radius: 6px;
        border-left: 3px solid #ffc107;
    }

    .trade-row.completed {
        border-left-color: #28a745;
    }

    .trade-status {
        font-size: 24px;
        min-width: 40px;
        text-align: center;
    }

    .trade-info {
        flex: 1;
    }

    .trade-info h4 {
        margin: 0 0 5px 0;
        color: #333;
    }

    .trade-info p {
        margin: 0;
        font-size: 13px;
        color: #999;
    }

    .trade-action {
        text-align: right;
    }

    /* Button Styles */
    .btn-small {
        display: inline-block;
        padding: 8px 16px;
        background: #667eea;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
    }

    .btn-small:hover {
        background: #5568d3;
    }

    /* Actions Section */
    .dashboard-actions {
        margin-top: 40px;
    }

    .dashboard-actions h2 {
        text-align: center;
        font-size: 24px;
        margin-bottom: 30px;
    }

    .action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .action-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 8px;
        text-align: center;
        text-decoration: none;
        transition: transform 0.3s, box-shadow 0.3s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .action-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    .action-icon {
        font-size: 48px;
        display: block;
        margin-bottom: 15px;
    }

    .action-card h3 {
        margin: 0 0 10px 0;
        font-size: 18px;
    }

    .action-card p {
        margin: 0;
        font-size: 14px;
        opacity: 0.9;
    }

    @media (max-width: 768px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-hero h1 {
            font-size: 24px;
        }
    }
</style>
