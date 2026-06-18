<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Daftar Draft Surat</h2>
        <p class="text-gray-500 mt-1">Draft surat yang dikirim oleh Staf untuk direview</p>
    </div>

    <!-- Tabs -->
    <div class="flex items-center gap-6 border-b border-gray-200 mb-6">
        <a href="{{ route('kasubtim.draft.index', ['tab' => 'perlu-review']) }}" class="flex items-center gap-2 pb-3 font-medium {{ $tab === 'perlu-review' ? 'border-b-2 border-[#5c3a21] text-[#5c3a21]' : 'text-gray-500 hover:text-gray-700' }}">
            Perlu Review
            @if($countPerluReview > 0)
                <span class="px-2 py-0.5 bg-[#fefce8] text-[#92400e] text-xs font-bold rounded-full">{{ $countPerluReview }}</span>
            @endif
        </a>
        <a href="{{ route('kasubtim.draft.index', ['tab' => 'sudah-diproses']) }}" class="flex items-center gap-2 pb-3 font-medium {{ $tab === 'sudah-diproses' ? 'border-b-2 border-[#5c3a21] text-[#5c3a21]' : 'text-gray-500 hover:text-gray-700' }}">
            Sudah Diproses
        </a>
    </div>

    @if($tab === 'perlu-review')
    <div class="bg-[#fefce8] border border-[#fef08a] text-[#a16207] p-4 rounded-lg mb-6 shadow-sm">
        Draft yang masuk ke halaman ini dikirim oleh Staf sesuai disposisi yang Anda berikan.
    </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-[11px] text-gray-500 bg-[#f8fafc] border-b border-gray-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-bold">NO. AGENDA</th>
                        <th class="px-6 py-4 font-bold">PERIHAL</th>
                        <th class="px-6 py-4 font-bold">DIBUAT OLEH (STAF)</th>
                        <th class="px-6 py-4 font-bold">VERSI</th>
                        <th class="px-6 py-4 font-bold">TGL UNGGAH</th>
                        <th class="px-6 py-4 font-bold text-center">STATUS</th>
                        <th class="px-6 py-4 font-bold text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($drafSurats as $index => $draf)
                        @php
                            // Ambil review tingkat 1 (Kasubtim)
                            $kasubtimReviu = $draf->reviuSurat->first(function ($r) {
                                return $r->tingkat == 1 || $r->tingkat == '1';
                            });
                            // Ambil review terbaru untuk penentuan status akhir
                            $latestReviu = $draf->reviuSurat->sortByDesc('created_at')->first();
                            
                            $statusText = 'Menunggu Review Kasubtim';
                            $statusClasses = 'bg-[#fefce8] text-[#92400e]';
                            
                            if ($kasubtimReviu) {
                                if ($kasubtimReviu->status == 'disetujui') {
                                    if ($latestReviu && $latestReviu->tingkat > 1) {
                                        if ($latestReviu->status == 'disetujui') {
                                            $statusText = 'Selesai';
                                            $statusClasses = 'bg-[#dcfce7] text-[#166534]';
                                        } else {
                                            $statusText = 'Diteruskan ke Kabag';
                                            $statusClasses = 'bg-[#e0e7ff] text-[#3730a3]';
                                        }
                                    } else {
                                        $statusText = 'Diteruskan ke Kabag';
                                        $statusClasses = 'bg-[#e0e7ff] text-[#3730a3]';
                                    }
                                } elseif ($kasubtimReviu->status == 'revisi') {
                                    $statusText = 'Dikembalikan ke Staf';
                                    $statusClasses = 'bg-[#fee2e2] text-[#991b1b]';
                                }
                            }
                        @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">#{{ $draf->suratMasuk->nomor_surat }}</td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-gray-900">{{ $draf->suratMasuk->perihal }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $draf->pembuat->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-500">v{{ $draf->versi ?? '1' }}</td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($draf->created_at)->translatedFormat('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-bold {{ $statusClasses }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($tab === 'perlu-review')
                                @if($kasubtimReviu && $kasubtimReviu->status == 'menunggu')
                                    <a href="{{ route('kasubtim.review.show', $kasubtimReviu->id) }}" class="inline-flex px-4 py-2 bg-[#5c3a21] text-white font-bold rounded text-xs hover:bg-[#4a2e1a] transition-colors shadow-sm whitespace-nowrap">
                                        Review Draft
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400 italic">Tidak ada aksi</span>
                                @endif
                            @else
                                <a href="{{ route('kasubtim.draft.show', $draf->id) }}" class="inline-flex px-4 py-2 bg-white border border-gray-200 text-gray-700 font-medium rounded text-xs hover:bg-gray-50 transition-colors shadow-sm whitespace-nowrap">
                                    Lihat Detail
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">Belum ada draf surat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $drafSurats->appends(['tab' => $tab])->links() }}
        </div>
    </div>
</x-app-layout>
