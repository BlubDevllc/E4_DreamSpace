<!-- Registration Page -->

<div class="auth-container">
    <div class="auth-box">
        <div class="auth-header">
            <h1>Registreren</h1>
            <p>Maak een nieuw account aan</p>
        </div>

        <form method="POST" action="<?php echo BASE_URL; ?>controllers/auth/register.php" class="auth-form">
            <div class="form-group">
                <label for="username">Gebruikersnaam</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    placeholder="Kies een gebruikersnaam (3-50 tekens)"
                    required
                    autofocus
                >
                <small>Alleen letters, nummers, _ en - toegestaan</small>
            </div>

            <div class="form-group">
                <label for="email">E-mailadres</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="Voer je e-mailadres in"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Wachtwoord</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Sterke wachtwoord (min. 8 tekens)"
                    required
                >
                <small>Min. 8 tekens met HOOFD, klein, getal en speciaalteken (!@#$%^&*)</small>
            </div>

            <div class="form-group">
                <label for="password_confirm">Wachtwoord bevestigen</label>
                <input 
                    type="password" 
                    id="password_confirm" 
                    name="password_confirm" 
                    placeholder="Herhaal je wachtwoord"
                    required
                >
            </div>

            <div class="form-group">
                <label class="checkbox">
                    <input type="checkbox" name="agree" required>
                    <span>Ik accepteer de gebruiksvoorwaarden</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Account Aanmaken</button>
        </form>

        <div class="auth-footer">
            <p>Heb je al een account? <a href="<?php echo BASE_URL; ?>?page=login">Log hier in</a></p>
        </div>
    </div>

    <!-- Info Box -->
    <div class="auth-info">
        <h2>Begin Je Avontuur</h2>
        <p>Sluit je aan bij duizenden spelers in de DreamSpace!</p>
        
        <div class="features">
            <div class="feature">
                <span class="icon">🎮</span>
                <h3>Instant Toegang</h3>
                <p>Meteen na registratie klaar om te spelen</p>
            </div>
            <div class="feature">
                <span class="icon">🛡️</span>
                <h3>Veilig & Beveiligd</h3>
                <p>Bcrypt wachtwoord encryptie</p>
            </div>
            <div class="feature">
                <span class="icon">👥</span>
                <h3>Community</h3>
                <p>Handel en interactie met andere spelers</p>
            </div>
        </div>

        <div class="password-requirements">
            <h4>Wachtwoord Vereisten:</h4>
            <ul>
                <li>✓ Minimaal 8 tekens</li>
                <li>✓ Minstens 1 HOOFDLETTER</li>
                <li>✓ Minstens 1 kleine letter</li>
                <li>✓ Minstens 1 getal</li>
                <li>✓ Minstens 1 speciaal teken (!@#$%^&*)</li>
            </ul>
        </div>
    </div>
</div>

<style>
    .auth-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 200px);
        gap: 40px;
        padding: 40px 20px;
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    }

    .auth-box {
        background: white;
        border-radius: 8px;
        padding: 40px;
        width: 100%;
        max-width: 450px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    }

    .auth-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .auth-header h1 {
        font-size: 28px;
        margin-bottom: 10px;
        color: #333;
    }

    .auth-header p {
        color: #666;
    }

    .auth-form {
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #333;
    }

    .form-group small {
        display: block;
        margin-top: 5px;
        color: #999;
        font-size: 12px;
    }

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="password"] {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        box-sizing: border-box;
    }

    .form-group input:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
    }

    .checkbox {
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .checkbox input {
        margin-right: 8px;
    }

    .btn-block {
        width: 100%;
    }

    .auth-footer {
        text-align: center;
        font-size: 14px;
    }

    .auth-footer p {
        margin: 10px 0;
    }

    .auth-footer a {
        color: #007bff;
        text-decoration: none;
    }

    .auth-footer a:hover {
        text-decoration: underline;
    }

    .auth-info {
        color: white;
        width: 100%;
        max-width: 350px;
    }

    .auth-info h2 {
        font-size: 32px;
        margin-bottom: 15px;
    }

    .auth-info p {
        font-size: 16px;
        margin-bottom: 30px;
        opacity: 0.9;
    }

    .features {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-bottom: 30px;
    }

    .feature {
        padding: 15px;
        background: rgba(255,255,255,0.1);
        border-radius: 8px;
        border-left: 3px solid #28a745;
    }

    .feature .icon {
        font-size: 24px;
        display: block;
        margin-bottom: 8px;
    }

    .feature h3 {
        margin: 0 0 5px 0;
        font-size: 16px;
    }

    .feature p {
        margin: 0;
        font-size: 14px;
        opacity: 0.8;
    }

    .password-requirements {
        background: rgba(255,255,255,0.1);
        padding: 15px;
        border-radius: 8px;
        border-left: 3px solid #ffc107;
    }

    .password-requirements h4 {
        margin: 0 0 10px 0;
        font-size: 14px;
    }

    .password-requirements ul {
        margin: 0;
        padding-left: 20px;
        list-style: none;
    }

    .password-requirements li {
        font-size: 13px;
        margin-bottom: 5px;
        opacity: 0.9;
    }

    @media (max-width: 768px) {
        .auth-container {
            flex-direction: column;
        }

        .auth-info {
            display: none;
        }
    }
</style>
