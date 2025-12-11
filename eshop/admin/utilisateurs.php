<?php
require_once '../config/config.php';
require_once '../classes_2/User.php';

requireAdmin();

$page_title = 'Gestion des Utilisateurs';
$message = '';

$userObj = new User();

// Supprimer un utilisateur
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($id != $_SESSION['user_id']) { // Ne pas supprimer son propre compte
        if ($userObj->deleteUser($id)) {
            $message = '<div class="alert alert-success">Utilisateur supprimé avec succès.</div>';
        } else {
            $message = '<div class="alert alert-error">Erreur lors de la suppression.</div>';
        }
    } else {
        $message = '<div class="alert alert-error">Vous ne pouvez pas supprimer votre propre compte.</div>';
    }
}

$users = $userObj->getAllUsers();

include '../includes/header.php';
?>

<div class="container">
    <div class="admin-header">
        <h2>Gestion des Utilisateurs</h2>
        <a href="index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
    
    <?php echo $message; ?>
    
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Rôle</th>
                <th>Date d'inscription</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['nom']); ?></td>
                    <td><?php echo htmlspecialchars($user['prenom']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['telephone'] !== null ? $user['telephone'] : ''); ?></td>
                    <td>
                        <?php if ($user['is_admin']): ?>
                            <span class="badge badge-admin"><i class="fas fa-shield-alt"></i> Admin</span>
                        <?php else: ?>
                            <span class="badge badge-user"><i class="fas fa-user"></i> Utilisateur</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo date('d/m/Y', strtotime($user['date_creation'])); ?></td>
                    <td>
                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                            <a href="?delete=<?php echo $user['id']; ?>" 
                               onclick="return confirm('Confirmer la suppression de cet utilisateur ?')" 
                               class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </a>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="stats-info">
        <p><strong>Total utilisateurs :</strong> <?php echo count($users); ?></p>
        <p><strong>Administrateurs :</strong> <?php echo count(array_filter($users, function($u) { return $u['is_admin']; })); ?></p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>