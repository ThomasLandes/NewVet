<?php 
include '../../Fonction/element.php';
include '../../Fonction/auth.php';


autoriserOnlyAdmin();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Back Office</title>
    <style>
        .table-wrapper {
            margin: 20px auto;
            max-width: 1200px;
        }
        .table thead th {
            background-color: #f8f9fa;
        }
        .status-pending {
            color: #ffc107;
        }
        .status-shipped {
            color: #17a2b8;
        }
        .status-delivered {
            color: #28a745;
        }
        .status-canceled {
            color: #dc3545;
        }
    </style>
    <?php BO_headerElementPrint();?>
  
  </head>
  <body>
    <?php BO_afficherNavbar();
    BO_ContenuDashboardOuverture()?>

    <h1 class="h2">Gestion des Commandes</h1>
    <div class="container table-wrapper">
        <h1 class="text-center mb-4">Répartition des Commandes</h1>
        
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Commande ID</th>
                    <th>État</th>
                    <th>Date de Création</th>
                    <th>Date d'Envoi</th>
                    <th>Adresse Livraison ID</th>
                    <th>Adresse Facturation ID</th>
                    <th>Paiement ID</th>
                    <th>Utilisateur ID</th>
                </tr>
            </thead>
            <tbody>
                <!-- Exemple de lignes de tableau, répétez pour créer les 20 commandes -->
                <tr>
                    <td>1001</td>
                    <td class="status-pending">En attente</td>
                    <td>2024-08-01</td>
                    <td>-</td>
                    <td>501</td>
                    <td>502</td>
                    <td>201</td>
                    <td>301</td>
                </tr>
                <tr>
                    <td>1002</td>
                    <td class="status-shipped">Expédiée</td>
                    <td>2024-08-02</td>
                    <td>2024-08-05</td>
                    <td>503</td>
                    <td>504</td>
                    <td>202</td>
                    <td>302</td>
                </tr>
                <tr>
                    <td>1003</td>
                    <td class="status-delivered">Livrée</td>
                    <td>2024-08-03</td>
                    <td>2024-08-06</td>
                    <td>505</td>
                    <td>506</td>
                    <td>203</td>
                    <td>303</td>
                </tr>
                <tr>
                    <td>1004</td>
                    <td class="status-canceled">Annulée</td>
                    <td>2024-08-04</td>
                    <td>-</td>
                    <td>507</td>
                    <td>508</td>
                    <td>204</td>
                    <td>304</td>
                </tr>
                <!-- Répétez ces lignes pour obtenir une vingtaine d'exemples -->
                <tr>
                    <td>1005</td>
                    <td class="status-pending">En attente</td>
                    <td>2024-08-05</td>
                    <td>-</td>
                    <td>509</td>
                    <td>510</td>
                    <td>205</td>
                    <td>305</td>
                </tr>
                <tr>
                    <td>1006</td>
                    <td class="status-shipped">Expédiée</td>
                    <td>2024-08-06</td>
                    <td>2024-08-09</td>
                    <td>511</td>
                    <td>512</td>
                    <td>206</td>
                    <td>306</td>
                </tr>
                <!-- Ajoutez d'autres lignes ici pour compléter les 20 commandes -->
            </tbody>
        </table>
    </div>
    <?php BO_ContenuDashboardFermeture();?>
</body>

</html>
