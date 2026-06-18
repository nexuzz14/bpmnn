<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Riwayat Disposisi</h2>
        <p class="text-gray-500 mt-1">Histori disposisi surat yang telah dikirim ke Kepala Bagian</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">Total Disposisi Bulan Ini</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total_bulan_ini'] }}</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">Sedang Diproses</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['sedang_diproses'] }}</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">Selesai</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['selesai'] }}</h3>
        </div>
    </div>

    <!-- Filters & Search -->
    <form method="GET" action="{{ route('tu.disposisi.riwayat') }}" class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor agenda, perihal..." class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#0284c7] focus:border-[#0284c7] shadow-sm text-sm">
        </div>
        <div class="flex gap-4">
            <select name="status" onchange="this.form.submit()" class="py-2 px-4 bg-white border border-gray-200 rounded-lg focus:ring-[#0284c7] focus:border-[#0284c7] shadow-sm text-sm text-gray-700 min-w-[150px]">
                <option value="">Semua Status</option>
                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
            <select name="waktu" onchange="this.form.submit()" class="py-2 px-4 bg-white border border-gray-200 rounded-lg focus:ring-[#0284c7] focus:border-[#0284c7] shadow-sm text-sm text-gray-700 min-w-[120px]">
                <option value="">Semua Waktu</option>
                <option value="Bulan Ini" {{ request('waktu') == 'Bulan Ini' ? 'selected' : '' }}>Bulan Ini</option>
                <option value="Bulan Lalu" {{ request('waktu') == 'Bulan Lalu' ? 'selected' : '' }}>Bulan Lalu</option>
                <option value="Tahun Ini" {{ request('waktu') == 'Tahun Ini' ? 'selected' : '' }}>Tahun Ini</option>
            </select>
        </div>
        <button type="submit" class="hidden">Cari</button>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-[11px] text-gray-500 bg-gray-50 border-b border-gray-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">NO. AGENDA</th>
                        <th class="px-6 py-4 font-medium">NOMOR SURAT</th>
                        <th class="px-6 py-4 font-medium">PERIHAL</th>
                        <th class="px-6 py-4 font-medium">TUJUAN</th>
                        <th class="px-6 py-4 font-medium">TANGGAL DISPOSISI</th>
                        <th class="px-6 py-4 font-medium">STATUS</th>
                        <th class="px-6 py-4 font-medium text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($disposisis as $disposisi)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-900 whitespace-nowrap">
                            {{ $disposisi->suratMasuk->nomor_agenda ?? date('Y', strtotime($disposisi->created_at)) . '/' . $disposisi->id }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            {{ $disposisi->suratMasuk->nomor_surat }}
                        </td>
                        <td class="px-6 py-4 text-gray-900 min-w-[200px]">{{ $disposisi->suratMasuk->perihal }}</td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ $disposisi->penerima->unitKerja->nama ?? str_replace('_', ' ', $disposisi->penerima->role) }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            {{ $disposisi->created_at->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($disposisi->status === 'selesai')
                                <span class="inline-flex px-3 py-1.5 rounded text-[11px] font-bold bg-[#dcfce7] text-[#166534] flex-col text-center">
                                    Selesai
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1.5 rounded text-[11px] font-bold bg-[#dbeafe] text-[#1e40af] flex-col text-center">
                                    Diproses<br>Kabag
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('tu.disposisi.pdf', $disposisi->id) }}" target="_blank" class="inline-flex px-4 py-2 bg-white text-gray-600 font-medium rounded text-xs border border-gray-300 hover:bg-gray-50 transition-colors flex-col text-center shadow-sm">
                                Lihat<br>Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">Belum ada riwayat disposisi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-white flex flex-col md:flex-row items-center justify-between gap-4">
            <span class="text-sm text-gray-500">Menampilkan {{ $disposisis->firstItem() ?? 0 }}-{{ $disposisis->lastItem() ?? 0 }} dari {{ $disposisis->total() }} disposisi</span>
            <div class="w-full md:w-auto overflow-x-auto">
                {{ $disposisis->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
