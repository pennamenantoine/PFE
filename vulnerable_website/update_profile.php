<?php
session_start();
include "db.php";

if (!isset($_SESSION['id']) || !isset($_SESSION['username'])) {
    	die("User is not logged in");
}
$id = $_SESSION['id'];
$username = $_SESSION['username'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$email = $_POST['email'];
	if (empty($_POST['old_password']) && empty($_POST['new_password']) && empty($_POST['confirm_new_password'])) {
		$sql = "UPDATE users set email = '$email' WHERE id = '$id'";
		$result = $conn->query($sql);
		if ($result) {
			header("Location: profile.php?param=1");
			exit();
			}
		else
			echo "error in profile update";
	}
	else {
		$old_password = hash('md5', $_POST['old_password']);
		$new_password = hash('md5', $_POST['new_password']);
		$confirm_new_password = hash('md5', $_POST['confirm_new_password']);
		if ($new_password == $confirm_new_password) {
			$sql = "SELECT password FROM users WHERE username = '$username'";
			$result = $conn->query($sql);
	 	        $password = $result->fetchColumn();
			if ($password == $old_password) {
				$sql = "UPDATE users set password = '$new_password', email = '$email' WHERE id = '$id'";
				$result = $conn->query($sql);
				if ($result) {
					header("Location: profile.php?param=1");
					exit();
				}
				else
					echo "error in profile update";
			}
			else
				echo "old_password is incorrect";
		}
		else
			echo "new_password is different than confirm_new_password";
	}
}
?>
