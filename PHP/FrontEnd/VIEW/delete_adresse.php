<?php

include '../../Fonction/db.php';
include '../../Fonction/conf.php';
include '../../Fonction/auth.php';

// Connexion à la base de données
$dbh = connexion_bdd();
$idAdr = isset($_GET['id']) ? $_GET['id'] : null;

$info = getUserInfo();
$idUser = $info['user_id'];
$check = recupAdresseSpecifique($dbh, $idAdr, $info['user_id']);
if ($idAdr !== null) {
    // Préparer la requête SQL pour vérifier que l'adresse appartient à l'utilisateur
    $sql = "SELECT * FROM adresse WHERE adresse_id = :idAdr AND utilisateur_id = :idUser";

    // Utilisation de PDO pour sécuriser la requête
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':idAdr', $idAdr, PDO::PARAM_INT);
    $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    $stmt->execute();

    // Vérifier si l'adresse existe pour cet utilisateur
    if ($stmt->rowCount() > 0) {
        deleteAdresse($dbh,$idAdr);
        header('Location: compte.php?message=AdrDel');
    } else  header('Location: compte.php?message=NotAllowed');
}
