<?php
include './configs/DBconnect.php'; // Include database connection
include 'pages/addProducts.php';
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
                    <ul class="navbar-nav">
                        <li class="nav-item d-block d-xl-none">
                            <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                                <i class="ti ti-menu-2"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                                <i class="ti ti-bell-ringing"></i>
                                <div class="notification bg-primary rounded-circle"></div>
                            </a>
                        </li>
                    </ul>
                    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                        <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                            <a href="https://adminmart.com/product/modernize-free-bootstrap-admin-dashboard/" target="_blank" class="btn btn-primary">Download Free</a>
                            <li class="nav-item dropdown">
                                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <img src="./assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                                    <div class="message-body">
                                        <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                            <i class="ti ti-user fs-6"></i>
                                            <p class="mb-0 fs-3">My Profile</p>
                                        </a>
                                        <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                            <i class="ti ti-mail fs-6"></i>
                                            <p class="mb-0 fs-3">My Account</p>
                                        </a>
                                        <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                            <i class="ti ti-list-check fs-6"></i>
                                            <p class="mb-0 fs-3">My Task</p>
                                        </a>
                                        <a href="./login.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!-- Header End -->

            <div class="container-fluid">
                <div class="d-flex justify-content-between ">
                    <h5 class="card-title fw-semibold mb-4 ">View Products</h5>
                    <button class="btn btn-danger text-white" data-toggle="modal" data-target="#addModal">Add Product</button>
                </div>
                <div class="card mb-0 p-4 mt-2">
                    <!-- Products Table -->
                    <table class="table table-bordered">
                        <thead class="bg-info">
                            <tr class="text-white">
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Category</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include './function.php';

                            // Fetch all products from the database
                            $sql = "SELECT p.*, c.title AS category_name 
                                        FROM products p 
                                        LEFT JOIN categories c ON p.category_id = c.id";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>

                            <?php
                            if (count($products) > 0) {
                                foreach ($products as $product) {
                            ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($product['name'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($product['description'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($product['price'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($product['stock_quantity'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></td>
                                        <td>
                                            <img src="<?php echo htmlspecialchars($product['image'] ?? ''); ?>" alt="Product Image" width="50">
                                        </td>
                                        <td>
                                            <!-- <a href="./pages/editProduct.php?id=<?php echo htmlspecialchars($product['id'] ?? ''); ?>" class="btn  btn-primary">Edit</a>
                                                        <a href="./pages/deleteProducts.php?id=<?php echo htmlspecialchars($product['id'] ?? ''); ?>" class="btn  btn-danger" onclick="return confirm('Are you sure you want to delete this Product?')" >Delete</a> -->
                                            <div class="dropdown show">
                                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                                    id="dropdownMenuLink" data-toggle="dropdown"
                                                    aria-haspopup="true"
                                                    aria-expanded="false">Options</a>

                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                    <!-- <a class="dropdown-item text-info" data-toggle="modal" data-target="#editModal" href="./pages/updateProducts.php?id=<?php
                                                                                                                                                                                echo htmlspecialchars($product['id'] ?? ''); ?>">edite</a> -->
                                                    <button class="dropdown-item text-success" data-toggle="modal" data-target="#addModal">Add</button>

                                                    <button class="dropdown-item text-info" data-toggle="modal" data-target="#editModal<?php echo htmlspecialchars($product['id'] ?? ''); ?>"
                                                        data-product-id="">Update</button>

                                                    <a class="dropdown-item text-danger" href="./pages/deleteProducts.php?id=<?php
                                                                                                                                echo htmlspecialchars($product['id'] ?? ''); ?>" class="btn  btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this Product?')">Delete</a>
                                                </div>

                                            </div>

                                        </td>

                                    </tr>
                                    <?php include 'pages/editProduct.php'; ?>


                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="7" class="text-center">No products found.</td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>

                </div>


            </div>
        </div>
    </div>

    <?php include 'components/js.php' ?>
</body>

</html>