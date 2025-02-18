<?php
include "db.php";

function isValidPassword($password) {
    // Minimum 8 characters, at least 1 digit and 1 special character
    return preg_match('/^(?=.*\d)(?=.*[\W_]).{8,}$/', $password);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {

    // get form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    if (!isValidPassword($password)) {
        die("Password must be at least 8 characters long, contain one digit, and one special character.");
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $email = $_POST['email'];
    if (empty($_POST['role']))
	{
		$role = 'user';
	}
    else {
		$role = $_POST['role'];
	}

    $sql = "INSERT INTO users (username, password, email, role) VALUES ('$username', '$hashed_password', '$email', '$role')";
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
