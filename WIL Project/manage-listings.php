<?php session_start(); ?>
<?php
if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in'] || !isset($_SESSION['id'])) {
    echo "<p>You must be signed in to manage listings.</p>";
    echo '<a href="./sign-login.php">Sign in</a>';
    exit();
}

// Database connection
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "wil_project_login"; 
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle add/edit property
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['id']; // Use id from session

    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'add') {
            // Add property
            $title = $_POST['title'];
            $location = $_POST['location'];
            $price = $_POST['price'];
            $propertyType = $_POST['property_type'];
            $description = $_POST['description'];
            $image = '';

            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
                $image = $target_file;
            }

            // Prepare and execute insert statement
            $stmt = $conn->prepare("INSERT INTO properties (user_id, title, location, price, property_type, description, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssss", $userId, $title, $location, $price, $propertyType, $description, $image);
            if ($stmt->execute()) {
                echo "<p>Property added successfully!</p>";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();

        } elseif ($action === 'edit') {
            // Edit property
            $propertyId = $_POST['id'];
            $title = $_POST['title'];
            $location = $_POST['location'];
            $price = $_POST['price'];
            $propertyType = $_POST['property_type'];
            $description = $_POST['description'];

            $image = $_POST['existing_image']; // Keep the existing image if not uploading a new one

            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
                $image = $target_file; // Update the image path
            }

            // Prepare and execute update statement
            $stmt = $conn->prepare("UPDATE properties SET title = ?, location = ?, price = ?, property_type = ?, description = ?, image = ? WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ssissssii", $title, $location, $price, $propertyType, $description, $image, $propertyId, $userId);
            if ($stmt->execute()) {
                echo "<p>Property updated successfully!</p>";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

// Handle delete property
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM properties WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $delete_id, $_SESSION['id']);
    if ($stmt->execute()) {
        echo "<p>Property deleted successfully!</p>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch properties for the logged-in user
$stmt = $conn->prepare("SELECT id, title, location, price, property_type, description, image FROM properties WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();
$properties = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Listings</title>
</head>
<body>
    <h1>Manage Your Listings</h1>
    <form action="manage-listings.php" method="POST" enctype="multipart/form-data">
        <h2>Add Property</h2>
        <input type="hidden" name="action" value="add">
        <input type="text" name="title" placeholder="Title" required>
        <input type="text" name="location" placeholder="Location" required>
        <input type="number" name="price" placeholder="Price" required>
        
        <select name="property_type" required>
            <option value="">Select Property Type</option>
            <option value="house">House</option>
            <option value="apartment">Apartment</option>
            <option value="land">Land</option>
        </select>
        
        <textarea name="description" placeholder="Description" required></textarea>
        <input type="file" name="image" accept="image/*" required>
        <button type="submit">Add Property</button>
    </form>

    <h2>Your Properties</h2>
    <table>
        <tr>
            <th>Title</th>
            <th>Location</th>
            <th>Price</th>
            <th>Type</th>
            <th>Description</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($properties as $property): ?>
            <tr>
                <td><?php echo htmlspecialchars($property['title']); ?></td>
                <td><?php echo htmlspecialchars($property['location']); ?></td>
                <td><?php echo htmlspecialchars($property['price']); ?></td>
                <td><?php echo htmlspecialchars($property['property_type']); ?></td>
                <td><?php echo htmlspecialchars($property['description']); ?></td>
                <td>
                    <?php if ($property['image']): ?>
                        <img src="<?php echo htmlspecialchars($property['image']); ?>" alt="<?php echo htmlspecialchars($property['title']); ?>" style="width: 100px; height: auto;">
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit-property.php?id=<?php echo $property['id']; ?>">Edit</a>
                    <a href="manage-listings.php?delete_id=<?php echo $property['id']; ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
