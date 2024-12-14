<?php
session_start();
include 'includes/db_connect.php';

// Check if the user is already logged in. If logged in, redirect to the dashboard.
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } else {
        // Password hashing for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute the insert query
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            // After successful signup, redirect to login page
            header("Location: login.php");
            exit();
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .signup-form { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); width: 300px; }
        .signup-form input { width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .signup-form button { width: 100%; padding: 10px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .error-message { color: red; font-size: 14px; margin-bottom: 10px; }
        .signup-form p { font-size: 14px; text-align: center; }
        .signup-form a { color: #007bff; text-decoration: none; }
        .signup-form a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <form class="signup-form" method="POST" action="">
        <h2>Sign Up</h2>
        
        <?php
        // Display error message if passwords don't match
        if (isset($error_message)) {
            echo "<p class='error-message'>$error_message</p>";
        }
        ?>
        
        <input type="text" name="name" placeholder="Enter your name" required>
        <input type="email" name="email" placeholder="Enter your email" required>
        <input type="password" name="password" placeholder="Enter your password" required>
        <input type="password" name="confirm_password" placeholder="Confirm your password" required>
        <button type="submit">Sign Up</button>
        
        <p>Already have an account? <a href="login.php">Log In</a></p>
    </form>
</body>
</html>
