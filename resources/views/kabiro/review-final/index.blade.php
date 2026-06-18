<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Surat Perlu Review</h2>
        <p class="text-gray-500 mt-1">Daftar surat yang telah disetujui Kabag dan menunggu persetujuan final Anda</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <p class="text-sm font-medium text-gray-500 mb-2">Menunggu Review</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ \App\Models\ReviuSurat::where('tingkat', 'final')->where('status', 'menunggu')->count() }}</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <p class="text-sm font-medium text-gray-500 mb-2">Direview Bulan Ini</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ \App\Models\ReviuSurat::where('tingkat', 'final')->where('status', 'disetujui')->whereMonth('updated_at', now()->month)->count() }}</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <p class="text-sm font-medium text-gray-500 mb-2">Dikembalikan</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ \App\Models\ReviuSurat::where('tingkat', 'final')->where('status', 'revisi')->count() }}</h3>
        </div>
    </div>

    @php
        $countMenunggu = \App\Models\ReviuSurat::where('tingkat', 'final')->where('status', 'menunggu')->count();
    @endphp

    @if($countMenunggu > 0)
    <!-- Alert Banner -->
    <div class="mb-6 bg-[#f5f3ff] border-l-4 border-[#3b2c85] p-4 rounded-r-xl shadow-sm flex items-start gap-3">
        <div class="flex-1">
            <h4 class="text-[#3b2c85] font-bold text-sm">{{ $countMenunggu }} surat menunggu persetujuan final Anda</h4>
            <p class="text-[#3b2c85]/80 text-sm mt-0.5">Surat yang disetujui akan otomatis diteruskan ke TU untuk upload TTD</p>
        </div>
    </div>
    @endif

    <!-- Cards List -->
    <div class="flex flex-col gap-6">
        @forelse($reviuSurats as $reviu)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-sm font-medium text-gray-500">DRAFT-{{ str_pad($reviu->drafSurat->id, 3, '0', STR_PAD_LEFT) }}/{{ date('Y') }}</span>
                    <span class="text-gray-300">&bull;</span>
                    <span class="text-sm font-medium text-gray-400">v{{ $reviu->drafSurat->versi ?? '1' }}</span>
                    
                    @if($reviu->status === 'menunggu')
                        <span class="inline-flex px-2 py-1 rounded text-[10px] font-bold bg-[#e0e7ff] text-[#3730a3]">
                            Menunggu Review
                        </span>
                    @elseif($reviu->status === 'disetujui')
                        <span class="inline-flex px-2 py-1 rounded text-[10px] font-bold bg-[#dcfce7] text-[#166534]">
                            Disetujui
                        </span>
                    @else
                        <span class="inline-flex px-2 py-1 rounded text-[10px] font-bold bg-[#fef2f2] text-[#dc2626]">
                            Dikembalikan
                        </span>
                    @endif
                </div>
                
                <h4 class="text-xl font-bold text-gray-900 mb-1">{{ $reviu->drafSurat->suratMasuk->perihal ?? $reviu->drafSurat->judul }}</h4>
                <p class="text-sm text-gray-600 mb-4">{{ $reviu->drafSurat->suratMasuk->catatan ?? 'Balasan atas permintaan data dari ' . $reviu->drafSurat->suratMasuk->asal_surat }}</p>
                
                <!-- Riwayat Persetujuan -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                    <h5 class="text-sm font-bold text-gray-700 mb-3">Riwayat Persetujuan:</h5>
                    <div class="flex flex-col gap-3 text-sm">
                        @php
                            $kasubtimReview = \App\Models\ReviuSurat::where('draf_surat_id', $reviu->drafSurat->id)->where('tingkat', '1')->first();
                            $kabagReview = \App\Models\ReviuSurat::where('draf_surat_id', $reviu->drafSurat->id)->where('tingkat', '2')->first();
                        @endphp
                        
                        <!-- Kasubtim -->
                        <div class="flex items-center gap-3">
                            <div class="w-5 h-5 rounded-full bg-green-500 text-white flex items-center justify-center text-xs">
                                <i class="ph ph-check font-bold"></i>
                            </div>
                            <span class="text-gray-700 font-medium">Kasubtim - {{ $kasubtimReview->reviewer->name ?? 'Rizki Maulana' }}</span>
                            <span class="text-gray-400 text-xs">{{ $kasubtimReview ? $kasubtimReview->updated_at->format('d M Y, H:i') : '' }}</span>
                        </div>
                        
                        <!-- Kabag -->
                        <div class="flex items-center gap-3">
                            <div class="w-5 h-5 rounded-full bg-green-500 text-white flex items-center justify-center text-xs">
                                <i class="ph ph-check font-bold"></i>
                            </div>
                            <span class="text-gray-700 font-medium">Kabag - {{ $kabagReview->reviewer->name ?? '-' }}</span>
                            <span class="text-gray-400 text-xs">{{ $kabagReview ? $kabagReview->updated_at->format('d M Y, H:i') : '' }}</span>
                        </div>
                        
                        <!-- Kabiro -->
                        <div class="flex items-center gap-3">
                            <div class="w-5 h-5 rounded-full bg-[#3b2c85] text-white flex items-center justify-center text-xs">
                                <i class="ph ph-dots-three font-bold"></i>
                            </div>
                            <span class="text-gray-700 font-medium">Kepala Biro - Menunggu persetujuan</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-between items-center mt-2">
                <div class="text-sm text-gray-500">
                    Penyusun: {{ $reviu->drafSurat->pembuat->name ?? 'Andi Wijaya' }} &bull; Diajukan: {{ $reviu->created_at->format('d M Y, H:i') }}
                </div>
                @if($reviu->status === 'menunggu')
                    <a href="{{ route('kabiro.review-final.show', $reviu) }}" class="px-6 py-2 bg-[#3b2c85] text-white font-bold rounded-lg hover:bg-[#2e2269] transition-colors shadow-sm text-sm">
                        Review Sekarang
                    </a>
                @else
                    <a href="{{ route('kabiro.review-final.show', $reviu) }}" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50 transition-colors shadow-sm text-sm">
                        Lihat Detail
                    </a>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center text-gray-500">
            Tidak ada draf yang menunggu review Anda saat ini.
        </div>
        @endforelse
    </div>

    @if($reviuSurats->hasPages())
    <div class="mt-6">
        {{ $reviuSurats->links() }}
    </div>
    @endif
</x-app-layout>
