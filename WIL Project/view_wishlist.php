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

// Fetch user's wishlist
$user_id = $_SESSION['id'];
$stmt = $conn->prepare("SELECT w.property_id, p.title FROM wishlist w JOIN properties p ON w.property_id = p.id WHERE w.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<h1>Your Wishlist</h1>";
    echo "<table>";
    echo "<tr><th>Property Title</th><th>Action</th></tr>";

    while ($row = $result->fetch_assoc()) {
        $property_id = $row['property_id'];
        $property_title = $row['title']; // Updated to use the correct column name

        echo "<tr>";
        echo "<td>" . htmlspecialchars($property_title) . "</td>";
        echo "<td>";
        // Form to remove the property from the wishlist
        echo "<form method='POST' action='remove_from_wishlist.php' style='display:inline;'>";
        echo "<input type='hidden' name='property_id' value='" . htmlspecialchars($property_id) . "'>";
        echo "<button type='submit'>Remove</button>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<p>Your wishlist is empty.</p>";
}

$stmt->close();
$conn->close();
?>
