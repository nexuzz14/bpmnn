<x-app-layout>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Rekap Bulanan Biro Keuangan &amp; BMN</h2>
            <p class="text-gray-500 mt-1">Laporan kinerja dan statistik persuratan Biro</p>
        </div>
        <div class="flex gap-3">
            <form action="{{ route('kabiro.rekap.index') }}" method="GET" class="flex gap-3">
                <select name="tahun" onchange="this.form.submit()" class="py-2 pl-4 pr-10 bg-white border border-gray-200 rounded-lg focus:ring-[#3b2c85] focus:border-[#3b2c85] shadow-sm text-sm text-gray-700">
                    <option value="2026" {{ $year == '2026' ? 'selected' : '' }}>Tahun 2026</option>
                    <option value="2025" {{ $year == '2025' ? 'selected' : '' }}>Tahun 2025</option>
                </select>
            </form>
            <a href="{{ route('kabiro.rekap.pdf', ['tahun' => $year]) }}" class="px-4 py-2 bg-white border border-[#3b2c85] text-[#3b2c85] text-sm font-medium rounded-lg hover:bg-[#f5f3ff] transition-colors shadow-sm whitespace-nowrap">
                Export PDF
            </a>
        </div>
    </div>

    @php
        $totalDireview = array_sum($chartDireview);
        $totalDisetujui = array_sum($chartDisetujui);
        $totalDikembalikan = array_sum($chartDikembalikan);
        
        $totalDays = 0;
        $countDays = 0;
        foreach($rekapData as $data) {
            if ($data['waktu_review'] !== '-') {
                $totalDays += (float)str_replace(' hari', '', $data['waktu_review']);
                $countDays++;
            }
        }
        $avgTimeTotal = $countDays > 0 ? round($totalDays / $countDays, 1) : 0;
    @endphp

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <p class="text-sm font-medium text-gray-500 mb-2">Total Surat Direview</p>
            <h3 class="text-3xl font-bold text-[#3b2c85]">{{ $totalDireview }}</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <p class="text-sm font-medium text-gray-500 mb-2">Disetujui</p>
            <h3 class="text-3xl font-bold text-green-600">{{ $totalDisetujui }}</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <p class="text-sm font-medium text-gray-500 mb-2">Dikembalikan</p>
            <h3 class="text-3xl font-bold text-red-600">{{ $totalDikembalikan }}</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <p class="text-sm font-medium text-gray-500 mb-2">Rata-rata Waktu Review</p>
            <h3 class="text-3xl font-bold text-gray-500">{{ $avgTimeTotal }} hari</h3>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <h3 class="font-bold text-gray-900 mb-6">Trend Review Surat (Bulanan)</h3>
        <div class="relative h-[300px] w-full">
            <canvas id="rekapChart"></canvas>
        </div>
    </div>

    <!-- Table Details -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-5 border-b border-gray-100 bg-white">
            <h3 class="font-bold text-gray-900">Rekap Detail Per Bulan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-[11px] text-gray-500 bg-white border-b border-gray-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">PERIODE</th>
                        <th class="px-6 py-4 font-medium text-center">SURAT MASUK</th>
                        <th class="px-6 py-4 font-medium text-center">DISETUJUI</th>
                        <th class="px-6 py-4 font-medium text-center">DIKEMBALIKAN</th>
                        <th class="px-6 py-4 font-medium text-center">RATA-RATA WAKTU REVIEW</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rekapData as $data)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-900">{{ $data['periode'] }}</td>
                        <td class="px-6 py-4 text-center text-gray-500 font-medium">{{ $data['surat_masuk'] }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex px-2 py-1 rounded bg-[#dcfce7] text-[#166534] font-bold text-xs">{{ $data['disetujui'] }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex px-2 py-1 rounded bg-[#fee2e2] text-[#991b1b] font-bold text-xs">{{ $data['dikembalikan'] }}</span>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-500 font-medium">{{ $data['waktu_review'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">Tidak ada data rekapitulasi untuk tahun ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart.js Setup -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('rekapChart');
            if (ctx) {
                // Ensure chart only shows up to the data we have, ignoring full empty months if desired,
                // but usually we show Jan-Dec or Jan-Apr based on data.
                // In controller $chartLabels contains Jan to Dec.
                // We'll pass the arrays directly.
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [
                            {
                                label: 'Direview',
                                data: {!! json_encode($chartDireview) !!},
                                backgroundColor: '#3b2c85',
                                borderRadius: 0,
                                barPercentage: 0.8,
                                categoryPercentage: 0.7
                            },
                            {
                                label: 'Disetujui',
                                data: {!! json_encode($chartDisetujui) !!},
                                backgroundColor: '#16a34a', // darker green
                                borderRadius: 0,
                                barPercentage: 0.8,
                                categoryPercentage: 0.7
                            },
                            {
                                label: 'Dikembalikan',
                                data: {!! json_encode($chartDikembalikan) !!},
                                backgroundColor: '#dc2626', // red
                                borderRadius: 0,
                                barPercentage: 0.8,
                                categoryPercentage: 0.7
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: false, // use square box
                                    boxWidth: 12,
                                    font: { size: 12, family: "'Inter', sans-serif" },
                                    padding: 20
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                border: { display: false, dash: [5, 5] },
                                grid: { 
                                    color: '#f3f4f6',
                                    drawBorder: false,
                                    tickLength: 0
                                },
                                ticks: {
                                    stepSize: 4
                                }
                            },
                            x: {
                                grid: { display: false },
                                border: { display: true, color: '#e5e7eb' }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
