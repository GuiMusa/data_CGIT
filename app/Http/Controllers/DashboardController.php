<?php

namespace App\Http\Controllers;

use App\Services\OpenMeteoService;
use App\Services\EmberService;

class DashboardController extends Controller
{
    public function index(OpenMeteoService $openMeteoService, EmberService $emberService)
    {
        // Coordonnées GPS
        $fortDeFrance = ['lat' => 14.6161, 'lon' => -61.0588];
        $dakar        = ['lat' => 14.7167, 'lon' => -17.4677];

        // --- Données Solaire (Open-Meteo) ---
        $dataMartinique = $openMeteoService->getSolarData($fortDeFrance['lat'], $fortDeFrance['lon']);
        $dataDakar      = $openMeteoService->getSolarData($dakar['lat'], $dakar['lon']);

        // --- Données Carbone (Ember) ---
        $carbonFrance  = $emberService->getCarbonIntensity('FRA');
        $carbonSenegal = $emberService->getCarbonIntensity('SEN');

        // --- Préparation pour la vue ---
        $chartData = [
            'martinique' => [
                'labels' => array_slice($dataMartinique['hourly']['time'] ?? [], 0, 24),
                'values' => array_slice($dataMartinique['hourly']['shortwave_radiation'] ?? [], 0, 24),
            ],
            'dakar' => [
                'labels' => array_slice($dataDakar['hourly']['time'] ?? [], 0, 24),
                'values' => array_slice($dataDakar['hourly']['shortwave_radiation'] ?? [], 0, 24),
            ],
            'carbon' => [
                'france'  => $carbonFrance,
                'senegal' => $carbonSenegal,
            ],
        ];

        return view('home', compact('chartData'));
    }
}