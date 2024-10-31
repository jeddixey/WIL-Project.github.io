<?php
session_start();
session_destroy(); // Destroy all session data
header("Location: sign-login.php"); // Redirect to the login page
exit();
?>
