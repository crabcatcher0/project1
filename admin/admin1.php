<?php

include("../db_conn.php");
$conn = connectToDatabase();

// check if there are any existing admin accounts
$query = "SELECT COUNT(*) as adminCount FROM admin";
$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $adminCount = $row['adminCount'];
    
   if ($adminCount > 0) {
        // redirect to login page if an admin account already exists
        header("Location: login1.php?admin_exists=1");       
        exit();

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $contact = $_POST['contact'];

        //  validating data
        if (empty($name) || empty($email) || empty($password) || empty($contact)) {
            $error_message = 'All fields are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = 'Invalid email format.';
        } elseif (strlen($password) < 6) {
            $error_message = 'Password must be at least 6 characters long.';
        } else {
            // hash the password before storing it 
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insertQuery = "INSERT INTO admin (name, email, password, contact) VALUES ('$name', '$email', '$hashedPassword', '$contact')";
            $insertResult = mysqli_query($conn, $insertQuery);

            if ($insertResult) {
                // Redirect to login page 
                header("Location: login1.php?signup_success=1");
                exit();
            } else {
                $error_message = 'Error creating admin. Please try again.';
            }
        }
    }
} else {
    $error_message = 'Error checking existing admin accounts. Please try again.';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Signup</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php if (isset($error_message)) : ?>
        <p><?php echo $error_message; ?></p>
    <?php else : ?>
        <form method="post" action="">
            <label for="name">Name:</label>
            <input type="text" name="name" required><br>

            <label for="email">Email:</label>
            <input type="email" name="email" required><br>

            <label for="password">Password:</label>
            <input type="password" name="password" required><br>

            <label for="contact">Contact:</label>
            <input type="text" name="contact" required><br>

            <input type="submit" value="Sign Up">
        </form>
    <?php endif; ?>
</body>
</html>
