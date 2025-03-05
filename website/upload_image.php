<?php
// Ensure user is authenticated
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit();
}

$target_dir = "./uploads/";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
}

$comments = " ";

function go_to_profile() {   
    global $comments;
    $_SESSION['comments'] .= $comments;    
    header("Location: profile.php");
    exit;
}

$maxFileSize = 2 * 1024 * 1024; // 2MB
$allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

if (!isset($_FILES['fileToUpload']) || $_FILES['fileToUpload']['error'] !== UPLOAD_ERR_OK) {
    $comments .= "There was an error while uploading the file.";
    go_to_profile();
}

// file check
$fileTmpPath = $_FILES['fileToUpload']['tmp_name'];
$fileSize = $_FILES['fileToUpload']['size'];
$fileExtension = strtolower(pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION));
// finfo() to check the mime type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$fileMimeType = finfo_file($finfo, $fileTmpPath);
finfo_close($finfo);

// check if file is a real image, use @ to delete warnings
if (!@getimagesize($fileTmpPath)) {
    $comments .= "file format not accepted.";
    go_to_profile();
}

// check extension and mime type
if (!in_array($fileExtension, $allowedExtensions) || !in_array($fileMimeType, $allowedMimeTypes)) {
    $comments .= "file type not allowed.";
    go_to_profile();
}

// Check metadata
if (!$imageData = getimagesize($fileTmpPath)) {
    $comments .= "File is not a valid image.";
    go_to_profile();
}

// Check image dimension
if ($imageData[0] <= 0 || $imageData[1] <= 0) {
    $comments .= "Invalid image dimension.";
    go_to_profile();
}

// check file size
if ($fileSize > $maxFileSize) {
    $comments .= "ile is too large. Max size allowed : 2MB.";
    go_to_profile();
}

// rename file on the server
$newFileName = bin2hex(random_bytes(16)) . '.' . 'jpg';
$destination = $target_dir . $newFileName;
$_SESSION['uploaded_image'] = $destination;


// move the file
if (move_uploaded_file($fileTmpPath, $destination)) {
    $comments .= "The file has been uploaded. You can save it now";
    go_to_profile();
} else {
    $comments .=  "There was an error while moving the file.";
    go_to_profile();
}

 

?>
