<?php
require_once 'config2/config.php';
require_once '../classes_2/Commande.php';

requireAdmin();

$page_title = 'Détails de la Commande';

if (!isset($_GET['id'])) {
    redirect('commandes.php');
}

$commandeObj = new Commande();
$commande = $commandeObj->getById($_GET['id']);

if (!$commande) {
    redirect('commandes.php');
}

$details = $commandeObj->getDetails($_GET['id']);

include '../includes/header.php';
?>

<div class="container">
    <div class="admin-header">
        <h2>Commande #<?php echo $commande['id']; ?></h2>
        <a href="commandes.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
    
    <div class="commande-details-grid">
        <div class="commande-info-card">
            <h3><i class="fas fa-user"></i> Informations Client</h3>
            <div class="info-row">
                <span class="info-label">Nom complet :</span>
                <span class="info-value"><?php echo htmlspecialchars($commande['nom'] . ' ' . $commande['prenom']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Email :</span>
                <span class="info-value"><?php echo htmlspecialchars($commande['email']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Téléphone :</span>
                <span class="info-value"><?php echo htmlspecialchars($commande['telephone']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Adresse :</span>
                <span class="info-value"><?php echo nl2br(htmlspecialchars($commande['adresse'])); ?></span>
            </div>
        </div>
        
        <div class="commande-info-card">
            <h3><i class="fas fa-shopping-cart"></i> Informations Commande</h3>
            <div class="info-row">
                <span class="info-label">N° Commande :</span>
                <span class="info-value">#<?php echo $commande['id']; ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Date :</span>
                <span class="info-value"><?php echo date('d/m/Y à H:i', strtotime($commande['date_commande'])); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Statut :</span>
                <span class="info-value">
                    <span class="badge badge-<?php echo $commande['statut']; ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $commande['statut'])); ?>
                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Montant total :</span>
                <span class="info-value price-total"><?php echo number_format($commande['montant_total'], 2); ?> €</span>
            </div>
        </div>
    </div>
    
    <div class="commande-produits">
        <h3>Produits Commandés</h3>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Produit</th>
                    <th>Prix unitaire</th>
                    <th>Quantité</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($details as $item): ?>
                    <tr>
                        <td>
                            <img src="../assets/images/<?php echo $item['image'] ? $item['image'] : 'default.jpg'; ?>" 
                                 alt="" class="table-img">
                        </td>
                        <td><?php echo htmlspecialchars($item['nom']); ?></td>
                        <td><?php echo number_format($item['prix_unitaire'], 2); ?> €</td>
                        <td><?php echo $item['quantite']; ?></td>
                        <td><strong><?php echo number_format($item['prix_unitaire'] * $item['quantite'], 2); ?> €</strong></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right"><strong>Total de la commande :</strong></td>
                    <td><strong class="price-total"><?php echo number_format($commande['montant_total'], 2); ?> €</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>