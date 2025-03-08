<?php
// Include the database connection file
include("configs/DBconnect.php");

// Handle form submission
if (isset($_POST['add-order'])) {
    $orderNumber = $_POST['order_number'];
    $customerName = $_POST['customer_name'];
    $orderDate = $_POST['order_date'];
    $totalAmount = $_POST['total_amount'];
    $status = $_POST['status'];

    try {
        // Prepare the SQL query
        $sql = "INSERT INTO orders (order_number, customer_name, order_date, total_amount, status) 
                VALUES (:order_number, :customer_name, :order_date, :total_amount, :status)";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':order_number', $orderNumber);
        $stmt->bindParam(':customer_name', $customerName);
        $stmt->bindParam(':order_date', $orderDate);
        $stmt->bindParam(':total_amount', $totalAmount);
        $stmt->bindParam(':status', $status);

        // Execute the query
        $stmt->execute();

        // Redirect to the orders page with a success message
        header("Location: viewOrders.php?message=Order added successfully!");
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
    <title>Add Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Add Order</h2>
        <form action="addOrder.php" method="post">
            <!-- Order Number -->
            <div class="mb-3">
                <label for="order_number" class="form-label">Order Number</label>
                <input type="text" class="form-control" id="order_number" name="order_number" required>
            </div>

            <!-- Customer Name -->
            <div class="mb-3">
                <label for="customer_name" class="form-label">Customer Name</label>
                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
            </div>

            <!-- Order Date -->
            <div class="mb-3">
                <label for="order_date" class="form-label">Order Date</label>
                <input type="date" class="form-control" id="order_date" name="order_date" required>
            </div>

            <!-- Total Amount -->
            <div class="mb-3">
                <label for="total_amount" class="form-label">Total Amount</label>
                <input type="number" step="0.01" class="form-control" id="total_amount" name="total_amount" required>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="Pending">Pending</option>
                    <option value="Processing">Processing</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>

            <!-- Submit Button -->
            <button type="submit" name="add-order" class="btn btn-primary">Add Order</button>
        </form>
    </div>
</body>
</html>