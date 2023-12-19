<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <a class="logo" href="#">HostelDiscover</a>
            <ul class="menu-links">
                <li><a href="../index.php">Home</a></li>
                <li><a href="hostel.php">Hostels</a></li>
                <?php
                session_start(); //session started 
                // check if the user is logged in
                if (isset($_SESSION['email'])) {
                    // if User is logged in, show "Account" link
                    echo '<li><a href="../accounts/account.php">Account</a></li>';
                } else {
                    // else User is not logged in, show "Sign Up" link
                    echo '<li><a href="../signup/signup.php">Sign Up</a></li>';
                }
                ?>
                <li><a href="../contact/contact.php">Contact us</a></li>
            </ul>
        </nav>
    </header>

    <?php
    include("../db_conn.php"); //database connection calling function connectToDatabase
    $conn = connectToDatabase();

    if (isset($_GET['id'])) {
        $hostelId = $_GET['id'];

        // Fetch hostel details along with additional information from the rooms table
        $query = "SELECT hostel.*, AVG(average_rating) AS avg_rating, rooms.num_beds, rooms.num_students, hostel.location, hostel.type
          FROM hostel
          LEFT JOIN rooms ON hostel.id = rooms.hostel_id
          WHERE hostel.id = $hostelId";


        $result = mysqli_query($conn, $query);
        
    // Check if the query was successful
    if (!$result) {
        die('Query failed: ' . mysqli_error($conn));
    }

        if ($row = mysqli_fetch_assoc($result)) {
            // Display information
            echo '<div class="card-details">';
            $imagePath = "../dashboard/upload/" . $row['image'];
            if (file_exists($imagePath)) {
                echo '<img src="' . $imagePath . '" alt="Hostel Image">';
            } else {
                echo 'Image not found';
            }
            echo '<h3>' . $row['hostelname'] . '</h3>';
            echo '<p>Price: Rs. ' . $row['price'] . '</p>';
            echo '<p1>' . $row['information'] . '</p1>';
            echo '<p>Type: ' . $row['type'] . '</p>';
            echo '<p>Available Beds: ' . $row['num_beds'] . '</p>';
            echo '<p>Number of Student in one room: ' . $row['num_students'] . '</p>';
            echo '<p> ' . $row['location'] . '</p>';
            echo '<p>Average Rating: ' . round($row['avg_rating'], 1) . '</p>';

            // Check if the user is logged in
            if (isset($_SESSION['email'])) {
                // If the user is logged in, display the booking form / unbook button
                $userEmail = $_SESSION['email'];
                $checkBookingQuery = mysqli_query($conn, "SELECT * FROM booked_hostel WHERE email = '$userEmail' AND hostel_id = $hostelId");

                if ($checkBookingQuery && mysqli_num_rows($checkBookingQuery) > 0) {
                    // If the hostel is already booked, show this
                    echo '<p style="color: red;">This hostel was already booked by you!</p>';
                } else {
                    // If the hostel is not booked, display the book button
                    echo '<form action="book_hostel.php" method="post">';
                    echo '<input type="hidden" name="hostel_id" value="' . $row['id'] . '">';
                    echo '<button type="submit">Book Hostel</button>';
                    echo '</form>';
                }
            } else {
                // User is not logged in, redirect to the login page
                echo '<button onclick="redirectToLogin()">Login to Book Hostel</button>';
            }

            echo '</div>';
        } else {
            echo 'Hostel not found';
        }
    } else {
        echo 'Invalid request';
    }

    // Close the database connection
    mysqli_close($conn);
    ?>

    <script>
        function redirectToLogin() { // Function to redirect to the login page
            // Redirect to the login page
            window.location.href = '../signup/signup.php';
        }
    </script>

</body>
</html>
