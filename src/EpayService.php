<?php

namespace Sarojsardar\EsewaEpay;

use Illuminate\Support\Facades\Http;

class EpayService
{
    protected $merchantCode;
    protected $secretKey;
    protected $apiEndpoint;

    public function __construct()
    {
        $this->merchantCode = config('esewa.merchant_code');
        $this->secretKey = config('esewa.secret_key');
        $this->apiEndpoint = config('esewa.api_endpoint');
    }

    public function initiatePayment($amount, $transactionUuid, $productCode, $successUrl, $failureUrl)
    {
        $totalAmount = $amount; // Assuming total amount is just the amount for simplicity
        $taxAmount = 0; // Set your tax amount as needed
        $productServiceCharge = 0; // Set service charge as needed
        $productDeliveryCharge = 0; // Set delivery charge as needed

        $data = [
            'amount' => $amount,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'transaction_uuid' => $transactionUuid,
            'product_code' => $productCode,
            'product_service_charge' => $productServiceCharge,
            'product_delivery_charge' => $productDeliveryCharge,
            'success_url' => $successUrl,
            'failure_url' => $failureUrl,
            'signed_field_names' => 'total_amount,transaction_uuid,product_code',
        ];

        // Create a signature for the transaction
        $signature = $this->createSignature($data);
        $data['signature'] = $signature;

        // Return the form for redirecting to eSewa
        return view('esewa::payment-form', compact('data'));
    }

    private function createSignature($data)
    {
        // Create the signature based on the required fields
        return md5(implode('|', [
            $data['total_amount'],
            $data['transaction_uuid'],
            $data['product_code'],
            $this->secretKey,
        ]));
    }

    public function verifyTransaction($transactionId)
    {
        // Decode the Base64 encoded transaction ID
        $decodedData = json_decode(base64_decode($transactionId), true);

        // Check if decoding was successful and data is valid
        if (json_last_error() !== JSON_ERROR_NONE || !isset($decodedData['product_code'], $decodedData['total_amount'], $decodedData['transaction_uuid'])) {
            return [
                'status' => 'error',
                'message' => 'Invalid transaction ID format.',
            ];
        }

        // Prepare the data for verification
        $data = [
            'product_code' => $decodedData['product_code'],
            'total_amount' => $decodedData['total_amount'],
            'transaction_uuid' => $decodedData['transaction_uuid'],
        ];

        // Send a GET request to the eSewa API endpoint to verify the transaction
        try {
            $response = Http::get($this->apiEndpoint . '/transaction/status', $data);

            // Check if the response was successful
            if ($response->successful()) {
                return $response->json();
            } else {
                // Handle unsuccessful response
                return [
                    'status' => 'error',
                    'message' => 'Transaction verification failed. API responded with an error.',
                    'api_response' => $response->json(),
                ];
            }
        } catch (\Exception $e) {
            // Handle exceptions
            return [
                'status' => 'error',
                'message' => 'Transaction verification failed due to an exception.',
                'exception' => $e->getMessage(),
            ];
        }
    }
}
