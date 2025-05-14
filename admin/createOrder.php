<?php
session_start();
include './configs/DBconnect.php';

// Fetch customers & products
$customers = $conn->query("SELECT * FROM customers")->fetchAll(PDO::FETCH_ASSOC);
$products = $conn->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Order</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Create Order</h2>

    <?php if (isset($_SESSION['message'])) { ?>
        <div class="alert alert-success"><?php echo $_SESSION['message']; ?></div>
        <?php unset($_SESSION['message']); } ?>

    <form action="process_order.php" method="POST">
        <div class="mb-3">
            <label>Customer:</label>
            <select name="customer_id" class="form-control" required>
                <option value="">Select Customer</option>
                <?php foreach ($customers as $customer) { ?>
                    <option value="<?php echo $customer['id']; ?>">
                        <?php echo $customer['name']; ?>
                    </option>
                <?php } ?>
            </select>
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
        <button type="submit" name="create_order" class="btn btn-primary mt-3">Create Order</button>
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
