<?php
include '../../Fonction/element.php';
include '../../Fonction/db.php';
include '../../Fonction/conf.php';
include '../../Fonction/auth.php';

autoriserOnlyAdmin();
$dbh = connexion_bdd();

// Récupérer les messages de contact
$sql = "SELECT contact_id, contact_nom, contact_email, contact_message, contact_date, contact_status 
        FROM contact 
        ORDER BY (contact_status = 'à traiter') DESC, contact_date DESC";
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            <th scope="col">Statut de traitement</th> <!-- Nouvelle colonne pour le statut -->
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
                <td>
                    <select class="form-select status-select" data-id="<?php echo htmlspecialchars($contact['contact_id']); ?>" <?php echo $contact['contact_status'] == 'traité' ? 'disabled' : ''; ?>>
                        <option value="à traiter" <?php echo $contact['contact_status'] == 'à traiter' ? 'selected' : ''; ?>>À traiter</option>
                        <option value="en cours de traitement" <?php echo $contact['contact_status'] == 'en cours de traitement' ? 'selected' : ''; ?>>En cours de traitement</option>
                        <option value="traité" <?php echo $contact['contact_status'] == 'traité' ? 'selected' : ''; ?>>Traité</option>
                    </select>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php if (empty($contacts)): ?>
        <p>Aucun message trouvé.</p>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function() {
        $('.status-select').change(function() {
            var status = $(this).val();
            var contactId = $(this).data('id');

            $.ajax({
                url: 'update_contact_status.php', // Script PHP pour mettre à jour le statut
                type: 'POST',
                data: {
                    id: contactId,
                    status: status
                },
                success: function(response) {
                    alert('Statut mis à jour avec succès !');
                },
                error: function() {
                    alert('Erreur lors de la mise à jour du statut.');
                }
            });
        });
    });
</script>

</body>
</html>
