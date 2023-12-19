<?php
include("../db_conn.php");
$conn = connectToDatabase();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = isset($_POST['email']) ? $_POST['email'] : "";

    // checking if the email exists in the user table
    $checkUserQuery = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email'");

    if ($checkUserQuery) {
        $user = mysqli_fetch_assoc($checkUserQuery);

        if ($user) {
            // The email exists in the database
            // You can now proceed with sending a reset link or redirect to a password reset page
            // For now, let's redirect to reset_password.php
            header("Location: reset_password.php");
            exit();
        } else {
            // If the email doesn't exist, show an error message
            header("Location: forgot_password.php?message=Email%20address%20not%20found");
            exit();
        }
    } else {
        die("Query failed: " . mysqli_error($conn));
    }
}
?>
