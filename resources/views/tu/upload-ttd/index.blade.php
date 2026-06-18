<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Upload & Verifikasi TTD</h2>
        <p class="text-gray-500 mt-1">Upload draft final yang telah ditandatangani untuk didistribusikan</p>
    </div>

    <!-- Filters & Search -->
    <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="flex-1">
            <form action="{{ route('tu.upload-ttd.index') }}" method="GET" class="flex gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor surat atau perihal..." class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#0284c7] focus:border-[#0284c7] shadow-sm text-sm">
                <button type="submit" class="px-6 py-2 bg-[#0284c7] text-white text-sm font-medium rounded-lg hover:bg-[#0369a1] transition-colors shadow-sm">
                    Cari
                </button>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-[11px] text-gray-500 bg-gray-50 border-b border-gray-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">NO.</th>
                        <th class="px-6 py-4 font-medium">NOMOR SURAT</th>
                        <th class="px-6 py-4 font-medium">PERIHAL</th>
                        <th class="px-6 py-4 font-medium">TANGGAL DIBUAT</th>
                        <th class="px-6 py-4 font-medium">STATUS</th>
                        <th class="px-6 py-4 font-medium text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($drafSurats as $index => $draf)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $drafSurats->firstItem() + $index }}</td>
                            <td class="px-6 py-4 text-gray-900 whitespace-nowrap">
                                <span class="font-medium">{{ $draf->suratMasuk->nomor_surat }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-800 min-w-[200px]">{{ $draf->suratMasuk->perihal }}</td>
                            <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                                {{ $draf->created_at->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-3 py-1 rounded text-[11px] font-bold bg-[#fef3c7] text-[#92400e]">
                                    Menunggu TTD
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('tu.upload-ttd.create', $draf->id) }}" class="inline-flex px-4 py-2 bg-[#0284c7] text-white font-medium rounded text-xs hover:bg-[#0369a1] transition-colors shadow-sm whitespace-nowrap">
                                    Upload TTD
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                <i class="ph ph-check-circle text-4xl text-gray-300 mb-2 block"></i>
                                Tidak ada surat yang menunggu upload TTD.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $drafSurats->links() }}
        </div>
    </div>
</x-app-layout>
