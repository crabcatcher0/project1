<?php
//function to connect the database 
function connectToDatabase() {
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "signup";
    
    //creating mysqli connection
    $conn = new mysqli($host, $username, $password, $database);
   //checking connection error
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //returning the databse conn object 

    return $conn;
}

?>
