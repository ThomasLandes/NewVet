<?php 
include '../../Fonction/element.php';
include '../../Fonction/db.php';
include '../../Fonction/conf.php';

$dbh = connexion_bdd();

// Récupérer les détails des catégories
$categories = recupCategories($dbh);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Back Office</title>
    <?php BO_headerElementPrint();?>
    <script type="text/javascript">
      function confirmDeletion(categorieNom, deleteUrl) {
        if (confirm("Êtes-vous sûr de vouloir supprimer la catégorie \"" + categorieNom + "\" ?")) {
          window.location.href = deleteUrl;
        }
      }
    </script>
  </head>
  <body>
    <?php BO_afficherNavbar();
    BO_ContenuDashboardOuverture(); ?>

    <!-- Affichage des messages de succès ou d'erreur -->
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
      <div class="alert alert-success" role="alert">
        La catégorie a été supprimée avec succès.
      </div>
      <?php elseif (isset($_GET['success']) && $_GET['success'] == 2): ?>
      <div class="alert alert-success" role="alert">
        La catégorie a été modifiée avec succès.
      </div>
    <?php elseif (isset($_GET['error']) && $_GET['error'] == 1): ?>
      <div class="alert alert-danger" role="alert">
        Une erreur est survenue lors de la suppression de la catégorie.
      </div>
    <?php endif; ?>

        <!-- Contenu principal -->
        <h1 class="h2">Gestion des Catégories</h1>

        <!-- Tableau de gestion des catégories -->
        <table class="table table-bordered table-hover BO_Tableau">
            <thead class="table-light">
              <tr>
                <th scope="col">Nom de la Catégorie</th>
                <th scope="col">Description</th>
                <th scope="col">Image</th>
                <th scope="col">Mise en Avant</th>
                <th scope="col">Modifier</th>
                <th scope="col">Supprimer</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($categories as $categorie): ?>
              <tr>
                <td><?php echo htmlspecialchars($categorie['categorie_nom']); ?></td>
                <td><?php echo htmlspecialchars($categorie['categorie_desc']); ?></td>
                <td><img src="<?php echo htmlspecialchars($categorie['categorie_image']); ?>" alt="<?php echo htmlspecialchars($categorie['categorie_nom']); ?>" style="height: 125px;"></td>
                <td>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="highlightCategory<?php echo $categorie['categorie_id']; ?>" <?php echo $categorie['categorie_highlight'] ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="highlightCategory<?php echo $categorie['categorie_id']; ?>"></label>
                  </div>
                </td>
                <td><a href="backoffice-cat-modif.php?id=<?php echo $categorie['categorie_id']; ?>" class="btn btn-warning btn-sm">Modifier</a></td>
                <td>
                  <button class="btn btn-danger btn-sm" 
                    onclick="confirmDeletion('<?php echo htmlspecialchars($categorie['categorie_nom']); ?>', 'backoffice-cat-suppr.php?id=<?php echo $categorie['categorie_id']; ?>')">
                    Supprimer
                  </button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <a href="backoffice-cat-ajout.php" class="btn btn-success mt-3">Créer une catégorie</a>
        </main>
      </div>
    </div>
    <?php BO_ContenuDashboardFermeture();?>
  </body>
</html>
