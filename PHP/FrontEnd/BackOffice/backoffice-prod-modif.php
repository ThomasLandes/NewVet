<?php
include '../../Fonction/db.php';
include '../../Fonction/conf.php';
include '../../Fonction/auth.php';

autoriserOnlyAdmin();

$dbh = connexion_bdd();

// Vérifier que l'ID est passé dans l'URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $produitId = intval($_GET['id']);
    $details = ProductDetailsMultiTable($dbh, $produitId);
    $product = $details['product'];
    $materials = $details['materials'];
    $images = $details['images'];
} else {
    // Redirection en cas d'ID invalide
    header("Location: backoffice-produit.php?error=1");
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php
  BO_headerElementPrint();?>
  <title>Modifier un Produit</title>
  <link rel="stylesheet" href="path_to_bootstrap.css">
  <script>
    // Fonction pour ajouter dynamiquement un champ d'image
    function addImageField() {
      const imageContainer = document.getElementById("image-container");
      const newImageField = document.createElement("div");
      newImageField.className = "mb-2 d-flex align-items-center image-row";
      newImageField.innerHTML = `
        <input type="file" class="form-control" name="productImage[]">
        <button type="button" class="btn btn-danger btn-sm ms-2" onclick="removeImageField(this)">&#10005;</button>
      `;
      imageContainer.appendChild(newImageField);
    }

    // Fonction pour supprimer un champ d'image
    function removeImageField(button) {
      button.closest(".image-row").remove();
    }
  </script>
</head>
<body>
  <div class="container mt-5">
    <a href="backoffice-produit.php" class="text-decoration-none text-dark mb-4 d-inline-flex align-items-center">
      <i class="bi bi-arrow-left me-2"></i> Retour
    </a>

    <h1 class="h2">Modifier le Produit</h1>

    <!-- Affichage des messages d'erreur -->
    <?php if (isset($error)): ?>
      <div class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>

    <!-- Formulaire de modification de produit -->
    <form action="backoffice-prod-modif.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="produit_id" value="<?php echo htmlspecialchars($product['produit_id']); ?>">

      <!-- Champ Nom du produit -->
      <div class="mb-3">
        <label for="productName" class="form-label">Nom du Produit</label>
        <input type="text" class="form-control" id="productName" name="productName" value="<?php echo htmlspecialchars($product['produit_nom']); ?>" maxlength="100" required>
      </div>

      <!-- Sélecteur de catégorie -->
      <div class="mb-3">
        <label for="productCategory" class="form-label">Catégorie</label>
        <select class="form-select" id="productCategory" name="productCategory" required>
          <option value="">Choisir une catégorie</option>
          <?php
          // Dynamically populate categories here
          $categories = recupCategories($dbh); // Assuming this function fetches categories from the database
          foreach ($categories as $category) {
            $selected = $product['categorie_id'] == $category['categorie_id'] ? 'selected' : '';
            echo '<option value="' . htmlspecialchars($category['categorie_id']) . '" ' . $selected . '>' . htmlspecialchars($category['categorie_nom']) . '</option>';
          }
          ?>
        </select>
      </div>

      <!-- Champ Prix -->
      <div class="mb-3">
        <label for="productPrice" class="form-label">Prix du Produit</label>
        <input
          type="text"
          class="form-control"
          id="productPrice"
          name="productPrice"
          pattern="^\d{1,3}(\.\d{2})?$"
          title="Veuillez entrer un prix entre 0.00 et 999.99 avec deux chiffres après le point décimal."
          value="<?php echo htmlspecialchars($product['produit_prix']); ?>"
          placeholder="0.00"
          required />
      </div>

      <!-- Champ Stock -->
      <div class="mb-3">
        <label for="productStock" class="form-label">Stock du Produit</label>
        <input
          type="number"
          class="form-control"
          id="productStock"
          name="productStock"
          min="0"
          max="999"
          step="1"
          value="<?php echo htmlspecialchars($product['produit_stock']); ?>"
          required
          placeholder="0" />
      </div>

      <!-- Champ Description -->
      <div class="mb-3">
        <label for="productDescription" class="form-label">Description du Produit</label>
        <textarea class="form-control" id="productDescription" name="productDescription" maxlength="500" rows="4" required><?php echo htmlspecialchars($product['produit_desc']); ?></textarea>
      </div>

      <!-- Zone d'upload d'image -->
      <div id="image-container" class="mb-3">
        <label class="form-label">Images du Produit</label>
        <?php foreach ($images as $image): ?>
          <div class="mb-2 d-flex align-items-center image-row">
            <input type="file" class="form-control" name="productImage[]" value="<?php echo htmlspecialchars($image['image_lien']); ?>">
            <button type="button" class="btn btn-danger btn-sm ms-2" onclick="removeImageField(this)">&#10005;</button>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Bouton pour ajouter une image -->
      <button type="button" class="btn btn-secondary mb-3" onclick="addImageField()">Ajouter une image</button>

      <!-- Sélection des matériaux et pourcentage -->
      <div id="material-container">
        <label class="form-label">Matériaux</label>
        <?php foreach ($materials as $index => $material): ?>
          <div class="row mb-3 material-row">
            <div class="col-md-6">
              <select class="form-select" name="material[]">
                <?php foreach (recupMateriaux($dbh) as $materiau): ?>
                  <option value="<?php echo htmlspecialchars($materiau['materiau_id']); ?>" <?php echo $material['materiau_id'] == $materiau['materiau_id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($materiau['materiau_nom']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <input type="number" class="form-control" name="percentage[]" value="<?php echo htmlspecialchars($material['composition_pourcentage']); ?>" placeholder="%" min="0" max="100">
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Bouton pour ajouter un matériau -->
      <button type="button" class="btn btn-secondary mb-3" onclick="addMaterialField()">Ajouter un matériau</button>

      <!-- Bouton de validation -->
      <button type="submit" class="btn btn-primary">Valider</button>
    </form>
  </div>
</body>
</html>
