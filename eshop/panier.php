<?php
require_once 'config/config.php';
require_once 'classes/Produit.php';
require_once 'classes/Commande.php';

$page_title = 'Mon Panier';
$produitObj = new Produit();
$message = '';

// Ajouter au panier
if (isset($_POST['add_to_cart'])) {
    $produit_id = (int)$_POST['produit_id'];
    $quantite = (int)$_POST['quantite'];
    
    $produit = $produitObj->getById($produit_id);
    
    if ($produit && $quantite <= $produit['stock']) {
        if (isset($_SESSION['panier'][$produit_id])) {
            $_SESSION['panier'][$produit_id]['quantite'] += $quantite;
        } else {
            $_SESSION['panier'][$produit_id] = [
                'nom' => $produit['nom'],
                'prix' => $produit['prix'],
                'quantite' => $quantite,
                'image' => $produit['image']
            ];
        }
        $message = '<div class="alert alert-success">Produit ajouté au panier !</div>';
    }
}

// Supprimer du panier
if (isset($_GET['remove'])) {
    $produit_id = (int)$_GET['remove'];
    unset($_SESSION['panier'][$produit_id]);
    redirect('panier.php');
}

// Mettre à jour la quantité
if (isset($_POST['update_quantity'])) {
    $produit_id = (int)$_POST['produit_id'];
    $quantite = (int)$_POST['quantite'];
    
    if ($quantite > 0) {
        $_SESSION['panier'][$produit_id]['quantite'] = $quantite;
    }
    redirect('panier.php');
}

// Valider la commande
if (isset($_POST['validate_order'])) {
    if (!isLoggedIn()) {
        redirect('login.php');
    }
    
    if (!empty($_SESSION['panier'])) {
        $commande = new Commande();
        $commande_id = $commande->create($_SESSION['user_id'], $_SESSION['panier']);
        
        if ($commande_id) {
            // Mettre à jour les stocks
            foreach ($_SESSION['panier'] as $produit_id => $item) {
                $produitObj->updateStock($produit_id, $item['quantite']);
            }
            
            $_SESSION['panier'] = [];
            $message = '<div class="alert alert-success">Commande validée avec succès ! Numéro de commande: #' . $commande_id . '</div>';
        }
    }
}

// Calculer le total
$total = 0;

if (!empty($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $item) {
        $prix = isset($item['prix']) ? $item['prix'] : 0;
        $quantite = isset($item['quantite']) ? $item['quantite'] : 1;
        $total += $prix * $quantite;
    }
}

include 'includes/header.php';
?>

<div class="container">
    <h2>Mon Panier</h2>
    
    <?php echo $message; ?>
    
    <?php if (empty($_SESSION['panier'])): ?>
        <div class="alert alert-info">Votre panier est vide.</div>
        <a href="produits.php" class="btn btn-primary">Continuer mes achats</a>
    <?php else: ?>
        <div class="panier-container">
            <table class="panier-table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix unitaire</th>
                        <th>Quantité</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['panier'] as $produit_id => $item): ?>
                        <tr>
                            <td>
                                <div class="panier-produit">
                                    <?php 
                                        $image = isset($item['image']) && !empty($item['image']) ? $item['image'] : 'default.jpg';
                                        $nom = isset($item['nom']) ? htmlspecialchars($item['nom']) : 'Produit inconnu';
                                    ?>
                                    <img src="assets/images/<?php echo $image; ?>" alt="<?php echo $nom; ?>">
                                    <span><?php echo $nom; ?></span>
                                </div>

                            </td>
                            <td>
                                <?php echo isset($item['prix']) ? number_format($item['prix'], 2) . ' €' : '0.00 €'; ?>
                            </td>

                            <td>
                                <form method="POST" action="" class="quantity-form">
                                    <input type="hidden" name="produit_id" value="<?php echo $produit_id; ?>">
                                    <input type="number" name="quantite" value="<?php echo $item['quantite']; ?>" min="1">
                                    <button type="submit" name="update_quantity" class="btn btn-sm">
                                        <i class="fas fa-sync"></i>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <?php 
                                    $prix = isset($item['prix']) ? $item['prix'] : 0;
                                    $quantite = isset($item['quantite']) ? $item['quantite'] : 1;
                                    echo number_format($prix * $quantite, 2) . ' €';
                                ?>
                            </td>

                            <td>
                                <a href="panier.php?remove=<?php echo $produit_id; ?>" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><strong>Total</strong></td>
                        <td colspan="2"><strong><?php echo number_format($total, 2); ?> €</strong></td>
                    </tr>
                </tfoot>
            </table>
            
            <div class="panier-actions">
                <a href="produits.php" class="btn btn-secondary">Continuer mes achats</a>
                
                <?php if (isLoggedIn()): ?>
                    <form method="POST" action="" style="display: inline;">
                        <button type="submit" name="validate_order" class="btn btn-success">
                            <i class="fas fa-check"></i> Valider la commande
                        </button>
                    </form>
                <?php else: ?>
                    <a href="login.php" class="btn btn-success">Se connecter pour valider</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>