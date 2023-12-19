<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
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
                session_start();
                    // Check if the user is logged in
                    if (isset($_SESSION['email'])) {
                        // User is logged in, show "Account" link
                        echo '<li><a href="../accounts/account.php">Account</a></li>';
                    } else {
                        // User is not logged in, show "Sign Up" link
                        echo '<li><a href="../signup/signup.php">Sign Up</a></li>';
                    }
                ?>
                <li><a href="#">Contact us</a></li>
            </ul>
        </nav>
    </header>
    <?php
include("../db_conn.php");
$conn = connectToDatabase();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $insertQuery = "INSERT INTO contact (fullname, email, message) VALUES ('$fullname', '$email', '$message')";

    if (mysqli_query($conn, $insertQuery)) {
      $thankYouMessage = "Thank you for your message!";
      // redirecting to contact us page after a short delay
      header("refresh:2;url=contact.php");
  } else {
      echo "Error: " . $insertQuery . "<br>" . mysqli_error($conn);
  }
}

mysqli_close($conn);
?>

    <div class="container">
        <div class="content">
            <div class="right-side">
            <?php
            // checking if  variable $thankYouMessage is empty or not
                if (!empty($thankYouMessage)) {
                        // if $thankYouMessage is not empty div class  containing the message is executed
                    echo '<div class="thank-you-message">' . $thankYouMessage . '</div>';
                } else {
                    //if variable $thankYouMessage is empty showing nothing
                ?>
                <div class="topic-text">Send us a message.</div>
                <p>Feel free to reach out to us with any inquiries or special requests about our hostels. We are dedicated to providing a seamless and delightful experience, ensuring your stay with HostelDiscover exceeds expectations.</p>
                <form action="#" method="post">
                    <div class="input-box">
                        <input type="text" name="fullname" placeholder="Enter your Full name" required />
                    </div>
                    <div class="input-box">
                        <input type="email" name="email" placeholder="Example@gmail.com" required />
                    </div>
                    <div class="input-box message-box">
                        <textarea name="message" placeholder="Enter your message"></textarea>
                    </div>
                    <div class="button">
                        <input type="submit" value="Send Now" />
                    </div>
                    <?php
                }
                ?>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
