<?php
include 'auth_check.php';

// Vérifiez si l'utilisateur a un rôle d'administrateur
if ($currentUser['role'] !== 'admin') {
    header("Location: dashboard.php"); // Redirige vers le tableau de bord si ce n'est pas un admin
    exit();
}

// Vérifier si un ID d'utilisateur a été passé via la méthode POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $userId = intval($_POST['user_id']);

    echo "L'ID de l'utilisateur est : " . $userId;    
    // Préparer la suppression de l'utilisateur
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $userId);

    // Exécuter la suppression et vérifier le succès
    if ($stmt->execute()) {
        header("Location: user_management.php?success=User deleted successfully");
    } else {
        header("Location: user_management.php?error=An error occurred while deleting the user");
    }
} else {
    header("Location: user_management.php?error=Invalid request");
}
?>
