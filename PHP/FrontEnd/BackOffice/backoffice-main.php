<?php 
include '../../Fonction/element.php';
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Back Office</title>
    <?php BO_headerElementPrint();?>
  
  </head>
  <body>
    <?php BO_afficherNavbar();
    BO_ContenuDashboardOuverture()?>
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2">Tableau de bord</h1>
    </div>
    <?php BO_ContenuDashboardFermeture();?>
  </body>
</html>
