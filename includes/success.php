<?php
session_start();
$_SESSION['cart'] = []; // Clear the cart after successful payment
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Success</title>
</head>
<body>
    <h2>Thank you for your purchase!</h2>
    <a href="index.php">Return to Shop</a>
</body>
</html>
