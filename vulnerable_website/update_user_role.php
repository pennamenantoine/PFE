<?php
include 'auth_check.php';

// Vérifiez si l'utilisateur a un rôle d'administrateur
if ($currentUser['role'] !== 'admin') {
    header("Location: dashboard.php"); // Redirige vers le tableau de bord si ce n'est pas un admin
    exit();
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id']) && isset($_POST['role'])) {
    $userId = intval($_POST['user_id']);
    $newRole = $_POST['role'];

    // Valider le rôle (pour éviter les entrées non valides)
    if ($newRole !== 'user' && $newRole !== 'admin') {
        die("Invalid role specified.");
    }

    // Préparer la mise à jour du rôle
    $stmt = $pdo->prepare("UPDATE users SET role = :role WHERE id = :user_id");
    $stmt->bindParam(':role', $newRole);
    $stmt->bindParam(':user_id', $userId);

    // Exécuter la mise à jour et vérifier le succès
    if ($stmt->execute()) {
        header("Location: user_management.php?success=Role updated successfully");
    } else {
        header("Location: user_management.php?error=An error occurred while updating the role");
    }
} else {
    header("Location: user_management.php?error=Invalid request");
}
?>

