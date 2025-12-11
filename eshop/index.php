<?php
require_once 'config/config.php';
require_once 'classes/Produit.php';
require_once 'classes/Categorie.php';

$page_title = 'Accueil';

$produit = new Produit();
$categorie = new Categorie();

$produits = $produit->getAll();
$categories = $categorie->getAll();

// Limiter à 6 produits pour la page d'accueil
$produits_featured = array_slice($produits, 0, 6);

include 'includes/header.php';
?>

<div class="hero">
    <div class="hero-content">
        <h1>Bienvenue sur <?php echo SITE_NAME; ?></h1>
        <p>Découvrez nos produits de qualité à prix imbattables</p>
        <a href="produits.php" class="btn btn-primary">Voir les produits</a>
    </div>
</div>

<div class="container">
    <section class="categories-section">
        <h2>Nos Catégories</h2>
        <div class="categories-grid">
            <?php foreach ($categories as $cat): ?>
                <a href="produits.php?categorie=<?php echo $cat['id']; ?>" class="category-card">
                    <i class="fas fa-tag"></i>
                    <h3><?php echo htmlspecialchars($cat['nom']); ?></h3>
                    <p><?php echo $cat['nb_produits']; ?> produits</p>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="produits-section">
        <h2>Produits en vedette</h2>
        <div class="produits-grid">
            <?php foreach ($produits_featured as $prod): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="assets/images/<?php echo $prod['image'] ? $prod['image'] : 'default.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($prod['nom']); ?>">
                    </div>
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($prod['nom']); ?></h3>
                        <p class="product-description"><?php echo htmlspecialchars(substr($prod['description'], 0, 80)) . '...'; ?></p>
                        <div class="product-footer">
                            <span class="product-price"><?php echo number_format($prod['prix'], 2); ?> €</span>
                            <span class="product-stock">Stock: <?php echo $prod['stock']; ?></span>
                        </div>
                        <form method="POST" action="panier.php">
                            <input type="hidden" name="produit_id" value="<?php echo $prod['id']; ?>">
                            <input type="number" name="quantite" value="1" min="1" max="<?php echo $prod['stock']; ?>">
                            <button type="submit" name="add_to_cart" class="btn btn-primary">
                                <i class="fas fa-cart-plus"></i> Ajouter
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>