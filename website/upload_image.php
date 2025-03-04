<?php
$target_dir = "./uploads/";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
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
$fileExtension = strtolower(pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION));
// finfo() to check the mime type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$fileMimeType = finfo_file($finfo, $fileTmpPath);
finfo_close($finfo);

// check if file is a real image, use @ to delete warnings
if (!@getimagesize($fileTmpPath)) {
    die("file format not accepted.");
}

// check extension and mime type
if (!in_array($fileExtension, $allowedExtensions) || !in_array($fileMimeType, $allowedMimeTypes)) {
    die("file type not allowed.");
}

// Check metadata
if (!$imageData = getimagesize($fileTmpPath)) {
    die("File is not a valid image.");
}

// Check image dimension
if ($imageData[0] <= 0 || $imageData[1] <= 0) {
    die("Invalid image dimensions.");
}

// check file size
if ($fileSize > $maxFileSize) {
    die ("file is too large. Max size allowed : 2MB.");
}

// rename file on the server
$newFileName = bin2hex(random_bytes(16)) . '.' . 'jpg';
$destination = $target_dir . $newFileName;
$_SESSION['uploaded_image'] = $destination;

// move the file
if (move_uploaded_file($fileTmpPath, $destination)) {
    header("Location: profile.php");
    exit;
} else {
    die("There was an error while moving the file.");
}
?>
