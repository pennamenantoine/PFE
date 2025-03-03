<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//check if no session started
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

// Récupérer le role de l'utilisateur pour la session en cours
$role = $_SESSION['role'];

// Vérifier si l'utilisateur a un rôle d'administrateur
if ($role === 'admin') {
    return true;
} else {
    return false;
}
?>

