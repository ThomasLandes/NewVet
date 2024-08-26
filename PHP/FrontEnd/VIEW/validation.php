<?php
include '../../Fonction/element.php';
include '../../Fonction/conf.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Connexion à la base de données
    $dbh = connexion_bdd();

    // Vérifier si le token existe dans la base de données
    $sql = "SELECT * FROM utilisateur WHERE utilisateur_token = :token AND utilisateur_is_valide = 0";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Token valide, activation du compte
        $sql = "UPDATE utilisateur SET utilisateur_is_valide = 1, utilisateur_token = NULL WHERE utilisateur_id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id', $user['utilisateur_id']);
        $stmt->execute();

        $message = "Votre compte a été activé avec succès. Vous allez être redirigé vers la page de connexion.";
    } else {
        $message = "Lien de vérification invalide ou compte déjà activé.";
    }
} else {
    $message = "Token non fourni.";
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Validation du compte</title>
    <?php headerElementPrint(); ?>
</head>
<body>
<?php afficherNavbar(); ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center mb-4">
                <img src="../IMAGE/newVet-logo.png" alt="Logo" style="max-width: 150px;">
            </div>

            <!-- Affichage du message -->
            <div class="alert alert-success">
                <?php echo htmlspecialchars($message); ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Redirection après 5 secondes
    setTimeout(function() {
        window.location.href = 'connexion.php';
    }, 4000);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoDa5a9F0QwS5c5N9zdoSm+NYzFJ8huFt1dJRJZ1HA+famK" crossorigin="anonymous"></script>
</body>
</html>
