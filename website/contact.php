<?php
// <--return to index
echo '<a href="index.php" style="display: inline-block; margin-bottom: 10px; text-decoration: none; color: blue;">&larr; Home</a>';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars($_POST['name']);  
    $email = htmlspecialchars($_POST['email']);  
    $message = nl2br(htmlspecialchars($_POST['message']));  

    echo "<h2>Thank you for your message, $nom</h2>";
    echo "<p>Email : $email</p>";
    echo "<p>Message : $message</p>";
}
?>

