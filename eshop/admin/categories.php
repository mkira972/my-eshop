<?php
require_once '../config/config.php';
require_once '../classes_2/Categorie.php';

requireAdmin();

$page_title = 'Gestion des Catégories';
$message = '';

$categorieObj = new Categorie();

// Créer ou modifier une catégorie
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        $nom = trim($_POST['nom']);
        $description = trim($_POST['description']);
        
        if (!empty($nom)) {
            if ($categorieObj->create($nom, $description)) {
                $message = '<div class="alert alert-success">Catégorie créée avec succès.</div>';
            } else {
                $message = '<div class="alert alert-error">Erreur lors de la création.</div>';
            }
        }
    } elseif (isset($_POST['update'])) {
        $id = intval($_POST['id']);
        $nom = trim($_POST['nom']);
        $description = trim($_POST['description']);
        
        if (!empty($nom)) {
            if ($categorieObj->update($id, $nom, $description)) {
                $message = '<div class="alert alert-success">Catégorie modifiée avec succès.</div>';
            } else {
                $message = '<div class="alert alert-error">Erreur lors de la modification.</div>';
            }
        }
    }
}

// Supprimer une catégorie
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($categorieObj->delete($id)) {
        $message = '<div class="alert alert-success">Catégorie supprimée avec succès.</div>';
    } else {
        $message = '<div class="alert alert-error">Impossible de supprimer cette catégorie (elle contient peut-être des produits).</div>';
    }
}

// Mode édition
$edit_categorie = null;
if (isset($_GET['edit'])) {
    $edit_categorie = $categorieObj->getById($_GET['edit']);
}

$categories = $categorieObj->getAll();

include '../includes/header.php';
?>

<div class="container">
    <div class="admin-header">
        <h2>Gestion des Catégories</h2>
        <a href="index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
    
    <?php echo $message; ?>
    
    <div class="admin-grid">
        <div class="form-container">
            <h3><?php echo $edit_categorie ? 'Modifier la Catégorie' : 'Nouvelle Catégorie'; ?></h3>
            <form method="POST" action="">
                <?php if ($edit_categorie): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_categorie['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="nom">Nom de la catégorie *</label>
                    <input type="text" id="nom" name="nom" required
                           value="<?php echo $edit_categorie ? htmlspecialchars($edit_categorie['nom']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4"><?php echo $edit_categorie ? htmlspecialchars($edit_categorie['description']) : ''; ?></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" name="<?php echo $edit_categorie ? 'update' : 'create'; ?>" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?php echo $edit_categorie ? 'Mettre à jour' : 'Créer'; ?>
                    </button>
                    <?php if ($edit_categorie): ?>
                        <a href="categories.php" class="btn btn-secondary">Annuler</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        
        <div class="list-container">
            <h3>Liste des Catégories</h3>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Nb Produits</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td><?php echo $cat['id']; ?></td>
                            <td><?php echo htmlspecialchars($cat['nom']); ?></td>
                            <td><?php echo $cat['nb_produits']; ?></td>
                            <td>
                                <a href="?edit=<?php echo $cat['id']; ?>" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?delete=<?php echo $cat['id']; ?>" 
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
    </div>
</div>

<?php include '../includes/footer.php'; ?>