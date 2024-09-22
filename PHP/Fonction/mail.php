<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\htdocs\Projet\NewVet\PHP\PHPMailer\src\Exception.php';
require 'C:\xampp\htdocs\Projet\NewVet\PHP\PHPMailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\Projet\NewVet\PHP\PHPMailer\src\SMTP.php';




function sendVerifMail($email, $token) {
    $mail = new PHPMailer(true);
    try {
        // Paramètres du serveur
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'newvet.confirm@outlook.com'; // renseigner l'adresse ici
        $mail->Password = 'NewVet31!'; // le mot de passe ici
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Destinataire
        $mail->setFrom('newvet.confirm@outlook.com', 'NEW VET'); // de nouveau l'adresse
        $mail->addAddress($email);

        // Contenu de l'e-mail
        $mail->isHTML(true);
        $mail->Subject = 'Vérifiez votre adresse e-mail';
        $verificationLink = "http://localhost/Projet/NewVet/PHP/FrontEnd/VIEW/validation.php?token=" . $token;
        $mail->Body    = "Cliquez sur le lien suivant pour vérifier votre adresse e-mail : <a href='$verificationLink'>$verificationLink</a>";
        $mail->AltBody = "Cliquez sur le lien suivant pour vérifier votre adresse e-mail : $verificationLink";

        $mail->send();
        return "Un e-mail de vérification a été envoyé à $email. Veuillez vérifier votre boîte de réception.";
    } catch (Exception $e) {
        return "L'e-mail de vérification n'a pas pu être envoyé. Erreur : {$mail->ErrorInfo}";
    }
}
?>