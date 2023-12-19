<?php
session_start();

// Unset all  the session variables
$_SESSION = array();

// Destroying the session
session_destroy();

// Redirecting to the login page after logout
header("Location: ../index.php");
exit;
?>
