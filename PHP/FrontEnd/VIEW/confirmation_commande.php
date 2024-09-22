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
    // Si l'utilisateur n'est pas connecté, redirection vers la page de connexion
    header('Location: connexion.php');
    exit();
}

$dbh = connexion_bdd();

// Vérifier si le paramètre commande_id est donné
if (!isset($_GET['commande_id']) || empty($_GET['commande_id'])) {
    // Si commande_id est manquant, redirection vers une page d'erreur ou une autre page
    header('Location: index.php?error=missing_order_id'); // Vous pouvez personnaliser cette URL
    exit();
}

// Récupérer l'ID de la commande depuis l'URL
$commandeId = $_GET['commande_id'];

// Vérifier si la commande appartient à l'utilisateur connecté
$sql = "SELECT * FROM commande WHERE commande_id = :commande_id AND utilisateur_id = :utilisateur_id";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':commande_id', $commandeId);
$stmt->bindValue(':utilisateur_id', $_SESSION['utilisateur_id']);
$stmt->execute();
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

// Si aucune commande n'est trouvée, rediriger vers une page d'erreur
if ($commande === false) {
    header('Location: index.php?error=order_not_found'); // Personnaliser l'URL si nécessaire
    exit();
}


// Supprimer les cookies créés, y compris ceux du panier
if (isset($_COOKIE['panier'])) {
    setcookie('panier', '', time() - 3600, '/');  // Suppression du cookie panier
}

// Récupérer les détails de la commande
$sql = "SELECT * FROM commande WHERE commande_id = :commande_id";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':commande_id', $commandeId);
$stmt->execute();
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer les articles de la commande
$contenuCommande = getContenuCommandeId($dbh, $commandeId);

// Initialiser le total
$total = 0;
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmation de Commande</title>
    <?php headerElementPrint(); ?>
</head>

<body>
<?php afficherNavbar($dbh); ?>
<div class="container mt-5">
    <h1 class="text-center mb-4">Confirmation de Commande</h1>

    <div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Votre commande a été passée avec succès !</h4>
        <p>Merci pour votre achat. Votre numéro de commande est : <strong><?php echo htmlspecialchars($commande['commande_id']); ?></strong></p>
        <hr>
        <p class="mb-0">Vous recevrez un e-mail de confirmation avec les détails de votre commande.</p>
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
        <?php foreach ($contenuCommande as $item) : ?>
            <tr>
                <td><?php echo htmlspecialchars($item['produit_nom']); ?></td>
                <td><?php echo htmlspecialchars($item['contenu_quantite']); ?></td>
                <td><?php echo number_format($item['contenu_prix_unite'], 2, ',', ' '); ?> €</td>
                <td><?php echo number_format($item['contenu_quantite'] * $item['contenu_prix_unite'], 2, ',', ' '); ?> €</td>
            </tr>
            <?php
            // Calculer le total pour chaque article
            $total += $item['contenu_quantite'] * $item['contenu_prix_unite'];
            ?>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <th colspan="3" class="text-end">Total</th>
            <th><?php echo number_format($total, 2, ',', ' '); ?> €</th>
        </tr>
        </tfoot>
    </table>
</div>
<?php afficherFooter(); ?>
</body>

</html>
