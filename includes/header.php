<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

// Calculate base path for assets and links
$base_path = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../' : '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crochet art by Orly | Futuristic Fashion</title>
    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/style.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@300;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <nav class="nav-container">
            <a href="<?php echo $base_path; ?>index.php" class="logo">
                <span class="logo-text">ORLY</span><span class="logo-sub">CROCHET</span>
            </a>
            <ul class="nav-menu">
                <li><a href="<?php echo $base_path; ?>index.php">Accueil</a></li>
                <li class="dropdown">
                    <a href="#">Femme <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-content">
                        <li><a href="<?php echo $base_path; ?>products.php?gender=Femme&category=Top">Top</a></li>
                        <li><a href="<?php echo $base_path; ?>products.php?gender=Femme&category=Robe">Robe</a></li>
                        <li><a href="<?php echo $base_path; ?>products.php?gender=Femme&category=Bikini">Bikini</a></li>
                        <li><a href="<?php echo $base_path; ?>products.php?gender=Femme&category=Ensemble+plage">Ensemble plage</a></li>
                        <li><a href="<?php echo $base_path; ?>products.php?gender=Femme&category=Chapeau">Chapeau</a></li>
                        <li><a href="<?php echo $base_path; ?>products.php?gender=Femme&category=Sac">Sac</a></li>
                        <li><a href="<?php echo $base_path; ?>products.php?gender=Femme&category=Jupe">Jupe</a></li>
                        <li><a href="<?php echo $base_path; ?>products.php?gender=Femme&category=Culotte">Culotte</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#">Homme <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-content">
                        <li><a href="<?php echo $base_path; ?>products.php?gender=Homme&category=Chemise">Chemise</a></li>
                        <li><a href="<?php echo $base_path; ?>products.php?gender=Homme&category=Ensemble">Ensemble</a></li>
                        <li><a href="<?php echo $base_path; ?>products.php?gender=Homme&category=Vêtement+de+plage">Vêtement de plage</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#">Enfant <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-content">
                        <li><a href="<?php echo $base_path; ?>products.php?gender=Enfant&category=Peluche">Peluche</a></li>
                        <li><a href="<?php echo $base_path; ?>products.php?gender=Enfant&category=Vêtements+unisexes">Vêtements unisexes</a></li>
                        <li><a href="<?php echo $base_path; ?>products.php?gender=Enfant&category=Chaussettes">Chaussettes</a></li>
                    </ul>
                </li>
            </ul>
            <div class="nav-icons">
                <a href="<?php echo $base_path; ?>cart.php" class="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count"><?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></span>
                </a>
                <?php if (isLoggedIn()): ?>
                    <div class="user-dropdown">
                        <a href="#"><i class="fas fa-user"></i> <?php echo e($_SESSION['username']); ?></a>
                        <ul class="dropdown-content">
                            <?php if (isAdmin()): ?>
                                <li><a href="<?php echo $base_path; ?>admin/index.php">Dashboard</a></li>
                            <?php endif; ?>
                            <li><a href="<?php echo $base_path; ?>logout.php">Déconnexion</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?php echo $base_path; ?>login.php" class="login-btn">Connexion</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <main class="main-content">
