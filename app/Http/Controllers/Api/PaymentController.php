<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // Get payment settings (is payment enabled + key)
    public function settings(Request $request) {
        return response()->json([
            'status'           => 200,
            'razorpay_enabled' => AppSetting::get('razorpay_enabled', '1') === '1',
            'razorpay_key_id'  => AppSetting::get('razorpay_key_id', 'rzp_test_TG7oWaY3UZvOZo'),
            'platform_fee'     => (int) AppSetting::get('platform_fee', '1'),
        ]);
    }

    // Create Razorpay order
    public function createOrder(Request $request) {
        $request->validate(['amount' => 'required|integer|min:1']);

        $keyId     = AppSetting::get('razorpay_key_id', 'rzp_test_TG7oWaY3UZvOZo');
        $keySecret = env('RAZORPAY_KEY_SECRET', '');

        $amountPaise = $request->amount * 100; // convert to paise

        $response = \Illuminate\Support\Facades\Http::withBasicAuth($keyId, $keySecret)
            ->post('https://api.razorpay.com/v1/orders', [
                'amount'   => $amountPaise,
                'currency' => 'INR',
                'receipt'  => 'rcpt_' . time(),
            ]);

        if (!$response->successful()) {
            return response()->json(['status' => 500, 'message' => 'Order create failed'], 500);
        }

        return response()->json([
            'status'   => 200,
            'order_id' => $response->json('id'),
            'amount'   => $amountPaise,
            'currency' => 'INR',
            'key_id'   => $keyId,
        ]);
    }

    // Verify payment signature
    public function verifyPayment(Request $request) {
        $request->validate([
            'razorpay_order_id'   => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature'  => 'required|string',
        ]);

        $keySecret = env('RAZORPAY_KEY_SECRET', '');
        $body      = $request->razorpay_order_id . '|' . $request->razorpay_payment_id;
        $expected  = hash_hmac('sha256', $body, $keySecret);

        if ($expected !== $request->razorpay_signature) {
            return response()->json(['status' => 400, 'message' => 'Payment verification failed'], 400);
        }

        return response()->json(['status' => 200, 'message' => 'Payment verified', 'payment_id' => $request->razorpay_payment_id]);
    }
}
