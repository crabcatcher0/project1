<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"> 
    <title>Reset Password</title>
</head>
<body>

    <div class="login-container">
        <h2>Reset Password</h2>
        <?php 
            if (isset($_GET['message'])) {
                $error = urldecode($_GET['message']);
                echo "<p class='error-message'>$error</p>";
            }
        ?>
        <form action="password_recovery.php" method="post">
            <div class="form-group-login-login"> 
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group-login-login"> 
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group-button-login"> 
                <input type="hidden" name="email" value="<?php echo $email; ?>">
                <button type="submit">Reset Password</button>
            </div>
        </form>
    </div>

</body>
</html>
