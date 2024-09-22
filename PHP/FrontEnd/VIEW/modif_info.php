<?php
include '../../Fonction/element.php';
include '../../Fonction/conf.php';
include '../../Fonction/db.php';
include '../../Fonction/auth.php';

$dbh = connexion_bdd();

// Vérifier si l'utilisateur est connecté
$userInfo = getUserInfo();

if ($userInfo === null) {
    // Si l'utilisateur n'est pas connecté, redirection vers la page de connexion
    header('Location: connexion.php');
    exit();
}

// Récupérer l'ID utilisateur de l'URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Vérifier si l'ID dans l'URL correspond à l'utilisateur connecté
if ($id !== $userInfo['user_id']) {
    // Si ce n'est pas le cas, rediriger l'utilisateur vers son propre compte
    header('Location: compte.php?id=' . $userInfo['user_id']);
    exit();
}

// Récupérer les informations actuelles de l'utilisateur
$query = $dbh->prepare("SELECT utilisateur_prenom, utilisateur_nom, utilisateur_tel FROM utilisateur WHERE utilisateur_id = :id");
$query->bindParam(':id', $id, PDO::PARAM_INT);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Utilisateur non trouvé.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Traitement de la mise à jour
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $tel = $_POST['tel'];

    $sql = $dbh->prepare("UPDATE utilisateur SET utilisateur_prenom = :prenom, utilisateur_nom = :nom, utilisateur_tel = :tel WHERE utilisateur_id = :id");
    $sql->bindParam(':prenom', $prenom, PDO::PARAM_STR);
    $sql->bindParam(':nom', $nom, PDO::PARAM_STR);
    $sql->bindParam(':tel', $tel, PDO::PARAM_STR);
    $sql->bindParam(':id', $id, PDO::PARAM_INT);

    if ($sql->execute()) {
        header("Location: compte.php?id=$id");
        exit();
    } else {
        echo "Erreur lors de la mise à jour.";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier les informations</title>
    <?php headerElementPrint(); ?>
</head>

<body>
<?php afficherNavbar($dbh); ?>

<div class="container mt-5">
    <h3>Modifier mes informations personnelles</h3>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo htmlspecialchars($user['utilisateur_prenom']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($user['utilisateur_nom']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="tel" class="form-label">Téléphone</label>
            <input type="text" class="form-control" id="tel" name="tel" value="<?php echo htmlspecialchars($user['utilisateur_tel']); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Valider les modifications</button>
        <a href="compte.php?id=<?php echo $id; ?>" class="btn btn-secondary">Annuler</a>
    </form>
</div>

</body>
</html>
