<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"> 
    <title>Forgot Password</title>
</head>
<body>
    <div class="login-container">
        <h2>Forgot Password</h2>
        <p>Enter your email address to receive a password reset link.</p>
        <?php 
            if (isset($_GET['message'])) {
                $message = urldecode($_GET['message']);
                echo "<p>$message</p>";
            }
        ?>
        <form action="forgot_process.php" method="post">
            <div class="form-group-login-login"> 
                <label for="email">Email:</label>
                <input type="email" id="email" placeholder="example@gmail.com" name="email" required>
            </div>
            <div class="form-group-button-login"> 
                <button type="submit">Reset Password</button>
            </div>
        </form>
        <button class="go-back-button" onclick="goBack()">Go Back</button>
    </div>

    <script>
        function goBack() {
            window.location.href = '../signup/login.php';
        }
    </script>
</body>
</html>
