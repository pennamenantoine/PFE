<?php
session_start();

// Récupérer le role de l'utilisateur pour la session en cours
$role = $_SESSION['role'];

// Vérifier si l'utilisateur a un rôle d'administrateur
if ($role === 'admin') {
    return true;
} else {
    return false;
}
?>

