<?php

session_start();

include 'db/DBconnnect.php'; 

if (isset($_POST['place_order'])) {

    // 1. Get user information and stored it in database
    $name = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $address = $_POST['address'];

    $order_status = "on_hold";
    $customer_id = 1; // Hardcoded customer_id, consider getting from $_SESSION['customer_id'] after login
    $order_date = date('Y-m-d H:i:s');

    // Calculate order_cost server-side
    $order_cost = 0;
    $cart_items = $_SESSION['cart'] ?? [];
    foreach ($cart_items as $item) {
        $order_cost += ($item['price'] * $item['quantity']);
    }

    // Corrected typo: cumstomer_address -> customer_address
    $stmt = $conn->prepare("INSERT INTO orders (order_cost, status, customer_id, customer_city, customer_phone, customer_address, order_date)
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$order_cost, $order_status, $customer_id, $city, $phone, $address, $order_date]);
    $order_id = $conn->lastInsertId();

    // 2. Store user information in session
    foreach ($cart_items as $item) {
        
        $product_id = $item['id'];
        $product_name = $item['name'];
        $product_price = $item['price'];
        $product_quantity = $item['quantity'];
        $product_image = $item['image']; 

        $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity)
                                     VALUES (?, ?, ?, ?, ?)");
        $stmt_item->execute([$order_id, $product_id, $product_name, $product_price, $product_quantity]);
    }

    // 5. Remove everything from cart
    unset($_SESSION['cart']);

    // 6. Inform user whether everything is fine or there is a problem (ជូនដំណឹងអ្នកប្រើប្រាស់ថា មានបញ្ហាឬអ្វីកើតឡើង)
    header('location: success.php?order_id=' . $order_id);
    exit;
}

else {
    header('location: shoping-cart.php'); // Or checkout.php
    exit;
}

?>