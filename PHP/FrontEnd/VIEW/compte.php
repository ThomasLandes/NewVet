<?php
include '../../Fonction/element.php';
include '../../Fonction/conf.php';
include '../../Fonction/db.php';
include '../../Fonction/auth.php';

$dbh = connexion_bdd();

// Vérifier si l'utilisateur est connecté
$userInfo = getUserInfo();

if ($userInfo === null) {
    // Si l'utilisateur n'est pas connecté, redirection vers la page de connexion
    header('Location: connexion.php');
    exit();
}

// Récupérer l'ID utilisateur de l'URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Vérifier si l'ID dans l'URL correspond à l'utilisateur connecté
if ($id !== $userInfo['user_id']) {
    // Si ce n'est pas le cas, rediriger l'utilisateur vers son propre compte
    header('Location: compte.php?id=' . $userInfo['user_id']);
    exit();
}
$userId = $_SESSION['utilisateur_id'];
$userInfo = recupUserInfoParID($dbh, $userId);
$userAdresse = recupUserAdresseParUser($dbh, $userId);
$userCb = recupCbParUser($dbh, $userId);

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mon Compte</title>
    <?php headerElementPrint(); ?>
</head>

<body>
<?php
afficherNavbar($dbh);
?>
<div class="container mt-5">
    <div class="row">
        <!-- Navigation latérale -->
        <div class="col-md-3">
            <div class="list-group" id="accountMenu">
                <a href="#collapseInfos" class="list-group-item list-group-item-action" data-bs-toggle="collapse"
                   data-bs-parent="#accordionAccount">Mes informations perso</a>
                <a href="#collapseCommandes" class="list-group-item list-group-item-action" data-bs-toggle="collapse"
                   data-bs-parent="#accordionAccount">Mes commandes</a>
                <a href="#collapseAdresses" class="list-group-item list-group-item-action" data-bs-toggle="collapse"
                   data-bs-parent="#accordionAccount">Mes adresses</a>
                <a href="#collapsePaiements" class="list-group-item list-group-item-action" data-bs-toggle="collapse"
                   data-bs-parent="#accordionAccount">Mes moyens de paiements</a>
            </div>
        </div>

        <!-- Contenu des sections -->
        <div class="col-md-9">
            <!-- Mes informations personnelles (Ouvert par défaut) -->
            <div id="accordionAccount">
                <div id="collapseInfos" class="collapse show" data-bs-parent="#accordionAccount">
                    <h3>Mes informations personnelles</h3>
                    <form action="update_info.php" method="POST">
                        <div class="mb-3">
                            <label for="adresse_prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="adresse_prenom" name="adresse_prenom"
                                   value="<?= htmlspecialchars($userInfo['utilisateur_prenom']); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="adresse_nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="adresse_nom" name="adresse_nom"
                                   value="<?= htmlspecialchars($userInfo['utilisateur_nom']); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="adresse_tel" class="form-label">Téléphone</label>
                            <input type="text" class="form-control" id="adresse_tel" name="adresse_tel"
                                   value="<?= htmlspecialchars($userInfo['utilisateur_tel']); ?>" readonly>
                        </div>
                        <a href="modif_info.php?id=<?php echo htmlspecialchars($_SESSION['utilisateur_id']); ?>"
                           class="btn btn-primary">Modifier mes infos</a>
                    </form>
                </div>
            </div>


            <!-- Mes commandes -->
            <div id="collapseCommandes" class="collapse" data-bs-parent="#accordionAccount">
                <h3>Mes commandes</h3>
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Date</th>
                        <th scope="col">Statut</th>
                        <th scope="col">Total</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    // Requête pour récupérer les commandes avec le total pour chaque commande
                    $sql = "
    SELECT 
        c.commande_id, 
        c.commande_date_crea, 
        c.commande_etat,
        SUM(cc.contenu_prix_unite * cc.contenu_quantite) AS total
    FROM 
        commande c
    JOIN 
        contenu_commande cc ON c.commande_id = cc.commande_id
    WHERE 
        c.utilisateur_id = :utilisateur_id
    GROUP BY 
        c.commande_id
    ORDER BY 
        c.commande_date_crea DESC
";

                    $stmt = $dbh->prepare($sql);
                    $stmt->bindValue(':utilisateur_id', $id);
                    $stmt->execute();
                    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Vérifier s'il y a des commandes
                    if (count($commandes) > 0) {
                        foreach ($commandes as $commande) {
                            echo '<tr>';
                            echo '<th scope="row">' . htmlspecialchars($commande['commande_id']) . '</th>';
                            echo '<td>' . htmlspecialchars(date('d/m/Y', strtotime($commande['commande_date_crea']))) . '</td>';
                            echo '<td>' . htmlspecialchars($commande['commande_etat']) . '</td>';
                            echo '<td>' . number_format($commande['total'], 2, ',', ' ') . ' €</td>';
                            echo '<td><a href="details_commande.php?commande_id=' . htmlspecialchars($commande['commande_id']) . '" class="btn btn-info">Voir détails</a></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5" class="text-center">Aucune commande trouvée.</td></tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
                    <!-- Mes adresses -->
            <div id="collapseAdresses" class="collapse" data-bs-parent="#accordionAccount">
                <div class="mb-3">
                    <h3>Mes adresses</h3>
                    <?php foreach ($userAdresse as $adresse) : ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <?php if ($adresse['is_principal'] == true) : ?>
                                    <h5 class="card-title">Adresse principale</h5>
                                <?php endif; ?>
                                <p class="card-text">
                                    <?php echo $adresse['adresse_rue'] . ', ' . $adresse['adresse_complement'] . ', ' . $adresse['adresse_region'] . ', ' . $adresse['adresse_ville'] . ', ' . $adresse['adresse_pays']; ?>
                                </p>
                                <!-- Bouton Modifier et icône poubelle -->
                                <a href="modif_adresse.php?id=<?php echo htmlspecialchars($adresse['adresse_id']); ?>"
                                   class="btn btn-primary">Modifier</a>
                                <a href="delete_adresse.php?id=<?php echo htmlspecialchars($adresse['adresse_id']); ?>"
                                   class="btn btn-danger"
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette adresse ?');">
                                    <i class="bi bi-trash"></i>
                                </a>

                            </div>
                        </div>
                    <?php endforeach; ?>
                    <!-- Ajouter une autre adresse -->
                    <a href="add_adresse.php?" class="btn btn-success">Ajouter une adresse</a>
                </div>
            </div>


            <!-- Mes moyens de paiements -->
            <div id="collapsePaiements" class="collapse" data-bs-parent="#accordionAccount">
                <div class="mb-3">
                    <h3>Mes moyens de paiement</h3>
                    <?php foreach ($userCb as $cb) : ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <?php if ($cb['is_principal'] == 1) : ?>
                                    <h5 class="card-title">Carte principale</h5>
                                <?php endif; ?>
                                <p class="card-text">**** **** **** <b><?= $cb['paiement_numero']?></b></p>
                                <?php
                                $expiration = $cb['paiement_date_exp'];
                                $mois = substr($expiration, 0, 2);
                                $annee = substr($expiration, 2, 2);
                                $formattedDate = $mois . '/' . $annee;
                                ?>
                                <p class="card-text">Date exp : <?=$formattedDate?> </p>
                                <a href="delete_cb.php?id=<?php echo htmlspecialchars($cb['paiement_id']); ?>"

                                   class="btn btn-danger"
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette carte bancaire ?');"><i
                                            class="bi bi-trash"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <!-- Ajouter une autre carte -->
                    <a href="add_cb.php?" class="btn btn-success">Ajouter une CB</a>

                </div>

            </div>
            <!-- Bouton de déconnexion -->
            <a href="deconnexion.php" class="btn btn-outline-danger ms-2">
                <i class="bi bi-box-arrow-right"></i> Déconnexion
            </a>
        </div>
    </div>
</div>
</div>

<?php afficherFooter(); ?>
</body>

</html>