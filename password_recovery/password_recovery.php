<?php
include("../db_conn.php");
$conn = connectToDatabase();

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = isset($_SESSION['reset_email']) ? $_SESSION['reset_email'] : "";
    $password = isset($_POST['password']) ? $_POST['password'] : "";
    $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : "";

    // Validate that password and confirm password match
    if ($password === $confirmPassword) {
        // Hash the password before storing it in the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Update the user's password in the database
        $updatePasswordQuery = mysqli_query($conn, "UPDATE user SET password = '$hashedPassword' WHERE email = '$email'");

        if ($updatePasswordQuery) {
            // Password updated successfully
            // You might want to redirect the user to a login page or show a success message
            header("Location: ../signup/login.php?success=Password%20updated%20successfully.%20Please%20login.");
            exit();
        } else {
            // Handle the case where the database update fails
            die("Query failed: " . mysqli_error($conn));
        }
    } else {
        // Passwords do not match, handle this case (e.g., show an error message)
        header("Location: reset_password.php?message=Passwords%20did%20not%20match.");
        exit();
    }
} else {
    // Redirect the user if the form is not submitted
    header("Location: reset_password.php");
    exit();
}
?>
