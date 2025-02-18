<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "db.php"; // Ensure database connection

$connection = 0; // Set default connection status

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Sanitize input to prevent XSS
        $username = htmlspecialchars($username);

        // Query database for user
        $stmt = $conn->prepare("SELECT id, username, password, role, email FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            // Log failed login attempt
            error_log("FAILED LOGIN: Username: $username | IP: " . $_SERVER['REMOTE_ADDR'] . " | Time: " . date("Y-m-d H:i:s") . "\n", 3, __DIR__ . "/../logs/security.log");
            $connection = 0;
            echo "Invalid Username or Password.";
        } else {
            // Successful authentication
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['id'] = intval($user['id']);
            
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true); //Regenerating the session ID on login to prevent session fixation attacks

            $connection = 1;
            echo "Login successful!";
        }
    }
} else {
    $connection = 0;
    echo "Form was not submitted correctly.";
}

if ($connection == 1) {
    header("Location: dashboard.php");
    exit();
}
?>
