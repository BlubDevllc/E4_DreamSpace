<div class="trades-page">
    <div class="trades-header">
        <h1>Ruilvoorstellen</h1>
        <p>Beheer je handelsactiviteiten</p>
    </div>

    <!-- Tabs -->
    <div class="trades-tabs">
        <button class="tab-btn active" data-tab="active">Actief</button>
        <button class="tab-btn" data-tab="history">Geschiedenis</button>
        <button class="tab-btn" data-tab="pending">In afwachting</button>
    </div>

    <!-- Active Trades -->
    <div class="tab-content active" data-tab="active">
        <div class="trades-list">
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-sync"></i></div>
                <h2>Geen actieve ruilvoorstellen</h2>
                <p>Je hebt momenteel geen actieve ruilvoorstellen. 
                   <a href="<?php echo BASE_URL; ?>?page=items">Verken items</a> om te handelen met andere spelers</p>
            </div>
        </div>
    </div>

    <!-- Trade History -->
    <div class="tab-content" data-tab="history">
        <div class="trades-list">
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-scroll"></i></div>
                <h2>Geen handelsgeschiedenis</h2>
                <p>Je hebt nog geen ruilvoorstellen voltooid</p>
            </div>
        </div>
    </div>

    <!-- Pending -->
    <div class="tab-content" data-tab="pending">
        <div class="trades-list">
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-hourglass-end"></i></div>
                <h2>Geen in afwachting</h2>
                <p>Je hebt geen ruilvoorstellen in afwachting van reactie</p>
            </div>
        </div>
    </div>

    <!-- Example Trade (for development) -->
    <div class="trade-example" style="display: none;">
        <div class="trade-card">
            <div class="trade-header">
                <h3>Ruilvoorstel #1001</h3>
                <span class="trade-status status-open">Open</span>
            </div>
            
            <div class="trade-participants">
                <div class="participant">
                    <h4>Jij</h4>
                    <div class="items-offered">
                        <div class="item-chip">Legende Zwaard</div>
                    </div>
                </div>
                
                <div class="trade-arrows"><i class="fas fa-arrow-right-arrow-left"></i></div>
                
                <div class="participant">
                    <h4>Player123</h4>
                    <div class="items-offered">
                        <div class="item-chip">Mystieke Ring</div>
                    </div>
                </div>
            </div>
            
            <div class="trade-actions">
                <button class="btn btn-primary">Details</button>
                <button class="btn btn-danger">Annuleren</button>
            </div>
        </div>
    </div>
</div>

<style>
.trades-page {
    padding: 20px;
}

.trades-header {
    text-align: center;
    margin-bottom: 30px;
    padding: 30px 0;
    border-bottom: 2px solid #e0e0e0;
}

.trades-header h1 {
    margin: 0 0 10px;
    font-size: 2.5em;
}

.trades-header p {
    color: #666;
    font-size: 1.1em;
}

/* Tabs */
.trades-tabs {
    display: flex;
    gap: 0;
    margin-bottom: 30px;
    border-bottom: 2px solid #e0e0e0;
}

.tab-btn {
    padding: 15px 20px;
    background: none;
    border: none;
    border-bottom: 3px solid transparent;
    cursor: pointer;
    font-weight: 500;
    color: #666;
    transition: all 0.2s;
    font-size: 1em;
}

.tab-btn:hover {
    color: #333;
}

.tab-btn.active {
    color: #667eea;
    border-bottom-color: #667eea;
}

/* Tab Content */
.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* Trades List */
.trades-list {
    max-width: 800px;
    margin: 0 auto;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 2px dashed #ddd;
}

.empty-icon {
    font-size: 4em;
    margin-bottom: 20px;
}

.empty-state h2 {
    margin: 0 0 10px;
    color: #333;
}

.empty-state p {
    margin: 0;
    color: #666;
    line-height: 1.6;
}

.empty-state a {
    color: #667eea;
    text-decoration: none;
    font-weight: bold;
}

.empty-state a:hover {
    text-decoration: underline;
}

/* Trade Card */
.trade-card {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    transition: all 0.2s;
}

.trade-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.trade-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e0e0e0;
}

.trade-header h3 {
    margin: 0;
    color: #333;
}

.trade-status {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85em;
    font-weight: bold;
}

.status-open {
    background: #d4edda;
    color: #155724;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-completed {
    background: #d1ecf1;
    color: #0c5460;
}

.status-cancelled {
    background: #f8d7da;
    color: #721c24;
}

/* Trade Participants */
.trade-participants {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    gap: 20px;
    align-items: center;
    margin-bottom: 20px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 6px;
}

.participant h4 {
    margin: 0 0 10px;
    color: #333;
}

.items-offered {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.item-chip {
    background: white;
    border: 1px solid #ddd;
    padding: 8px 12px;
    border-radius: 4px;
    font-size: 0.9em;
    color: #333;
}

.trade-arrows {
    text-align: center;
    font-size: 1.8em;
    color: #667eea;
}

/* Trade Actions */
.trade-actions {
    display: flex;
    gap: 10px;
}

.trade-actions .btn {
    flex: 1;
}

/* Responsive */
@media (max-width: 992px) {
    .trade-participants {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .trade-arrows {
        transform: rotate(90deg);
    }
}

@media (max-width: 576px) {
    .trades-page {
        padding: 10px;
    }

    .trades-header h1 {
        font-size: 1.8em;
    }

    .trades-tabs {
        flex-wrap: wrap;
    }

    .tab-btn {
        flex: 1;
    }

    .trade-card {
        padding: 15px;
    }

    .trade-actions {
        flex-direction: column;
    }
}
</style>

<script>
// Tab switching
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const tab = this.dataset.tab;
        
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });
        
        // Remove active from all buttons
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('active');
        });
        
        // Show selected tab
        document.querySelector(`[data-tab="${tab}"]`).classList.add('active');
        this.classList.add('active');
    });
});
</script>
