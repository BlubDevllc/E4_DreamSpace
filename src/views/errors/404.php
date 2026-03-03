<!-- 404 Error Page -->

<div class="error-container">
    <div class="error-content">
        <div class="error-code">404</div>
        <h1>Pagina Niet Gevonden</h1>
        <p>Sorry! De pagina die je zoekt bestaat niet.</p>

        <div class="error-suggestions">
            <h3>Wat wil je doen?</h3>
            <ul>
                <li><a href="<?php echo BASE_URL; ?>?page=home">↤ Terug naar Home</a></li>
                <li><a href="<?php echo BASE_URL; ?>?page=items">🔍 Items Ontdekken</a></li>
                <?php if (isLoggedIn()): ?>
                    <li><a href="<?php echo BASE_URL; ?>?page=inventory">📦 Mijn Inventaris</a></li>
                    <li><a href="<?php echo BASE_URL; ?>?page=profile">👤 Mijn Profiel</a></li>
                <?php else: ?>
                    <li><a href="<?php echo BASE_URL; ?>?page=login">🔐 Inloggen</a></li>
                    <li><a href="<?php echo BASE_URL; ?>?page=register">✍️ Registreren</a></li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="error-contact">
            <p>Vind je het probleem niet? <a href="mailto:support@dreamspace.game">Neem contact op</a></p>
        </div>
    </div>
</div>

<style>
    .error-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 200px);
        padding: 40px 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .error-content {
        background: white;
        border-radius: 12px;
        padding: 60px 40px;
        text-align: center;
        max-width: 600px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }

    .error-code {
        font-size: 120px;
        font-weight: bold;
        color: #667eea;
        line-height: 1;
        margin-bottom: 20px;
        opacity: 0.8;
    }

    .error-content h1 {
        font-size: 32px;
        color: #333;
        margin-bottom: 10px;
    }

    .error-content > p {
        font-size: 16px;
        color: #666;
        margin-bottom: 40px;
    }

    .error-suggestions {
        text-align: left;
        background: #f8f9fa;
        padding: 30px;
        border-radius: 8px;
        margin-bottom: 30px;
        border-left: 4px solid #667eea;
    }

    .error-suggestions h3 {
        margin: 0 0 20px 0;
        color: #333;
        font-size: 18px;
    }

    .error-suggestions ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .error-suggestions li {
        margin: 0;
    }

    .error-suggestions a {
        display: inline-block;
        padding: 12px 20px;
        background: #667eea;
        color: white;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.3s;
        width: 100%;
        text-align: left;
    }

    .error-suggestions a:hover {
        background: #5568d3;
        transform: translateX(5px);
        text-decoration: none;
    }

    .error-contact {
        font-size: 14px;
        color: #999;
    }

    .error-contact a {
        color: #667eea;
        text-decoration: none;
    }

    .error-contact a:hover {
        text-decoration: underline;
    }

    @media (max-width: 600px) {
        .error-content {
            padding: 40px 20px;
        }

        .error-code {
            font-size: 80px;
        }

        .error-content h1 {
            font-size: 24px;
        }

        .error-suggestions {
            padding: 20px;
        }

        .error-suggestions a {
            padding: 10px 15px;
            font-size: 14px;
        }
    }
</style>
