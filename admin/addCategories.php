<?php


?>

<!doctype html>
<html lang="en">
<?php include './components/head.php';
include("./configs/DBconnect.php");
include("./function.php");
?>
<title>Add Categories</title>

<body>
    <!--  Body Wrapper -->
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
                <?php include './components/sidebarNavigation.php'; ?>

                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
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
            <!--  Header End -->
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold mb-4">Add Categories</h5>
                        <div class="card mb-0">
                            <div class="container-fluid">
                                <!-- Add Categories Form -->
                                <div class="card">
                                    <div class="card mb-0">
                                        <div class="card-body">

                                            <?php if (isset($_GET['message'])): ?>
                                                <div class="alert alert-success"><?= htmlspecialchars($_GET['message']) ?></div>
                                            <?php endif; ?>

                                            <?php if (isset($_GET['error'])): ?>
                                                <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
                                            <?php endif; ?>
                                            <!-- Form -->
                                            <form action="function.php" method="post" enctype="multipart/form-data">
                                                <!-- Category Title -->
                                                <div class="mb-3">
                                                    <label for="categoryTitle" class="form-label">Category Title</label>
                                                    <input type="text" class="form-control" id="categoryTitle" name="categoryTitle" placeholder="Enter category title" required>
                                                </div>

                                                <!-- Meta Keyword -->
                                                <div class="mb-3">
                                                    <label for="metaKeyword" class="form-label">Meta Keyword</label>
                                                    <input type="text" class="form-control" id="metaKeyword" name="metaKeyword" placeholder="Enter meta keyword">
                                                </div>

                                                <!-- Meta Title -->
                                                <div class="mb-3">
                                                    <label for="metaTitle" class="form-label">Meta Title</label>
                                                    <input type="text" class="form-control" id="metaTitle" name="metaTitle" placeholder="Enter meta title">
                                                </div>

                                                <!-- Meta Description -->
                                                <div class="mb-3">
                                                    <label for="metaDescription" class="form-label">Meta Description</label>
                                                    <textarea class="form-control" id="metaDescription" name="metaDescription" rows="3" placeholder="Enter meta description"></textarea>
                                                </div>

                                                <!-- Status Toggle -->
                                                <div class="mb-3 form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="categoryStatus" name="categoryStatus" checked>
                                                    <label class="form-check-label" for="categoryStatus">Active</label>
                                                </div>

                                                <!-- Submit Button -->
                                                <button type="submit" name="add-categories" class="btn btn-primary">Add Category</button>
                                            </form>
                                            <!-- End of Form -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--  Main wrapper -->
</body>

<html>

</script>