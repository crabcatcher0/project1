<?php
include("../db_conn.php");
$conn = connectToDatabase();

// initializing variables
$successMessage = "";
$errorMessage = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $hostelname = $_POST['hostelname'];
    $price = $_POST['price'];
    $information = $_POST['information'];
    $location = $_POST['location'];


    // New fields for number of beds and number of students
    $num_beds = $_POST['num_beds'];
    $num_students = $_POST['num_students'];
    if ($id !== null) {
        // Using prepared statement to update the record in the hostel table
        $updateQuery = $conn->prepare("UPDATE hostel SET hostelname=?, price=?, information=?, location=? WHERE id=?");
        $updateQuery->bind_param("ssssi", $hostelname, $price, $information, $location, $id);
    
        if ($updateQuery->execute()) {
            // Checking if an attempt to update the record was made
            if ($updateQuery->affected_rows >= 0) {
                $successMessage = "Hostel updated successfully!";
            } else {
                $errorMessage = "No changes made.";
            }
        } else {
            // loog the error
            error_log("Error updating record: " . $updateQuery->error);
            $errorMessage = "An error occurred while updating the hostel.";
        }
    
        // Closing the prepared statement
        $updateQuery->close();
    
        // Using prepared statement to update the record in the rooms table
        $updateRoomsQuery = $conn->prepare("UPDATE rooms SET num_beds=?, num_students=? WHERE hostel_id=?");
        $updateRoomsQuery->bind_param("iii", $num_beds, $num_students, $id);
    
        if (!$updateRoomsQuery->execute()) {
            // Log the error
            error_log("Error updating rooms record: " . $updateRoomsQuery->error);
            $errorMessage = "An error occurred while updating the rooms information.";
        }
    
        // Close the prepared statement
        $updateRoomsQuery->close();
    } else {
        $errorMessage = "Invalid ID provided.";
    }
    
}

// Checking if ID is provided
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);

    // Using prepared statement to fetch the record from the hostel table
    $selectQuery = $conn->prepare("SELECT * FROM hostel WHERE id=?");
    $selectQuery->bind_param("i", $id);

    if ($selectQuery->execute()) {
        // Fetching the record as an associative array
        $result = $selectQuery->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            // Log error Hostel with the provided ID not found
            $errorMessage = "Hostel with ID $id not found.";
            $row = [];
        }
    } else {
        // Log the error
        error_log("Error fetching record: " . $conn->error);
        $errorMessage = "An error occurred while fetching the hostel record.";
        // Initialize $row to prevent undefined index notice
        $row = [];
    }

    // Using prepared statement to fetch the record from the rooms table
    $selectRoomsQuery = $conn->prepare("SELECT * FROM rooms WHERE hostel_id=?");
    $selectRoomsQuery->bind_param("i", $id);

    if ($selectRoomsQuery->execute()) {
        // Fetching the record as an associative array
        $resultRooms = $selectRoomsQuery->get_result();

        if ($resultRooms->num_rows > 0) {
            $rowRooms = $resultRooms->fetch_assoc();
            $numBeds = $rowRooms['num_beds'];
            $numStudents = $rowRooms['num_students'];
        } else {
            $numBeds = "";
            $numStudents = "";
        }
    } else {
        // Log the error
        error_log("Error fetching rooms record: " . $conn->error);
        $errorMessage = "An error occurred while fetching the rooms record.";
        // Initialize $numBeds and $numStudents to prevent undefined index notice
        $numBeds = "";
        $numStudents = "";
    }

    // Close the prepared statements
    $selectQuery->close();
    $selectRoomsQuery->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hostel</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="edit-container">
        <h2>Edit Hostel</h2>
        <?php
        // Display success or error message
        if (!empty($successMessage)) {
            echo '<div class="success-message">' . $successMessage . '</div>';
        } elseif (!empty($errorMessage)) {
            echo '<div class="error-message">' . $errorMessage . '</div>';
        }
        ?>
                <!--here in  php code dynamically generates the value for the action attribute of the form-->
                
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="hidden" name="id" value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>">
    <label for="hostelname">Hostel Name:</label>
    <input type="text" id="hostelname" name="hostelname" value="<?php echo isset($row['hostelname']) ? $row['hostelname'] : ''; ?>" required>

    <label for="price">Hostel Price:</label>
    <input type="text" id="price" name="price" value="<?php echo isset($row['price']) ? $row['price'] : ''; ?>" required>

    <label for="information">Hostel Information:</label>
    <input type="text" id="information" name="information" value="<?php echo isset($row['information']) ? $row['information'] : ''; ?>" required>
    
    <label for="information">Location:</label>
    <input type="text" id="location" name="location" value="<?php echo isset($row['location']) ? $row['location'] : ''; ?>" required>
    
    <label for="num_beds">Number of Beds:</label>
    <input type="text" id="num_beds" name="num_beds" value="<?php echo isset($numBeds) ? $numBeds : ''; ?>" required>

    <label for="num_students">Number of Students:</label>
    <input type="text" id="num_students" name="num_students" value="<?php echo isset($numStudents) ? $numStudents : ''; ?>" required>

    <button type="submit" name="update">Update Hostel</button>
    <a href="admin.php" class="btn-go-back">Go Back</a>
</form>

        
    </div>
</body>
</html>
