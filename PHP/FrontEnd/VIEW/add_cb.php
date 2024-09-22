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

// Traitement de l'ajout de carte
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $numero = $_POST['numero'];
    $date_exp = $_POST['date_exp'];
    $is_principal = isset($_POST['is_principal']) ? 1 : 0;

    // Vérifier si la carte ajoutée doit être principale
    if ( $is_principal == 1) {
        // Mettre à jour les autres cartes pour qu'elles ne soient plus principales
        $sqlUpdate = "UPDATE paiement SET is_principal = 0 WHERE utilisateur_id = :utilisateur_id";
        $updateStmt = $dbh->prepare($sqlUpdate);
        $updateStmt->bindParam(':utilisateur_id', $userInfo['user_id'], PDO::PARAM_INT);
        $updateStmt->execute();
    }

        // Stocker uniquement les 4 derniers chiffres
        $numero_visible = substr($numero, -4);

        // Préparer la requête d'insertion
        $query = $dbh->prepare("INSERT INTO paiement (paiement_nom, paiement_numero, paiement_date_exp, is_principal, utilisateur_id) VALUES (:nom, :numero, :date_exp, :is_principal, :user_id)");
        $query->bindParam(':nom', $nom, PDO::PARAM_STR);
        $query->bindParam(':numero', $numero_visible, PDO::PARAM_STR);
        $query->bindParam(':date_exp', $date_exp, PDO::PARAM_STR);
        $query->bindParam(':is_principal', $is_principal, PDO::PARAM_INT);
        $query->bindParam(':user_id', $userInfo['user_id'], PDO::PARAM_INT);

        if ($query->execute()) {
            header('Location: compte.php?id=' . $userInfo['user_id']);
            exit();
        } else {
            $error = "Erreur lors de l'ajout de la carte.";
        }
    }
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajouter une carte bancaire</title>
    <?php headerElementPrint(); ?>
</head>

<body>
<?php afficherNavbar($dbh); ?>

<div class="container mt-5">
    <h3>Ajouter un moyen de paiement</h3>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom sur la carte</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
        </div>
        <div class="mb-3">
            <label for="numero" class="form-label">Numéro de carte (16 chiffres)</label>
            <input type="text" class="form-control" id="numero" name="numero" maxlength="16" required pattern="\b\d{16}\b">
        </div>
        <div class="mb-3">
            <label for="date_exp" class="form-label">Date d'expiration (MM/AA)</label>
            <input type="text" class="form-control" id="date_exp" name="date_exp" required placeholder="MM/AA" pattern="^(0[1-9]|1[0-2])\/?([0-9]{2})$">
        </div>
        <div class="mb-3">
            <input type="checkbox" id="is_principal" name="is_principal">
            <label for="is_principal">Définir comme carte principale</label>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
        <a href="compte.php?id=<?php echo $userInfo['user_id']; ?>" class="btn btn-secondary">Annuler</a>
    </form>
</div>

</body>
</html>
