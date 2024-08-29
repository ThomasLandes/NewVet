<?php

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

function recupAllProduits($dbh)
{
        $sql = "SELECT *
        FROM produit p";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $produits;
}


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

function recupMateriaux($dbh)
{
        $sql = "SELECT * from materiau";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $materiaux = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $materiaux;
}

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

function recupCategories($dbh)
{
        $sql = "SELECT * FROM categorie";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function recupCategoriesLimit($dbh, $i)
{
        $sql = "SELECT categorie_id, categorie_nom, categorie_image FROM categorie LIMIT $i";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function recupCategoriesHighlight($dbh)
{
        $sql = "SELECT categorie_id, categorie_nom, categorie_image FROM categorie WHERE categorie_highlight = 1";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function recupCategoriesParID($dbh, $id)
{
        $sql = "SELECT * FROM categorie WHERE categorie_id = $id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
}




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

function countTotalProduits($dbh)
{
        $sql = "SELECT COUNT(*) FROM produit";
        $stmt = $dbh->query($sql);
        return $stmt->fetchColumn();
}

function recupProduitsParPage($dbh, $start, $limit)
{
        $sql = "SELECT p.*, i.image_lien
                FROM produit p
                LEFT JOIN illustration_produit ip ON p.produit_id = ip.produit_id
                LEFT JOIN image i ON ip.image_id = i.image_id
                GROUP BY p.produit_id
                LIMIT :start, :limit";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $produits;
}
