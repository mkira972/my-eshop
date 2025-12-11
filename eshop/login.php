<?php
require_once 'config/config.php';
require_once 'classes/User.php';

$page_title = 'Connexion';
$error = '';

if (isLoggedIn()) {
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        $user = new User();
        
        if ($user->login($email, $password)) {
            redirect('index.php');
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    }
}

include 'includes/header.php';
?>

<div class="container">
    <div class="form-container">
        <h2>Connexion</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
        </form>
        
        <p class="text-center">
            Pas encore de compte ? <a href="register.php">S'inscrire</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>