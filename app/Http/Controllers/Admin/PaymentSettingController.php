<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class PaymentSettingController extends Controller
{
    public function index() {
        $settings = [
            'razorpay_enabled' => AppSetting::get('razorpay_enabled', '1'),
            'razorpay_key_id'  => AppSetting::get('razorpay_key_id', ''),
            'platform_fee'     => AppSetting::get('platform_fee', '1'),
        ];
        return view('admin.payment.settings', compact('settings'));
    }

    public function update(Request $request) {
        AppSetting::set('razorpay_enabled', $request->has('razorpay_enabled') ? '1' : '0');
        AppSetting::set('razorpay_key_id',  $request->razorpay_key_id ?? '');
        AppSetting::set('platform_fee',     $request->platform_fee ?? '1');

        // Save secret to .env
        if ($request->razorpay_key_secret) {
            $this->setEnv('RAZORPAY_KEY_SECRET', $request->razorpay_key_secret);
        }

        return back()->with('success', 'Payment settings updated successfully!');
    }

    public function toggle(Request $request) {
        $current = AppSetting::get('razorpay_enabled', '1');
        AppSetting::set('razorpay_enabled', $current === '1' ? '0' : '1');
        return response()->json(['status' => 200, 'enabled' => AppSetting::get('razorpay_enabled') === '1']);
    }

    private function setEnv(string $key, string $value): void {
        $path    = base_path('.env');
        $content = file_get_contents($path);
        if (str_contains($content, "{$key}=")) {
            $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
        } else {
            $content .= "\n{$key}={$value}";
        }
        file_put_contents($path, $content);
    }
}
