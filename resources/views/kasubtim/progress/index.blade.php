<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ $header['title'] }}</h2>
        <p class="text-gray-500 mt-1">{{ $header['subtitle'] }}</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
            <h3 class="text-gray-500 text-sm font-medium">Surat Aktif</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['surat_aktif'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
            <h3 class="text-gray-500 text-sm font-medium">Perlu Review Saya</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['perlu_review_saya'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
            <h3 class="text-gray-500 text-sm font-medium">Selesai Bulan Ini</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['selesai_bulan_ini'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Table: Surat Dalam Proses -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-900">{{ $header['table_title'] }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-[11px] text-gray-500 bg-white border-b border-gray-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-bold">NO. AGENDA</th>
                        <th class="px-6 py-4 font-bold">PERIHAL</th>
                        <th class="px-6 py-4 font-bold">{{ $header['col3_title'] }}</th>
                        <th class="px-6 py-4 font-bold">STATUS SAAT INI</th>
                        <th class="px-6 py-4 font-bold">PROGRESS</th>
                        <th class="px-6 py-4 font-bold text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($drafSurats as $draf)
                        @php
                            $latestReviu = $draf->reviuSurat->first();
                            
                            $statusText = 'Draft Sedang Disusun';
                            $statusClasses = 'bg-[#e0e7ff] text-[#3730a3]'; // default blue
                            $actionType = 'lihat';
                            $actionUrl = route('kasubtim.progress.show', $draf->id);
                            
                            $stepKonsep = true; // always true if draft exists
                            $stepReviewKasubtim = false;
                            $stepReviewKabag = false;
                            $stepKabiro = false;
                            $stepSelesai = false;

                            if ($draf->status === 'menunggu_reviu') {
                                $statusText = 'Menunggu Review Saya';
                                $statusClasses = 'bg-[#fefce8] text-[#92400e]'; // yellow
                                $stepReviewKasubtim = true;
                                
                                // Ambil ID reviu Kasubtim
                                $kasubtimReviu = $draf->reviuSurat->first(function($r) { return $r->tingkat == 1 || $r->tingkat == '1'; });
                                if ($kasubtimReviu && $kasubtimReviu->status == 'menunggu') {
                                    $actionType = 'review';
                                    $actionUrl = route('kasubtim.review.show', $kasubtimReviu->id);
                                }
                            } elseif ($latestReviu) {
                                $stepReviewKasubtim = true;
                                if ($latestReviu->tingkat == '1' && $latestReviu->status == 'menunggu') {
                                    $statusText = 'Menunggu Review Saya';
                                    $statusClasses = 'bg-[#fefce8] text-[#92400e]';
                                    $actionType = 'review';
                                    $actionUrl = route('kasubtim.review.show', $latestReviu->id);
                                } elseif ($latestReviu->tingkat == '1' && $latestReviu->status == 'disetujui') {
                                    $statusText = 'Menunggu Review Kabag';
                                    $statusClasses = 'bg-[#e0e7ff] text-[#3730a3]';
                                    $stepReviewKabag = true;
                                } elseif ($latestReviu->tingkat == '1' && $latestReviu->status == 'revisi') {
                                    $statusText = 'Revisi';
                                    $statusClasses = 'bg-[#fee2e2] text-[#991b1b]';
                                } elseif ($latestReviu->tingkat == '2' && $latestReviu->status == 'menunggu') {
                                    $statusText = 'Menunggu Review Kabag';
                                    $statusClasses = 'bg-[#e0e7ff] text-[#3730a3]';
                                    $stepReviewKabag = true;
                                } elseif ($latestReviu->tingkat == '2' && $latestReviu->status == 'disetujui') {
                                    $statusText = 'Menunggu Review Kabiro';
                                    $statusClasses = 'bg-[#e0e7ff] text-[#3730a3]';
                                    $stepReviewKabag = true;
                                    $stepKabiro = true;
                                } elseif ($latestReviu->tingkat == '3' || $latestReviu->tingkat == 'final') {
                                    $stepReviewKabag = true;
                                    $stepKabiro = true;
                                    if ($latestReviu->status == 'menunggu') {
                                        $statusText = 'Menunggu Review Kabiro';
                                    } elseif ($latestReviu->status == 'disetujui') {
                                        $statusText = 'Selesai';
                                        $stepSelesai = true;
                                    }
                                }
                            }
                            
                            if ($draf->status === 'selesai') {
                                $statusText = 'Selesai';
                                $stepSelesai = true;
                                $stepReviewKasubtim = true;
                                $stepReviewKabag = true;
                                $stepKabiro = true;
                            }
                        @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">#{{ $draf->suratMasuk->nomor_surat }}</td>
                        <td class="px-6 py-4 text-gray-900 font-bold min-w-[200px]">{{ $draf->suratMasuk->perihal }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $draf->pembuat->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-3 py-1 rounded text-[11px] font-bold {{ $statusClasses }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex px-3 py-1 rounded text-[11px] font-bold {{ $stepKonsep ? 'bg-[#166534] text-white' : 'bg-gray-100 text-gray-400' }}">Konsep</span>
                                <div class="w-4 h-0.5 {{ $stepReviewKasubtim ? 'bg-[#166534]' : 'bg-gray-200' }}"></div>
                                <span class="inline-flex px-3 py-1 rounded text-[11px] font-bold {{ $stepReviewKasubtim ? 'bg-[#166534] text-white' : 'bg-gray-100 text-gray-400' }}">Review Kasubtim</span>
                                <div class="w-4 h-0.5 {{ $stepReviewKabag ? 'bg-[#166534]' : 'bg-gray-200' }}"></div>
                                <span class="inline-flex px-3 py-1 rounded text-[11px] font-bold {{ $stepReviewKabag ? 'bg-[#166534] text-white' : 'bg-gray-100 text-gray-400' }}">Review Kabag</span>
                                <div class="w-4 h-0.5 {{ $stepKabiro ? 'bg-[#166534]' : 'bg-gray-200' }}"></div>
                                <span class="inline-flex px-3 py-1 rounded text-[11px] font-bold {{ $stepKabiro ? 'bg-[#166534] text-white' : 'bg-gray-100 text-gray-400' }}">Kabiro</span>
                                <div class="w-4 h-0.5 {{ $stepSelesai ? 'bg-[#166534]' : 'bg-gray-200' }}"></div>
                                <span class="inline-flex px-3 py-1 rounded text-[11px] font-bold {{ $stepSelesai ? 'bg-[#166534] text-white' : 'bg-gray-100 text-gray-400' }}">Selesai</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($actionType === 'review')
                                <a href="{{ $actionUrl }}" class="inline-flex px-5 py-2 bg-[#5c3a21] text-white font-bold rounded text-xs hover:bg-[#4a2e1a] transition-colors shadow-sm whitespace-nowrap">
                                    Review
                                </a>
                            @else
                                <a href="{{ $actionUrl }}" class="text-sm text-gray-500 hover:text-[#5c3a21] font-medium">
                                    Lihat
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">Belum ada surat yang diproses.</td>
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

    <!-- Table: Surat Selesai -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-900">Surat Selesai</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-[11px] text-gray-500 bg-white border-b border-gray-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-bold">NO. AGENDA</th>
                        <th class="px-6 py-4 font-bold">PERIHAL</th>
                        <th class="px-6 py-4 font-bold">DIKERJAKAN OLEH</th>
                        <th class="px-6 py-4 font-bold">TANGGAL SELESAI</th>
                        <th class="px-6 py-4 font-bold text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($riwayatSelesai ?? [] as $selesai)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">#{{ $selesai->suratMasuk->nomor_surat }}</td>
                        <td class="px-6 py-4 text-gray-900 font-bold min-w-[200px]">{{ $selesai->suratMasuk->perihal }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $selesai->pembuat->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($selesai->updated_at)->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('kasubtim.progress.show', $selesai->id) }}" class="text-sm text-gray-500 hover:text-[#5c3a21] font-medium">
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
