<?php
session_start();
//function calling to establish connection 
include("../db_conn.php");
$conn = connectToDatabase(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hostelId = $_POST["hostel_id"];
    $email = $_SESSION["email"];

    // fetch hostel details from the database
    $fetchHostelQuery = $conn->prepare("SELECT hostelname, price, information, image FROM hostel WHERE id = ?"); //takes the id of hostel
    $fetchHostelQuery->bind_param("i", $hostelId);
    $fetchHostelQuery->execute();
    $fetchHostelResult = $fetchHostelQuery->get_result();
    $hostelRow = $fetchHostelResult->fetch_assoc();
    $hostelName = $hostelRow['hostelname'];
    $hostelPrice = $hostelRow['price'];
    $hostelInformation = $hostelRow['information'];
    $hostelImage = $hostelRow['image'];

    // inserting bookings into booked_hostels table in signup database
    $insertQuery = $conn->prepare("INSERT INTO booked_hostel (email, hostel_id, hostelname, price, information, image) VALUES (?, ?, ?, ?, ?, ?)");
    $insertQuery->bind_param("sissss", $email, $hostelId, $hostelName, $hostelPrice, $hostelInformation, $hostelImage);
    $insertQuery->execute();

    // Close the queries
    $fetchHostelQuery->close();
    $insertQuery->close();

    // redirecting to the account page
    header("Location: ../accounts/account.php");
    exit;
}
?>
