# eSewa Payment Integration Package for Laravel

![License](https://img.shields.io/badge/license-MIT-blue.svg)

This package provides a simple and effective way to integrate eSewa payment processing into your Laravel application. It supports initiating payments and verifying transactions through the eSewa API.

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
  - [Set Up Environment Variables](#set-up-environment-variables)
  - [Using the Service](#using-the-service)
  - [Payment Form View](#payment-form-view)
  - [Defining Routes](#defining-routes)
- [Example Controller](#example-controller)
- [Testing the Package](#testing-the-package)
- [Contributing](#contributing)
- [License](#license)

## Features

- Easy integration with eSewa payment gateway.
- Supports payment initiation and transaction verification.
- Customizable payment form view.

## Requirements

- PHP 8.1 or higher
- Laravel 11.x

## Installation

1. **Install the package via Composer**:

   Run the following command in your Laravel project directory:

   ```bash
   composer require sarojsardar/esewa-epay:dev-master
Publish the configuration file:

After installation, publish the package configuration file using the following command:

bash
Copy code
php artisan vendor:publish --provider="Sarojsardar\EsewaEpay\EsewaEpayServiceProvider"
This command will create a configuration file at config/esewa.php.

Configuration
Add your eSewa merchant credentials to your .env file:

env
Copy code
ESEWA_MERCHANT_CODE=your_merchant_code
ESEWA_SECRET=your_secret_key
ESEWA_API_ENDPOINT=https://rc-epay.esewa.com.np/api/epay/main/v2
Replace your_merchant_code and your_secret_key with the appropriate values provided by eSewa.
For production, change the API endpoint to:
env
Copy code
ESEWA_API_ENDPOINT=https://epay.esewa.com.np/api/epay/main/v2
Usage
Set Up Environment Variables
Ensure that you have set up the environment variables as shown in the configuration section.

Using the Service
You can use the EpayService class in your controllers to handle payments.

Payment Form View
The package provides a view for the payment form. You can customize it as needed. The view is located at resources/views/vendor/esewa/payment-form.blade.php.

Defining Routes
Define routes in your web.php file for initiating and verifying payments:

php
Copy code
use App\Http\Controllers\PaymentController;

Route::get('/payment/initiate', [PaymentController::class, 'initiatePayment']);
Route::get('/payment/verify/{transactionId}', [PaymentController::class, 'verifyPayment']);
Example Controller
Create a controller named PaymentController:

php
Copy code
namespace App\Http\Controllers;

use Sarojsardar\EsewaEpay\EpayService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $epayService;

    public function __construct(EpayService $epayService)
    {
        $this->epayService = $epayService;
    }

    // Method to initiate payment
    public function initiatePayment(Request $request)
    {
        $amount = $request->input('amount', 100); // Default amount
        $transactionUuid = 'your-transaction-uuid'; // Generate a unique transaction UUID
        $productCode = 'EPAYTEST';
        $successUrl = 'https://your-success-url.com';
        $failureUrl = 'https://your-failure-url.com';

        return $this->epayService->initiatePayment($amount, $transactionUuid, $productCode, $successUrl, $failureUrl);
    }

    // Method to verify payment
    public function verifyPayment($transactionId)
    {
        $result = $this->epayService->verifyTransaction($transactionId);
        
        // Handle the result as needed
        return response()->json($result);
    }
}
Testing the Package
To test the package, you can use the following steps:

Run your Laravel application: Start your local server with:

bash
Copy code
php artisan serve
Initiate a payment: Open your browser and navigate to http://localhost:8000/payment/initiate. This should redirect you to the eSewa payment page.

Verify the payment: After completing the payment, you will be redirected to the success URL you specified. You can verify the transaction by calling the verifyPayment method with the transaction ID.
