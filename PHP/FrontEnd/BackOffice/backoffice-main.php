<?php
include '../../Fonction/element.php';
include '../../Fonction/auth.php';
include '../../Fonction/conf.php';
include '../../Fonction/db.php';

autoriserOnlyAdmin();

// Connexion à la base de données
$conn = connexion_bdd(); // Assurez-vous que cette fonction est définie dans db.php

// Requête pour les ventes par catégorie
$ventesParCategorie = [];
$categorieRequete = "SELECT 
    c.categorie_nom,
    SUM(cc.contenu_prix_unite * cc.contenu_quantite) AS total_ventes
FROM 
    contenu_commande cc
JOIN 
    produit p ON cc.produit_id = p.produit_id
JOIN 
    categorie c ON p.categorie_id = c.categorie_id
JOIN 
    commande cmd ON cc.commande_id = cmd.commande_id
WHERE 
    cmd.commande_etat != 'Annulé' -- Exclure les commandes annulées
GROUP BY 
    c.categorie_id
ORDER BY 
    total_ventes DESC;";
$resultCategorie = $conn->query($categorieRequete);
while ($row = $resultCategorie->fetch(PDO::FETCH_ASSOC)) {
    $ventesParCategorie[] = $row;
}

// Requête pour les ventes par mois
$ventesParMois = [];
$moisRequete = "SELECT 
    DATE_FORMAT(commande_date_crea, '%Y-%m') AS mois,
    SUM(cc.contenu_prix_unite * cc.contenu_quantite) AS total_ventes
FROM 
    commande cmd
JOIN 
    contenu_commande cc ON cmd.commande_id = cc.commande_id
WHERE 
    YEAR(cmd.commande_date_crea) = 2024 
    AND cmd.commande_etat != 'Annulé' -- Exclure les commandes annulées
GROUP BY 
    mois
ORDER BY 
    mois;";
$resultMois = $conn->query($moisRequete);
while ($row = $resultMois->fetch(PDO::FETCH_ASSOC)) {
    $ventesParMois[] = $row;
}

// Requête pour le nombre de commandes par mois
$commandesParMois = [];
$nombreCommandesRequete = "SELECT 
    DATE_FORMAT(commande_date_crea, '%Y-%m') AS mois,
    COUNT(commande_id) AS nombre_commandes
FROM 
    commande
WHERE 
    commande_etat != 'Annulé' -- Exclure les commandes annulées
GROUP BY 
    mois
ORDER BY 
    mois;";
$resultCommandesMois = $conn->query($nombreCommandesRequete);
while ($row = $resultCommandesMois->fetch(PDO::FETCH_ASSOC)) {
    $commandesParMois[] = $row;
}

// Préparer les données pour le graphique
$moisCommandesLabels = array_column($commandesParMois, 'mois');
$nombreCommandesData = array_column($commandesParMois, 'nombre_commandes');

$categorieLabels = array_column($ventesParCategorie, 'categorie_nom');
$categorieData = array_column($ventesParCategorie, 'total_ventes');

$moisLabels = array_column($ventesParMois, 'mois');
$moisData = array_column($ventesParMois, 'total_ventes');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Back Office</title>
    <?php BO_headerElementPrint();?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php BO_afficherNavbar();
BO_ContenuDashboardOuverture()?>
<h1 class="h2">Tableau de bord</h1>
</div>
<div class="container mt-5">
    <h1 class="text-center mb-4">Tableau de Bord</h1>

    <!-- Graphique des évolutions des ventes -->
    <div class="row">
        <div class="col-md-6">
            <h3>Évolutions des Ventes</h3>
            <canvas id="salesEvolutionChart"></canvas>
        </div>

        <!-- Graphique des ventes par catégorie -->
        <div class="col-md-6">
            <h3>Ventes par Catégorie</h3>
            <canvas id="salesByCategoryChart"></canvas>
        </div>
    </div>

    <div class="col-md-6">
        <h3>Nombre de Commandes par Mois</h3>
        <canvas id="ordersByMonthChart"></canvas>
    </div>

</div>

<script>
    // Graphique des ventes par catégorie
    var ctxCategory = document.getElementById('salesByCategoryChart').getContext('2d');
    var salesByCategoryChart = new Chart(ctxCategory, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($categorieLabels); ?>,
            datasets: [{
                label: 'Ventes par Catégorie (€)',
                data: <?php echo json_encode($categorieData); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });

    // Graphique des évolutions des ventes
    var ctx1 = document.getElementById('salesEvolutionChart').getContext('2d');
    var salesEvolutionChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($moisLabels); ?>,
            datasets: [{
                label: 'Ventes (€)',
                data: <?php echo json_encode($moisData); ?>,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Graphique du nombre de commandes par mois
    var ctxOrders = document.getElementById('ordersByMonthChart').getContext('2d');
    var ordersByMonthChart = new Chart(ctxOrders, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($moisCommandesLabels); ?>,
            datasets: [{
                label: 'Nombre de Commandes',
                data: <?php echo json_encode($nombreCommandesData); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });

</script>

</body>
</html>
