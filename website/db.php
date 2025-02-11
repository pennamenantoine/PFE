<?php

// Connexion à la base de données
$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');
$dbname = getenv('DB_NAME');

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
