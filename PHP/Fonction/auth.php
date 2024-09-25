<?php
/**
 * Vérifie si une session est active et l'utilisateur est connecté.
 *
 * @return bool Retourne true si l'utilisateur est connecté, sinon false.
 */
function verifierSessionActive()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    return isset($_SESSION['utilisateur_id']);
}

/**
 * Récupère les informations de l'utilisateur connecté.
 *
 * @param PDO $dbh Instance de la connexion PDO à la base de données.
 * @return array|null Tableau associatif des informations utilisateur ou null si non connecté.
 */
function obtenirInfosUtilisateur($dbh)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['utilisateur_id'])) {
        $stmt = $dbh->prepare("SELECT utilisateur_id, role_id FROM utilisateur WHERE utilisateur_id = :id");
        $stmt->bindParam(':id', $_SESSION['utilisateur_id'], PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    return null;
}

/**
 * Autorise l'accès uniquement aux utilisateurs de rôle "user" (role_id = 2).
 * Redirige vers "unauthorized.php" si l'utilisateur n'est pas autorisé.
 *
 * @param PDO $dbh Instance de la connexion PDO à la base de données.
 */
function autoriserAccesUser($dbh)
{
    verifierSessionActive();

    $user = obtenirInfosUtilisateur($dbh);

    if ($user && $user['role_id'] != 2) {
        header("Location: unauthorized.php");
        exit();
    }
}

/**
 * Autorise uniquement l'accès aux administrateurs (role_id = 1).
 * Redirige vers "unauthorized.php" si l'utilisateur n'est pas admin ou non connecté.
 */
function autoriserOnlyAdmin()
{
    $testConnecte = verifierSessionActive();

    if ($testConnecte) {
        $userInfo = getUserInfo();

        if ($userInfo['role_id'] != 1) {
            header("Location: unauthorized.php");
            exit();
        }
    } else {
        header("Location: unauthorized.php");
        exit();
    }
}

/**
 * Récupère les informations de l'utilisateur depuis la session.
 *
 * @return array|null Tableau associatif avec 'user_id' et 'role_id' ou null si non connecté.
 */
function getUserInfo()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['utilisateur_id']) && isset($_SESSION['role_id'])) {
        return [
            'user_id' => $_SESSION['utilisateur_id'],
            'role_id' => $_SESSION['role_id']
        ];
    }

    return null;
}

/**
 * Vérifie si l'utilisateur est administrateur (role_id = 1).
 *
 * @return bool Retourne true si l'utilisateur est administrateur, sinon false.
 */
function isAdmin()
{
    if (verifierSessionActive()) {
        $userInfo = getUserInfo();
        return isset($userInfo['role_id']) && $userInfo['role_id'] == 1;
    }

    return false;
}

?>