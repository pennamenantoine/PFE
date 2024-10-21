<?php
session_start(); // Démarre la session

// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=user_auth', 'root', 'toor');

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: login.html"); // Redirige vers la page de connexion si non connecté
    exit(); // Quitte le script
}
?>
