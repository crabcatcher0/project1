<?php
session_start();
include("../db_conn.php");
$conn = connectToDatabase();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT email, password FROM user WHERE email = '$email'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $existingEmail = $row['email'];
    $dbPassword = $row['password'];
 
    if ($existingEmail == $email && password_verify($password, $dbPassword)) {
        // password is correct checks email and password with function password_verify
        $_SESSION["email"] = $existingEmail;

        // redirect to the account page
        header("Location: ../accounts/account.php");
        exit;
    } else {  //error message
        $message = "Invalid email or password";
        header("Location: login.php?message=" . urlencode($message));
    }
}

$conn->close();
?>
