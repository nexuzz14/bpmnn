<x-app-layout>
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('kabag.progress.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-lg flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors shadow-sm">
            <i class="ph ph-arrow-left text-lg"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Detail Progress Surat</h2>
            <p class="text-gray-500 mt-1">Lacak riwayat lengkap dan posisi surat saat ini</p>
        </div>
    </div>

    @php
        $suratMasuk = $drafSurat->suratMasuk;
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <!-- Informasi Surat -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-white">
                    <h3 class="font-bold text-gray-900">Detail Surat</h3>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Asal Surat</span>
                        <p class="text-sm text-gray-900">{{ $suratMasuk->asal_surat ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tanggal Terima</span>
                        <p class="text-sm text-gray-900">{{ $suratMasuk ? \Carbon\Carbon::parse($suratMasuk->tanggal_terima)->translatedFormat('d F Y') : '-' }}</p>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Perihal</span>
                        <p class="text-sm text-gray-900">{{ $suratMasuk->perihal ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nomor Draf</span>
                        <p class="text-sm text-gray-900">DRAFT - {{ sprintf('%03d', $drafSurat->id) }}/{{ date('Y') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-white">
                    <h3 class="font-bold text-gray-900">Informasi Pembuat</h3>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Penyusun (Staf)</span>
                        <p class="text-sm text-gray-900">{{ $drafSurat->pembuat->name ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Dari Sub Tim</span>
                        <p class="text-sm text-gray-900">{{ $drafSurat->pembuat->unitKerja->nama ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jejak Aktivitas (Timeline) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Jejak Aktivitas (Timeline)</h3>
                
                <div class="relative pl-6 space-y-8 before:content-[''] before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-px before:bg-gray-200">
                    <!-- Step 1: Draft Dibuat -->
                    <div class="relative">
                        <div class="absolute -left-[29px] top-1 w-3 h-3 rounded-full bg-green-500 ring-4 ring-white"></div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2">
                            <h4 class="font-bold text-gray-900 text-sm">Konsep Draft Selesai</h4>
                            <span class="text-xs text-gray-400 mt-1 sm:mt-0 font-medium">
                                {{ \Carbon\Carbon::parse($drafSurat->created_at)->translatedFormat('d M Y H:i') }}
                            </span>
                        </div>
                        <div class="bg-gray-50 border border-gray-100 rounded p-4">
                            <p class="text-sm text-gray-700">Draft surat berhasil disusun dan diteruskan untuk proses reviu.</p>
                            <div class="mt-3 text-xs text-gray-500">
                                Oleh: <span class="font-medium text-gray-900">{{ $drafSurat->pembuat->name ?? 'Staf' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Iterasi Reviu -->
                    @foreach($drafSurat->reviuSurat as $reviu)
                        <div class="relative">
                            @if($reviu->status === 'disetujui')
                                <div class="absolute -left-[29px] top-1 w-3 h-3 rounded-full bg-green-500 ring-4 ring-white"></div>
                            @elseif($reviu->status === 'revisi')
                                <div class="absolute -left-[29px] top-1 w-3 h-3 rounded-full bg-red-500 ring-4 ring-white"></div>
                            @else
                                <div class="absolute -left-[29px] top-1 w-3 h-3 rounded-full bg-[#312e81] ring-4 ring-white"></div>
                            @endif
                            
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2">
                                <h4 class="font-bold text-gray-900 text-sm">
                                    @if($reviu->tingkat === '1')
                                        Review Kasubtim
                                    @elseif($reviu->tingkat === '2')
                                        Review Kepala Bagian
                                    @else
                                        Review Kepala Biro
                                    @endif
                                    
                                    @if($reviu->status === 'disetujui')
                                        <span class="ml-2 inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700">Disetujui</span>
                                    @elseif($reviu->status === 'revisi')
                                        <span class="ml-2 inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700">Revisi</span>
                                    @else
                                        <span class="ml-2 inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-[#e0e7ff] text-[#4f46e5]">Menunggu</span>
                                    @endif
                                </h4>
                                <span class="text-xs text-gray-400 mt-1 sm:mt-0 font-medium">
                                    {{ \Carbon\Carbon::parse($reviu->updated_at)->translatedFormat('d M Y H:i') }}
                                </span>
                            </div>
                            
                            @if($reviu->status !== 'menunggu')
                                <div class="bg-gray-50 border border-gray-100 rounded p-4">
                                    @if($reviu->catatan_reviu)
                                        <p class="text-sm text-gray-700 italic border-l-2 border-gray-300 pl-3">"{{ $reviu->catatan_reviu }}"</p>
                                    @else
                                        <p class="text-sm text-gray-700">Telah diperiksa dan disetujui tanpa catatan tambahan.</p>
                                    @endif
                                    
                                    <div class="mt-3 text-xs text-gray-500">
                                        Oleh: <span class="font-medium text-gray-900">{{ $reviu->user->name ?? ($reviu->tingkat == '1' ? 'Kasubtim' : 'Kepala Bagian') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                    
                    @if($drafSurat->status === 'selesai' && $drafSurat->suratFinal)
                        <div class="relative">
                            <div class="absolute -left-[29px] top-1 w-3 h-3 rounded-full bg-green-500 ring-4 ring-white"></div>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2">
                                <h4 class="font-bold text-gray-900 text-sm">Surat Selesai (Final)</h4>
                                <span class="text-xs text-gray-400 mt-1 sm:mt-0 font-medium">
                                    {{ \Carbon\Carbon::parse($drafSurat->suratFinal->created_at)->translatedFormat('d M Y H:i') }}
                                </span>
                            </div>
                            <div class="bg-gray-50 border border-gray-100 rounded p-4">
                                <p class="text-sm text-gray-700">Surat final berhasil diterbitkan dengan nomor: <span class="font-bold text-gray-900">{{ $drafSurat->suratFinal->nomor_surat_final ?? '-' }}</span></p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
