<?php
require '../lib/pay/paypal/PayPalPay.php';

$paypal = new PayPalPay(
    'ASrAq4rngq0NuwVZFUr28ZYYvVr3oj8f_h686nqEIfPdIoSiJrVw4em1NlVm5GzTpm4KWxgUtpxgKM22',
    'EN5Nzz4iR0dfnLmM1VVHn-BrOSJaCNSRWO_loUYGlVp7GCsjz4Yd_MctduQ9BGo2it5Qiw-a2bcGumbN',
    true // sandbox
);

$order = $paypal->createOrder(
    25.00,
    'USD',
    'http://localhost/interpal/apps/pay/paypalSuccess.php',
    'http://localhost/interpal/apps/pay/paypalCancel.php'
);

foreach ($order['links'] as $link) {
    if ($link['rel'] === 'approve') {
        header("Location: {$link['href']}");
        exit;
    }
}
