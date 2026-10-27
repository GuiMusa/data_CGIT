<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WorldBankService
{
    public function getInternetUsers(string $countryCode): ?float
    {
        // Cache de 7 jours (les données de la Banque Mondiale sont annuelles)
        $cacheKey = "worldbank_internet_{$countryCode}";

        return Cache::remember($cacheKey, 604800, function () use ($countryCode) {
            try {
                // L'API de la Banque Mondiale ne nécessite pas de clé
                // On demande les données les plus récentes avec mrnev=1
                $response = Http::withoutVerifying()
                    ->timeout(15)
                    ->get("https://api.worldbank.org/v2/country/{$countryCode}/indicator/IT.NET.USER.ZS", [
                        'format' => 'json',
                        'mrnev'  => 1, // Most Recent Non-Empty Value
                    ]);

                if ($response->successful()) {
                    $data = $response->json();

                    // La réponse est un tableau : [metadata, data]
                    // Les données sont dans $data[1]
                    if (isset($data[1]) && is_array($data[1]) && count($data[1]) > 0) {
                        return $data[1][0]['value'] ?? null;
                    }

                    Log::warning('Aucune donnée World Bank retournée', ['country' => $countryCode]);
                    return null;
                }

                Log::error('Erreur API World Bank', [
                    'status' => $response->status(),
                    'country' => $countryCode
                ]);
                return null;

            } catch (\Exception $e) {
                Log::error('Exception World Bank : ' . $e->getMessage());
                return null;
            }
        });
    }
}