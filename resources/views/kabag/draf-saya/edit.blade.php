<x-app-layout>
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('kabag.draf-saya.show', $drafSurat) }}" class="w-10 h-10 bg-white border border-gray-200 rounded-lg flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors shadow-sm">
            <i class="ph ph-arrow-left text-lg"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Perbaikan Draf Surat</h2>
            <p class="text-gray-500 mt-1">Upload revisi draf surat berdasarkan feedback dari atasan</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Kolom Kiri: Catatan & History -->
        <div class="space-y-6">
            <!-- Catatan Revisi -->
            <div class="bg-red-50 border border-red-200 rounded-xl overflow-hidden shadow-sm">
                <div class="p-5 border-b border-red-100 bg-red-100/50">
                    <h3 class="font-bold text-red-800 flex items-center gap-2">
                        <i class="ph ph-warning-circle text-red-600"></i>
                        Catatan Revisi
                    </h3>
                </div>
                <div class="p-5 space-y-4">
                    @php
                        $revisi = $drafSurat->reviuSurat->where('status', 'revisi')->last();
                        $jabatan = 'Atasan';
                        if ($revisi) {
                            if ($revisi->tingkat == '1') $jabatan = 'Kasubtim';
                            elseif ($revisi->tingkat == '2') $jabatan = 'Kabag Keuangan';
                            elseif ($revisi->tingkat == 'final') $jabatan = 'Kepala Biro';
                        }
                    @endphp
                    @if($revisi)
                    <div>
                        <span class="block text-xs font-bold text-red-700 uppercase tracking-wider mb-1">Dari {{ $jabatan }} ({{ $revisi->user->name ?? '-' }})</span>
                        <div class="bg-white/60 rounded p-3 text-sm text-red-900 border border-red-100 font-medium">
                            {{ $revisi->catatan ?? 'Terdapat bagian yang perlu diperbaiki sesuai arahan lisan/rapat sebelumnya.' }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Draf Sebelumnya -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h3 class="font-semibold text-gray-800">Draf Sebelumnya</h3>
                    @if($drafSurat->file_draf)
                    <a href="{{ Storage::url($drafSurat->file_draf) }}" target="_blank" class="text-[#701a35] hover:underline text-xs font-medium">Lihat Full PDF</a>
                    @endif
                </div>
                <div class="h-80 bg-gray-100">
                    @if($drafSurat->file_draf)
                        <iframe src="{{ Storage::url($drafSurat->file_draf) }}" class="w-full h-full border-0" title="Preview Draf"></iframe>
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                            <i class="ph ph-file-x text-4xl mb-2"></i>
                            <p>File tidak ditemukan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Upload Revisi -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <h3 class="font-bold text-gray-900 mb-6 text-lg">Upload Draf Revisi</h3>
                    
                    <form action="{{ route('kabag.draf-saya.update', $drafSurat) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-10 text-center hover:bg-gray-50 transition-colors bg-white relative">
                            <input type="file" name="file_draf" id="file_draf" accept=".pdf" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="document.getElementById('file-name').textContent = this.files[0].name">
                            <i class="ph ph-file-pdf text-5xl text-gray-400 mb-4 block"></i>
                            <p class="text-base font-medium text-gray-900 mb-1">Pilih file PDF draf hasil revisi</p>
                            <p class="text-sm text-gray-500">Atau seret dan lepas file ke area ini (Maks. 2MB)</p>
                            <p id="file-name" class="mt-4 text-sm font-bold text-[#701a35]"></p>
                        </div>
                        @error('file_draf')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror

                        <div class="pt-4 border-t border-gray-100">
                            <button type="submit" class="w-full px-4 py-3 bg-[#701a35] text-white font-medium rounded-lg hover:bg-[#5b152b] transition-colors shadow-sm flex items-center justify-center gap-2">
                                <i class="ph ph-paper-plane-right"></i> Kirim Revisi Draf
                            </button>
                            <p class="text-xs text-center text-gray-500 mt-3">Draf akan dikirim kembali ke {{ $jabatan }} untuk direview.</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
