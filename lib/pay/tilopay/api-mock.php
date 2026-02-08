<?php

/**
 * TiloPay Mock API for Testing
 * Use this when you can't connect to the real TiloPay API
 */
class TiloPayMock
{
    private string $apiKey;
    private string $merchantId;
    private string $secretKey;
    private string $endpoint;

    public function __construct(
        string $apiKey,
        string $merchantId,
        string $secretKey,
        bool $sandbox = true
    ) {
        $this->apiKey     = $apiKey;
        $this->merchantId = $merchantId;
        $this->secretKey  = $secretKey;

        $this->endpoint = $sandbox
            ? 'https://sandbox.tilopay.com/api/v1/payment'
            : 'https://app.tilopay.com/api/v1/payment';
    }

    public function createPayment(array $data): array
    {
        // Generate a mock payment URL for testing
        $mockPaymentId = uniqid('mock_payment_');
        
        // Simulate successful payment creation
        return [
            'success' => true,
            'payment_id' => $mockPaymentId,
            'payment_url' => 'http://localhost/interpal/apps/planes/mock-payment.php?id=' . $mockPaymentId . '&amount=' . $data['amount'],
            'order_id' => $data['order_id'],
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'status' => 'pending',
            'mock' => true,
            'message' => 'This is a MOCK payment for testing purposes'
        ];
    }

    private function sign(array $data): string
    {
        return hash_hmac('sha256', json_encode($data), $this->secretKey);
    }
}
