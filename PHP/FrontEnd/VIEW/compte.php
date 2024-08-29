<?php
include '../../Fonction/element.php';
include '../../Fonction/conf.php';
include '../../Fonction/db.php';

$dbh = connexion_bdd();
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
          <!-- Mes informations perso (Ouvert par défaut) -->
          <div id="accordionAccount">
            <div id="collapseInfos" class="collapse show" data-bs-parent="#accordionAccount">
              <h3>Mes informations personnelles</h3>
              <form>
                <div class="mb-3">
                  <label for="nom" class="form-label">Nom</label>
                  <input type="text" class="form-control" id="nom" placeholder="Votre nom">
                </div>
                <div class="mb-3">
                  <label for="prenom" class="form-label">Prénom</label>
                  <input type="text" class="form-control" id="prenom" placeholder="Votre prénom">
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Adresse e-mail</label>
                  <input type="email" class="form-control" id="email" placeholder="Votre e-mail">
                </div>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
              </form>
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
              <form>
                <div class="mb-3">
                  <label for="adresse1" class="form-label">Adresse 1</label>
                  <input type="text" class="form-control" id="adresse1" placeholder="Adresse principale">
                </div>
                <div class="mb-3">
                  <label for="adresse2" class="form-label">Adresse 2</label>
                  <input type="text" class="form-control" id="adresse2" placeholder="Adresse secondaire">
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
                  <label for="expiryDate" class="form-label">Date d'expiration</label>
                  <input type="text" class="form-control" id="expiryDate" placeholder="MM/AA">
                </div>
                <button type="submit" class="btn btn-primary">Mettre à jour les paiements</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

   
  </body>
</html>


  </body>
</html>
