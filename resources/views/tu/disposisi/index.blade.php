<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Buat Disposisi</h2>
        <p class="text-gray-500 mt-1">Daftar surat yang perlu dicatat dan didisposisi ke Kabag</p>
    </div>

    <!-- Alert Box -->
    @if($suratMasuks->count() > 0)
    <div class="bg-[#fef3c7] border-l-4 border-[#f59e0b] rounded-r-xl p-2 MB-8">
        <p class="font-bold text-[#b45309] text-lg mb-1">{{ $suratMasuks->total() }} surat menunggu untuk diproses</p>
        <p class="text-[#b45309]">Segera catat agenda dan buat disposisi ke Kepala Bagian terkait</p>
    </div>
    @endif

    <!-- Cards List -->
    <div class="space-y-4">
        @forelse($suratMasuks as $surat)
        <div class="bg-white border border-gray-200 border-l-4 border-l-[#0284c7] rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <p class="text-sm text-gray-400 mb-1">#{{ date('Y', strtotime($surat->created_at)) }}/{{ $surat->id }}</p>
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $surat->perihal }}</h3>
                <p class="text-sm text-gray-500">Dari: {{ $surat->asal_surat }} &middot; {{ \Carbon\Carbon::parse($surat->tanggal_surat ?? $surat->created_at)->translatedFormat('d M Y') }}</p>
            </div>
            <div>
                <a href="{{ route('tu.disposisi.create', $surat) }}" class="inline-flex px-6 py-2.5 bg-[#0284c7] text-white font-medium rounded-lg hover:bg-[#0369a1] transition-colors shadow-sm whitespace-nowrap">
                    Catat & Disposisi
                </a>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-10 text-center">
            <p class="text-gray-500">Belum ada surat masuk yang perlu didisposisi.</p>
        </div>
        @endforelse
    </div>

    @if($suratMasuks->hasPages())
    <div class="mt-6">
        {{ $suratMasuks->links() }}
    </div>
    @endif
</x-app-layout>
