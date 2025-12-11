<?php
if (!defined('SITE_NAME')) {
    require_once 'config/config.php';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="container-nav">
            <a href="index.php" class="logo">
                <i class="fas fa-shopping-cart"></i> <?php echo SITE_NAME; ?>
            </a>
            
            <div class="nav-links">
                <a href="index.php">Accueil</a>
                <a href="produits.php">Produits</a>
                <a href="categories.php">Catégories</a>
                
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <a href="admin/index.php">Administration</a>
                    <?php endif; ?>
                    <a href="panier.php" class="panier-link">
                        <i class="fas fa-shopping-cart"></i> 
                        Panier (<?php echo count($_SESSION['panier']); ?>)
                    </a>
                    <a href="compte.php">Mon Compte</a>
                    <a href="logout.php">Déconnexion</a>
                <?php else: ?>
                    <a href="login.php">Connexion</a>
                    <a href="register.php">Inscription</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <main class="main-content">
                </main>
