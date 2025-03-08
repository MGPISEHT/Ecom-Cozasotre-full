<?php
include './configs/DBconnect.php'; // Include database connection
include 'function.php'; // Include functions

?>

<!doctype html>
<html lang="en">

<?php include 'components/head.php'; ?>
<title>Add Products</title>

<body>
    <!-- Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="./index.php" class="text-nowrap logo-img">
                        <img src="./assets/images/logos/dark-logo.svg" width="180" alt="" />
                    </a>
                    <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-8"></i>
                    </div>
                </div>
                <!-- Sidebar navigation-->
                <?php include 'components/sidebarNavigation.php'; ?>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- Sidebar End -->

        <!-- Main wrapper -->
        <div class="body-wrapper">
            <!-- Header Start -->
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <!-- Header content -->
                </nav>
            </header>
            <!-- Header End -->

            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <?php
                        if (isset($_SESSION['message'])) {
                        ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>Hey!</strong> <?= $_SESSION['message'] ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php
                            unset($_SESSION['message']);
                        }
                        ?>
                        <h5 class="card-title fw-semibold mb-4">Add Products</h5>
                        <div class="card mb-0">
                            <div class="container-fluid">
                                <!-- Add Product Form -->
                                <form action="function.php" method="post" enctype="multipart/form-data">
                                    <!-- Product Name -->
                                    <div class="mb-3">
                                        <label for="proName" class="form-label">Product Name</label>
                                        <input type="text" class="form-control" id="proName" name="proName" required>
                                    </div>

                                    <!-- Product Description -->
                                    <div class="mb-3">
                                        <label for="proDescription" class="form-label">Description</label>
                                        <textarea class="form-control" id="proDescription" name="proDescription" rows="3"></textarea>
                                    </div>

                                    <!-- Product Price -->
                                    <div class="mb-3">
                                        <label for="proPrice" class="form-label">Price</label>
                                        <input type="number" step="0.01" class="form-control" id="proPrice" name="proPrice" required>
                                    </div>

                                    <!-- Product Category -->
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Category</label>
                                        <select class="form-control" id="category_id" name="category_id" required>
                                            <option value="">--- Select Category ---</option>
                                            <?php
                                            // Fetch categories from the database
                                            $sql = "SELECT * FROM categories";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();
                                            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            foreach ($categories as $category) {
                                                echo "<option value='" . htmlspecialchars($category['id']) . "'>" . htmlspecialchars($category['title']) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <!-- Product Image -->
                                    <div class="mb-3">
                                        <label for="proImage" class="form-label">Product Image</label>
                                        <input type="file" class="form-control" id="proImage" name="proImage" accept="image/*" required>
                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit" name="add-product" class="btn btn-primary">Add Product</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'components/js.php' ?>
</body>

</html>