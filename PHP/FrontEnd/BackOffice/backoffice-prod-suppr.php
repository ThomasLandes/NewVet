<?php
include '../../Fonction/db.php';
include '../../Fonction/conf.php';
include '../../Fonction/auth.php';

autoriserOnlyAdmin();

// Connexion à la base de données
$dbh = connexion_bdd();

// Vérifier que l'ID est passé dans l'URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $produitId = intval($_GET['id']);

    try {
        // Démarrer une transaction
        $dbh->beginTransaction();

        // Supprimer les associations d'images pour ce produit
        $stmt = $dbh->prepare("DELETE FROM illustration_produit WHERE produit_id = :produit_id");
        $stmt->bindParam(':produit_id', $produitId);
        $stmt->execute();

        // Supprimer les associations de matériaux pour ce produit
        $stmt = $dbh->prepare("DELETE FROM composition WHERE produit_id = :produit_id");
        $stmt->bindParam(':produit_id', $produitId);
        $stmt->execute();

        // Supprimer le produit
        $stmt = $dbh->prepare("DELETE FROM produit WHERE produit_id = :produit_id");
        $stmt->bindParam(':produit_id', $produitId);
        $stmt->execute();

        // Commit la transaction
        $dbh->commit();

        // Redirection avec succès
        header("Location: backoffice-produit.php?success=2");
        exit;
    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        $dbh->rollBack();

        // Afficher un message d'erreur
        $error = "Erreur lors de la suppression du produit. Veuillez réessayer.";
    }
} else {
    // Redirection en cas d'ID invalide
    header("Location: backoffice-produit.php?error=1");
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Suppression du Produit</title>
  <link rel="stylesheet" href="path_to_bootstrap.css">
</head>
<body>
  <div class="container mt-5">
    <!-- Lien de retour -->
    <a href="backoffice-produit.php" class="text-decoration-none text-dark mb-4 d-inline-flex align-items-center">
      <i class="bi bi-arrow-left me-2"></i> Retour
    </a>

    <h1 class="h2">Suppression du Produit</h1>

    <!-- Affichage des messages d'erreur -->
    <?php if (isset($error)): ?>
      <div class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>

    <!-- Affichage du message de succès --
