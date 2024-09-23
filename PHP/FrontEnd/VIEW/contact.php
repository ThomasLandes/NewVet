<?php
include '../../Fonction/element.php';
include '../../Fonction/conf.php';
include '../../Fonction/db.php';
include '../../Fonction/auth.php';

$dbh = connexion_bdd();
$message_sent = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    if (!empty($nom) && !empty($email) && !empty($message)) {
        $sql = "INSERT INTO contact (contact_nom, contact_email, contact_message) VALUES (:nom, :email, :message)";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':message', $message);

        if ($stmt->execute()) {
            $message_sent = true;
        }
    }
}
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contactez-nous</title>
    <?php headerElementPrint(); ?>
</head>
<body>
<?php afficherNavbar($dbh); ?>

<div class="container mt-5">
    <h1>Contactez-nous</h1>

    <?php if ($message_sent): ?>
        <div class="alert alert-success" role="alert">
            Votre message a été envoyé avec succès.
        </div>
    <?php else: ?>
        <form method="POST" action="contact.php">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
    <?php endif; ?>
</div>

<?php afficherFooter(); ?>
</body>
</html>
