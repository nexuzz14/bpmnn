<x-app-layout>
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Rekap Surat</h2>
            <p class="text-gray-500 mt-1">Laporan dan statistik persuratan</p>
        </div>
        <div class="flex items-center gap-3">
            <select class="py-2 pl-4 pr-10 bg-white border border-gray-200 rounded-lg focus:ring-[#055a40] focus:border-[#055a40] shadow-sm text-gray-700">
                <option>Tahun 2026</option>
                <option>Tahun 2025</option>
            </select>
            <a href="{{ route('admin.rekap.pdf', ['tahun' => $year ?? date('Y')]) }}" class="px-4 py-2 border border-[#055a40] text-[#055a40] rounded-lg text-sm font-medium hover:bg-green-50 flex items-center gap-2 shadow-sm transition-colors">
                Export PDF
            </a>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Surat Masuk -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <p class="text-sm font-medium text-gray-500 mb-2">Total Surat Masuk</p>
                <h3 class="text-3xl font-bold text-[#055a40]">{{ $suratMasukCount }}</h3>
            </div>

            <!-- Total Surat Keluar -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <p class="text-sm font-medium text-gray-500 mb-2">Total Surat Keluar</p>
                <h3 class="text-3xl font-bold text-blue-600">{{ $suratFinalCount }}</h3>
            </div>

            <!-- Sedang Diproses -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <p class="text-sm font-medium text-gray-500 mb-2">Sedang Diproses</p>
                <h3 class="text-3xl font-bold text-yellow-500">{{ $suratProsesCount }}</h3>
            </div>

            <!-- Rata-rata Waktu Proses -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <p class="text-sm font-medium text-gray-500 mb-2">Rata-rata Waktu Proses</p>
                <h3 class="text-3xl font-bold text-gray-700">3.2 <span class="text-xl font-normal">hari</span></h3>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Surat Masuk vs Keluar Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Surat Masuk vs Keluar (Bulanan)</h3>
                <div class="relative h-64">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <!-- Distribusi Jenis Surat Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Distribusi Jenis Surat</h3>
                <div class="relative h-64">
                    <canvas id="jenisChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Rekap Detail Per Bulan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-[11px] text-gray-500 bg-gray-50 border-b border-gray-100 uppercase tracking-wider text-center">
                        <tr>
                            <th class="px-6 py-4 font-medium text-left">PERIODE</th>
                            <th class="px-6 py-4 font-medium">SURAT MASUK</th>
                            <th class="px-6 py-4 font-medium">SURAT KELUAR</th>
                            <th class="px-6 py-4 font-medium">SEDANG DIPROSES</th>
                            <th class="px-6 py-4 font-medium">SELESAI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-center">
                        @forelse($monthlyDetails as $detail)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-gray-900 text-left whitespace-nowrap">{{ $detail['periode'] }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $detail['surat_masuk'] }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $detail['surat_keluar'] }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded font-bold">
                                    {{ $detail['sedang_diproses'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 bg-green-100 text-green-700 text-xs rounded font-bold">
                                    {{ $detail['selesai'] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                Belum ada data rekapitulasi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Trend Chart
        const ctxTrend = document.getElementById('trendChart');
        new Chart(ctxTrend, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [
                    {
                        label: 'Surat Masuk',
                        data: @json($chartValues),
                        backgroundColor: '#055a40',
                        borderWidth: 0,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    },
                    {
                        label: 'Surat Keluar',
                        data: @json($suratKeluarValues),
                        backgroundColor: '#0088cc',
                        borderWidth: 0,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [2, 4],
                            drawBorder: false,
                        },
                        ticks: { stepSize: 15 }
                    },
                    x: {
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true, boxWidth: 8 }
                    }
                }
            }
        });

        // Jenis Chart
        const ctxJenis = document.getElementById('jenisChart');
        new Chart(ctxJenis, {
            type: 'bar',
            data: {
                labels: @json(array_keys($distribusiJenis)),
                datasets: [{
                    label: 'Jumlah',
                    data: @json(array_values($distribusiJenis)),
                    backgroundColor: '#3b2c85',
                    borderWidth: 0,
                    barPercentage: 0.6,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [2, 4],
                            drawBorder: false,
                        },
                        ticks: { stepSize: 20 }
                    },
                    y: {
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
