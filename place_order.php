<?php

session_start(); // Start the session to use session variables

include 'db/DBconnnect.php'; // Include your database connection file

if (isset($_POST['place_order'])) {

    // 1. Get user information and stored it in database
    $name = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $order_cost = $_POST['total']; // Using POST total, consider server-side recalculation for security
    $order_status = "on_hold";
    $customer_id = 1; // Hardcoded customer_id, consider getting from $_SESSION['customer_id'] after login
    $order_date = date('Y-m-d H:i:s');

    // Corrected typo: cumstomer_address -> customer_address
    $conn->prepare("INSERT INTO orders (order_cost, status, customer_id, customer_city, customer_phone, customer_address, order_date)
                             VALUES (?, ?, ?, ?, ?, ?, ?)")->execute([$order_cost, $order_status, $customer_id, $city, $phone, $address, $order_date]);
    $order_id = $conn->lastInsertId();

    // 2. Get products from cart (from session)
    $cart_items = $_SESSION['cart'] ?? []; // Assuming cart is stored in $_SESSION['cart']

    // 3. (Implicit) Order already issued into database above

    // 4. Store each single item in order_items database table
    foreach ($cart_items as $item) {
        // Ensure product keys match your cart item structure (e.g., 'id', 'name', 'price', 'quantity', 'image')
        $product_id = $item['id'];
        $product_name = $item['name'];
        $product_price = $item['price'];
        $product_quantity = $item['quantity'];
        $product_image = $item['image']; // Assuming image is stored in cart item

        $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity)
                                 VALUES (?, ?, ?, ?, ?)")->execute([$order_id, $product_id, $product_name, $product_price, $product_quantity]);
    }

    // 5. Remove everything from cart
    unset($_SESSION['cart']);

    // 6. Inform user whether everything is fine or there is a problem (ជូនដំណឹងអ្នកប្រើប្រាស់ថា មានបញ្ហាឬអ្វីកើតឡើង)
    // Redirect to a success page
    header('location: success.php?order_id=' . $order_id);
    exit; // Always exit after a header redirect
}

// Optional: If the script is accessed directly without POST, redirect back to cart/checkout
else {
    header('location: shoping-cart.php'); // Or checkout.php
    exit;
}

?>