<?php
include '../../Fonction/element.php';
include '../../Fonction/conf.php';
include '../../Fonction/db.php';
include '../../Fonction/auth.php';
// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$dbh = connexion_bdd();

// Récupérer les informations du panier depuis les cookies ou la session
$panier = isset($_COOKIE['panier']) ? json_decode($_COOKIE['panier'], true) : array();
$userId = isset($_SESSION['utilisateur_id']) ? $_SESSION['utilisateur_id'] : null;

if (empty($panier)) {
    // Si le panier est vide, redirection vers index.php
    header('Location: index.php');
    exit();
}

// Récupérer les adresses et les cartes de paiement de l'utilisateur si connecté
$adresses = recupUserAdresseParUser($dbh, $userId);
$cartes = recupCbParUser($dbh, $userId);
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout</title>
    <?php headerElementPrint(); ?>
    <script>
        function hideButton(button) {
            button.style.display = 'none';
        }

        function showReview() {
            const selectedAddress = document.querySelector('input[name="adresse_id"]:checked');
            const selectedCard = document.querySelector('input[name="carte_id"]:checked'); // Utiliser l'ID de paiement

            if (selectedAddress && selectedCard) {
                document.getElementById('reviewAddress').textContent = selectedAddress.nextElementSibling.textContent;
                document.getElementById('reviewCard').textContent = '**** **** **** ' + selectedCard.nextElementSibling.textContent.slice(-4);

                // Remplir les champs cachés avec les valeurs sélectionnées
                document.getElementById('hiddenAddress').value = selectedAddress.value;
                document.getElementById('hiddenCard').value = selectedCard.value;  // L'ID de paiement

                // Afficher l'étape de révision
                document.getElementById('collapseReview').classList.remove('collapse');
            } else {
                alert('Veuillez sélectionner une adresse et un moyen de paiement.');
            }
        }

    </script>
</head>

<body>
<?php afficherNavbar($dbh); ?>
<div class="container mt-5">

    <?php
    // Vérifier si le paramètre 'error' est défini dans l'URL et si sa valeur est 'stock'
    if (isset($_GET['error']) && $_GET['error'] === 'stock') {
        echo '<div class="alert alert-danger" role="alert">Stock insuffisant pour certains produits.</div>';
    }
    ?>

    <!-- Etape 1 : Récapitulatif du panier -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    Récapitulatif du panier
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Quantité</th>
                            <th>Prix</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($panier as $index => $produit) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($produit['nom']); ?></td>
                                <td>
                                    <input type="number" name="quantite_<?php echo $index; ?>"
                                           value="<?php echo htmlspecialchars($produit['quantite']); ?>" min="1"
                                           class="form-control" style="width: 80px; display:inline-block;">
                                    <button class="btn btn-primary" onclick="updateQuantity(<?php echo $index; ?>)">
                                        Modifier
                                    </button>
                                </td>
                                <td><?php echo htmlspecialchars(number_format($produit['prix'], 2)); ?> €</td>
                                <td><?php echo htmlspecialchars(number_format($produit['quantite'] * $produit['prix'], 2)); ?>
                                    €
                                </td>
                                <td>
                                    <button class="btn btn-danger" onclick="removeFromCart(<?php echo $index; ?>)">
                                        Supprimer
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#collapseAddress"
                            onclick="hideButton(this)">
                        Valider et passer à l'étape suivante
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Etape 2 : Sélection d'adresse -->
    <div class="row mt-3 collapse" id="collapseAddress">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    Adresse de livraison
                </div>
                <div class="card-body">
                    <?php if (!empty($adresses)) : ?>
                        <?php foreach ($adresses as $adresse) : ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="adresse_id"
                                       value="<?php echo htmlspecialchars($adresse['adresse_id']); ?>">
                                <label class="form-check-label">
                                    <?php echo htmlspecialchars($adresse['adresse_prenom']) . ' ' . htmlspecialchars($adresse['adresse_nom']) . ', ' . htmlspecialchars($adresse['adresse_rue']) . ', ' . htmlspecialchars($adresse['adresse_complement']) . ', ' . htmlspecialchars($adresse['adresse_region']) . ', ' . htmlspecialchars($adresse['adresse_ville']) . ', ' . htmlspecialchars($adresse['adresse_pays']); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                        <p><a href="add_adresse.php">Ajouter une nouvelle adresse</a></p>
                    <?php else : ?>
                        <p>Aucune adresse enregistrée. <a href="add_adresse.php">Ajouter une nouvelle adresse</a></p>
                    <?php endif; ?>
                    <button class="btn btn-primary mt-3" data-bs-toggle="collapse" data-bs-target="#collapsePayment"
                            onclick="hideButton(this)">
                        Suivant
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Etape 3 : Moyen de paiement -->
    <div class="row mt-3 collapse" id="collapsePayment">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    Moyen de paiement
                </div>
                <div class="card-body">
                    <?php if (!empty($cartes)) : ?>
                        <?php foreach ($cartes as $carte) : ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="carte_id"
                                       value="<?php echo htmlspecialchars($carte['paiement_id']); ?>">
                                <label class="form-check-label">
                                    **** ****
                                    **** <?php echo htmlspecialchars(substr($carte['paiement_numero'], -4)); ?>
                                    <?php
                                    // Récupérer et formater la date d'expiration
                                    $expiration = $carte['paiement_date_exp']; // Assurez-vous que cette clé existe
                                    $mois = substr($expiration, 0, 2);
                                    $annee = substr($expiration, 2, 2);
                                    $formattedDate = $mois . '/' . $annee;
                                    ?>
                                    (Date exp: <?php echo htmlspecialchars($formattedDate); ?>)
                                </label>
                            </div>
                        <?php endforeach; ?>

                    <?php else : ?>
                        <p>Aucune carte enregistrée. <a href="add_card.php">Ajouter une carte</a></p>
                    <?php endif; ?>
                    <button class="btn btn-primary mt-3" onclick="showReview()">
                        Suivant
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Étape 4 : Révision et confirmation -->
    <form action="confirm_commande.php" method="POST">
        <div class="row mt-3 collapse" id="collapseReview">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        Révision et confirmation
                    </div>
                    <div class="card-body">
                        <p><strong>Adresse de livraison :</strong> <span id="reviewAddress"></span></p>
                        <p><strong>Moyen de paiement :</strong> <span id="reviewCard"></span></p>
                        <p><strong>Total
                                :</strong> <?php echo htmlspecialchars(number_format(array_sum(array_map(fn($item) => $item['quantite'] * $item['prix'], $panier)), 2)); ?>
                            € TTC</p>

                        <!-- Champs cachés pour envoyer l'adresse et le paiement sélectionnés -->
                        <input type="hidden" name="adresse_id" id="hiddenAddress">
                        <input type="hidden" name="carte_id" id="hiddenCard">

                        <button type="submit" class="btn btn-success">Confirmer la commande</button>
                    </div>
                </div>
            </div>
        </div>
    </form>


</div>
<?php afficherFooter(); ?>
</body>
<script>
    function updateQuantity(index) {
        const quantity = document.querySelector(`input[name="quantite_${index}"]`).value;

// Vérifier que la quantité est valide
        if (quantity <= 0) {
            alert("La quantité doit être supérieure à zéro.");
            return;
        }

// Envoi de la requête AJAX
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "update_cart_ajax.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                location.reload(); // Recharge la page pour actualiser le panier
            }
        };
        xhr.send(`index=${index}&quantite=${quantity}`);
    }

    function removeFromCart(index) {
        // Confirmation de suppression
        if (!confirm("Voulez-vous vraiment supprimer cet article ?")) {
            return;
        }

        // Envoi de la requête AJAX
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "remove_from_cart_ajax.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                location.reload(); // Recharge la page pour actualiser le panier
            }
        };
        xhr.send(`index=${index}`);
    }

</script>
</html>
