<?php
include '../../Fonction/element.php';
include '../../Fonction/db.php';
include '../../Fonction/conf.php';

$dbh = connexion_bdd();
$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom = $_POST['categoryName'] ?? '';
    $description = $_POST['categoryDescription'] ?? '';
    $highlight = isset($_POST['highlightCategory']) ? 1 : 0;

    // Chemin vers le dossier où les images seront stockées
    $targetDir = "../IMAGE/Categorie/";
    $nomFormat = ucfirst(strtolower($nom));
    $newFileName = $nomFormat . ".jpg";
    $targetFilePath = $targetDir . $newFileName;

    // Vérifier si un fichier est téléchargé
    if (isset($_FILES['categoryImage']) && $_FILES['categoryImage']['error'] === UPLOAD_ERR_OK) {
        // Déplacer le fichier temporaire à l'endroit voulu avec le nouveau nom
        if (move_uploaded_file($_FILES['categoryImage']['tmp_name'], $targetFilePath)) {
            echo "L'image de la catégorie a été téléchargée avec succès.<br>";
        } else {
            $error = "Erreur lors du téléchargement de l'image.<br>";
        }
    } else {
        $error = "Aucun fichier ou erreur lors du téléchargement.<br>";
    }

    if (empty($error)) {
        // Insertion de la nouvelle catégorie dans la base de données
        $stmt = $dbh->prepare("INSERT INTO categorie (categorie_nom, categorie_desc, categorie_image, categorie_highlight) VALUES (:nom, :description, :image, :highlight)");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image', $targetFilePath);
        $stmt->bindParam(':highlight', $highlight);

        if ($stmt->execute()) {
            header("Location: backoffice-categorie.php?success=1");
            exit;
        } else {
            $error = "Erreur lors de l'ajout de la catégorie.";
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php BO_headerElementPrint(); ?>
    <title>Ajouter Catégorie</title>
</head>
<body>
    <div class="container mt-5">
        <!-- Lien de retour -->
        <a href="backoffice-categorie.php" class="text-decoration-none text-dark mb-4 d-inline-flex align-items-center">
            <i class="bi bi-arrow-left me-2"></i> Retour
        </a>

        <h1 class="h2">Ajouter une Catégorie</h1>

        <!-- Affichage des messages d'erreur -->
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire d'ajout de catégorie -->
        <form action="backoffice-cat-ajout.php" method="post" enctype="multipart/form-data">
            <!-- Champ Nom de la catégorie -->
            <div class="mb-3">
                <label for="categoryName" class="form-label">Nom de la Catégorie</label>
                <input type="text" class="form-control" id="categoryName" name="categoryName" maxlength="50" required>
            </div>

            <!-- Champ Description de la catégorie -->
            <div class="mb-3">
                <label for="categoryDescription" class="form-label">Description de la Catégorie</label>
                <textarea class="form-control" id="categoryDescription" name="categoryDescription" maxlength="250" rows="3" required></textarea>
            </div>

            <!-- Sélecteur de fichier pour l'image -->
            <div class="mb-3">
                <label for="categoryImage" class="form-label">Image de la Catégorie</label>
                <input type="file" class="form-control" id="categoryImage" name="categoryImage" required>
            </div>

            <!-- Checkbox pour mise en avant -->
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="highlightCategory" name="highlightCategory">
                <label class="form-check-label" for="highlightCategory">Mettre en avant</label>
            </div>

            <!-- Bouton de validation -->
            <button type="submit" class="btn btn-primary">Valider</button>
        </form>
    </div>
</body>
</html>
