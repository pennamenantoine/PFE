<?php
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Image</title>
    <style nonce="<?= $nonce; ?>">
        /* Add space below the message */
        .alert {
            margin-bottom: 20px;  /* Adds space between the message and the form */
        }
    </style>
</head>
<body>

<!-- Image upload form -->
<form action="upload_image.php" method="post" enctype="multipart/form-data">
    <label for="fileToUpload">Select image to upload:</label>
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>

</body>
</html>
