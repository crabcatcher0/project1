<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    
    <div class="container">
        <h4>Please Register.</h4>
        <?php
        // This is to display a thank you message
        if (isset($_GET['message'])) {
            $message = urldecode($_GET['message']);
            echo "<p>$message</p>";
        }
         // Include the database connection file
        include("../db_conn.php");
        $conn = connectToDatabase();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieving form data
            $name = isset($_POST["fullname"]) ? $_POST["fullname"] : "";
            $lastName = isset($_POST["lastname"]) ? $_POST["lastname"] : "";
            $email = isset($_POST["email"]) ? $_POST["email"] : "";
            $password = isset($_POST["password"]) ? $_POST["password"] : "";
            $address = isset($_POST["address"]) ? $_POST["address"] : "";
            $contactNumber = isset($_POST["contact_number"]) ? $_POST["contact_number"] : "";
            //  validating before inserting
            if (empty($name) || empty($lastName) || empty($email) || empty($password) || empty($address) || empty($contactNumber)) {
                echo "All fields are required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Invalid email address.";
            } elseif (!preg_match('/^\d{10}$/', $contactNumber)) {
                echo "Contact number must be a 10-digit number.";
            } else {
                // Hashing the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $existedEmail = $conn->prepare("SELECT email FROM user WHERE email = ?");
                if ($existedEmail === false) {
                    echo "Error: " . $conn->error;
                } else {
                    $existedEmail->bind_param("s", $email);
                    $existedEmail->execute();
                    $existedEmail->store_result();
                    if ($existedEmail->num_rows > 0) {
                        echo "Email already exists!";
                    } else {
                        // Inserting data into the database
                        $stmt = $conn->prepare("INSERT INTO user(name, last_name, email, password, address, contact_number) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("ssssss", $name, $lastName, $email, $hashedPassword, $address, $contactNumber);

                        if ($stmt->execute()) {
                            $message = "Thank You! You can now log in.";
                            // Redirect to login.php
                            header("Location: login.php?message=" . urlencode($message));
                            exit;
                        } else {
                            echo "Error: " . $stmt->error;
                        }
                        $stmt->close();
                    }
                   $existedEmail->close();
                }
            }
        }
        // Closing the database connection
        $conn->close();
        ?>        
       <form action="signup.php" method="post">
            <div class="form-group">
                <input type="text" name="fullname" placeholder="Name" required>
            </div>
            <div class="form-group">
                <input type="text" name="lastname" placeholder="Last Name" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="example@gmail.com" required>
            </div>
            <div class="form-group">
                <input type="text" name="address" placeholder="Address" required>
            </div>
            <div class="form-group">
                <input type="text" name="contact_number" placeholder="Contact Number" required>
            </div>
            <div class="form-group">
                <input type="text" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Register" name="submit">
            </div>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
        <a href="../index.php">
            <button>Go Back</button>
       </a>

    </div>
</body>
</html>
