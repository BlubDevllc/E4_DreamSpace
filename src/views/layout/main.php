<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="DreamSpace - Inventory Management System for Gamers">
    
    <title><?php echo APP_NAME . ' - Inventory Management'; ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/main.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/layout.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/responsive.css">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>assets/images/favicon.ico">
</head>
<body>
    <!-- Header/Navigation -->
    <header class="navbar">
        <nav class="navbar-container">
            <div class="navbar-brand">
                <a href="<?php echo BASE_URL; ?>?page=home">
                    <span class="brand-icon"><i class="fas fa-star"></i></span>
                    <?php echo APP_NAME; ?>
                </a>
            </div>

            <ul class="navbar-menu">
                <?php if (!isLoggedIn()): ?>
                    <!-- Guest Navigation -->
                    <li><a href="<?php echo BASE_URL; ?>?page=login" class="nav-link">Inloggen</a></li>
                    <li><a href="<?php echo BASE_URL; ?>?page=register" class="nav-link btn-primary">Registreren</a></li>
                
                <?php else: ?>
                    <!-- Logged In Navigation -->
                    <li><a href="<?php echo BASE_URL; ?>?page=items" class="nav-link"><i class="fas fa-box"></i> Items</a></li>
                    <li><a href="<?php echo BASE_URL; ?>?page=inventory" class="nav-link"><i class="fas fa-backpack"></i> Inventaris</a></li>
                    <li><a href="<?php echo BASE_URL; ?>?page=trades" class="nav-link"><i class="fas fa-sync"></i> Handel</a></li>
                    <li><a href="<?php echo BASE_URL; ?>?page=notifications" class="nav-link notification-bell">
                        <i class="fas fa-bell"></i> <span class="badge">3</span>
                    </a></li>

                    <?php if (isAdmin()): ?>
                        <!-- Admin Menu -->
                        <li class="dropdown">
                            <a href="#" class="nav-link dropdown-toggle"><i class="fas fa-cog"></i> Admin</a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo BASE_URL; ?>?page=admin">Dashboard</a></li>
                                <li><a href="<?php echo BASE_URL; ?>?page=admin-users">Gebruikers</a></li>
                                <li><a href="<?php echo BASE_URL; ?>?page=admin-items"><i class="fas fa-box"></i> Items</a></li>
                                <li><a href="<?php echo BASE_URL; ?>?page=admin-statistics">Statistieken</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <!-- User Menu -->
                    <li class="dropdown">
                        <a href="#" class="nav-link dropdown-toggle"><i class="fas fa-user"></i> <?php echo getCurrentUsername(); ?></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo BASE_URL; ?>?page=profile">Profiel</a></li>
                            <li><hr></li>
                            <li><a href="<?php echo BASE_URL; ?>?page=logout">Uitloggen</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- Mobile Menu Toggle -->
            <button class="navbar-toggle"><i class="fas fa-bars"></i></button>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <?php echo $content ?? ''; ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h4><?php echo APP_NAME; ?></h4>
                <p>Professional Inventory Management System</p>
            </div>
            <div class="footer-section">
                <h4>Links</h4>
                <ul>
                    <li><a href="<?php echo BASE_URL; ?>?page=home">Home</a></li>
                    <li><a href="<?php echo BASE_URL; ?>?page=items"><i class="fas fa-box"></i> Items</a></li>
                    <li><a href="<?php echo BASE_URL; ?>?page=trades">Handel</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Informatie</h4>
                <ul>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 <?php echo APP_NAME; ?> - DreamScape Interactive. All rights reserved.</p>
            <p>Project v<?php echo APP_VERSION; ?></p>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="<?php echo BASE_URL; ?>assets/js/main.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/interaction.js"></script>
</body>
</html>
