<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'navbar.php';
if (isset($_GET['message'])) {
    $message = urldecode($_GET['message']);
    echo "<div class='alert alert-info text-center' role='alert'>";
    echo nl2br(htmlspecialchars($message));  // nl2br converts newlines to <br>
    echo "</div>";
}
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