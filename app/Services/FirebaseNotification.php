<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FirebaseNotification
{
    /**
     * Send FCM v1 notification
     */
    public static function send(string $fcmToken, string $title, string $body, array $data = []): bool
    {
        $accessToken = self::getAccessToken();
        if (!$accessToken || !$fcmToken) return false;

        $projectId = self::getProjectId();
        if (!$projectId) return false;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type'  => 'application/json',
            ])->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
                'message' => [
                    'token'        => $fcmToken,
                    'notification' => [
                        'title' => $title,
                        'body'  => $body,
                    ],
                    'data'    => array_map('strval', $data),
                    'android' => [
                        'priority'     => 'high',
                        'notification' => ['sound' => 'default'],
                    ],
                ],
            ]);

            if (!$response->successful()) {
                Log::error('FCM v1 Error: ' . $response->body());
            }

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('FCM v1 Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get OAuth2 access token from service account JSON (cached 55 min)
     */
    private static function getAccessToken(): ?string
    {
        return Cache::remember('fcm_access_token', 3300, function () {
            $credPath = storage_path('app/rogisewa-b3189-dfa65691786a.json');
            if (!file_exists($credPath)) {
                Log::error('Firebase service account JSON not found at: ' . $credPath);
                return null;
            }

            $creds = json_decode(file_get_contents($credPath), true);

            $now = time();
            $payload = [
                'iss'   => $creds['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud'   => 'https://oauth2.googleapis.com/token',
                'iat'   => $now,
                'exp'   => $now + 3600,
            ];

            $jwt = self::buildJwt($payload, $creds['private_key']);

            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt,
            ]);

            return $response->json('access_token');
        });
    }

    private static function getProjectId(): ?string
    {
        $credPath = storage_path('app/rogisewa-b3189-dfa65691786a.json');
        if (!file_exists($credPath)) return null;
        $creds = json_decode(file_get_contents($credPath), true);
        return $creds['project_id'] ?? null;
    }

    /**
     * Build JWT manually (no external library needed)
     */
    private static function buildJwt(array $payload, string $privateKey): string
    {
        $header  = self::base64url(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $payload = self::base64url(json_encode($payload));
        $data    = $header . '.' . $payload;

        openssl_sign($data, $signature, $privateKey, 'SHA256');

        return $data . '.' . self::base64url($signature);
    }

    private static function base64url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
