<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Draft Perlu Saya Review</h2>
        <p class="text-gray-500 mt-1">Draft yang telah diselesaikan staf dan menunggu persetujuan Anda</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Menunggu Review</p>
            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['menunggu'] }}</h3>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Direview Bulan Ini</p>
            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['direview_bulan_ini'] }}</h3>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Dikembalikan untuk Revisi</p>
            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['revisi'] }}</h3>
        </div>
    </div>

    @if($stats['menunggu'] > 0)
    <!-- Banner -->
    <div class="bg-[#fefce8] border border-[#fef08a] rounded-xl p-4 mb-6">
        <p class="font-bold text-[#b45309] mb-1">{{ $stats['menunggu'] }} draft menunggu review Anda</p>
        <p class="text-[#d97706] text-sm">Review dan setujui draft sebelum diteruskan ke Kabag</p>
    </div>
    @endif

    <!-- Cards -->
    <div class="space-y-4">
        @forelse($reviuSurats as $reviu)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 border-l-4 border-l-[#5c3a21]">
                <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-xs font-bold text-gray-400 tracking-wider uppercase">DRAFT-{{ str_pad($reviu->draf_surat_id, 3, '0', STR_PAD_LEFT) }}/{{ date('Y') }} &middot; v{{ $reviu->drafSurat->versi }}</span>
                            <span class="inline-flex px-2 py-1 rounded bg-[#fefce8] text-[#92400e] text-[10px] font-bold">
                                Menunggu Review
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $reviu->drafSurat->suratMasuk->perihal }}</h3>
                        <p class="text-gray-500 text-sm mt-1">Balasan atas surat dari {{ $reviu->drafSurat->suratMasuk->asal_surat }}</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-3 flex flex-col sm:flex-row sm:justify-between gap-2 mb-4">
                    <p class="text-sm text-gray-600"><span class="font-medium">Penyusun:</span> {{ $reviu->drafSurat->pembuat->name ?? '-' }}</p>
                    <p class="text-sm text-gray-400">Terkait Disposisi: {{ explode('/', $reviu->drafSurat->suratMasuk->nomor_surat)[0] ?? ('#' . $reviu->drafSurat->suratMasuk->id) }}</p>
                </div>

                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <p class="text-xs text-gray-400">Diajukan: {{ \Carbon\Carbon::parse($reviu->created_at)->translatedFormat('d M Y, H:i') }}</p>
                    <a href="{{ route('kasubtim.review.show', $reviu->id) }}" class="inline-flex px-6 py-2 bg-[#5c3a21] text-white font-bold rounded-lg text-sm hover:bg-[#4a2e1a] transition-colors shadow-sm w-full sm:w-auto justify-center">
                        Review Sekarang
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-10 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                    <i class="ph ph-check-circle text-3xl"></i>
                </div>
                <h3 class="text-gray-900 font-bold mb-1">Semua Selesai</h3>
                <p class="text-gray-500 text-sm">Tidak ada draf yang perlu direview saat ini.</p>
            </div>
        @endforelse
    </div>

    @if($reviuSurats->hasPages())
    <div class="mt-6">
        {{ $reviuSurats->links() }}
    </div>
    @endif
</x-app-layout>
