<?php

function recupDetailProduit($dbh, $produitId)
{
        $sql = "SELECT p.produit_nom, p.produit_desc, p.produit_prix, c.categorie_nom, p.produit_stock, p.categorie_id
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

function recupDetailMateriau($dbh, $produitId)
{
        $sql = "SELECT m.materiau_nom, c.composition_pourcentage
        FROM composition c
        JOIN materiau m ON c.materiau_id = m.materiau_id
        WHERE c.produit_id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id', $produitId, PDO::PARAM_INT);
        $stmt->execute();
        $materiaux = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $materiaux;
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

function recupCategoriesParID($dbh,$id)
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

