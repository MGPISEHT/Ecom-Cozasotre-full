<?php
// Include the database connection file
include("configs/DBconnect.php");

// Fetch the order to edit
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $sql = "SELECT * FROM orders WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            header("Location: viewOrders.php");
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: viewOrders.php");
    exit();
}

// Handle form submission
if (isset($_POST['update-order'])) {
    $id = $_POST['id'];
    $orderNumber = $_POST['order_number'];
    $customerName = $_POST['customer_name'];
    $orderDate = $_POST['order_date'];
    $totalAmount = $_POST['total_amount'];
    $status = $_POST['status'];

    try {
        $sql = "UPDATE orders 
                SET order_number = :order_number, customer_name = :customer_name, 
                    order_date = :order_date, total_amount = :total_amount, status = :status 
                WHERE id = :id";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':order_number', $orderNumber);
        $stmt->bindParam(':customer_name', $customerName);
        $stmt->bindParam(':order_date', $orderDate);
        $stmt->bindParam(':total_amount', $totalAmount);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Redirect to the orders page with a success message
        header("Location: viewOrders.php?message=Order updated successfully!");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Order</h2>
        <form action="editOrder.php" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($order['id']); ?>">

            <!-- Order Number -->
            <div class="mb-3">
                <label for="order_number" class="form-label">Order Number</label>
                <input type="text" class="form-control" id="order_number" name="order_number" value="<?php echo htmlspecialchars($order['order_number']); ?>" required>
            </div>

            <!-- Customer Name -->
            <div class="mb-3">
                <label for="customer_name" class="form-label">Customer Name</label>
                <input type="text" class="form-control" id="customer_name" name="customer_name" value="<?php echo htmlspecialchars($order['customer_name']); ?>" required>
            </div>

            <!-- Order Date -->
            <div class="mb-3">
                <label for="order_date" class="form-label">Order Date</label>
                <input type="date" class="form-control" id="order_date" name="order_date" value="<?php echo htmlspecialchars($order['order_date']); ?>" required>
            </div>

            <!-- Total Amount -->
            <div class="mb-3">
                <label for="total_amount" class="form-label">Total Amount</label>
                <input type="number" step="0.01" class="form-control" id="total_amount" name="total_amount" value="<?php echo htmlspecialchars($order['total_amount']); ?>" required>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="Pending" <?php echo $order['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="Processing" <?php echo $order['status'] === 'Processing' ? 'selected' : ''; ?>>Processing</option>
                    <option value="Completed" <?php echo $order['status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                    <option value="Cancelled" <?php echo $order['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                </select>
            </div>

            <!-- Submit Button -->
            <button type="submit" name="update-order" class="btn btn-primary">Update Order</button>
        </form>
    </div>
</body>
</html>