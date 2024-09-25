<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$panier = isset($_COOKIE['panier']) ? json_decode($_COOKIE['panier'], true) : array();

if (isset($_POST['index'])) {
    $index = (int)$_POST['index'];

    if (isset($panier[$index])) {
        // Supprimer l'article du panier
        unset($panier[$index]);

        // Réindexer le panier pour éviter des trous dans les clés
        $panier = array_values($panier);
    }

    // Mettre à jour le cookie du panier
    setcookie('panier', json_encode($panier), time() + (86400 * 30), "/"); // 30 jours
}

echo json_encode(["status" => "success"]);
exit();

