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

// Start session
session_start();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']); // Trim whitespace
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashed_password);
        $stmt->fetch();
        
        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Set session variables
            $_SESSION['user_logged_in'] = true;
            $_SESSION['id'] = $id; // Change to 'id' for consistency
            $_SESSION['user_name'] = $name; // Store user's name in session

            header("Location: home.php"); // Redirect to home page
            exit();
        } else {
            echo "<p style='color:red;'>Invalid password.</p>"; // User-friendly error
        }
    } else {
        echo "<p style='color:red;'>No user found with this email.</p>"; // User-friendly error
    }

    $stmt->close();
}
$conn->close();
