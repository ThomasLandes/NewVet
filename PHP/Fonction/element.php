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

    // Démarrer la session si elle n'est pas déjà démarrée
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Vérifier si l'utilisateur est connecté
    $userInfo = getUserInfo();
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
                </div>';

    // Afficher le lien vers le compte ou le bouton de connexion selon l'état de la session
    if ($userInfo !== null) {
        echo '
                <a href="compte.php?id='.$userInfo['user_id'].'" class="btn btn-outline-secondary">
                    <i class="bi bi-person"></i> Mon Compte
                </a>';
    } else {
        echo '
                <a href="connexion.php" class="btn btn-outline-secondary">
                    <i class="bi bi-person"></i> Se connecter
                </a>';
    }

    echo '
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
              <li class="nav-item">
                <a class="nav-link" href="../VIEW">
                  <i class="bi bi-arrow-bar-left"></i> Retour au site
                </a>
              </li>
            </ul>
          </div>
        </nav>';
}


function BO_ContenuDashboardOuverture(){
  echo '
  <!-- Contenu principal avec largeur ajustée -->
  <main class="col-md-10 col-lg-10 px-md-4">
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
    <link rel="stylesheet" href="../CSS/BO_Tableau.css">
';
}

function BO_ScriptAjoutSectionMateriau() {
  echo '<script>
    // Fonction pour ajouter dynamiquement un champ de matériau
    function addMaterialField() {
      const materialContainer = document.getElementById("material-container");
      const newMaterial = document.createElement("div");
      newMaterial.className = "row mb-3 align-items-center material-row";
      newMaterial.innerHTML = `
  <div class="col-md-6">
    <select class="form-select" name="material[]" required>
      <option value="">Choisir un matériau</option>
      <option value="3">Coton</option>
      <option value="6">Elastane</option>
      <option value="5">Lyocell</option>
      <option value="7">Modal</option>
      <option value="2">Polyamide</option>
      <option value="4">Polyester</option>
      <option value="1">Viscose</option>
    </select>
  </div>
  <div class="col-md-4">
    <input type="number" class="form-control" name="percentage[]" placeholder="%" min="0" max="100" required>
  </div>
  <div class="col-md-2">
    <button type="button" class="btn btn-danger btn-sm" onclick="removeMaterialField(this)">&#10005;</button>
  </div>
`;
      materialContainer.appendChild(newMaterial);
    }';}

    function BO_ScriptAjoutSectionImage() {
    echo '// Fonction pour ajouter dynamiquement un champ d\'image
    function addImageField() {
      const imageContainer = document.getElementById("image-container");
      const newImageField = document.createElement("div");
      newImageField.className = "mb-2 d-flex align-items-center image-row";
      newImageField.innerHTML = `
        <input type="file" class="form-control" name="productImage[]" required>
        <button type="button" class="btn btn-danger btn-sm ms-2" onclick="removeImageField(this)">&#10005;</button>
      `;
      imageContainer.appendChild(newImageField);
    }

    // Fonction pour supprimer un champ de matériau
    function removeMaterialField(button) {
      button.closest(".material-row").remove();
    }

    // Fonction pour supprimer un champ d\'image
    function removeImageField(button) {
      button.closest(".image-row").remove();
    }
  </script>';
}
