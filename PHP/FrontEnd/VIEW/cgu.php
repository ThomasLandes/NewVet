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
    <title>Conditions Générales d'Utilisation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="icon" href="../IMAGE/favicon.ico" type="image/x-icon">
</head>

<body>
    <?php 
    afficherNavbar($dbh);?>


    <div class="container mt-5">
        <h1 class="text-center">Conditions Générales d'Utilisation</h1>

        <p>Bienvenue sur notre site. En accédant et en utilisant ce site, vous acceptez les présentes Conditions Générales d'Utilisation (CGU). Si vous n'acceptez pas ces conditions, veuillez ne pas utiliser notre site.</p>

        <h2>1. Acceptation des CGU</h2>
        <p>En accédant au site, vous acceptez les présentes conditions générales d'utilisation. Ces conditions peuvent être modifiées à tout moment, nous vous invitons donc à les consulter régulièrement.</p>

        <h2>2. Accès au site</h2>
        <p>Nous mettons tout en œuvre pour garantir que le site est accessible 24 heures sur 24, 7 jours sur 7. Cependant, nous ne pouvons être tenus responsables des interruptions de service, des erreurs ou des dysfonctionnements du site.</p>

        <h2>3. Propriété intellectuelle</h2>
        <p>Le contenu du site, y compris les textes, images, vidéos, logos, etc., est protégé par les droits d'auteur et autres droits de propriété intellectuelle. Toute reproduction, distribution, modification ou exploitation du contenu est strictement interdite sans notre accord préalable.</p>

        <h2>4. Responsabilité</h2>
        <p>Nous déclinons toute responsabilité pour les dommages directs ou indirects pouvant résulter de l'utilisation de notre site ou des informations qui y sont présentées.</p>

        <h2>5. Protection des données personnelles</h2>
        <p>Nous nous engageons à protéger vos données personnelles conformément aux lois en vigueur. Pour plus d'informations, veuillez consulter notre Politique de Confidentialité.</p>

        <h2>6. Loi applicable</h2>
        <p>Les présentes CGU sont régies par la loi française. En cas de litige, les tribunaux français seront seuls compétents.</p>

        <h2>7. Contact</h2>
        <p>Pour toute question concernant ces CGU, vous pouvez nous contacter à l'adresse suivante : support@votresite.com</p>
    </div>

    <?php 
    afficherFooter();?>


    <!-- Inclure les scripts JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
