<?php
session_start();
include '../configs/DBconnect.php'; // Ensure database connection

if (!isset($conn)) {
    die("Database connection not found!");
}

if (isset($_GET['id'])) {
    $productId = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if ($productId) {
        try {
            // Check if product exists before deleting
            $checkSql = "SELECT * FROM products WHERE id = :id";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bindParam(':id', $productId, PDO::PARAM_INT);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                // Delete the product
                $sql = "DELETE FROM products WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
                $stmt->execute();

                $_SESSION['message'] = "Product deleted successfully!";
            } else {
                $_SESSION['error'] = "Product not found!";
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "Invalid product ID.";
    }

    header("Location: ../viewProducts.php");
    exit();
} else {
    $_SESSION['error'] = "No product ID provided.";
    header("Location: ../viewProducts.php");
    exit();
}
?>
