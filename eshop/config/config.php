<?php
session_start();

// Configuration du site
define('SITE_NAME', 'E-Shop Pro');
define('SITE_URL', 'http://localhost/ecommerce/');


// Chemins
define('BASE_PATH', dirname(__DIR__) . '/');
define('UPLOAD_PATH', BASE_PATH . 'uploads/');

// Fonctions utilitaires
function redirect($page) {
    header("Location: " . SITE_URL . $page);
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect('login.php');
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        redirect('index.php');
    }
}

// Initialiser le panier
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}
?>