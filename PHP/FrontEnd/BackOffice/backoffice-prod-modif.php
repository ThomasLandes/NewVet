<?php
include '../../Fonction/db.php';
include '../../Fonction/conf.php';
include '../../Fonction/auth.php';
include '../../Fonction/element.php';

autoriserOnlyAdmin();

$dbh = connexion_bdd();

// Vérifier que l'ID est passé dans l'URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $produitId = intval($_GET['id']);
    $details = ProductDetailsMultiTable($dbh, $produitId);

    // Vérification si les détails du produit sont valides
    if (!$details) {
        header("Location: backoffice-produit.php?error=2");
        exit;
    }

    $product = $details['product'];
    $materials = $details['materials'];
} else {
    // Redirection en cas d'ID invalide
    header("Location: backoffice-produit.php?error=1");
    exit;
}

// Traitement du formulaire lorsque le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $produit_id = $_POST['produit_id'];
    $nom = $_POST['productName'];
    $categorie = $_POST['productCategory'];
    $prix = $_POST['productPrice'];
    $stock = $_POST['productStock'];
    $description = $_POST['productDescription'];
    $highlight = isset($_POST['highlight']) ? 1 : 0;

    // Mettre à jour les détails du produit
    $stmt = $dbh->prepare("UPDATE produit SET produit_nom = :nom, categorie_id = :categorie, produit_prix = :prix, produit_stock = :stock, produit_desc = :description WHERE produit_id = :id");
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':categorie', $categorie);
    $stmt->bindParam(':prix', $prix);
    $stmt->bindParam(':stock', $stock);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':id', $produit_id);
    $stmt->execute();

    // Redirection vers la liste des produits après la mise à jour
    header("Location: backoffice-produit.php?success=1");
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php BO_headerElementPrint(); ?>
    <title>Modifier un Produit</title>
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
    <form action="backoffice-prod-modif.php?id=<?php echo htmlspecialchars($produitId); ?>" method="post">
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
                    <div class="col-md-6">
                        <input type="number" class="form-control" name="percentage[]" value="<?php echo htmlspecialchars($material['composition_pourcentage']); ?>" min="0" max="100" readonly>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Champ pour mettre en avant le produit -->
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="highlight" name="highlight" <?php echo $product['produit_highlander'] ? 'checked' : ''; ?>>
            <label class="form-check-label" for="highlight">Mettre en avant le produit</label>
        </div>

        <!-- Bouton de soumission -->
        <button type="submit" class="btn btn-primary">Modifier le Produit</button>
    </form>
</div>
</body>
</html>
