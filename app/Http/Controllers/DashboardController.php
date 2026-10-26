<?php

namespace App\Http\Controllers;

use App\Services\OpenMeteoService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(OpenMeteoService $openMeteoService)
    {
        // Coordonnées GPS
        $fortDeFrance = ['lat' => 14.6161, 'lon' => -61.0588];
        $dakar = ['lat' => 14.7167, 'lon' => -17.4677];

        // On récupère les données
        $dataMartinique = $openMeteoService->getSolarData($fortDeFrance['lat'], $fortDeFrance['lon']);
        $dataDakar = $openMeteoService->getSolarData($dakar['lat'], $dakar['lon']);

        // Pour l'instant, on affiche les données brutes pour tester (dd = dump and die)
        dd([
            'Martinique' => $dataMartinique,
            'Dakar' => $dataDakar
        ]);

        // Plus tard, on retournera une vue :
        // return view('dashboard', compact('dataMartinique', 'dataDakar'));
    }
}
