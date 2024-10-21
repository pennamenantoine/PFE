<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et échappement des données utilisateur
    $nom = htmlspecialchars(trim($_POST['nom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Affichage sécurisé des entrées utilisateur
    echo "<h2>Merci pour votre message, " . $nom . "</h2>";
    echo "<p>Email : " . $email . "</p>";
    echo "<p>Message : " . nl2br($message) . "</p>"; // nl2br pour afficher les nouvelles lignes
}
?>

