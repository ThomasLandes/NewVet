<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\htdocs\Projet\NewVet\PHP\PHPMailer\src\Exception.php';
require 'C:\xampp\htdocs\Projet\NewVet\PHP\PHPMailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\Projet\NewVet\PHP\PHPMailer\src\SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $to = $_POST['email'];

  $mail = new PHPMailer(true);
  try {
      // Paramètres du serveur
      $mail->isSMTP();
      $mail->Host = 'smtp.office365.com';         // Serveur SMTP de Outlook
      $mail->SMTPAuth = true;
      $mail->Username = 'verification.newvet@outlook.com'; // Votre adresse e-mail Outlook
      $mail->Password = 'NewVet31!';      // Votre mot de passe Outlook
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = 587;

      // Destinataire(s)
      $mail->setFrom('verification.newvet@outlook.com', 'NEW VET');
      $mail->addAddress($to);

      // Contenu de l'e-mail
      $mail->isHTML(true);
      $mail->Subject = 'Test Mail from Localhost using Outlook';
      $mail->Body    = 'Ceci est un e-mail de test envoyé depuis un serveur local utilisant XAMPP et Outlook.';
      $mail->AltBody = 'Ceci est un e-mail de test envoyé depuis un serveur local utilisant XAMPP et Outlook.';

      $mail->send();
      $resultMessage = "E-mail envoyé avec succès à $to!";
  } catch (Exception $e) {
      $resultMessage = "Échec de l'envoi de l'e-mail. Erreur : {$mail->ErrorInfo}";
  }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test d'envoi d'e-mail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <div class="container mt-5">
      <h1 class="text-center">Test d'envoi d'e-mail</h1>
      <div class="row justify-content-center">
        <div class="col-md-6">
          <!-- Formulaire pour saisir l'adresse e-mail -->
          <form method="POST" action="">
            <div class="mb-3">
              <label for="email" class="form-label">Adresse e-mail</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Entrez l'adresse e-mail" required>
            </div>
            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-primary">Envoyer un e-mail de test</button>
            </div>
          </form>

          <!-- Message de résultat -->
          <?php if (isset($resultMessage)): ?>
            <div class="alert alert-info mt-3" role="alert">
              <?php echo $resultMessage; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>