<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apple Estate</title>
    <link rel="stylesheet" href="./Styles/style.css">
    <script src="https://kit.fontawesome.com/ffc82079a3.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <div class="logo"><img src="./Images/logo.png" alt="">RealHome</div>
        <nav>
            <ul class="left">
                <li><a href="./home.php">Home</a></li>
                <li><a href="./listings.php">Listings</a></li>
                <li><a href="./about-us.html">About</a></li>
                <li><a href="./contact.html">Contact</a></li>
                <div class="nav-right">
                    <?php if (isset($_SESSION['user_name'])): ?>
                        <li>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</li>
                        <li><a href="./logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="./sign-login.php">Sign in</a></li>
                    <?php endif; ?>
                </div>
            </ul>
        </nav>
    </header>
    
    <main>
        <div class="container-wallpaper">
            <img class="wallpaper" src="./Images/Wallpaper.jpg" alt="img">
            <div class="middle-heading">
                <section>
                    <h2 class="heading-search">Find Your <br> Dream Home!</h2>
                    <div class="row-filter">
                        <form id="search-form" action="search.php" method="GET"> 
                            <div class="location-filter" id="location-filter">
                                <select name="location" required>
                                    <option value="">Select Location</option>
                                    <option value="Gauteng">Gauteng</option>
                                    <option value="Cape Town">Cape Town</option>
                                    <option value="Durban">Durban</option>
                                </select>
                            </div>
                            <input type="number" name="min_price" placeholder="Min Price" min="0">
                            <input type="number" name="max_price" placeholder="Max Price">
                            <select name="property_type" required>
                                <option value="">Property Type</option>
                                <option value="house">House</option>
                                <option value="apartment">Apartment</option>
                                <option value="land">Land</option>
                            </select>
                            <button type="submit"><i class="fa-brands fa-searchengin fa-2xl"></i></button>
                        </form>
                    </div>
                </section>
            </div>
        </div>

        <div class="blank-space"></div>

        <section>
            <h2 class="section02">Check out how we can assist.</h2>
        </section>
        
        <div class="box-container">
            <div class="box">
                <img src="./Images/iconhouse.png" alt="Image 1">
                <h3>Buying</h3>
                <p>Determine the worth of any South African property.</p>
            </div>
            <div class="box">
                <img src="./Images/iconsell.png" alt="Image 2">
                <h3>Selling</h3>
                <p>Find out how much your property is worth.</p>
            </div>
            <div class="box">
                <img src="./Images/iconrenting.png" alt="Image 3">
                <h3>Renting</h3>
                <p>Locate reputable real estate brokers in your area.</p>
            </div>
        </div>

        <section class="listings" id="listings">
            <h2>Property Listings</h2>
            <div class="listing">
                <a href="./listings.php">
                    <img src="house1.jpg" alt="House 1">
                    <h3>Beautiful Family Home</h3>
                    <p>$500,000</p>
                </a>
            </div>
            <div class="listing">
                <a href="./listings.php">
                    <img src="house2.jpg" alt="House 2">
                    <h3>Modern Apartment</h3>
                    <p>$350,000</p>
                </a>
            </div>
        </section>
    </main>
    
    <script src="script.js"></script>
    <footer>
        <p>&copy; 2024 Real Estate. All rights reserved.</p>
    </footer>
</body>
</html>
