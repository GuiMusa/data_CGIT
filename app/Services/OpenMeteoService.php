<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenMeteoService
{
  /**
     * Récupère les données météo et solaires pour une localisation.
     */
    public function getSolarData(float $latitude, float $longitude): ?array
{
    try {
        $response = Http::withoutVerifying()->timeout(10)->get('https://api.open-meteo.com/v1/forecast', [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'hourly' => 'temperature_2m,shortwave_radiation',
            'timezone' => 'auto',
            'forecast_days' => 3,
        ]);

        // --- DEBUG TEMPORAIRE ---
        if (!$response->successful()) {
            dd([
                'status' => $response->status(),
                'body' => $response->body(),
                'url_appelee' => $response->effectiveUri(),
            ]);
        }
        // ------------------------

        return $response->json();

    } catch (\Exception $e) {
        dd([
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
        ]);
    }
}
}