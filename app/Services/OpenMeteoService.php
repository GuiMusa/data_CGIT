<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OpenMeteoService
{
    public function getSolarData(float $latitude, float $longitude): ?array
    {
        // Clé de cache unique basée sur les coordonnées
        $cacheKey = "openmeteo_{$latitude}_{$longitude}";

        // On stocke la réponse pendant 3 heures (10800 secondes)
        return Cache::remember($cacheKey, 10800, function () use ($latitude, $longitude) {
        
            try {
                $response = Http::withoutVerifying()->timeout(10)->get('https://api.open-meteo.com/v1/forecast', [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'hourly' => 'temperature_2m,shortwave_radiation',
                    'timezone' => 'auto',
                    'forecast_days' => 3,
                ]);

                if ($response->successful()) {
                    return $response->json();
                }

                Log::error('Erreur API Open-Meteo', ['status' => $response->status()]);
                return null;

            } catch (\Exception $e) {
                Log::error('Exception API Open-Meteo : ' . $e->getMessage());
                return null;
            }
        });
    }
}