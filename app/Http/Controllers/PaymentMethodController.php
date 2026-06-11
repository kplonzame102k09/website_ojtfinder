<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentMethodController extends Controller
{
    public function createGcash(Request $request)
    {
        $secretKey = config('services.paymongo.secret');

        /**
         * STEP 1: Create Payment Intent
         */
        $paymentIntent = Http::withBasicAuth($secretKey, '')
            ->post('https://api.paymongo.com/v1/payment_intents', [
                'data' => [
                    'attributes' => [
                        'amount' => 15000, // ₱150.00 (centavos)
                        'payment_method_allowed' => ['gcash'],
                        'payment_method_options' => [
                            'gcash' => [
                                'redirect' => [
                                    'success' => route('home'),
                                    'failed'  => route('home'),
                                ],
                            ],
                        ],
                        'currency' => 'PHP',
                        'description' => 'Company Pro Plan Subscription',
                    ],
                ],
            ])
            ->json();

        if (!isset($paymentIntent['data']['id'])) {
            return response()->json(['error' => 'Failed to create payment intent'], 500);
        }

        $paymentIntentId = $paymentIntent['data']['id'];

        /**
         * STEP 2: Create GCash Payment Method
         */
        $paymentMethod = Http::withBasicAuth($secretKey, '')
            ->post('https://api.paymongo.com/v1/payment_methods', [
                'data' => [
                    'attributes' => [
                        'type' => 'gcash',
                    ],
                ],
            ])
            ->json();

        if (!isset($paymentMethod['data']['id'])) {
            return response()->json(['error' => 'Failed to create payment method'], 500);
        }

        /**
         * STEP 3: Attach Payment Method to Payment Intent
         */
        $attach = Http::withBasicAuth($secretKey, '')
            ->post("https://api.paymongo.com/v1/payment_intents/{$paymentIntentId}/attach", [
                'data' => [
                    'attributes' => [
                        'payment_method' => $paymentMethod['data']['id'],
                        'return_url' => route('home'),
                    ],
                ],
            ])
            ->json();

        if (!isset($attach['data']['attributes']['next_action']['redirect']['url'])) {
            return response()->json(['error' => 'Scan To pay'], 500);
        }

        return response()->json([
            'checkout_url' =>
                $attach['data']['attributes']['next_action']['redirect']['url'],
        ]);
    }
}
