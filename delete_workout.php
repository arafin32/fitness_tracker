<?php
session_start();
include 'includes/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if workout ID is passed
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$workout_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Delete workout
$stmt = $conn->prepare("DELETE FROM workouts WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $workout_id, $user_id);

if ($stmt->execute()) {
    header("Location: dashboard.php");
    exit();
} else {
    echo "Error deleting workout: " . $stmt->error;
}
$stmt->close();
?>
