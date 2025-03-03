<?php
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit();
}

include "db.php";
include "navbar.php";

function validateCSRFToken($csrfToken) {
    return hash_equals($_SESSION['csrf_token'] ?? '', $csrfToken);
}

// Check if user is an admin
if ($result === false) {
    header("Location: dashboard.php"); // Redirection to dashboard if user is not an admin
    exit();
} else {
	try {
    		// get all users
    		$stmt = $conn->prepare("SELECT * FROM users");
		$stmt->execute();
    		$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
	} catch (PDOException $e) {
            error_stmt("Execution Error (User Fetch): " . $e->getMessage());
	}
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id']) && isset($_POST['role'])) {
    if (!isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF validation failed.');
    }

    $userId = intval($_POST['user_id']);
    $newRole = $_POST['role'];

    // role validation to prevent invalid entries
    if ($newRole !== 'user' && $newRole !== 'admin') {
        die("Invalid role specified.");
    }

    try {
    	// Prepare role update
    	$stmt = $conn->prepare("UPDATE users SET role = :role WHERE id = :user_id");
    	$stmt->bindParam(':role', $newRole);
    	$stmt->bindParam(':user_id', $userId);
	$stmt->execute();

	//user role updated
        header("Location: user_management.php?success=Role updated successfully");
        exit();
    } catch (PDOException $e) {
        header("Location: user_management.php?error=Database error: " . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: user_management.php?error=Invalid request");
    exit();
}
?>

