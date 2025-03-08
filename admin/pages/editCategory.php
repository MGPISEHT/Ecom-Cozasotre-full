<?php
session_start(); // Start the session for messages
include("../configs/DBconnect.php");

// Check if the ID is provided in the URL
if (!isset($_GET['id'])) {
    $_SESSION['message'] = "Invalid request: No ID provided!";
    header("Location: ../categories.php");
    exit();
}

// Sanitize and validate the ID
$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
if (!$id) {
    $_SESSION['message'] = "Invalid category ID!";
    header("Location: ../categories.php");
    exit();
}

// Fetch the category details
try {
    $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$category) {
        $_SESSION['message'] = "Category not found!";
        header("Location: ../categories.php");
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['message'] = "Error fetching category details: " . $e->getMessage();
    header("Location: ../categories.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $title = htmlspecialchars(trim($_POST['title']));
    $meta_description = htmlspecialchars(trim($_POST['meta_description']));

    // Validate inputs
    if (empty($title) || empty($meta_description)) {
        $_SESSION['message'] = "All fields are required!";
        header("Location: editCategory.php?id=$id");
        exit();
    }

    // Update the category
    try {
        $stmt = $conn->prepare("UPDATE categories SET title = ?, meta_description = ? WHERE id = ?");
        if ($stmt->execute([$title, $meta_description, $id])) {
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Category</h2>

        <!-- Display session messages -->
        <?php
        if (isset($_SESSION['message'])) {
            echo "<div class='alert alert-info'>" . htmlspecialchars($_SESSION['message']) . "</div>";
            unset($_SESSION['message']);
        }
        ?>

        <!-- Edit Category Form -->
        <form action="editCategory.php?id=<?= htmlspecialchars($id) ?>" method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($category['id']) ?>">

            <!-- Category Title -->
            <div class="mb-3">
                <label for="title" class="form-label">Category Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($category['title']) ?>" required>
            </div>

            <!-- Meta Description -->
            <div class="mb-3">
                <label for="meta_description" class="form-label">Meta Description</label>
                <textarea class="form-control" id="meta_description" name="meta_description" rows="3" required><?= htmlspecialchars($category['meta_description']) ?></textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Update Category</button>
        </form>
    </div>
</body>
</html>