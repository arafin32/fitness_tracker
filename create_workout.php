<?php
session_start();
include 'includes/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $duration = $_POST['duration'];
    $date = $_POST['date'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO workouts (user_id, name, description, duration, date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issis", $user_id, $name, $description, $duration, $date);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Workout</title>
    <style>
        /* Add your styling here */
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .workout-form { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); width: 300px; }
        .workout-form input, .workout-form textarea { width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .workout-form button { width: 100%; padding: 10px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <form class="workout-form" method="POST" action="">
        <h2>Create Workout</h2>
        <input type="text" name="name" placeholder="Workout Name" required>
        <textarea name="description" placeholder="Workout Description" required></textarea>
        <input type="number" name="duration" placeholder="Duration (minutes)" required>
        <input type="date" name="date" required>
        <button type="submit">Create Workout</button>
    </form>
</body>
</html>
