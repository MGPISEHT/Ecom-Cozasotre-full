<?php
// filepath: c:\xampp\htdocs\Assignment\cozastore-master\admin\pages\updateProduct.php
include '../configs/DBconnect.php'; // Database connection

if (isset($_POST['update_product'])) {
    $product_id = $_POST['product_id'];
    $name = htmlspecialchars($_POST['name']); // Sanitize input
    $description = htmlspecialchars($_POST['description']); // Sanitize input
    $price = $_POST['price'];
    $stock = $_POST['stock_quantity'];
    $category_id = $_POST['category_id'];
    $current_image = $_POST['current_image'];
    $image = $current_image; // Use current image if no new image is uploaded

    // Validate required fields
    if (empty($name) || empty($description) || empty($price) || empty($stock) || empty($category_id)) {
        header("Location: editProduct.php?id=$product_id&error=All fields are required.");
        exit();
    }

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($fileType, $allowedTypes)) {
            header("Location: editProduct.php?id=$product_id&error=Invalid file type. Allowed types: jpg, jpeg, png, gif.");
            exit();
        }

        $maxFileSize = 2 * 1024 * 1024; // 2MB
        if ($_FILES['image']['size'] > $maxFileSize) {
            header("Location: editProduct.php?id=$product_id&error=File size exceeds 2MB.");
            exit();
        }

        $imageName = uniqid() . '_' . basename($_FILES['image']['name']);
        $imagePath = $uploadDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            // Delete the old image if it exists and is not a default image
            if ($current_image && !strpos($current_image, 'default.jpg')) {
                if (file_exists($current_image)) {
                    unlink($current_image);
                }
            }
            $image = $imagePath;
        } else {
            header("Location: editProduct.php?id=$product_id&error=Failed to upload image.");
            exit();
        }
    } else {
        // If no new image is uploaded, ensure $image is set to the current image
        $image = $current_image;
    }

    try {
        $stmt = $conn->prepare("UPDATE products SET name = :name, description = :description, price = :price, category_id = :category_id, stock_quantity= :stock_quantity , image = :image WHERE id = :id");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':stock_quantity', $stock, PDO::PARAM_STR);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':image', $image, PDO::PARAM_STR);
        $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);

        $stmt->execute();

        header("Location: ../viewProducts.php?message=Product updated successfully.");
        exit();
    } catch (PDOException $e) {
        header("Location: editProduct.php?id=$product_id&error=" . $e->getMessage());
        exit();
    }
}
?>