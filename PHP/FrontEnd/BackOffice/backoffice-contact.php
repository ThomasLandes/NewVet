<?php
include '../../Fonction/element.php';
include '../../Fonction/db.php';
include '../../Fonction/conf.php';
include '../../Fonction/auth.php';

autoriserOnlyAdmin();
$dbh = connexion_bdd();

// Récupérer les messages de contact
$sql = "SELECT contact_id, contact_nom, contact_email, contact_message, contact_date FROM contact ORDER BY contact_date DESC";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion des Contacts - Back Office</title>
    <?php BO_headerElementPrint(); ?>
</head>
<body>
<?php BO_afficherNavbar();
BO_ContenuDashboardOuverture();?>

<div class="container mt-5">
    <h1 class="h2">Gestion des Messages de Contact</h1>

    <!-- Tableau des messages de contact -->
    <table class="table table-bordered table-hover BO_Tableau">
        <thead class="table-light">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Nom</th>
            <th scope="col">Email</th>
            <th scope="col">Message</th>
            <th scope="col">Date</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($contacts as $contact): ?>
            <tr>
                <td><?php echo htmlspecialchars($contact['contact_id']); ?></td>
                <td><?php echo htmlspecialchars($contact['contact_nom']); ?></td>
                <td><?php echo htmlspecialchars($contact['contact_email']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($contact['contact_message'])); ?></td>
                <td><?php echo htmlspecialchars($contact['contact_date']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php if (empty($contacts)): ?>
        <p>Aucun message trouvé.</p>
    <?php endif; ?>
</div>

</body>
</html>
