<x-app-layout>
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Disposisi Masuk</h2>
            <p class="text-gray-500 mt-1">Daftar disposisi dari Kepala Bagian yang perlu ditindaklanjuti</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 text-green-800 p-4 rounded-xl border border-green-100 flex items-center gap-3">
            <i class="ph ph-check-circle text-xl text-green-600"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Tabs -->
    <div class="flex items-center gap-6 border-b border-gray-200 mb-6">
        <a href="{{ route('kasubtim.penugasan.index') }}" class="flex items-center gap-2 pb-3 font-medium {{ $tab === 'belum' ? 'border-b-2 border-[#5c3a21] text-[#5c3a21]' : 'text-gray-500 hover:text-gray-700' }}">
            Belum Ditindaklanjuti
            @if($belumCount > 0)
                <span class="px-2 py-0.5 bg-[#fef3c7] text-[#92400e] text-xs font-bold rounded-full">{{ $belumCount }}</span>
            @endif
        </a>
        <a href="{{ route('kasubtim.penugasan.index', ['tab' => 'sudah']) }}" class="flex items-center gap-2 pb-3 font-medium {{ $tab === 'sudah' ? 'border-b-2 border-[#5c3a21] text-[#5c3a21]' : 'text-gray-500 hover:text-gray-700' }}">
            Sudah Ditindaklanjuti
        </a>
    </div>

    <!-- Content -->
    <div class="space-y-4">
        @forelse($disposisis as $disposisi)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col sm:flex-row justify-between items-start gap-4 {{ $tab === 'belum' ? 'border-l-4 border-l-[#5c3a21]' : '' }}">
                <div class="flex-1 w-full">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-500">#{{ $disposisi->suratMasuk->nomor_surat }}</span>
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-gray-400">{{ \Carbon\Carbon::parse($disposisi->created_at)->translatedFormat('d M Y, H:i') }}</span>
                            @if($tab === 'belum' && $disposisi->status === 'menunggu')
                                <span class="text-xs font-bold text-[#92400e] bg-[#fef3c7] px-2 py-1 rounded">Belum Dibaca</span>
                            @endif
                        </div>
                    </div>
                    
                    <h4 class="font-bold text-gray-900 text-lg mb-2">{{ $disposisi->suratMasuk->perihal }}</h4>
                    <p class="text-sm text-gray-500 mb-4">Dari: Kabag {{ $disposisi->pengirim ? $disposisi->pengirim->name : '-' }} &nbsp;&middot;&nbsp; Pengirim asal: {{ $disposisi->suratMasuk->asal_surat }}</p>
                    
                    <div class="border-l-2 border-[#5c3a21] pl-4 py-2 bg-[#fefce8]/50 mb-4">
                        <p class="text-sm text-[#92400e] italic">"{{ $disposisi->catatan ?? '-' }}"</p>
                    </div>

                    <div class="flex justify-between items-center mt-2">
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-gray-500">Batas waktu: {{ \Carbon\Carbon::parse($disposisi->tenggat_waktu)->translatedFormat('d M Y') }}</span>
                            @php
                                $isTinggi = $disposisi->suratMasuk->sifat === 'segera' || $disposisi->suratMasuk->sifat === 'sangat_segera';
                            @endphp
                            @if($isTinggi)
                                <span class="px-2 py-1 bg-red-100 text-red-600 text-xs font-bold rounded-full">Tinggi</span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-full">Normal</span>
                            @endif
                        </div>
                        
                        <div class="flex items-center gap-4">
                            @if($tab === 'belum')
                                <a href="{{ route('kasubtim.penugasan.show', ['penugasan' => $disposisi->id, 'action' => 'tugaskan']) }}" class="inline-flex px-6 py-2 bg-[#5c3a21] text-white font-medium rounded text-xs hover:bg-[#4a2e1a] transition-colors shadow-sm">
                                    Disposisi ke Staf
                                </a>
                                <a href="{{ route('kasubtim.penugasan.show', $disposisi->id) }}" class="text-sm text-gray-500 hover:text-[#5c3a21]">Lihat Detail</a>
                            @else
                                <span class="text-sm text-green-600 font-medium">Sudah Didisposisi ke Staf</span>
                                <a href="{{ route('kasubtim.penugasan.show', $disposisi) }}" class="text-sm text-gray-500 hover:text-[#5c3a21]">Lihat</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center text-gray-500">
                Tidak ada data disposisi.
            </div>
        @endforelse

        <div class="mt-4">
            {{ $disposisis->appends(['tab' => $tab])->links() }}
        </div>
    </div>
</x-app-layout>
