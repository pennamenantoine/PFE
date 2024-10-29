<?php
session_start();

// Configuration de la base de données
$servername = "localhost";
$username = "root"; // Remplacez par votre nom d'utilisateur MySQL
$password = "toor"; // Remplacez par votre mot de passe MySQL
$dbname = "user_auth"; // Nom de votre base de données

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// NE PAS UTILISER - Vulnérable aux injections SQL
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Requête vulnérable
	$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
	echo $sql;
        $result = $conn->query($sql);

        // Vérifier si l'utilisateur existe
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Authentification réussie
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php"); // Rediriger vers la page d'accueil ou un tableau de bord
            exit();
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Please fill in both fields.";
    }
} else {
    echo "Form was not submitted correctly.";
}

$conn->close();
?>
