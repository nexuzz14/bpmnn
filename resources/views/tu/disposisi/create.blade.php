<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Catat Surat & Disposisi ke Kabag</h2>
        <p class="text-gray-500 mt-1">Catat agenda TU dan buat disposisi ke Kepala Bagian</p>
    </div>

    <!-- Informasi Surat (Blue Banner) -->
    <div class="bg-[#f0f9ff] border-l-4 border-[#0284c7] rounded-r-xl p-2 MB-8">
        <p class="font-bold text-gray-900 mb-1">Nomor: {{ $suratMasuk->nomor_surat }}</p>
        <p class="text-gray-600 text-sm mb-1">Perihal: {{ $suratMasuk->perihal }}</p>
        <p class="text-gray-600 text-sm">Pengirim: {{ $suratMasuk->asal_surat }}</p>
    </div>

    <!-- Form Disposisi -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 max-w-4xl overflow-hidden">
        <form action="{{ route('tu.disposisi.store', $suratMasuk) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Agenda TU & File Surat -->
            <div class="border-b border-gray-100">
                <div class="p-6">
                    <h3 class="font-bold text-gray-900 mb-6">Agenda TU & Surat Masuk</h3>
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label for="nomor_agenda" class="block text-sm font-medium text-gray-700">Nomor Agenda TU</label>
                            <input type="text" name="nomor_agenda" id="nomor_agenda" value="{{ old('nomor_agenda', $suratMasuk->nomor_agenda) }}" class="w-full rounded-lg border-gray-300 focus:border-[#0284c7] focus:ring-[#0284c7] shadow-sm transition-colors text-sm text-gray-900" placeholder="Contoh: 001/AGD/IV/2026">
                        </div>
                        <div class="space-y-2">
                            <label for="file_surat" class="block text-sm font-medium text-gray-700">Upload File Surat Masuk (PDF)</label>
                            @if($suratMasuk->file_surat)
                                <div class="text-sm text-green-600 mb-2 font-medium">
                                    <i class="ph ph-check-circle"></i> File surat sudah ada. Anda bisa mengunggah file baru untuk menggantinya.
                                </div>
                            @endif
                            <input type="file" name="file_surat" id="file_surat" accept=".pdf" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 file:mr-4 file:rounded-md file:border-0 file:bg-[#f0f9ff] file:px-4 file:py-2 file:text-sm file:font-semibold file:text-[#0284c7] hover:file:bg-[#e0f2fe] focus:border-[#0284c7] focus:ring-[#0284c7] transition-all">
                            <p class="text-xs text-gray-500 mt-1">Format yang didukung: PDF. Maksimal ukuran: 2MB.</p>
                            @error('file_surat') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan TU</label>
                            <textarea name="catatan" id="catatan" rows="3" class="w-full rounded-lg border-gray-300 focus:border-[#0284c7] focus:ring-[#0284c7] shadow-sm transition-colors text-sm text-gray-900" placeholder="Catatan internal TU...">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Disposisi ke Kepala Bagian -->
            <div class="border-b border-gray-100">
                <div class="p-6">
                    <h3 class="font-bold text-gray-900 mb-6">Disposisi ke Kepala Bagian</h3>
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label for="ke_user_id" class="block text-sm font-medium text-gray-700">Tujukan ke Kabag</label>
                            <select name="ke_user_id" id="ke_user_id" class="w-full rounded-lg border-gray-300 focus:border-[#0284c7] focus:ring-[#0284c7] shadow-sm transition-colors text-sm text-gray-900" required>
                                <option value="">Pilih Kabag...</option>
                                @foreach($penerimas as $penerima)
                                    <option value="{{ $penerima->id }}" {{ old('ke_user_id') == $penerima->id ? 'selected' : '' }}>
                                        {{ $penerima->name }} - {{ $penerima->jabatan ?? str_replace('_', ' ', $penerima->role) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ke_user_id') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="instruksi" class="block text-sm font-medium text-gray-700">Instruksi</label>
                            <textarea name="instruksi" id="instruksi" rows="4" class="w-full rounded-lg border-gray-300 focus:border-[#0284c7] focus:ring-[#0284c7] shadow-sm transition-colors text-sm text-gray-900" required placeholder="Tuliskan instruksi untuk Kabag...">{{ old('instruksi') }}</textarea>
                            @error('instruksi') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="tenggat_waktu" class="block text-sm font-medium text-gray-700">Batas Waktu</label>
                                <input type="date" name="tenggat_waktu" id="tenggat_waktu" value="{{ old('tenggat_waktu') }}" class="w-full rounded-lg border-gray-300 focus:border-[#0284c7] focus:ring-[#0284c7] shadow-sm transition-colors text-sm text-gray-900">
                                @error('tenggat_waktu') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-2">
                                <label for="prioritas" class="block text-sm font-medium text-gray-700">Prioritas</label>
                                <select name="prioritas" id="prioritas" class="w-full rounded-lg border-gray-300 focus:border-[#0284c7] focus:ring-[#0284c7] shadow-sm transition-colors text-sm text-gray-900">
                                    <option value="">Pilih prioritas...</option>
                                    <option value="biasa">Biasa</option>
                                    <option value="segera">Segera</option>
                                    <option value="sangat_segera">Sangat Segera</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="p-6 flex justify-end gap-3 bg-gray-50">
                <a href="{{ route('tu.disposisi.index') }}" class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 rounded-lg transition-colors border border-gray-300 shadow-sm">
                    Kembali
                </a>
                <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-[#0284c7] hover:bg-[#0369a1] rounded-lg transition-colors shadow-sm">
                    Simpan & Disposisi
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
