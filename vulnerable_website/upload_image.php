<?php
$target_dir = "uploads/";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}
$uploadOk = 0;
$message = "";

// Prepare file path and image type
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if the uploaded file is an actual image
if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] === UPLOAD_ERR_OK)
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
else
  $check = false;

if ($check !== false) {
    $message = "File is an image - " . $check["mime"] . ". ";
    $uploadOk = 1;
} else {
    $message = "File is not an image. ";
    $uploadOk = 0;
}

// Allow only specific image formats
//if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
//    $message .= "Only JPG, JPEG, PNG & GIF files are allowed. ";
//   $uploadOk = 0;
//}

// Allow all except specific executable formats
// List of excluded file extensions (dangerous files)
$excluded_extensions = ['php', 'phtml', 'js', 'sh', 'bash', 'zsh', 'exe', 'bin', 'app', 'msi', 'dll', 'so', 'pdb', 'asp', 'aspx', 'pl', 'cgi', 'py', 'swf', 'flv', 'docm', 'xlsm', 'pptm'];

// Check if the file extension is in the excluded list
if (in_array($imageFileType, $excluded_extensions)) {
  $message .= "File type not allowed. ";
    $uploadOk = 0;
}

// Check if file upload was successful
if ($uploadOk == 0) {
    $message .= "Your file was not uploaded. ";
    header("Location: upload.php?message=" . urlencode($message));  // Redirect with the message
    exit;
} else {
    // Try to move the uploaded file
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $message = "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded. ";
        header("Location: profile.php?param_img=$target_file");  // Redirect to profile with the file path
        exit;
    } else {
        $message = "Sorry, there was an error uploading your file. ";
        header("Location: upload.php?message=" . urlencode($message));  // Redirect with error message
        exit;
    }
}
?>