<div class="notifications-page">
    <div class="notifications-header">
        <h1>Meldingen</h1>
        <p>Je berichten en activiteiten</p>
    </div>

    <!-- Filters -->
    <div class="notifications-filter">
        <button class="filter-btn active" data-filter="all">Alle (0)</button>
        <button class="filter-btn" data-filter="trades">Ruilvoorstellen (0)</button>
        <button class="filter-btn" data-filter="inventory">Inventaris (0)</button>
        <button class="filter-btn" data-filter="system">Systeem (0)</button>
    </div>

    <!-- Notifications List -->
    <div class="notifications-list">
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-bell"></i></div>
            <h2>Geen meldingen</h2>
            <p>Je hebt momenteel geen ongelezen meldingen. Wanneer andere spelers contact maken of je inventaris verandert, zie je dat hier</p>
        </div>
    </div>

    <!-- Example Notification (for development) -->
    <div class="notification-example" style="display: none;">
        <div class="notification-item notification-unread">
            <div class="notification-content">
                <h3>Ruilvoorstel ontvangen</h3>
                <p>Player123 wil je Legende Zwaard ruilen voor Mystieke Ring</p>
                <small>5 minuten geleden</small>
            </div>
            <div class="notification-actions">
                <button class="btn btn-primary btn-sm">Bekijken</button>
                <button class="btn btn-secondary btn-sm">Sluiten</button>
            </div>
        </div>
    </div>
</div>

<style>
.notifications-page {
    padding: 20px;
}

.notifications-header {
    text-align: center;
    margin-bottom: 30px;
    padding: 30px 0;
    border-bottom: 2px solid #e0e0e0;
}

.notifications-header h1 {
    margin: 0 0 10px;
    font-size: 2.5em;
}

.notifications-header p {
    color: #666;
    font-size: 1.1em;
}

/* Filters */
.notifications-filter {
    display: flex;
    gap: 10px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.filter-btn {
    padding: 10px 16px;
    border: 2px solid #ddd;
    background: white;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.2s;
    font-weight: 500;
}

.filter-btn:hover {
    border-color: #667eea;
}

.filter-btn.active {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

/* Notifications List */
.notifications-list {
    max-width: 700px;
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

/* Notification Item */
.notification-item {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    transition: all 0.2s;
}

.notification-item:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.notification-unread {
    background: linear-gradient(135deg, #f0f4ff 0%, #e8f0ff 100%);
    border-left: 4px solid #667eea;
}

.notification-content h3 {
    margin: 0 0 8px;
    color: #333;
    font-size: 1.1em;
}

.notification-content p {
    margin: 0 0 8px;
    color: #666;
    font-size: 0.95em;
}

.notification-content small {
    color: #999;
    font-size: 0.85em;
}

.notification-actions {
    display: flex;
    gap: 8px;
    flex-shrink: 0;
}

.notification-actions .btn-sm {
    padding: 6px 12px;
    font-size: 0.85em;
}

/* Responsive */
@media (max-width: 576px) {
    .notifications-page {
        padding: 10px;
    }

    .notifications-header h1 {
        font-size: 1.8em;
    }

    .notifications-filter {
        flex-direction: column;
    }

    .filter-btn {
        width: 100%;
    }

    .notification-item {
        flex-direction: column;
        align-items: stretch;
    }

    .notification-actions {
        flex-direction: column;
    }

    .notification-actions .btn-sm {
        width: 100%;
    }
}
</style>

<script>
// Filter functionality
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const filter = this.dataset.filter;
        // TODO: Filter notifications based on type
        console.log('Filtering by:', filter);
    });
});
</script>
