
<!doctype html>
<html lang="en">

<?php include 'components/head.php'; ?>
<title>Manage Orders</title>

<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical">
        <!-- Sidebar -->
        <?php include 'components/sidebarNavigation.php'; ?>

        <!-- Main Wrapper -->
        <div class="body-wrapper">
            <!-- Header -->
            <?php include 'components/header.php'; ?>

            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold mb-4">Manage Orders</h5>

                        <!-- Orders Table -->
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User ID</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?= $order['id'] ?></td>
                                        <td><?= $order['user_id'] ?></td>
                                        <td>$<?= $order['total_amount'] ?></td>
                                        <td><?= ucfirst($order['status']) ?></td>
                                        <td>
                                            <a href="updateOrderStatus.php?id=<?= $order['id'] ?>" class="btn btn-info">Update Status</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'components/js.php' ?>
</body>

</html>
