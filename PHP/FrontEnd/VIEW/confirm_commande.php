<?php
include '../../Fonction/conf.php';
include '../../Fonction/db.php';
include '../../Fonction/auth.php';

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
$adresseFacturationId = $_POST['adresse_id'];
$paiementId = $_POST['carte_id']; // ID du moyen de paiement
$userId = $_SESSION['utilisateur_id']; // Récupéré depuis la session

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

// Récupérer le panier
$panier = isset($_COOKIE['panier']) ? json_decode($_COOKIE['panier'], true) : array();

// Insertion des articles dans la table "contenu_commande"
foreach ($panier as $item) {
    $sql = "INSERT INTO contenu_commande (contenu_prix_unite, contenu_quantite, commande_id, produit_id)
            VALUES (:contenu_prix_unite, :contenu_quantite, :commande_id, :produit_id)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':contenu_prix_unite', $item['prix']);
    $stmt->bindValue(':contenu_quantite', $item['quantite']);
    $stmt->bindValue(':commande_id', $commandeId);
    $stmt->bindValue(':produit_id', $item['id']);
    $stmt->execute();
}

// Rediriger vers la page de confirmation après l'insertion
header('Location: confirmation_commande.php?commande_id=' . $commandeId);
exit;
?>
