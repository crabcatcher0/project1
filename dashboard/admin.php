<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="container">
    <?php
    // database connection calling the function connectToDatabase
    include("../db_conn.php");
    $conn = connectToDatabase();
    
    $messages = []; //array for messages
    
    // check if the form is submitted or not
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addhostel'])) {
        $hostelname = isset($_POST['hostelname']) ? $_POST['hostelname'] : "";
        $price = isset($_POST['price']) ? $_POST['price'] : "";
        $information = isset($_POST['information']) ? $_POST['information'] : "";
        $location = isset($_POST['location']) ? $_POST['location'] : ""; 
        $num_beds = isset($_POST['num_beds']) ? $_POST['num_beds'] : "";
        $num_students = isset($_POST['num_students']) ? $_POST['num_students'] : "";
        $type = isset($_POST['type']) ? $_POST['type'] : "";
        
        // if image is provided it retrieves the image name, temporary name, and constructs the destination folder path.
        if (isset($_FILES['image'])) {
            $image_name = $_FILES['image']['name'];
            $image_tmp_name = $_FILES['image']['tmp_name'];
            $image_folder = 'upload/' . $image_name;
            
            // validate form fields if empty then show message
            if (empty($hostelname) || empty($price) || empty($information) || empty($num_beds) || empty($num_students) || empty($type)) {
                $messages[] = 'Please fill out all fields.';
            } else {
                //if not empty the insert data into the table named hostel
                $stmt = $conn->prepare("INSERT INTO hostel (hostelname, price, information, location, image, type) VALUES (?, ?, ?, ?, ?, ?)");
                // check if the statement was created successfully
                if ($stmt) {
                    // binding parameters
                    $stmt->bind_param("ssssss", $hostelname, $price, $information, $location, $image_name, $type);
                    // execute the statement
                    if ($stmt->execute()) {
                        // Moving uploaded file 
                        move_uploaded_file($image_tmp_name, $image_folder);
                    
                        // Get the ID of the last inserted hostel
                        $lastHostelId = $stmt->insert_id;
                    
                        // Insert data into the rooms table
                        $stmtRooms = $conn->prepare("INSERT INTO rooms (hostel_id, hostelname, num_beds, num_students) VALUES (?, ?, ?, ?)");
                        if ($stmtRooms) {
                            $stmtRooms->bind_param("issi", $lastHostelId, $hostelname, $num_beds, $num_students);
                            $stmtRooms->execute();
                            $stmtRooms->close();
                        } else {
                            $messages[] = 'Failed to prepare statement for rooms.';
                        }
                    
                        $messages[] = 'New hostel added';
                        header('Location: ' . $_SERVER['PHP_SELF']);
                    } else {
                        $messages[] = 'Failed to add new hostel: ' . $stmt->error;
                    }
                    
                    // close the statement
                    $stmt->close();
                } else {
                    $messages[] = 'Failed to prepare statement.';
                }
            }     
        } else {
            $messages[] = 'Please provide an image.';
        }
    }

    // delete hostel if delete is clicked
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $deleteQuery = "DELETE FROM hostel WHERE id=$id"; //checks the hostel id from hostel database
        if (mysqli_query($conn, $deleteQuery)) {
            header('location: admin.php');
        } else {
            $messages[] = "Error deleting record: " . mysqli_error($conn);
        }
    }
    ?>
    <div class="admin-form-container">
        
        <?php foreach ($messages as $msg) : ?>
            <p><?php echo htmlspecialchars($msg); ?></p>
        <?php endforeach; ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <h3>Add new Hostel</h3>
            <input type="text" placeholder="Enter hostel name" name="hostelname" class="box" required>
            <input type="text" placeholder="Enter hostel price" name="price" class="box" required>
            <input type="text" placeholder="Enter information" name="information" class="box" required>
            <input type="text" placeholder="Enter location" name="location" class="box" required>
            <input type="text" placeholder="Enter hostel type (e.g., girls/boys)" name="type" class="box" required>
            <input type="number" placeholder="Available beds in one room" name="num_beds" class="box" required>
            <input type="number" placeholder="Number of students in one room" name="num_students" class="box" required>
            <input type="file" accept="image/png, image/jpg, image/jpeg" name="image" class="box" required>
            <input type="submit" class="btn" name="addhostel" value="Add hostel">
        </form>
    </div>

    <div class="additional-buttons">
        <button class="btn" onclick="location.href='users.php'">Users</button>
        <button class="btn" onclick="location.href='message.php'">Messages</button>
    </div>

    <?php
$select = mysqli_query($conn, "SELECT * FROM hostel"); // selecting from the hostel table

?>

<div class="hostel-display">
    <table class="hostel-display-table">
        <thead>
            <tr>
                <th>Hostel image</th>
                <th>Hostel name</th>
                <th>Hostel price</th>
                <th>Hostel information</th>
                <th>Location</th>
                <th>Available Beds</th>
                <th>Students in Room</th>
                <th>Action</th>
            </tr>
        </thead>
        <?php while ($row = mysqli_fetch_array($select)) : ?>
            <tr>
                <td><img src="upload/<?php echo htmlspecialchars($row['image']); ?>" height="100" alt=""></td>
                <td><?php echo htmlspecialchars($row['hostelname']); ?></td>
                <td><?php echo htmlspecialchars($row['price']); ?></td>
                
                <td><?php echo htmlspecialchars($row['information']); ?></td>
                <td><?php echo htmlspecialchars($row['location']); ?></td>

                
                <?php
                // Fetching data from the rooms table based on the hostel_id
                $hostelId = $row['id'];
                $roomsQuery = mysqli_query($conn, "SELECT * FROM rooms WHERE hostel_id = $hostelId");
                
                if ($roomsQuery) {
                    $roomsData = mysqli_fetch_array($roomsQuery);
                    $numBeds = $roomsData['num_beds'];
                    $numStudents = $roomsData['num_students'];
                } else {
                    $numBeds = "N/A";
                    $numStudents = "N/A";
                }
                ?>

                <td><?php echo $numBeds; ?></td>
                <td><?php echo $numStudents; ?></td>
                <td>
                    <a href="edit.php?edit=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                    <!-- deelete button with an onclick event calling the confirmDelete function -->
                    <a href="#" class="btn-delete" onclick="confirmDelete(<?php echo $row['id']; ?>)">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

    </div>
    <script>
    // here creating function to confirm hostel deletion which select a id and delete them
    function confirmDelete(id) {
        var confirmation = confirm("Are you sure you want to delete this hostel?");
        if (confirmation) {
            window.location.href = 'admin.php?delete=' + id;
        }
    }
</script>
</body>
</html>
