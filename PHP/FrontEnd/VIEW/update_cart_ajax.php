<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$panier = isset($_COOKIE['panier']) ? json_decode($_COOKIE['panier'], true) : array();

if (isset($_POST['index'], $_POST['quantite'])) {
    $index = (int)$_POST['index'];
    $quantite = (int)$_POST['quantite'];

    if ($quantite > 0 && isset($panier[$index])) {
        // Mettre à jour la quantité
        $panier[$index]['quantite'] = $quantite;
    }

    // Mettre à jour le cookie du panier
    setcookie('panier', json_encode($panier), time() + (86400 * 30), "/"); // 30 jours
}

echo json_encode(["status" => "success"]);
exit();


