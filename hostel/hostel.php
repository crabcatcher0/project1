<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostels</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <header>
        <nav class="navbar">
            <a class="logo" href="#">HostelDiscover</a>
            <ul class="menu-links">
                <li><a href="../index.php">Home</a></li>
                <li><a href="#">Hostels</a></li>
                <?php
                    session_start(); // starting a php session to manage user data across pages
                    // Check if the user is logged in through email
                    if (isset($_SESSION['email'])) {
                        // User is logged in show "Account" link
                        echo '<li><a href="../accounts/account.php">Account</a></li>';
                    } else {
                        // User is not logged in show "Sign Up" link
                        echo '<li><a href="../signup/signup.php">Sign Up</a></li>';
                    }
                ?>
                <li><a href="../contact/contact.php">Contact us</a></li>
            </ul>
        </nav>
    </header>
    
    <div class="long-card-container">
        <h1 class="hostel-list-heading">Welcome to Our Hostel List.</h1>
        <p class="hostel-description">
            Whether you're a student, solo traveler, a group of friends, or a family, our hostels provide a welcoming and affordable accommodation option. Enjoy friendly staff, and a vibrant atmosphere. Browse through our hostel list below to find the perfect place for you.
        </p>
    </div>

    <div class="card-container">
        <?php
            include("../db_conn.php");
            $conn = connectToDatabase();

            // from the database table name hostel
            $result = mysqli_query($conn, "SELECT * FROM hostel");

            while ($row = mysqli_fetch_assoc($result)) { // while loop through each fetched hostel record
                // display each hostel as a card
                echo '<div class="card">';
                $imagePath = "../dashboard/upload/" . $row['image']; // making the image path for the hostel

                if (file_exists($imagePath)) { // check If the image exists then display it
                    echo '<img src="' . $imagePath . '" alt="Hostel Image">';
                } else { // if the image doesn't exist then display a message image not found
                    echo 'Image not found';
                }

                echo '<h3>' . $row['hostelname'] . '</h3>'; // displaying hostel name
                echo '<p>Price: Rs. ' . $row['price'] . '</p>'; // hostel price
                echo '<a class="view-more-btn" href="details.php?id=' . $row['id'] . '">View More</a>';
                echo '</div>';
            }

            // close the database connection
            mysqli_close($conn);
        ?>
    </div>
</body>
</html>
