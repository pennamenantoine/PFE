<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "db.php"; // Ensure database connection

$lockout_time = 300; // Lockout duration in seconds (5 minutes)
$max_attempts = 3; // Maximum allowed failed login attempts


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Fetch and sanitize input
        $username = isset($_POST['username']) ? htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8') : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : ''; // Passwords should not be sanitized destructively

        if (empty($username) || empty($password)) {
            echo htmlspecialchars("Invalid input. Please fill in both fields.");
            exit();
        }
    
        try {    
            // Check if the user exists and get lockout status
            $stmt = $conn->prepare("SELECT id, username, password, role, email, failed_attempts, lockout_until FROM users WHERE username = :username");
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_stmt("Execution Error (User Fetch): " . $e->getMessage());
        }    
        //user doesn't exist
        if (!$user) {
            //log failed login in security log
            error_log("FAILED LOGIN: Username: $username | IP: " . $_SERVER['REMOTE_ADDR'] . " | Time: " . date("Y-m-d H:i:s") . "\n", 3, $SECURITY_LOG);
            $connection = 0;
            // Mitigate user enumeration
            sleep(1); 
            echo htmlspecialchars("Invalid username or password.");
            exit();
        }

        // Check if the account is locked
        if (!empty($user['lockout_until']) && strtotime($user['lockout_until']) > time()) {
            echo htmlspecialchars("Your account is temporarily locked. Try again later.");
            exit();
        }
        
        // Verify the password
        if (!password_verify($password, $user['password'])) {
            //log failed login in security log
            error_log("FAILED LOGIN: Username: $username | IP: " . $_SERVER['REMOTE_ADDR'] . " | Time: " . date("Y-m-d H:i:s") . "\n", 3, $SECURITY_LOG);
            $connection = 0;
            try {
                // Increment failed attempts
                $attempts = $user['failed_attempts'] + 1;
                if ($attempts >= $max_attempts) {
                    $lockout_until = date("Y-m-d H:i:s", time() + $lockout_time);
                    $stmt = $conn->prepare("UPDATE users SET failed_attempts = :attempts, lockout_until = :lockout WHERE username = :username");
                    $stmt->bindParam(":lockout", $lockout_until, PDO::PARAM_STR);
                    //log locked user in security log
                    error_log("LOCKED User: $username | IP: " . $_SERVER['REMOTE_ADDR'] . " | Time: " . date("Y-m-d H:i:s") . "\n", 3, $SECURITY_LOG);
                    $connection = 0;
                } else {
                    $stmt = $conn->prepare("UPDATE users SET failed_attempts = :attempts WHERE username = :username");
                }
                $stmt->bindParam(":attempts", $attempts, PDO::PARAM_INT);
                $stmt->bindParam(":username", $username, PDO::PARAM_STR);
                $stmt->execute();
            } catch (PDOException $e) {
                error_stmt("Update Error (Failed Attempts): " . $e->getMessage());
            }        
                echo htmlspecialchars("Invalid username or password.");
                exit();
            }

            // Successful login: Reset failed attempts and lockout
            try{
                $stmt = $conn->prepare("UPDATE users SET failed_attempts = 0, lockout_until = NULL WHERE username = :username");
                $stmt->bindParam(":username", $username, PDO::PARAM_STR);
                $stmt->execute();
            } catch (PDOException $e) {
                error_stmt("Update Error (Reset Attempts): " . $e->getMessage());
            }

            // Session Management
            session_regenerate_id(true); // Prevent session fixation
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['id'] = intval($user['id']);
    
            // Redirect to dashboard on success
            header("Location: dashboard.php");
            exit();
    }
}    
echo htmlspecialchars("Form was not submitted correctly.");
exit();
?>