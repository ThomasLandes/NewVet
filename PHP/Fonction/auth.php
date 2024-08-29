<?php 

function verifierSessionActive() {
    // Démarrer la session si elle n'est pas déjà démarrée
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Vérifier si l'utilisateur est connecté
    return isset($_SESSION['utilisateur_id']);
}

function obtenirInfosUtilisateur($dbh) {
    // Assurez-vous que la session est démarrée
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Vérifier si l'utilisateur est connecté
    if (isset($_SESSION['utilisateur_id'])) {
        $stmt = $dbh->prepare("SELECT utilisateur_id, role_id FROM utilisateur WHERE utilisateur_id = :id");
        $stmt->bindParam(':id', $_SESSION['utilisateur_id'], PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    return null; // L'utilisateur n'est pas connecté
}

function autoriserAccesUser($dbh) {
    // Vérifier que la session est active
    verifierSessionActive();

    // Récupérer les infos utilisateur
    $user = obtenirInfosUtilisateur($dbh);

    // Vérifier si le rôle est bien "user" (assume que "user" a un `role_id` de 2)
    if ($user && $user['role_id'] != 2) {
        // Rediriger vers une page d'erreur ou la page d'accueil si l'utilisateur n'est pas autorisé
        header("Location: unauthorized.php");
        exit();
    }
}

function autoriserOnlyAdmin() {
    // Vérifier que la session est active
   $testConnecte = verifierSessionActive();
   if ($testConnecte){
    // Récupérer les infos utilisateur
    $userInfo = getUserInfo();

    // Vérifier si le rôle est bien "admin" (assume que "admin" a un `role_id` de 1)
    if ($userInfo['role_id'] != 1) {
        // Rediriger vers une page d'erreur ou la page d'accueil si l'utilisateur n'est pas autorisé
        header("Location: unauthorized.php");
        exit();
    }
    else {
        header("Location: ../VIEW/connexion.php");
    }
}
}


function getUserInfo() {
    // Démarrer la session si elle n'est pas déjà démarrée
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Vérifier si l'utilisateur est connecté
    if (isset($_SESSION['utilisateur_id']) && isset($_SESSION['role_id'])) {
        return [
            'user_id' => $_SESSION['utilisateur_id'],
            'role_id' => $_SESSION['role_id']
        ];
    } else {
        // Si l'utilisateur n'est pas connecté, retourner null ou false
        return null;
    }
}

