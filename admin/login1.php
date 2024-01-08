<?php
session_start();

include("../db_conn.php");
$conn = connectToDatabase();

// check if the admin already exists message is set
$adminExists = isset($_GET['admin_exists']) && $_GET['admin_exists'] == 1;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // validation for email and password 

    $query = "SELECT * FROM admin WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $admin = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin'] = $admin['id'];
            header('Location: ../dashboard/admin.php'); // redirecting to the admin dashboard
            exit();
        } else {
            $error_message = 'Invalid email or password. Please try again.';
        }
    } else {
        $error_message = 'Error in preparing the login statement: ' . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <?php if (isset($error_message)) : ?>
        <p><?php echo $error_message; ?></p>
    <?php endif; ?>

    <?php if ($adminExists) : //message display?>
        <p>Admin account already exists cannot register. Please login.</p>
    <?php endif; ?>

    <form method="post" action="">
        <label for="email">Email:</label>
        <input type="email" name="email" placeholder="example@gmail.com" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" placeholder="password" required><br>

        <input type="submit" value="Login">
        
    </form>
</body>
</html>
