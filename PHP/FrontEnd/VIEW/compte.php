<?php
include '../../Fonction/element.php';
include '../../Fonction/conf.php';
include '../../Fonction/db.php';
include '../../Fonction/auth.php';

$dbh = connexion_bdd();
$TEST = isAdmin();
print_r($TEST);

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
          <a href="#collapseInfos" class="list-group-item list-group-item-action" data-bs-toggle="collapse" data-bs-parent="#accordionAccount">Mes informations perso</a>
          <a href="#collapseCommandes" class="list-group-item list-group-item-action" data-bs-toggle="collapse" data-bs-parent="#accordionAccount">Mes commandes</a>
          <a href="#collapseAdresses" class="list-group-item list-group-item-action" data-bs-toggle="collapse" data-bs-parent="#accordionAccount">Mes adresses</a>
          <a href="#collapsePaiements" class="list-group-item list-group-item-action" data-bs-toggle="collapse" data-bs-parent="#accordionAccount">Mes moyens de paiements</a>
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
                <input type="text" class="form-control" id="adresse_prenom" name="adresse_prenom" placeholder="Votre prénom" value="Pascal">
              </div>
              <div class="mb-3">
                <label for="adresse_nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="adresse_nom" name="adresse_nom" placeholder="Votre nom" value="Duromi">
              </div>
              <div class="mb-3">
                <label for="adresse_tel" class="form-label">Téléphone</label>
                <input type="text" class="form-control" id="adresse_tel" name="adresse_tel" placeholder="Votre téléphone" value="0809121145">
              </div>
              <button type="submit" class="btn btn-primary">Mettre à jour</button>
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
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row">1</th>
                <td>01/01/2024</td>
                <td>Livré</td>
                <td>49,99 €</td>
              </tr>
              <tr>
                <th scope="row">2</th>
                <td>15/02/2024</td>
                <td>En cours</td>
                <td>29,99 €</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Mes adresses -->
        <div id="collapseAdresses" class="collapse" data-bs-parent="#accordionAccount">
          <h3>Mes adresses</h3>
          <form action="update_adresse.php" method="POST">
            <div class="mb-3">
              <label for="adresse_rue" class="form-label">Rue</label>
              <input type="text" class="form-control" id="adresse_rue" name="adresse_rue" placeholder="Rue" value="Rue du sang de serp">
            </div>
            <div class="mb-3">
              <label for="adresse_complement" class="form-label">Complément d'adresse</label>
              <input type="text" class="form-control" id="adresse_complement" name="adresse_complement" placeholder="Complément d'adresse" value="">
            </div>
            <div class="mb-3">
              <label for="adresse_ville" class="form-label">Ville</label>
              <input type="text" class="form-control" id="adresse_ville" name="adresse_ville" placeholder="Ville" value="Toulouse">
            </div>
            <div class="mb-3">
              <label for="adresse_region" class="form-label">Région</label>
              <input type="text" class="form-control" id="adresse_region" name="adresse_region" placeholder="Région" value="31000">
            </div>
            <div class="mb-3">
              <label for="adresse_pays" class="form-label">Pays</label>
              <input type="text" class="form-control" id="adresse_pays" name="adresse_pays" placeholder="Pays" value="France">
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour les adresses</button>
          </form>
        </div>


        <!-- Mes moyens de paiements -->
        <div id="collapsePaiements" class="collapse" data-bs-parent="#accordionAccount">
          <h3>Mes moyens de paiements</h3>
          <form>
            <div class="mb-3">
              <label for="cardNumber" class="form-label">Numéro de carte</label>
              <input type="text" class="form-control" id="cardNumber" placeholder="1234 5678 9012 3456">
            </div>
            <div class="mb-3">
              <label for="cardName" class="form-label">Nom sur la carte</label>
              <input type="text" class="form-control" id="cardName" placeholder="Nom complet">
            </div>
            <div class="mb-3">
              <label for="expiryDate" class="form-label">Date d\'expiration</label>
              <input type="text" class="form-control" id="expiryDate" placeholder="MM/AA">
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour les paiements</button>
          </form>
        </div>
        <!-- Bouton de déconnexion -->
        <a href="deconnexion.php" class="btn btn-outline-danger ms-2">
          <i class="bi bi-box-arrow-right"></i> Déconnexion
        </a>
      </div>
    </div>
  </div>
  </div>


</body>

</html>