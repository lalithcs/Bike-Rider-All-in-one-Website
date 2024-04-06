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
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';

    // Sanitize inputs to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);
    $email = mysqli_real_escape_string($conn, $email);
    $phone = mysqli_real_escape_string($conn, $phone);

    // Hash the entered password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query to insert user into the database
    $sql = "INSERT INTO users (username, password, email, phone) VALUES ('$username', '$hashed_password', '$email', '$phone')";
    if ($conn->query($sql) === TRUE) {
        echo "Registration successful";
        header('Location: index.html');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
