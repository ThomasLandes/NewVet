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
    $password = trim($_POST['password']);

    // Validation de l'email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Validation du mot de passe (au moins 12 caractères, une majuscule, un chiffre, un caractère spécial)
        if (preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{12,}$/', $password)) {


            // Vérifier si l'email est déjà utilisé
            $stmt = $dbh->prepare("SELECT utilisateur_id FROM utilisateur WHERE utilisateur_email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                // Génération d'un token de vérification
                $token = bin2hex(random_bytes(32));

                // Hashage du mot de passe
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // Insertion dans la base de données
                $stmt = $dbh->prepare("INSERT INTO utilisateur (utilisateur_email, utilisateur_mdp, utilisateur_token, role_id) VALUES (:email, :password, :token, 2)"); // 2 pour rôle utilisateur par défaut
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
                $stmt->bindParam(':token', $token, PDO::PARAM_STR);
                
                if ($stmt->execute()) {
                    // Envoi de l'e-mail de vérification
                    $successMessage = sendVerifMail($email, $token);
                } else {
                    $errorMessage = "Une erreur est survenue lors de l'inscription. Veuillez réessayer.";
                }
            } else {
                $errorMessage = "Cette adresse e-mail est déjà utilisée.";
            }
        } else {
            $errorMessage = "Le mot de passe doit contenir au moins 12 caractères, une majuscule, un chiffre, et un caractère spécial.";
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
    <title>Inscription</title>
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

            <!-- Formulaire d'inscription -->
            <form method="POST" action="inscription.php">
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse e-mail</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Votre e-mail" required>
                </div>
                <div class="mb-3 password-container">
                        <label for="password" class="form-label">Mot de passe</label>
                        <span class="toggle-password" onclick="togglePassword()">
                            <i class="bi bi-eye"></i> 
                        </span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Choisissez un mot de passe" required>
                        
                    </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">S'inscrire</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
        function togglePassword() {
            var passwordInput = document.getElementById('password');
            var toggleIcon = document.querySelector('.toggle-password i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>
