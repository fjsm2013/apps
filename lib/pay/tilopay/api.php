<?php

class TiloPay
{
    private string $apiUser;
    private string $apiPassword;
    private string $apiKey;
    private string $baseUrl;
    private ?string $token = null;

    public function __construct(
        string $apiUser,
        string $apiPassword,
        string $apiKey,
        bool $sandbox = true
    ) {
        $this->apiUser = $apiUser;
        $this->apiPassword = $apiPassword;
        $this->apiKey = $apiKey;
        
        // TiloPay uses app.tilopay.com for both sandbox and production
        // Sandbox is controlled by the account type, not the URL
        $this->baseUrl = 'https://app.tilopay.com/api/v1';
    }

    /**
     * Get authentication token
     */
    private function getToken(): string
    {
        if ($this->token !== null) {
            return $this->token;
        }

        $payload = [
            'api_user' => $this->apiUser,
            'api_password' => $this->apiPassword
        ];

        $ch = curl_init($this->baseUrl . '/login');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CONNECTTIMEOUT => 10
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        // Check for cURL errors
        if ($curlError) {
            throw new Exception('TiloPay cURL error (login): ' . $curlError);
        }

        // Check for empty response
        if (empty($response)) {
            throw new Exception('TiloPay error: Empty response from login API (HTTP ' . $httpCode . ')');
        }

        $decoded = json_decode($response, true);

        // Check for JSON decode errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('TiloPay error: Invalid JSON response from login - ' . json_last_error_msg() . ' | Response: ' . substr($response, 0, 500));
        }

        // Check for token in response
        if (!isset($decoded['token'])) {
            $errorMsg = isset($decoded['message']) ? $decoded['message'] : 'No token in response';
            throw new Exception('TiloPay login error: ' . $errorMsg . ' | Response: ' . json_encode($decoded));
        }

        $this->token = $decoded['token'];
        return $this->token;
    }

    /**
     * Create a payment with all required TiloPay fields
     */
    public function createPayment(array $data): array
    {
        $token = $this->getToken();

        $payload = [
            // API Key (Required)
            "key"               => $this->apiKey,
            
            // Amount and Currency (Required)
            "amount"            => $data['amount'],
            "currency"          => $data['currency'],
            
            // Order Information (Required)
            "orderNumber"       => $data['orderNumber'],
            "capture"           => $data['capture'] ?? 1, // 1 = Capture and authorize, 0 = Authorize only
            
            // Customer Billing Information (Required)
            "billToFirstName"   => $data['billToFirstName'],
            "billToLastName"    => $data['billToLastName'],
            "billToAddress"     => $data['billToAddress'],
            "billToAddress2"    => $data['billToAddress2'] ?? '',
            "billToCity"        => $data['billToCity'],
            "billToState"       => $data['billToState'],
            "billToZipPostCode" => $data['billToZipPostCode'],
            "billToCountry"     => $data['billToCountry'],
            "billToTelephone"   => $data['billToTelephone'],
            "billToEmail"       => $data['billToEmail'],
            
            // Platform (Required)
            "platform"          => $data['platform'] ?? 'Custom Integration',
        ];
        
        // Optional fields
        if (isset($data['description'])) {
            $payload['description'] = $data['description'];
        }
        
        if (isset($data['return_url'])) {
            $payload['redirect'] = $data['return_url'];
        }
        
        if (isset($data['callback_url'])) {
            $payload['callback_url'] = $data['callback_url'];
        }
        
        if (isset($data['returnData'])) {
            $payload['returnData'] = $data['returnData'];
        }

        $ch = curl_init($this->baseUrl . '/processPayment');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ],
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CONNECTTIMEOUT => 10
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        // Check for cURL errors
        if ($curlError) {
            throw new Exception('TiloPay cURL error: ' . $curlError);
        }

        // Check for empty response
        if (empty($response)) {
            throw new Exception('TiloPay error: Empty response from API (HTTP ' . $httpCode . ')');
        }

        $decoded = json_decode($response, true);

        // Check for JSON decode errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('TiloPay error: Invalid JSON response - ' . json_last_error_msg() . ' | Response: ' . substr($response, 0, 500));
        }

        // Check for API errors
        if (isset($decoded['error'])) {
            $errorMsg = isset($decoded['message']) ? $decoded['message'] : 'Unknown error';
            throw new Exception('TiloPay API error: ' . $errorMsg . ' | Full response: ' . json_encode($decoded));
        }

        // Check for payment URL (could be 'url' or 'payment_url')
        if (!isset($decoded['url']) && !isset($decoded['payment_url'])) {
            throw new Exception('TiloPay error: No payment URL in response (HTTP ' . $httpCode . ') | Response: ' . json_encode($decoded));
        }

        return $decoded;
    }
}
