<?php

$target_dir = "uploads/";

if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}

$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 0;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

if(isset($_POST["submit"])) {
	$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  	if($check !== false) {
    		echo "File is an image - " . $check["mime"] . ".";
    		$uploadOk = 1;
  	}
	else {
    		echo "File is not an image.";
    		$uploadOk = 0;
  	}
}

if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
 	echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
 	$uploadOk = 0;
}

if ($uploadOk == 0) {
 	echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
}
else {
	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    		echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
  	}
	else {
    		echo "Sorry, there was an error uploading your file.";
  	}
}
?>
