<?php
/*************************************************
 * CONFIGURATION
 *************************************************/
$BASE_URL = 'https://app.tilopay.com/api/v1';

// Credentials (from TiloPay)
$API_USER = '27pl8y';
$API_PASS = 'LdcNAh';
$API_KEY  = '7522-8987-4366-9416-5242';

// URLs
$RETURN_URL   = 'https://tusitio.com/retorno.php';
$CALLBACK_URL = 'https://tusitio.com/webhook.php';

// Payment data
$AMOUNT      = 15000; // CRC (no decimals)
$CURRENCY    = 'CRC';
$DESCRIPTION = 'Pago de prueba TiloPay';
$ORDER_ID    = uniqid('orden_');

$CUSTOMER = [
    'name'  => 'Juan Perez',
    'email' => 'juan@test.com'
];

/*************************************************
 * CURL HELPER
 *************************************************/
function curl_post($url, $payload = null, $headers = [])
{
    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_TIMEOUT        => 30
    ]);

    if ($payload !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    }

    $response = curl_exec($ch);

    if ($response === false) {
        die('cURL error: ' . curl_error($ch));
    }

    curl_close($ch);
    return json_decode($response, true);
}

/*************************************************
 * 1) LOGIN (QUERY PARAMS)
 *************************************************/
$loginUrl = $BASE_URL . '/login'
          . '?apiuser=' . urlencode($API_USER)
          . '&password=' . urlencode($API_PASS);

$loginResponse = curl_post($loginUrl);

if (!isset($loginResponse['access_token'])) {
    die('Login failed: ' . json_encode($loginResponse));
}

$token = $loginResponse['access_token'];

/*************************************************
 * 2) PROCESS PAYMENT
 *************************************************/
$paymentPayload = [
    'api_key'      => $API_KEY,
    'order_id'     => $ORDER_ID,
    'amount'       => $AMOUNT,
    'currency'     => $CURRENCY,
    'description'  => $DESCRIPTION,
    'return_url'   => $RETURN_URL,
    'callback_url' => $CALLBACK_URL,
    'customer'     => $CUSTOMER
];

$paymentResponse = curl_post(
    $BASE_URL . '/processPayment',
    $paymentPayload,
    [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json'
    ]
);

if (!isset($paymentResponse['payment_url'])) {
    die('Payment creation failed: ' . json_encode($paymentResponse));
}

/*************************************************
 * 3) REDIRECT USER
 *************************************************/
header('Location: ' . $paymentResponse['payment_url']);
exit;
