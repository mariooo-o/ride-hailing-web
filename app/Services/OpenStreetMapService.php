<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenStreetMapService
{
    public function searchLocation(string $place): ?array
    {
        try {
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->withHeaders(['User-Agent' => 'RideHailingLaravel/1.0'])
                ->get('https://nominatim.openstreetmap.org/search', [
                    'q'      => $place,
                    'format' => 'json',
                    'limit'  => 1,
                    'countrycodes' => 'id',
                    'viewbox'      => '106.6,-6.4,107.1,-5.9', 
                    'bounded'      => 1,              
                ]);

            if ($response->failed()) {
                return null;
            }

            $data = $response->json();

            if (empty($data)) {
                return null;
            }

            return [
                'lat' => (float) $data[0]['lat'],
                'lon' => (float) $data[0]['lon'],
            ];

        } catch (\Exception $e) {
            Log::error('Nominatim error: ' . $e->getMessage());
            return null;
        }
    }

    public function getDistance(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
        $earthRadius = 6371;

        $latDiff = deg2rad($lat2 - $lat1);
        $lonDiff = deg2rad($lon2 - $lon1);

        $a = sin($latDiff / 2) * sin($latDiff / 2)
            + cos(deg2rad($lat1))
            * cos(deg2rad($lat2))
            * sin($lonDiff / 2)
            * sin($lonDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function getRoadDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
{
    try {
        $response = Http::timeout(15)
            ->withOptions(['verify' => false])
            ->get("http://router.project-osrm.org/route/v1/driving/{$lon1},{$lat1};{$lon2},{$lat2}", [
                'overview' => 'false',
            ]);

        $data = $response->json();

        if (isset($data['routes'][0]['distance'])) {
            // OSRM return meter, bagi 1000 jadi km
            return $data['routes'][0]['distance'] / 1000;
        }

        return 0;

    } catch (\Exception $e) {
        \Log::error('OSRM error: ' . $e->getMessage());
        // Fallback ke Haversine kalau OSRM gagal
        return $this->getDistance($lat1, $lon1, $lat2, $lon2);
    }
}
}