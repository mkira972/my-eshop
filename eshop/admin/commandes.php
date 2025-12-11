<?php
require_once '../config/config.php';
require_once '../classes_2/Commande.php';

requireAdmin();

$page_title = 'Gestion des Commandes';
$message = '';

$commandeObj = new Commande();

// Mettre à jour le statut
if (isset($_POST['update_statut'])) {
    $commande_id = (int)$_POST['commande_id'];
    $statut = $_POST['statut'];
    
    if ($commandeObj->updateStatut($commande_id, $statut)) {
        $message = '<div class="alert alert-success">Statut mis à jour.</div>';
    }
}

// Supprimer une commande
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($commandeObj->delete($id)) {
        $message = '<div class="alert alert-success">Commande supprimée.</div>';
    }
}

$commandes = $commandeObj->getAll();

include '../includes/header.php';
?>

<div class="container">
    <div class="admin-header">
        <h2>Gestion des Commandes</h2>
    </div>
    
    <?php echo $message; ?>
    
    <table class="admin-table">
        <thead>
            <tr>
                <th>N° Commande</th>
                <th>Client</th>
                <th>Email</th>
                <th>Montant</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($commandes as $cmd): ?>
                <tr>
                    <td>#<?php echo $cmd['id']; ?></td>
                    <td><?php echo htmlspecialchars($cmd['nom'] . ' ' . $cmd['prenom']); ?></td>
                    <td><?php echo htmlspecialchars($cmd['email']); ?></td>
                    <td><?php echo number_format($cmd['montant_total'], 2); ?> €</td>
                    <td>
                        <form method="POST" action="" class="inline-form">
                            <input type="hidden" name="commande_id" value="<?php echo $cmd['id']; ?>">
                            <select name="statut" onchange="this.form.submit()">
                                <option value="en_attente" <?php echo $cmd['statut'] == 'en_attente' ? 'selected' : ''; ?>>En attente</option>
                                <option value="en_cours" <?php echo $cmd['statut'] == 'en_cours' ? 'selected' : ''; ?>>En cours</option>
                                <option value="expediee" <?php echo $cmd['statut'] == 'expediee' ? 'selected' : ''; ?>>Expédiée</option>
                                <option value="livree" <?php echo $cmd['statut'] == 'livree' ? 'selected' : ''; ?>>Livrée</option>
                                <option value="annulee" <?php echo $cmd['statut'] == 'annulee' ? 'selected' : ''; ?>>Annulée</option>
                            </select>
                            <button type="submit" name="update_statut" style="display:none;"></button>
                        </form>
                    </td>
                    <td><?php echo date('d/m/Y H:i', strtotime($cmd['date_commande'])); ?></td>
                    <td>
                        <a href="commande_details.php?id=<?php echo $cmd['id']; ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="?delete=<?php echo $cmd['id']; ?>" 
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