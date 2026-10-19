<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class LoginAlertService
{
    public function __construct(private IpLocationService $ipLocationService)
    {
    }

    public function send(Request $request, string $outcome, string $account): void
    {
        $recipient = config('services.login_alert_email');
        if (!is_string($recipient) || !filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            Log::error('Login alert email recipient is not configured with a valid address.');

            return;
        }

        $ip = $request->ip() ?: 'unknown';
        $rateLimitKey = 'login-alert:'.$outcome.':'.hash('sha256', $ip);

        RateLimiter::attempt($rateLimitKey, 1, function () use ($recipient, $outcome, $account, $ip): void {
            $location = $this->locationFor($ip);
            $isSuccess = $outcome === 'success';
            $event = $isSuccess ? 'Successful login' : 'Failed login attempt';
            $subject = $event.' alert';
            $body = implode("\n", [
                'Event: '.$event,
                'Account: '.$account,
                'Time: '.now()->toDateTimeString().' ('.config('app.timezone').')',
                'IP address: '.$ip,
                'Location: '.$location,
            ]);

            $headers = [
                'MIME-Version: 1.0',
                'Content-Type: text/plain; charset=UTF-8',
            ];
            $sender = config('mail.from.address');
            if (is_string($sender) && filter_var($sender, FILTER_VALIDATE_EMAIL)) {
                $headers[] = 'From: '.$sender;
            }

            if (!function_exists('mail')) {
                Log::error('PHP mail() is unavailable; login alert was not sent.', [
                    'event' => $event,
                    'ip_address' => $ip,
                ]);

                return;
            }

            if (!mail($recipient, $subject, $body, implode("\r\n", $headers))) {
                Log::error('PHP mail() could not send a login alert.', [
                    'event' => $event,
                    'ip_address' => $ip,
                ]);
            }
        }, 300);
    }

    private function locationFor(string $ip): string
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return 'Unavailable';
        }

        $location = $this->ipLocationService->fetch($ip);
        if (!$location) {
            return 'Unavailable';
        }

        $parts = array_filter(
            [$location['city'] ?? null, $location['region'] ?? null, $location['country'] ?? null],
            fn ($part) => is_string($part) && trim($part) !== ''
        );

        return $parts ? implode(', ', $parts) : 'Unavailable';
    }
}
