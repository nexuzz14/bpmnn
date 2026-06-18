<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ $header['title'] ?? 'Progress Surat Bagian' }}</h2>
        <p class="text-gray-500 mt-1">{{ $header['subtitle'] ?? 'Pantau semua surat aktif dalam lingkup Bagian Keuangan' }}</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-sm text-gray-500 mb-2">Surat Aktif</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['surat_aktif'] ?? 6 }}</h3>
        </div>
        <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-sm text-gray-500 mb-2">Perlu Review Saya</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['perlu_review_saya'] ?? 1 }}</h3>
        </div>
        <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-sm text-gray-500 mb-2">Selesai Bulan Ini</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['selesai_bulan_ini'] ?? 12 }}</h3>
        </div>
    </div>

    <!-- Table Surat Dalam Proses -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="p-5 border-b border-gray-100 bg-white">
            <h3 class="font-bold text-gray-900 text-lg">Surat Dalam Proses</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 bg-white border-b border-gray-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-bold">NO. AGENDA</th>
                        <th class="px-6 py-4 font-bold">PERIHAL</th>
                        <th class="px-6 py-4 font-bold">SUB TIM</th>
                        <th class="px-6 py-4 font-bold">STATUS SAAT INI</th>
                        <th class="px-6 py-4 font-bold min-w-[300px]">PROGRESS</th>
                        <th class="px-6 py-4 font-bold text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($drafSurats as $draf)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                                {{ $draf->suratMasuk->nomor_surat ?? '-' }}
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900 max-w-[200px] truncate" title="{{ $draf->suratMasuk->perihal ?? '-' }}">
                                {{ $draf->suratMasuk->perihal ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $draf->pembuat->unitKerja->nama ?? 'Kasubtim Keuangan' }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusLabel = 'Dalam Proses';
                                    
                                    // Let's actually check the latest review!
                                    $latestReview = $draf->reviuSurat->first();
                                    $pendingReview = null;
                                    
                                    if ($latestReview) {
                                        if ($latestReview->tingkat == '1') {
                                            $statusLabel = 'Review Kasubtim';
                                            if ($latestReview->status == 'revisi') $statusLabel = 'Draft Sedang Disusun';
                                        } elseif ($latestReview->tingkat == '2') {
                                            $statusLabel = 'Menunggu Review Saya';
                                            if ($latestReview->status == 'revisi') $statusLabel = 'Revisi oleh Kasubtim';
                                        } elseif ($latestReview->tingkat == 'final') {
                                            $statusLabel = 'Menunggu Review Kabiro';
                                        }
                                    }
                                    
                                    // Calculate progress steps
                                    $steps = ['konsep' => true, 'kasubtim' => true, 'kabag' => false, 'kabiro' => false, 'selesai' => false];
                                    
                                    if ($draf->status === 'revisi' || $draf->status === 'menunggu_reviu') {
                                        $steps['kasubtim'] = true;
                                    } else {
                                        $steps['kasubtim'] = true;
                                        if ($latestReview && ($latestReview->tingkat == '2' || $latestReview->tingkat == 'final')) {
                                            $steps['kabag'] = true;
                                        }
                                        if ($latestReview && $latestReview->tingkat == 'final') {
                                            $steps['kabiro'] = true;
                                        }
                                    }
                                    
                                    if ($statusLabel === 'Menunggu Review Saya') {
                                        $pendingReview = $draf->reviuSurat->where('tingkat', '2')->where('status', 'menunggu')->first();
                                        // Fallback just in case pendingReview is null but it says Menunggu Review Saya
                                        if (!$pendingReview) {
                                            $statusLabel = 'Menunggu Review Kabag'; 
                                        }
                                    }
                                @endphp
                                <span class="inline-flex px-3 py-1 rounded text-xs font-bold bg-[#e0e7ff] text-[#4f46e5]">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1">
                                    <div class="px-3 py-1 rounded text-xs font-bold text-white {{ $steps['konsep'] ? 'bg-[#166534]' : 'bg-gray-200 text-gray-400' }}">Konsep</div>
                                    <div class="w-4 h-0.5 {{ $steps['kasubtim'] ? 'bg-[#166534]' : 'bg-gray-200' }}"></div>
                                    <div class="px-3 py-1 rounded text-xs font-bold text-white {{ $steps['kasubtim'] ? 'bg-[#064e3b]' : 'bg-gray-200 text-gray-400' }}">Review Kasubtim</div>
                                    <div class="w-4 h-0.5 {{ $steps['kabag'] ? 'bg-[#064e3b]' : 'bg-gray-200' }}"></div>
                                    <div class="px-3 py-1 rounded text-xs font-bold text-white {{ $steps['kabag'] ? 'bg-[#064e3b]' : 'bg-gray-200 text-gray-400' }}">Review Kabag</div>
                                    <div class="w-4 h-0.5 {{ $steps['kabiro'] ? 'bg-[#064e3b]' : 'bg-gray-200' }}"></div>
                                    <div class="px-3 py-1 rounded text-xs font-bold text-white {{ $steps['kabiro'] ? 'bg-[#064e3b]' : 'bg-gray-200 text-gray-400' }}">Kabiro</div>
                                    <div class="w-4 h-0.5 {{ $steps['selesai'] ? 'bg-[#064e3b]' : 'bg-gray-200' }}"></div>
                                    <div class="px-3 py-1 rounded text-xs font-bold {{ $steps['selesai'] ? 'bg-[#166534] text-white' : 'bg-gray-200 text-gray-400' }}">Selesai</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($statusLabel === 'Menunggu Review Saya' && $pendingReview)
                                    <a href="{{ route('kabag.review.show', $pendingReview->id) }}" class="inline-flex px-6 py-2 bg-[#312e81] text-white font-medium rounded text-xs hover:bg-[#1e1b4b] transition-colors shadow-sm">
                                        Review
                                    </a>
                                @else
                                    <a href="{{ route('kabag.progress.show', $draf->id) }}" class="inline-flex px-4 py-2 text-gray-500 font-medium hover:text-gray-900 transition-colors">
                                        Lihat
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">Belum ada progress surat saat ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $drafSurats->links() }}
        </div>
    </div>

    <!-- Table Riwayat Selesai -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="p-5 border-b border-gray-100 bg-white">
            <h3 class="font-bold text-gray-900 text-lg">Riwayat Selesai</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 bg-white border-b border-gray-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-bold">NO. AGENDA</th>
                        <th class="px-6 py-4 font-bold">PERIHAL</th>
                        <th class="px-6 py-4 font-bold">SUB TIM</th>
                        <th class="px-6 py-4 font-bold">TANGGAL SELESAI</th>
                        <th class="px-6 py-4 font-bold text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($riwayatSelesai as $riwayat)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-500">{{ $riwayat->suratMasuk->nomor_surat ?? '-' }}</td>
                            <td class="px-6 py-4 font-bold text-gray-900">{{ $riwayat->suratMasuk->perihal ?? 'Surat Selesai' }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $riwayat->pembuat->unitKerja->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ \Carbon\Carbon::parse($riwayat->updated_at)->translatedFormat('d M Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('kabag.progress.show', $riwayat->id) }}" class="inline-flex px-4 py-2 text-gray-500 font-medium hover:text-gray-900 transition-colors">Lihat</a>
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
