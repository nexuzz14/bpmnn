<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Profil Saya</h2>
        <p class="text-gray-500 mt-1">Perbarui informasi profil dan password akun Anda</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden max-w-4xl">
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('patch')
            
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
                            <label for="nip" class="block text-sm font-medium text-gray-700">NIP</label>
                            <input type="text" name="nip" id="nip" value="{{ old('nip', $user->nip) }}" class="w-full rounded-lg border-gray-300 focus:border-[#055a40] focus:ring-[#055a40] shadow-sm transition-colors text-sm">
                            @error('nip') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- Jabatan (Read Only) -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                            <input type="text" value="{{ $user->jabatan }}" class="w-full rounded-lg border-gray-300 bg-gray-50 text-gray-500 shadow-sm text-sm" disabled>
                            <p class="text-xs text-gray-500 mt-1">Hubungi admin untuk mengubah jabatan</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-5 py-2 text-sm font-medium text-white bg-[#055a40] hover:bg-[#044733] rounded-lg transition-colors shadow-sm">
                            Simpan Profil
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            @method('put')
            
            <!-- Ubah Password Section -->
            <div class="border-b border-gray-100">
                <div class="p-6 bg-white">
                    <h3 class="text-md font-bold text-gray-900 mb-6">Ubah Password</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="space-y-2 md:col-span-2">
                            <label for="current_password" class="block text-sm font-medium text-gray-700">Password Saat Ini</label>
                            <input type="password" name="current_password" id="current_password" class="w-full rounded-lg border-gray-300 focus:border-[#055a40] focus:ring-[#055a40] shadow-sm transition-colors text-sm">
                            @error('current_password', 'updatePassword') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>

                        <!-- Password -->
                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                            <input type="password" name="password" id="password" class="w-full rounded-lg border-gray-300 focus:border-[#055a40] focus:ring-[#055a40] shadow-sm transition-colors text-sm">
                            @error('password', 'updatePassword') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-2">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full rounded-lg border-gray-300 focus:border-[#055a40] focus:ring-[#055a40] shadow-sm transition-colors text-sm">
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-5 py-2 text-sm font-medium text-white bg-gray-800 hover:bg-gray-700 rounded-lg transition-colors shadow-sm">
                            Ubah Password
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
