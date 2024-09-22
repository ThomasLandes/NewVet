<?php
include '../../Fonction/element.php';
include '../../Fonction/conf.php';
include '../../Fonction/db.php';
include '../../Fonction/auth.php';

$dbh = connexion_bdd();
$idAdr = isset($_GET['id']) ? $_GET['id'] : null;

$info = getUserInfo();
$idUser = $info['user_id'];

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
        // Récupérer les informations de l'adresse
        $adresse = $stmt->fetch(PDO::FETCH_ASSOC);

        // Traitement du formulaire de modification
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
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

// verification unicité des champs is_principal et is_facture
            $checkSql = "SELECT * FROM adresse WHERE utilisateur_id = :idUser AND adresse_id != :idAdr AND is_facture = :is_facture";
            $stmt = $dbh->prepare($checkSql);
            $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $stmt->bindParam(':idAdr', $idAdr, PDO::PARAM_INT);
            $stmt->bindParam(':is_facture', $is_facture, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Réinitialiser l'ancienne adresse
                $updateOtherSql = "UPDATE adresse SET is_facture = 0 WHERE utilisateur_id = :idUser AND is_facture = :is_facture";
                $updateStmt = $dbh->prepare($updateOtherSql);
                $updateStmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
                $updateStmt->bindParam(':is_facture', $is_facture, PDO::PARAM_INT);
                $updateStmt->execute();
            }

            $checkSql = "SELECT * FROM adresse WHERE utilisateur_id = :idUser AND adresse_id != :idAdr AND is_principal = :is_principal";
            $stmt = $dbh->prepare($checkSql);
            $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $stmt->bindParam(':idAdr', $idAdr, PDO::PARAM_INT);
            $stmt->bindParam(':is_principal', $is_principal, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Réinitialiser l'ancienne adresse
                $updateOtherSql = "UPDATE adresse SET  is_principal = 0 WHERE utilisateur_id = :idUser AND is_principal = :is_principal";
                $updateStmt = $dbh->prepare($updateOtherSql);
                $updateStmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
                $updateStmt->bindParam(':is_principal', $is_principal, PDO::PARAM_INT);
                $updateStmt->execute();
            }


            // Préparer la requête SQL pour mettre à jour l'adresse
            $updateSql = "UPDATE adresse SET adresse_nom = :adresse_nom, adresse_prenom = :adresse_prenom, 
                          adresse_rue = :adresse_rue, adresse_complement = :adresse_complement, 
                          adresse_ville = :adresse_ville, adresse_region = :adresse_region, 
                          adresse_pays = :adresse_pays, adresse_tel = :adresse_tel, 
                          is_facture = :is_facture, is_principal = :is_principal 
                          WHERE adresse_id = :idAdr";

            $updateStmt = $dbh->prepare($updateSql);
            $updateStmt->bindParam(':adresse_nom', $adresse_nom);
            $updateStmt->bindParam(':adresse_prenom', $adresse_prenom);
            $updateStmt->bindParam(':adresse_rue', $adresse_rue);
            $updateStmt->bindParam(':adresse_complement', $adresse_complement);
            $updateStmt->bindParam(':adresse_ville', $adresse_ville);
            $updateStmt->bindParam(':adresse_region', $adresse_region);
            $updateStmt->bindParam(':adresse_pays', $adresse_pays);
            $updateStmt->bindParam(':adresse_tel', $adresse_tel);
            $updateStmt->bindParam(':is_facture', $is_facture, PDO::PARAM_BOOL);
            $updateStmt->bindParam(':is_principal', $is_principal, PDO::PARAM_BOOL);
            $updateStmt->bindParam(':idAdr', $idAdr, PDO::PARAM_INT);

            if ($updateStmt->execute()) {
                // Rediriger vers la page de compte avec un message de succès
                header('Location: compte.php?message=Adresse modifiée avec succès');
                exit();
            } else {
                $error_message = "Erreur lors de la mise à jour de l'adresse.";
            }
        }
    } else {
        // Rediriger ou afficher un message d'erreur si l'adresse n'appartient pas à l'utilisateur
        header('Location: compte.php?error=Adresse non trouvée ou vous n\'avez pas l\'autorisation de la modifier.');
        exit();
    }
} else {
    // Rediriger ou afficher un message d'erreur si l'ID d'adresse est manquant
    header('Location: compte.php?error=Adresse invalide.');
    exit();
}
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier l'adresse</title>
    <?php headerElementPrint(); ?>
</head>

<body>
<?php afficherNavbar($dbh); ?>

<div class="container mt-5">
    <h1>Modifier l'adresse</h1>
    <?php if (isset($error_message)) : ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php endif; ?>
    <form action="modif_adresse.php?id=<?php echo htmlspecialchars($idAdr); ?>" method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($adresse['adresse_id']); ?>">
        <div class="mb-3">
            <label for="adresse_nom" class="form-label">Nom *</label>
            <input type="text" class="form-control" id="adresse_nom" name="adresse_nom" value="<?php echo htmlspecialchars($adresse['adresse_nom']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="adresse_prenom" class="form-label">Prénom *</label>
            <input type="text" class="form-control" id="adresse_prenom" name="adresse_prenom" value="<?php echo htmlspecialchars($adresse['adresse_prenom']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="adresse_rue" class="form-label">Rue *</label>
            <input type="text" class="form-control" id="adresse_rue" name="adresse_rue" value="<?php echo htmlspecialchars($adresse['adresse_rue']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="adresse_complement" class="form-label">Complément d'adresse</label>
            <input type="text" class="form-control" id="adresse_complement" name="adresse_complement" value="<?php echo htmlspecialchars($adresse['adresse_complement']); ?>">
        </div>
        <div class="mb-3">
            <label for="adresse_ville" class="form-label">Ville *</label>
            <input type="text" class="form-control" id="adresse_ville" name="adresse_ville" value="<?php echo htmlspecialchars($adresse['adresse_ville']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="adresse_region" class="form-label">Région (code postal à 5 chiffres) *</label>
            <input type="text" class="form-control" id="adresse_region" name="adresse_region" value="<?php echo htmlspecialchars($adresse['adresse_region']); ?>" required pattern="\b\d{5}\b" maxlength="5" title="Veuillez entrer exactement 5 chiffres">
        </div>
        <div class="mb-3">
            <label for="adresse_pays" class="form-label">Pays *</label>
            <input type="text" class="form-control" id="adresse_pays" name="adresse_pays" value="<?php echo htmlspecialchars($adresse['adresse_pays']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="adresse_tel" class="form-label">Téléphone</label>
            <input type="text" class="form-control" id="adresse_tel" name="adresse_tel" value="<?php echo htmlspecialchars($adresse['adresse_tel']); ?>">
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="is_facture" name="is_facture" <?php echo $adresse['is_facture'] ? 'checked' : ''; ?>>
            <label class="form-check-label" for="is_facture">Adresse de facturation</label>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="is_principal" name="is_principal" <?php echo $adresse['is_principal'] ? 'checked' : ''; ?>>
            <label class="form-check-label" for="is_principal">Définir comme adresse principale</label>
        </div>
        <button type="submit" class="btn btn-success">Modifier l'adresse</button>
    </form>
</div>
</body>
</html>
