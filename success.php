<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function getCart()
{
    return $_SESSION['cart'] ?? [];
}

function getPayerInfo()
{
    return $_SESSION['payer_info'] ?? [];
}

if (isset($_GET['token']) && isset($_GET['PayerID'])) {
    $paymentToken = htmlspecialchars($_GET['token']);
    $payerID = htmlspecialchars($_GET['PayerID']);

    $payerInfo = getPayerInfo();
    $cartItems = getCart();

    
    $totalAmount = 0;
    foreach ($cartItems as $item) {
        
        $totalAmount += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 20px; background-color: #f0f2f5; color: #333; }
        .container { max-width: 800px; margin: 50px auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1, h2 { color: #28a745; text-align: center; margin-bottom: 20px; }
        h2 { border-bottom: 1px solid #eee; padding-bottom: 10px; margin-top: 30px; }
        ul { list-style: none; padding: 0; }
        ul li { margin-bottom: 10px; line-height: 1.5; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th, table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        table th { background-color: #f8f8f8; }
        tfoot td { font-weight: bold; background-color: #f2f2f2; }
        p { text-align: center; margin-top: 30px; font-size: 1.1em; color: #555; }
        .warning { color: #dc3545; font-weight: bold; text-align: center; margin-top: 20px; }
        .error-message { color: #dc3545; font-weight: bold; text-align: center; margin-top: 20px; }
        .action-link { display: block; text-align: center; margin-top: 20px; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; max-width: 200px; margin-left: auto; margin-right: auto; }
        .action-link:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Thank You for Your Order!</h1>

        <p class="warning">
            WARNING: For a production environment, you MUST implement server-side PayPal API verification here
            to confirm the payment status and prevent fraud. This demonstration code does not perform that critical step.
        </p>

        <h2>Payment Details:</h2>
        <ul>
            <li><strong>Payment Token:</strong> <?php echo $paymentToken; ?></li>
            <li><strong>Payer ID:</strong> <?php echo $payerID; ?></li>
            <li><strong>Total Amount Paid:</strong> $<?php echo number_format($totalAmount, 2); ?></li>
        </ul>

        <h2>Payer Information:</h2>
        <ul>
            <li><strong>Username:</strong> <?php echo htmlspecialchars($payerInfo['username'] ?? 'N/A'); ?></li>
            <li><strong>Email:</strong> <?php echo htmlspecialchars($payerInfo['email'] ?? 'N/A'); ?></li>
            <li><strong>Phone:</strong> <?php echo htmlspecialchars($payerInfo['phone'] ?? 'N/A'); ?></li>
            <li><strong>Address:</strong> <?php echo htmlspecialchars($payerInfo['address'] ?? 'N/A'); ?></li>
            <li><strong>City:</strong> <?php echo htmlspecialchars($payerInfo['city'] ?? 'N/A'); ?></li>
        </ul>

        <h2>Ordered Items:</h2>
        <?php if (!empty($cartItems)): ?>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($item['quantity'] ?? '0'); ?></td>
                        <td>$<?php echo number_format($item['price'] ?? 0, 2); ?></td>
                        <td>$<?php echo number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Grand Total:</strong></td>
                    <td>$<?php echo number_format($totalAmount, 2); ?></td>
                </tr>
            </tfoot>
        </table>
        <?php else: ?>
            <p style="text-align: center;">No items found in your cart for this order.</p>
        <?php endif; ?>

        <p>Your order is being processed. You will receive a confirmation email shortly.</p>

        <?php
        // Clear cart and payer info from session after successful order
        unset($_SESSION['cart']);
        unset($_SESSION['payer_info']);
        ?>
    </div>
</body>
</html>
<?php
} else {
    // If token or PayerID are missing, something went wrong with the PayPal redirect
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Error</title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 20px; background-color: #f0f2f5; color: #333; }
        .container { max-width: 800px; margin: 50px auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .error-message { color: #dc3545; font-weight: bold; text-align: center; margin-top: 20px; font-size: 1.2em; }
        .action-link { display: block; text-align: center; margin-top: 20px; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; max-width: 200px; margin-left: auto; margin-right: auto; }
        .action-link:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <p class="error-message">
            An error occurred during payment processing.
            <br>
            PayPal transaction details (token or PayerID) were missing from the URL.
            This might happen if the payment was cancelled or an issue occurred during redirection.
        </p>
        <a href="index.php" class="action-link">Go back to shopping</a>
    </div>
</body>
</html>
<?php
}
?>
