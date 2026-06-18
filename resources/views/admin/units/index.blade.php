<x-app-layout>
    <div class="flex justify-between items-end mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Kelola Unit Kerja</h2>
            <p class="text-gray-500 mt-1">Manajemen struktur organisasi dan unit kerja</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('admin.units.create') }}" class="px-4 py-2 bg-[#055a40] text-white font-bold rounded-lg hover:bg-[#044733] transition-colors shadow-sm flex items-center justify-center text-sm">
                + Tambah Unit Kerja
            </a>
        </div>
    </div>

    <!-- Filters & Search -->
    <form method="GET" action="{{ route('admin.units.index') }}" class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="flex-1">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama unit kerja..." class="w-full pl-4 pr-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#055a40] focus:border-[#055a40] shadow-sm">
            </div>
        </div>
        <div class="flex gap-4">
            <select name="level" onchange="this.form.submit()" class="py-2 pl-4 pr-10 bg-white border border-gray-200 rounded-lg focus:ring-[#055a40] focus:border-[#055a40] shadow-sm text-gray-700">
                <option value="">Semua Level</option>
                <option value="Biro" {{ request('level') == 'Biro' ? 'selected' : '' }}>Biro</option>
                <option value="Bagian" {{ request('level') == 'Bagian' ? 'selected' : '' }}>Bagian</option>
                <option value="Sub Bagian" {{ request('level') == 'Sub Bagian' ? 'selected' : '' }}>Sub Bagian</option>
            </select>
            <button type="submit" class="hidden">Cari</button>
        </div>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-[11px] text-gray-500 bg-gray-50 border-b border-gray-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">KODE</th>
                        <th class="px-6 py-4 font-medium">NAMA UNIT KERJA</th>
                        <th class="px-6 py-4 font-medium">PARENT UNIT</th>
                        <th class="px-6 py-4 font-medium">KEPALA UNIT</th>
                        <th class="px-6 py-4 font-medium">JUMLAH PEGAWAI</th>
                        <th class="px-6 py-4 font-medium">STATUS</th>
                        <th class="px-6 py-4 font-medium text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($units as $unit)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-700">{{ $unit->kode ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $unit->nama }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $unit->parent ? $unit->parent->nama : '-' }}</td>
                        <td class="px-6 py-4 text-gray-500">
                            @php
                                $kepala = \App\Models\User::where('unit_kerja_id', $unit->id)
                                    ->whereIn('role', ['kepala_bagian', 'kepala_sub_tim', 'kepala_biro'])
                                    ->first();
                            @endphp
                            {{ $kepala ? $kepala->name : '-' }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ $unit->users_count }} orang
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-bold bg-[#dcfce7] text-[#166534]">
                                Aktif
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap space-x-2">
                            <a href="{{ route('admin.units.edit', $unit->id) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                Edit
                            </a>
                            <form action="{{ route('admin.units.destroy', $unit->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus unit kerja ini?')" class="inline-flex items-center px-3 py-1.5 border border-red-300 rounded-md text-xs font-medium text-red-700 bg-white hover:bg-red-50 transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-6 text-center text-gray-500">Belum ada data unit kerja.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($units->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $units->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
