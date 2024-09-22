<?php

include '../../Fonction/db.php';
include '../../Fonction/conf.php';
include '../../Fonction/auth.php';

// Connexion à la base de données
$dbh = connexion_bdd();
$idCb = isset($_GET['id']) ? $_GET['id'] : null;

$info = getUserInfo();
$idUser = $info['user_id'];

if ($idCb !== null) {
    // Préparer la requête SQL pour vérifier que la carte appartient à l'utilisateur
    $sql = "SELECT * FROM paiement WHERE paiement_id = :idCb AND utilisateur_id = :idUser";

    // Utilisation de PDO pour sécuriser la requête
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':idCb', $idCb, PDO::PARAM_INT);
    $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    $stmt->execute();

    // Vérifier si la carte existe pour cet utilisateur
    if ($stmt->rowCount() > 0) {
        // Suppression de la carte
       deleteCb($dbh,$idCb);

        header('Location: compte.php?id='.$idUser.'?message=CbDel');
    } else {
        header('Location: compte.php?id='.$idUser.'?message=NotAllowed');
    }
} else {
    header('Location: compte.php?id='.$idUser.'?message=InvalidId');
}
