<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Pilih Template Surat</h2>
        <p class="text-gray-500 mt-1">Pilih jenis surat yang akan dibuat. Template akan mengisi format otomatis</p>
    </div>

    @if($disposisi)
    <div class="bg-[#fefce8] border border-[#fef08a] rounded-xl p-2 MB-8 shadow-sm">
        <div class="mb-2">
            <span class="font-bold text-[#92400e]">Disposisi Terpilih dari {{ $disposisi->pengirim->name ?? 'Kasubtim' }}</span>
        </div>
        <div class="text-[#92400e] text-sm">
            {{ $disposisi->catatan ?? 'Tolong tindaklanjuti dan siapkan draft surat untuk agenda ini.' }} &middot; Batas: {{ \Carbon\Carbon::parse($disposisi->tenggat_waktu)->translatedFormat('d M Y') }}
        </div>
    </div>
    @else
    <div class="bg-red-50 border border-red-200 rounded-xl p-2 MB-8 shadow-sm">
        <div class="mb-2">
            <span class="font-bold text-red-800"><i class="ph ph-warning-circle"></i> Tidak Ada Tugas Disposisi Aktif</span>
        </div>
        <div class="text-red-700 text-sm">
            Anda saat ini tidak memiliki tugas yang berstatus <b>Menunggu</b> atau <b>Diproses</b>. Pembuatan draf surat harus ditautkan dengan sebuah tugas disposisi. Silakan cek menu <a href="{{ route('staf.tugas.index') }}" class="underline font-bold">Tugas Saya</a>.
        </div>
    </div>
    @endif

    <form method="GET" action="{{ route('staf.buat-surat.index') }}" class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama template..." class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#701a35] focus:border-[#701a35] shadow-sm text-sm">
        </div>
        <div class="w-full sm:w-64">
            <select name="kategori" onchange="this.form.submit()" class="w-full py-2 px-4 bg-white border border-gray-200 rounded-lg focus:ring-[#701a35] focus:border-[#701a35] shadow-sm text-sm text-gray-700">
                <option value="">Semua Kategori</option>
                <option value="Surat Keluar" {{ request('kategori') == 'Surat Keluar' ? 'selected' : '' }}>Surat Keluar</option>
                <option value="Nota Dinas" {{ request('kategori') == 'Nota Dinas' ? 'selected' : '' }}>Nota Dinas</option>
                <option value="Surat Undangan" {{ request('kategori') == 'Surat Undangan' ? 'selected' : '' }}>Surat Undangan</option>
            </select>
        </div>
        <button type="submit" class="hidden">Cari</button>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
        @forelse($templates as $tpl)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col relative overflow-hidden">
            <h3 class="font-bold text-gray-900 text-lg mb-2 mt-7">{{ $tpl->nama_template }}</h3>
            <div class="mb-4">
                <span class="inline-flex px-2 py-1 rounded bg-gray-100 text-gray-600 text-[10px] font-medium border border-gray-200">Template Sistem</span>
            </div>
            <p class="text-sm text-gray-500 flex-1 mb-6">Format baku yang disediakan oleh Admin untuk mempercepat pembuatan draf.</p>
            <div class="flex items-center justify-between border-t border-gray-100 pt-4 mt-auto">
                <span class="text-xs text-gray-400">Diunggah {{ $tpl->created_at->diffForHumans() }}</span>
                @if($disposisi)
                <a href="{{ route('staf.draf-surat.create', ['disposisi_id' => $disposisi->id, 'template' => $tpl->id]) }}" class="px-4 py-1.5 bg-white border border-[#701a35] text-[#701a35] hover:bg-[#701a35] hover:text-white font-bold rounded transition-colors text-sm shadow-sm">
                    Gunakan Template
                </a>
                @else
                <button onclick="alert('Silakan pilih tugas disposisi terlebih dahulu dari menu Tugas Saya.')" class="px-4 py-1.5 bg-gray-50 border border-gray-200 text-gray-400 font-bold rounded cursor-not-allowed text-sm shadow-sm">
                    Gunakan Template
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full py-8 text-center bg-white rounded-xl border border-gray-100">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 text-gray-400 mb-3">
                <i class="ph ph-file-dashed text-xl"></i>
            </div>
            <p class="text-gray-500">Belum ada template surat yang diunggah oleh Admin.</p>
        </div>
        @endforelse
    </div>

    <div class="flex justify-center border-t border-gray-100 pt-8 pb-4">
        @if($disposisi)
        <a href="{{ route('staf.draf-surat.create', ['disposisi_id' => $disposisi->id, 'template' => 'blank']) }}" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm text-sm">
            Buat Tanpa Template (kosong)
        </a>
        @else
        <button onclick="alert('Silakan pilih tugas disposisi terlebih dahulu dari menu Tugas Saya.')" class="px-6 py-2.5 bg-gray-50 border border-gray-200 text-gray-400 font-medium rounded-lg cursor-not-allowed shadow-sm text-sm">
            Buat Tanpa Template (kosong)
        </button>
        @endif
    </div>
</x-app-layout>
