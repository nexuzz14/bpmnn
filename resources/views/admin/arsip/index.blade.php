<x-app-layout>
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Arsip Surat</h2>
            <p class="text-gray-500 mt-1">Pengelolaan arsip dan dokumen surat</p>
        </div>
        <a href="{{ route('admin.arsip.pdf') }}" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 flex items-center gap-2 shadow-sm transition-colors">
            <i class="ph ph-file-pdf text-lg"></i>
            Unduh PDF
        </a>
    </div>

    <!-- Filters & Search -->
    <form method="GET" action="{{ route('admin.arsip.index') }}" class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="flex-1">
            <div class="relative">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor agenda, nomor surat, atau perihal..." class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#055a40] focus:border-[#055a40] shadow-sm">
            </div>
        </div>
        <div class="flex gap-4">
            <select name="jenis" onchange="this.form.submit()" class="py-2 pl-4 pr-10 bg-white border border-gray-200 rounded-lg focus:ring-[#055a40] focus:border-[#055a40] shadow-sm text-gray-700">
                <option value="">Semua Jenis</option>
                <option value="Surat Masuk" {{ request('jenis') == 'Surat Masuk' ? 'selected' : '' }}>Surat Masuk</option>
                <option value="Surat Keluar" {{ request('jenis') == 'Surat Keluar' ? 'selected' : '' }}>Surat Keluar</option>
            </select>
            <select name="kategori" onchange="this.form.submit()" class="py-2 pl-4 pr-10 bg-white border border-gray-200 rounded-lg focus:ring-[#055a40] focus:border-[#055a40] shadow-sm text-gray-700">
                <option value="">Semua Kategori</option>
                <option value="Surat Dinas" {{ request('kategori') == 'Surat Dinas' ? 'selected' : '' }}>Surat Dinas</option>
                <option value="Surat Undangan" {{ request('kategori') == 'Surat Undangan' ? 'selected' : '' }}>Surat Undangan</option>
                <option value="Laporan" {{ request('kategori') == 'Laporan' ? 'selected' : '' }}>Laporan</option>
            </select>
            <select name="tahun" onchange="this.form.submit()" class="py-2 pl-4 pr-10 bg-white border border-gray-200 rounded-lg focus:ring-[#055a40] focus:border-[#055a40] shadow-sm text-gray-700">
                <option value="">Semua Tahun</option>
                <option value="2026" {{ request('tahun') == '2026' ? 'selected' : '' }}>Tahun 2026</option>
                <option value="2025" {{ request('tahun') == '2025' ? 'selected' : '' }}>Tahun 2025</option>
            </select>
            <button type="submit" class="hidden">Cari</button>
        </div>
    </form>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">Total Arsip</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $arsips->count() }}</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">Surat Masuk</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $arsips->where('jenis', 'Surat Masuk')->count() }}</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">Surat Keluar</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $arsips->where('jenis', 'Surat Keluar')->count() }}</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">Lokasi Penyimpanan</p>
            <h3 class="text-3xl font-bold text-gray-900">8 <span class="text-xl font-normal">Lemari</span></h3>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-[11px] text-gray-500 bg-gray-50 border-b border-gray-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">No. Agenda</th>
                        <th class="px-6 py-4 font-medium">Nomor Surat</th>
                        <th class="px-6 py-4 font-medium">Perihal</th>
                        <th class="px-6 py-4 font-medium">Jenis</th>
                        <th class="px-6 py-4 font-medium">Kategori</th>
                        <th class="px-6 py-4 font-medium">Tanggal</th>
                        <th class="px-6 py-4 font-medium">Lokasi Fisik</th>
                        <th class="px-6 py-4 font-medium"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($arsips as $arsip)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $arsip->nomor_agenda ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-900 whitespace-nowrap">{{ $arsip->nomor_surat ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-700 min-w-[200px]">{{ $arsip->perihal }}</td>
                        <td class="px-6 py-4">
                            @if($arsip->jenis == 'Surat Masuk')
                                <span class="inline-flex px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded font-medium">Surat Masuk</span>
                            @else
                                <span class="inline-flex px-2 py-1 bg-green-100 text-green-700 text-xs rounded font-medium">Surat Keluar</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">{{ $arsip->kategori }}</td>
                        <td class="px-6 py-4 text-gray-600 whitespace-nowrap">{{ $arsip->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span>{{ explode(' - ', $arsip->lokasi_fisik)[0] ?? '' }}</span>
                                <span>{{ explode(' - ', $arsip->lokasi_fisik)[1] ?? '' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.arsip.unduh', ['id' => $arsip->id, 'jenis' => $arsip->jenis == 'Surat Masuk' ? 'masuk' : 'keluar']) }}" class="px-3 py-1.5 border border-red-200 text-red-600 rounded-md hover:bg-red-50 text-xs font-medium transition-colors flex items-center gap-1">
                                    <i class="ph ph-file-pdf"></i> Unduh PDF
                                </a>
                                @if($arsip->jenis == 'Surat Masuk')
                                    <a href="{{ route('admin.surat-masuk.show', $arsip->id) }}" class="px-3 py-1.5 border border-gray-200 text-gray-700 rounded-md hover:bg-gray-50 text-xs font-medium transition-colors">
                                        Lihat
                                    </a>
                                @else
                                    <button class="px-3 py-1.5 border border-gray-200 text-gray-700 rounded-md hover:bg-gray-50 text-xs font-medium transition-colors">
                                        Lihat
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-10 text-center text-gray-500">Belum ada arsip surat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
