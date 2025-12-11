<?php
require_once 'config/config.php';
require_once 'classes/Categorie.php';
require_once 'classes/Produit.php';

$page_title = 'Catégories';

$categorieObj = new Categorie();
$produitObj = new Produit();

$categories = $categorieObj->getAll();

include 'includes/header.php';
?>

<div class="container">
    <h2>Toutes nos Catégories</h2>
    <p class="subtitle">Explorez nos différentes catégories de produits</p>
    
    <div class="categories-showcase">
        <?php foreach ($categories as $cat): ?>
            <div class="category-showcase-card">
                <div class="category-showcase-header">
                    <i class="fas fa-tag"></i>
                    <h3><?php echo htmlspecialchars($cat['nom']); ?></h3>
                </div>
                
                <div class="category-showcase-body">
                    <p><?php echo htmlspecialchars($cat['description']); ?></p>
                    <div class="category-stats">
                        <span><i class="fas fa-box"></i> <?php echo $cat['nb_produits']; ?> produit(s)</span>
                    </div>
                </div>
                
                <?php
                // Récupérer quelques produits de cette catégorie
                $produits_cat = $produitObj->getByCategorie($cat['id']);
                $produits_preview = array_slice($produits_cat, 0, 3);
                ?>
                
                <?php if (!empty($produits_preview)): ?>
                    <div class="category-products-preview">
                        <h4>Aperçu des produits :</h4>
                        <div class="products-mini-grid">
                            <?php foreach ($produits_preview as $prod): ?>
                                <div class="product-mini">
                                    <img src="assets/images/<?php echo $prod['image'] ? $prod['image'] : 'default.jpg'; ?>" 
                                         alt="<?php echo htmlspecialchars($prod['nom']); ?>">
                                    <p><?php echo htmlspecialchars($prod['nom']); ?></p>
                                    <span class="mini-price"><?php echo number_format($prod['prix'], 2); ?> €</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="category-showcase-footer">
                    <a href="produits.php?categorie=<?php echo $cat['id']; ?>" class="btn btn-primary">
                        Voir tous les produits <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <?php if (empty($categories)): ?>
        <div class="alert alert-info">Aucune catégorie disponible pour le moment.</div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>