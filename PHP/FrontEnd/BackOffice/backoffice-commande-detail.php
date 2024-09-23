<?php
include '../../Fonction/element.php';
include '../../Fonction/db.php';
include '../../Fonction/conf.php';
include '../../Fonction/auth.php';

autoriserOnlyAdmin();
$dbh = connexion_bdd();

// Récupérer l'ID de la commande à afficher
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: backoffice-commande.php');
    exit();
}

$commande_id = $_GET['id'];

// Requête pour récupérer les détails de la commande
$sql = "
SELECT 
    c.commande_id, 
    c.commande_date_crea, 
    c.commande_etat,
    SUM(cc.contenu_prix_unite * cc.contenu_quantite) AS total,
    CONCAT(af.adresse_rue, ', ', af.adresse_region, ', ', af.adresse_ville, ', ', af.adresse_pays) AS adresse_facturation,
    CONCAT(al.adresse_rue, ', ', al.adresse_region, ', ', al.adresse_ville, ', ', al.adresse_pays) AS adresse_livraison,
    u.utilisateur_email
FROM 
    commande c
JOIN 
    contenu_commande cc ON c.commande_id = cc.commande_id
JOIN 
    adresse af ON c.adresse_facturation_id = af.adresse_id
JOIN 
    adresse al ON c.adresse_livraison_id = al.adresse_id
JOIN 
    utilisateur u ON c.utilisateur_id = u.utilisateur_id
WHERE 
    c.commande_id = :commande_id
GROUP BY 
    c.commande_id
";

$stmt = $dbh->prepare($sql);
$stmt->bindParam(':commande_id', $commande_id, PDO::PARAM_INT);
$stmt->execute();
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commande) {
    header('Location: backoffice-commande.php');
    exit();
}

// Requête pour récupérer les produits associés à la commande
$sqlProduits = "
SELECT 
    p.produit_nom, 
    cc.contenu_quantite, 
    cc.contenu_prix_unite, 
    (cc.contenu_prix_unite * cc.contenu_quantite) AS total_ligne
FROM 
    contenu_commande cc
JOIN 
    produit p ON cc.produit_id = p.produit_id
WHERE 
    cc.commande_id = :commande_id
";

$stmtProduits = $dbh->prepare($sqlProduits);
$stmtProduits->bindParam(':commande_id', $commande_id, PDO::PARAM_INT);
$stmtProduits->execute();
$produits = $stmtProduits->fetchAll(PDO::FETCH_ASSOC);

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Détails de la Commande - Back Office</title>
    <?php BO_headerElementPrint(); ?>
</head>
<body>
<?php BO_afficherNavbar();
BO_ContenuDashboardOuverture();?>

    <h1 class="h2">Détails de la Commande #<?php echo htmlspecialchars($commande['commande_id']); ?></h1>

    <h2 class="h4">Informations Générales</h2>
    <p><strong>Date de création :</strong> <?php echo htmlspecialchars($commande['commande_date_crea']); ?></p>
    <p><strong>Utilisateur :</strong> <?php echo htmlspecialchars($commande['utilisateur_email']); ?></p>
    <p><strong>Statut :</strong> <?php echo htmlspecialchars($commande['commande_etat']); ?></p>
    <p><strong>Adresse de Facturation :</strong> <?php echo htmlspecialchars($commande['adresse_facturation']); ?></p>
    <p><strong>Adresse de Livraison :</strong> <?php echo htmlspecialchars($commande['adresse_livraison']); ?></p>

    <h2 class="h4">Produits</h2>
    <table class="table table-bordered table-hover BO_Tableau">
        <thead class="table-light">
        <tr>
            <th scope="col">Nom du Produit</th>
            <th scope="col">Quantité</th>
            <th scope="col">Prix Unitaire</th>
            <th scope="col">Total Ligne</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($produits as $produit): ?>
            <tr>
                <td><?php echo htmlspecialchars($produit['produit_nom']); ?></td>
                <td><?php echo htmlspecialchars($produit['contenu_quantite']); ?></td>
                <td><?php echo number_format($produit['contenu_prix_unite'], 2); ?> €</td>
                <td><?php echo number_format($produit['total_ligne'], 2); ?> €</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h3 class="h5">Total de la commande : <?php echo number_format($commande['total'], 2); ?> €</h3>

    <a href="backoffice-commande.php" class="btn btn-secondary">Retour à la liste des commandes</a>
</div>
</body>
</html>
