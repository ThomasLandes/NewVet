<?php

function headerElementPrint()
{
    echo '
<link rel="stylesheet" href="../CSS/navbar.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
 <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
';
}


function afficherNavbar($dbh)
{ 
    // Récupérer les catégories
    $categories = recupCategories($dbh);

    echo '
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg bg-dark">
        <div class="container-fluid d-flex justify-content-between">
            <!-- Logo à gauche -->
            <a class="navbar-brand" href="index.php">
                <img src="../IMAGE/newVet-logo.png" alt="Logo" width="80" height="80" class="d-inline-block align-text-top">
            </a>

            <!-- Liens de la navbar centrés -->
            <div class="mx-auto">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="produit.php">PRODUIT</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            CATEGORIE
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">';

    foreach ($categories as $categorie) {
        echo '<li><a class="dropdown-item" href="categorie.php?id=' . $categorie['categorie_id'] . '">' . htmlspecialchars($categorie['categorie_nom']) . '</a></li>';
    }

    echo '      </ul>
                    </li>
                </ul>
            </div>

            <!-- Icônes à droite -->
            <div class="d-flex">
                <div class="dropdown">
                    <a href="#" class="btn btn-outline-secondary dropdown-toggle" id="cartDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-cart"></i> Panier
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="cartDropdown" style="min-width: 300px;">
                        <li class="mb-2"><strong>Votre Panier</strong></li>
                        <li>
                            <div class="d-flex justify-content-between">
                                <span>Article 1</span>
                                <span>19,99 €</span>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex justify-content-between">
                                <span>Article 2</span>
                                <span>29,99 €</span>
                            </div>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <div class="d-flex justify-content-between">
                                <strong>Total</strong>
                                <strong>49,98 €</strong>
                            </div>
                        </li>
                        <li class="mt-3 text-center">
                            <a href="checkout.php" class="btn btn-primary btn-sm">Passer à la caisse</a>
                        </li>
                    </ul>
                </div>
                <a href="compte.php" class="btn btn-outline-secondary">
                    <i class="bi bi-person"></i> <!-- Icône de compte -->
                </a>
            </div>
        </div>
    </nav>
    <!-- NAVBAR -->
    ';
}

function BO_afficherNavbar() {
  echo'
  <div class="container-fluid">
      <div class="row">
        <!-- Navbar verticale avec largeur réduite -->
        <nav class="col-md-2 sidebar bg-light">
          <div class="position-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="backoffice-main.php">
                  <i class="bi bi-house-door"></i> Général
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="backoffice-index.php">
                  <i class="bi bi-house"></i> Accueil
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="backoffice-categorie.php">
                  <i class="bi bi-tags"></i> Catégorie
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="backoffice-produit.php">
                  <i class="bi bi-box"></i> Produit
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="backoffice-commande.php">
                  <i class="bi bi-receipt"></i> Commande
                </a>
              </li>
            </ul>
          </div>
        </nav>';
}


function BO_ContenuDashboardOuverture(){
  echo '
  <!-- Contenu principal avec largeur ajustée -->
  <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
    <!-- Contenu dynamique du back office -->
    <div id="dashboard-content">';
}

function BO_ContenuDashboardFermeture(){
  echo '</div>
  </main>
</div>
</div>';
}

function BO_headerElementPrint()
{
    echo '
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
 <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   <style>
      /* Réduire la largeur de la navbar verticale */
      .sidebar {
        width: 200px; 
        height: 100vh;
      }
    </style>
';
}
