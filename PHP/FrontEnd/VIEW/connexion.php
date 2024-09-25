<?php
session_start(); // Démarrer la session

// Inclure les fonctions
include '../../Fonction/element.php';
include '../../Fonction/conf.php';
include '../../Fonction/db.php';
include '../../Fonction/auth.php';

$dbh = connexion_bdd();


$errorMessage = ''; // Variable pour stocker le message d'erreur


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {

        // Préparer la requête SQL pour récupérer l'utilisateur
        $stmt = $dbh->prepare("SELECT utilisateur_id, utilisateur_mdp, role.role_id FROM utilisateur , role WHERE utilisateur.role_id = role.role_id AND utilisateur_email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        // Vérifier si l'utilisateur existe
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // Vérifier le mot de passe
            if (password_verify($password, $user['utilisateur_mdp'])) {
                // Initialiser la session
                $_SESSION['utilisateur_id'] = $user['utilisateur_id'];
                $_SESSION['role_id'] = $user['role_id'];

                // Rediriger vers une page sécurisée
                header("Location: index.php");
                exit();
            } else {
                $errorMessage = "Mot de passe incorrect.";
            }
        } else {
            $errorMessage = "Adresse e-mail non trouvée.";
        }
    } else {
        $errorMessage = "Veuillez remplir tous les champs.";
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
<style>
    .password-container {
        position: relative;
    }

    .password-container input {
        width: 100%;
        padding-right: 2.5rem;
    }

    .password-container .toggle-password {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        padding: 0.5rem;
    }
</style>

<body>
<?php afficherNavbar($dbh); ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center mb-4">
                <img src="../IMAGE/newVet-logo.png" alt="Logo" style="max-width: 150px;">
            </div>

            <!-- Affichage du message d'erreur -->
            <?php if (!empty($errorMessage)) : ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($errorMessage); ?>
                </div>
            <?php endif; ?>

            <!-- Formulaire de connexion -->
            <form method="POST" action="connexion.php">
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse e-mail</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Votre e-mail"
                           required>
                </div>
                <div class="mb-3 password-container">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe"
                           required>
                    <span class="toggle-password" onclick="togglePassword()">
                            <i class="bi bi-eye"></i> 
                        </span>
                    <div class="text mt-1">
                        <a href="forgot_password.php">Mot de passe oublié ?</a>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Se connecter</button>
                </div>
            </form>

            <div class="text-center mt-4">
                <p>Pas encore de compte ?</p>
                <a href="inscription.php" class="btn btn-outline-secondary">Créer le vôtre !</a>
            </div>
        </div>
    </div>
    <?php afficherFooter(); ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoDa5a9F0QwS5c5N9zdoSm+NYzFJ8huFt1dJRJZ1HA+famK"
        crossorigin="anonymous"></script>
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