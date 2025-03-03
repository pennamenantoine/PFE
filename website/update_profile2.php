<?php
include 'db.php';
session_start();

// Ensure user is authenticated
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit();
}

function redirect($url) {
    header("Location: $url");
    exit();
}

// CSRF token check
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token mismatch.");
    }
    
    $id = $_SESSION['id'];

    try {
        // Update email if provided
        if (!empty($_POST['email'])) {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $stmt = $conn->prepare("UPDATE users SET email = :email WHERE id = :id");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }

        // Update password if provided
        if (!empty($_POST['old_password']) && !empty($_POST['new_password']) && !empty($_POST['confirm_new_password'])) {
            $stmt = $conn->prepare("SELECT password FROM users WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $hashedPassword = $stmt->fetchColumn();

            if (password_verify($_POST['old_password'], $hashedPassword)) {
                if ($_POST['new_password'] === $_POST['confirm_new_password']) {
                    $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
                    $stmt->bindParam(':password', $newPassword, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                } else {
                    die("Passwords do not match.");
                }
            } else {
                die("Incorrect old password.");
            }
        }

        // Update profile picture if provided
        if (!empty($_POST['picture'])) {
            $picture = filter_var($_POST['picture'], FILTER_SANITIZE_URL);
            $stmt = $conn->prepare("UPDATE images SET file_path = :file_path WHERE user_id = :id");
            $stmt->bindParam(':file_path', $picture, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }

        redirect("profile.php?param=success");

    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>