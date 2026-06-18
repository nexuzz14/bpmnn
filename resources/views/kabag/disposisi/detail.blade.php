<x-app-layout>
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('kabag.disposisi.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-lg flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors shadow-sm">
                <i class="ph ph-arrow-left text-lg"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Detail Surat Masuk</h2>
                <p class="text-gray-500 mt-1">Informasi detail surat dan lampiran dokumen</p>
            </div>
        </div>
        <div>
            <a href="{{ route('kabag.disposisi.show', $disposisi) }}" class="px-5 py-2.5 bg-[#312e81] text-white font-medium rounded-lg hover:bg-[#1e1b4b] transition-colors shadow-sm">
                Buat Disposisi ke Kasubtim
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Informasi Detail -->
        <div class="space-y-6">
            <!-- Informasi Disposisi Sebelumnya -->
            <div class="bg-[#f0f9ff] border-l-4 border-l-[#0284c7] rounded-r-xl p-6 shadow-sm">
                <p class="text-xs text-gray-500 font-bold mb-1 tracking-wider uppercase">Didisposisikan Oleh</p>
                <p class="font-bold text-gray-900 mb-4">{{ $disposisi->pengirim->name ?? 'Tata Usaha' }}</p>
                
                <p class="text-xs text-gray-500 font-bold mb-1 tracking-wider uppercase">Instruksi / Catatan</p>
                <p class="text-gray-800 text-sm italic border-l-2 border-[#0284c7] pl-3">"{{ $disposisi->catatan ?? $disposisi->instruksi }}"</p>
                
                <div class="mt-4 pt-4 border-t border-blue-100 flex justify-between items-center">
                    <div>
                        <p class="text-xs text-gray-500 font-bold mb-1 tracking-wider uppercase">Batas Waktu</p>
                        <p class="text-sm font-bold text-[#eab308]">
                            {{ \Carbon\Carbon::parse($disposisi->tenggat_waktu)->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Detail Surat -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-gray-900 mb-6 pb-4 border-b border-gray-100 text-lg">Informasi Surat</h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 font-medium mb-1">Nomor Surat</p>
                        <p class="text-gray-900 font-bold text-lg">{{ $disposisi->suratMasuk->nomor_surat ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium mb-1">Asal / Pengirim</p>
                        <p class="text-gray-900 font-medium">{{ $disposisi->suratMasuk->asal_surat ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium mb-1">Perihal</p>
                        <p class="text-gray-900 font-medium">{{ $disposisi->suratMasuk->perihal ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium mb-1">Tanggal Surat</p>
                        <p class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($disposisi->suratMasuk->tanggal_surat)->translatedFormat('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium mb-1">Sifat / Klasifikasi</p>
                        <p class="text-gray-900 font-medium">{{ ucfirst($disposisi->suratMasuk->sifat ?? 'Biasa') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Surat -->
        <div class="bg-gray-100 rounded-xl overflow-hidden shadow-inner flex flex-col h-[700px] border border-gray-200">
            <div class="p-4 bg-gray-200 border-b border-gray-300 flex justify-between items-center">
                <span class="text-sm font-bold text-gray-700">Preview Dokumen</span>
                @if($disposisi->suratMasuk->file_surat)
                    <a href="{{ Storage::url($disposisi->suratMasuk->file_surat) }}" target="_blank" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 font-medium rounded text-xs hover:bg-gray-50 transition-colors shadow-sm flex items-center gap-2">
                        <i class="ph ph-arrow-square-out"></i>
                        Buka Penuh
                    </a>
                @endif
            </div>
            @if($disposisi->suratMasuk->file_surat)
                <iframe src="{{ Storage::url($disposisi->suratMasuk->file_surat) }}" class="w-full flex-1"></iframe>
            @else
                <div class="flex-1 flex flex-col items-center justify-center text-gray-400">
                    <i class="ph ph-file-dashed text-5xl mb-3"></i>
                    <p>File surat tidak tersedia</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
