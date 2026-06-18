<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Tambah Pengguna Baru</h2>
        <p class="text-gray-500 mt-1">Isi data pengguna baru untuk sistem SiPersurat</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden max-w-4xl">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            <!-- Data Pribadi Section -->
            <div class="border-b border-gray-100">
                <div class="p-6 bg-white">
                    <h3 class="text-md font-bold text-gray-900 mb-6">Data Pribadi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Lengkap -->
                        <div class="space-y-2 md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" class="w-full rounded-lg border-gray-300 focus:border-[#055a40] focus:ring-[#055a40] shadow-sm transition-colors text-sm" required>
                            @error('name') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="nama@kemenag.go.id" class="w-full rounded-lg border-gray-300 focus:border-[#055a40] focus:ring-[#055a40] shadow-sm transition-colors text-sm" required>
                            @error('email') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>

                        <!-- NIP -->
                        <div class="space-y-2">
                            <label for="nip" class="block text-sm font-medium text-gray-700">NIP <span class="text-red-500">*</span></label>
                            <input type="text" name="nip" id="nip" value="{{ old('nip') }}" placeholder="198501012010011001" class="w-full rounded-lg border-gray-300 focus:border-[#055a40] focus:ring-[#055a40] shadow-sm transition-colors text-sm" required>
                            @error('nip') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Akun Section -->
            <div class="border-b border-gray-100">
                <div class="p-6 bg-white">
                    <h3 class="text-md font-bold text-gray-900 mb-6">Informasi Akun</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Role -->
                        <div class="space-y-2">
                            <label for="role" class="block text-sm font-medium text-gray-700">Role <span class="text-red-500">*</span></label>
                            <select name="role" id="role" class="w-full rounded-lg border-gray-300 focus:border-[#055a40] focus:ring-[#055a40] shadow-sm transition-colors text-sm" required>
                                <option value="">Pilih role...</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin Persuratan</option>
                                <option value="tata_usaha" {{ old('role') == 'tata_usaha' ? 'selected' : '' }}>Tata Usaha</option>
                                <option value="kepala_bagian" {{ old('role') == 'kepala_bagian' ? 'selected' : '' }}>Kepala Bagian</option>
                                <option value="kepala_sub_tim" {{ old('role') == 'kepala_sub_tim' ? 'selected' : '' }}>Kepala Sub Tim</option>
                                <option value="staf" {{ old('role') == 'staf' ? 'selected' : '' }}>Staf</option>
                                <option value="kepala_biro" {{ old('role') == 'kepala_biro' ? 'selected' : '' }}>Kepala Biro</option>
                            </select>
                            @error('role') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>

                        <!-- Unit Kerja -->
                        <div class="space-y-2">
                            <label for="unit_kerja_id" class="block text-sm font-medium text-gray-700">Unit Kerja <span class="text-red-500">*</span></label>
                            <select name="unit_kerja_id" id="unit_kerja_id" class="w-full rounded-lg border-gray-300 focus:border-[#055a40] focus:ring-[#055a40] shadow-sm transition-colors text-sm" required>
                                <option value="">Pilih unit kerja...</option>
                                @foreach(\App\Models\UnitKerja::all() as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_kerja_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama }}</option>
                                @endforeach
                            </select>
                            @error('unit_kerja_id') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>

                        <!-- Password -->
                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password" id="password" placeholder="Minimal 8 karakter" class="w-full rounded-lg border-gray-300 focus:border-[#055a40] focus:ring-[#055a40] shadow-sm transition-colors text-sm" required>
                            @error('password') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-2">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi password" class="w-full rounded-lg border-gray-300 focus:border-[#055a40] focus:ring-[#055a40] shadow-sm transition-colors text-sm" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="p-6 flex items-center justify-between bg-gray-50/50 rounded-b-xl">
                <div class="text-sm text-gray-500">
                    <span class="text-red-500">*</span> Wajib diisi
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.users.index') }}" class="px-6 py-2 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 rounded-lg transition-colors border border-gray-300 shadow-sm">Batal</a>
                    <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-[#055a40] hover:bg-[#044733] rounded-lg transition-colors shadow-sm">
                        Simpan Pengguna
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
