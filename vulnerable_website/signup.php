<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Fonction pour créer l'utilisateur
    createUser($username, $password, $email);
}

function createUser($username, $password, $email) {
    // Configuration de la base de données
    $servername = "localhost";
    $db_username = "root"; // Remplacez par votre nom d'utilisateur MySQL
    $db_password = "toor"; // Remplacez par votre mot de passe MySQL
    $dbname = "user_auth"; // Nom de votre base de données

    // Créer la connexion
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Préparer la requête pour éviter les injections SQL
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Associer les paramètres et exécuter la requête
    $stmt->bind_param("sss", $username, $password, $email);

    if ($stmt->execute()) {
        echo "User created successfully. <a href='login.html'>Connectez-vous ici</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Fermer la déclaration et la connexion
    $stmt->close();
    $conn->close();
}
?>

