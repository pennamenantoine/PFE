<?php

include "db.php";
include 'navbar.php';
//nonce for javascript

$id = $_SESSION['id'];
$uploadDir = "uploads/";

$sql = "SELECT email from users where id= '$id'";
$result = $conn->query($sql);
$email = $result->fetchColumn();


if (isset($_SESSION['uploaded_image'])) {
    $picture = $_SESSION['uploaded_image'];
    unset($_SESSION['uploaded_image']);
} else {
    $sql = "SELECT file_path FROM images WHERE user_id = '$id'";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $picture = $row['file_path'];
        } else {
            $picture = $uploadDir . "blank-profile-picture.png";
        }
    } else {
        echo "Query Error: " . $conn->errorInfo()[2];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body id="profile.php">
    <div class="signup-container">
	<main>
        <h2><?php echo htmlspecialchars($_SESSION['username']); ?> Profile</h2>
    	</main>
       <form action="update_profile.php" method="POST">
	    <a href="upload.php">update photo</a>
        <img src="<?php echo $picture; ?>" style="width:100%; max-width: 300px; max-height: 300px; object-fit: cover;">
        <input type="text" name="picture" value="<?php echo $picture; ?>" hidden>

	    <label id="enable_email_field" style="color: blue; cursor: pointer; text-decoration: underline;">update email</label>
            <input type="email" id="email" name="email" value=<?php echo htmlspecialchars("$email");?> readonly>
        <script nonce="<?php echo $nonce; ?>">
            document.getElementById("enable_email_field").addEventListener("click", function() {
            document.getElementById("email").removeAttribute("readonly");
            });
        </script>

        <label id="password_update" style="color: blue; cursor: pointer; text-decoration: underline;">update password</label>
	    <script nonce="<?php echo $nonce; ?>">
                document.getElementById("password_update").addEventListener("click", function() {
                        document.getElementById("old_password").removeAttribute("hidden");
                        document.getElementById("new_password").removeAttribute("hidden");
                        document.getElementById("confirm_new_password").removeAttribute("hidden");
                });
            </script>
            <input id="old_password" type="password" placeholder="Old password" name="old_password" hidden>
            <input id="new_password" type="password" placeholder="New password" name="new_password" hidden>
            <input id="confirm_new_password" type="password" placeholder="Confirm Password" name="confirm_new_password" hidden>
            <button type="submit">Save</button>
        </form>
	<?php
	if (isset ($_GET['param'])){
        //echo $_GET['param'];
		echo "Profile updated successfully";
    }
	?>
    </div>
</body>
</html>
