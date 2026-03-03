<div class="admin-users">
    <div class="admin-header">
        <h1>Gebruikers Beheren</h1>
        <p>Beheer gebruikersaccounts en toestanden</p>
    </div>

    <div class="admin-actions">
        <button class="btn btn-primary">Nieuwe Gebruiker</button>
        <input type="text" placeholder="Zoeken..." class="form-control" style="max-width: 300px;">
    </div>

    <div class="admin-table">
        <table class="table">
            <thead>
                <tr>
                    <th>Gebruikersnaam</th>
                    <th>E-mail</th>
                    <th>Rol</th>
                    <th>Status</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px; color: #999;">
                        Geen gebruikers gevonden
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<style>
.admin-users {
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

.admin-actions {
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
