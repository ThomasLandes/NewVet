<?php
include '../../Fonction/element.php';
include '../../Fonction/conf.php';
include '../../Fonction/db.php';
include '../../Fonction/auth.php';
$dbh = connexion_bdd();
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout</title>
    <?php headerElementPrint(); ?>
    <script>
      // Fonction pour masquer le bouton après clic
      function hideButton(button) {
        button.style.display = 'none';
      }
    </script>
</head>

<body>
<?php
    afficherNavbar($dbh);
    ?>
    <div class="container mt-5">
      <!-- Dropdown 1: Adresse de livraison -->
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-header">
              Adresse de livraison
            </div>
            <div id="collapseAddress" class="collapse show">
              <div class="card-body">
                <form>
                  <div class="mb-3">
                    <label for="adresse1" class="form-label">Adresse 1</label>
                    <input type="text" class="form-control" id="adresse1" placeholder="Adresse principale">
                  </div>
                  <div class="mb-3">
                    <label for="adresse2" class="form-label">Adresse 2</label>
                    <input type="text" class="form-control" id="adresse2" placeholder="Adresse secondaire">
                  </div>
                  <button type="button" class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#collapsePayment" aria-expanded="false" aria-controls="collapsePayment" onclick="hideButton(this)">
                    Suivant
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Dropdown 2: Moyen de paiement -->
      <div class="row mt-3">
        <div class="col">
          <div class="card">
            <div class="card-header">
              Moyen de paiement
            </div>
            <div id="collapsePayment" class="collapse">
              <div class="card-body">
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
                  <button type="button" class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#collapseReview" aria-expanded="false" aria-controls="collapseReview" onclick="hideButton(this)">
                    Suivant
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Dropdown 3: Révision et confirmation -->
      <div class="row mt-3">
        <div class="col">
          <div class="card">
            <div class="card-header">
              Révision et confirmation
            </div>
            <div id="collapseReview" class="collapse">
              <div class="card-body">
                <p><strong>Adresse de livraison :</strong> Adresse 1, Adresse 2</p>
                <p><strong>Moyen de paiement :</strong> **** **** **** 3456</p>
                <p><strong>Total :</strong> 79,98 €</p>
                <a href="confirm_commande.php" class="btn btn-success">Confirmer la commande</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php afficherFooter();?>