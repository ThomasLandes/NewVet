<?php
// Démarrer la session
session_start();

// Détruire toutes les variables de session
$_SESSION = array();

// Supprimer les cookies créés, y compris ceux du panier
if (isset($_COOKIE['panier'])) {
    setcookie('panier', '', time() - 3600, '/');  // Suppression du cookie panier
}

// Si vous souhaitez détruire complètement la session, vous devez également détruire le cookie de session.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalement, détruire la session elle-même
session_destroy();

// Rediriger l'utilisateur vers la page d'accueil
header("Location: index.php");
exit();
