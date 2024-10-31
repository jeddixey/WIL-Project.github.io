<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Details with Leaflet</title>
    <link rel="stylesheet" href="./Styles/listing-style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        
        #map {
            height: 400px; 
            width: 100%; 
            margin-top: 20px;
        }
    </style>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>
<body>
    <?php session_start(); ?>
    <header>
        <div class="logo"><img src="./Images/logo.png" alt="">RealHome</div>
        <nav>
            <ul class="left">
                <li><a href="./home.php">Home</a></li>
                <li><a href="./listings.php">Listings</a></li>
                <li><a href="./about-us.html">About</a></li>
                <li><a href="./contact.html">Contact</a></li>
                <div class="nav-right">
                    <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in']): ?>
                        <li><a href="./manage-listings.php">Manage Listings</a></li>
                        <li><a href="./logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="./sign-login.php">Sign in</a></li>
                    <?php endif; ?>
                </div>
            </ul>
        </nav>
    </header>

    <div class="property-detail">
        <?php
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

        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            // Fetch property details
            $stmt = $conn->prepare("SELECT title, location, price, description, amenities, image FROM properties WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($title, $location, $price, $description, $amenities, $image);
            $stmt->fetch();
            $stmt->close();

            // Display property details
            echo '<h1>' . htmlspecialchars($title) . '</h1>';
            echo '<p><strong>Location:</strong> ' . htmlspecialchars($location) . '</p>';
            echo '<p><strong>Price:</strong> R' . htmlspecialchars($price) . '</p>';
            echo '<p><strong>Description:</strong> ' . htmlspecialchars($description) . '</p>';
            echo '<p><strong>Amenities:</strong> ' . htmlspecialchars($amenities) . '</p>';

            if ($image) {
                echo '<img src="' . htmlspecialchars($image) . '" alt="' . htmlspecialchars($title) . '" style="width: 100%; height: auto;">';
            } else {
                echo '<i class="fa-solid fa-house fa-3x"></i>'; // Placeholder icon
            }

            // Display map using Leaflet
            echo '<div id="map"></div>';
            echo '<script>
                const map = L.map("map").setView([-34.0, 18.0], 15); 
                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    maxZoom: 19,
                    attribution: "© OpenStreetMap"
                }).addTo(map);
                L.marker([-34.0, 18.0]).addTo(map)
                    .bindPopup("Your Property Location")
                    .openPopup();
            </script>';

            // Contact information section 
            echo '<h2>Contact Information</h2>';
            echo '<p>If you are interested in this property, please contact us at:</p>';
            echo '<p><strong>Email:</strong> info@realhome.com</p>';
            echo '<p><strong>Phone:</strong> +123456789</p>';
        } else {
            echo '<p>Property not found.</p>';
        }

        $conn->close();
        ?>
    </div>

    <footer>
        <p>&copy; 2024 Real Estate. All rights reserved.</p>
    </footer>
</body>
</html>
