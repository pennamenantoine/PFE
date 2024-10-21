<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];  // Pas de filtrage ou de validation
    $email = $_POST['email'];  // Pas de filtrage ou de validation
    $message = $_POST['message'];  // Pas de filtrage ou de validation

    // Affichage direct des entrées utilisateur sans protection
    echo "<h2>Merci pour votre message, $nom</h2>";
    echo "<p>Email : $email</p>";
    echo "<p>Message : $message</p>";
}
?>

