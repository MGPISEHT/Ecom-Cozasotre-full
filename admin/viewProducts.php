<?php
include './configs/DBconnect.php'; // Include database connection
?>

<!doctype html>
<html lang="en">
<?php include 'components/head.php'; ?>
<title>View Products</title>

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
                        <h5 class="card-title fw-semibold mb-4">View Products</h5>
                        <div class="card mb-0">
                            <div class="container-fluid">
                                <!-- Products Table -->
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Price</th>
                                            <th>Category</th>
                                            <th>Image</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Fetch all products from the database
                                        $sql = "SELECT p.*, c.title AS category_name 
                                                FROM products p 
                                                LEFT JOIN categories c ON p.category_id = c.id";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        if (count($products) > 0) {
                                            foreach ($products as $product) {
                                                echo '<tr>';
                                                echo '<td>' . htmlspecialchars($product['id'] ?? '') . '</td>';
                                                echo '<td>' . htmlspecialchars($product['name'] ?? '') . '</td>';
                                                echo '<td>' . htmlspecialchars($product['description'] ?? '') . '</td>';
                                                echo '<td>$' . htmlspecialchars($product['price'] ?? '') . '</td>';
                                                echo '<td>' . htmlspecialchars($product['category_name'] ?? 'Uncategorized') . '</td>';
                                                echo '<td><img src="' . htmlspecialchars($product['image'] ?? '') . '" alt="Product Image" width="50"></td>';
                                                echo '<td>
                                                        <a href="./pages/editProduct.php?id=' . htmlspecialchars($product['id'] ?? '') . '" class="btn btn-sm btn-primary">Edit</a>
                                                        <a href="./pages/deleteProduct.php?id=' . htmlspecialchars($product['id'] ?? '') . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this product?\')">Delete</a>
                                                      </td>';
                                                echo '</tr>';
                                            }
                                        } else {
                                            echo '<tr><td colspan="7" class="text-center">No products found.</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
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