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
            <p class="text-gray-600">Comparatif Europe / Afrique de l'Ouest sur les énergies renouvelables, l'impact carbone et la maturité digitale.</p>
        </div>

        <div class="space-y-8">

            <!-- ============ CARTE 1 : SOLAIRE ============ -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-indigo-500">
                <h3 class="text-xl font-bold mb-2">☀️ Rayonnement Solaire (Prévisions 24h)</h3>
                <p class="text-sm text-gray-500 mb-6">Potentiel d'ensoleillement — Source : Open-Meteo</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-2">🇫🇷 Paris (France)</p>
                        <div class="h-48">
                            <canvas id="chartParis"></canvas>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-2">🇸🇳 Dakar (Sénégal)</p>
                        <div class="h-48">
                            <canvas id="chartDakar"></canvas>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-4">Unité : W/m²</p>
            </div>

            <!-- ============ CARTE 2 : CARBONE ============ -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-yellow-500">
                <h3 class="text-xl font-bold mb-2">🌍 Intensité Carbone du Réseau Électrique</h3>
                <p class="text-sm text-gray-500 mb-6">Indicateur clé pour l'implantation de Data Centers verts (Source : Ember)</p>

                <div class="grid grid-cols-2 gap-8 text-center">
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-4">🇫🇷 France</p>
                        <span class="block text-6xl font-extrabold text-gray-800">
                            {{ $chartData['carbon']['france'] !== null ? round($chartData['carbon']['france']) : 'N/A' }}
                        </span>
                        <span class="text-lg font-semibold text-gray-600">gCO₂/kWh</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-4">🇸🇳 Sénégal</p>
                        <span class="block text-6xl font-extrabold text-gray-800">
                            {{ $chartData['carbon']['senegal'] !== null ? round($chartData['carbon']['senegal']) : 'N/A' }}
                        </span>
                        <span class="text-lg font-semibold text-gray-600">gCO₂/kWh</span>
                    </div>
                </div>
            </div>

            <!-- ============ CARTE 3 : INTERNET ============ -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-purple-500">
                <h3 class="text-xl font-bold mb-2">🌐 Accès à Internet</h3>
                <p class="text-sm text-gray-500 mb-6">Indicateur TIC clé pour les projets de transformation digitale (Source : Banque Mondiale)</p>

                <div class="grid grid-cols-2 gap-8 text-center">
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-4">🇫🇷 France</p>
                        <span class="block text-6xl font-extrabold text-gray-800">
                            {{ $chartData['internet']['france'] !== null ? round($chartData['internet']['france'], 1) : 'N/A' }}%
                        </span>
                        <span class="text-lg font-semibold text-gray-600">de la population</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-4">🇸🇳 Sénégal</p>
                        <span class="block text-6xl font-extrabold text-gray-800">
                            {{ $chartData['internet']['senegal'] !== null ? round($chartData['internet']['senegal'], 1) : 'N/A' }}%
                        </span>
                        <span class="text-lg font-semibold text-gray-600">de la population</span>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script>
        const chartData = @json($chartData);

        function buildLineChart(id, data, color, bg) {
            new Chart(document.getElementById(id).getContext('2d'), {
                type: 'line',
                data: {
                    labels: data.labels.map(t => t.split('T')[1]),
                    datasets: [{
                        data: data.values,
                        borderColor: color,
                        backgroundColor: bg,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, title: { display: true, text: 'W/m²' } }
                    },
                    plugins: { legend: { display: false } }
                }
            });
        }

        buildLineChart('chartParis', chartData.paris, 'rgb(99, 102, 241)', 'rgba(99, 102, 241, 0.1)');
        buildLineChart('chartDakar', chartData.dakar, 'rgb(34, 197, 94)', 'rgba(34, 197, 94, 0.1)');
    </script>

</body>
</html>