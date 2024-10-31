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

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch property details associated with the logged-in user
    $stmt = $conn->prepare("SELECT title, location, price, description, image FROM properties WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $_SESSION['user_id']); // Assuming user_id is stored in session
    $stmt->execute();
    $stmt->bind_result($title, $location, $price, $description, $image);
    $stmt->fetch();
    $stmt->close();

    if (!$title) {
        // If no property found, redirect to manage listings
        header("Location: manage-listings.php");
        exit();
    }
} else {
    header("Location: manage-listings.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Property</title>
</head>
<body>
    <h1>Edit Property</h1>
    <form action="manage-listings.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
        <input type="text" name="location" value="<?php echo htmlspecialchars($location); ?>" required>
        <input type="number" name="price" value="<?php echo htmlspecialchars($price); ?>" required>
        <textarea name="description" required><?php echo htmlspecialchars($description); ?></textarea>
        <input type="file" name="image" accept="image/*">
        <?php if ($image): ?>
            <p>Current Image:</p>
            <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($title); ?>" style="width: 100px; height: auto;">
            <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($image); ?>">
        <?php endif; ?>
        <button type="submit">Update Property</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>
