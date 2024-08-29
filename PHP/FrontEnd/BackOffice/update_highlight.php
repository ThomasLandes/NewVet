<?php
include '../../Fonction/db.php';
include '../../Fonction/conf.php';
include '../../Fonction/auth.php';
autoriserOnlyAdmin();


$dbh = connexion_bdd();

// Vérifier si les paramètres sont définis
if (isset($_POST['categorie_id']) && isset($_POST['highlight'])) {
    $categorieId = $_POST['categorie_id'];
    $highlight = $_POST['highlight'] ? 1 : 0;

    // Préparer et exécuter la requête SQL pour mettre à jour le statut
    $sql = "UPDATE categorie SET categorie_highlight = :highlight WHERE categorie_id = :categorie_id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':highlight', $highlight, PDO::PARAM_INT);
    $stmt->bindParam(':categorie_id', $categorieId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Succès
        echo 'OK';
    } else {
        // Erreur
        echo 'Erreur';
    }
} else {
    // Paramètres manquants
    echo 'Paramètres manquants';
}
?>
