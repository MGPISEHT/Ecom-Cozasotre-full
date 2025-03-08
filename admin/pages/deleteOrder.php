<?php
// Include the database connection file
include("configs/DBconnect.php");

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Prepare the SQL query to delete the order
        $sql = "DELETE FROM orders WHERE id = :id";
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Redirect back to the orders page with a success message
        header("Location: viewOrders.php?message=Order deleted successfully!");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // If 'id' is not set, redirect back to the orders page
    header("Location: viewOrders.php");
    exit();
}
?>