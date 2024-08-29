<?php 
include '../../Fonction/element.php';
include '../../Fonction/auth.php';
include '../../Fonction/conf.php';
include '../../Fonction/db.php';



autoriserOnlyAdmin();
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
    </div>

    <script>
        // Graphique des évolutions des ventes
        var ctx1 = document.getElementById('salesEvolutionChart').getContext('2d');
        var salesEvolutionChart = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
                datasets: [{
                    label: 'Ventes (€)',
                    data: [1200, 1500, 1800, 1600, 2000, 2200, 2100, 2300, 2400, 2500, 2600, 2800],
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

        // Graphique des ventes par catégorie
        var ctx2 = document.getElementById('salesByCategoryChart').getContext('2d');
        var salesByCategoryChart = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['ROBE', 'PANTALON', 'HAUT', 'SURVETMENT', 'PULL'],
                datasets: [{
                    label: 'Ventes (%)',
                    data: [35, 25, 15, 15, 10],
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
    </script>

</body>
</html>

