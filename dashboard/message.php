<?php
include("../db_conn.php");
$conn = connectToDatabase();

// connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// fetching messages from the database
$fetchMessagesQuery = "SELECT * FROM contact";
$result = $conn->query($fetchMessagesQuery);

// handling delete operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $messageId = $_POST['delete'];

    // performing the delete operation
    $deleteMessageQuery = $conn->prepare("DELETE FROM contact WHERE message = ?");
    $deleteMessageQuery->bind_param("s", $messageId);

    if ($deleteMessageQuery->execute()) {
        echo "Message deleted successfully.";
        // redirecting back to the same page
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error deleting message.";
    }

    
    $deleteMessageQuery->close();
}

// closing  connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

    <div class="message-list">
        <h2>Message List</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Action</th>
                
            </tr>

            <?php
            if ($result->num_rows > 0) { //we are checking if there are rows in the result set ($result). If true there are messages to display.
                while ($row = $result->fetch_assoc()) {   //loop through each row in the result set 

                    echo "<tr>";    // displaying each row as a table row
                    echo "<td>" . $row['fullname'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['message'] . "</td>";
                    echo "<td>";          //here  creating a form for deleting messages
                    echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "' style='display: inline;'>";
                    echo "<input type='hidden' name='delete' value='" . $row['message'] . "'>";
                    echo "<button type='submit' class='btn-delete'>Delete</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No messages found</td></tr>";
            }
            ?>
        </table>
            <a href="admin.php" class="btn-go-back-a">Go Back</a> 
    </div>
</body>
</html>
