<?php
session_start();
include '../configs/DBconnect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['message'] = "category deleted successfully!";
            header("Location: viewProducts.php");
            exit();
        } else {
            $_SESSION['error'] = "Failed to delete category!";
            header("Location: viewProducts.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: viewProducts.php");
        exit();
    }
} else {
    $_SESSION['error'] = "No ID provided!";
    header("Location: viewProducts.php");
    exit();
}
?>
