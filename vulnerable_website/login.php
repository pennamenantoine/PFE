<?php
session_start();
include "db.php";

// NE PAS UTILISER - Vulnérable aux injections SQL
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = hash('md5', $_POST['password']);

        // Requête vulnérable
	$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
	$result = $conn->query($sql);

	$user = $result->fetch(PDO::FETCH_ASSOC);

	if ($user){
        	// Authentification réussie
	    $_SESSION['username'] = $user['username'];
		$_SESSION['role'] = $user['role'];
		$_SESSION['email'] = $user['email'];
		$_SESSION['id'] = intval($user['id']);;
		$connection = 1;
    	}
	else {
		$connection = 0;
        	echo "Invalid Username or Password";
	}
}
	else {
		$connection = 0;
    		echo "Form was not submitted correctly.";
	}
	if ($connection == 1) {
		header("Location: dashboard.php");
        	exit();
	}
}
?>
