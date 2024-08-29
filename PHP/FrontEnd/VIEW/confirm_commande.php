<?php
include '../../Fonction/element.php';
include '../../Fonction/conf.php';
include '../../Fonction/db.php';
include '../../Fonction/auth.php';

$dbh = connexion_bdd();

// Exemple de récupération des détails de la commande depuis la session ou une base de données
// Pour cet exemple, nous allons simuler des données de commande
$commande = [
    [
        'produit_nom' => 'Produit 1',
        'quantite' => 2,
        'prix_unitaire' => 49.99
    ],
    [
        'produit_nom' => 'Produit 2',
        'quantite' => 1,
        'prix_unitaire' => 29.99
    ],
    [
        'produit_nom' => 'Produit 3',
        'quantite' => 3,
        'prix_unitaire' => 39.99
    ]
];

// Calcul du total
$total = 0;
foreach ($commande as $item) {
    $total += $item['quantite'] * $item['prix_unitaire'];
}
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmation de Commande</title>
    <link rel="stylesheet" href="../CSS/accueil.css">
    <?php headerElementPrint(); ?>
    <style>
        .confirmation-table th,
        .confirmation-table td {
            text-align: center;
        }

        .confirmation-table {
            margin: 20px auto;
            max-width: 800px;
        }

        .confirmation-table th {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <?php afficherNavbar($dbh); ?>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Confirmation de Commande</h1>

        <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Votre commande a été passée avec succès !</h4>
            <p>Merci pour votre achat. Vous recevrez un e-mail de confirmation à l'adresse suivante :</p>
            <p><strong>example@example.com</strong></p>
            <hr>
            <p class="mb-0">Votre commande sera envoyée à l'adresse suivante :</p>
            <address>
                123 Rue de l'Exemple,<br>
                75000 Paris,<br>
                France
            </address>
            <p class="mb-0">Le paiement a été effectué par carte bancaire. Détails de la carte :</p>
            <p><strong>CB **** **** **** 3486</strong></p>
        </div>

        <h3 class="text-center mb-4">Récapitulatif de la Commande</h3>

        <table class="table table-bordered confirmation-table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($commande as $item) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['produit_nom']); ?></td>
                        <td><?php echo htmlspecialchars($item['quantite']); ?></td>
                        <td><?php echo number_format($item['prix_unitaire'], 2, ',', ' '); ?> €</td>
                        <td><?php echo number_format($item['quantite'] * $item['prix_unitaire'], 2, ',', ' '); ?> €</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total</th>
                    <th><?php echo number_format($total, 2, ',', ' '); ?> €</th>
                </tr>
            </tfoot>
        </table>
        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-primary">Retour à l'accueil</a>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoDa5a9F0QwS5c5N9zdoSm+NYzFJ8huFt1dJRJZ1HA+famK" crossorigin="anonymous"></script>
</body>

</html>