<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Distribusi & Pengarsipan</h2>
        <p class="text-gray-500 mt-1">Catatan surat keluar yang telah didistribusikan secara manual oleh TU Biro.</p>
    </div>

    <!-- Banner -->
    <div class="bg-[#f0f9ff] rounded-xl mb-6 p-4 border border-blue-200">
        <p class="text-[#0284c7] text-sm">Distribusi dilaksanakan secara manual. Halaman ini hanya mencatat surat yang telah keluar. Untuk mencatat distribusi baru, lakukan melalui halaman Upload TTD setelah surat ditandatangani.</p>
    </div>

    <!-- Filters & Search -->
    <form method="GET" action="{{ route('tu.surat-final.index') }}" class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor surat atau perihal..." class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#0284c7] focus:border-[#0284c7] shadow-sm text-sm">
        </div>
        <div class="flex gap-4">
            <select name="tahun" onchange="this.form.submit()" class="py-2 px-4 bg-white border border-gray-200 rounded-lg focus:ring-[#0284c7] focus:border-[#0284c7] shadow-sm text-sm text-gray-700 w-24">
                <option value="">Tahun</option>
                <option value="2026" {{ request('tahun') == '2026' ? 'selected' : '' }}>2026</option>
                <option value="2025" {{ request('tahun') == '2025' ? 'selected' : '' }}>2025</option>
            </select>
            <select name="bulan" onchange="this.form.submit()" class="py-2 px-4 bg-white border border-gray-200 rounded-lg focus:ring-[#0284c7] focus:border-[#0284c7] shadow-sm text-sm text-gray-700 w-32">
                <option value="">Semua Bulan</option>
                @foreach(range(1, 12) as $m)
                    <option value="{{ sprintf('%02d', $m) }}" {{ request('bulan') == sprintf('%02d', $m) ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endforeach
            </select>
            <button type="submit" class="hidden">Cari</button>
            <a href="#" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm whitespace-nowrap">
                Unduh PDF
            </a>
            <a href="#" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm whitespace-nowrap">
                Unduh Excel
            </a>
        </div>
    </form>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if(session('success'))
            <div class="bg-green-50 text-green-800 p-4 border-b border-green-100 flex items-center gap-2">
                <i class="ph ph-check-circle text-xl"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-[11px] text-gray-500 bg-gray-50 border-b border-gray-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">NO. SURAT KELUAR</th>
                        <th class="px-6 py-4 font-medium">TANGGAL DISTRIBUSI</th>
                        <th class="px-6 py-4 font-medium">PERIHAL</th>
                        <th class="px-6 py-4 font-medium">TUJUAN</th>
                        <th class="px-6 py-4 font-medium">JENIS DISTRIBUSI</th>
                        <th class="px-6 py-4 font-medium">DIDISTRIBUSI OLEH</th>
                        <th class="px-6 py-4 font-medium text-center">FILE</th>
                        <th class="px-6 py-4 font-medium text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($suratFinals as $surat)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $surat->nomor_surat_final ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                                {{ $surat->updated_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-gray-900 min-w-[200px]">
                                {{ $surat->drafSurat->suratMasuk->perihal ?? 'Surat Keluar Baru' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $surat->drafSurat->suratMasuk->asal_surat ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $jenis = $surat->via ?? 'Langsung';
                                    $bg = $jenis == 'Langsung' ? 'bg-gray-100 text-gray-600' : ($jenis == 'email' ? 'bg-blue-100 text-blue-600' : 'bg-yellow-100 text-yellow-600');
                                @endphp
                                <span class="inline-flex px-2.5 py-1 rounded text-xs font-medium {{ $bg }}">
                                    {{ ucfirst($jenis) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                {{ auth()->user()->name }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ Storage::url($surat->file_ttd) }}" target="_blank" class="text-[#0284c7] hover:underline text-sm font-medium">
                                    Unduh
                                </a>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('tu.surat-final.show', $surat->id) }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="ph ph-file-text text-4xl text-gray-300"></i>
                                    <p>Belum ada surat final yang siap didistribusikan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $suratFinals->links() }}
        </div>
    </div>
</x-app-layout>
