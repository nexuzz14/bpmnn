<x-app-layout>
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('kasubtim.progress.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-lg flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors shadow-sm">
            <i class="ph ph-arrow-left text-lg"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Detail Progress Surat</h2>
            <p class="text-gray-500 mt-1">Lacak riwayat lengkap dan posisi surat saat ini</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informasi Surat -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-semibold text-gray-800">Detail Surat</h3>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Asal Surat</span>
                        <p class="text-sm font-semibold text-gray-900">{{ $drafSurat->suratMasuk->asal_surat }}</p>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Tanggal Terima</span>
                        <p class="text-sm text-gray-800">{{ \Carbon\Carbon::parse($drafSurat->suratMasuk->tanggal_terima)->translatedFormat('d F Y') }}</p>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Perihal</span>
                        <p class="text-sm text-gray-800">{{ $drafSurat->suratMasuk->perihal }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-semibold text-gray-800">Tim Terlibat</h3>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Staf Pembuat Draf</span>
                        <div class="flex items-center gap-3 mt-1">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                                {{ substr($drafSurat->pembuat->name ?? 'S', 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $drafSurat->pembuat->name ?? '-' }}</p>
                                <p class="text-xs text-gray-500">Staf</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Dokumen Preview -->
            @if($drafSurat->suratMasuk->file_surat)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h3 class="font-semibold text-gray-800">Surat Asli</h3>
                    <a href="{{ Storage::url($drafSurat->suratMasuk->file_surat) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs font-medium flex items-center gap-1">
                        <i class="ph ph-arrow-square-out"></i> Buka
                    </a>
                </div>
                <div class="h-64 bg-gray-100 relative">
                    <iframe src="{{ Storage::url($drafSurat->suratMasuk->file_surat) }}" class="w-full h-full border-0" title="Preview Surat"></iframe>
                </div>
            </div>
            @endif
        </div>

        <!-- Timeline Progress -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                <h3 class="font-bold text-gray-900 mb-8 text-lg">Timeline Progress Surat</h3>
                
                <div class="relative border-l border-gray-200 ml-3 space-y-8">
                    <!-- Step 1: Draf Dibuat -->
                    <div class="relative pl-8">
                        <div class="absolute -left-3 top-0 w-6 h-6 rounded-full bg-blue-500 border-4 border-white flex items-center justify-center">
                            <i class="ph ph-check text-white text-xs"></i>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-1">
                            <h4 class="font-bold text-gray-900 text-base">Draf Dibuat</h4>
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded font-medium mt-1 sm:mt-0">
                                {{ \Carbon\Carbon::parse($drafSurat->created_at)->translatedFormat('d F Y, H:i') }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Staf <span class="font-medium">{{ $drafSurat->pembuat->name ?? '-' }}</span> telah membuat draf awal surat.</p>
                        
                        @if($drafSurat->catatan)
                        <div class="mt-3 bg-gray-50 border border-gray-200 rounded p-3 text-sm text-gray-700">
                            <span class="font-medium text-xs text-gray-500 uppercase block mb-1">Catatan Staf:</span>
                            {{ $drafSurat->catatan }}
                        </div>
                        @endif
                        
                        @if($drafSurat->file_draf)
                        <a href="{{ Storage::url($drafSurat->file_draf) }}" target="_blank" class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-gray-200 text-sm font-medium rounded hover:bg-gray-50 text-blue-600 transition-colors">
                            <i class="ph ph-file-pdf"></i> Lihat Draf Awal
                        </a>
                        @endif
                    </div>

                    <!-- Step 2: Review Berjenjang -->
                    @foreach($drafSurat->reviuSurat as $reviu)
                    <div class="relative pl-8">
                        @php
                            $isDone = $reviu->status !== 'menunggu';
                            $isRejected = $reviu->status === 'revisi';
                            $colorClass = $isRejected ? 'bg-red-500' : ($isDone ? 'bg-blue-500' : 'bg-amber-400');
                            $iconClass = $isRejected ? 'ph-x' : ($isDone ? 'ph-check' : 'ph-hourglass-high');
                            
                            $jabatan = 'Atasan';
                            if ($reviu->tingkat == '1') $jabatan = 'Kasubtim';
                            elseif ($reviu->tingkat == '2') $jabatan = 'Kabag';
                            elseif ($reviu->tingkat == 'final') $jabatan = 'Kabiro';
                        @endphp
                        
                        <div class="absolute -left-3 top-0 w-6 h-6 rounded-full {{ $colorClass }} border-4 border-white flex items-center justify-center">
                            <i class="ph {{ $iconClass }} text-white text-xs"></i>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-1">
                            <h4 class="font-bold text-gray-900 text-base">Review {{ $jabatan }}</h4>
                            @if($isDone)
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded font-medium mt-1 sm:mt-0">
                                {{ \Carbon\Carbon::parse($reviu->updated_at)->translatedFormat('d F Y, H:i') }}
                            </span>
                            @else
                            <span class="text-xs text-amber-700 bg-amber-100 px-2 py-1 rounded font-medium mt-1 sm:mt-0">
                                Menunggu
                            </span>
                            @endif
                        </div>
                        
                        @if($reviu->status === 'menunggu')
                            <p class="text-sm text-gray-600 mt-1">Sedang menunggu proses review oleh <span class="font-medium">{{ $jabatan }}</span>.</p>
                        @elseif($reviu->status === 'disetujui')
                            <p class="text-sm text-gray-600 mt-1">Draf telah <strong class="text-green-600">disetujui</strong> oleh <span class="font-medium">{{ $reviu->user->name ?? $jabatan }}</span>.</p>
                        @elseif($reviu->status === 'revisi')
                            <p class="text-sm text-gray-600 mt-1">Draf <strong class="text-red-600">dikembalikan untuk direvisi</strong> oleh <span class="font-medium">{{ $reviu->user->name ?? $jabatan }}</span>.</p>
                        @endif

                        @if($reviu->catatan)
                        <div class="mt-3 {{ $isRejected ? 'bg-red-50 border-red-200' : 'bg-gray-50 border-gray-200' }} border rounded p-3 text-sm text-gray-700">
                            <span class="font-medium text-xs {{ $isRejected ? 'text-red-500' : 'text-gray-500' }} uppercase block mb-1">Catatan {{ $jabatan }}:</span>
                            {{ $reviu->catatan }}
                        </div>
                        @endif
                    </div>
                    @endforeach

                    <!-- Step 3: TTD & Distribusi -->
                    <div class="relative pl-8 opacity-{{ $drafSurat->suratFinal ? '100' : '50' }}">
                        @php
                            $isTtd = $drafSurat->suratFinal && $drafSurat->suratFinal->status !== 'menunggu_ttd';
                            $colorClassFinal = $isTtd ? 'bg-blue-500' : 'bg-gray-300';
                            $iconClassFinal = $isTtd ? 'ph-check' : 'ph-clock';
                        @endphp
                        
                        <div class="absolute -left-3 top-0 w-6 h-6 rounded-full {{ $colorClassFinal }} border-4 border-white flex items-center justify-center">
                            <i class="ph {{ $iconClassFinal }} text-white text-xs"></i>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-1">
                            <h4 class="font-bold text-gray-900 text-base">Selesai & Distribusi</h4>
                            @if($isTtd)
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded font-medium mt-1 sm:mt-0">
                                {{ \Carbon\Carbon::parse($drafSurat->suratFinal->updated_at)->translatedFormat('d F Y, H:i') }}
                            </span>
                            @endif
                        </div>
                        
                        @if(!$drafSurat->suratFinal)
                            <p class="text-sm text-gray-500 mt-1">Menunggu semua proses review selesai.</p>
                        @elseif($drafSurat->suratFinal->status === 'menunggu_ttd')
                            <p class="text-sm text-gray-600 mt-1">Surat sedang menunggu proses upload tanda tangan (TTD) oleh TU.</p>
                        @elseif($drafSurat->suratFinal->status === 'terdistribusi')
                            <p class="text-sm text-gray-600 mt-1">Surat final telah ditandatangani dan berhasil didistribusikan.</p>
                            
                            @if($drafSurat->suratFinal->file_ttd)
                            <a href="{{ Storage::url($drafSurat->suratFinal->file_ttd) }}" target="_blank" class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 bg-green-50 text-green-700 font-medium rounded hover:bg-green-100 transition-colors border border-green-200 text-sm">
                                <i class="ph ph-check-circle"></i> Lihat Surat Final
                            </a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
