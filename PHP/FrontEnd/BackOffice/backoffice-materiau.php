<?php
include '../../Fonction/element.php';
include '../../Fonction/db.php';
include '../../Fonction/conf.php';
include '../../Fonction/auth.php';

autoriserOnlyAdmin();
$dbh = connexion_bdd();

// Récupérer les détails des matériaux
$sql = "SELECT materiau_id, materiau_nom FROM materiau ORDER BY materiau_id ASC";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$materiaux = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion des Matériaux - Back Office</title>
    <?php BO_headerElementPrint(); ?>
    <script type="text/javascript">
        function confirmDeletion(materiauNom, deleteUrl) {
            if (confirm("Êtes-vous sûr de vouloir supprimer le matériau \"" + materiauNom + "\" ?")) {
                window.location.href = deleteUrl;
            }
        }
    </script>
</head>
<body>
    <?php BO_afficherNavbar(); ?>
    <?php BO_ContenuDashboardOuverture(); ?>

    <!-- Affichage des messages de succès ou d'erreur -->
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="alert alert-success" role="alert">
            Le matériau a été supprimé avec succès.
        </div>
    <?php elseif (isset($_GET['error']) && $_GET['error'] == 1): ?>
        <div class="alert alert-danger" role="alert">
            Une erreur est survenue lors de la suppression du matériau.
        </div>
    <?php endif; ?>

    <!-- Contenu principal -->
    <h1 class="h2">Gestion des Matériaux</h1>

    <!-- Tableau de gestion des matériaux -->
    <table class="table table-bordered table-hover BO_Tableau">
        <thead class="table-light">
            <tr>
                <th scope="col">Nom du Matériau</th>
                <th scope="col">Modifier</th>
                <th scope="col">Supprimer</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($materiaux as $materiau): ?>
            <tr>
                <td><?php echo htmlspecialchars($materiau['materiau_nom']); ?></td>
                <td><a href="backoffice-mat-modif.php?id=<?php echo $materiau['materiau_id']; ?>" class="btn btn-warning btn-sm">Modifier</a></td>
                <td>
                    <button class="btn btn-danger btn-sm"
                        onclick="confirmDeletion('<?php echo htmlspecialchars($materiau['materiau_nom']); ?>', 'backoffice-mat-suppr.php?id=<?php echo $materiau['materiau_id']; ?>')">
                        Supprimer
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="backoffice-mat-ajout.php" class="btn btn-success mt-3">Ajouter un matériau</a>

    <?php BO_ContenuDashboardFermeture(); ?>
</body>
</html>
