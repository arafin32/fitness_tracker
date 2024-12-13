<?php
session_start();
include 'includes/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the workout ID is passed
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$workout_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Fetch workout details
$stmt = $conn->prepare("SELECT * FROM workouts WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $workout_id, $user_id);
$stmt->execute();
$workout = $stmt->get_result()->fetch_assoc();

if (!$workout) {
    echo "Workout not found!";
    exit();
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $duration = $_POST['duration'];
    $date = $_POST['date'];

    // Update workout details
    $stmt = $conn->prepare("UPDATE workouts SET name = ?, description = ?, duration = ?, date = ? WHERE id = ?");
    $stmt->bind_param("ssisi", $name, $description, $duration, $date, $workout_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error updating workout: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Workout</title>
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
        <h2>Edit Workout</h2>
        <input type="text" name="name" value="<?php echo $workout['name']; ?>" required>
        <textarea name="description" required><?php echo $workout['description']; ?></textarea>
        <input type="number" name="duration" value="<?php echo $workout['duration']; ?>" required>
        <input type="date" name="date" value="<?php echo $workout['date']; ?>" required>
        <button type="submit">Update Workout</button>
    </form>
</body>
</html>
