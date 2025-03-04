<?php
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit();
}

// Check if the user is an admin
$result = include 'is_admin.php'; // Ensure is_admin.php returns a boolean
?>
<link rel="stylesheet" href="navbar.css"> <!-- Include the stylesheet -->
<header>
    <nav class="navbar">
        <ul class="left-menu">
            <li><a href="dashboard.php">Dashboard</a></li>
        </ul>
        <ul class="right-menu">
            <?php if ($result === true): ?>
                <li><a href="user_management.php">User Management</a></li>
                <li><a href="product_management.php">Product Management</a></li>
            <?php endif; ?>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="reviews.php">Reviews</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>
