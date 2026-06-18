<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Surat Masuk</h2>
        <p class="text-gray-500 mt-1">Daftar surat masuk yang diterima dari Admin Persuratan</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Toolbar -->
        <form method="GET" action="{{ route('tu.surat-masuk.index') }}" class="p-4 border-b border-gray-100 flex flex-col md:flex-row gap-4 justify-between items-center bg-white">
            <div class="w-full md:w-1/2 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor agenda, perihal..." 
                    class="w-full pl-4 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-[#0284c7] focus:border-[#0284c7] text-sm">
            </div>
            <div class="w-full md:w-auto">
                <select name="status" onchange="this.form.submit()" class="w-full md:w-48 border border-gray-200 rounded-lg focus:ring-[#0284c7] focus:border-[#0284c7] text-sm py-2 px-3 text-gray-700">
                    <option value="">Semua Status</option>
                    <option value="belum_diproses" {{ request('status') == 'belum_diproses' ? 'selected' : '' }}>Belum Diproses</option>
                    <option value="sudah_didisposisi" {{ request('status') == 'sudah_didisposisi' ? 'selected' : '' }}>Sudah Didisposisi</option>
                </select>
            </div>
            <button type="submit" class="hidden">Cari</button>
        </form>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 bg-gray-50 border-b border-gray-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">NO. AGENDA TU</th>
                        <th class="px-6 py-4 font-medium">NOMOR SURAT</th>
                        <th class="px-6 py-4 font-medium">TANGGAL TERIMA</th>
                        <th class="px-6 py-4 font-medium">PENGIRIM</th>
                        <th class="px-6 py-4 font-medium">PERIHAL</th>
                        <th class="px-6 py-4 font-medium">STATUS</th>
                        <th class="px-6 py-4 font-medium text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($suratMasuks as $surat)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-900 whitespace-nowrap">#{{ $surat->nomor_agenda ?? $surat->id }}/{{ \Carbon\Carbon::parse($surat->tanggal_terima ?? $surat->created_at)->format('Y') }}</td>
                        <td class="px-6 py-4 text-gray-700 min-w-[200px]">{{ $surat->nomor_surat }}</td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">{{ \Carbon\Carbon::parse($surat->tanggal_terima ?? $surat->created_at)->translatedFormat('d M Y') }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $surat->asal_surat }}</td>
                        <td class="px-6 py-4 text-gray-700 min-w-[250px]">{{ $surat->perihal }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(\App\Models\Disposisi::where('surat_masuk_id', $surat->id)->exists())
                                <span class="inline-flex px-2.5 py-1 rounded text-[11px] font-bold bg-[#dcfce7] text-[#166534]">
                                    Sudah Didisposisi
                                </span>
                            @else
                                <span class="inline-flex px-2.5 py-1 rounded text-[11px] font-bold bg-[#fef3c7] text-[#92400e]">
                                    Belum Diproses
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            @if(\App\Models\Disposisi::where('surat_masuk_id', $surat->id)->exists())
                                <a href="{{ route('tu.surat-masuk.show', $surat->id) }}" class="inline-flex px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded hover:bg-gray-50 transition-colors shadow-sm bg-white">
                                    Lihat Detail
                                </a>
                            @else
                                <a href="{{ route('tu.disposisi.create', $surat) }}" class="inline-flex px-4 py-2 bg-[#0284c7] text-white text-sm font-medium rounded hover:bg-[#0369a1] transition-colors shadow-sm">
                                    Proses
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            Belum ada surat masuk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-100 text-sm text-gray-500">
            Menampilkan {{ $suratMasuks->firstItem() ?? 0 }}-{{ $suratMasuks->lastItem() ?? 0 }} dari {{ $suratMasuks->total() }} surat
            <div class="mt-4">
                {{ $suratMasuks->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
