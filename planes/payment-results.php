<?php
require 'PayPalPay.php';

$paypal = new PayPalPay(
    'ASrAq4rngq0NuwVZFUr28ZYYvVr3oj8f_h686nqEIfPdIoSiJrVw4em1NlVm5GzTpm4KWxgUtpxgKM22',
    'EN5Nzz4iR0dfnLmM1VVHn-BrOSJaCNSRWO_loUYGlVp7GCsjz4Yd_MctduQ9BGo2it5Qiw-a2bcGumbN',
    true
);

$orderId = $_GET['token'] ?? null;

if (!$orderId) {
    die('Invalid PayPal response');
}

$result = $paypal->captureOrder($orderId);

if ($result['status'] === 'COMPLETED') {
    echo "Payment successful!";
    // Save transaction ID, amount, payer info
} else {
    echo "Payment not completed";
}
