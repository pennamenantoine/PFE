<?php
// Unset all session variables
$_SESSION = [];
session_destroy(); // Détruit la session
// Delete the session cookie (if it exists)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

header("Location: index.php"); // Redirige vers la page de login
exit();
?>

