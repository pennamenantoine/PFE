<?php
$target_dir = "uploads/";
if (!is_dir($target_dir)) {
	mkdir($target_dir, 0777, true);
}
$uploadOk = 1;
$message = "";

// Prepare file path
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$file_extension = pathinfo($target_file, PATHINFO_EXTENSION);

// Blacklist
$excluded_extensions = ['php', 'phtml', 'js', 'sh', 'bash', 'zsh', 'exe', 'bin', 'app', 'msi', 'dll', 'so', 'pdb', 'asp', 'aspx', 'pl', 'cgi', 'py', 'swf', 'flv', 'docm', 'xlsm', 'pptm'];

// Check if the file extension is in the excluded list
if (in_array($file_extension, $excluded_extensions)) {
	$message = "File type not allowed. ";
	$uploadOk = 0;
}

// Check if file upload was successful
if ($uploadOk == 0) {
	$message .= "Your file was not uploaded. ";
   	header("Location: upload.php?message=" . urlencode($message));  // Redirect with the message
 	exit;
} else {
    // move the uploaded file
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
