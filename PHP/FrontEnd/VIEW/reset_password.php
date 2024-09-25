<?php
session_start();
include '../../Fonction/element.php';
include '../../Fonction/conf.php';
include '../../Fonction/db.php';
include '../../Fonction/auth.php';

$dbh = connexion_bdd();
$errorMessage = '';
$successMessage = '';

$token = $_GET['token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer le token et le nouveau mot de passe
    $newPassword = trim($_POST['password']);

    // Valider le mot de passe (au moins 12 caractères, une majuscule, un chiffre, un caractère spécial)
    if (preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{10,}$/', $newPassword)) {
        // Vérifier le token
        $stmt = $dbh->prepare("SELECT utilisateur_id, reset_token_expire FROM utilisateur WHERE reset_token = :token");
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérifier si le token n'a pas expiré
            if (strtotime($user['reset_token_expire']) > time()) {
                // Hashage du nouveau mot de passe
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

                // Mettre à jour le mot de passe
                $update = $dbh->prepare("UPDATE utilisateur SET utilisateur_mdp = :password, reset_token = NULL, reset_token_expire = NULL WHERE utilisateur_id = :id");
                $update->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
                $update->bindParam(':id', $user['utilisateur_id'], PDO::PARAM_INT);

                if ($update->execute()) {
                    $successMessage = "Votre mot de passe a été réinitialisé avec succès.";
                } else {
                    $errorMessage = "Une erreur est survenue lors de la réinitialisation du mot de passe.";
                }
            } else {
                $errorMessage = "Le token de réinitialisation a expiré.";
            }
        } else {
            $errorMessage = "Token invalide.";
        }
    } else {
        $errorMessage = "Le mot de passe doit contenir au moins 10 caractères, une majuscule, un chiffre, et un caractère spécial.";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Réinitialiser le mot de passe</title>
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

            <!-- Formulaire de réinitialisation du mot de passe -->
            <form method="POST" action="reset_password.php?token=<?=$token?>">
                <!-- Conserver le token dans un champ caché -->
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_POST['token'] ?? $_GET['token'] ?? ''); ?>">
                <div class="mb-3">
                    <label for="password" class="form-label">Nouveau mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Réinitialiser</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php afficherFooter(); ?>
</body>
</html>
