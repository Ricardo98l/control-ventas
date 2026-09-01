<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-slate-800 leading-tight tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                {{ __('Estadísticas e Historial') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors">← Volver al Sistema</a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- SECCIÓN DE GRÁFICOS -->
            <div class="flex flex-col md:flex-row gap-6">
                
                <!-- Gráfico de Barras: Últimos 7 Días -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 w-full md:w-2/3">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Volumen de Cargas (Últimos 7 Días)</h3>
                    <div class="relative h-64 w-full">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>

                <!-- Gráfico de Dona: Porcentaje por Canal -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 w-full md:w-1/3">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Distribución por Ruta (Este Mes)</h3>
                    <div class="relative h-64 w-full flex justify-center">
                        <canvas id="doughnutChart"></canvas>
                    </div>
                </div>

            </div>

            <!-- SECCIÓN DEL HISTORIAL COMPLETO -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                <h3 class="text-xl font-extrabold text-slate-800 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Historial Completo de Cargas
                </h3>

                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-800 text-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Ruta / Canal</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Producto</th>
                                <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wider">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse($allSales as $sale)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-3 whitespace-nowrap text-sm font-semibold text-slate-900">{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm font-bold text-indigo-700">{{ $sale->channel->name }}</td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm font-bold text-slate-700">{{ $sale->product->name }} <span class="text-slate-400 font-normal">{{ $sale->product->size }}</span></td>
                                    <td class="px-6 py-3 whitespace-nowrap text-center">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">{{ $sale->quantity }} Cajas</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-500 font-medium">No hay registros en el historial.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación Nativa de Laravel (ya viene estilizada con Tailwind) -->
                <div class="mt-6">
                    {{ $allSales->links() }}
                </div>

            </div>

        </div>
    </div>

    <!-- Script de Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gráfico de Barras
            const barCtx = document.getElementById('barChart').getContext('2d');
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($diasFormateados) !!},
                    datasets: [{
                        label: 'Cajas Físicas Movidas',
                        data: {!! json_encode($totalesPorDia) !!},
                        backgroundColor: '#6366f1', // Indigo-500
                        borderRadius: 6,
                        barThickness: 20
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, grid: { borderDash: [4, 4] } } }
                }
            });

            // Gráfico de Dona
            const donutCtx = document.getElementById('doughnutChart').getContext('2d');
            new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($nombresCanales) !!},
                    datasets: [{
                        data: {!! json_encode($totalesPorCanal) !!},
                        backgroundColor: ['#10b981', '#6366f1', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } },
                    cutout: '70%'
                }
            });
        });
    </script>
</x-app-layout>