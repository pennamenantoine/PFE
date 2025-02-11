<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {

    // Récuperation des données du formulaire
    $username = $_POST['username'];
    $password = hash('md5', $_POST['password']);
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

/*	$id = $conn->lastInsertId();

	$uploadDir = "uploads/";
	$picture = $uploadDir . "blank-profile-picture.png";
	
	$sqlPicture = "INSERT INTO images (user_id, file_path) VALUES ('$id', '$picture')";
    $result = $conn->query($sqlPicture);

	if (!$result) {
        echo "Picture Error";
    } */
	}
}

?>

