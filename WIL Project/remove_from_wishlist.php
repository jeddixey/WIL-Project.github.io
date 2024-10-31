<?php
session_start();

if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in']) {
    header("Location: sign-login.php");
    exit();
}

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "wil_project_login"; 

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['property_id'])) {
    $property_id = $_POST['property_id'];
    $user_id = $_SESSION['id'];

    // Prepare the delete statement
    $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND property_id = ?");
    $stmt->bind_param("ii", $user_id, $property_id);

    if ($stmt->execute()) {
        echo "Property removed from wishlist!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
header("Location: listings.php"); // Redirect back to wishlist page
?>
