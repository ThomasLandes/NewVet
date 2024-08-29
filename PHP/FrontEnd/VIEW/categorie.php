<?php
include '../../Fonction/element.php';
include '../../Fonction/conf.php';
include '../../Fonction/db.php';
include '../../Fonction/auth.php';

$dbh = connexion_bdd();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de catégorie invalide.");
}

$categorieId = $_GET['id'];

// Récupérer les détails de la catégorie
$categorie = recupDetailCategorie($dbh, $categorieId);

// Récupérer la liste des produits pour cette catégorie
$produits = recupProduitsParCategorie($dbh, $categorieId);

?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($categorie['categorie_nom']); ?></title>
    <link rel="stylesheet" href="../CSS/accueil.css">
    <?php headerElementPrint(); ?>
    <style>
        /* Style pour centrer le texte sur l'image */
        .category-image {
            position: relative;
            text-align: center;
            color: white;
        }

        .category-image img {
            width: 50%;
            height: auto;
        }

        .category-name {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 48px;
            font-weight: bold;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
        }

        /* Style pour le texte de description */
        .category-description {
            margin-top: 30px;
            font-size: 20px;
            text-align: center;
            padding: 0 15px;
        }
    </style>
</head>

<body>
    <?php afficherNavbar ($dbh); ?>
    <div class="container mt-5">
        <!-- Bloc Image avec nom de la catégorie -->
        <div class="category-image">
            <img src="<?php echo htmlspecialchars($categorie['categorie_image']); ?>" alt="<?php echo htmlspecialchars($categorie['categorie_nom']); ?>">
            <div class="category-name"><?php echo htmlspecialchars($categorie['categorie_nom']); ?></div>
        </div>

        <!-- Bloc Description de la catégorie -->
        <div class="category-description">
            <p><?php echo nl2br(htmlspecialchars($categorie['categorie_desc'])); ?></p>
        </div>

        <div class="container text-center">
            <p style="text-align: left; font-size: 24px;">NOS PRODUITS</p>
            <div class="row">
                <?php foreach ($produits as $produit): ?>
                    <div class="col">
                        <div class="card" style="width: 18rem;">
                            <img src="<?php echo htmlspecialchars($produit['image_lien']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($produit['produit_nom']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($produit['produit_nom']); ?></h5>
                                <p class="card-text">Prix : <?php echo number_format($produit['produit_prix'], 2, ',', ' '); ?> €</p>
                                <?php if ($produit['produit_stock'] == 0): ?>
                                    <p class="card-text text-danger">Stock épuisé</p>
                                <?php endif; ?>
                                <a href="detailproduit.php?id=<?php echo $produit['produit_id']; ?>" class="btn btn-primary">Explorer</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>

</html>
