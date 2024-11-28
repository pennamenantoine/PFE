<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {

    // Récupérer les données du formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    if (empty($_POST['role']))
	{
		$role = 'user';
	}
    else {
		$role = $_POST['role'];
	}

    $sql = "INSERT INTO users (username, password, email, role) VALUES ('$username', '$password', '$email', '$role')";
    $result = $conn->query($sql);

	if ($result) {
	    	if (empty($_POST['role'])) {
        	echo "User created successfully <a href='login.html'>Connectez-vous ici</a>";
    		}
	else {
	        header("Location: user_management.php?success=User created successfully");
}
	}
	else {
        	echo "Error";
    	}

	}
}

?>

