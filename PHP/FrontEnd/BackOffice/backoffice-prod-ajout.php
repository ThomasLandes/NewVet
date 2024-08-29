<?php
include '../../Fonction/db.php';
include '../../Fonction/conf.php';
include '../../Fonction/element.php';
include '../../Fonction/auth.php';

autoriserOnlyAdmin();

$dbh = connexion_bdd();
$materiaux = recupMateriaux($dbh);

// Traitement du formulaire lors de la soumission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Récupérer les données du formulaire
  $nom = $_POST['productName'] ?? '';
  $description = $_POST['productDescription'] ?? '';
  $categorieId = $_POST['productCategory'] ?? '';
  $prix = $_POST['productPrice'] ?? 0.00;
  $stock = $_POST['productStock'] ?? 0;

  // Récupérer les matériaux et leurs pourcentages
  $materiaux = $_POST['material'] ?? [];
  $percentages = $_POST['percentage'] ?? [];

  // Calculer la somme des pourcentages
  $totalPercentage = array_sum($percentages);

  // Validation des données
  if ($nom && $description && $categorieId && is_numeric($prix) && is_numeric($stock)) {
    // Vérifier que la somme des pourcentages est égale à 100
    if ($totalPercentage == 100) {
      // Préparer la requête d'insertion du produit
      $stmt = $dbh->prepare("INSERT INTO produit (produit_nom, produit_desc, produit_prix, produit_stock, categorie_id) VALUES (:nom, :description, :prix, :stock, :categorie_id)");
      $stmt->bindParam(':nom', $nom);
      $stmt->bindParam(':description', $description);
      $stmt->bindParam(':prix', $prix);
      $stmt->bindParam(':stock', $stock);
      $stmt->bindParam(':categorie_id', $categorieId);

      if ($stmt->execute()) {
        // Récupérer l'ID du produit nouvellement inséré
        $produitId = $dbh->lastInsertId();

        // Insertion des matériaux et pourcentages dans la table composition
        $stmt = $dbh->prepare("INSERT INTO composition (produit_id, materiau_id, composition_pourcentage) VALUES (:produit_id, :materiau_id, :pourcentage)");

        foreach ($materiaux as $index => $materiauId) {
          $pourcentage = $percentages[$index] ?? 0;
          if (is_numeric($pourcentage)) {
            $stmt->bindParam(':produit_id', $produitId);
            $stmt->bindParam(':materiau_id', $materiauId);
            $stmt->bindParam(':pourcentage', $pourcentage);
            $stmt->execute();
          }
        }

        // Gestion des images
        if (!empty($_FILES['productImage']['name'][0])) {
          // Récupérer le nom de la catégorie pour le dossier
          $stmtCategory = $dbh->prepare("SELECT categorie_nom FROM categorie WHERE categorie_id = :categorie_id");
          $stmtCategory->bindParam(':categorie_id', $categorieId);
          $stmtCategory->execute();
          $categorieNom = $stmtCategory->fetchColumn();

          // Formatage du nom de la catégorie
          $dossierCategorie = ucfirst(strtolower($categorieNom));

          // Chemin du dossier de la catégorie
          $cheminCategorie = "../IMAGE/Produit/$dossierCategorie";

          // Chemin du dossier du produit
          $dossierProduit = "$cheminCategorie/$nom";

          // Créer le dossier du produit s'il n'existe pas
          if (!is_dir($dossierProduit)) {
            if (!mkdir($dossierProduit, 0777, true)) {
              $error = "Erreur lors de la création du dossier pour les images.";
            }
          }

          // Si le dossier est bien créé ou existant, traiter les images
          if (!isset($error)) {
            foreach ($_FILES['productImage']['name'] as $index => $imageName) {
              $extension = pathinfo($imageName, PATHINFO_EXTENSION);
              $imageNom = "$nom - " . ($index + 1) . ".$extension";
              $cheminImage = "$dossierProduit/$imageNom";

              // Déplacement du fichier uploadé
              if (move_uploaded_file($_FILES['productImage']['tmp_name'][$index], $cheminImage)) {
                // Insertion dans la table image
                $stmtImage = $dbh->prepare("INSERT INTO image (image_nom, image_lien) VALUES (:image_nom, :image_lien)");
                $stmtImage->bindParam(':image_nom', $imageNom);
                $stmtImage->bindParam(':image_lien', $cheminImage);
                $stmtImage->execute();

                // Récupérer l'ID de l'image insérée
                $imageId = $dbh->lastInsertId();

                // Insertion dans la table illustration_produit
                $stmtIllustration = $dbh->prepare("INSERT INTO illustration_produit (produit_id, image_id) VALUES (:produit_id, :image_id)");
                $stmtIllustration->bindParam(':produit_id', $produitId);
                $stmtIllustration->bindParam(':image_id', $imageId);
                $stmtIllustration->execute();
              } else {
                $error = "Erreur lors du téléchargement de l'image.";
                break;
              }
            }
          }
        }
      }

      // Redirection en cas de succès
      if (!isset($error)) {
        header("Location: backoffice-produit.php?success=1");
        exit;
      }
    } else {
      $error = "Erreur lors de l'ajout du produit.";
    }
  } else {
    $error = "Veuillez remplir tous les champs requis correctement.";
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php
  BO_headerElementPrint();
  BO_ScriptAjoutSectionMateriau();
  BO_ScriptAjoutSectionImage();
  ?>
  <title>Ajouter un Produit</title>
</head>

<body>
  <div class="container mt-5">
    <!-- Lien de retour -->
    <a href="backoffice-produit.php" class="text-decoration-none text-dark mb-4 d-inline-flex align-items-center">
      <i class="bi bi-arrow-left me-2"></i> Retour
    </a>

    <h1 class="h2">Ajouter un Produit</h1>

    <!-- Affichage des messages d'erreur -->
    <?php if (isset($error)): ?>
      <div class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>

    <!-- Formulaire d'ajout de produit -->
    <form action="backoffice-prod-ajout.php" method="post" enctype="multipart/form-data">
      <!-- Champ Nom du produit -->
      <div class="mb-3">
        <label for="productName" class="form-label">Nom du Produit</label>
        <input type="text" class="form-control" id="productName" name="productName" maxlength="100" required>
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
            echo '<option value="' . htmlspecialchars($category['categorie_id']) . '">' . htmlspecialchars($category['categorie_nom']) . '</option>';
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
          required
          placeholder="0" />
      </div>

      <!-- Champ Description -->
      <div class="mb-3">
        <label for="productDescription" class="form-label">Description du Produit</label>
        <textarea class="form-control" id="productDescription" name="productDescription" maxlength="500" rows="4" required></textarea>
      </div>

      <!-- Zone d'upload d'image -->
      <div id="image-container" class="mb-3">
        <label class="form-label">Images du Produit</label>
        <div class="mb-2">
          <input type="file" class="form-control" name="productImage[]" required>
        </div>
      </div>

      <!-- Bouton pour ajouter une image -->
      <button type="button" class="btn btn-secondary mb-3" onclick="addImageField()">Ajouter une image</button>

      <!-- Sélection des matériaux et pourcentage -->
      <div id="material-container">
        <label class="form-label">Matériaux</label>
        <div class="row mb-3">
          <div class="col-md-6">
            <select class="form-select" name="material[]" required>
              <?php
              $materiaux = recupMateriaux($dbh); // Récupère les matériaux disponibles
              foreach ($materiaux as $materiau) {
                echo '<option value="' . htmlspecialchars($materiau['materiau_id']) . '">' . htmlspecialchars($materiau['materiau_nom']) . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="col-md-4">
            <input type="number" class="form-control" name="percentage[]" placeholder="%" min="0" max="100" required>
          </div>
        </div>
      </div>

      <!-- Bouton pour ajouter un matériau -->
      <button type="button" class="btn btn-secondary mb-3" onclick="addMaterialField()">Ajouter un matériau</button>
      <br>
      <!-- Bouton de validation -->
      <button type="submit" class="btn btn-primary">Valider</button>
    </form>
  </div>
</body>

</html>