<?php
include '../../Fonction/element.php';
include '../../Fonction/conf.php';
include '../../Fonction/db.php';
include '../../Fonction/auth.php';

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'ID du produit est passé en paramètre
if (!isset($_GET['id'])) {
    die("ID du produit non spécifié.");
}

$produitId = intval($_GET['id']);
$dbh = connexion_bdd();

// Récupérer les détails du produit
$produit = recupDetailProduit($dbh, $produitId);

// Récupérer les matériaux du produit
$materiaux = recupDetailMateriau($dbh, $produitId);

// Récupérer les images du produit
$images = recupDetailImage($dbh, $produitId);

// Récupérer des produits similaires
$sql = "SELECT p.produit_id, p.produit_nom, p.produit_prix, i.image_lien 
        FROM produit p
        JOIN illustration_produit ip ON p.produit_id = ip.produit_id
        JOIN image i ON ip.image_id = i.image_id
        WHERE p.categorie_id = :categorie_id AND p.produit_id != :produit_id
        GROUP BY p.produit_id
        ORDER BY p.produit_stock DESC
        LIMIT 4";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':categorie_id', $produit['categorie_id'], PDO::PARAM_INT);
$stmt->bindParam(':produit_id', $produitId, PDO::PARAM_INT);
$stmt->execute();
$produitsSimilaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ajouter le produit au panier lorsqu'il est ajouté via le bouton
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantite = isset($_POST['quantite']) ? intval($_POST['quantite']) : 1;

    // Récupérer le panier actuel à partir des cookies
    $panier = isset($_COOKIE['panier']) ? json_decode($_COOKIE['panier'], true) : [];

    // Vérifier si le produit est déjà dans le panier
    $produitDejaDansPanier = false;
    foreach ($panier as &$item) {
        if ($item['id'] == $produitId) {
            $item['quantite'] += $quantite; // Augmenter la quantité si déjà dans le panier
            $produitDejaDansPanier = true;
            break;
        }
    }

    // Si le produit n'est pas encore dans le panier, l'ajouter
    if (!$produitDejaDansPanier) {
        $panier[] = [
            'id' => $produitId,
            'nom' => $produit['produit_nom'],
            'prix' => $produit['produit_prix'],
            'quantite' => $quantite
        ];
    }

    // Mettre à jour le cookie avec le nouveau panier
    setcookie('panier', json_encode($panier), time() + (86400 * 30), "/"); // 30 jours
    header("Location: detailproduit.php?id=$produitId"); // Rediriger pour éviter le renvoi du formulaire
    exit();
}

?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Produit - <?php echo htmlspecialchars($produit['produit_nom']); ?></title>
    <?php headerElementPrint(); ?>
</head>

<style>
    .carousel-inner img {
        max-height: 600px;
        object-fit: scale-down;
    }

    .carousel-control-next,
    .carousel-control-prev {
        filter: invert(100%);
    }
</style>

<body>
<?php afficherNavbar($dbh); ?>
<div class="container mt-5">
    <div class="row">
        <!-- Carrousel d'images du produit -->
        <div class="col-md-6">
            <div id="car-produit" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($images as $index => $image) : ?>
                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                            <img src="<?php echo htmlspecialchars($image['image_lien']); ?>" class="d-block w-100" alt="Image du produit">
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#car-produit" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#car-produit" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>

        <!-- Détails du produit -->
        <div class="col-md-6">
            <h2><?php echo htmlspecialchars($produit['produit_nom']); ?></h2>
            <p><?php echo htmlspecialchars($produit['produit_desc']); ?></p>

            <ul>
                <?php foreach ($materiaux as $materiau) : ?>
                    <li><?php echo htmlspecialchars($materiau['materiau_nom']) . ' - ' . htmlspecialchars($materiau['composition_pourcentage']) . '%'; ?></li>
                <?php endforeach; ?>
            </ul>

            <p>Stock disponible : <strong><?php echo htmlspecialchars($produit['produit_stock']); ?></strong></p>

            <h3>Prix : <?php echo htmlspecialchars(number_format($produit['produit_prix'], 2)) . ' €'; ?></h3>

            <!-- Formulaire pour ajouter au panier -->
            <form method="POST" action="detailproduit.php?id=<?php echo $produitId; ?>">
                <div class="mb-3">
                    <label for="quantite" class="form-label">Quantité :</label>
                    <input type="number" id="quantite" name="quantite" class="form-control" value="1" min="1" max="<?php echo $produit['produit_stock']; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary btn-lg" <?php echo $produit['produit_stock'] == 0 ? 'disabled' : ''; ?>>
                    <?php echo $produit['produit_stock'] == 0 ? "Rupture de stock" : "Ajouter au panier"; ?>
                </button>
            </form>
        </div>
    </div>

    <!-- Produits similaires -->
    <div class="mt-5">
        <h4>Produits similaires</h4>
        <div class="row">
            <?php foreach ($produitsSimilaires as $produitSimilaire) : ?>
                <div class="col-md-3">
                    <a href="detailproduit.php?id=<?php echo htmlspecialchars($produitSimilaire['produit_id']); ?>">
                        <img src="<?php echo htmlspecialchars($produitSimilaire['image_lien']); ?>" class="img-fluid" alt="Produit similaire"></a>
                    <p><?php echo htmlspecialchars($produitSimilaire['produit_nom']); ?></p>
                    <p><?php echo htmlspecialchars(number_format($produitSimilaire['produit_prix'], 2)) . ' €'; ?></p>

                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php afficherFooter(); ?>
</body>

</html>
