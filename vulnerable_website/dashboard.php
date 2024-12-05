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
    <header><link rel="stylesheet" href="dashboard.css">
        <h1>Dashboard</h1>
        <nav>
            <ul>
                <li><a href="logout.php">Logout</a></li>
		<li><a href="profile.php">Profile</a></li>
		<?php if ($result === true): ?>
                <li><a href="user_management.php">User Management</a></li>
	        <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    </main>
</body>
</html>

