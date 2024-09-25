
<?php
include '../../Fonction/db.php';
include '../../Fonction/conf.php';
include '../../Fonction/auth.php';

autoriserOnlyAdmin();
$dbh = connexion_bdd();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['status'])) {
    $contactId = intval($_POST['id']);
    $status = $_POST['status'];

    // Vérifier les valeurs possibles
    $validStatuses = ['à traiter', 'en cours de traitement', 'traité'];
    if (in_array($status, $validStatuses)) {
        $stmt = $dbh->prepare("UPDATE contact SET contact_status = :status WHERE contact_id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $contactId);
        $stmt->execute();
        echo 'Statut mis à jour.';
    } else {
        echo 'Statut invalide.';
    }
} else {
    echo 'Requête invalide.';
}
?>
