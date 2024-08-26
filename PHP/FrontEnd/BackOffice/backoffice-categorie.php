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
    <?php BO_ContenuDashboardFermeture();?>
  </body>
</html>
