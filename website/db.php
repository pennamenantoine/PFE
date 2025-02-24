<?php

function error_stmt ($error_st){
    // Log error to a file for debugging purposes
    error_log("MySQL Error: " . $error_st, 3, './logs/errors.log');
    // Display a friendly message to the user
    echo "There was an error processing your request. Please try again later.";
}

// Check if the encrypted file exists
$CONFIG_FILE = '../secure/config.php';

// If the config file doesn't exist, show an error
if (!file_exists($CONFIG_FILE)) {
    echo "Error: config file '$CONFIG_FILE' not found!";
    exit(1);
}

// Include the config file and assign values
$config = include $CONFIG_FILE;

$servername = $config['host'];  // Assuming 'host' is in the config array
$username = $config['user'];   // Assuming 'user' is in the config array
$password = $config['pass'];   // Assuming 'pass' is in the config array
$dbname = $config['dbname'];   // Assuming 'dbname' is in the config array

// If start_php.sh set another HOST
if (isset($DB_HOST) && !empty($DB_HOST)) {
    $servername = $DB_HOST;
}

// Connection to DB
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $error_st = "Connection Error: . $e->getMessage()";
    error_stmt ($error_st);
}
?>


