<?php
require_once '../config/config.php';
require_once '../classes_2/Produit.php';
require_once '../classes_2/Categorie.php';

requireAdmin();

$page_title = 'Formulaire Produit';
$message = '';
$edit_mode = false;
$produit = null;

$produitObj = new Produit();
$categorieObj = new Categorie();

$categories = $categorieObj->getAll();

// Mode édition
if (isset($_GET['id'])) {
    $edit_mode = true;
    $produit = $produitObj->getById($_GET['id']);
    if (!$produit) {
        redirect('produits.php');
    }
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $prix = floatval($_POST['prix']);
    $stock = intval($_POST['stock']);
    $categories_selected = isset($_POST['categories']) ? $_POST['categories'] : [];
    
    $image_name = null;
    
    // Gestion de l'upload d'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $image_name = uniqid() . '.' . $ext;
            $upload_path = '../assets/images/' . $image_name;
            
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $message = '<div class="alert alert-error">Erreur lors de l\'upload de l\'image.</div>';
                $image_name = null;
            }
        } else {
            $message = '<div class="alert alert-error">Format d\'image non autorisé. Utilisez JPG, PNG ou GIF.</div>';
        }
    }
    
    if (empty($nom) || empty($prix) || $prix <= 0) {
        $message = '<div class="alert alert-error">Veuillez remplir tous les champs obligatoires.</div>';
    } else {
        if ($edit_mode) {
            // Mise à jour
            $image_to_use = $image_name ? $image_name : $produit['image'];
            if ($produitObj->update($_GET['id'], $nom, $description, $prix, $stock, $image_name, $categories_selected)) {
                $message = '<div class="alert alert-success">Produit modifié avec succès ! <a href="produits.php">Retour à la liste</a></div>';
            } else {
                $message = '<div class="alert alert-error">Erreur lors de la modification.</div>';
            }
        } else {
            // Création
            if ($produitObj->create($nom, $description, $prix, $stock, $image_name, $categories_selected)) {
                $message = '<div class="alert alert-success">Produit créé avec succès ! <a href="produits.php">Retour à la liste</a></div>';
            } else {
                $message = '<div class="alert alert-error">Erreur lors de la création.</div>';
            }
        }
    }
}

// Récupérer les catégories du produit en mode édition
$produit_categories = [];
if ($edit_mode) {
    $cats = $produitObj->getCategories($produit['id']);
    $produit_categories = array_column($cats, 'id');
}

include '../includes/header.php';
?>

<div class="container">
    <div class="admin-header">
        <h2><?php echo $edit_mode ? 'Modifier le Produit' : 'Nouveau Produit'; ?></h2>
        <a href="produits.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
    
    <?php echo $message; ?>
    
    <div class="form-container">
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nom">Nom du produit *</label>
                <input type="text" id="nom" name="nom" required 
                       value="<?php echo $edit_mode ? htmlspecialchars($produit['nom']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="5"><?php echo $edit_mode ? htmlspecialchars($produit['description']) : ''; ?></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="prix">Prix (€) *</label>
                    <input type="number" id="prix" name="prix" step="0.01" min="0" required
                           value="<?php echo $edit_mode ? $produit['prix'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="stock">Stock *</label>
                    <input type="number" id="stock" name="stock" min="0" required
                           value="<?php echo $edit_mode ? $produit['stock'] : ''; ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="image">Image du produit</label>
                <?php if ($edit_mode && $produit['image']): ?>
                    <div class="current-image">
                        <img src="../assets/images/<?php echo $produit['image']; ?>" alt="Image actuelle" style="max-width: 200px; margin-bottom: 10px;">
                        <p><small>Image actuelle - Uploadez une nouvelle image pour la remplacer</small></p>
                    </div>
                <?php endif; ?>
                <input type="file" id="image" name="image" accept="image/*">
                <small>Formats acceptés : JPG, PNG, GIF</small>
            </div>
            
            <div class="form-group">
                <label>Catégories</label>
                <div class="checkbox-group">
                    <?php foreach ($categories as $cat): ?>
                        <label class="checkbox-label">
                            <input type="checkbox" name="categories[]" value="<?php echo $cat['id']; ?>"
                                   <?php echo in_array($cat['id'], $produit_categories) ? 'checked' : ''; ?>>
                            <?php echo htmlspecialchars($cat['nom']); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-save"></i> <?php echo $edit_mode ? 'Mettre à jour' : 'Créer le produit'; ?>
            </button>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>