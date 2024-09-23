<?php
include '../../Fonction/element.php';
include '../../Fonction/db.php';
include '../../Fonction/conf.php';
include '../../Fonction/auth.php';
autoriserOnlyAdmin();
if (isset($_POST['commande_id']) && isset($_POST['nouveau_statut'])) {
    $commande_id = intval($_POST['commande_id']);
    $nouveau_statut = htmlspecialchars($_POST['nouveau_statut']);

    $dbh = connexion_bdd();

    // Récupérer le statut actuel de la commande
    $sql = "SELECT commande_etat FROM commande WHERE commande_id = :commande_id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':commande_id', $commande_id, PDO::PARAM_INT);
    $stmt->execute();
    $commande = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($commande) {
        // Vérifier si le statut est déjà "Terminé" ou "Annulé"
        if ($commande['commande_etat'] == 'Terminé' || $commande['commande_etat'] == 'Annulé') {
            echo json_encode(['success' => false, 'message' => 'Le statut ne peut plus être modifié.']);
            exit;
        }

        // Mettre à jour le statut si possible
        $sql = "UPDATE commande SET commande_etat = :nouveau_statut WHERE commande_id = :commande_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':nouveau_statut', $nouveau_statut, PDO::PARAM_STR);
        $stmt->bindParam(':commande_id', $commande_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Commande introuvable.']);
    }
}
?>
