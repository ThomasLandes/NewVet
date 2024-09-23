<?php
include '../../Fonction/element.php';
include '../../Fonction/db.php';
include '../../Fonction/conf.php';
include '../../Fonction/auth.php';

autoriserOnlyAdmin();
$dbh = connexion_bdd();

// Requête pour récupérer les commandes
$sql = "
SELECT 
    c.commande_id, 
    c.commande_date_crea, 
    c.commande_etat,
    SUM(cc.contenu_prix_unite * cc.contenu_quantite) AS total,
    CONCAT(af.adresse_rue, ', ', af.adresse_region, ', ', af.adresse_ville, ', ', af.adresse_pays) AS adresse_facturation,
    CONCAT(al.adresse_rue, ', ', al.adresse_region, ', ', al.adresse_ville, ', ', al.adresse_pays) AS adresse_livraison
FROM 
    commande c
JOIN 
    contenu_commande cc ON c.commande_id = cc.commande_id
JOIN 
    adresse af ON c.adresse_facturation_id = af.adresse_id
JOIN 
    adresse al ON c.adresse_livraison_id = al.adresse_id
GROUP BY 
    c.commande_id
ORDER BY 
    c.commande_date_crea DESC
";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion des Commandes - Back Office</title>
    <?php BO_headerElementPrint(); ?>
</head>
<body>
<?php BO_afficherNavbar(); ?>

    <?php BO_ContenuDashboardOuverture(); ?>
    <h1 class="h2">Gestion des Commandes</h1>

    <!-- Tableau des commandes -->
    <table class="table table-bordered table-hover BO_Tableau">
        <thead class="table-light">
        <tr>
            <th scope="col">ID Commande</th>
            <th scope="col">Date</th>
            <th scope="col">Prix Total</th>
            <th scope="col">Adresse de Facturation</th>
            <th scope="col">Adresse de Livraison</th>
            <th scope="col">Statut</th>
            <th scope="col">Détails</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($commandes as $commande): ?>
            <tr>
                <td><?php echo htmlspecialchars($commande['commande_id']); ?></td>
                <td><?php echo htmlspecialchars($commande['commande_date_crea']); ?></td>
                <td><?php echo number_format($commande['total'], 2); ?> €</td>
                <td><?php echo htmlspecialchars($commande['adresse_facturation']); ?></td>
                <td><?php echo htmlspecialchars($commande['adresse_livraison']); ?></td>
                <td>
                    <select class="form-select form-select-sm"
                            onchange="updateCommandeStatut(<?php echo $commande['commande_id']; ?>, this.value)"
                        <?php echo ($commande['commande_etat'] == 'Terminé' || $commande['commande_etat'] == 'Annulé') ? 'disabled' : ''; ?>>
                        <option value="En cours de traitement" <?php echo ($commande['commande_etat'] == 'En cours de traitement') ? 'selected' : ''; ?>>En cours de traitement</option>
                        <option value="En cours de Livraison" <?php echo ($commande['commande_etat'] == 'En cours de Livraison') ? 'selected' : ''; ?>>En cours de livraison</option>
                        <option value="Terminé" <?php echo ($commande['commande_etat'] == 'Terminé') ? 'selected' : ''; ?>>Terminé</option>
                        <option value="Annulé" <?php echo ($commande['commande_etat'] == 'Annulé') ? 'selected' : ''; ?>>Annulé</option>
                    </select>
                </td>


                <td>
                    <a href="backoffice-commande-detail.php?id=<?php echo $commande['commande_id']; ?>" class="btn btn-info btn-sm">Voir Détails</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php if (empty($commandes)): ?>
        <p>Aucune commande trouvée.</p>
    <?php endif; ?>

<?php BO_ContenuDashboardFermeture(); ?>
</body>
<script type="text/javascript">
    function updateCommandeStatut(commandeId, nouveauStatut) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_commande_etat.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert("Statut mis à jour avec succès !");
                    location.reload(); // Rafraîchir la page
                } else {
                    alert(response.message); // Afficher le message d'erreur
                }
            }
        };
        xhr.send('commande_id=' + encodeURIComponent(commandeId) + '&nouveau_statut=' + encodeURIComponent(nouveauStatut));
    }

</script>
</html>
