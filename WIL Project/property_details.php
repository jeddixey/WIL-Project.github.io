<?php
session_start();
include("connect.php");

if (isset($_GET['id'])) {
    $property_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM properties WHERE id = ?");
    $stmt->bind_param("i", $property_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $property = $result->fetch_assoc();
} else {
    echo "Property not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($property['title']); ?></title>
</head>
<body>
    <h1><?php echo htmlspecialchars($property['title']); ?></h1>
    <img src="<?php echo htmlspecialchars($property['image']); ?>" alt="<?php echo htmlspecialchars($property['title']); ?>">
    <p>Location: <?php echo htmlspecialchars($property['location']); ?></p>
    <p>Price: $<?php echo htmlspecialchars($property['price']); ?></p>
    <p>Description: <?php echo nl2br(htmlspecialchars($property['description'])); ?></p>
    <p>Amenities: <?php echo nl2br(htmlspecialchars($property['amenities'])); ?></p>
    <p>Contact: <?php echo htmlspecialchars($property['contact_info']); ?></p>
</body>
</html>

<?php
$conn->close();
?>
