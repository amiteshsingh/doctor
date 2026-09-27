<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class IpLocationService
{
    private array $privateRanges = ['127.', '10.', '192.168.', '::1'];

    public function fetch(string $ip): ?array
    {
        // Private IP check
        foreach ($this->privateRanges as $range) {
            if (str_starts_with($ip, $range) || $ip === 'localhost') {
                return null;
            }
        }

        try {
            $response = Http::timeout(5)->get("http://ip-api.com/json/{$ip}");

            if (!$response->successful()) {
                return null;
            }

            $data = $response->json();

            if (!isset($data['status']) || $data['status'] !== 'success') {
                return null;
            }

            $lat = isset($data['lat']) ? (float) $data['lat'] : null;
            $lng = isset($data['lon']) ? (float) $data['lon'] : null;

            // Validate ranges
            if ($lat !== null && ($lat < -90 || $lat > 90)) {
                $lat = null;
            }
            if ($lng !== null && ($lng < -180 || $lng > 180)) {
                $lng = null;
            }

            return [
                'city'    => $data['city']       ?? null,
                'region'  => $data['regionName'] ?? null,
                'country' => $data['country']    ?? null,
                'isp'     => $data['isp']        ?? null,
                'lat'     => $lat,
                'lng'     => $lng,
            ];

        } catch (\Throwable $e) {
            return null;
        }
    }
}
