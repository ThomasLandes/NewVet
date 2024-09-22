<?php
include '../../Fonction/element.php';
include '../../Fonction/db.php';
include '../../Fonction/conf.php';
include '../../Fonction/auth.php';

autoriserOnlyAdmin();
$dbh = connexion_bdd();

// Récupérer l'ID du matériau à modifier
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: backoffice-materiau.php');
    exit();
}

$materiauId = $_GET['id'];

// Requête pour récupérer les détails du matériau actuel
$sql = "SELECT materiau_nom FROM materiau WHERE materiau_id = :materiau_id";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':materiau_id', $materiauId, PDO::PARAM_INT);
$stmt->execute();
$materiau = $stmt->fetch(PDO::FETCH_ASSOC);

// Si le matériau n'existe pas, rediriger
if (!$materiau) {
    header('Location: backoffice-materiau.php?error=notfound');
    exit();
}

// Vérification et mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nouveauNom = trim($_POST['materiau_nom']);

    // Vérifier que le champ n'est pas vide
    if (empty($nouveauNom)) {
        header("Location: backoffice-mat-modif.php?id=$materiauId&error=empty");
        exit();
    }

    // Vérifier si le nom existe déjà
    $sqlCheck = "SELECT COUNT(*) FROM materiau WHERE materiau_nom = :materiau_nom AND materiau_id != :materiau_id";
    $stmtCheck = $dbh->prepare($sqlCheck);
    $stmtCheck->bindValue(':materiau_nom', $nouveauNom, PDO::PARAM_STR);
    $stmtCheck->bindValue(':materiau_id', $materiauId, PDO::PARAM_INT);
    $stmtCheck->execute();
    $nomExistant = $stmtCheck->fetchColumn();

    if ($nomExistant > 0) {
        // Le nom existe déjà, redirection avec un message d'erreur
        header("Location: backoffice-mat-modif.php?id=$materiauId&error=exists");
        exit();
    }

    // Si tout est bon, mettre à jour le nom du matériau
    $sqlUpdate = "UPDATE materiau SET materiau_nom = :materiau_nom WHERE materiau_id = :materiau_id";
    $stmtUpdate = $dbh->prepare($sqlUpdate);
    $stmtUpdate->bindValue(':materiau_nom', $nouveauNom, PDO::PARAM_STR);
    $stmtUpdate->bindValue(':materiau_id', $materiauId, PDO::PARAM_INT);

    if ($stmtUpdate->execute()) {
        // Redirection avec un message de succès
        header('Location: backoffice-materiau.php?success=modif');
        exit();
    } else {
        // En cas d'échec
        header("Location: backoffice-mat-modif.php?id=$materiauId&error=update");
        exit();
    }
}
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier Matériau</title>
    <?php BO_headerElementPrint();?>
</head>
<body>
<?php BO_afficherNavbar(); ?>
<?php BO_ContenuDashboardOuverture(); ?>
<div class="container mt-5">
    <h1>Modifier le Matériau</h1>

    <!-- Affichage des messages d'erreur -->
    <?php if (isset($_GET['error']) && $_GET['error'] == 'empty'): ?>
        <div class="alert alert-danger">Le nom du matériau ne peut pas être vide.</div>
    <?php elseif (isset($_GET['error']) && $_GET['error'] == 'exists'): ?>
        <div class="alert alert-danger">Ce nom de matériau existe déjà.</div>
    <?php elseif (isset($_GET['error']) && $_GET['error'] == 'update'): ?>
        <div class="alert alert-danger">Une erreur est survenue lors de la mise à jour du matériau.</div>
    <?php endif; ?>

    <!-- Formulaire de modification du matériau -->
    <form method="POST" action="">
        <div class="mb-3">
            <label for="materiau_nom" class="form-label">Nom du Matériau</label>
            <input type="text" class="form-control" id="materiau_nom" name="materiau_nom" value="<?php echo htmlspecialchars($materiau['materiau_nom']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Modifier</button>
        <a href="backoffice-materiau.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</body>
</html>

