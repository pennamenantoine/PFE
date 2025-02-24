<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "db.php"; // Ensure database connection

$lockout_time = 300; // Lockout duration in seconds (5 minutes)
$max_attempts = 3; // Maximum allowed failed login attempts

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Sanitize input to prevent XSS
        $username = htmlspecialchars($username);

        // Check if the user is locked out
        $stmt = $conn->prepare("SELECT failed_attempts, lockout_until FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $failedLogin = $stmt->fetch();

        if ($failedLogin && !empty($failedLogin['lockout_until']) && strtotime($failedLogin['lockout_until']) > time()) {
            echo "Account locked. Try again later.";
            exit();
        }

        // Query database for user
        $stmt = $conn->prepare("SELECT id, username, password, role, email FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            // Update failed attempts
            if ($user) {
                $attempts = $failedLogin['failed_attempts'] + 1;

                if ($attempts >= $max_attempts) {
                    $lockout_until = date("Y-m-d H:i:s", time() + $lockout_time);
                    $stmt = $conn->prepare("UPDATE users SET failed_attempts = ?, lockout_until = ? WHERE username = ?");
                    $stmt->execute([$attempts, $lockout_until, $username]);
                    echo "Account locked. Try again later.";
                } else {
                    $stmt = $conn->prepare("UPDATE users SET failed_attempts = ? WHERE username = ?");
                    $stmt->execute([$attempts, $username]);
                    echo "Invalid Username or Password.";
                }
            } else {
                echo "Invalid Username or Password.";
            }
            exit();
        }

        // Successful login: reset failed attempts
        $stmt = $conn->prepare("UPDATE users SET failed_attempts = 0, lockout_until = NULL WHERE username = ?");
        $stmt->execute([$username]);

        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['id'] = intval($user['id']);

        session_regenerate_id(true); // Prevent session fixation

        header("Location: dashboard.php");
        exit();
    }
}
echo "Form was not submitted correctly.";
?>
