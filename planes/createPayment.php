<?php
include '../lib/pay/tilopay/api.php';

try {
    // Initialize TiloPay with credentials from Admin - Tilopay Checkout
    $tiloPay = new TiloPay(
        'fjyl14',                           // API User
        'HJSmBg',                           // API Password
        '7522-8987-4366-9416-5242',        // API Key
        true // sandbox mode
    );

    // Create payment with all required TiloPay fields
    $payment = $tiloPay->createPayment([
        // Amount and Currency (Required)
        'amount'            => 5000,        // Amount in colones (50.00 CRC = 5000)
        'currency'          => 'CRC',       // ISO currency code
        
        // Order Information (Required)
        'orderNumber'       => uniqid('order_'),
        'capture'           => 1,           // 1 = Capture and authorize, 0 = Authorize only
        'description'       => 'Pago de prueba - Plan Premium',
        
        // Customer Billing Information (Required)
        'billToFirstName'   => 'Juan',
        'billToLastName'    => 'Pérez',
        'billToAddress'     => 'Avenida Central 123',
        'billToAddress2'    => 'Apartamento 4B',
        'billToCity'        => 'San José',
        'billToState'       => 'CR-SJ',     // ISO format: CR-SJ (San José, Costa Rica)
        'billToZipPostCode' => '10101',
        'billToCountry'     => 'CR',        // ISO Alpha-2: CR (Costa Rica)
        'billToTelephone'   => '+50622334455',
        'billToEmail'       => 'froshsystems@gmail.com',
        
        // Platform (Required)
        'platform'          => 'Frosh LavaCar App',
        
        // URLs (Optional but recommended)
        'return_url'        => 'http://localhost/interpal/apps/planes/payment-results.php',
        'callback_url'      => 'http://localhost/interpal/apps/planes/tilopay-webhook.php',
        
        // Return Data (Optional) - Data you want to receive back
        'returnData'        => base64_encode(json_encode([
            'user_id' => 123,
            'plan' => 'premium',
            'timestamp' => time()
        ]))
    ]);

    // Redirect to TiloPay payment page
    $paymentUrl = $payment['url'] ?? $payment['payment_url'] ?? null;
    
    if ($paymentUrl) {
        header('Location: ' . $paymentUrl);
        exit;
    } else {
        throw new Exception('No payment URL received from TiloPay');
    }
    
} catch (Exception $e) {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error - TiloPay</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card border-danger">
                        <div class="card-header bg-danger text-white">
                            <h4 class="mb-0">❌ Error al crear el pago</h4>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-danger">
                                <strong>Mensaje:</strong><br>
                                <?= htmlspecialchars($e->getMessage()) ?>
                            </div>
                            
                            <h5>Detalles Técnicos:</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th>Archivo:</th>
                                    <td><?= $e->getFile() ?></td>
                                </tr>
                                <tr>
                                    <th>Línea:</th>
                                    <td><?= $e->getLine() ?></td>
                                </tr>
                            </table>
                            
                            <h5>Configuración:</h5>
                            <pre class="bg-light p-3">Endpoint: https://app.tilopay.com/api/v1
Login: https://app.tilopay.com/api/v1/login
Payment: https://app.tilopay.com/api/v1/processPayment
API User: fjyl14
API Key: 7522-8987-4366-9416-5242</pre>
                            
                            <h5>Campos Requeridos por TiloPay:</h5>
                            <ul>
                                <li>✅ amount (monto)</li>
                                <li>✅ currency (moneda ISO)</li>
                                <li>✅ billToFirstName (nombre)</li>
                                <li>✅ billToLastName (apellidos)</li>
                                <li>✅ billToAddress (dirección)</li>
                                <li>✅ billToCity (ciudad)</li>
                                <li>✅ billToState (estado ISO)</li>
                                <li>✅ billToZipPostCode (código postal)</li>
                                <li>✅ billToCountry (país ISO Alpha-2)</li>
                                <li>✅ billToTelephone (teléfono)</li>
                                <li>✅ billToEmail (email)</li>
                                <li>✅ orderNumber (número de orden)</li>
                                <li>✅ capture (1 o 0)</li>
                                <li>✅ platform (nombre plataforma)</li>
                            </ul>
                            
                            <a href="javascript:history.back()" class="btn btn-secondary">← Volver</a>
                            <a href="test-tilopay-login.php" class="btn btn-primary">Probar Credenciales</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
}
