<?php
include '../../Fonction/element.php';
include '../../Fonction/db.php';
include '../../Fonction/conf.php';
include '../../Fonction/auth.php';

autoriserOnlyAdmin();
$dbh = connexion_bdd();

$error = '';
$success = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $materiauNom = trim($_POST['materiau_nom']);

    // Vérification si le champ n'est pas vide
    if (empty($materiauNom)) {
        $error = "Le nom du matériau est obligatoire.";
    } else {
        // Vérifier si le matériau existe déjà
        $sqlCheck = "SELECT COUNT(*) FROM materiau WHERE materiau_nom = :materiau_nom";
        $stmtCheck = $dbh->prepare($sqlCheck);
        $stmtCheck->bindValue(':materiau_nom', $materiauNom);
        $stmtCheck->execute();
        $existingMateriau = $stmtCheck->fetchColumn();

        if ($existingMateriau > 0) {
            $error = "Ce matériau existe déjà.";
        } else {
            // Insertion du nouveau matériau dans la base de données
            $sql = "INSERT INTO materiau (materiau_nom) VALUES (:materiau_nom)";
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':materiau_nom', $materiauNom);
            if ($stmt->execute()) {
                $success = "Le matériau a été ajouté avec succès.";
            } else {
                $error = "Une erreur est survenue lors de l'ajout du matériau.";
            }
        }
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajouter un Matériau - Back Office</title>
    <?php BO_headerElementPrint(); ?>
</head>
<body>
<?php BO_afficherNavbar(); ?>
<?php BO_ContenuDashboardOuverture(); ?>

<!-- Affichage des messages d'erreur ou de succès -->
<?php if (!empty($error)): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php elseif (!empty($success)): ?>
    <div class="alert alert-success" role="alert">
        <?php echo htmlspecialchars($success); ?>
    </div>
<?php endif; ?>

<!-- Formulaire d'ajout de matériau -->
<h1 class="h2">Ajouter un Matériau</h1>
<form action="backoffice-mat-ajout.php" method="post" class="mt-4">
    <div class="mb-3">
        <label for="materiau_nom" class="form-label">Nom du Matériau</label>
        <input type="text" class="form-control" id="materiau_nom" name="materiau_nom" value="<?php echo isset($_POST['materiau_nom']) ? htmlspecialchars($_POST['materiau_nom']) : ''; ?>" required>
    </div>
    <button type="submit" class="btn btn-success">Ajouter le Matériau</button>
    <a href="backoffice-materiau.php" class="btn btn-secondary">Retour</a>
</form>

<?php BO_ContenuDashboardFermeture(); ?>
</body>
</html>
