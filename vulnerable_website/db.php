<?php

// Connexion à la base de données
//$pdo = new PDO('mysql:host=localhost;dbname=user_auth', 'root', 'toor');
$servername = "localhost";
$username = "root";
$password = "toor";
$dbname = "user_auth";

//$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
try {
   $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifie si l'utilisateur est connecté
//if (!isset($_SESSION['username'])) {
//    header("Location: login.html"); // Redirige vers la page de connexion si non connecté
//    exit(); // Quitte le script
//}
?>
