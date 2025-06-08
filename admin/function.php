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
        header("Location: viewCategories.php?message=User added successfully!");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// ========================== Edit Category =================================
// Sanitize and validate the ID from GET request
if (isset($_POST["update-categories"])) {
    // Sanitize and validate the ID
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT); // Get ID from POST for updates
    if (!$id) {
        $_SESSION['message'] = "Invalid category ID for update!";
        header("Location: ../viewCategories.php"); // Redirect to the view page
        exit();
    }

    // Handle form submission for updating category
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update-category'])) {
        // Sanitize inputs
        $title = htmlspecialchars(trim($_POST['title']));
        $metaKeyword = htmlspecialchars(trim($_POST['metaKeyword'] ?? ''));
        $metaTitle = htmlspecialchars(trim($_POST['metaTitle'] ?? ''));
        $metaDescription = htmlspecialchars(trim($_POST['metaDescription']));
        $categoryStatus = isset($_POST["categoryStatus"]) ? 1 : 0; // Check if status is active

        // Validate inputs (you can add more specific validation)
        if (empty($title) || empty($metaDescription)) {
            $_SESSION['message'] = "Category Title and Meta Description are required!";
            header("Location: editCategory.php?id=$id");
            exit();
        }

        // Update the category
        try {
            $stmt = $conn->prepare("UPDATE categories SET title = ?, meta_keyword = ?, meta_title = ?, meta_description = ?, status = ? WHERE id = ?");
            if ($stmt->execute([$title, $metaKeyword, $metaTitle, $metaDescription, $categoryStatus, $id])) {
                $_SESSION['message'] = "Category updated successfully!";
                header("Location: ../viewCategories.php");
                exit();
            } else {
                $_SESSION['message'] = "Error updating category.";
                header("Location: editCategory.php?id=$id");
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION['message'] = "Error updating category: " . $e->getMessage();
            header("Location: editCategory.php?id=$id");
            exit();
        }
    } else {
        // If the form wasn't submitted for update, but the 'update-categories' block was accessed,
        // it likely means we need to fetch data for the edit form.

        // Sanitize and validate the ID from GET request (for displaying the edit form)
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id) {
            $_SESSION['message'] = "Invalid category ID for editing!";
            header("Location: ../viewCategories.php"); // Redirect to the view page
            exit();
        }

        // Fetch the category details for editing
        try {
            $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
            $stmt->execute([$id]);
            $category = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$category) {
                $_SESSION['message'] = "Category not found!";
                header("Location: ../viewCategories.php"); // Redirect to the view page
                exit();
            }

            // You would typically return or make $category available to your HTML form here.
            // For this fix, I'm assuming you're including this code in the same file as the form.

        } catch (PDOException $e) {
            $_SESSION['message'] = "Error fetching category details: " . $e->getMessage();
            header("Location: ../viewCategories.php"); // Redirect to the view page
            exit();
        }
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


// ========================================================= add products ==========================================================
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


// ==================================== update products ===========================================================
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

// ========================== Delete Products ==================================
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $productIdToDelete = $_GET['id'];

    try {
        // Prepare the SQL query to delete the product
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindParam(':id', $productIdToDelete, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Check if any rows were affected (meaning the product existed)
        if ($stmt->rowCount() > 0) {
            // Redirect to the products page with a success message
            header("Location: viewProducts.php?message=Product deleted successfully!");
            exit();
        } else {
            // Redirect with an error message if the product wasn't found
            header("Location: viewProducts.php?error=Product not found!");
            exit();
        }
    } catch (PDOException $e) {
        // Display an error message if there's a database issue
        header("Location: viewProducts.php?error=Error deleting product: " . urlencode($e->getMessage()));
        exit();
    }
}

// ========================== add Users ================================

if (isset($_POST['add-user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $email = $_POST['email'];
    $role = $_POST['role'];

    try {
        // Prepare the SQL query
        $sql = "INSERT INTO users (username, password, email, role) 
                VALUES (:username, :password, :email, :role)";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);

        // Execute the query
        $stmt->execute();

        // Redirect to the users page with a success message
        header("Location: viewUsers.php?message=User added successfully!");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// ========================== Update Users =================================
if (isset($_POST['update-user'])) {
    $userId = $_POST['user_id']; // Assuming you have a hidden input field with the user ID
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    try {
        // ពិនិត្យមើលថាតើឈ្មោះអ្នកប្រើប្រាស់ថ្មីមានរួចហើយឬនៅ សម្រាប់អ្នកប្រើប្រាស់ផ្សេងទៀត
        $checkSql = "SELECT COUNT(*) FROM users WHERE username = :username AND id != :id";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bindParam(':username', $username);
        $checkStmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $checkStmt->execute();
        $userCount = $checkStmt->fetchColumn();

        if ($userCount > 0) {
            // បង្ហាញសារកំហុសប្រសិនបើឈ្មោះអ្នកប្រើប្រាស់ថ្មីមានរួចហើយ
            header("Location: editUser.php?id=$userId&error=ឈ្មោះអ្នកប្រើប្រាស់នេះមានរួចហើយ។ សូមជ្រើសរើសឈ្មោះផ្សេងទៀត។");
            exit();
        } else {
            // ប្រសិនបើឈ្មោះអ្នកប្រើប្រាស់មិនទាន់មានទេ ឬជាឈ្មោះអ្នកប្រើប្រាស់បច្ចុប្បន្ន ធ្វើបច្ចុប្បន្នភាពព័ត៌មានអ្នកប្រើប្រាស់
            $sql = "UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            // Redirect to the users page with a success message
            header("Location: viewUsers.php?message=ព័ត៌មានអ្នកប្រើប្រាស់បានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ!");
            exit();
        }
    } catch (PDOException $e) {
        header("Location: editUser.php?id=$userId&error=កំហុសមូលដ្ឋានទិន្នន័យ: " . urlencode($e->getMessage()));
        exit();
    }
}

// ========================== Delete Users ==================================
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userIdToDelete = $_GET['id'];

    try {
        // Prepare the SQL query to delete the user
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindParam(':id', $userIdToDelete, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Check if any rows were affected (meaning the user existed)
        if ($stmt->rowCount() > 0) {
            // Redirect to the users page with a success message
            header("Location: viewUsers.php?message=User deleted successfully!");
            exit();
        } else {
            // Redirect with an error message if the user wasn't found
            header("Location: viewUsers.php?error=User not found!");
            exit();
        }
    } catch (PDOException $e) {
        // Display an error message if there's a database issue
        header("Location: viewUsers.php?error=Error deleting user: " . urlencode($e->getMessage()));
        exit();
    }
}
