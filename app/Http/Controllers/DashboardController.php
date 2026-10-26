<?php

namespace App\Http\Controllers;

use App\Services\OpenMeteoService;

class DashboardController extends Controller
{
    public function index(OpenMeteoService $openMeteoService)
    {
        $fortDeFrance = ['lat' => 14.6161, 'lon' => -61.0588];
        $dakar = ['lat' => 14.7167, 'lon' => -17.4677];

        $dataMartinique = $openMeteoService->getSolarData($fortDeFrance['lat'], $fortDeFrance['lon']);
        $dataDakar = $openMeteoService->getSolarData($dakar['lat'], $dakar['lon']);

        // On prépare les données pour Chart.js (on prend les 24 premières heures)
        $chartData = [
            'martinique' => [
                'labels' => array_slice($dataMartinique['hourly']['time'] ?? [], 0, 24),
                'values' => array_slice($dataMartinique['hourly']['shortwave_radiation'] ?? [], 0, 24),
            ],
            'dakar' => [
                'labels' => array_slice($dataDakar['hourly']['time'] ?? [], 0, 24),
                'values' => array_slice($dataDakar['hourly']['shortwave_radiation'] ?? [], 0, 24),
            ],
        ];

        return view('home', compact('chartData'));
    }
}