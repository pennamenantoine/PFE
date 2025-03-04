<?php
// Ensure user is authenticated
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit();
}

include "db.php";

$id = $_SESSION['id'];
$username = $_SESSION['username'];
$comment = " ";
$alert_msg = "!!! ";

function isValidPassword($password) {
    // Minimum 8 characters, at least 1 digit and 1 special character
    return preg_match('/^(?=.*\d)(?=.*[\W_]).{8,}$/', $password);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
		die('CSRF validation failed.');
	}
	
	try {
		$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		$users = $stmt->fetch(PDO::FETCH_ASSOC);
	} catch (PDOException $e) {
		// Catch the PDOException error
		$error_message = "Error: " . $e->getMessage(); // Get the error message from the exception
		error_stmt ($error_message);
	}

	// Update profile picture
    if (!empty($_POST['picture'])) {

		try {
			$stmt = $conn->prepare("SELECT * FROM images WHERE user_id = :id");
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			$u = $stmt->fetch(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			// Catch the PDOException error
			$error_message = "Error: " . $e->getMessage(); // Get the error message from the exception
			error_stmt ($error_message);
		}

		$oldImage = $u['file_path'];
		$newImagePath = $_POST['picture'];
        $newImagePath = htmlspecialchars($newImagePath, ENT_QUOTES, 'UTF-8');
		
		//new picture upload
		if ($oldImage != $newImagePath) {
			// Update profile picture if provided
			if (!empty($newImagePath)) {
				$picture = filter_var($newImagePath, FILTER_SANITIZE_URL);
				// if user has an image in images table
				if ($u) {
					try {
						$stmt = $conn->prepare("UPDATE images SET file_path = :file_path WHERE user_id = :id");
						$stmt->bindParam(':file_path', $picture, PDO::PARAM_STR);
						$stmt->bindParam(':id', $id, PDO::PARAM_INT);
						$stmt->execute();
						// $result_img = $stmt->fetch(PDO::FETCH_ASSOC);
					} catch (PDOException $e) {
						error_stmt("Execution Error (update picture): " . $e->getMessage());
					}
					
					if ($stmt->rowCount() > 0) {
						$comments .= "Image path updated successfully.";
					} else {
						$alert_msg .= "Error in photo $picture update.";
					}	
				} 
				else {
					// insert a new line in images table 
					try {
						$stmt = $conn->prepare("INSERT INTO images (user_id, file_path) VALUES  (:id, :file_path)");
						$stmt->bindParam(':file_path', $picture, PDO::PARAM_STR);
						$stmt->bindParam(':id', $id, PDO::PARAM_INT);
						$stmt->execute();
						$result_img = $stmt->fetch(PDO::FETCH_ASSOC);
					} catch (PDOException $e) {
						error_stmt("Execution Error (insert picture): " . $e->getMessage());
					}

					if (!$result_img) {
						$alert_msg .= "Picture Error";
					}
				}
			}
		}
		
	}

	// update email if changed
	$email = $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
	if ($email != $users['email']) {
		try {
			$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
			$stmt = $conn->prepare("UPDATE users SET email = :email WHERE id = :id");
			$stmt->bindParam(':email', $email, PDO::PARAM_STR);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
		}catch (PDOException $e) {
				error_stmt("Execution Error (email update): " . $e->getMessage());
		}
		if ($stmt->rowCount() > 0) {
			$comments .= "email updated successfully.";
		} else {
			$alert_msg .= "Error in email update.";
		}	
	}	

	// update password
	if (!empty($_POST['old_password']) && !empty($_POST['new_password']) && !empty($_POST['confirm_new_password'])) {
		// Validate new password strength
        if (!isValidPassword($_POST['new_password']) || !isValidPassword($_POST['confirm_new_password'])) {
            $message = "Password must be at least 8 characters long, contain one digit, and one special character.";
            $alert_msg .= $message;
        } 
		else {
			$old_password = password_hash($_POST['old_password'], PASSWORD_ARGON2ID);
			// check old password is correct
			try {    
				// Check if the user exists and get lockout status
				$stmt = $conn->prepare("SELECT id, username, password, role, email, failed_attempts, lockout_until FROM users WHERE username = :username");
				$stmt->bindParam(":username", $username, PDO::PARAM_STR);
				$stmt->execute();
				$user = $stmt->fetch(PDO::FETCH_ASSOC);
			} catch (PDOException $e) {
				error_stmt("Execution Error (User Fetch): " . $e->getMessage());
			}    

			if (!password_verify($old_password, $user['password'])) {
				$alert_msg .= "Incorrect old password.";
			}
			// check confirm password
			if ($_POST['new_password'] === $_POST['confirm_new_password']) {

				$new_password = password_hash($_POST['new_password'], PASSWORD_ARGON2ID);
				$confirm_new_password = password_hash($_POST['confirm_new_password'], PASSWORD_ARGON2ID);
			
				try{
					$stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
					$stmt->bindParam(':password', $newPassword, PDO::PARAM_STR);
					$stmt->bindParam(':id', $id, PDO::PARAM_INT);
					$stmt->execute();
				} catch (PDOException $e) {
					error_stmt("Execution Error (password update): " . $e->getMessage());
				}	
				$comments .= "Password updated successfully";
            } 
			else {
                $alert_msg .= "Passwords do not match.";
            }
		}
	}
	if (isset($_POST['comments'])) {
		//$_SESSION['comments'] = trim($_POST['comments']);
		$_SESSION['comments'] = $alert_msg;
		$_SESSION['comments'] .= $comments;
		header('Location: profile.php');
		exit();
	}
}
?>
