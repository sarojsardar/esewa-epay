<?php

return [
    'merchant_code' => env('ESEWA_MERCHANT_CODE'),
    'secret_key' => env('ESEWA_SECRET'),
    'api_endpoint' => env('ESEWA_API_ENDPOINT', 'https://rc-epay.esewa.com.np/api/epay/main/v2'),
];
