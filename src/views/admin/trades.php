<div class="admin-trades">
    <div class="admin-header">
        <h1>Ruilvoorstellen Beheren</h1>
        <p>Monitor en beheer alle ruilactiviteiten</p>
    </div>

    <div class="admin-filters">
        <select class="form-control" style="max-width: 200px;">
            <option>Alle Statussen</option>
            <option>Open</option>
            <option>Voltooid</option>
            <option>Geannuleerd</option>
        </select>
    </div>

    <div class="admin-table">
        <table class="table">
            <thead>
                <tr>
                    <th>Trade ID</th>
                    <th>Verzender</th>
                    <th>Ontvanger</th>
                    <th>Items</th>
                    <th>Status</th>
                    <th>Datum</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: #999;">
                        Geen ruilvoorstellen gevonden
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<style>
.admin-trades {
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

.admin-filters {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.admin-table {
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

.table th {
    background: #f8f9fa;
    padding: 15px;
    font-weight: bold;
    border-bottom: 2px solid #e0e0e0;
}

.table td {
    padding: 15px;
    border-bottom: 1px solid #e0e0e0;
}
</style>
