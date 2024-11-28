<?php
session_start();
include "db.php";

// NE PAS UTILISER - Vulnérable aux injections SQL
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Requête vulnérable
	$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
	$result = $conn->query($sql);

	$user = $result->fetch(PDO::FETCH_ASSOC);

	if ($user){
        	// Authentification réussie
	        $_SESSION['username'] = $user['username'];
		$_SESSION['role'] = $user['role'];
      	        header("Location: dashboard.php"); // Rediriger vers la page d'accueil ou un tableau de bord
                exit();
    	}
	else {
        	echo "Invalid Username or Password";
	}
}
	else {
    		echo "Form was not submitted correctly.";
	}
}
?>
