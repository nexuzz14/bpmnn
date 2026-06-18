<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Buat Disposisi ke Kasubtim &mdash; {{ $disposisi->suratMasuk->nomor_surat ?? '-' }}</h2>
        <p class="text-gray-500 mt-1">Buat disposisi untuk Kepala Sub Tim</p>
    </div>

    <!-- Info Banner -->
    <div class="bg-[#f3f0ff] p-5 rounded-lg border border-[#ddd6fe] mb-6">
        <p class="text-gray-900 font-medium"><span class="text-gray-600 font-normal">Nomor:</span> {{ $disposisi->suratMasuk->nomor_surat ?? '-' }}</p>
        <p class="text-gray-900 font-medium"><span class="text-gray-600 font-normal">Perihal:</span> {{ $disposisi->suratMasuk->perihal ?? '-' }}</p>
        <p class="text-gray-900 font-medium"><span class="text-gray-600 font-normal">Pengirim:</span> {{ $disposisi->suratMasuk->asal_surat ?? '-' }}</p>
    </div>

    <!-- Accordion Ringkasan -->
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-8 overflow-hidden" x-data="{ open: true }">
        <button @click="open = !open" class="w-full flex justify-between items-center p-4 bg-white hover:bg-gray-50 transition-colors">
            <span class="font-bold text-gray-900">Ringkasan Surat Masuk</span>
            <i class="ph" :class="open ? 'ph-caret-up' : 'ph-caret-down'"></i>
        </button>
        
        <div x-show="open" class="p-6 border-t border-gray-100 space-y-4">
            <h4 class="font-bold text-gray-900 mb-4">Ringkasan Surat Masuk</h4>
            
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Nomor Surat</p>
                    <p class="text-gray-900 font-medium">{{ $disposisi->suratMasuk->nomor_surat ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Tanggal</p>
                    <p class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($disposisi->suratMasuk->tanggal_surat)->translatedFormat('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Pengirim</p>
                    <p class="text-gray-900 font-medium">{{ $disposisi->suratMasuk->asal_surat ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Klasifikasi</p>
                    <p class="text-gray-900 font-medium">Surat Dinas</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Perihal</p>
                    <p class="text-gray-900 font-medium">{{ $disposisi->suratMasuk->perihal ?? '-' }}</p>
                </div>
                
                <div class="pt-2">
                    <p class="text-xs text-gray-500 mb-2">Keterangan / Ringkasan Isi</p>
                    <div class="bg-gray-50 p-4 rounded border border-gray-100 text-sm text-gray-700">
                        {{ $disposisi->suratMasuk->keterangan ?: 'Tidak ada keterangan/ringkasan tambahan.' }}
                    </div>
                </div>
                
                <div class="pt-2">
                    <p class="text-xs text-gray-500 mb-2">Instruksi Disposisi Sebelumnya</p>
                    <div class="text-sm text-gray-900 space-y-1">
                        <p>{{ $disposisi->instruksi ?: 'Tidak ada instruksi khusus.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stepper -->
    <div class="flex items-center gap-4 mb-8">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-[#312e81] text-white flex items-center justify-center text-sm font-bold">
                <i class="ph ph-check"></i>
            </div>
            <span class="text-gray-400 font-medium">Terima Disposisi</span>
        </div>
        
        <div class="flex-1 h-0.5 bg-[#312e81]"></div>
        
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-[#312e81] text-white flex items-center justify-center text-sm font-bold">
                2
            </div>
            <span class="text-gray-900 font-bold">Disposisi ke Kasubtim</span>
        </div>
        
        <div class="flex-1 h-0.5 bg-gray-200"></div>
        
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-sm font-bold">
                3
            </div>
            <span class="text-gray-400 font-medium">Selesai</span>
        </div>
    </div>

    <!-- Form Disposisi -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="border-b border-gray-100 p-5">
            <h3 class="font-bold text-gray-900">Disposisi ke Kasubtim</h3>
        </div>
        
        <form action="{{ route('kabag.disposisi.update', $disposisi) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label for="kasubtim_id" class="block text-sm font-bold text-gray-700 mb-2">Tujukan ke Kasubtim</label>
                    <select name="kasubtim_id" id="kasubtim_id" class="w-full rounded border-gray-300 focus:border-[#312e81] focus:ring-[#312e81] shadow-sm text-sm text-gray-900" required>
                        <option value="">Pilih Kasubtim...</option>
                        @foreach($penerimas as $penerima)
                            <option value="{{ $penerima->id }}" {{ old('kasubtim_id') == $penerima->id ? 'selected' : '' }}>
                                {{ $penerima->name }} - {{ $penerima->jabatan ?? str_replace('_', ' ', $penerima->role) }}
                            </option>
                        @endforeach
                    </select>
                    @error('kasubtim_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="instruksi" class="block text-sm font-bold text-gray-700 mb-2">Instruksi / Arahan</label>
                    <textarea name="instruksi" id="instruksi" rows="4" class="w-full rounded border-gray-300 focus:border-[#312e81] focus:ring-[#312e81] shadow-sm text-sm text-gray-900" required placeholder="Tuliskan instruksi untuk Kasubtim...">{{ old('instruksi') }}</textarea>
                    @error('instruksi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tenggat_waktu" class="block text-sm font-bold text-gray-700 mb-2">Batas Waktu</label>
                        <input type="date" name="tenggat_waktu" id="tenggat_waktu" value="{{ old('tenggat_waktu', \Carbon\Carbon::parse($disposisi->tenggat_waktu)->format('Y-m-d')) }}" class="w-full rounded border-gray-300 focus:border-[#312e81] focus:ring-[#312e81] shadow-sm text-sm text-gray-900" required>
                        @error('tenggat_waktu') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="prioritas" class="block text-sm font-bold text-gray-700 mb-2">Prioritas</label>
                        <select name="prioritas" id="prioritas" class="w-full rounded border-gray-300 focus:border-[#312e81] focus:ring-[#312e81] shadow-sm text-sm text-gray-900">
                            <option value="">Pilih prioritas...</option>
                            <option value="biasa" {{ in_array($disposisi->prioritas, ['biasa', 'rendah', 'Normal']) ? 'selected' : '' }}>Biasa</option>
                            <option value="segera" {{ in_array($disposisi->prioritas, ['segera', 'sedang']) ? 'selected' : '' }}>Segera</option>
                            <option value="sangat_segera" {{ in_array($disposisi->prioritas, ['sangat_segera', 'tinggi', 'Tinggi']) ? 'selected' : '' }}>Sangat Segera</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-4">
                <button type="button" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded hover:bg-gray-50 transition-colors shadow-sm">
                    Simpan Draf
                </button>
                <button type="submit" class="px-6 py-2.5 bg-[#312e81] text-white font-medium rounded hover:bg-[#1e1b4b] transition-colors shadow-sm">
                    Kirim ke Kasubtim
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
