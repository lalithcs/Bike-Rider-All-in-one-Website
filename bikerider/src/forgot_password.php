<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer autoload file
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = mysqli_connect("localhost", "root", "lalith1.", "bikerider");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $email = $_POST['email'];

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address";
        exit;
    }

    // Check if the email exists in the database
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && $result->num_rows > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(32));

        // Store the token in the database
        $sql = "UPDATE users SET reset_token='$token' WHERE email='$email'";
        if ($conn->query($sql) === TRUE) {
            // Send password reset email using PHPMailer
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'lalithchalla111@gmail.com'; // Your Gmail address
                $mail->Password = 'bexfiyziykcejbbe'; // Your Gmail password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('ridesavers@gmail.com', 'Bike Riders');
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Password Reset Request';
                $mail->Body = "Hello,<br><br>You have requested to reset your password. Please click the following link to reset your password:<br><br><a href='http://127.0.0.1:8000/reset_password.html?token=$token'>Reset Password</a><br><br>If you did not request this, please ignore this email.";
                $mail->AltBody = 'Hello, You have requested to reset your password. Please visit the following link to reset your password: http://127.0.0.1:8000/reset_password.html?token=' . $token . ' If you did not request this, please ignore this email.';

                $mail->send();
                echo "Password reset instructions sent to your email address.";
            } catch (Exception $e) {
                echo "Failed to send password reset instructions. Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "Email address not found.";
    }

    $conn->close();
}
?>
