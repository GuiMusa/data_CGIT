<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class EmberService
{
    public function getCarbonIntensity(string $countryCode): ?float
    {
        $cacheKey = "ember_carbon_intensity_{$countryCode}";

        return Cache::remember($cacheKey, 604800, function () use ($countryCode) {
            try {
                $response = Http::withoutVerifying()
                    ->timeout(10)
                    ->get('https://api.ember-energy.org/v1/carbon-intensity/yearly', [
                        'entity_code' => $countryCode,
                        'api_key'    => config('services.ember.key'),
                    ]);

                if ($response->successful()) {
                    $data = $response->json();

                    // On vérifie qu'on a bien des données
                    if (!empty($data['data']) && is_array($data['data'])) {
                        // L'API renvoie toutes les années (2000 → 2025)
                        // On récupère la dernière entrée = l'année la plus récente
                        $latestEntry = end($data['data']);

                        // Le champ correct est 'emissions_intensity_gco2_per_kwh'
                        return $latestEntry['emissions_intensity_gco2_per_kwh'] ?? null;
                    }

                    Log::warning('Aucune donnée Ember retournée', ['body' => $response->body()]);
                    return null;
                }

                Log::error('Erreur API Ember', ['status' => $response->status(), 'body' => $response->body()]);
                return null;

            } catch (\Exception $e) {
                Log::error('Exception Ember : ' . $e->getMessage());
                return null;
            }
        });
    }
}