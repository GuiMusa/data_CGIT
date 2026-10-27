<?php

namespace App\Http\Controllers;

use App\Services\OpenMeteoService;
use App\Services\EmberService;
use App\Services\WorldBankService;

class DashboardController extends Controller
{
    public function index(
        OpenMeteoService $openMeteoService,
        EmberService $emberService,
        WorldBankService $worldBankService
    ) {
        // Coordonnées GPS
        $paris = ['lat' => 48.8566, 'lon' => 2.3522];
        $dakar = ['lat' => 14.7167, 'lon' => -17.4677];

        // --- Solaire (Open-Meteo) ---
        $dataParis = $openMeteoService->getSolarData($paris['lat'], $paris['lon']);
        $dataDakar = $openMeteoService->getSolarData($dakar['lat'], $dakar['lon']);

        // --- Carbone (Ember) ---
        $carbonFrance  = $emberService->getCarbonIntensity('FRA');
        $carbonSenegal = $emberService->getCarbonIntensity('SEN');

        // --- Internet (World Bank) ---
        $internetFrance  = $worldBankService->getInternetUsers('FRA');
        $internetSenegal = $worldBankService->getInternetUsers('SEN');

        // --- Préparation vue ---
        $chartData = [
            'paris' => [
                'labels' => array_slice($dataParis['hourly']['time'] ?? [], 0, 24),
                'values' => array_slice($dataParis['hourly']['shortwave_radiation'] ?? [], 0, 24),
            ],
            'dakar' => [
                'labels' => array_slice($dataDakar['hourly']['time'] ?? [], 0, 24),
                'values' => array_slice($dataDakar['hourly']['shortwave_radiation'] ?? [], 0, 24),
            ],
            'carbon' => [
                'france'  => $carbonFrance,
                'senegal' => $carbonSenegal,
            ],
            'internet' => [
                'france'  => $internetFrance,
                'senegal' => $internetSenegal,
            ],
        ];

        return view('home', compact('chartData'));
    }
}