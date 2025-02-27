<?php
include "db.php";

function isValidPassword($password) {
    // Minimum 8 characters, at least 1 digit and 1 special character
    return preg_match('/^(?=.*\d)(?=.*[\W_]).{8,}$/', $password);
}

//redirect either to Signup or user_management depending on the calling component
function redirect($message) {
    // Sanitize the message to prevent XSS attacks (even though it's static here, for safety)
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    
    if (empty($_POST['role'])) {
        if ($message == "User created successfully."){
            echo "$message <a href='login.html'>Login</a>";
        }else {
            echo "$message <a href='signup.html'>Sign Up</a>";
        }    
    } else {
        //echo "$message <a href='user_management.php'>User Management</a>";
        header("Location: user_management.php?success=$message");

    }
    // End the script execution after showing the message
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {

        // Get form data
        $username = htmlspecialchars(trim($_POST['username']));
        $password = $_POST['password'];
        $email = htmlspecialchars(trim($_POST['email']));

        $role = empty($_POST['role']) ? 'user' : htmlspecialchars(trim($_POST['role']));

        // Validate password strength
        if (!isValidPassword($password)) {
            $message = "Password must be at least 8 characters long, contain one digit, and one special character.";
            redirect($message);
        }

        // Check if username already exists
        try {
            $stmt = $conn->prepare("SELECT id, username, password, role, email FROM users WHERE username = ?");
            if ($stmt->execute([$username]))
                $user = $stmt->fetch();
        } catch (PDOException $e) {
            // Catch the PDOException error
            $error_message = "Error: " . $e->getMessage(); // Get the error message from the exception
            error_stmt ($error_message);
        }    

        // Check if a row is found
        if ($user) {
            $message = "Username already taken! Please choose another one.";
            redirect($message);
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_ARGON2ID);

        // Insert new user
        try {
            $insert_query = "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);

            if ($stmt->execute([$username, $hashed_password, $email, $role])) {
                $message = "User created successfully.";
                redirect($message);
            }    
        }catch (PDOException $e) {
            // Catch the PDOException error
            $error_message = "Error: " . $e->getMessage(); // Get the error message from the exception
            error_stmt ($error_message);
        }
}
}
?>
