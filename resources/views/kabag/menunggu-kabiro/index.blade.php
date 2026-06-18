<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Menunggu Persetujuan Kepala Biro</h2>
        <p class="text-gray-500 mt-1">Surat yang telah Anda setujui dan menunggu persetujuan final dari Kepala Biro</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Menunggu Kabiro</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['menunggu'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Disetujui Bulan Ini</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['disetujui_bulan_ini'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Dikembalikan</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['dikembalikan'] }}</p>
        </div>
    </div>

    <!-- Info Banner -->
    <div class="bg-[#f3e8ff] border-l-4 border-[#7e22ce] rounded-r-xl p-4 mb-6">
        <h4 class="text-[#6b21a8] font-medium">{{ $stats['menunggu'] }} surat menunggu persetujuan Kepala Biro</h4>
        <p class="text-[#7e22ce] text-sm mt-1">Surat akan otomatis diteruskan ke TU untuk upload TTD setelah disetujui</p>
    </div>

    <!-- Cards -->
    <div class="space-y-4">
        @forelse($reviuSurats as $reviu)
            @php 
                $draf = $reviu->drafSurat;
                
                // Get timeline dynamically
                $timelineReviews = \App\Models\ReviuSurat::with('user')
                    ->where('draf_surat_id', $draf->id)
                    ->orderBy('created_at', 'asc')
                    ->get();
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-sm font-medium text-gray-500">DRAFT-{{ str_pad($draf->id, 3, '0', STR_PAD_LEFT) }}/{{ now()->year }} - v{{ $draf->versi ?? 1 }}</span>
                        
                        @if($reviu->status === 'menunggu')
                            <span class="px-2.5 py-1 rounded bg-[#f3e8ff] text-[#7e22ce] text-xs font-medium">Menunggu Kabiro</span>
                        @elseif($reviu->status === 'disetujui')
                            <span class="px-2.5 py-1 rounded bg-green-100 text-green-700 text-xs font-medium">Disetujui</span>
                        @else
                            <span class="px-2.5 py-1 rounded bg-red-100 text-red-700 text-xs font-medium">Dikembalikan</span>
                        @endif
                    </div>
                    
                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $draf->suratMasuk->perihal }}</h3>
                    <p class="text-gray-500 text-sm mb-6">-</p>

                    <div class="bg-gray-50/50 rounded-xl border border-gray-100 p-5">
                        <h4 class="text-sm font-medium text-gray-700 mb-4">Riwayat Persetujuan:</h4>
                        <div class="space-y-4">
                            @foreach($timelineReviews as $tr)
                                <div class="flex gap-3">
                                    <div class="mt-0.5">
                                        @if($tr->status === 'disetujui')
                                            <div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center text-white">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                        @elseif($tr->status === 'revisi')
                                            <div class="w-5 h-5 rounded-full bg-red-500 flex items-center justify-center text-white">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </div>
                                        @else
                                            <div class="w-5 h-5 rounded-full bg-[#4f46e5] flex items-center justify-center text-white">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">
                                            @if($tr->tingkat == '1')
                                                Kasubtim
                                            @elseif($tr->tingkat == '2')
                                                Kabag
                                            @else
                                                Kepala Biro
                                            @endif
                                            
                                            @if($tr->user)
                                                - <span class="font-normal text-gray-500">{{ $tr->user->name }}</span>
                                            @endif
                                            
                                            @if($tr->status === 'disetujui' || $tr->status === 'revisi')
                                                <span class="text-gray-400 font-normal ml-2">{{ \Carbon\Carbon::parse($tr->updated_at)->translatedFormat('d M Y, H:i') }}</span>
                                            @else
                                                <span class="text-gray-400 font-normal ml-2">- Menunggu persetujuan</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-gray-50/50 flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        Diajukan: {{ \Carbon\Carbon::parse($draf->created_at)->translatedFormat('d M Y') }}
                    </div>
                    <div>
                        <a href="{{ route('kabag.menunggu-kabiro.show', $reviu->id) }}" class="inline-flex px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded text-sm hover:bg-gray-50 transition-colors shadow-sm">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <p class="text-gray-500">Tidak ada surat yang sedang menunggu persetujuan Kepala Biro.</p>
            </div>
        @endforelse
    </div>

    @if($reviuSurats->hasPages())
        <div class="mt-6">
            {{ $reviuSurats->links() }}
        </div>
    @endif
</x-app-layout>
