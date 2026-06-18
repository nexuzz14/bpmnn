<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Selamat Datang, {{ Auth::user()->name }}</h2>
        <p class="text-gray-500 mt-1">TU Biro — {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($suratMasukCount) }}</h3>
            <p class="text-gray-500 text-sm font-medium">Surat Masuk</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <h3 class="text-3xl font-bold text-blue-600 mb-2">{{ number_format($suratDalamProsesCount) }}</h3>
            <p class="text-blue-600/80 text-sm font-medium">Dalam Proses</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <h3 class="text-3xl font-bold text-amber-600 mb-2">{{ number_format($suratBelumDisposisiCount) }}</h3>
            <p class="text-amber-600/80 text-sm font-medium">Belum Didisposisi</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <h3 class="text-3xl font-bold text-emerald-600 mb-2">{{ number_format($suratSelesaiCount) }}</h3>
            <p class="text-emerald-600/80 text-sm font-medium">Selesai</p>
        </div>
    </div>

    <!-- Surat Masuk Perlu Diproses -->
    <div class="mb-8">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Surat Masuk Perlu Diproses</h3>
        <div class="space-y-4">
            @forelse($suratMasukPerluDiproses as $surat)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 border-l-4 border-l-[#0284c7] flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-400 mb-1">#{{ $surat->nomor_surat }}</p>
                    <h4 class="font-bold text-gray-900 mb-2">{{ $surat->perihal }}</h4>
                    <p class="text-sm text-gray-500">Dari: {{ $surat->asal_surat }} &middot; {{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d M Y') }}</p>
                </div>
                <div>
                    <a href="{{ route('tu.surat-masuk.show', $surat->id) }}" class="px-4 py-2 bg-[#0284c7] text-white font-medium rounded text-sm hover:bg-[#0369a1] transition-colors shadow-sm">
                        Catat & Disposisi
                    </a>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center text-gray-500">
                Tidak ada surat yang perlu diproses.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Siap Upload TTD -->
    <div>
        <h3 class="text-lg font-bold text-gray-900 mb-4">Siap Upload TTD</h3>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="text-[11px] text-gray-500 bg-white border-b border-gray-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">PERIHAL</th>
                        <th class="px-6 py-4 font-medium">NOMOR SURAT</th>
                        <th class="px-6 py-4 font-medium">TANGGAL</th>
                        <th class="px-6 py-4 font-medium text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($siapUploadTtd as $surat)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-900">{{ $surat->suratMasuk->perihal }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $surat->suratMasuk->nomor_surat }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ \Carbon\Carbon::parse($surat->suratMasuk->tanggal_surat)->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('tu.upload-ttd.create', $surat->id) }}" class="px-4 py-2 bg-[#0284c7] text-white font-medium rounded text-sm hover:bg-[#0369a1] transition-colors shadow-sm inline-block">
                                Upload TTD
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada surat yang siap diupload TTD-nya.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
