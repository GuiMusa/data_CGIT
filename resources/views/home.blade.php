<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Observatoire CGIT - Énergies & Tech</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-blue-700">CGIT Consulting</h1>
            <span class="text-sm font-medium text-gray-500">Observatoire International</span>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Hub de Données International</h2>
            <p class="text-gray-600">Analyse comparative des potentiels d'énergies renouvelables et de l'impact carbone pour les projets internationaux.</p>
        </div>

        <!-- Grille principale -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- Carte Martinique -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                <h3 class="text-xl font-bold mb-4">🇲🇶 Martinique</h3>
                <div class="h-64">
                    <canvas id="chartMartinique"></canvas>
                </div>
                <p class="text-xs text-gray-500 mt-3">Rayonnement solaire (W/m²) — Prévisions 24h</p>
            </div>

            <!-- Carte Dakar -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-green-500">
                <h3 class="text-xl font-bold mb-4">🇸🇳 Dakar (Sénégal)</h3>
                <div class="h-64">
                    <canvas id="chartDakar"></canvas>
                </div>
                <p class="text-xs text-gray-500 mt-3">Rayonnement solaire (W/m²) — Prévisions 24h</p>
            </div>

            <!-- Carte Carbone (Pleine largeur) -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-yellow-500 md:col-span-2">
                <h3 class="text-xl font-bold mb-2">🌍 Intensité Carbone du Réseau Électrique</h3>
                <p class="text-sm text-gray-500 mb-6">Indicateur clé pour l'implantation de Data Centers verts (données annuelles — Source : Ember).</p>

                <div class="grid grid-cols-2 gap-8 text-center">
                    <!-- France -->
                    <div>
                        <span class="block text-5xl font-extrabold text-gray-800">
                            {{ $chartData['carbon']['france'] !== null ? round($chartData['carbon']['france']) : 'N/A' }}
                        </span>
                        <span class="text-lg font-semibold text-gray-600">gCO₂/kWh</span>
                        <p class="text-sm text-gray-500 mt-2">🇫🇷 France</p>
                    </div>

                    <!-- Sénégal -->
                    <div>
                        <span class="block text-5xl font-extrabold text-gray-800">
                            {{ $chartData['carbon']['senegal'] !== null ? round($chartData['carbon']['senegal']) : 'N/A' }}
                        </span>
                        <span class="text-lg font-semibold text-gray-600">gCO₂/kWh</span>
                        <p class="text-sm text-gray-500 mt-2">🇸🇳 Sénégal</p>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script>
        const chartData = @json($chartData);

        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'W/m² (Ensoleillement)' }
                }
            },
            plugins: { legend: { display: false } }
        };

        // Graphique Martinique
        new Chart(document.getElementById('chartMartinique').getContext('2d'), {
            type: 'line',
            data: {
                labels: chartData.martinique.labels.map(t => t.split('T')[1]),
                datasets: [{
                    label: 'Rayonnement solaire',
                    data: chartData.martinique.values,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: commonOptions
        });

        // Graphique Dakar
        new Chart(document.getElementById('chartDakar').getContext('2d'), {
            type: 'line',
            data: {
                labels: chartData.dakar.labels.map(t => t.split('T')[1]),
                datasets: [{
                    label: 'Rayonnement solaire',
                    data: chartData.dakar.values,
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: commonOptions
        });
    </script>

</body>
</html>