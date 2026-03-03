<div class="admin-statistics">
    <div class="admin-header">
        <h1>Statistieken & Analytics</h1>
        <p>Gedetailleerde systeemrapporten</p>
    </div>

    <!-- Stats Overview -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Totale Gebruikers</h3>
            <div class="stat-value">0</div>
            <small>Alle geregistreerde accounts</small>
        </div>
        <div class="stat-card">
            <h3>Actieve Items</h3>
            <div class="stat-value">0</div>
            <small>Items in inventarissen</small>
        </div>
        <div class="stat-card">
            <h3>Voltooide Ruilvoorstellen</h3>
            <div class="stat-value">0</div>
            <small>Succesvolle transacties</small>
        </div>
        <div class="stat-card">
            <h3>Systeembelasting</h3>
            <div class="stat-value">0%</div>
            <small>Database utilisation</small>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
        <h2>Grafieken & Trends</h2>
        <div class="charts-grid">
            <div class="chart-card">
                <h3>Gebruikers Over Tijd</h3>
                <div class="chart-placeholder">
                    <p>Grafiek wordt hier weergegeven</p>
                </div>
            </div>
            <div class="chart-card">
                <h3>Ruilactiviteit</h3>
                <div class="chart-placeholder">
                    <p>Grafiek wordt hier weergegeven</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Reports -->
    <div class="reports-section">
        <h2>Gedetailleerde Rapporten</h2>
        <div class="reports-list">
            <button class="report-btn"><i class="fas fa-file-pdf"></i> PDF Exporteren</button>
            <button class="report-btn"><i class="fas fa-chart-line"></i> CSV Exporteren</button>
        </div>
    </div>
</div>

<style>
.admin-statistics {
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
    font-size: 2em;
}

/* Stats Grid */
.stats-grid {
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
}

.stat-card h3 {
    margin: 0 0 10px;
    color: #333;
    font-size: 0.95em;
}

.stat-value {
    font-size: 2.5em;
    font-weight: bold;
    color: #667eea;
    margin: 10px 0;
}

.stat-card small {
    color: #999;
}

/* Charts */
.charts-section h2 {
    margin-bottom: 20px;
    color: #333;
}

.charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.chart-card {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
}

.chart-card h3 {
    margin-top: 0;
    margin-bottom: 20px;
    color: #333;
}

.chart-placeholder {
    height: 300px;
    background: #f8f9fa;
    border: 2px dashed #ddd;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
}

/* Reports */
.reports-section h2 {
    margin-bottom: 20px;
    color: #333;
}

.reports-list {
    display: flex;
    gap: 10px;
}

.report-btn {
    padding: 10px 20px;
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
}

.report-btn:hover {
    background: #f8f9fa;
    border-color: #667eea;
}

/* Responsive */
@media (max-width: 576px) {
    .admin-statistics {
        padding: 10px;
    }

    .charts-grid {
        grid-template-columns: 1fr;
    }

    .reports-list {
        flex-direction: column;
    }

    .report-btn {
        width: 100%;
    }
}
</style>
