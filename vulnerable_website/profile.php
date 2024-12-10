<?php
session_start();
include "db.php";

$id = $_SESSION['id'];
$sql = "SELECT email from users where id= '$id'";
$result = $conn->query($sql);
$email = $result->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>
    <div class="signup-container">
	<main>
        <h2><?php echo htmlspecialchars($_SESSION['username']); ?> Profile</h2>
    	</main>
       <form action="update_profile.php" method="POST">
	    <a href="upload.html">update photo</a>
	    <img src="blank-profile-picture-973460_1280.png" style="width:100%">
	    <label id="enable_email_field" style="color: blue; cursor: pointer; text-decoration: underline;">update email</label>
            <input type="email" id="email" name="email" value=<?php echo htmlspecialchars("$email");?> readonly>
	    <script>
                document.getElementById("enable_email_field").addEventListener("click", function() {
			document.getElementById("email").removeAttribute("readonly");
		});
    	    </script>
            <label id="password_update" style="color: blue; cursor: pointer; text-decoration: underline;">update password</label>
	    <script>
                document.getElementById("password_update").addEventListener("click", function() {
                        document.getElementById("old_password").removeAttribute("hidden");
                        document.getElementById("new_password").removeAttribute("hidden");
                        document.getElementById("confirm_new_password").removeAttribute("hidden");
                });
            </script>
            <input id="old_password" type="password" placeholder="Old password" name="old_password" hidden>
            <input id="new_password" type="password" placeholder="New password" name="new_password" hidden>
            <input id="confirm_new_password" type="password" placeholder="Confirm Password" name="confirm_new_password" hidden>
            <button type="submit">Modify</button>
        </form>
	<?php
	if (isset ($_GET['param']))
		if ($_GET['param'] == 1)
			echo "Profile updated successfully";
	?>
    </div>
</body>
</html>
