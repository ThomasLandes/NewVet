<?php
include '../../Fonction/element.php';
include '../../Fonction/conf.php';
include '../../Fonction/db.php';
include '../../Fonction/auth.php';

$dbh = connexion_bdd();

// Définir le nombre de produits par page
$limit = 6;

// Récupérer le numéro de page actuel depuis l'URL (défaut à 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = $page > 0 ? $page : 1;

// Calculer le début des produits pour la page actuelle
$start = ($page - 1) * $limit;

// Récupérer les produits pour la page actuelle
$produits = recupProduitsParPage($dbh, $start, $limit);

// Récupérer le nombre total de produits
$totalProduits = countTotalProduits($dbh);
$totalPages = ceil($totalProduits / $limit);
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nos Produits</title>
    <link rel="stylesheet" href="../CSS/accueil.css">
    <?php headerElementPrint(); ?>
    <style>
      .product-card {
        transition: transform 0.3s, box-shadow 0.3s;
      }
      .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
      }
    </style>
</head>

<body>
    <?php afficherNavbar($dbh); ?>
    <div class="container mt-5">
      <h1 class="text-center mb-4">Nos Produits</h1>

      <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($produits as $produit) : 
            // Récupération des détails du produit incluant les images
            $details = ProductDetailsMultiTable($dbh, $produit['produit_id']);
            $product = $details['product'];
            $images = $details['images'];
            
            // Affichage du produit avec la première image disponible
            $firstImage = !empty($images) ? $images[0]['image_lien'] : 'default.jpg'; // Image par défaut si aucune image n'est disponible
        ?>
        <div class="col">
          <a href="detailproduit.php?id=<?php echo htmlspecialchars($product['produit_id']); ?>" class="text-decoration-none text-dark">
            <div class="card product-card">
              <img src="<?php echo htmlspecialchars($firstImage); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['produit_nom']); ?>">
              <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($product['produit_nom']); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($product['produit_prix']); ?> €</p>
                <?php if ($product['produit_stock'] > 0) : ?>
                  <span class="badge bg-success">En stock</span>
                <?php else : ?>
                  <span class="badge bg-danger">Rupture de stock</span>
                <?php endif; ?>
              </div>
            </div>
          </a>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Liens de pagination -->
      <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center mt-4">
          <?php if ($page > 1) : ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>
          <?php endif; ?>
          
          <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
              <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
          <?php endfor; ?>
          
          <?php if ($page < $totalPages) : ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoDa5a9F0QwS5c5N9zdoSm+NYzFJ8huFt1dJRJZ1HA+famK" crossorigin="anonymous"></script>
  </body>
</html>
