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

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Préparer la requête pour éviter les injections SQL
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Vérifier si l'utilisateur existe
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Vérifier le mot de passe
	if ($password === $user['password']) {                // Authentification réussie
                $_SESSION['username'] = $user['username'];
                header("Location: dashboard.php"); // Rediriger vers la page d'accueil ou un tableau de bord
                exit();
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "No user found.";
        }

        $stmt->close();
    } else {
        echo "Please fill in both fields.";
    }
} else {
    echo "Form was not submitted correctly.";
}

$conn->close();
?>

