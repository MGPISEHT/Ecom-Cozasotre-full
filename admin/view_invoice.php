<?php
include './configs/DBconnect.php';

if (!isset($_GET['id'])) {
    die("Invoice ID is required.");
}

$invoice_id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM invoices WHERE id = ?");
$stmt->execute([$invoice_id]);
$invoice = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("
    SELECT p.name, p.price, i.quantity, i.subtotal
    FROM invoice_items i
    JOIN products p ON i.product_id = p.id
    WHERE i.invoice_id = ?");
$stmt->execute([$invoice_id]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Invoice #<?php echo $invoice['id']; ?></h2>
    <p><strong>Customer:</strong> <?php echo $invoice['customer_name']; ?></p>
    <p><strong>Email:</strong> <?php echo $invoice['customer_email']; ?></p>
    <p><strong>Date:</strong> <?php echo $invoice['invoice_date']; ?></p>
    <p><strong>Status:</strong> <?php echo $invoice['status']; ?></p>

    <h4>Products</h4>
    <table class="table table-bordered">
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>
        <?php foreach ($products as $product) { ?>
            <tr>
                <td><?php echo $product['name']; ?></td>
                <td>$<?php echo number_format($product['price'], 2); ?></td>
                <td><?php echo $product['quantity']; ?></td>
                <td>$<?php echo number_format($product['subtotal'], 2); ?></td>
            </tr>
        <?php } ?>
    </table>

    <h3>Total Amount: $<?php echo number_format($invoice['total_amount'], 2); ?></h3>
    <a href="download_invoice.php?id=<?php echo $invoice_id; ?>" class="btn btn-primary">Download PDF</a>
</div>
</body>
</html>
