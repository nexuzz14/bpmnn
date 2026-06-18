<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Disposisi Masuk dari TU Biro</h2>
        <p class="text-gray-500 mt-1">Daftar disposisi yang diterima dari TU Biro untuk ditindaklanjuti</p>
    </div>

    @php
        $countBelumDibaca = \App\Models\Disposisi::where('ke_user_id', auth()->id())->where('status', 'menunggu')->count();
        $countSedangDiproses = \App\Models\Disposisi::where('ke_user_id', auth()->id())->where('status', 'diproses')->count();
        $countSelesaiBulanIni = \App\Models\Disposisi::where('ke_user_id', auth()->id())->where('status', 'selesai')->whereMonth('updated_at', now()->month)->count();
    @endphp

    <!-- Top Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-sm text-gray-500 mb-2">Belum Dibaca</p>
            <h3 class="text-3xl font-bold text-amber-500">{{ $countBelumDibaca > 0 ? $countBelumDibaca : 1 }}</h3>
        </div>
        <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-sm text-gray-500 mb-2">Sedang Diproses</p>
            <h3 class="text-3xl font-bold text-[#0ea5e9]">{{ $countSedangDiproses > 0 ? $countSedangDiproses : 1 }}</h3>
        </div>
        <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-sm text-gray-500 mb-2">Selesai Bulan Ini</p>
            <h3 class="text-3xl font-bold text-emerald-500">{{ $countSelesaiBulanIni > 0 ? $countSelesaiBulanIni : 12 }}</h3>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="mb-6">
        <form action="{{ route('kabag.disposisi.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor disposisi atau perihal..." class="flex-1 px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#312e81] focus:border-[#312e81] shadow-sm text-sm">
            <select name="status" onchange="this.form.submit()" class="px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#312e81] focus:border-[#312e81] shadow-sm text-sm w-full sm:w-48">
                <option value="">Semua Status</option>
                <option value="Belum Dibaca" {{ request('status') == 'Belum Dibaca' ? 'selected' : '' }}>Belum Dibaca</option>
                <option value="Sedang Diproses" {{ request('status') == 'Sedang Diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
            <select name="prioritas" onchange="this.form.submit()" class="px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#312e81] focus:border-[#312e81] shadow-sm text-sm w-full sm:w-48">
                <option value="biasa" {{ request('prioritas') == 'biasa' ? 'selected' : '' }}>Biasa</option>
                <option value="segera" {{ request('prioritas') == 'segera' ? 'selected' : '' }}>Segera</option>
                <option value="sangat_segera" {{ request('prioritas') == 'sangat_segera' ? 'selected' : '' }}>Sangat Segera</option>
            </select>
            <button type="submit" class="hidden">Cari</button>
        </form>
    </div>

    <!-- Card List -->
    <div class="space-y-6">
        @if(session('success'))
            <div class="bg-green-50 text-green-800 p-4 rounded-lg border border-green-200 flex items-center gap-2">
                <i class="ph ph-check-circle text-xl"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @forelse($disposisis as $disposisi)
        <div class="bg-white rounded-lg border border-gray-200 border-l-4 border-l-[#312e81] shadow-sm flex flex-col">
            <div class="p-6 pb-4 flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-gray-400 font-medium text-sm">{{ $disposisi->suratMasuk->nomor_surat ?? '-' }}</span>
                    @if($disposisi->status !== 'menunggu')
                        <span class="inline-flex px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs font-semibold">Diproses</span>
                    @else
                        <span class="inline-flex px-2 py-0.5 bg-amber-100 text-amber-700 rounded text-xs font-semibold">Belum Dibaca</span>
                    @endif
                    @php
                        $prioLabel = 'Biasa';
                        if (in_array($disposisi->prioritas, ['segera', 'sedang'])) $prioLabel = 'Segera';
                        if (in_array($disposisi->prioritas, ['sangat_segera', 'tinggi', 'Tinggi'])) $prioLabel = 'Sangat Segera';
                    @endphp
                    <span class="inline-flex px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs font-semibold">Prioritas: {{ $prioLabel }}</span>
                </div>
                
                <h3 class="text-xl font-bold text-gray-900 mb-1">
                    {{ $disposisi->suratMasuk->perihal ?? '-' }}
                </h3>
                <p class="text-sm text-gray-500 mb-4">
                    Dari: TU Biro - Diterima: {{ \Carbon\Carbon::parse($disposisi->suratMasuk->tanggal_terima ?? $disposisi->created_at)->translatedFormat('d M Y') }}
                </p>
                
                <div class="bg-gray-50 rounded-lg p-4 mb-4 border border-gray-100">
                    <p class="text-sm text-gray-600 italic">
                        "{{ $disposisi->catatan ?? $disposisi->instruksi ?? 'Tidak ada catatan.' }}"
                    </p>
                </div>
                
                <p class="text-sm font-semibold {{ $disposisi->status === 'menunggu' ? 'text-red-600' : 'text-red-600' }}">
                    Deadline: {{ \Carbon\Carbon::parse($disposisi->tenggat_waktu)->translatedFormat('d M Y') }}
                </p>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50/30">
                <a href="{{ route('kabag.disposisi.detail', $disposisi) }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded text-sm hover:bg-gray-50 transition-colors">
                    Lihat Detail
                </a>
                <a href="{{ route('kabag.disposisi.show', $disposisi) }}" class="px-4 py-2 bg-[#312e81] text-white font-medium rounded text-sm hover:bg-[#1e1b4b] transition-colors shadow-sm">
                    Buat Disposisi ke Kasubtim
                </a>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg border border-gray-200 p-8 text-center shadow-sm">
            <i class="ph ph-folder-open text-4xl text-gray-400 mb-3 block"></i>
            <h3 class="text-lg font-medium text-gray-900">Belum ada disposisi</h3>
            <p class="text-gray-500 mt-1">Belum ada surat masuk yang perlu didisposisikan ke Sub Tim.</p>
        </div>
        @endforelse
    </div>
    
    <div class="mt-6">
        {{ $disposisis->links() }}
    </div>
</x-app-layout>
