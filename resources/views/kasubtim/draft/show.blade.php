<x-app-layout>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Detail Draft — {{ $drafSurat->suratMasuk->perihal }}</h2>
            <p class="text-gray-500 mt-1">Lihat detail draft dan riwayat persetujuannya</p>
        </div>
        <a href="{{ route('kasubtim.draft.index', ['tab' => 'sudah-diproses']) }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-600 font-medium rounded hover:bg-gray-50 transition-colors shadow-sm text-sm">
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-12">
        <!-- Left Column: PDF & Jejak -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- PDF Viewer -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-[600px]">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <span class="text-sm font-medium text-gray-700">{{ basename($drafSurat->file_draf) }}</span>
                    <a href="{{ Storage::url($drafSurat->file_draf) }}" target="_blank" class="px-3 py-1.5 bg-white border border-gray-200 rounded text-xs font-medium text-gray-600 hover:bg-gray-50 transition-colors shadow-sm">
                        Buka PDF
                    </a>
                </div>
                <div class="flex-1 bg-gray-100 p-4">
                    @if($drafSurat->file_draf)
                        <iframe src="{{ Storage::url($drafSurat->file_draf) }}" class="w-full h-full border border-gray-200 rounded bg-white" frameborder="0"></iframe>
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400 bg-white border border-gray-200 rounded">
                            <p>File draft tidak ditemukan</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Jejak Persetujuan -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-900 mb-6">Jejak Persetujuan</h3>
                <div class="relative pl-6 space-y-8 before:content-[''] before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-px before:bg-gray-200">
                    
                    <!-- Draft Created -->
                    <div class="relative">
                        <div class="absolute -left-[29px] top-1 w-3 h-3 rounded-full bg-green-500 ring-4 ring-white"></div>
                        <div class="flex justify-between items-start mb-1">
                            <h4 class="font-bold text-gray-900 text-sm">Draft dibuat oleh Staf</h4>
                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($drafSurat->created_at)->translatedFormat('d M Y, H:i') }}</span>
                        </div>
                        <p class="text-xs text-gray-500">{{ $drafSurat->pembuat->name ?? '-' }}</p>
                        @if($drafSurat->catatan)
                            <div class="mt-2 bg-gray-50 p-3 rounded border border-gray-100 text-sm text-gray-700">
                                {{ $drafSurat->catatan }}
                            </div>
                        @endif
                    </div>

                    <!-- Reviews -->
                    @foreach($drafSurat->reviuSurat as $reviu)
                        <div class="relative">
                            @php
                                $color = 'bg-gray-300';
                                if($reviu->status === 'disetujui') $color = 'bg-green-500';
                                elseif($reviu->status === 'revisi') $color = 'bg-red-500';
                                elseif($reviu->status === 'menunggu') $color = 'bg-yellow-500';
                                
                                $tingkatLabel = 'Kasubtim';
                                if($reviu->tingkat == '2') $tingkatLabel = 'Kepala Bagian';
                                elseif($reviu->tingkat == '3') $tingkatLabel = 'Kepala Biro';
                            @endphp
                            <div class="absolute -left-[29px] top-1 w-3 h-3 rounded-full {{ $color }} ring-4 ring-white"></div>
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="font-bold text-gray-900 text-sm">Review {{ $tingkatLabel }}</h4>
                                <span class="text-xs text-gray-500">{{ $reviu->tanggal_reviu ? \Carbon\Carbon::parse($reviu->tanggal_reviu)->translatedFormat('d M Y, H:i') : '-' }}</span>
                            </div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xs text-gray-500">{{ $reviu->reviewer->name ?? 'Sistem' }}</span>
                                @if($reviu->status === 'disetujui')
                                    <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold rounded">Disetujui</span>
                                @elseif($reviu->status === 'revisi')
                                    <span class="px-2 py-0.5 bg-red-100 text-red-700 text-[10px] font-bold rounded">Revisi</span>
                                @else
                                    <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-[10px] font-bold rounded">Menunggu</span>
                                @endif
                            </div>
                            @if($reviu->catatan_reviu)
                                <div class="mt-2 bg-gray-50 p-3 rounded border border-gray-100 text-sm text-gray-700">
                                    {{ $reviu->catatan_reviu }}
                                </div>
                            @endif
                        </div>
                    @endforeach

                </div>
            </div>

        </div>

        <!-- Right Column: Info Cards -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Ringkasan Surat Masuk -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4">Ringkasan Surat Masuk</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500">Nomor Surat</p>
                        <p class="text-sm font-medium text-gray-900">{{ $drafSurat->suratMasuk->nomor_surat }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Tanggal</p>
                        <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($drafSurat->suratMasuk->tanggal_terima)->translatedFormat('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Pengirim</p>
                        <p class="text-sm font-medium text-gray-900">{{ $drafSurat->suratMasuk->asal_surat }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Klasifikasi</p>
                        <p class="text-sm font-medium text-gray-900">Surat Dinas</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Perihal</p>
                        <p class="text-sm font-medium text-gray-900">{{ $drafSurat->suratMasuk->perihal }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Ringkasan Isi</p>
                        <div class="mt-1 bg-gray-50 p-3 rounded text-sm text-gray-700">
                            {{ $drafSurat->suratMasuk->ringkasan ?? '-' }}
                        </div>
                    </div>
                    @php
                        $disposisi = $drafSurat->suratMasuk->disposisi()->where('ke_user_id', auth()->id())->first();
                    @endphp
                    @if($disposisi && $disposisi->catatan)
                    <div>
                        <p class="text-xs text-gray-500">Poin Tindak Lanjut</p>
                        <div class="mt-1 text-sm text-gray-700 space-y-1">
                            {!! nl2br(e($disposisi->catatan)) !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Info Surat -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4">Info Surat</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500">Perihal</p>
                        <p class="text-sm font-medium text-gray-900">{{ $drafSurat->suratMasuk->perihal }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Versi</p>
                        <p class="text-sm font-medium text-gray-900">v{{ $drafSurat->versi ?? '1' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Diunggah Oleh</p>
                        <p class="text-sm font-medium text-gray-900">{{ $drafSurat->pembuat->name ?? '-' }} (Staf)</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Waktu Unggah</p>
                        <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($drafSurat->created_at)->translatedFormat('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
