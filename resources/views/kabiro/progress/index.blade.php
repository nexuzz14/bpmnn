<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Progress Surat &mdash; Biro Keuangan &amp; BMN</h2>
        <p class="text-gray-500 mt-1">Pantau semua surat aktif di seluruh Biro</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <p class="text-sm font-medium text-gray-500 mb-2">Perlu Tanda Tangan</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['perlu_ttd'] ?? 1 }}</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <p class="text-sm font-medium text-gray-500 mb-2">Disetujui Bulan Ini</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['disetujui_bulan_ini'] ?? 8 }}</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <p class="text-sm font-medium text-gray-500 mb-2">Total Surat Keluar</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total_surat_keluar'] ?? 47 }}</h3>
        </div>
    </div>

    <!-- Semua Surat Aktif -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900">Semua Surat Aktif</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-[11px] text-gray-500 bg-white border-b border-gray-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">NO. AGENDA</th>
                        <th class="px-6 py-4 font-medium">PERIHAL</th>
                        <th class="px-6 py-4 font-medium">BAGIAN</th>
                        <th class="px-6 py-4 font-medium">STATUS SAAT INI</th>
                        <th class="px-6 py-4 font-medium text-center">PROGRESS</th>
                        <th class="px-6 py-4 font-medium text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($drafSurats as $draf)
                    @php
                        if ($draf->status === 'selesai') continue; // Hide selesai from active

                        $posisi = 'Review Kasubtim';
                        $statusText = 'Review Kasubtim';
                        $statusClass = 'bg-[#e0e7ff] text-[#3730a3]'; // Light purple/blue
                        $menungguSaya = false;

                        $latestReviu = $draf->reviuSurat->first();
                        $progressSteps = [
                            'Konsep' => true,
                            'Review Kasubtim' => false,
                            'Review Kabag' => false,
                            'Kabiro' => false,
                            'Selesai' => false,
                        ];

                        if ($latestReviu) {
                            if ($latestReviu->status == 'menunggu') {
                                if ($latestReviu->tingkat == '1') {
                                    $posisi = 'Review Kasubtim';
                                    $progressSteps['Konsep'] = true;
                                }
                                elseif ($latestReviu->tingkat == '2') {
                                    $posisi = 'Review Kabag';
                                    $progressSteps['Review Kasubtim'] = true;
                                }
                                elseif ($latestReviu->tingkat == 'final') {
                                    $posisi = 'Menunggu Persetujuan Saya';
                                    $statusClass = 'bg-[#f3e8ff] text-[#6b21a8]'; // lighter purple
                                    $menungguSaya = true;
                                    $progressSteps['Review Kasubtim'] = true;
                                    $progressSteps['Review Kabag'] = true;
                                }
                            } elseif ($latestReviu->status == 'revisi') {
                                $posisi = 'Revisi';
                                $statusClass = 'bg-red-100 text-red-700';
                            } elseif ($latestReviu->status == 'disetujui' && $latestReviu->tingkat == 'final') {
                                $posisi = 'Menunggu Upload TTD';
                                $statusClass = 'bg-[#e0e7ff] text-[#3730a3]';
                                $progressSteps['Review Kasubtim'] = true;
                                $progressSteps['Review Kabag'] = true;
                                $progressSteps['Kabiro'] = true;
                            }
                        }

                        if ($draf->status == 'selesai' || $draf->status == 'menunggu_ttd') {
                            $progressSteps['Review Kasubtim'] = true;
                            $progressSteps['Review Kabag'] = true;
                            $progressSteps['Kabiro'] = true;
                            if($draf->status == 'selesai') {
                                $progressSteps['Selesai'] = true;
                            }
                        }
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            #{{ date('Y') }}/{{ str_pad($draf->id, 3, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900">{{ $draf->suratMasuk->perihal ?? $draf->judul }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            {{ $draf->pembuat->unitKerja->nama ?? 'Bagian Keuangan' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-3 py-1.5 rounded text-xs font-medium {{ $statusClass }}">
                                {{ $posisi }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <!-- Progress Diagram -->
                            <div class="flex items-center justify-center gap-1 text-[10px] font-bold uppercase tracking-wider">
                                <span class="px-2 py-1 rounded {{ $progressSteps['Konsep'] ? 'bg-[#115e59] text-white' : 'border border-gray-200 text-gray-400 bg-white' }}">Konsep</span>
                                <span class="text-gray-300">-</span>
                                <span class="px-2 py-1 rounded {{ $progressSteps['Review Kasubtim'] ? 'bg-[#115e59] text-white' : 'border border-gray-200 text-gray-400 bg-white' }}">Review Kasubtim</span>
                                <span class="text-gray-300">-</span>
                                <span class="px-2 py-1 rounded {{ $progressSteps['Review Kabag'] ? 'bg-[#115e59] text-white' : 'border border-gray-200 text-gray-400 bg-white' }}">Review Kabag</span>
                                <span class="text-gray-300">-</span>
                                <span class="px-2 py-1 rounded {{ $progressSteps['Kabiro'] ? 'bg-[#115e59] text-white' : 'border border-gray-200 text-gray-400 bg-white' }}">Kabiro</span>
                                <span class="text-gray-300">-</span>
                                <span class="px-2 py-1 rounded {{ $progressSteps['Selesai'] ? 'bg-[#115e59] text-white' : 'border border-gray-200 text-gray-400 bg-white' }}">Selesai</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            @if($menungguSaya)
                                <a href="{{ route('kabiro.review-final.show', $latestReviu->id) }}" class="inline-flex px-6 py-2 bg-[#3b2c85] text-white font-bold rounded hover:bg-[#2e2269] transition-colors shadow-sm text-sm">
                                    Tinjau
                                </a>
                            @else
                                <a href="{{ route('kabiro.progress.show', $draf) }}" class="inline-flex px-4 py-2 text-gray-600 hover:text-gray-900 font-medium text-sm">
                                    Lihat
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">Tidak ada data progress surat aktif.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($drafSurats->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $drafSurats->links() }}
        </div>
        @endif
    </div>

    <!-- Riwayat Selesai -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900">Riwayat Selesai</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-[11px] text-gray-500 bg-white border-b border-gray-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">NO. AGENDA</th>
                        <th class="px-6 py-4 font-medium">PERIHAL</th>
                        <th class="px-6 py-4 font-medium">BAGIAN</th>
                        <th class="px-6 py-4 font-medium">TANGGAL SELESAI</th>
                        <th class="px-6 py-4 font-medium text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                        $riwayatSelesai = \App\Models\DrafSurat::with(['suratMasuk', 'pembuat.unitKerja'])->whereHas('suratFinal', function($q) {
                            $q->where('status', 'terdistribusi');
                        })->orWhere('status', 'selesai')->latest()->take(5)->get();
                    @endphp
                    @forelse($riwayatSelesai as $selesai)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            #{{ date('Y') }}/{{ str_pad($selesai->id, 3, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900">{{ $selesai->suratMasuk->perihal ?? $selesai->judul }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            {{ $selesai->pembuat->unitKerja->nama ?? 'Bagian Keuangan' }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            {{ $selesai->updated_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <a href="{{ route('kabiro.progress.show', $selesai) }}" class="inline-flex px-4 py-2 text-gray-600 hover:text-gray-900 font-medium text-sm">
                                Lihat
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">Belum ada riwayat surat selesai.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
