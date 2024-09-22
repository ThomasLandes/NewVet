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

$userId= $_SESSION['utilisateur_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $utilisateur_id = $_SESSION['utilisateur_id'];
    $adresse_nom = $_POST['adresse_nom'];
    $adresse_prenom = $_POST['adresse_prenom'];
    $adresse_rue = $_POST['adresse_rue'];
    $adresse_complement = $_POST['adresse_complement'];
    $adresse_ville = $_POST['adresse_ville'];
    $adresse_region = $_POST['adresse_region'];
    $adresse_pays = $_POST['adresse_pays'];
    $adresse_tel = $_POST['adresse_tel'];
    $is_facture = isset($_POST['is_facture']) ? 1 : 0;
    $is_principal = isset($_POST['is_principal']) ? 1 : 0;

    // Validation des champs obligatoires
    if (empty($adresse_nom) || empty($adresse_prenom) || empty($adresse_rue) ||
        empty($adresse_complement) || empty($adresse_ville) ||
        empty($adresse_region) || empty($adresse_pays)) {
        $error_message = "Veuillez remplir tous les champs obligatoires.";
    } else {
        // Insérer l'adresse dans la base de données
        $sql = "INSERT INTO adresse (utilisateur_id, adresse_nom, adresse_prenom, adresse_rue, 
                  adresse_complement, adresse_ville, adresse_region, adresse_pays, 
                  adresse_tel, is_facture, is_principal) 
                  VALUES (:utilisateur_id, :adresse_nom, :adresse_prenom, :adresse_rue, 
                  :adresse_complement, :adresse_ville, :adresse_region, :adresse_pays, 
                  :adresse_tel, :is_facture, :is_principal)";

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':utilisateur_id', $utilisateur_id, PDO::PARAM_INT);
        $stmt->bindParam(':adresse_nom', $adresse_nom);
        $stmt->bindParam(':adresse_prenom', $adresse_prenom);
        $stmt->bindParam(':adresse_rue', $adresse_rue);
        $stmt->bindParam(':adresse_complement', $adresse_complement);
        $stmt->bindParam(':adresse_ville', $adresse_ville);
        $stmt->bindParam(':adresse_region', $adresse_region);
        $stmt->bindParam(':adresse_pays', $adresse_pays);
        $stmt->bindParam(':adresse_tel', $adresse_tel);
        $stmt->bindParam(':is_facture', $is_facture, PDO::PARAM_BOOL);
        $stmt->bindParam(':is_principal', $is_principal, PDO::PARAM_BOOL);

        if ($stmt->execute()) {
            header('Location: compte.php?message=Adresse ajoutée avec succès');
            exit();
        } else {
            $error_message = "Erreur lors de l'ajout de l'adresse.";
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mon Compte</title>
    <?php headerElementPrint(); ?>
</head>

<body>
<?php
afficherNavbar($dbh);
?>
<div class="container mt-5">
    <h1>Ajouter une nouvelle adresse</h1>
    <?php if (isset($error_message)) : ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php endif; ?>
    <form action="add_adresse.php" method="POST">
        <div class="mb-3">
            <label for="adresse_nom" class="form-label">Nom *</label>
            <input type="text" class="form-control" id="adresse_nom" name="adresse_nom" required>
        </div>
        <div class="mb-3">
            <label for="adresse_prenom" class="form-label">Prénom *</label>
            <input type="text" class="form-control" id="adresse_prenom" name="adresse_prenom" required>
        </div>
        <div class="mb-3">
            <label for="adresse_rue" class="form-label">Rue *</label>
            <input type="text" class="form-control" id="adresse_rue" name="adresse_rue" required>
        </div>
        <div class="mb-3">
            <label for="adresse_complement" class="form-label">Complément d'adresse</label>
            <input type="text" class="form-control" id="adresse_complement" name="adresse_complement">
        </div>
        <div class="mb-3">
            <label for="adresse_ville" class="form-label">Ville *</label>
            <input type="text" class="form-control" id="adresse_ville" name="adresse_ville" required>
        </div>
        <div class="mb-3">
            <label for="adresse_region" class="form-label">Code postal *</label>
            <input type="text" class="form-control" id="adresse_region" name="adresse_region" required pattern="\b\d{5}\b" maxlength="5" title="Veuillez entrer un code postal de 5 chiffres">
        </div>
        <div class="mb-3">
            <label for="adresse_pays" class="form-label">Pays *</label>
            <input type="text" class="form-control" id="adresse_pays" name="adresse_pays" required>
        </div>
        <div class="mb-3">
            <label for="adresse_tel" class="form-label">Téléphone</label>
            <input type="text" class="form-control" id="adresse_tel" name="adresse_tel">
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="is_facture" name="is_facture">
            <label class="form-check-label" for="is_facture">Adresse de facturation</label>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="is_principal" name="is_principal">
            <label class="form-check-label" for="is_principal">Définir comme adresse principale</label>
        </div>
        <button type="submit" class="btn btn-success">Ajouter l'adresse</button>
    </form>
</div>
</body>
</html>
