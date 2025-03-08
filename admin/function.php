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

// update category

// add products
function addProducts($conn, $name, $description, $price, $categoryId, $image) {
    try {
        $sql = "INSERT INTO products (name, description, price, category_id, image) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $description, $price, $categoryId, $image]);
        return true; // Indicate success
    } catch (PDOException $e) {
        error_log("Error adding product: " . $e->getMessage()); // Log the error
        return false; // Indicate failure
    }
}

// Handle form submission
if (isset($_POST['add-product'])) {
    $name = $_POST['proName'];
    $description = $_POST['proDescription'];
    $price = $_POST['proPrice'];
    $categoryId = $_POST['category_id'];
    $image = null;

    // Validate required fields
    if (empty($name) || empty($description) || empty($price) || empty($categoryId)) {
        header("Location: addProducts.php?error=All fields are required.");
        exit();
    }

    // Handle file upload
    if (isset($_FILES['proImage']) && $_FILES['proImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/products/';
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
    if (addProducts($conn, $name, $description, $price, $categoryId, $image)) {
        header("Location: viewProducts.php?message=Product added successfully.");
        exit();
    } else {
        header("Location: addProducts.php?error=Failed to add product.");
        exit();
    }
}

?>