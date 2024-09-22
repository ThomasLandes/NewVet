<?php
include '../../Fonction/element.php';
include '../../Fonction/db.php';
include '../../Fonction/conf.php';
include '../../Fonction/auth.php';

autoriserOnlyAdmin();
$dbh = connexion_bdd();

// Récupérer l'ID du matériau à supprimer
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: backoffice-materiau.php');
    exit();
}

$materiauId = $_GET['id'];

// Vérifier si le matériau est utilisé dans une composition
$sqlCheck = "SELECT COUNT(*) FROM composition WHERE materiau_id = :materiau_id";
$stmtCheck = $dbh->prepare($sqlCheck);
$stmtCheck->bindValue(':materiau_id', $materiauId, PDO::PARAM_INT);
$stmtCheck->execute();
$compositionCount = $stmtCheck->fetchColumn();

if ($compositionCount > 0) {
    // Le matériau est utilisé dans une composition, redirection avec un message d'erreur
    header('Location: backoffice-materiau.php?error=1');
    exit();
}

// Si le matériau n'est pas utilisé dans une composition, on peut le supprimer
$sqlDelete = "DELETE FROM materiau WHERE materiau_id = :materiau_id";
$stmtDelete = $dbh->prepare($sqlDelete);
$stmtDelete->bindValue(':materiau_id', $materiauId, PDO::PARAM_INT);

if ($stmtDelete->execute()) {
    // Redirection avec un message de succès
    header('Location: backoffice-materiau.php?success=1');
    exit();
} else {
    // En cas d'échec de la suppression
    header('Location: backoffice-materiau.php?error=2');
    exit();
}
