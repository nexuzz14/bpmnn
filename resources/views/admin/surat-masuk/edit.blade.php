<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.surat-masuk.index') }}" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                <i class="ph ph-arrow-left text-xl"></i>
            </a>
            <h2 class="font-display font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Edit Surat Masuk') }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden max-w-4xl">
        <div class="p-6 sm:px-8 sm:py-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900">Edit Data Surat</h3>
        </div>
        
        @if ($errors->any())
            <div class="bg-red-50 text-red-800 p-4 border-b border-red-100 flex items-start gap-3">
                <i class="ph ph-warning-circle text-xl mt-0.5 text-red-600"></i>
                <div>
                    <h4 class="text-sm font-bold mb-1">Gagal memperbarui surat masuk</h4>
                    <ul class="text-xs list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.surat-masuk.update', $suratMasuk) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:px-8 sm:py-6 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nomor Surat -->
                <div class="space-y-2">
                    <label for="nomor_surat" class="block text-sm font-medium text-gray-700">Nomor Surat</label>
                    <input type="text" name="nomor_surat" id="nomor_surat" value="{{ old('nomor_surat', $suratMasuk->nomor_surat) }}" class="w-full rounded-lg border-gray-300 focus:border-role-admin focus:ring-role-admin shadow-sm transition-colors" required>
                    @error('nomor_surat') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <!-- Asal Surat -->
                <div class="space-y-2">
                    <label for="asal_surat" class="block text-sm font-medium text-gray-700">Asal Surat</label>
                    <input type="text" name="asal_surat" id="asal_surat" value="{{ old('asal_surat', $suratMasuk->asal_surat) }}" class="w-full rounded-lg border-gray-300 focus:border-role-admin focus:ring-role-admin shadow-sm transition-colors" required>
                    @error('asal_surat') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <!-- Tanggal Surat -->
                <div class="space-y-2">
                    <label for="tanggal_surat" class="block text-sm font-medium text-gray-700">Tanggal Surat</label>
                    <input type="date" name="tanggal_surat" id="tanggal_surat" value="{{ old('tanggal_surat', $suratMasuk->tanggal_surat) }}" class="w-full rounded-lg border-gray-300 focus:border-role-admin focus:ring-role-admin shadow-sm transition-colors" required>
                    @error('tanggal_surat') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <!-- Tanggal Terima -->
                <div class="space-y-2">
                    <label for="tanggal_terima" class="block text-sm font-medium text-gray-700">Tanggal Diterima</label>
                    <input type="date" name="tanggal_terima" id="tanggal_terima" value="{{ old('tanggal_terima', $suratMasuk->tanggal_terima) }}" class="w-full rounded-lg border-gray-300 focus:border-role-admin focus:ring-role-admin shadow-sm transition-colors" required>
                    @error('tanggal_terima') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <!-- Perihal -->
                <div class="space-y-2 md:col-span-2">
                    <label for="perihal" class="block text-sm font-medium text-gray-700">Perihal</label>
                    <input type="text" name="perihal" id="perihal" value="{{ old('perihal', $suratMasuk->perihal) }}" class="w-full rounded-lg border-gray-300 focus:border-role-admin focus:ring-role-admin shadow-sm transition-colors" required>
                    @error('perihal') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <!-- Sifat Surat -->
                <div class="space-y-2">
                    <label for="sifat" class="block text-sm font-medium text-gray-700">Prioritas / Sifat</label>
                    <select name="sifat" id="sifat" class="w-full rounded-lg border-gray-300 focus:border-role-admin focus:ring-role-admin shadow-sm transition-colors" required>
                        <option value="biasa" {{ old('sifat', $suratMasuk->sifat) == 'biasa' ? 'selected' : '' }}>Biasa</option>
                        <option value="segera" {{ old('sifat', $suratMasuk->sifat) == 'segera' ? 'selected' : '' }}>Segera</option>
                    </select>
                    @error('sifat') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <!-- Jenis Surat -->
                <div class="space-y-2">
                    <label for="jenis_surat" class="block text-sm font-medium text-gray-700">Klasifikasi Surat</label>
                    <select name="jenis_surat" id="jenis_surat" class="w-full rounded-lg border-gray-300 focus:border-role-admin focus:ring-role-admin shadow-sm transition-colors" required>
                        <option value="fisik" {{ old('jenis_surat', $suratMasuk->jenis_surat) == 'fisik' ? 'selected' : '' }}>Fisik</option>
                        <option value="digital" {{ old('jenis_surat', $suratMasuk->jenis_surat) == 'digital' ? 'selected' : '' }}>Digital</option>
                    </select>
                    @error('jenis_surat') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <!-- Keterangan -->
                <div class="space-y-2 md:col-span-2">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan Tambahan</label>
                    <textarea name="keterangan" id="keterangan" rows="3" class="w-full rounded-lg border-gray-300 focus:border-role-admin focus:ring-role-admin shadow-sm transition-colors">{{ old('keterangan', $suratMasuk->keterangan) }}</textarea>
                    @error('keterangan') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <!-- File Surat -->
                <div class="space-y-2 md:col-span-2 mt-4">
                    <label class="block text-sm font-medium text-gray-700">File Surat Saat Ini</label>
                    
                    @if($suratMasuk->file_surat)
                        <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-lg mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
                                    <i class="ph ph-file-text text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">File Tersimpan</p>
                                    <a href="{{ Storage::url($suratMasuk->file_surat) }}" target="_blank" class="text-xs text-role-admin hover:underline">Lihat File</a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-10 flex flex-col items-center justify-center text-center hover:bg-gray-50 transition-colors relative cursor-pointer" onclick="document.getElementById('file_surat').click()">
                        <p id="file-name-display" class="text-gray-500 text-sm mb-3">Seret & lepas file PDF atau Word baru di sini untuk mengganti</p>
                        <button type="button" id="file-btn" class="px-4 py-2 bg-white border border-[#055a40] text-[#055a40] font-medium text-sm rounded-lg shadow-sm hover:bg-green-50 pointer-events-none">
                            Pilih File Baru
                        </button>
                        <p class="text-xs text-gray-400 mt-3">PDF / Word maks. 10 MB</p>
                        <input type="file" name="file_surat" id="file_surat" accept=".pdf,.doc,.docx" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="if(this.files.length) { document.getElementById('file-name-display').innerHTML = '<span class=\'font-bold text-[#055a40] text-base\'>📄 File Terpilih:</span><br><span class=\'text-gray-800 mt-1 block\'>' + this.files[0].name + '</span>'; document.getElementById('file-btn').innerText = 'Ganti File'; document.getElementById('file-btn').className = 'px-4 py-2 bg-[#055a40] text-white font-medium text-sm rounded-lg shadow-sm pointer-events-none mt-2'; }">
                    </div>
                    @error('file_surat') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('admin.surat-masuk.index') }}" class="px-5 py-2 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors border border-gray-200">Batal</a>
                <button type="submit" id="submit-btn" class="px-5 py-2 text-sm font-medium text-white bg-role-admin hover:bg-role-admin/90 rounded-lg transition-colors shadow-sm flex items-center" onclick="if(this.closest('form').checkValidity()) { this.innerHTML = '<i class=\'ph ph-spinner animate-spin mr-2\'></i> Menyimpan...'; this.classList.add('opacity-75', 'cursor-wait'); }">
                    Update Surat Masuk
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
