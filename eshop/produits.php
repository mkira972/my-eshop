<?php
require_once 'config/config.php';
require_once 'classes/Produit.php';
require_once 'classes/Categorie.php';

$page_title = 'Nos Produits';

$produitObj = new Produit();
$categorieObj = new Categorie();

$categories = $categorieObj->getAll();

// Filtrer par catégorie si demandé
if (isset($_GET['categorie'])) {
    $categorie_id = (int)$_GET['categorie'];
    $produits = $produitObj->getByCategorie($categorie_id);
} else {
    $produits = $produitObj->getAll();
}

include 'includes/header.php';
?>

<div class="container">
    <h2>Nos Produits</h2>
    
    <div class="filter-section">
        <a href="produits.php" class="filter-btn <?php echo !isset($_GET['categorie']) ? 'active' : ''; ?>">
            Tous les produits
        </a>
        <?php foreach ($categories as $cat): ?>
            <a href="produits.php?categorie=<?php echo $cat['id']; ?>" 
               class="filter-btn <?php echo (isset($_GET['categorie']) && $_GET['categorie'] == $cat['id']) ? 'active' : ''; ?>">
                <?php echo htmlspecialchars($cat['nom']); ?>
            </a>
        <?php endforeach; ?>
    </div>
    
    <?php if (empty($produits)): ?>
        <div class="alert alert-info">Aucun produit trouvé dans cette catégorie.</div>
    <?php else: ?>
        <div class="produits-grid">
            <?php foreach ($produits as $prod): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="assets/images/<?php echo $prod['image'] ? $prod['image'] : 'default.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($prod['nom']); ?>">
                    </div>
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($prod['nom']); ?></h3>
                        <p class="product-description"><?php echo htmlspecialchars($prod['description']); ?></p>
                        <div class="product-footer">
                            <span class="product-price"><?php echo number_format($prod['prix'], 2); ?> €</span>
                            <span class="product-stock">
                                <?php if ($prod['stock'] > 0): ?>
                                    <i class="fas fa-check-circle" style="color: green;"></i> En stock (<?php echo $prod['stock']; ?>)
                                <?php else: ?>
                                    <i class="fas fa-times-circle" style="color: red;"></i> Rupture de stock
                                <?php endif; ?>
                            </span>
                        </div>
                        <?php if ($prod['stock'] > 0): ?>
                            <form method="POST" action="panier.php">
                                <input type="hidden" name="produit_id" value="<?php echo $prod['id']; ?>">
                                <div class="product-quantity">
                                    <input type="number" name="quantite" value="1" min="1" max="<?php echo $prod['stock']; ?>">
                                    <button type="submit" name="add_to_cart" class="btn btn-primary btn-block">
                                        <i class="fas fa-cart-plus"></i> Ajouter au panier
                                    </button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>