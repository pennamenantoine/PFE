<?php
include 'auth_check.php';

// Récupérer le nom d'utilisateur de la session
$username = $_SESSION['username'];

// Préparer et exécuter la requête pour récupérer le rôle de l'utilisateur
$stmt = $pdo->prepare("SELECT role FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'utilisateur a un rôle d'administrateur
if ($currentUser && $currentUser['role'] === 'admin') {
    return true; // Utilisateur est admin
} else {
    return false; // Utilisateur n'est pas admin
}
?>

