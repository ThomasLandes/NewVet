<?php
include '../../Fonction/element.php';
include '../../Fonction/db.php';
include '../../Fonction/conf.php';
include '../../Fonction/auth.php';

autoriserOnlyAdmin();
$dbh = connexion_bdd();

//CATEGORIE ----------------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_categories'])) {
        // Réinitialiser toutes les catégories pour qu'elles ne soient plus en avant
        $dbh->exec("UPDATE categorie SET categorie_highlight = 0");

        // Mettre en avant les catégories sélectionnées
        if (isset($_POST['highlighted_categories'])) {
            $highlightedCategories = $_POST['highlighted_categories'];
            foreach ($highlightedCategories as $categorie_id) {
                $stmt = $dbh->prepare("UPDATE categorie SET categorie_highlight = 1 WHERE categorie_id = :categorie_id");
                $stmt->bindParam(':categorie_id', $categorie_id);
                $stmt->execute();
            }
        }
    }
}

// Récupérer toutes les catégories pour les afficher
$categories = $dbh->query("SELECT * FROM categorie")->fetchAll(PDO::FETCH_ASSOC);
//CATEGORIE ----------------------------------------------------------------------------------
//____________________________________________________________________________________________
//____________________________________________________________________________________________
//____________________________________________________________________________________________
//____________________________________________________________________________________________
//CARROUSEL ----------------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Chemin vers le dossier où les images seront stockées
    $targetDir = "../IMAGE/Accueil/";

    // Liste des champs de téléchargement
    $carouselImages = ['carouselImage1', 'carouselImage2', 'carouselImage3'];

    foreach ($carouselImages as $key => $carouselImage) {
        if (!empty($_FILES[$carouselImage]['name'])) {
            // Renommer l'image en fonction de sa position
            $newFileName = "image" . ($key + 1) . ".jpeg"; // On peut changer l'extension selon le type de fichier

            // Chemin complet de destination
            $targetFilePath = $targetDir . $newFileName;

            // Déplacer le fichier temporaire à l'endroit voulu avec le nouveau nom
            if (move_uploaded_file($_FILES[$carouselImage]['tmp_name'], $targetFilePath)) {
                echo "Carrousel " . ($key + 1) . " a été mise à jour avec succès.<br>";
            } else {
                echo "Erreur lors du téléchargement de l'image " . ($key + 1) . ".<br>";
            }
        }
    }
}
//CARROUSEL ----------------------------------------------------------------------------------
//____________________________________________________________________________________________
//____________________________________________________________________________________________
//____________________________________________________________________________________________
//____________________________________________________________________________________________
//PRODUIT ------------------------------------------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_highlanders'])) {
        // Réinitialiser tous les produits pour qu'ils ne soient plus Highlanders
        $dbh->exec("UPDATE produit SET produit_highlander = 0, ordre_highlander = NULL");

        // Mettre en avant les produits sélectionnés
        if (isset($_POST['highlander_products'])) {
            $highlanderProducts = $_POST['highlander_products'];
            foreach ($highlanderProducts as $produit_id) {
                $ordre = $_POST['ordre_highlander'][$produit_id];
                $stmt = $dbh->prepare("UPDATE produit SET produit_highlander = 1, ordre_highlander = :ordre WHERE produit_id = :produit_id");
                $stmt->bindParam(':ordre', $ordre);
                $stmt->bindParam(':produit_id', $produit_id);
                $stmt->execute();
            }
        }
    }
}

// Récupérer tous les produits pour les afficher
$produits = $dbh->query("SELECT * FROM produit WHERE produit_highlander = 1")->fetchAll(PDO::FETCH_ASSOC);

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Back Office</title>
    <?php BO_headerElementPrint(); ?>

</head>

<body>
    <?php BO_afficherNavbar();
    BO_ContenuDashboardOuverture(); ?>
    <h1 class="h2">Gestion de l'Accueil</h1>

    <hr>

    <!-- Gestion des images du carrousel -->
    <section id="carousel-management">
        <h2>Images du Carrousel</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="carouselImage1" class="form-label">Image 1</label>
                <input type="file" class="form-control" id="carouselImage1" name="carouselImage1">
            </div>
            <div class="mb-3">
                <label for="carouselImage2" class="form-label">Image 2</label>
                <input type="file" class="form-control" id="carouselImage2" name="carouselImage2">
            </div>
            <div class="mb-3">
                <label for="carouselImage3" class="form-label">Image 3</label>
                <input type="file" class="form-control" id="carouselImage3" name="carouselImage3">
            </div>
            <button type="submit" class="btn btn-primary" name="carrousel_update">Mettre à jour le carrousel</button>
        </form>
    </section>

    <hr>


    <!-- Gestion des catégories mises en avant -->
    <section id="highlighted-categories">
        <h2>Catégories en Avant</h2>
        <form method="POST">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nom de la Catégorie</th>

                        <th>Mise en avant</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $categorie): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($categorie['categorie_nom']); ?></td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="highlighted_categories[]" value="<?php echo $categorie['categorie_id']; ?>" <?php echo $categorie['categorie_highlight'] ? 'checked' : ''; ?>>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary" name="update_categories">Mettre à jour les catégories</button>
        </form>
    </section>


    <hr>

    <!-- Gestion des produits "Highlanders" -->
    <section id="highlanders-management">
        <h2>Les Highlanders du Moment</h2>
        <form method="POST">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nom du Produit</th>
                        <th>Ordre</th>
                        <th>Highlander</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produits as $produit): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($produit['produit_nom']); ?></td>
                            <td>
                                <input type="number" class="form-control" name="ordre_highlander[<?php echo $produit['produit_id']; ?>]" value="<?php echo $produit['ordre_highlander'] ?? ''; ?>">
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="highlander_products[]" value="<?php echo $produit['produit_id']; ?>" <?php echo $produit['produit_highlander'] ? 'checked' : ''; ?>>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary" name="update_highlanders">Mettre à jour les Highlanders</button>
        </form>
    </section>
    <!-- Ajouter des produits supplémentaires ici -->
    </tbody>
    </table>
    </section>
    <?php BO_ContenuDashboardFermeture(); ?>
</body>

</html>