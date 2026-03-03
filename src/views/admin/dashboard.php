<div class="admin-dashboard">
    <div class="admin-header">
        <h1>Admin Dashboard</h1>
        <p>Beheer het DreamSpace systeem</p>
    </div>

    <!-- Stats Overview -->
    <div class="admin-stats">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-content">
                <div class="stat-value">0</div>
                <div class="stat-label">Gebruikers</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-box"></i></div>
            <div class="stat-content">
                <div class="stat-value">0</div>
                <div class="stat-label">Items</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-sync"></i></div>
            <div class="stat-content">
                <div class="stat-value">0</div>
                <div class="stat-label">Ruilvoorstellen</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-bell"></i></div>
            <div class="stat-content">
                <div class="stat-value">0</div>
                <div class="stat-label">Meldingen</div>
            </div>
        </div>
    </div>

    <!-- Admin Tools -->
    <div class="admin-tools">
        <h2>Beheerstools</h2>
        <div class="tools-grid">
            <a href="<?php echo BASE_URL; ?>?page=admin-users" class="tool-card">
                <div class="tool-icon"><i class="fas fa-users"></i></div>
                <h3>Gebruikers Beheren</h3>
                <p>Beheer gebruikersaccounts, rollen en toestanden</p>
            </a>

            <a href="<?php echo BASE_URL; ?>?page=admin-items" class="tool-card">
                <div class="tool-icon"><i class="fas fa-box"></i></div>
                <h3>Items Beheren</h3>
                <p>Voeg items toe, bewerk of verwijder itemcatalogus</p>
            </a>

            <a href="<?php echo BASE_URL; ?>?page=admin-trades" class="tool-card">
                <div class="tool-icon"><i class="fas fa-sync"></i></div>
                <h3>Ruilvoorstellen</h3>
                <p>Monitor en beheer ruilactiviteiten</p>
            </a>

            <a href="<?php echo BASE_URL; ?>?page=admin-statistics" class="tool-card">
                <div class="tool-icon"><i class="fas fa-chart-bar"></i></div>
                <h3>Statistieken</h3>
                <p>Bekijk gedetailleerde analytics en rapporten</p>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="recent-activity">
        <h2>Recente Activiteiten</h2>
        <div class="activity-list">
            <div class="empty-state">
                <p>Geen recente activiteiten geregistreerd</p>
            </div>
        </div>
    </div>
</div>

<style>
.admin-dashboard {
    padding: 20px;
}

.admin-header {
    text-align: center;
    margin-bottom: 30px;
    padding: 30px 0;
    border-bottom: 2px solid #e0e0e0;
}

.admin-header h1 {
    margin: 0 0 10px;
    font-size: 2.5em;
}

.admin-header p {
    color: #666;
    font-size: 1.1em;
}

/* Stats */
.admin-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.stat-card {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.2s;
}

.stat-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    font-size: 2.5em;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 2em;
    font-weight: bold;
    color: #333;
}

.stat-label {
    color: #666;
    font-size: 0.95em;
}

/* Admin Tools */
.admin-tools h2 {
    margin-bottom: 20px;
    color: #333;
}

.tools-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.tool-card {
    background: white;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 25px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 15px;
}

.tool-card:hover {
    border-color: #667eea;
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.15);
    transform: translateY(-4px);
}

.tool-icon {
    font-size: 3em;
}

.tool-card h3 {
    margin: 0;
    color: #333;
}

.tool-card p {
    margin: 0;
    color: #666;
    font-size: 0.9em;
}

/* Recent Activity */
.recent-activity h2 {
    margin-bottom: 20px;
    color: #333;
}

.activity-list {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
}

.empty-state {
    text-align: center;
    padding: 40px;
    color: #999;
}

/* Responsive */
@media (max-width: 576px) {
    .admin-dashboard {
        padding: 10px;
    }

    .admin-header h1 {
        font-size: 1.8em;
    }

    .admin-stats {
        grid-template-columns: repeat(2, 1fr);
    }

    .stat-card {
        flex-direction: column;
        text-align: center;
    }

    .tools-grid {
        grid-template-columns: 1fr;
    }

    .tool-card {
        padding: 20px;
    }
}
</style>
