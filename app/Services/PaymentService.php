<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Srmklive\PayPal\Services\PayPal;
use Illuminate\Support\Facades\Log;
use Exception;

class PaymentService
{
    private array $stripeConfig;
    private array $paypalConfig;

    public function __construct()
    {
        $this->stripeConfig = [
            'key' => config('services.stripe.key'),
            'secret' => config('services.stripe.secret'),
            'webhook_secret' => config('services.stripe.webhook_secret'),
        ];

        $this->paypalConfig = [
            'mode' => config('services.paypal.mode', 'sandbox'),
            'client_id' => config('services.paypal.client_id'),
            'client_secret' => config('services.paypal.client_secret'),
            'currency' => config('services.paypal.currency', 'USD'),
        ];
    }

    public function createStripePaymentIntent(float $amount, string $currency = 'usd'): ?array
    {
        try {
            if (empty($this->stripeConfig['key']) || empty($this->stripeConfig['secret'])) {
                Log::warning('Stripe credentials not configured');
                return null;
            }

            Stripe::setApiKey($this->stripeConfig['secret']);

            $paymentIntent = PaymentIntent::create([
                'amount' => (int)($amount * 100),
                'currency' => $currency,
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            return [
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
            ];
        } catch (Exception $e) {
            Log::error('Stripe payment intent creation failed', [
                'error' => $e->getMessage(),
                'amount' => $amount,
            ]);
            return null;
        }
    }

    public function createPaypalOrder(float $amount, string $currency = 'USD'): ?array
    {
        try {
            if (empty($this->paypalConfig['client_id']) || empty($this->paypalConfig['client_secret'])) {
                Log::warning('PayPal credentials not configured');
                return null;
            }

            $provider = new PayPal();
            $provider->setApiCredentials($this->paypalConfig);
            $provider->getAccessToken();

            $order = $provider->createOrder([
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => $currency,
                            'value' => number_format($amount, 2, '.', ''),
                        ],
                    ],
                ],
            ]);

            if (isset($order['id'])) {
                return [
                    'order_id' => $order['id'],
                    'approval_link' => collect($order['links'])->firstWhere('rel', 'approve')['href'] ?? null,
                ];
            }

            return null;
        } catch (Exception $e) {
            Log::error('PayPal order creation failed', [
                'error' => $e->getMessage(),
                'amount' => $amount,
            ]);
            return null;
        }
    }

    public function capturePaypalOrder(string $paypalOrderId): bool
    {
        try {
            $provider = new PayPal();
            $provider->setApiCredentials($this->paypalConfig);
            $provider->getAccessToken();

            $result = $provider->capturePaymentOrder($paypalOrderId);

            return isset($result['status']) && $result['status'] === 'COMPLETED';
        } catch (Exception $e) {
            Log::error('PayPal payment capture failed', [
                'error' => $e->getMessage(),
                'order_id' => $paypalOrderId,
            ]);
            return false;
        }
    }

    public function isStripeConfigured(): bool
    {
        return !empty($this->stripeConfig['key']) && !empty($this->stripeConfig['secret']);
    }

    public function isPaypalConfigured(): bool
    {
        return !empty($this->paypalConfig['client_id']) && !empty($this->paypalConfig['client_secret']);
    }
}