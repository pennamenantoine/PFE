<?php
$result = include 'is_admin.php'; // Inclut le fichier de vérification admin

// À ce stade, l'utilisateur est connecté et est soit un admin soit un utilisateur régulier
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <header>
        <h1>Tableau de Bord</h1>
        <nav>
            <ul>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

        <?php if ($result === true): ?>
            <h2>Panneau Administrateur</h2>
            <p>En tant qu'administrateur, vous avez accès aux fonctionnalités suivantes :</p>
            <ul>
                <li><a href="user_management.php">Gestion des Utilisateurs</a></li>
                <li><a href="some_other_admin_functionality.php">Autre Fonctionnalité Administrateur</a></li>
            </ul>
        <?php else: ?>
            <h2>Panneau Utilisateur</h2>
            <p>En tant qu'utilisateur régulier, vous avez accès aux fonctionnalités utilisateur.</p>
        <?php endif; ?>
    </main>
</body>
</html>

