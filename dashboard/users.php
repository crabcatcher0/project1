<?php
include("../db_conn.php");
$conn = connectToDatabase();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// fetching user and booked hostel
$fetchUsersQuery = "SELECT 
                        user.name AS user_name, 
                        user.last_name, 
                        user.address, 
                        user.contact_number, 
                        user.email, 
                        GROUP_CONCAT(hostel.hostelname) AS booked_hostels
                    FROM user
                    LEFT JOIN booked_hostel ON user.email = booked_hostel.email
                    LEFT JOIN hostel ON booked_hostel.hostel_id = hostel.id
                    GROUP BY user.email";
$result = $conn->query($fetchUsersQuery);

// connection close
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

    <div class="user-list">
        <h2>User List</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Contact</th>
                <th>Booked Hostels</th>
                <th>Action</th>
            </tr>

            <?php
            if ($result->num_rows > 0) { 
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['user_name'] . "</td>";
                    echo "<td>" . $row['last_name'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['address'] . "</td>";
                    echo "<td>" . $row['contact_number'] . "</td>";
                    echo "<td>";
                    $bookedHostels = explode(',', $row['booked_hostels']);
                    echo "<ul>";
                    foreach ($bookedHostels as $hostel) {
                        echo "<li>" . $hostel . "</li>";
                    }
                    echo "</ul>";
                    echo "</td>";
                    echo "<td><button onclick=\"deleteUser('" . $row['email'] . "')\">Delete</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No users found</td></tr>";
            }
            ?>
        </table>
        
        <a href="admin.php" class="btn-go-back">Go Back</a>
    </div>

    <script>   
        function deleteUser(email) {
            var result = confirm("Are you sure you want to delete this user?");
            if (result) {
                window.location.href = 'delete_user.php?email=' + email;
            }
        }
    </script>
</body>
</html>
