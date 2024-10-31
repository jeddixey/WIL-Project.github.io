<?php
session_start();
include("connect.php"); // Your database connection file

$location = isset($_GET['location']) ? $_GET['location'] : '';
$min_price = isset($_GET['min_price']) ? $_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? $_GET['max_price'] : 10000000; // max price
$property_type = isset($_GET['property_type']) ? $_GET['property_type'] : '';

// Build the SQL query
$sql = "SELECT * FROM properties WHERE price BETWEEN ? AND ?";
$params = [$min_price, $max_price];

if ($location) {
    $sql .= " AND location = ?";
    $params[] = $location;
}

if ($property_type) {
    $sql .= " AND property_type = ?";
    $params[] = $property_type;
}

// Prepare and bind parameters
$typeString = str_repeat('d', 2); // For price filters

if ($location) {
    $typeString .= 's';
}

if ($property_type) {
    $typeString .= 's';
}

$stmt = $conn->prepare($sql);
$stmt->bind_param($typeString, ...$params);

// Execute the query and check for errors
if (!$stmt->execute()) {
    echo "Error executing query: " . $stmt->error;
    exit;
}

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listings</title>
</head>
<body>
    <h1>Search Results</h1>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($property = $result->fetch_assoc()): ?>
            <div class="property">
                <h2><?php echo htmlspecialchars($property['title']); ?></h2>
                <p>Location: <?php echo htmlspecialchars($property['location']); ?></p>
                <p>Price: $<?php echo htmlspecialchars($property['price']); ?></p>
                <a href="property_details.php?id=<?php echo $property['id']; ?>">View Details</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No properties found matching your criteria.</p>
    <?php endif; ?>
</body>
</html>

<?php
$conn->close();
?>
