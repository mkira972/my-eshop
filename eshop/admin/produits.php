<?php
require_once '../config/config.php';
require_once '../classes_2/Produit.php';
require_once '../classes_2/Categorie.php';

requireAdmin();

$page_title = 'Gestion des Produits';
$message = '';

$produitObj = new Produit();
$categorieObj = new Categorie();

// Supprimer un produit
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($produitObj->delete($id)) {
        $message = '<div class="alert alert-success">Produit supprimé avec succès.</div>';
    }
}

$produits = $produitObj->getAll();
$categories = $categorieObj->getAll();

include '../includes/header.php';
?>

<div class="container">
    <div class="admin-header">
        <h2>Gestion des Produits</h2>
        <a href="produit_form.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau Produit
        </a>
    </div>
    
    <?php echo $message; ?>
    
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Nom</th>
                <th>Prix</th>
                <th>Stock</th>
                <th>Catégories</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produits as $prod): ?>
                <tr>
                    <td><?php echo $prod['id']; ?></td>
                    <td>
                        <img src="../assets/images/<?php echo $prod['image'] ? $prod['image'] : 'default.jpg'; ?>" 
                             alt="" class="table-img">
                    </td>
                    <td><?php echo htmlspecialchars($prod['nom']); ?></td>
                    <td><?php echo number_format($prod['prix'], 2); ?> €</td>
                    <td><?php echo $prod['stock']; ?></td>
                    <td>
                        <?php 
                        $cats = $produitObj->getCategories($prod['id']);
                        $cat_names = array_map(function($c) { return $c['nom']; }, $cats);
                        echo implode(', ', $cat_names);
                        ?>
                    </td>
                    <td>
                        <a href="produit_form.php?id=<?php echo $prod['id']; ?>" class="btn btn-sm btn-secondary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="?delete=<?php echo $prod['id']; ?>" 
                           onclick="return confirm('Confirmer la suppression ?')" 
                           class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>