<?php
$target_dir = "uploads/";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}

$maxFileSize = 2 * 1024 * 1024; // 2MB
$allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

if (!isset($_FILES['fileToUpload']) || $_FILES['fileToUpload']['error'] !== UPLOAD_ERR_OK) {
    die("There was an error while uploading the file.");
}

// file check
$fileTmpPath = $_FILES['fileToUpload']['tmp_name'];
$fileSize = $_FILES['fileToUpload']['size'];
$fileMimeType = mime_content_type($fileTmpPath);
$fileExtension = strtolower(pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION));

// check if file is a real image
if (!getimagesize($fileTmpPath)) {
    die("file is not a valid image.");
}

// check extension and mime type
if (!in_array($fileExtension, $allowedExtensions) || !in_array($fileMimeType, $allowedMimeTypes)) {
    die("file type not allowed.");
}

// check file size
if ($fileSize > $maxFileSize) {
    die("file is too large. Max size allowed : 2MB.");
}

// rename file on the server
$newFileName = uniqid() . '.' . $fileExtension;
$destination = $target_dir . $newFileName;
$_SESSION['uploaded_image'] = $destination;

// move the file
if (move_uploaded_file($fileTmpPath, $destination)) {
    // redirection with secure path
    header("Location: profile.php");
//    header("Location: profile.php?param_img=" . urlencode($destination));
    exit;
} else {
    die("There was an error while moving the file.");
}
?>
