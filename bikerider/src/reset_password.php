<?php
// Replace 'config.php' with the actual file path to your database connection and configuration file
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
    // Include database connection and configuration

    // Extract token and new password from the form
    $token = $_POST['token'];
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate passwords match
    if ($new_password !== $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Check if the token exists in the database
    $sql = "SELECT * FROM users WHERE reset_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token is valid, update the password and clear the token
        $row = $result->fetch_assoc();
        $user_id = $row['id']; // Assuming 'id' is the primary key of your 'users' table

        $update_sql = "UPDATE users SET password = ?, reset_token = NULL WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param('si', $hashed_password, $user_id);
        if ($update_stmt->execute()) {
            echo "Password reset successfully.";
        } else {
            echo "Failed to reset password.";
        }
    } else {
        echo "Invalid or expired token.";
    }

    $conn->close(); // Close the database connection
}
?>
