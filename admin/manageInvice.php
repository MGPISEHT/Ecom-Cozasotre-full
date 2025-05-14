<?php
session_start();
include './configs/DBconnect.php';

// Fetch all products
$stmt = $conn->prepare("SELECT * FROM products");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Invoice</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Create Invoice</h2>

    <?php if (isset($_SESSION['message'])) { ?>
        <div class="alert alert-success"><?php echo $_SESSION['message']; ?></div>
        <?php unset($_SESSION['message']); } ?>

    <form action="process_invoice.php" method="POST">
        <div class="mb-3">
            <label>Customer Name:</label>
            <input type="text" name="customer_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Customer Email:</label>
            <input type="email" name="customer_email" class="form-control">
        </div>
        <div class="mb-3">
            <label>Invoice Date:</label>
            <input type="date" name="invoice_date" class="form-control" required>
        </div>

        <h4>Products</h4>
        <div id="product-list">
            <div class="product-item mb-3">
                <select name="product_id[]" class="form-control" required>
                    <option value="">Select Product</option>
                    <?php foreach ($products as $product) { ?>
                        <option value="<?php echo $product['id']; ?>" data-price="<?php echo $product['price']; ?>">
                            <?php echo $product['name'] . " ($" . $product['price'] . ")"; ?>
                        </option>
                    <?php } ?>
                </select>
                <input type="number" name="quantity[]" class="form-control mt-2" placeholder="Quantity" min="1" required>
                <button type="button" class="btn btn-danger mt-2 remove-product">Remove</button>
            </div>
        </div>

        <button type="button" id="add-product" class="btn btn-secondary mt-3">Add Product</button>
        <button type="submit" name="create_invoice" class="btn btn-primary mt-3">Create Invoice</button>
    </form>
</div>

<script>
document.getElementById("add-product").addEventListener("click", function() {
    let productList = document.getElementById("product-list");
    let newProduct = productList.firstElementChild.cloneNode(true);
    productList.appendChild(newProduct);
});

document.addEventListener("click", function(e) {
    if (e.target.classList.contains("remove-product")) {
        if (document.querySelectorAll(".product-item").length > 1) {
            e.target.parentElement.remove();
        }
    }
});
</script>

</body>
</html>
