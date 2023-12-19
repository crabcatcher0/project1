<?php
session_start();
include("../db_conn.php");
$conn = connectToDatabase();

// checking if  user is logged in
if (!isset($_SESSION["email"])) {
    // Redirecting to login page if not logged in
    header("Location: ../signup/login.php");
    exit;
}

// fetching user's info
$email = $_SESSION["email"];
$fetchUserInfoQuery = $conn->prepare("SELECT * FROM user WHERE email = ?");
if ($fetchUserInfoQuery === false) {
    die('Error preparing query: ' . $conn->error);
}

$fetchUserInfoQuery->bind_param("s", $email);
$fetchUserInfoQuery->execute();
$fetchUserInfoResult = $fetchUserInfoQuery->get_result();
if ($fetchUserInfoResult === false) {
    die('Error executing query: ' . $fetchUserInfoQuery->error);
}

$userInfo = $fetchUserInfoResult->fetch_assoc();

// Closing the query
$fetchUserInfoQuery->close();

// fetching booked hostels information
$fetchBookedHostelsQuery = $conn->prepare("SELECT * FROM booked_hostel WHERE email = ?");
if ($fetchBookedHostelsQuery === false) {
    die('Error preparing query: ' . $conn->error);
}

$fetchBookedHostelsQuery->bind_param("s", $email);
$fetchBookedHostelsQuery->execute();
$bookedHostelsResult = $fetchBookedHostelsQuery->get_result();
if ($bookedHostelsResult === false) {
    die('Error executing query: ' . $fetchBookedHostelsQuery->error);
}

$bookedHostels = $bookedHostelsResult->fetch_all(MYSQLI_ASSOC);

// Closing the query
$fetchBookedHostelsQuery->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
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
                    //  if the user is logged in
                    if (isset($_SESSION['email'])) {
                        // User is logged in, show "Account" link
                        echo '<li><a href="../accounts/account.php">Account</a></li>';
                    } else {
                        // if User is not logged in, show "Sign Up" link
                        echo '<li><a href="../signup/signup.php">Sign Up</a></li>';
                    }
                ?>
                <li><a href="../contact/contact.php">Contact us</a></li>
            </ul>
        </nav>
    </header>
    <div class="account-info">
        <h2>Account Information</h2>
        <p>Email: <?php echo $userInfo['email']; ?></p> 
        <p>Welcome, <?php echo $userInfo['name']; ?></p>
        <h3>Booked Hostels</h3>
        <table>
    <tr>
        <th>Image</th>
        <th>Name</th>
        <th>Price</th>
        <th>Information</th>
        <th>Action</th>
        <th>Rating</th>
    </tr>
    <?php foreach ($bookedHostels as $bookedHostel): ?>
        <tr>
            <?php
            // fetching hostel details from the hostel table using hostel ID
            $hostelId = $bookedHostel['hostel_id'];
            $fetchHostelQuery = $conn->prepare("SELECT * FROM hostel WHERE id = ?");
            if ($fetchHostelQuery === false) {
                die('Error preparing query: ' . $conn->error);
            }

            $fetchHostelQuery->bind_param("i", $hostelId);
            $fetchHostelQuery->execute();
            $fetchHostelResult = $fetchHostelQuery->get_result();
            if ($fetchHostelResult === false) {
                die('Error executing query: ' . $fetchHostelQuery->error);
            }

            $hostelDetails = $fetchHostelResult->fetch_assoc();
            $fetchHostelQuery->close();

            // displaying hostel details
            echo '<td><img src="../dashboard/upload/' . $hostelDetails['image'] . '" alt="Hostel Image"></td>';
            echo '<td>' . $hostelDetails['hostelname'] . '</td>';
            echo '<td>Rs.' . $hostelDetails['price'] . '</td>';
            echo '<td>' . $hostelDetails['information'] . '</td>';

          // action column (Unbook and Payment buttons)
            echo '<td class="action-cell">';
            echo '<form action="unbook.php" method="post">';
            echo '<input type="hidden" name="hostel_id" value="' . $hostelId . '">';
            echo '<button type="submit">Unbook</button>';
            echo '</form>';

            // for payment button
            echo '<form action="payment.php" >';   //method="post" is removed
            echo '<input type="hidden" name="hostel_id" value="' . $hostelId . '">';
            echo '<button type="submit">Payment</button>';
            echo '</form>';
            echo '</td>';

            // for rating 
            echo '<td class="rating-cell">';
            echo '<form action="submit_rating.php" method="post">';
            echo '<input type="hidden" name="hostel_id" value="' . $hostelId . '">';
            echo '<label for="rating">Rate</label>';
            echo '<select name="rating" id="rating">';
            for ($i = 1; $i <= 5; $i++) {
                echo '<option value="' . $i . '">' . $i . '</option>';
            }
            echo '</select>';
            echo '<button type="submit">Submit</button>';
            echo '</form>';
            echo '</td>';
            
            ?>
        </tr>
    <?php endforeach; ?>
</table>
        <form action="logout.php" method="post">
            <button type="submit">Logout</button>
        </form>
    </div>

   
    <script>
    function goBack() {
        window.location.href = '../hostel/hostel.php';
    }
</script>

  
</body>
</html>
