<x-app-layout>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Pengguna</h2>
            <p class="text-gray-500 mt-1">Ubah data pengguna sistem SiPersurat</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 flex items-center gap-2">
            <i class="ph ph-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden max-w-4xl">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Data Pribadi Section -->
            <div class="border-b border-gray-100">
                <div class="p-6 bg-white">
                    <h3 class="text-md font-bold text-gray-900 mb-6">Data Pribadi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Lengkap -->
                        <div class="space-y-2 md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full rounded-lg border-gray-300 focus:border-[#055a40] focus:ring-[#055a40] shadow-sm transition-colors text-sm" required>
                            @error('name') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full rounded-lg border-gray-300 focus:border-[#055a40] focus:ring-[#055a40] shadow-sm transition-colors text-sm" required>
                            @error('email') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>

                        <!-- NIP -->
                        <div class="space-y-2">
                            <label for="nip" class="block text-sm font-medium text-gray-700">NIP <span class="text-red-500">*</span></label>
                            <input type="text" name="nip" id="nip" value="{{ old('nip', $user->nip) }}" class="w-full rounded-lg border-gray-300 focus:border-[#055a40] focus:ring-[#055a40] shadow-sm transition-colors text-sm" required>
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
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin Persuratan</option>
                                <option value="tata_usaha" {{ old('role', $user->role) == 'tata_usaha' ? 'selected' : '' }}>Tata Usaha</option>
                                <option value="kepala_bagian" {{ old('role', $user->role) == 'kepala_bagian' ? 'selected' : '' }}>Kepala Bagian</option>
                                <option value="kepala_sub_tim" {{ old('role', $user->role) == 'kepala_sub_tim' ? 'selected' : '' }}>Kepala Sub Tim</option>
                                <option value="staf" {{ old('role', $user->role) == 'staf' ? 'selected' : '' }}>Staf</option>
                                <option value="kepala_biro" {{ old('role', $user->role) == 'kepala_biro' ? 'selected' : '' }}>Kepala Biro</option>
                            </select>
                            @error('role') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>

                        <!-- Unit Kerja -->
                        <div class="space-y-2">
                            <label for="unit_kerja_id" class="block text-sm font-medium text-gray-700">Unit Kerja <span class="text-red-500">*</span></label>
                            <select name="unit_kerja_id" id="unit_kerja_id" class="w-full rounded-lg border-gray-300 focus:border-[#055a40] focus:ring-[#055a40] shadow-sm transition-colors text-sm" required>
                                <option value="">Pilih unit kerja...</option>
                                @foreach(\App\Models\UnitKerja::all() as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_kerja_id', $user->unit_kerja_id) == $unit->id ? 'selected' : '' }}>{{ $unit->nama }}</option>
                                @endforeach
                            </select>
                            @error('unit_kerja_id') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2 pt-2">
                            <p class="text-xs text-gray-500 italic">Kosongkan kolom password di bawah ini jika tidak ingin mengubah password pengguna.</p>
                        </div>

                        <!-- Password -->
                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                            <input type="password" name="password" id="password" placeholder="Minimal 8 karakter" class="w-full rounded-lg border-gray-300 focus:border-[#055a40] focus:ring-[#055a40] shadow-sm transition-colors text-sm">
                            @error('password') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-2">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi password" class="w-full rounded-lg border-gray-300 focus:border-[#055a40] focus:ring-[#055a40] shadow-sm transition-colors text-sm">
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
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
