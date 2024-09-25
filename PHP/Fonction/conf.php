<?php
/**
* Établit une connexion à la base de données MySQL et renvoie un objet PDO.
*
* Cette fonction tente de se connecter à une base de données MySQL avec les paramètres spécifiés
* (hôte, nom de la base de données, utilisateur, mot de passe) et retourne un objet PDO pour effectuer des requêtes SQL.
*
* En cas d'échec de la connexion, une exception PDO est attrapée et un message d'erreur est affiché, entraînant l'arrêt du script.
*
* @return PDO Renvoie un objet PDO en cas de succès de la connexion.
*
* @throws PDOException Si une erreur survient lors de la tentative de connexion, un message d'erreur est affiché.
*/
function connexion_bdd()
{
    $dsn = 'mysql:host=localhost;dbname=new_vet';
    $user = 'root';
    $password = '';
    try {
        $dbh = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbh;
    } catch (PDOException $ex) {
        die("Erreur lors de la connexion SQL : " . $ex->getMessage());
    }
}
?>