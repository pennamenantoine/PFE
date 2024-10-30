`<?php

//sanitization v1
//function sanitize_data($string)
//{
//	return str_replace(
//    	["<script>", "</script>"],
//    	['&lt;script&gt;', "&lt;/script&gt;"],
//    	$string
//	);
//}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et échappement des données utilisateur
    	$nom = htmlspecialchars_decode(trim($_POST['nom']));
 	$email = htmlspecialchars(trim($_POST['email']));
  	$message = htmlspecialchars(trim($_POST['message']));

//sanitization v1
//	$nom = sanitize_data($_POST['nom']);
//	$email = sanitize_data($_POST['email']);
//	$message = sanitize_data($_POST['message']);

    // Affichage sécurisé des entrées utilisateur
    echo "<h2>Merci pour votre message, " . $nom . "</h2>";
    echo "<p>Email : " . $email . "</p>";
    echo "<p>Message : " . nl2br($message) . "</p>"; // nl2br pour afficher les nouvelles lignes
}
?>

