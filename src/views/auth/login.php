<!-- Login Page -->

<div class="auth-container">
    <div class="auth-box">
        <div class="auth-header">
            <h1>Inloggen</h1>
            <p>Welkom terug bij <?php echo APP_NAME; ?></p>
        </div>

        <form method="POST" action="<?php echo BASE_URL; ?>controllers/auth/login.php" class="auth-form">
            <div class="form-group">
                <label for="username">Gebruikersnaam</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    placeholder="Voer je gebruikersnaam in"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password">Wachtwoord</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Voer je wachtwoord in"
                    required
                >
            </div>

            <div class="form-group">
                <label class="checkbox">
                    <input type="checkbox" name="remember" >
                    <span>Onthoud mij</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Inloggen</button>
        </form>

        <div class="auth-footer">
            <p>Heb je nog geen account? <a href="<?php echo BASE_URL; ?>?page=register">Registreer hier</a></p>
            <p><a href="#">Wachtwoord vergeten?</a></p>
        </div>
    </div>

    <!-- Info Box -->
    <div class="auth-info">
        <h2>DreamSpace Inventory System</h2>
        <p>Beheer je virtuele items, handel met andere spelers, en bouw je droomcollectie op!</p>
        
        <div class="features">
            <div class="feature">
                <span class="icon">📦</span>
                <h3>Inventarisbeheer</h3>
                <p>Organiseer je verzameling items</p>
            </div>
            <div class="feature">
                <span class="icon">🤝</span>
                <h3>Handel Systeem</h3>
                <p>Ruil items met andere spelers</p>
            </div>
            <div class="feature">
                <span class="icon">⚔️</span>
                <h3>Unieke Items</h3>
                <p>Verzamel legendaire equipement</p>
            </div>
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
        max-width: 400px;
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

    .form-group input[type="text"],
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
        max-width: 400px;
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
    }

    .feature {
        padding: 15px;
        background: rgba(255,255,255,0.1);
        border-radius: 8px;
        border-left: 3px solid #007bff;
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

    @media (max-width: 768px) {
        .auth-container {
            flex-direction: column;
        }

        .auth-info {
            display: none;
        }
    }
</style>
