<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apple Estate</title>
    <link rel="stylesheet" href="./Styles/listing-style.css">
    <script src="https://kit.fontawesome.com/ffc82079a3.js" crossorigin="anonymous"></script>
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
                        <li><a href="./view_wishlist.php">Wishlist</a></li>
                    <?php else: ?>
                        <li><a href="./sign-login.php">Sign in</a></li>
                    <?php endif; ?>
                </div>
                <div class="nav-right">
                <li>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</li>
                <li><a href="./logout.php">Logout</a></li>
                </div>
            </ul>
        </nav>
    </header>
    
    <div class="listing-form">
        <h1>Property Listings</h1>
        <form action="search.php" method="GET">
            <select name="location">
                <option value="">Select Location</option>
                <option value="Gauteng">Gauteng</option>
                <option value="Cape Town">Cape Town</option>
                <option value="Durban">Durban</option>
            </select>
            <input type="number" name="min_price" placeholder="Min Price">
            <input type="number" name="max_price" placeholder="Max Price">
            <select name="property_type">
                <option value="">Property Type</option>
                <option value="house">House</option>
                <option value="apartment">Apartment</option>
                <option value="land">Land</option>
            </select>
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="blank-space"></div>

    <section class="property-listings">
        <h2>Available Properties</h2>
        <div class="property-grid">
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

            // Fetch properties
            $stmt = $conn->prepare("SELECT id, title, location, price, description, image FROM properties");
            $stmt->execute();
            $result = $stmt->get_result();

            // Display properties
            while ($property = $result->fetch_assoc()) {
                echo '<div class="property-card">';
                if ($property['image']) {
                    echo '<img src="' . htmlspecialchars($property['image']) . '" alt="' . htmlspecialchars($property['title']) . '">';
                } else {
                    echo '<i class="fa-solid fa-house"></i>';
                }
                echo '<h3>' . htmlspecialchars($property['title']) . '</h3>';
                echo '<p>Location: ' . htmlspecialchars($property['location']) . '</p>';
                echo '<p>Price: R' . htmlspecialchars($property['price']) . '</p>';
                echo '<p>' . htmlspecialchars($property['description']) . '</p>';
                
                echo '<a href="property-detail.php?id=' . $property['id'] . '" class="view-link">View Details</a>';

                // Wishlist button
                if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in']) {
                    echo '<form action="add_to_wishlist.php" method="POST" class="wishlist-form">';
                    echo '<input type="hidden" name="property_id" value="' . $property['id'] . '">';
                    echo '<button type="submit" class="wishlist-button">Add to Wishlist</button>';
                    echo '</form>';
                }

                echo '</div>';
            }

            $stmt->close();
            $conn->close();
            ?>
        </div>
    </section>

    <script src="script.js"></script>

    <footer>
        <p>&copy; 2024 Real Estate. All rights reserved.</p>
    </footer>
</body>
</html>
