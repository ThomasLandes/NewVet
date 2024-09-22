<?php
include '../../Fonction/element.php';
include '../../Fonction/conf.php';
include '../../Fonction/db.php';
include '../../Fonction/auth.php';

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
$userInfo = getUserInfo();
if ($userInfo === null) {
    header('Location: connexion.php');
    exit();
}

$dbh = connexion_bdd();

// Récupérer l'ID de la commande depuis l'URL
$commandeId = $_GET['commande_id'];

// Requête pour récupérer les détails de la commande
$sql = "SELECT c.commande_id, c.commande_date_crea, c.commande_etat, 
               SUM(cc.contenu_prix_unite * cc.contenu_quantite) AS total,
               c.adresse_livraison_id, c.adresse_facturation_id
        FROM commande c
        JOIN contenu_commande cc ON c.commande_id = cc.commande_id
        WHERE c.commande_id = :commande_id
        AND c.utilisateur_id = :utilisateur_id
        GROUP BY c.commande_id";

$stmt = $dbh->prepare($sql);
$stmt->bindValue(':commande_id', $commandeId);
$stmt->bindValue(':utilisateur_id', $_SESSION['utilisateur_id']);
$stmt->execute();
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si la commande existe
if (!$commande) {
    echo '<div class="alert alert-danger">Commande non trouvée.</div>';
    exit();
}

// Récupérer les articles de la commande
$contenuCommande = getContenuCommandeId($dbh, $commandeId);

// Récupérer les adresses de livraison et de facturation
$adresseLivraison = getAdresseById($dbh, $commande['adresse_livraison_id']);
$adresseFacturation = getAdresseById($dbh, $commande['adresse_facturation_id']);
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Détails de la Commande</title>
    <?php headerElementPrint(); ?>
    <style>
        .invoice {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .invoice-details {
            margin-bottom: 20px;
        }
        .invoice-table th, .invoice-table td {
            padding: 10px;
            text-align: left;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .invoice-table th {
            background-color: #f2f2f2;
        }
        .address {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
<div class="container mt-5">
    <div class="invoice">
        <div class="invoice-header">
            <h1>Facture</h1>
            <p>Numéro de commande : <strong><?php echo htmlspecialchars($commande['commande_id']); ?></strong></p>
            <p>Date : <strong><?php echo htmlspecialchars(date('d/m/Y', strtotime($commande['commande_date_crea']))); ?></strong></p>
            <p>Statut : <strong><?php echo htmlspecialchars($commande['commande_etat']); ?></strong></p>
        </div>

        <div class="address">
            <h3>Adresse de Livraison</h3>
            <p><?php echo htmlspecialchars($adresseLivraison['adresse_nom'] . ' ' . $adresseLivraison['adresse_prenom']); ?></p>
            <p><?php echo htmlspecialchars($adresseLivraison['adresse_rue']); ?></p>
            <p><?php echo htmlspecialchars($adresseLivraison['adresse_complement']); ?></p>
            <p><?php echo htmlspecialchars($adresseLivraison['adresse_ville'] . ', ' . $adresseLivraison['adresse_region'] . ', ' . $adresseLivraison['adresse_pays']); ?></p>
            <p>Téléphone : <?php echo htmlspecialchars($adresseLivraison['adresse_tel']); ?></p>
        </div>

        <div class="address">
            <h3>Adresse de Facturation</h3>
            <p><?php echo htmlspecialchars($adresseFacturation['adresse_nom'] . ' ' . $adresseFacturation['adresse_prenom']); ?></p>
            <p><?php echo htmlspecialchars($adresseFacturation['adresse_rue']); ?></p>
            <p><?php echo htmlspecialchars($adresseFacturation['adresse_complement']); ?></p>
            <p><?php echo htmlspecialchars($adresseFacturation['adresse_ville'] . ', ' . $adresseFacturation['adresse_region'] . ', ' . $adresseFacturation['adresse_pays']); ?></p>
            <p>Téléphone : <?php echo htmlspecialchars($adresseFacturation['adresse_tel']); ?></p>
        </div>

        <div class="invoice-details">
            <h3>Détails de la commande</h3>
            <table class="invoice-table">
                <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($contenuCommande as $item) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['produit_nom']); ?></td>
                        <td><?php echo htmlspecialchars($item['contenu_quantite']); ?></td>
                        <td><?php echo number_format($item['contenu_prix_unite'], 2, ',', ' ') . ' €'; ?></td>
                        <td><?php echo number_format($item['contenu_quantite'] * $item['contenu_prix_unite'], 2, ',', ' ') . ' €'; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total</th>
                    <th><?php echo number_format($commande['total'], 2, ',', ' ') . ' €'; ?></th>
                </tr>
                </tfoot>
            </table>
        </div>
        <a href="compte.php" class="btn btn-primary">Retour</a>
    </div>
</div>
</body>
</html>
