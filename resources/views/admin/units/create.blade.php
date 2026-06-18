<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Tambah Unit Kerja Baru</h2>
        <p class="text-gray-500 mt-1">Tambahkan unit kerja baru ke dalam struktur organisasi</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 max-w-4xl">
        <div class="p-6 sm:px-8 sm:py-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900">Informasi Unit Kerja</h3>
        </div>
        <form action="{{ route('admin.units.store') }}" method="POST" class="p-6 sm:px-8 sm:py-6 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kode Unit -->
                <div class="space-y-2">
                    <label for="kode" class="block text-sm font-medium text-gray-700">Kode Unit <span class="text-red-500">*</span></label>
                    <input type="text" name="kode" id="kode" value="{{ old('kode') }}" class="w-full rounded-lg border-gray-300 focus:border-[#055a40] focus:ring-[#055a40] shadow-sm transition-colors text-gray-500" required placeholder="Contoh: BK-03">
                    <p class="text-xs text-gray-400">Format: [Kode Parent]-[Nomor Urut]</p>
                    @error('kode') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <!-- Parent Unit -->
                <div class="space-y-2">
                    <label for="parent_id" class="block text-sm font-medium text-gray-700">Parent Unit</label>
                    <select name="parent_id" id="parent_id" class="w-full rounded-lg border-gray-300 focus:border-[#055a40] focus:ring-[#055a40] shadow-sm transition-colors text-gray-900">
                        <option value="">- (Unit Level Tertinggi)</option>
                        @foreach(\App\Models\UnitKerja::whereNull('parent_id')->orWhere('level', 'biro')->get() as $p)
                            <option value="{{ $p->id }}" {{ old('parent_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400">Kosongkan jika unit level Biro</p>
                    @error('parent_id') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <!-- Nama Unit Kerja -->
                <div class="space-y-2 md:col-span-2">
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Unit Kerja <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" class="w-full rounded-lg border-gray-300 focus:border-[#055a40] focus:ring-[#055a40] shadow-sm transition-colors text-gray-500" required placeholder="Contoh: Bagian Perencanaan Anggaran">
                    @error('nama') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <!-- Kepala Unit -->
                <div class="space-y-2 md:col-span-2">
                    <label for="kepala_unit_id" class="block text-sm font-medium text-gray-700">Kepala Unit</label>
                    <select name="kepala_unit_id" id="kepala_unit_id" class="w-full rounded-lg border-gray-300 focus:border-[#055a40] focus:ring-[#055a40] shadow-sm transition-colors text-gray-500">
                        <option value="">Pilih kepala unit...</option>
                        @foreach(\App\Models\User::whereIn('role', ['kepala_bagian', 'kepala_sub_tim'])->get() as $user)
                            <option value="{{ $user->id }}" {{ old('kepala_unit_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} - {{ $user->role }}</option>
                        @endforeach
                    </select>
                    @error('kepala_unit_id') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <!-- Deskripsi / Tugas Pokok -->
                <div class="space-y-2 md:col-span-2">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi / Tugas Pokok</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" class="w-full rounded-lg border-gray-300 focus:border-[#055a40] focus:ring-[#055a40] shadow-sm transition-colors text-gray-500" placeholder="Jelaskan tugas pokok dan fungsi unit kerja ini...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-6 mt-6 flex items-center justify-between border-t border-gray-100">
                <p class="text-xs text-red-500">* Wajib diisi</p>
                <div class="flex gap-3">
                    <a href="{{ route('admin.units.index') }}" class="px-5 py-2 text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 rounded-lg transition-colors border border-gray-200">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2 text-sm font-medium text-white bg-[#055a40] hover:bg-[#044733] rounded-lg transition-colors shadow-sm">
                        Simpan Unit Kerja
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
