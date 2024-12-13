<?php
session_start();
include 'includes/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch workouts for logged-in user
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM workouts WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$workouts = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        /* Add your styling here */
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        h1 { text-align: center; }
        .workout-list { margin-top: 20px; }
        .workout-item { background: #fff; padding: 10px; margin: 10px 0; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .workout-item a { margin-right: 10px; }
    </style>
</head>
<body>
    <h1>Welcome to Your Dashboard</h1>
    <a href="create_workout.php">Create Workout</a>
    <div class="workout-list">
        <?php while ($workout = $workouts->fetch_assoc()): ?>
            <div class="workout-item">
                <strong><?php echo $workout['name']; ?></strong><br>
                <small><?php echo $workout['date']; ?> - <?php echo $workout['duration']; ?> minutes</small><br>
                <p><?php echo $workout['description']; ?></p>
                <a href="edit_workout.php?id=<?php echo $workout['id']; ?>">Edit</a>
                <a href="delete_workout.php?id=<?php echo $workout['id']; ?>">Delete</a>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
