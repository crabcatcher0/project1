<?php
session_start();
include("../db_conn.php");
$conn = connectToDatabase();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hostelId = $_POST["hostel_id"];
    $email = $_SESSION["email"];

    // deleting the booked hostel
    $deleteQuery = $conn->prepare("DELETE FROM booked_hostel WHERE email = ? AND hostel_id = ?");
    $deleteQuery->bind_param("si", $email, $hostelId);
    $deleteQuery->execute();

    // closing the query
    $deleteQuery->close();

    // redirecting back to the account page
    header("Location: ../accounts/account.php");
    exit;
}
?>
