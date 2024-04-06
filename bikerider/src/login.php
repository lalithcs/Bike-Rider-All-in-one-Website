<?php
// Replace these with your actual database credentials
$servername = "localhost";
$username = "root";
$password = "lalith1.";
$dbname = "bikerider";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['user'] ?? '';
    $password = $_POST['pass'] ?? '';

    // Sanitize inputs to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $username);

    // Query to fetch hashed password from the database based on the username
    $sql = "SELECT password FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verify entered password against hashed password
        if (password_verify($password, $hashed_password)) {
            // If credentials match, redirect to the "community" page
            header('Location: community.html');
            exit();
        } else {
            echo "Invalid username or password. Please try again.";
        }
    } else {
        echo "Invalid username or password. Please try again.";
    }
}

$conn->close();
?>
