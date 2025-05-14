<?php
// Include the database connection file
include("./configs/DBconnect.php");

// add category to the database
if (isset($_POST["add-categories"])) {
    // Retrieve form data
    $categoryTitle = $_POST["categoryTitle"];
    $metaKeyword = $_POST["metaKeyword"];
    $metaTitle = $_POST["metaTitle"];
    $metaDescription = $_POST["metaDescription"];
    $categoryStatus = isset($_POST["categoryStatus"]) ? 1 : 0; // Check if status is active
    try {
        // Prepare the SQL query
        $sql = "INSERT INTO categories (title, meta_keyword, meta_title, meta_description, status) 
                VALUES (:title, :meta_keyword, :meta_title, :meta_description, :status)";

        // Prepare and execute the statement
        $stmt = $conn->prepare($sql); // Use $conn instead of $pdo
        $stmt->execute([
            ':title' => $categoryTitle,
            ':meta_keyword' => $metaKeyword,
            ':meta_title' => $metaTitle,
            ':meta_description' => $metaDescription,
            ':status' => $categoryStatus
        ]);
?>
        <script type="text/javascript">
            alert("Category added successfully.");
            window.location.href = "viewCategories.php";
        </script>
    <?php

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    ?>
        <script type="text/javascript">
            alert("Error: <?php echo $e->getMessage(); ?>");
            window.location.href = "addCategories.php";
        </script>
<?php
    }
}

// function get categories
function getAllCategories()
{
    global $conn;
    try {
        // Prepare the SQL query
        $sql = "SELECT id, title, meta_description, image FROM categories"; // Ensure these columns exist
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo '<tr><td colspan="5" class="text-center text-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
    }
}


// delete category
function deleteCategory()
{
    global $conn;
    // Check if 'id' is set and is a valid integer
    if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
        $id = $_GET['id'];

        try {
            // Prepare the SQL query to delete the category
            $sql = "DELETE FROM categories WHERE id = :id";
            $stmt = $conn->prepare($sql);

            // Bind the parameter
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Execute the query
            $stmt->execute();

            // Check if a row was actually deleted
            if ($stmt->rowCount() > 0) {
                // Redirect back to the categories page with a success message
                header("Location: viewCategories.php?message=Category deleted successfully!");
                exit();
            } else {
                // No rows were deleted (e.g., invalid ID)
                header("Location: viewCategories.php?error=Category not found or already deleted.");
                exit();
            }
        } catch (PDOException $e) {
            // Log the error and redirect with an error message
            error_log("Error deleting category: " . $e->getMessage());
            header("Location: viewCategories.php?error=An error occurred while deleting the category.");
            exit();
        }
    } else {
        // If 'id' is not set or invalid, redirect back to the categories page
        header("Location: viewCategories.php?error=Invalid category ID.");
        exit();
    }
}


// add products
function addProducts($conn, $name, $description, $price, $stock, $categoryId, $image)
{
    try {
        $sql = "INSERT INTO products (name, description, price, stock_quantity, category_id, image) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $description, $price, $stock, $categoryId, $image]);
        return true; 
    } catch (PDOException $e) {
        error_log("Error adding product: " . $e->getMessage()); 
        return false; 
    }
}

// add products 
if (isset($_POST['add-product'])) {
    $name = $_POST['proName'];
    $description = $_POST['proDescription'];
    $price = $_POST['proPrice'];
    $stock = $_POST['stock_quantity'];
    $categoryId = $_POST['category_id'];
    $image = null;

    // Validate required fields
    if (empty($name) || empty($description) || empty($price) || empty($stock) || empty($categoryId)) {
        header("Location: addProducts.php?error=All fields are required.");
        exit();
    }

    // Handle file upload
    if (isset($_FILES['proImage']) && $_FILES['proImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $imageName = uniqid() . '_' . basename($_FILES['proImage']['name']);
        $imagePath = $uploadDir . $imageName;
        if (move_uploaded_file($_FILES['proImage']['tmp_name'], $imagePath)) {
            $image = $imagePath;
        } else {
            header("Location: addProducts.php?error=Failed to upload image.");
            exit();
        }
    }

    // Add product to database
    if (addProducts($conn, $name, $description, $price, $stock, $categoryId, $image)) {
        header("Location: viewProducts.php?message=Product added successfully.");
        exit();
    } else {
        header("Location: addProducts.php?error=Failed to add product.");
        exit();
    }
}


// update products
if (isset($_POST['update_product'])) {
    $product_id = $_POST['product_id'];
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $stock = filter_var($_POST['stock_quantity'], FILTER_SANITIZE_NUMBER_INT);
    $category_id = filter_var($_POST['category_id'], FILTER_SANITIZE_NUMBER_INT);
    $current_image = $_POST['current_image'];
    $image = $current_image;
    $uploadError = null;

    // Validate required fields
    if (empty($name) || empty($description) || empty($price) || $price === false || empty($stock) || $stock === false || empty($category_id) || $category_id === false) {
        header("Location: editProduct.php?id=$product_id&error=All fields are required and must be valid.");
        exit();
    }

    // Handle file upload
    if (isset($_FILES['proImage']) && $_FILES['proImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
            }
        }


        $imageName = uniqid() . '_' . basename($_FILES['proImage']['name']);
        $imagePath = $uploadDir . $imageName;
        if (move_uploaded_file($_FILES['proImage']['tmp_name'], $imagePath)) {
            $image = $imagePath;
        } else {
            header("Location: addProducts.php?error=Failed to upload image.");
            exit();
        }


        if ($uploadError) {
            header("Location: editProduct.php?id=$product_id&error=" . urlencode($uploadError));
            exit();
        }
    }

    try {
        $stmt = $conn->prepare("UPDATE products SET name = :name, description = :description, price = :price, category_id = :category_id, stock_quantity = :stock_quantity, image = :image WHERE id = :id");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':stock_quantity', $stock, PDO::PARAM_STR);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':image', $image, PDO::PARAM_STR);
        $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);

        $stmt->execute();

        header("Location: viewProducts.php?message=Product updated successfully.");
        exit();
    } catch (PDOException $e) {
        header("Location: editProduct.php?id=$product_id&error=" . urlencode("Database error: " . $e->getMessage()));
        exit();
    }
}


// delete products

// if (isset($_POST['update_product'])) {
//     $product_id = $_POST['product_id'];
//     $name = $_POST['name'];
//     $description = $_POST['description'];
//     $price = $_POST['price'];
//     $stocl = $_POST['stock_quantity'];
//     $category_id = $_POST['category_id'];
//     $image = $_POST['current_image']; // Use current image if no new image is uploaded

//     // Validate required fields
//     if (empty($name) || empty($description) || empty($price) || empty($stock) || empty($category_id)) {
//         header("Location: editProduct.php?id=$product_id&error=All fields are required.");
//         exit();
//     }

//     // Handle file upload
//     if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
//         $uploadDir = '../uploads/';
//         if (!is_dir($uploadDir)) {
//             mkdir($uploadDir, 0777, true);
//         }
//         $imageName = uniqid() . '_' . basename($_FILES['image']['name']);
//         $imagePath = $uploadDir . $imageName;
//         if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
//             $image = $imagePath;
//         }
//     }

//     try {
//         $stmt = $conn->prepare("UPDATE products SET name = :name, description = :description, price = :price, stock_quantity = :stock_quantity, category_id = :category_id, image = :image WHERE id = :id");
//         $stmt->execute([
//             ':name' => $name,
//             ':description' => $description,
//             ':price' => $price,
//             'stock_quantity' => $stock,
//             ':category_id' => $category_id,
//             ':image' => $image,
//             ':id' => $product_id
//         ]);

//         header("Location: ../viewProducts.php?message=Product updated successfully.");
//         exit();
//     } catch (PDOException $e) {
//         header("Location: editProduct.php?id=$product_id&error=" . $e->getMessage());
//         exit();
//     }
// }


?>