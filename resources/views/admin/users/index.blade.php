<x-app-layout>
    <div class="flex justify-between items-end mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Kelola Pengguna</h2>
            <p class="text-gray-500 mt-1">Manajemen data pengguna dan hak akses sistem</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#055a40] text-white rounded-lg hover:bg-[#044733] transition-colors font-medium text-sm">
            <i class="ph ph-plus font-bold"></i>
            Tambah Pengguna
        </a>
    </div>

    <!-- Filters & Search -->
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="flex-1">
            <div class="relative">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau NIP..." class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#055a40] focus:border-[#055a40] shadow-sm">
            </div>
        </div>
        <div class="flex gap-4">
            <select name="role" onchange="this.form.submit()" class="py-2 pl-4 pr-10 bg-white border border-gray-200 rounded-lg focus:ring-[#055a40] focus:border-[#055a40] shadow-sm text-gray-700">
                <option value="">Semua Role</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin Persuratan</option>
                <option value="tata_usaha" {{ request('role') == 'tata_usaha' ? 'selected' : '' }}>TU Biro</option>
                <option value="kepala_bagian" {{ request('role') == 'kepala_bagian' ? 'selected' : '' }}>Kepala Bagian</option>
                <option value="kepala_sub_tim" {{ request('role') == 'kepala_sub_tim' ? 'selected' : '' }}>Kepala Sub Tim</option>
                <option value="staf" {{ request('role') == 'staf' ? 'selected' : '' }}>Staf</option>
                <option value="kepala_biro" {{ request('role') == 'kepala_biro' ? 'selected' : '' }}>Kepala Biro</option>
            </select>
            <button type="submit" class="hidden">Cari</button>
        </div>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 bg-gray-50 border-b border-gray-100 uppercase">
                    <tr>
                        <th class="px-6 py-4 font-medium">Nama</th>
                        <th class="px-6 py-4 font-medium">Email</th>
                        <th class="px-6 py-4 font-medium">NIP</th>
                        <th class="px-6 py-4 font-medium">Role</th>
                        <th class="px-6 py-4 font-medium">Unit Kerja</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $user->nip ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @php
                                $roleNames = [
                                    'admin' => 'Admin Persuratan',
                                    'tata_usaha' => 'TU Biro',
                                    'kepala_bagian' => 'Kepala Bagian',
                                    'kepala_sub_tim' => 'Kepala Sub Tim',
                                    'staf' => 'Staf',
                                    'kepala_biro' => 'Kepala Biro'
                                ];
                            @endphp
                            <span class="text-gray-900">{{ $roleNames[$user->role] ?? ucfirst($user->role) }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $user->unitKerja->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Aktif
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                Edit
                            </a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')" class="inline-flex items-center px-3 py-1.5 border border-red-300 rounded-md text-xs font-medium text-red-700 bg-white hover:bg-red-50 transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">Belum ada data pengguna.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
