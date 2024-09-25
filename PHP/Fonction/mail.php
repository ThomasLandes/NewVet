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
        $mail->CharSet = 'UTF-8';
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

function sendResetMail($email, $token) {
    $mail = new PHPMailer(true);
    try {
        // Paramètres du serveur
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'newvet.confirm@outlook.com';
        $mail->Password = 'NewVet31!';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Destinataire
        $mail->setFrom('newvet.confirm@outlook.com', 'NEW VET');
        $mail->addAddress($email);

        // Contenu de l'e-mail
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Subject = 'Réinitialisez votre mot de passe';
        $resetLink = "http://localhost/Projet/NewVet/PHP/FrontEnd/VIEW/reset_password.php?token=" . $token;
        $mail->Body    = "Cliquez sur le lien suivant pour réinitialiser votre mot de passe : <a href='$resetLink'>$resetLink</a>";
        $mail->AltBody = "Cliquez sur le lien suivant pour réinitialiser votre mot de passe : $resetLink";

        $mail->send();
        return "Un e-mail de réinitialisation a été envoyé à $email. Veuillez vérifier votre boîte de réception.";
    } catch (Exception $e) {
        return "L'e-mail de réinitialisation n'a pas pu être envoyé. Erreur : {$mail->ErrorInfo}";
    }
}

function envoyerEmailConfirmation($email, $commandeId, $totalPrix)
{
    $mail = new PHPMailer(true);
    try {
        // Paramètres du serveur
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'newvet.confirm@outlook.com';
        $mail->Password = 'NewVet31!';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Destinataires
        // Destinataire
        $mail->setFrom('newvet.confirm@outlook.com', 'NEW VET');
        $mail->addAddress($email);

        // Contenu de l'email
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Subject = 'Confirmation de votre commande #' . $commandeId;
        $mail->Body    = "<h1>Merci pour votre commande</h1>
                          <p>Votre numéro de commande est : <strong>$commandeId</strong></p>
                          <p>Le montant total de votre commande est de : <strong>" . number_format($totalPrix, 2) . " €</strong></p>
                          <p>Vous pouvez suivre l'évolution de votre commande depuis l'espace \"Mon Compte\" sur notre site.</p>
                          <p><a href='http://127.0.0.1/projet/NewVet/PHP/FrontEnd/VIEW/compte.php'>Mon compte</a></p>";
        
        // Envoyer l'email
        $mail->send();
        return true; // Renvoie true si l'e-mail a été envoyé
    } catch (Exception $e) {
        // Log l'erreur ou l'afficher pour le débogage
        error_log("Erreur d'envoi d'e-mail : {$mail->ErrorInfo}");
        return false; // Renvoie false si l'envoi a échoué
    }
}
?>

