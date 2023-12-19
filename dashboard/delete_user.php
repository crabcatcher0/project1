<?php
include("../db_conn.php");
$conn = connectToDatabase();   //again database connection

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // retrieve the email from the URL
    $email = isset($_GET['email']) ? $_GET['email'] : '';

    // deleting  on user table 
    $deleteUserQuery = $conn->prepare("DELETE FROM user WHERE email = ?");
    $deleteUserQuery->bind_param("s", $email);

    if ($deleteUserQuery->execute()) {
        // if user deletion is successful then perform delete operation on booked_hostel table too
        $deleteBookingQuery = $conn->prepare("DELETE FROM booked_hostel WHERE email = ?");
        $deleteBookingQuery->bind_param("s", $email);

        if ($deleteBookingQuery->execute()) {
            // redirecting  back to the user list after successful deletion
            header('Location: users.php');
            exit();
        } else {
            echo "Error deleting booking information.";
        }

        $deleteBookingQuery->close(); //closing the querry here
    } else {
        echo "Error deleting user.";
    }

    $deleteUserQuery->close();
    $conn->close();
}
?>
