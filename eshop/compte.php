<?php
require_once 'config/config.php';
require_once 'classes/User.php';
require_once 'classes/Commande.php';

requireLogin();

$page_title = 'Mon Compte';
$message = '';

$userObj = new User();
$commandeObj = new Commande();

$user = $userObj->getUserById($_SESSION['user_id']);
$commandes = $commandeObj->getByUser($_SESSION['user_id']);

// Modifier le profil
if (isset($_POST['update_profile'])) {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $adresse = trim($_POST['adresse']);
    
    if (empty($nom) || empty($prenom) || empty($email)) {
        $message = '<div class="alert alert-error">Les champs nom, prénom et email sont obligatoires.</div>';
    } else {
        if ($userObj->updateUser($_SESSION['user_id'], $nom, $prenom, $email, $telephone, $adresse)) {
            $_SESSION['user_nom'] = $nom;
            $_SESSION['user_prenom'] = $prenom;
            $_SESSION['user_email'] = $email;
            $message = '<div class="alert alert-success">Profil mis à jour avec succès.</div>';
            $user = $userObj->getUserById($_SESSION['user_id']);
        } else {
            $message = '<div class="alert alert-error">Erreur lors de la mise à jour.</div>';
        }
    }
}

// Supprimer le compte
if (isset($_POST['delete_account'])) {
    if ($userObj->deleteUser($_SESSION['user_id'])) {
        session_destroy();
        redirect('index.php?message=compte_supprime');
    }
}

include 'includes/header.php';
?>

<div class="container">
    <h2>Mon Compte</h2>
    
    <?php echo $message; ?>
    
    <div class="compte-grid">
        <div class="compte-section">
            <h3><i class="fas fa-user-circle"></i> Mes Informations</h3>
            
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nom">Nom *</label>
                        <input type="text" id="nom" name="nom" required 
                               value="<?php echo htmlspecialchars($user['nom']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="prenom">Prénom *</label>
                        <input type="text" id="prenom" name="prenom" required 
                               value="<?php echo htmlspecialchars($user['prenom']); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo htmlspecialchars($user['email']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" 
                           value="<?php echo htmlspecialchars($user['telephone']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <textarea id="adresse" name="adresse" rows="3"><?php echo htmlspecialchars($user['adresse']); ?></textarea>
                </div>
                
                <button type="submit" name="update_profile" class="btn btn-primary">
                    <i class="fas fa-save"></i> Mettre à jour
                </button>
            </form>
            
            <div class="danger-zone">
                <h4><i class="fas fa-exclamation-triangle"></i> Zone dangereuse</h4>
                <p>La suppression de votre compte est irréversible et supprimera toutes vos données.</p>
                <form method="POST" action="" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.');">
                    <button type="submit" name="delete_account" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Supprimer mon compte
                    </button>
                </form>
            </div>
        </div>
        
        <div class="compte-section">
            <h3><i class="fas fa-history"></i> Historique des Commandes</h3>
            
            <?php if (empty($commandes)): ?>
                <div class="alert alert-info">Vous n'avez pas encore passé de commande.</div>
                <a href="produits.php" class="btn btn-primary">Découvrir nos produits</a>
            <?php else: ?>
                <div class="commandes-list">
                    <?php foreach ($commandes as $cmd): ?>
                        <div class="commande-card">
                            <div class="commande-header">
                                <h4>Commande #<?php echo $cmd['id']; ?></h4>
                                <span class="badge badge-<?php echo $cmd['statut']; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $cmd['statut'])); ?>
                                </span>
                            </div>
                            <div class="commande-body">
                                <p><i class="fas fa-calendar"></i> <?php echo date('d/m/Y à H:i', strtotime($cmd['date_commande'])); ?></p>
                                <p><i class="fas fa-euro-sign"></i> Montant : <strong><?php echo number_format($cmd['montant_total'], 2); ?> €</strong></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>