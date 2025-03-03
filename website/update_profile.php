<?php
// Ensure user is authenticated
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit();
}

include "db.php";

$id = $_SESSION['id'];
$username = $_SESSION['username'];

function isValidPassword($password) {
    // Minimum 8 characters, at least 1 digit and 1 special character
    return preg_match('/^(?=.*\d)(?=.*[\W_]).{8,}$/', $password);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
		die('CSRF validation failed.');
	}
	$newImagePath = $_POST['picture'];
	$result = $conn->query("SELECT user_id FROM images WHERE user_id = '$id'");
	$u = $result->fetchColumn();
	if ($result && $u){
		//$nb = 'YES!!!!!!!';
		$sql_img = "UPDATE images set file_path = '$newImagePath' WHERE user_id = $id";
		$result_img = $conn->query($sql_img);

		if ($result_img) {

			if ($conn->info > 0) {
				echo "Image path updated successfully.";
			}
			else {
				echo "Error in photo update.";
			}
		}
	}
	else {
		//$nb = 'NO!!!!!!!';
		$sqlPicture = "INSERT INTO images (user_id, file_path) VALUES ('$id', '$newImagePath')";
		$result = $conn->query($sqlPicture);
		if (!$result) {
			echo "Picture Error";
		}
	}

	$email = $_POST['email'];
	if (empty($_POST['old_password']) && empty($_POST['new_password']) && empty($_POST['confirm_new_password'])) {
		// Validate password strength
        if (!isValidPassword($password)) {
            $message = "Password must be at least 8 characters long, contain one digit, and one special character.";
            redirect($message);
        }
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
		$old_password = password_hash($_POST['old_password'], PASSWORD_ARGON2ID);
		$new_password = password_hash($_POST['new_password'], PASSWORD_ARGON2ID);
		$confirm_new_password = password_hash($_POST['confirm_new_password'], PASSWORD_ARGON2ID);
		if ($new_password == $confirm_new_password) {
			$sql = "SELECT password FROM users WHERE username = '$username'";
			$result = $conn->query($sql);
	 	        $password = $result->fetchColumn();
			if ($password == $old_password) {
				$sql = "UPDATE users set password = '$new_password', email = '$email' WHERE id = '$id'";
				$result = $conn->query($sql);
				if ($result) {
					header("Location: profile.php?param=2");
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
