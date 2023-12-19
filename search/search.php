<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <a class="logo" href="#">HostelDiscover</a>
            <ul class="menu-links">
                <li><a href="../index.php">Home</a></li>
                <li><a href="../hostel/hostel.php">Hostels</a></li>
                <?php
                session_start(); // starting  php session to manage user data through the pages
                if (isset($_SESSION['email'])) {  // check if the user is logged in
                    // if the user is logged in, show "Account" link
                    echo '<li><a href="../accounts/account.php">Account</a></li>';
                } else {
                    // else the user is not logged in, show "Sign Up" link
                    echo '<li><a href="../signup/signup.php">Sign Up</a></li>';
                }
                ?>
                <li><a href="../contact/contact.php">Contact us</a></li>
            </ul>
        </nav>
    </header>

    <?php

    include("../db_conn.php");
    $conn = connectToDatabase();

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        // Retrieve the search query from the form
        $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

        // Prepare and execute a search query
        $sql = "SELECT * FROM hostel WHERE hostelname LIKE '%$searchQuery%' OR information LIKE '%$searchQuery%' ";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Display the search results
            echo "<section class='hostels-section'>";
            while ($row = $result->fetch_assoc()) {
                echo "<div class='hostel'>";
                $imagePath = "../dashboard/upload/" . $row['image'];
                if (file_exists($imagePath)) {
                    echo '<img src="' . $imagePath . '" alt="Hostel Image">';
                } else {
                    echo 'Image not found';
                }
                echo "<h3>" . $row['hostelname'] . "</h3>";
                echo '<p>Price: Rs.' . $row['price'] . '</p>';
                echo '<p>' . $row['information'] . '</p>';
                echo '<button onclick="bookHostel(' . $row['id'] . ')">Book Now</button>';
                echo "</div>";
            }
            echo "</section>";
        } else {
            // Display a message when no hostels are found
            echo "<p class='no-hostel-message'>No hostels found for your search. <a href='../index.php'>Go back</a></p>";
        }
    }

    // Close the database connection
    mysqli_close($conn);
    ?>

    <script>
        function bookHostel(hostelId) {
            <?php if (isset($_SESSION['email'])) : ?>
                // if the User is logged in, redirect to the booking page
                window.location.href = '../hostel/details.php?id=' + hostelId;
            <?php else : ?>
                // User is not logged in, redirect to the login page
                window.location.href = '../signup/signup.php';
            <?php endif; ?>
        }
    </script>
</body>
</html>
