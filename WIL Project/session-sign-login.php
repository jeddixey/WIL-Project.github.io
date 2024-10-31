<?php
session_start();

$_SESSION['user_logged_in'] = true;
// Redirect to home or listings page
header('Location: home.php');
exit;
?>
