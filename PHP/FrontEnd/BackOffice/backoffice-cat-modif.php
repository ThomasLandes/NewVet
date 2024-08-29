<?php
include '../../Fonction/element.php';
include '../../Fonction/db.php';
include '../../Fonction/conf.php';
include '../../Fonction/auth.php';

autoriserOnlyAdmin();
$dbh = connexion_bdd();

// Vérification de l'ID passé dans l'URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $categorie = recupCategoriesParID($dbh, $id);

    // Si la catégorie n'existe pas, rediriger vers la page de gestion avec un message d'erreur
    if (!$categorie) {
        header("Location: backoffice-categorie.php?error=3");
        exit;
    }
} else {
    // Si l'ID n'est pas fourni, rediriger vers la page de gestion
    header("Location: backoffice-categorie.php?error=4");
    exit;
}

// Traitement du formulaire lorsque le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "Formulaire soumis."; 
    // Récupérer les données du formulaire
    $nom = $_POST['categoryName'] ?? '';
    $description = $_POST['categoryDescription'] ?? '';
    $highlight = isset($_POST['highlightCategory']) ? 1 : 0;
    $id = $_POST['categoryId'] ?? $id; 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Chemin vers le dossier où les images seront stockées
        $targetDir = "../IMAGE/Categorie/";
        // Récupérer le nom de la catégorie, et le convertir en format "Pantalon"
        $nomFormat = ucfirst(strtolower($_POST['categoryName'])); // Assurez-vous que $_POST['categoryName'] contient le nom de la catégorie
        // Nouveau nom de fichier
        $newFileName = $nomFormat . ".jpg";
        // Chemin complet de destination
        $targetFilePath = $targetDir . $newFileName;

    
    
        // Vérifier si un fichier est téléchargé
        if (isset($_FILES['categoryImage']) && $_FILES['categoryImage']['error'] === UPLOAD_ERR_OK) {
            // Déplacer le fichier temporaire à l'endroit voulu avec le nouveau nom
            if (move_uploaded_file($_FILES['categoryImage']['tmp_name'], $targetFilePath)) {
                echo "L'image de la catégorie a été mise à jour avec succès.<br>";
            } else {
                echo "Erreur lors du téléchargement de l'image.<br>";
            }
        } else {
            echo "Aucun fichier ou erreur lors du téléchargement.<br>";
        }
    }
    


    // Mise à jour de la catégorie dans la base de données
    $stmt = $dbh->prepare("UPDATE categorie SET categorie_nom = :nom, categorie_desc = :description, categorie_highlight = :highlight WHERE categorie_id = :id");
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':highlight', $highlight);
    $stmt->bindParam(':id', $id);

    // Exécution de la requête
    if ($stmt->execute()) {
        header("Location: backoffice-categorie.php?success=2");
        exit;
    } else {
        $error = "Erreur lors de la mise à jour de la catégorie.";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php BO_headerElementPrint() ?>
    <title>Modifier Catégorie</title>
</head>

<body>
    <div class="container mt-5">
        <!-- Lien de retour -->
        <a href="backoffice-categorie.php" class="text-decoration-none text-dark mb-4 d-inline-flex align-items-center">
            <i class="bi bi-arrow-left me-2"></i> Retour
        </a>

        <h1 class="h2">Modifier Catégorie</h1>

        <!-- Affichage des messages d'erreur -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire de modification de catégorie -->
        <form action="backoffice-cat-modif.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">

        <!-- Champ caché pour l'ID de la catégorie -->
        <input type="hidden" name="categoryId" id="categoryId"value="<?php echo htmlspecialchars($id); ?>">

            <!-- Champ Nom de la catégorie -->
            <div class="mb-3">
                <label for="categoryName" class="form-label">Nom</label>
                <input type="text" class="form-control" id="categoryName" name="categoryName" value="<?php echo htmlspecialchars($categorie['categorie_nom']); ?>" maxlength="50" required>
            </div>

            <!-- Champ Description de la catégorie -->
            <div class="mb-3">
                <label for="categoryDescription" class="form-label">Description de la Catégorie</label>
                <textarea class="form-control" id="categoryDescription" name="categoryDescription" maxlength="250" rows="3" required><?php echo htmlspecialchars($categorie['categorie_desc']); ?></textarea>
            </div>

            <!-- Sélecteur de fichier pour l'image -->
            <div class="mb-3">
                <label for="categoryImage" class="form-label">Image de la Catégorie (Laissez vide pour conserver l'image actuelle)</label>
                <input type="file" class="form-control" id="categoryImage" name="categoryImage">
                <?php if ($categorie['categorie_image']): ?>
                    <p class="mt-2"><img src="<?php echo htmlspecialchars($categorie['categorie_image']); ?>" alt="Image actuelle" style="max-width: 200px;"></p>
                <?php endif; ?>
            </div>

            <!-- Checkbox pour mise en avant -->
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="highlightCategory" name="highlightCategory" <?php echo $categorie['categorie_highlight'] ? 'checked' : ''; ?>>
                <label class="form-check-label" for="highlightCategory">Mettre en avant</label>
            </div>


        <!-- Conservation de l'ID -->
         <input class="form-check-input" type="hidden" id="highlightCategory" name="highlightCategory"

            <!-- Bouton de validation -->
            <button type="submit" class="btn btn-primary">Valider</button>
        </form>
    </div>
</body>

</html>