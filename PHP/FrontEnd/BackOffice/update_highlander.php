<?php
include '../../Fonction/db.php';
include '../../Fonction/conf.php';
include '../../Fonction/auth.php';
autoriserOnlyAdmin();

$dbh = connexion_bdd();

// Vérifier que les données sont présentes
if (isset($_POST['produit_id']) && isset($_POST['highlander'])) {
    $produitId = intval($_POST['produit_id']);
    $isHighlander = intval($_POST['highlander']);

    // Préparer et exécuter la requête de mise à jour
    $stmt = $dbh->prepare("UPDATE produit SET produit_highlander = :highlander WHERE produit_id = :produit_id");
    $stmt->bindParam(':highlander', $isHighlander, PDO::PARAM_INT);
    $stmt->bindParam(':produit_id', $produitId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo 'Success';
    } else {
        echo 'Error';
    }
} else {
    echo 'Invalid Request';
}
?>
