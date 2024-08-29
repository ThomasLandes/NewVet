<?php
include '../../Fonction/element.php';
include '../../Fonction/db.php';
include '../../Fonction/conf.php';

$dbh = connexion_bdd();

// Récupérer la liste des produits
$sql = "SELECT produit_id FROM produit";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Back Office - Gestion des Produits</title>
  <?php BO_headerElementPrint(); ?>
</head>

<body>
  <?php
  BO_afficherNavbar();
  BO_ContenuDashboardOuverture();
  ?>
  <!-- Contenu principal -->
  <h1 class="h2">Gestion des Produits</h1>
  <!-- Bouton Créer un produit -->
  <a href="backoffice-prod-ajout.php" class="btn btn-success mt-3">Créer un produit</a>
  <!-- Tableau de gestion des produits -->
  <table class="table table-bordered table-hover">
    <thead class="table-light">
      <tr>
        <th scope="col">Nom du Produit</th>
        <th scope="col">Image</th>
        <th scope="col">Prix</th>
        <th scope="col">Catégorie</th>
        <th scope="col">Description</th>
        <th scope="col">Composition</th>
        <th scope="col">Highlander</th>
        <th scope="col">Modifier</th>
        <th scope="col">Supprimer</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($produits as $produitData):
        $produitId = $produitData['produit_id'];
        $produit = recupDetailProduit($dbh, $produitId);
        $materiaux = recupDetailMateriau($dbh, $produitId);
        $images = recupDetailImage($dbh, $produitId);

        $composition = '';
        foreach ($materiaux as $materiau) {
          $composition .= $materiau['materiau_nom'] . ' (' . $materiau['composition_pourcentage'] . '%), ';
        }
        $composition = rtrim($composition, ', ');

        // Récupérer la première image, ou un lien par défaut si aucune image n'est disponible
        $imageLien = !empty($images) ? $images[0]['image_lien'] : 'default-image.jpg';
      ?>
        <tr>
          <td><?php echo htmlspecialchars($produit['produit_nom']); ?></td>
          <td><img src="<?php echo htmlspecialchars($imageLien); ?>" style="height:125px;"></td>
          <td><?php echo number_format($produit['produit_prix'], 2, ',', ' ') . ' €'; ?></td>
          <td><?php echo htmlspecialchars($produit['categorie_nom']); ?></td>
          <td><?php echo htmlspecialchars($produit['produit_desc']); ?></td>
          <td><?php echo htmlspecialchars($composition); ?></td>
          <td>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="highlanderProduct<?php echo $produitId; ?>" <?php echo $produit['produit_highlander'] ? 'checked' : ''; ?> data-produit-id="<?php echo $produitId; ?>">
              <label class="form-check-label" for="highlanderProduct<?php echo $produitId; ?>"></label>
            </div>
          </td>

          <td><a href="backoffice-prod-modif.php?id=<?php echo $produitId; ?>" class="btn btn-warning btn-sm">Modifier</a></td>
          <td>
            <a href="#" class="btn btn-danger btn-sm" onclick="confirmDeletion('<?php echo $produitId; ?>'); return false;">
              Supprimer
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!-- Bouton Créer un produit -->
  <a href="backoffice-prod-ajout.php" class="btn btn-success mt-3">Créer un produit</a>

  <?php BO_ContenuDashboardFermeture(); ?>
</body>
<script type="text/javascript">
  function confirmDeletion(produitId) {
    // Afficher la boîte de dialogue de confirmation
    var confirmAction = confirm("Êtes-vous sûr de vouloir supprimer ce produit ? Cette action est irréversible.");

    // Si l'utilisateur confirme la suppression
    if (confirmAction) {
      // Rediriger vers la page de suppression avec l'ID du produit
      window.location.href = 'backoffice-prod-suppr.php?id=' + produitId;
    }
    // Sinon, l'utilisateur annule et rien ne se passe
  }

  document.addEventListener('DOMContentLoaded', function () {
    // Fonction pour envoyer la requête AJAX pour mettre à jour le "Highlander"
    function updateHighlanderStatus(produitId, isHighlander) {
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'update_highlander.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.send('produit_id=' + encodeURIComponent(produitId) + '&highlander=' + (isHighlander ? 1 : 0));
    }

    // Écouter les changements sur les checkboxes
    document.querySelectorAll('.form-check-input').forEach(function (checkbox) {
      checkbox.addEventListener('change', function () {
        var produitId = this.getAttribute('data-produit-id');
        var isHighlander = this.checked;
        updateHighlanderStatus(produitId, isHighlander);
      });
    });
  });
</script>

</html>