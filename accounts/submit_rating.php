<?php
session_start();
include("../db_conn.php");
$conn = connectToDatabase();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hostelId = $_POST['hostel_id'];
    $rating = $_POST['rating'];

    // retrieving the user's email from the session
    $email = $_SESSION['email'];

    // checking if the user has already given a rating for this hostel
    $checkRatingQuery = mysqli_query($conn, "SELECT * FROM ratings WHERE hostel_id = $hostelId AND email = '$email'");

    if (mysqli_num_rows($checkRatingQuery) > 0) {
        // if User has already given a rating
        $message = 'You have already given a rating for this hostel.';
        echo '<div id="message" class="message">' . $message . '</div>';
        echo '<script>';
        echo 'setTimeout(function(){ window.location.href = "account.php"; }, 2000);'; //automatically redirect
        echo '</script>';
        exit;
    } else {
        // fetching hostelname from the hostel table
        $fetchHostelNameQuery = mysqli_query($conn, "SELECT hostelname FROM hostel WHERE id = $hostelId");
        $hostelNameRow = mysqli_fetch_assoc($fetchHostelNameQuery);
        $hostelName = $hostelNameRow['hostelname'];

        // Store the rating, hostelname, and email in the ratings table
        $insertRatingQuery = mysqli_query($conn, "INSERT INTO ratings (hostel_id, hostelname, rating, email) VALUES ('$hostelId', '$hostelName', '$rating', '$email')");

        if ($insertRatingQuery) {
            // calculating the new average rating
            $fetchRatingsQuery = mysqli_query($conn, "SELECT AVG(rating) AS averageRating FROM ratings WHERE hostel_id = $hostelId");
            $averageRatingRow = mysqli_fetch_assoc($fetchRatingsQuery);
            $newAverageRating = $averageRatingRow['averageRating'];

            // updating the average_rating column in the hostel table
            $updateHostelQuery = mysqli_query($conn, "UPDATE hostel SET average_rating = $newAverageRating WHERE id = $hostelId");

            if ($updateHostelQuery) {
                // redirect back to the account page
                header("Location: account.php");
                exit;
            } else {
                echo 'Error updating average rating: ' . mysqli_error($conn);
            }
        } else {
            echo 'Error inserting rating: ' . mysqli_error($conn);
        }
    }
}

// closing the database connection
mysqli_close($conn);
?>
