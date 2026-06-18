<x-app-layout>
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Rekap Bagian Keuangan</h2>
            <p class="text-gray-500 mt-1">Laporan kinerja dan statistik persuratan Bagian Keuangan</p>
        </div>
        <div class="flex gap-4">
            <form action="{{ route('kabag.rekap.index') }}" method="GET" class="flex gap-2">
                <select name="tahun" onchange="this.form.submit()" class="py-2 pl-4 pr-10 bg-white border border-gray-200 rounded-lg focus:ring-[#4f46e5] focus:border-[#4f46e5] shadow-sm text-sm text-gray-700">
                    <option value="2026" {{ request('tahun', now()->year) == 2026 ? 'selected' : '' }}>Tahun 2026</option>
                    <option value="2025" {{ request('tahun', now()->year) == 2025 ? 'selected' : '' }}>Tahun 2025</option>
                </select>
            </form>
            <a href="{{ route('kabag.rekap.pdf', ['tahun' => request('tahun', now()->year)]) }}" class="inline-flex px-4 py-2 border border-[#4f46e5] text-[#4f46e5] font-medium rounded-lg text-sm hover:bg-indigo-50 transition-colors shadow-sm bg-white items-center">
                Export PDF
            </a>
        </div>
    </div>

    @php
        $totalMasuk = collect($rekapData)->sum('disposisi_masuk');
        $totalDraft = collect($rekapData)->sum('draft_direview');
        $totalSelesai = collect($rekapData)->sum('disetujui');
        $waktuReviews = collect($rekapData)->pluck('waktu_review')->filter(function($val) {
            return $val !== '-';
        })->map(function($val) {
            return (float) str_replace(' hari', '', $val);
        });
        $avgTimeAll = $waktuReviews->count() > 0 ? round($waktuReviews->avg(), 1) : 0;
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm font-medium text-gray-500 mb-2">Total Disposisi Diterima</p>
            <h3 class="text-4xl font-bold text-[#4f46e5]">{{ $totalMasuk }}</h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm font-medium text-gray-500 mb-2">Total Draft Direview</p>
            <h3 class="text-4xl font-bold text-[#0ea5e9]">{{ $totalDraft }}</h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm font-medium text-gray-500 mb-2">Disetujui</p>
            <h3 class="text-4xl font-bold text-[#22c55e]">{{ $totalSelesai }}</h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm font-medium text-gray-500 mb-2">Rata-rata Waktu Review</p>
            <h3 class="text-4xl font-bold text-gray-600">{{ $avgTimeAll }} <span class="text-xl font-normal">hari</span></h3>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-6">Trend Disposisi & Review Draft (Bulanan)</h3>
        <div class="h-[300px]">
            <canvas id="barChart"></canvas>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-white">
            <h3 class="font-bold text-gray-900">Rekap Detail Per Bulan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-[11px] text-gray-500 bg-gray-50/50 border-b border-gray-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">PERIODE</th>
                        <th class="px-6 py-4 font-medium text-center">DISPOSISI MASUK</th>
                        <th class="px-6 py-4 font-medium text-center">DRAFT DIREVIEW</th>
                        <th class="px-6 py-4 font-medium text-center">DISETUJUI</th>
                        <th class="px-6 py-4 font-medium text-center">DIKEMBALIKAN</th>
                        <th class="px-6 py-4 font-medium text-center">RATA-RATA WAKTU REVIEW</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rekapData as $row)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-gray-700 whitespace-nowrap">{{ $row['periode'] }}</td>
                            <td class="px-6 py-4 text-center text-gray-500">{{ $row['disposisi_masuk'] }}</td>
                            <td class="px-6 py-4 text-center text-gray-500">{{ $row['draft_direview'] }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex px-3 py-1 rounded text-xs font-bold bg-green-100 text-green-600">{{ $row['disetujui'] }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex px-3 py-1 rounded text-xs font-bold bg-red-100 text-red-500">{{ $row['dikembalikan'] }}</span>
                            </td>
                            <td class="px-6 py-4 text-center text-gray-500">{{ $row['waktu_review'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada data rekapitulasi untuk tahun ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const barCtx = document.getElementById('barChart').getContext('2d');
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [
                        {
                            label: 'Disposisi Masuk',
                            data: {!! json_encode($chartDisposisiMasuk) !!},
                            backgroundColor: '#4338ca', // indigo-700
                            borderWidth: 0,
                            barPercentage: 0.6,
                            categoryPercentage: 0.8
                        },
                        {
                            label: 'Draft Direview',
                            data: {!! json_encode($chartDraftDireview) !!},
                            backgroundColor: '#0ea5e9', // sky-500
                            borderWidth: 0,
                            barPercentage: 0.6,
                            categoryPercentage: 0.8
                        },
                        {
                            label: 'Selesai',
                            data: {!! json_encode($chartSelesai) !!},
                            backgroundColor: '#22c55e', // green-500
                            borderWidth: 0,
                            barPercentage: 0.6,
                            categoryPercentage: 0.8
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8, padding: 20 } }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [4, 4] } },
                        x: { grid: { display: false } }
                    }
                }
            });
        });
    </script>
</x-app-layout>
