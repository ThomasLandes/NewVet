<?php
include '../../Fonction/element.php';
include '../../Fonction/conf.php';
include '../../Fonction/db.php';
include '../../Fonction/auth.php';

$dbh = connexion_bdd();?>



<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mentions Légales</title>
    <!-- Inclure le fichier CSS Bootstrap (ou votre propre fichier CSS) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="icon" href="../IMAGE/favicon.ico" type="image/x-icon">
</head>

<body>
<?php afficherNavbar($dbh);?>
    <div class="container mt-5">
        <h1 class="text-center">Mentions Légales</h1>

        <h2>1. Éditeur du site</h2>
        <p>
            Nom de l'entreprise : Newvet Inc.<br>
            Adresse : 3 rue de la pepiniere 31000 Toulouse<br>
            Téléphone : +33 5 84 98 65 32<br>
            Email : newvet@outlook.com<br>
            Numéro d'immatriculation : 1 234084635535<br>
            Directeur de la publication : Thierry NewVet<br>
        </p>

        <h2>2. Hébergement</h2>
        <p>
            Hébergeur : XAMPP <br>
            Adresse : 31 rue croix baragnon 31000 TOULOUSE<br>
            Téléphone : +33 5 64 25 23 98<br>
            Site Web : xampp-hebergement.com<br>
        </p>

        <h2>3. Propriété intellectuelle</h2>
        <p>
            Tout le contenu présent sur ce site, incluant, de façon non limitative, les graphismes, images, textes, vidéos, animations, sons, logos, gifs et icônes ainsi que leur mise en forme sont la propriété exclusive de [Nom de votre entreprise], à l'exception des marques, logos ou contenus appartenant à d'autres sociétés partenaires ou auteurs.
        </p>

        <h2>4. Données personnelles</h2>
        <p>
            Les informations recueillies sur ce site sont enregistrées dans un fichier informatisé par [Nom de votre entreprise] pour [finalité du traitement]. Elles sont conservées pendant [durée de conservation] et sont destinées à [destinataires des données]. Conformément à la loi « informatique et libertés », vous pouvez exercer votre droit d'accès aux données vous concernant et les faire rectifier en contactant : [Adresse e-mail].
        </p>

        <h2>5. Cookies</h2>
        <p>
            Ce site utilise des cookies pour améliorer votre expérience de navigation. En continuant à utiliser ce site, vous acceptez l'utilisation de cookies conformément à notre politique de confidentialité.
        </p>

        <h2>6. Litiges</h2>
        <p>
            Les présentes conditions du site sont régies par les lois françaises et tout litige ou contestation qui pourrait naître de l'interprétation ou de l'exécution de celles-ci sera de la compétence exclusive des tribunaux dont dépend le siège social de [Nom de votre entreprise].
        </p>

        <h2>7. Contact</h2>
        <p>
            Pour toute question concernant ces mentions légales, vous pouvez nous contacter à l'adresse suivante : newvet-contact@outlook.com.
        </p>
    </div>

    <?php afficherFooter();?>

    <!-- Inclure les scripts JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
