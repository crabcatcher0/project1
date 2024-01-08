<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"> 
    <title>Login Page</title>
</head>
<body>


    <div class="login-container">
      <h2>Please Login</h2>
        <?php 
        //for error checking also displays the message like einvalid email or password
        if (isset($_GET['message'])) {
            $message = urldecode($_GET['message']); //get message from login_process.php
            echo "<p style='color: red;'>$message</p>";
        }?>
        <?php 
            if (isset($_GET['success'])) {   
                $successMessage = urldecode($_GET['success']);
                echo "<p class='success-message'>$successMessage</p>";
            }
        ?>
        <form action="login_process.php" method="post">
            <div class="form-group-login-login"> 
                <label for="email">Email:</label>
                <input type="email" id="email" placeholder="example@gmail.com" name="email" required>
            </div>
            <div class="form-group-login-login"> 
                <label for="password">Password:</label>
                <input type="password" id="password" placeholder="Enter your password" name="password" required>
            </div>
            <div class="forgot-password">
            <a href="../password_recovery/forgot_password.php">Forgot Password?</a>
              </div>
            <div class="form-group-button-login"> 
                <button type="submit">Login</button>
            </div>

        </form>
        <button class="go-back-button" onclick="goBack()">Go Back</button>
    </div>
    <script>
            function goBack() {
                
                window.location.href = '../index.php';
            }
        </script>
</body>
</html>
