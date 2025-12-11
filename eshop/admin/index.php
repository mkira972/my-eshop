<?php
require_once '../config/config.php';
require_once '../classes_2/Produit.php';
require_once '../classes_2/Categorie.php';
require_once '../classes_2/Commande.php';
require_once '../classes_2/User.php';

requireAdmin();

$page_title = 'Administration';

$produitObj = new Produit();
$categorieObj = new Categorie();
$commandeObj = new Commande();
$userObj = new User();

$nb_produits = count($produitObj->getAll());
$nb_categories = count($categorieObj->getAll());
$nb_commandes = count($commandeObj->getAll());
$nb_users = count($userObj->getAllUsers());

include '../includes/header.php';
?>

<div class="container">
    <div class="admin-header">
        <h2><i class="fas fa-user-shield"></i> Panneau d'Administration</h2>
    </div>
    
    <div class="admin-stats">
        <div class="stat-card">
            <i class="fas fa-box"></i>
            <div class="stat-info">
                <h3><?php echo $nb_produits; ?></h3>
                <p>Produits</p>
            </div>
        </div>
        
        <div class="stat-card">
            <i class="fas fa-tags"></i>
            <div class="stat-info">
                <h3><?php echo $nb_categories; ?></h3>
                <p>Catégories</p>
            </div>
        </div>
        
        <div class="stat-card">
            <i class="fas fa-shopping-cart"></i>
            <div class="stat-info">
                <h3><?php echo $nb_commandes; ?></h3>
                <p>Commandes</p>
            </div>
        </div>
        
        <div class="stat-card">
            <i class="fas fa-users"></i>
            <div class="stat-info">
                <h3><?php echo $nb_users; ?></h3>
                <p>Utilisateurs</p>
            </div>
        </div>
    </div>
    
    <div class="admin-menu">
        <a href="produits.php" class="admin-menu-item">
            <i class="fas fa-box"></i>
            <span>Gérer les Produits</span>
        </a>
        
        <a href="categories.php" class="admin-menu-item">
            <i class="fas fa-tags"></i>
            <span>Gérer les Catégories</span>
        </a>
        
        <a href="commandes.php" class="admin-menu-item">
            <i class="fas fa-shopping-cart"></i>
            <span>Gérer les Commandes</span>
        </a>
        
        <a href="utilisateurs.php" class="admin-menu-item">
            <i class="fas fa-users"></i>
            <span>Gérer les Utilisateurs</span>
        </a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>