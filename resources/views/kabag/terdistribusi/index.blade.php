<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Surat Terdistribusi</h2>
        <p class="text-gray-500 mt-1">Surat yang telah selesai diproses dan didistribusikan oleh TU Biro</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Total Terdistribusi Bulan Ini</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Via Email</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['via_email'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Via Fisik</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['via_fisik'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Email & Fisik</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['keduanya'] }}</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <form method="GET" action="{{ route('kabag.terdistribusi.index') }}" class="flex flex-col md:flex-row gap-4 mb-6">
        <div class="flex-1">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor surat atau perihal..." class="w-full pl-4 pr-10 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#0284c7] focus:border-[#0284c7] shadow-sm text-sm">
            </div>
        </div>
        <div class="flex gap-4">
            <select name="via" onchange="this.form.submit()" class="bg-white border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-[#0284c7] focus:border-[#0284c7] px-4 py-2 shadow-sm min-w-[150px]">
                <option value="">Semua Via</option>
                <option value="email" {{ request('via') == 'email' ? 'selected' : '' }}>Email</option>
                <option value="fisik" {{ request('via') == 'fisik' ? 'selected' : '' }}>Fisik</option>
                <option value="keduanya" {{ request('via') == 'keduanya' ? 'selected' : '' }}>Keduanya</option>
            </select>
            <select name="periode" onchange="this.form.submit()" class="bg-white border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-[#0284c7] focus:border-[#0284c7] px-4 py-2 shadow-sm min-w-[120px]">
                <option value="">Semua Periode</option>
                <option value="bulan_ini" {{ request('periode') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                <option value="bulan_lalu" {{ request('periode') == 'bulan_lalu' ? 'selected' : '' }}>Bulan Lalu</option>
                <option value="tahun_ini" {{ request('periode') == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
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
                        <th class="px-6 py-4 font-bold">NOMOR SURAT</th>
                        <th class="px-6 py-4 font-bold">PERIHAL</th>
                        <th class="px-6 py-4 font-bold">TUJUAN</th>
                        <th class="px-6 py-4 font-bold">TANGGAL TTD</th>
                        <th class="px-6 py-4 font-bold">TANGGAL DISTRIBUSI</th>
                        <th class="px-6 py-4 font-bold">VIA</th>
                        <th class="px-6 py-4 font-bold">STATUS</th>
                        <th class="px-6 py-4 font-bold text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($suratFinals as $index => $surat)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $surat->nomor_surat_final ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-800 min-w-[200px]">{{ $surat->drafSurat->suratMasuk->perihal }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $surat->drafSurat->suratMasuk->asal_surat }}</td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            {{ $surat->tanggal_ttd ? \Carbon\Carbon::parse($surat->tanggal_ttd)->translatedFormat('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($surat->updated_at)->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                            @if($surat->via == 'email')
                                Email
                            @elseif($surat->via == 'fisik')
                                Fisik
                            @else
                                Email & Fisik
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-3 py-1 rounded text-[11px] font-bold bg-green-100 text-green-700">
                                Terkirim
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap flex items-center gap-2 justify-center">
                            <a href="{{ route('kabag.terdistribusi.show', $surat->id) }}" class="inline-flex px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded text-xs hover:bg-gray-50 transition-colors shadow-sm bg-white">
                                Lihat
                            </a>
                            <a href="{{ asset('storage/' . ($surat->file_distribusi ?? $surat->file_ttd ?? $surat->file_surat)) }}" target="_blank" class="inline-flex px-4 py-2 border border-[#4f46e5] text-[#4f46e5] font-medium rounded text-xs hover:bg-indigo-50 transition-colors shadow-sm bg-white">
                                Unduh PDF
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-10 text-center text-gray-500">
                            Belum ada surat yang terdistribusi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($suratFinals->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <span class="text-sm text-gray-500">
                Menampilkan {{ $suratFinals->firstItem() }}-{{ $suratFinals->lastItem() }} dari {{ $suratFinals->total() }} surat
            </span>
            <div class="flex items-center gap-2">
                <a href="{{ $suratFinals->previousPageUrl() }}" class="px-4 py-2 border border-gray-300 text-gray-600 rounded text-sm hover:bg-gray-50 {{ $suratFinals->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">Sebelumnya</a>
                <a href="{{ $suratFinals->nextPageUrl() }}" class="px-4 py-2 border border-gray-300 text-gray-900 rounded text-sm hover:bg-gray-50 font-medium {{ !$suratFinals->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">Berikutnya</a>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
