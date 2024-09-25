<?php
session_start(); // Démarrer la session

// Inclure les éléments réutilisables
include '../../Fonction/element.php';
include '../../Fonction/conf.php';
include '../../Fonction/mail.php';
include '../../Fonction/db.php';
include '../../Fonction/auth.php';
// Connexion à la base de données
$dbh = connexion_bdd();

$errorMessage = ''; // Variable pour stocker les messages d'erreur
$successMessage = ''; // Variable pour stocker le message de succès

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $email = trim($_POST['email']);

    // Validation de l'email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Vérifier si l'email existe dans la base de données
        $stmt = $dbh->prepare("SELECT utilisateur_id FROM utilisateur WHERE utilisateur_email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            // Générer un token de réinitialisation
            $token = bin2hex(random_bytes(32));
            $expiration = date('Y-m-d H:i:s', strtotime('+1 hour')); // Le lien de réinitialisation expire dans 1 heure

            // Stocker le token dans la base de données
            $stmt = $dbh->prepare("UPDATE utilisateur SET reset_token = :token, reset_token_expire = :expire WHERE utilisateur_email = :email");
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->bindParam(':expire', $expiration, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);

            if ($stmt->execute()) {
                // Envoyer l'e-mail avec le lien de réinitialisation
                $successMessage = sendResetMail($email, $token);
            } else {
                $errorMessage = "Une erreur est survenue lors de la génération du lien de réinitialisation.";
            }
        } else {
            $errorMessage = "Aucun compte associé à cet e-mail.";
        }
    } else {
        $errorMessage = "Veuillez entrer une adresse e-mail valide.";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mot de passe oublié</title>
    <?php headerElementPrint(); ?>
</head>
<body>
<?php afficherNavbar($dbh); ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center mb-4">
                <img src="../IMAGE/newVet-logo.png" alt="Logo" style="max-width: 150px;">
            </div>

            <!-- Affichage des messages d'erreur ou de succès -->
            <?php if (!empty($errorMessage)) : ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($errorMessage); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($successMessage)) : ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($successMessage); ?>
                </div>
            <?php endif; ?>

            <!-- Formulaire pour demander la réinitialisation du mot de passe -->
            <form method="POST" action="forgot_password.php">
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse e-mail</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Votre e-mail" required>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Réinitialiser le mot de passe</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php afficherFooter(); ?>
</body>
</html>
