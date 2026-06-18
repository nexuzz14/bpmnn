<x-app-layout>
    <div class="mb-6">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('kabag.draf-saya.index') }}" class="inline-flex items-center px-4 py-1.5 bg-white border border-gray-200 text-gray-700 font-bold rounded hover:bg-gray-50 transition-colors shadow-sm mb-4 gap-2 text-sm">
            <i class="ph ph-arrow-left"></i> Kembali
        </a>
        <h2 class="text-2xl font-bold text-gray-900">Detail Draft</h2>
        <p class="text-gray-500 mt-1">DRAFT-{{ str_pad($drafSurat->id, 3, '0', STR_PAD_LEFT) }}/{{ date('Y', strtotime($drafSurat->created_at)) }}</p>
    </div>

    @php
        $latestReviu = $drafSurat->reviuSurat->first();
        $statusText = 'Belum diajukan ke Kasubtim';
        $badgeText = 'Draft';
        $badgeColor = 'bg-gray-100 text-gray-600';
        
        if ($latestReviu) {
            if ($latestReviu->status == 'disetujui' && $latestReviu->tingkat == 'final') {
                $statusText = 'Telah disetujui final';
                $badgeText = 'Disetujui';
                $badgeColor = 'bg-[#dcfce7] text-[#166534]';
            } elseif ($latestReviu->status == 'disetujui') {
                $statusText = 'Telah disetujui oleh ' . ($latestReviu->tingkat == '1' ? 'Kasubtim' : 'Kabag');
                $badgeText = 'Disetujui';
                $badgeColor = 'bg-[#d1fae5] text-[#047857]';
            } elseif ($latestReviu->status == 'revisi') {
                $statusText = 'Memerlukan revisi berdasarkan catatan Kasubtim';
                $badgeText = 'Revisi';
                $badgeColor = 'bg-[#fee2e2] text-[#dc2626]';
            } elseif ($latestReviu->status == 'menunggu') {
                $statusText = 'Sedang direview oleh ' . ($latestReviu->tingkat == '1' ? 'Kasubtim' : ($latestReviu->tingkat == '2' ? 'Kabag' : 'Kabiro'));
                $badgeText = 'Direview ' . ($latestReviu->tingkat == '1' ? 'Kasubtim' : ($latestReviu->tingkat == '2' ? 'Kabag' : 'Kabiro'));
                $badgeColor = 'bg-[#e0e7ff] text-[#3730a3]';
            }
        }
        if ($drafSurat->status === 'selesai') {
            $statusText = 'Draft telah selesai diproses';
            $badgeText = 'Selesai';
            $badgeColor = 'bg-[#dcfce7] text-[#166534]';
        }
    @endphp

    <div class="bg-gray-50 rounded-lg p-4 mb-6 flex items-center gap-4">
        <span class="inline-flex px-3 py-1 rounded-md text-xs font-bold {{ $badgeColor }}">{{ $badgeText }}</span>
        <span class="text-gray-600 text-sm">{{ $statusText }}</span>
    </div>

    <!-- Informasi Draft -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-6 text-lg">Informasi Draft</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
            <div>
                <span class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">NOMOR DRAFT</span>
                <p class="text-sm font-medium text-gray-900">DRAFT-{{ str_pad($drafSurat->id, 3, '0', STR_PAD_LEFT) }}/{{ date('Y', strtotime($drafSurat->created_at)) }}</p>
            </div>
            <div>
                <span class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">VERSI</span>
                <p class="text-sm font-medium text-gray-900">v{{ $drafSurat->reviuSurat->count() ?: 1 }}</p>
            </div>
            <div>
                <span class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">TANGGAL DIBUAT</span>
                <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($drafSurat->created_at)->translatedFormat('d M Y, H:i') }}</p>
            </div>
            <div>
                <span class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">TERAKHIR DIUBAH</span>
                <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($drafSurat->updated_at)->translatedFormat('d M Y, H:i') }}</p>
            </div>
        </div>
        
        <div class="mb-6">
            <span class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">PERIHAL</span>
            <p class="text-base font-bold text-gray-900">{{ $drafSurat->suratMasuk->perihal }}</p>
        </div>
        
        <div>
            <span class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">CATATAN</span>
            <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-600">
                @php
                    $penugasan = \App\Models\Disposisi::where('surat_masuk_id', $drafSurat->surat_masuk_id)->where('ke_user_id', $drafSurat->dibuat_oleh)->first();
                @endphp
                {{ $penugasan->catatan ?? 'Draft balasan untuk surat ' . strtolower($drafSurat->suratMasuk->perihal) }}
            </div>
        </div>
    </div>

    <!-- File Draft -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-gray-900 text-lg">File Draft</h3>
            <a href="{{ Storage::url($drafSurat->file_draf) }}" target="_blank" class="px-4 py-2 bg-white border border-[#701a35] text-[#701a35] font-bold rounded-lg hover:bg-gray-50 transition-colors shadow-sm text-sm">
                Unduh PDF
            </a>
        </div>
        
        <div class="mb-4">
            <p class="text-sm text-gray-600 font-medium">{{ basename($drafSurat->file_draf) }} &middot; <span class="text-gray-400">PDF Document</span></p>
        </div>
        
        <div class="w-full h-[600px] bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
            <embed src="{{ Storage::url($drafSurat->file_draf) }}" type="application/pdf" width="100%" height="100%" class="rounded-lg" />
        </div>
    </div>

    <!-- Terkait Tugas -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8 flex justify-between items-center">
        <div>
            <h3 class="font-bold text-gray-900 mb-2">Terkait Tugas</h3>
            <p class="text-sm font-bold text-gray-900">Tugas #{{ $penugasan ? $penugasan->id : $drafSurat->id }}</p>
            <p class="text-sm text-gray-500">{{ $drafSurat->suratMasuk->perihal }}</p>
        </div>
        <a href="{{ route('staf.tugas.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm text-sm">
            Lihat Tugas
        </a>
    </div>

    <!-- Actions -->
    <div class="flex justify-between items-center mb-8">
        <form action="{{ route('kabag.draf-saya.destroy', $drafSurat->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus draft ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-2.5 bg-white border border-red-500 text-red-600 font-bold rounded-lg hover:bg-red-50 transition-colors shadow-sm text-sm">
                Hapus Draft
            </button>
        </form>
        <div class="flex gap-4">
            <a href="{{ route('kabag.draf-saya.index') }}" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50 transition-colors shadow-sm text-sm">
                Tutup
            </a>
            @if($latestReviu && $latestReviu->status == 'revisi' && $drafSurat->status === 'revisi')
            <a href="{{ route('kabag.draf-saya.edit', $drafSurat->id) }}" class="px-6 py-2.5 bg-[#701a35] text-white font-bold rounded-lg hover:bg-[#5b152b] transition-colors shadow-sm text-sm">
                Edit Draft
            </a>
            @endif
        </div>
    </div>
</x-app-layout>
