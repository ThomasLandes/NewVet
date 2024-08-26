<?php
include '../../Fonction/element.php';
include '../../Fonction/conf.php';
include '../../Fonction/db.php';

$dbh = connexion_bdd();

?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Projet NewVET</title>
    <link rel="stylesheet" href="../CSS/accueil.css">
    <?php headerElementPrint(); ?>
</head>

<body>
    <!-- NAVBAR -->
    <?php afficherNavbar($dbh); ?>

    <!-- CARROUSEL -->
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <?php
            // Chemin vers le dossier des images du carrousel
            $carouselDir = "../IMAGE/Accueil/";
            $carouselImages = ["image1.jpeg", "image2.jpeg", "image3.jpeg"];
            $active = 'active';

            foreach ($carouselImages as $image) {
                echo '<div class="carousel-item ' . $active . '">';
                echo '<img src="' . $carouselDir . $image . '" class="d-block w-100" alt="' . htmlspecialchars($image) . '">';
                echo '</div>';
                $active = ''; // Désactiver 'active' après la première image
            }
            ?>
        </div>
    </div>

    <!-- TEXTE FIXE -->
    <div class="container-fluid text-center textefixe" >
        <div class="mx-auto" style="background-color: rgba(0, 0, 0, 0.7); color: white; padding: 20px;width: 30%">
            <p>Bienvenue sur notre site d’e-commerce dédié à la mode féminine, où l’élégance rencontre l’éthique.
                Nous vous proposons une sélection de vêtements de haute qualité, entièrement fabriqués en France avec un savoir-faire inégalé.<br><br>
                Nos créations allient style intemporel et respect de l’environnement, en utilisant des matériaux éco-responsables et des procédés de fabrication durables.
                De plus, nous nous engageons à vous offrir une expérience d'achat rapide et fluide, avec des délais de livraison accélérés pour que vous puissiez profiter rapidement de vos nouvelles pièces. <br><br>
                Découvrez la mode qui fait rimer luxe et conscience écologique, tout en bénéficiant d’un service rapide et attentif.</p>
        </div>
        <div class="container mt-3 text-center">
            <i class="bi bi-stars me-3" style="font-size: 40px;"></i>
            <i class="bi bi-globe-asia-australia me-3" style="font-size: 40px;"></i>
            <i class="bi bi-truck" style="font-size: 40px;"></i>
        </div>
    </div>


    <!-- CATEGORIE -->
    <?php
    // Récupérer les catégories mises en avant
    $categories = recupCategoriesLimit($dbh, 3);
    ?>
    <div class="container text-center categorie">
        <p class="highlander-title">NOS CATEGORIES À LA UNE</p>
        <div class="row">
            <?php foreach ($categories as $categorie): ?>
                <div class="col d-flex justify-content-center">
                    <div class="card" style="width: 18rem;">
                        <img src="<?php echo htmlspecialchars($categorie['categorie_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($categorie['categorie_nom']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($categorie['categorie_nom']); ?></h5>
                            <a href="categorie.php?id=<?php echo htmlspecialchars($categorie['categorie_id']); ?>" class="btn btn-primary">Découvrir</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- HIGHLANDER -->
    <?php
    // Récupérer les Highlanders
    $highlanders = recupHighlanders($dbh);
    ?>
    <div class="container text-center highlander">
        <p class="highlander-title">NOS HIGHLANDER DU MOMENT</p>
        <div class="row">
            <?php foreach ($highlanders as $highlander): ?>
                <div class="col d-flex justify-content-center">
                    <div class="card" style="width: 18rem;">
                        <img src="<?php echo htmlspecialchars($highlander['image_lien']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($highlander['produit_nom']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($highlander['produit_nom']); ?></h5>
                            <a href="produit.php?id=<?php echo htmlspecialchars($highlander['produit_id']); ?>" class="btn btn-primary">Découvrir</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- FOOTER -->
</body>

</html>