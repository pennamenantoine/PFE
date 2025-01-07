<?php
include "db.php";
include "navbar.php";

// Vérifiez si l'utilisateur a un rôle d'administrateur
if ($result === false) { 
    header("Location: dashboard.php"); // Redirige vers le tableau de bord utilisateur si ce n'est pas un admin
    exit();
} else {
    // Récupérer tous les utilisateurs
    $stmt = $conn->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    $stmt = $conn->prepare("UPDATE users SET role = :role WHERE id = :user_id");
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

