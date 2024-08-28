<?php
include '../../Fonction/db.php';
include '../../Fonction/conf.php';

// Connexion à la base de données
$dbh = connexion_bdd();

// Vérifier si un ID est passé dans l'URL
if (isset($_GET['id'])) {
    $categorie_id = intval($_GET['id']);

    // Préparer la requête de suppression
    $stmt = $dbh->prepare("DELETE FROM categorie WHERE categorie_id = :id");

    // Lier l'ID de la catégorie
    $stmt->bindParam(':id', $categorie_id, PDO::PARAM_INT);

    // Essayer d'exécuter la requête
    if ($stmt->execute()) {
        // Rediriger vers la page de gestion avec un message de succès
        header("Location: backoffice-categorie.php?success=1");
        exit;
    } else {
        // Rediriger vers la page de gestion avec un message d'erreur
        header("Location: backoffice-categorie.php?error=1");
        exit;
    }
} else {
    // Si l'ID n'est pas défini, rediriger vers la page de gestion
    header("Location: backoffice-categorie.php");
    exit;
}
?>
