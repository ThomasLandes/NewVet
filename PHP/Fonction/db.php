<?php
/**
 * Récupère les détails d'un produit par son identifiant.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @param int $produitId L'identifiant du produit.
 * @return array|false Les détails du produit ou false si non trouvé.
 */
function recupDetailProduit($dbh, $produitId)
{
        $sql = "SELECT p.produit_nom, p.produit_desc, p.produit_prix, c.categorie_nom, p.produit_stock, p.categorie_id, produit_highlander
            FROM produit p
            JOIN categorie c ON p.categorie_id = c.categorie_id
            WHERE p.produit_id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id', $produitId, PDO::PARAM_INT);
        $stmt->execute();
        $produit = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$produit) {
                die("Produit introuvable.");
        }

        return $produit;
}
/**
 * Récupère tous les produits de la base de données.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @return array Tous les produits.
 */
function recupAllProduits($dbh)
{
        $sql = "SELECT *
        FROM produit p";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $produits;
}

/**
 * Récupère les détails d'un produit, ses matériaux et ses images associées.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @param int $produitId L'identifiant du produit.
 * @return array Les détails du produit, des matériaux et des images.
 */
function ProductDetailsMultiTable($dbh, $produitId)
{
        // Récupérer les détails du produit
        $stmt = $dbh->prepare("SELECT * FROM produit WHERE produit_id = :produit_id");
        $stmt->bindParam(':produit_id', $produitId);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // Récupérer les matériaux associés
        $stmt = $dbh->prepare("SELECT materiau_id, composition_pourcentage FROM composition WHERE produit_id = :produit_id");
        $stmt->bindParam(':produit_id', $produitId);
        $stmt->execute();
        $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Récupérer les images associées
        $stmt = $dbh->prepare("SELECT image.image_id, image_nom, image_lien FROM illustration_produit JOIN image ON illustration_produit.image_id = image.image_id WHERE produit_id = :produit_id");
        $stmt->bindParam(':produit_id', $produitId);
        $stmt->execute();
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
                'product' => $product,
                'materials' => $materials,
                'images' => $images
        ];
}

/**
 * Récupère les détails des matériaux d'un produit.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @param int $produitId L'identifiant du produit.
 * @return array Les matériaux du produit.
 */
function recupDetailMateriau($dbh, $produitId)
{
        $sql = "SELECT m.materiau_nom, c.composition_pourcentage
        FROM composition c
        JOIN materiau m ON c.materiau_id = m.materiau_id
        WHERE c.produit_id = :id
        ORDER BY c.composition_pourcentage DESC";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id', $produitId, PDO::PARAM_INT);
        $stmt->execute();
        $materiaux = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $materiaux;
}

/**
 * Récupère tous les matériaux de la base de données.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @return array Les matériaux.
 */
function recupMateriaux($dbh)
{
        $sql = "SELECT * from materiau";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $materiaux = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $materiaux;
}

/**
 * Génère les options de sélection des matériaux.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @return string Les options HTML pour un select de matériaux.
 */
function recupMateriauxOptions($dbh)
{
        $stmt = $dbh->prepare("SELECT materiau_nom FROM materiau ORDER BY materiau_nom");
        $stmt->execute();
        $materiaux = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $options = '<option value="">Choisir un matériau</option>';
        foreach ($materiaux as $materiau) {
                $options .= '<option value="' . htmlspecialchars($materiau) . '">' . htmlspecialchars($materiau) . '</option>';
        }

        return $options;
}

/**
 * Récupère les images associées à un produit.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @param int $produitId L'identifiant du produit.
 * @return array Les liens des images.
 */
function recupDetailImage($dbh, $produitId)
{
        $sql = "SELECT i.image_lien
        FROM illustration_produit ip
        JOIN image i ON ip.image_id = i.image_id
        WHERE ip.produit_id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id', $produitId, PDO::PARAM_INT);
        $stmt->execute();
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $images;
}


/**
 * Récupère les produits par catégorie.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @param int $categorieId L'identifiant de la catégorie.
 * @return array Les produits de la catégorie.
 */
function recupProduitsParCategorie($dbh, $categorieId)
{
        $sql = "SELECT p.produit_id, p.produit_nom, p.produit_prix, p.produit_stock, 
                       MIN(i.image_lien) AS image_lien
                FROM produit p
                JOIN illustration_produit ip ON p.produit_id = ip.produit_id
                JOIN image i ON ip.image_id = i.image_id
                WHERE p.categorie_id = :categorie_id
                GROUP BY p.produit_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':categorie_id', $categorieId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère les détails d'une catégorie.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @param int $categorieId L'identifiant de la catégorie.
 * @return array|false Les détails de la catégorie ou false si non trouvée.
 */
function recupDetailCategorie($dbh, $categorieId)
{
        $sql = "SELECT categorie_nom, categorie_desc, categorie_image
                FROM categorie
                WHERE categorie_id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id', $categorieId, PDO::PARAM_INT);
        $stmt->execute();
        $categorie = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$categorie) {
                die("Catégorie introuvable.");
        }

        return $categorie;
}

/**
 * Récupère toutes les catégories de la base de données.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @return array Les catégories.
 */
function recupCategories($dbh)
{
        $sql = "SELECT * FROM categorie";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère un nombre limité de catégories.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @param int $i Le nombre limite de catégories à récupérer.
 * @return array Les catégories limitées.
 */
function recupCategoriesLimit($dbh, $i)
{
        $sql = "SELECT categorie_id, categorie_nom, categorie_image FROM categorie LIMIT $i";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère les catégories mises en avant.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @return array Les catégories en avant.
 */
function recupCategoriesHighlight($dbh)
{
        $sql = "SELECT categorie_id, categorie_nom, categorie_image FROM categorie WHERE categorie_highlight = 1";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère une catégorie par son identifiant.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @param int $id L'identifiant de la catégorie.
 * @return array|false Les détails de la catégorie ou false si non trouvée.
 */
function recupCategoriesParID($dbh, $id)
{
        $sql = "SELECT * FROM categorie WHERE categorie_id = $id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
}



/**
 * Récupère les produits marqués comme Highlanders.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @return array Les produits Highlanders.
 */
function recupHighlanders($dbh)
{
        $sql = "SELECT p.produit_id, p.produit_nom, i.image_lien
FROM produit p, illustration_produit ip, image i 
WHERE p.produit_id = ip.produit_id
AND ip.image_id = i.image_id
AND produit_highlander = 1 
GROUP BY produit_id
ORDER BY ordre_highlander";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/**
 * Compte le nombre total de produits dans la base de données.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @return int Le nombre total de produits.
 */
function countTotalProduits($dbh)
{
        $sql = "SELECT COUNT(*) FROM produit";
        $stmt = $dbh->query($sql);
        return $stmt->fetchColumn();
}

/**
 * Récupère une sélection de produits pour la pagination.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @param int $start Le point de départ pour la pagination.
 * @param int $limit Le nombre de produits à récupérer.
 * @return array Les produits paginés.
 */
function recupProduitsParPage($dbh, $start, $limit)
{
        $sql = "SELECT p.*, i.image_lien
                FROM produit p
                LEFT JOIN illustration_produit ip ON p.produit_id = ip.produit_id
                LEFT JOIN image i ON ip.image_id = i.image_id
                GROUP BY p.produit_id
                ORDER BY produit_highlander DESC, produit_stock DESC
                LIMIT :start, :limit";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $produits;
}

/**
 * Récupère les informations d'un utilisateur par son identifiant.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @param int $id L'identifiant de l'utilisateur.
 * @return array|false Les informations de l'utilisateur ou false si non trouvées.
 */
function recupUserInfoParID($dbh,$id)
{
    $sql = "SELECT * FROM utilisateur 
            WHERE utilisateur_id = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Récupère les adresses d'un utilisateur.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @param int $id L'identifiant de l'utilisateur.
 * @return array Les adresses de l'utilisateur.
 */
function recupUserAdresseParUser($dbh,$id)
{
    $sql = "SELECT * FROM adresse
            WHERE utilisateur_id = :id
            ORDER BY is_principal DESC";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $adresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $adresses;
}

/**
 * Récupère une adresse spécifique d'un utilisateur.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @param int $idAdr L'identifiant de l'adresse.
 * @param int $idUser L'identifiant de l'utilisateur.
 * @return array|false Les détails de l'adresse ou false si non trouvée.
 */
function recupAdresseSpecifique($dbh,$idAdr,$idUser)
{
    $sql = "SELECT * FROM adresse a, utilisateur u
            WHERE a.utilisateur_id = u.utilisateur_id
            AND a.utilisateur_id = :idUser
            AND adresse_id = :idAdr";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':idAdr', $idAdr, PDO::PARAM_INT);
    $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Supprime une adresse par son identifiant.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @param int $idAdr L'identifiant de l'adresse.
 * @return void
 */
function deleteAdresse($dbh,$idAdr){
        $sql = "DELETE FROM adresse WHERE adresse_id = :id_adresse";
        $delete_stmt = $dbh->prepare($sql);
        $delete_stmt->bindParam(':id_adresse', $idAdr, PDO::PARAM_INT);
        $delete_stmt->execute();}


/**
 * Récupère les cartes bancaires d'un utilisateur.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @param int $id L'identifiant de l'utilisateur.
 * @return array Les cartes bancaires de l'utilisateur.
 */
function recupCbParUser($dbh,$id)
{
    $sql = "SELECT * FROM paiement
            WHERE utilisateur_id = :id
            ORDER BY is_principal DESC";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Supprime une carte bancaire par son identifiant.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @param int $idCb L'identifiant de la carte bancaire.
 * @return void
 */
function deleteCb($dbh,$idCb){
    $sql = "DELETE FROM paiement WHERE paiement_id = :id_paiement";
    $delete_stmt = $dbh->prepare($sql);
    $delete_stmt->bindParam(':id_paiement', $idCb, PDO::PARAM_INT);
    $delete_stmt->execute();}

/**
 * Récupère le contenu d'une commande par son identifiant.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @param int $id L'identifiant de la commande.
 * @return array Le contenu de la commande.
 */
function getContenuCommandeId($dbh,$id)
{
    $sql = "SELECT produit_nom, contenu_quantite, contenu_prix_unite 
            FROM contenu_commande c, produit p 
            WHERE c.produit_id = p.produit_id
            AND commande_id= :id
            GROUP BY p.produit_id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère une adresse par son identifiant.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @param int $adresseId L'identifiant de l'adresse.
 * @return array|false Les détails de l'adresse ou false si non trouvée.
 */
function getAdresseById($dbh, $adresseId) {
    $sql = "SELECT *
            FROM adresse 
            WHERE adresse_id = :adresse_id";

    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':adresse_id', $adresseId);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getStockProduit($dbh, $produitId)
{
    $sqlStock = "SELECT produit_stock FROM produit WHERE produit_id = :produit_id";
    $stmtStock = $dbh->prepare($sqlStock);
    $stmtStock->bindValue(':produit_id', $produitId);
    $stmtStock->execute();
    return $produit = $stmtStock->fetch(PDO::FETCH_ASSOC);
}

