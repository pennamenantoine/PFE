<?php
include "db.php";

// Vérifier si un ID d'utilisateur a été passé via la méthode POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $userId = intval($_POST['user_id']);

    echo "L'ID de l'utilisateur est : " . $userId;
    // suppression de l'utilisateur
    $sql = "DELETE FROM users WHERE id = '$userId'";
    $result = $conn->query($sql);

    // Exécuter la suppression et vérifier le succès
    if ($result) {
        header("Location: user_management.php?success=User deleted successfully");
    } else {
        header("Location: user_management.php?error=An error occurred while deleting the user");
    }
} else {
    header("Location: user_management.php?error=Invalid request");
}
?>
