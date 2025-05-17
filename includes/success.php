<?php
session_start();

// ពិនិត្យមើលថាតើមានព័ត៌មានការទូទាត់ពី PayPal ដែរឬទេ
if (isset($_GET['token']) && isset($_GET['PayerID'])) {
    $paymentToken = $_GET['token'];
    $payerID = $_GET['PayerID'];

    // នៅទីនេះ អ្នកគួរតែធ្វើការផ្ទៀងផ្ទាត់បន្ថែមជាមួយ PayPal API
    // ដើម្បីធានាថាការទូទាត់ពិតជាជោគជ័យ។
    // ឧទាហរណ៍ អ្នកអាចប្រើ NVP/SOAP API ឬ REST API របស់ PayPal
    // ដើម្បីទទួលបានព័ត៌មានលម្អិតអំពីប្រតិបត្តិការដោយប្រើ $paymentToken និង $payerID ។

    // បន្ទាប់ពីការផ្ទៀងផ្ទាត់ជោគជ័យ អ្នកអាចទាញយកព័ត៌មានអ្នកទូទាត់ពី session
    $payerInfo = getPayerInfo();
    $cartItems = getCart();
    $totalAmount = 0;
    foreach ($cartItems as $item) {
        $totalAmount += $item['price'] * $item['quantity'];
    }

    // បង្ហាញព័ត៌មានលម្អិតនៃការទូទាត់ និងព័ត៌មានអ្នកទូទាត់
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>ការទូទាត់ជោគជ័យ</title>
        </head>
    <body>
        <h1>សូមអរគុណសម្រាប់ការបញ្ជាទិញរបស់អ្នក!</h1>

        <h2>ព័ត៌មានលម្អិតនៃការទូទាត់:</h2>
        <ul>
            <li>Payment Token: <?php echo htmlspecialchars($paymentToken); ?></li>
            <li>Payer ID: <?php echo htmlspecialchars($payerID); ?></li>
            </ul>

        <h2>ព័ត៌មានអ្នកទូទាត់:</h2>
        <ul>
            <li>Username: <?php echo htmlspecialchars($payerInfo['username'] ?? ''); ?></li>
            <li>Email: <?php echo htmlspecialchars($payerInfo['email'] ?? ''); ?></li>
            <li>Phone: <?php echo htmlspecialchars($payerInfo['phone'] ?? ''); ?></li>
            <li>Address: <?php echo htmlspecialchars($payerInfo['address'] ?? ''); ?></li>
            </ul>

        <h2>ទំនិញដែលបានបញ្ជាទិញ:</h2>
        <table>
            <thead>
                <tr>
                    <th>ឈ្មោះផលិតផល</th>
                    <th>ចំនួន</th>
                    <th>តម្លៃ</th>
                    <th>សរុប</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>សរុបទាំងអស់:</strong></td>
                    <td>$<?php echo number_format($totalAmount, 2); ?></td>
                </tr>
            </tfoot>
        </table>

        <p>ការបញ្ជាទិញរបស់អ្នកកំពុងត្រូវបានដំណើរការ។ អ្នកនឹងទទួលបានអ៊ីមែលបញ្ជាក់ក្នុងពេលឆាប់ៗនេះ។</p>

        <?php
        // សម្អាត cart និង payer info ពី session បន្ទាប់ពីការបញ្ជាទិញជោគជ័យ
        unset($_SESSION['cart']);
        unset($_SESSION['payer_info']);
        ?>
    </body>
    </html>
    <?php
} else {
    // ប្រសិនបើមិនមាន token ឬ PayerID ទេ នោះមានន័យថាមានបញ្ហា
    echo "<p>មានបញ្ហាកើតឡើងក្នុងការដំណើរការការទូទាត់។</p>";
}

// មុខងារ getPayerInfo() និង getCart() គួរតែត្រូវបានកំណត់ម្តងទៀតនៅទីនេះ
function getPayerInfo()
{
    return isset($_SESSION['payer_info']) ? $_SESSION['payer_info'] : [];
}

function getCart()
{
    return isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
}
?>