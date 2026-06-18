<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Surat Terdistribusi</h2>
        <p class="text-gray-500 mt-1">Surat yang telah Anda setujui dan didistribusikan oleh TU Biro</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <p class="text-sm font-medium text-gray-500 mb-2">Total Terdistribusi Bulan Ini</p>
            <h3 class="text-3xl font-bold text-gray-900">12</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <p class="text-sm font-medium text-gray-500 mb-2">Via Email</p>
            <h3 class="text-3xl font-bold text-gray-900">7</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <p class="text-sm font-medium text-gray-500 mb-2">Via Fisik</p>
            <h3 class="text-3xl font-bold text-gray-900">2</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <p class="text-sm font-medium text-gray-500 mb-2">Email &amp; Fisik</p>
            <h3 class="text-3xl font-bold text-gray-900">3</h3>
        </div>
    </div>

    <!-- Filter & Search -->
    <form method="GET" action="{{ route('kabiro.terdistribusi.index') }}" class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor surat atau perihal..." class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg focus:ring-[#3b2c85] focus:border-[#3b2c85] shadow-sm text-sm">
        </div>
        <div class="flex gap-4">
            <select name="via" onchange="this.form.submit()" class="py-2.5 pl-4 pr-10 bg-white border border-gray-200 rounded-lg focus:ring-[#3b2c85] focus:border-[#3b2c85] shadow-sm text-sm text-gray-700 min-w-[150px]">
                <option value="">Semua Via</option>
                <option value="email" {{ request('via') == 'email' ? 'selected' : '' }}>Email</option>
                <option value="fisik" {{ request('via') == 'fisik' ? 'selected' : '' }}>Fisik</option>
                <option value="email_fisik" {{ request('via') == 'email_fisik' ? 'selected' : '' }}>Email &amp; Fisik</option>
            </select>
            <select name="periode" onchange="this.form.submit()" class="py-2.5 pl-4 pr-10 bg-white border border-gray-200 rounded-lg focus:ring-[#3b2c85] focus:border-[#3b2c85] shadow-sm text-sm text-gray-700 min-w-[150px]">
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
                <thead class="text-[11px] text-gray-500 bg-white border-b border-gray-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">NOMOR SURAT</th>
                        <th class="px-6 py-4 font-medium">PERIHAL</th>
                        <th class="px-6 py-4 font-medium">TUJUAN</th>
                        <th class="px-6 py-4 font-medium">TANGGAL<br>DISETUJUI</th>
                        <th class="px-6 py-4 font-medium">TANGGAL<br>DISTRIBUSI</th>
                        <th class="px-6 py-4 font-medium">VIA</th>
                        <th class="px-6 py-4 font-medium">STATUS</th>
                        <th class="px-6 py-4 font-medium text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($suratFinals as $surat)
                    @php
                        $nomorParts = explode('/', $surat->nomor_surat ?? 'B-000/TEST/2026');
                        $prefix = $nomorParts[0] . ' -';
                        $suffix = implode('/', array_slice($nomorParts, 1));
                        
                        $draf = $surat->drafSurat;
                        $tujuan = $surat->tujuan ?? ($draf?->suratMasuk?->asal_surat ?? 'Instansi Tujuan');
                        $perihal = $draf?->suratMasuk?->perihal ?? ($draf?->judul ?? 'Perihal Surat');
                        
                        $tglDisetujui = \Carbon\Carbon::parse($surat->created_at)->translatedFormat('d M Y');
                        $tglDistribusi = \Carbon\Carbon::parse($surat->updated_at)->translatedFormat('d M Y');
                        
                        $via = $surat->via ?? 'Email & Fisik';
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                            {{ $prefix }}<br>{{ $suffix }}
                        </td>
                        <td class="px-6 py-4 text-gray-900 font-medium">
                            {{ $perihal }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $tujuan }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            {{ $tglDisetujui }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            {{ $tglDistribusi }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            {{ ucfirst(str_replace('_', ' & ', $via)) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-3 py-1.5 rounded text-[11px] font-bold bg-[#dcfce7] text-[#166534]">
                                Terkirim
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-3">
                                @if($draf)
                                <a href="{{ route('kabiro.progress.show', $draf->id) }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                                    Lihat
                                </a>
                                @else
                                <span class="text-sm font-medium text-gray-400">
                                    Lihat
                                </span>
                                @endif
                                @if($surat->file_ttd)
                                <a href="{{ \Illuminate\Support\Facades\Storage::url($surat->file_ttd) }}" target="_blank" class="px-4 py-1.5 bg-white border border-[#3b2c85] text-[#3b2c85] font-bold rounded hover:bg-[#f5f3ff] transition-colors text-xs">
                                    Unduh PDF
                                </a>
                                @else
                                <span class="px-4 py-1.5 bg-gray-50 border border-gray-200 text-gray-400 font-bold rounded text-xs cursor-not-allowed">
                                    Tidak Ada File
                                </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-10 text-center text-gray-500">Tidak ada surat terdistribusi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($suratFinals->hasPages())
        <div class="p-4 border-t border-gray-100 flex items-center justify-between bg-white">
            <div class="text-sm text-gray-500">
                Menampilkan {{ $suratFinals->firstItem() ?? 0 }}-{{ $suratFinals->lastItem() ?? 0 }} dari {{ $suratFinals->total() }} surat
            </div>
            <div class="flex gap-2">
                @if ($suratFinals->onFirstPage())
                    <span class="px-4 py-2 border border-gray-200 text-gray-400 rounded bg-gray-50 text-sm">Sebelumnya</span>
                @else
                    <a href="{{ $suratFinals->previousPageUrl() }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50 text-sm font-medium">Sebelumnya</a>
                @endif
                
                @if ($suratFinals->hasMorePages())
                    <a href="{{ $suratFinals->nextPageUrl() }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50 text-sm font-medium">Berikutnya</a>
                @else
                    <span class="px-4 py-2 border border-gray-200 text-gray-400 rounded bg-gray-50 text-sm">Berikutnya</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
