<?php
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    // Validate and sanitize user_id
    $userId = htmlspecialchars($_POST['user_id'], ENT_QUOTES, 'UTF-8');

    if (!is_numeric($userId)) {
        header("Location: user_management.php?error=Invalid user ID");
        exit();
    }    

    echo "User ID is: " . htmlspecialchars($userId, ENT_QUOTES, 'UTF-8');
    try {
        // Prepare and execute the delete statement
        $stmt = $conn->prepare("DELETE FROM users WHERE id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        $stmt->execute();
        // Check if a row was deleted
        if ($stmt->rowCount() > 0) {
            header("Location: user_management.php?success=User deleted successfully");
        } else {
            header("Location: user_management.php?error=User not found or already deleted");
        }
        exit();
    } catch (PDOException $e) {
        // Log the error using the provided error handler
        error_stmt("Delete Error: " . $e->getMessage());
        header("Location: user_management.php?error=An error occurred while deleting the user");
        exit();
    }
} else {
    header("Location: user_management.php?error=Invalid request");
    exit();
}
?>
