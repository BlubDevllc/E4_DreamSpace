<div class="profile-page">
    <div class="profile-header">
        <h1>Mijn Profiel</h1>
        <p>Beheer je accountgegevens</p>
    </div>

    <div class="profile-container">
        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-avatar">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['username'] ?? 'User'); ?>&background=667eea&color=fff&size=128" 
                     alt="<?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>">
            </div>

            <div class="profile-info">
                <h2><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></h2>
                <p class="profile-role">
                    <span class="role-badge">
                        <?php 
                        $role = $_SESSION['role'] ?? 'Speler';
                        echo $role === 'Beheerder' ? '<i class="fas fa-crown"></i> Beheerder' : '<i class="fas fa-sword"></i> Speler';
                        ?>
                    </span>
                </p>
            </div>

            <div class="profile-stats">
                <div class="stat">
                    <div class="stat-value">0</div>
                    <div class="stat-label">Items</div>
                </div>
                <div class="stat">
                    <div class="stat-value">0</div>
                    <div class="stat-label">Ruilvoorstellen</div>
                </div>
                <div class="stat">
                    <div class="stat-value">0</div>
                    <div class="stat-label">Meldingen</div>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="profile-editor">
            <h3>Account Instellingen</h3>
            
            <form method="POST" class="form-group">
                <label>Gebruikersnaam</label>
                <input type="text" 
                       value="<?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>" 
                       disabled 
                       class="form-control">
                <small>Gebruikersnaam kan niet worden gewijzigd</small>
            </form>

            <form method="POST" class="form-group">
                <label>E-mailadres</label>
                <input type="email" 
                       name="email" 
                       placeholder="Voer je e-mailadres in" 
                       class="form-control"
                       required>
                <button type="submit" class="btn btn-primary">E-mail bijwerken</button>
            </form>

            <form method="POST" class="form-group">
                <label>Wachtwoord wijzigen</label>
                <input type="password" 
                       name="current_password" 
                       placeholder="Huidig wachtwoord" 
                       class="form-control"
                       required>
                <input type="password" 
                       name="new_password" 
                       placeholder="Nieuw wachtwoord" 
                       class="form-control"
                       required>
                <input type="password" 
                       name="confirm_password" 
                       placeholder="Bevestig wachtwoord" 
                       class="form-control"
                       required>
                <button type="submit" class="btn btn-primary">Wachtwoord wijzigen</button>
            </form>

            <div class="form-group danger-zone">
                <h4>Gevarenzone</h4>
                <form method="POST">
                    <input type="hidden" name="action" value="delete_account">
                    <p>Het verwijderen van je account kan niet ongedaan worden.</p>
                    <button type="submit" 
                            class="btn btn-danger" 
                            onclick="return confirm('Weet je zeker dat je je account wilt verwijderen? Dit kan niet ongedaan worden.')">
                        Account verwijderen
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.profile-page {
    padding: 20px;
}

.profile-header {
    text-align: center;
    margin-bottom: 30px;
    padding: 30px 0;
    border-bottom: 2px solid #e0e0e0;
}

.profile-header h1 {
    margin: 0 0 10px;
    font-size: 2.5em;
}

.profile-header p {
    color: #666;
    font-size: 1.1em;
}

.profile-container {
    max-width: 800px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 30px;
}

/* Profile Card */
.profile-card {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 30px;
    height: fit-content;
    position: sticky;
    top: 100px;
}

.profile-avatar {
    text-align: center;
    margin-bottom: 20px;
}

.profile-avatar img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    border: 3px solid #667eea;
}

.profile-info {
    text-align: center;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e0e0e0;
}

.profile-info h2 {
    margin: 0 0 10px;
    color: #333;
}

.profile-role {
    margin: 0;
}

.role-badge {
    display: inline-block;
    padding: 6px 12px;
    background: #e8f0ff;
    color: #667eea;
    border-radius: 20px;
    font-size: 0.85em;
    font-weight: bold;
}

/* Profile Stats */
.profile-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}

.stat {
    text-align: center;
}

.stat-value {
    font-size: 1.8em;
    font-weight: bold;
    color: #333;
}

.stat-label {
    font-size: 0.75em;
    color: #666;
    margin-top: 5px;
}

/* Profile Editor */
.profile-editor {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 30px;
}

.profile-editor h3 {
    margin-top: 0;
    margin-bottom: 30px;
    color: #333;
}

.form-group {
    margin-bottom: 25px;
    display: flex;
    flex-direction: column;
}

.form-group label {
    margin-bottom: 8px;
    font-weight: bold;
    color: #333;
}

.form-group .form-control {
    padding: 10px;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    font-size: 1em;
    margin-bottom: 8px;
}

.form-group button {
    align-self: flex-start;
}

.form-group small {
    color: #999;
    font-size: 0.85em;
}

.danger-zone {
    background: #fff3f3;
    border: 2px solid #f8d7da;
    padding: 20px;
    border-radius: 6px;
    margin-top: 30px;
}

.danger-zone h4 {
    margin-top: 0;
    color: #721c24;
}

.danger-zone p {
    color: #721c24;
    margin-bottom: 15px;
}

/* Responsive */
@media (max-width: 992px) {
    .profile-container {
        grid-template-columns: 1fr;
    }

    .profile-card {
        position: relative;
        top: 0;
    }

    .profile-stats {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 576px) {
    .profile-page {
        padding: 10px;
    }

    .profile-header h1 {
        font-size: 1.8em;
    }

    .profile-editor,
    .profile-card {
        padding: 15px;
    }

    .profile-avatar img {
        width: 80px;
        height: 80px;
    }

    .profile-stats {
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }
}
</style>
