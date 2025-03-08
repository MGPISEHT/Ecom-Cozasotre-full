<?php
include("../configs/DBconnect.php"); // Include database connection
include 'function.php'; // Include functions
session_start(); // Start session for managing login

if (!isset($_GET['id'])) {
    header("Location: deleteUser.php"); // Redirect if no user id is provided
    exit();
}

$id = $_GET['id'];

// Delete the user from the database
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
if ($stmt->execute([$id])) {
    header("Location: manageUsers.php"); // Redirect to manage users page after deletion
    exit();
} else {
    // Handle error in case of failure
    echo "Error: Unable to delete user.";
    exit();
}
