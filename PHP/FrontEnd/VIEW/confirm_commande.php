<?php
include '../../Fonction/conf.php';
include '../../Fonction/db.php';
include '../../Fonction/auth.php';
include '../../Fonction/mail.php';

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
$userInfo = getUserInfo();

if ($userInfo === null) {
    // Si l'utilisateur n'est pas connecté, redirection vers la page de connexion
    header('Location: connexion.php');
    exit();
}

$dbh = connexion_bdd();

// Vérifier si les données essentielles ont été envoyées via le formulaire de checkout
if (!isset($_POST['adresse_id'], $_POST['carte_id']) || empty($_POST['adresse_id']) || empty($_POST['carte_id'])) {
    // Si l'une des variables est manquante ou vide, redirection ou affichage d'un message d'erreur
    header('Location: index.php'); // Vous pouvez personnaliser l'URL avec un message d'erreur
    exit();
}


// Récupération des données depuis le formulaire de checkout
$adresseLivraisonId = $_POST['adresse_id'];
$adresseFacturationId = $_POST['adresse_facturation_id'];
$paiementId = $_POST['carte_id']; // ID du moyen de paiement
$userId = $_SESSION['utilisateur_id']; // Récupéré depuis la session

// Récupérer le panier
$panier = isset($_COOKIE['panier']) ? json_decode($_COOKIE['panier'], true) : array();

foreach ($panier as $item) {
// Vérifier le stock actuel du produit
    $produit = getStockProduit($dbh, $item['id']);
    if ($produit['produit_stock'] < $item['quantite']) {
        // Stock insuffisant, redirection avec un message d'erreur
        header('Location: checkout.php?error=stock');
        exit();
    }
}
foreach ($panier as $item) {
    $produit = getStockProduit($dbh, $item['id']);
    $nouveauStock = $produit['produit_stock'] - $item['quantite'];
    $sqlUpdateStock = "UPDATE produit SET produit_stock = :nouveau_stock WHERE produit_id = :produit_id";
    $stmtUpdateStock = $dbh->prepare($sqlUpdateStock);
    $stmtUpdateStock->bindValue(':nouveau_stock', $nouveauStock);
    $stmtUpdateStock->bindValue(':produit_id', $item['id']);
    $stmtUpdateStock->execute();
}

// Insertion de la commande dans la table "commande"
$sql = "INSERT INTO commande (commande_etat, commande_date_crea, adresse_livraison_id, adresse_facturation_id, paiement_id, utilisateur_id)
        VALUES (:commande_etat, NOW(), :adresse_livraison_id, :adresse_facturation_id, :paiement_id, :utilisateur_id)";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':commande_etat', 'en traitement'); // L'état initial de la commande
$stmt->bindValue(':adresse_livraison_id', $adresseLivraisonId);
$stmt->bindValue(':adresse_facturation_id', $adresseFacturationId);
$stmt->bindValue(':paiement_id', $paiementId);
$stmt->bindValue(':utilisateur_id', $userId);
$stmt->execute();

// Récupérer l'ID de la commande insérée
$commandeId = $dbh->lastInsertId();
// Insertion des articles dans la table "contenu_commande"
$totalPrix = 0; // Initialiser la variable pour le total
foreach ($panier as $item) {
    $sql = "INSERT INTO contenu_commande (contenu_prix_unite, contenu_quantite, commande_id, produit_id)
            VALUES (:contenu_prix_unite, :contenu_quantite, :commande_id, :produit_id)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':contenu_prix_unite', $item['prix']);
    $stmt->bindValue(':contenu_quantite', $item['quantite']);
    $stmt->bindValue(':commande_id', $commandeId);
    $stmt->bindValue(':produit_id', $item['id']);
    $stmt->execute();

    $totalPrix += $item['prix'] * $item['quantite'];
}

$mailinfo= recupUserInfoParID($dbh,$userId);
// Envoyer l'email de confirmation
$emailStatus = envoyerEmailConfirmation($mailinfo['utilisateur_email'], $commandeId, $totalPrix);

// Vérifier si l'e-mail a été envoyé avec succès
if ($emailStatus) {
    // Rediriger vers la page de confirmation après l'insertion
    header('Location: confirmation_commande.php?commande_id=' . $commandeId);
    exit;
} else {
    // Gérer l'erreur d'envoi d'e-mail (optionnel)
    // Tu peux rediriger vers une page d'erreur ou afficher un message
    echo "Erreur lors de l'envoi de l'e-mail de confirmation.";
}
