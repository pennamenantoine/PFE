<?php
session_start();
include "db.php";
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
       <form action="profile.php" method="post">
	    <a href="upload.php">update photo</a>
	    <img src="blank-profile-picture-973460_1280.png" style="width:100%">
	    <label id="enable_email_field" style="color: blue; cursor: pointer; text-decoration: underline;">update email</label>
            <input type="text" id="email" value=<?php echo htmlspecialchars($_SESSION['email']);?> readonly>
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
            <input id="old_password" type="password" placeholder="Old password" name="Old password" hidden>
            <input id="new_password" type="password" placeholder="New password" name="New password" hidden>
            <input id="confirm_new_password" type="password" placeholder="Confirm Password" name="confirm_password" hidden>
            <button type="submit">Modify</button>
        </form>
        <div class="login-link">
            <p>Already have an account? <a href="login.html">Login here</a></p>
        </div>
    </div>
</body>
</html>
