<?php
include 'db.php';
include 'navbar.php';

// Vérifiez si l'utilisateur a un rôle d'administrateur
if ($result === false) { 
    header("Location: dashboard.php"); // Redirige vers le tableau de bord utilisateur si ce n'est pas un admin
    exit();
} else {
    // Récupérer tous les utilisateurs
    $stmt = $conn->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
	.scrollable-table {
		max-height: calc(12 * 1.5em);
		overflow-y: auto;
	}
    </style>
</head>
<body>
    <h1>User Management</h1>

    <h2>Manage Users</h2>
    <div class="scrollable-table">
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td>
                <form action="update_user_role.php" method="POST" style="display: inline;">
                    <select name="role">
                        <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                        <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <input type="submit" value="Update Role">
                </form>
            </td>
            <td>
                <form action="delete_user.php" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    </div>
    <h2>Add New User</h2>
    <form action="signup.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <select name="role">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Add User">
    </form>
</body>
</html>
