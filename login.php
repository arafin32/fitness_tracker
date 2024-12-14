<?php
session_start();
include 'includes/db_connect.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input
    if (!empty($email) && !empty($password)) {
        // Prepare SQL query to fetch user by email
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid password";
            }
        } else {
            $error = "No user found with that email";
        }

        $stmt->close();
    } else {
        $error = "Please enter both email and password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .form-container { width: 300px; margin: 0 auto; background: #fff; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); border-radius: 5px; }
        h1 { text-align: center; }
        input { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc; }
        button { width: 100%; padding: 10px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .error { color: red; text-align: center; }
        .signup-link { text-align: center; margin-top: 10px; }
        .signup-link a { text-decoration: none; color: #007bff; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Login</h1>

        <!-- Display error if any -->
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <!-- Sign Up Link -->
        <div class="signup-link">
            <p>Don't have an account? <a href="index.php">Sign up here</a></p>
        </div>
    </div>
</body>
</html>
