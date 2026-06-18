<x-app-layout>
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Draft Saya</h2>
            <p class="text-gray-500 mt-1">Semua draft surat yang telah Anda buat</p>
        </div>
        <a href="{{ route('kasubtim.buat-surat.index') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm flex items-center gap-2">
            <i class="ph ph-plus"></i>
            Buat Draft Baru
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <span class="block text-sm text-gray-500 mb-1">Total Draft</span>
            <span class="block text-2xl font-bold text-gray-900">{{ $stats['total'] }}</span>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <span class="block text-sm text-gray-500 mb-1">Menunggu Review</span>
            <span class="block text-2xl font-bold text-gray-900">{{ $stats['menunggu'] }}</span>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <span class="block text-sm text-gray-500 mb-1">Disetujui</span>
            <span class="block text-2xl font-bold text-gray-900">{{ $stats['disetujui'] }}</span>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <span class="block text-sm text-gray-500 mb-1">Perlu Revisi</span>
            <span class="block text-2xl font-bold text-gray-900">{{ $stats['revisi'] }}</span>
        </div>
    </div>

    <!-- Filters & Search -->
    <form action="{{ route('kasubtim.draf-saya.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor draft atau judul..." class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#701a35] focus:border-[#701a35] shadow-sm text-sm">
        </div>
        <div class="flex gap-4">
            <select name="status" onchange="this.form.submit()" class="py-2 pl-4 pr-10 bg-white border border-gray-200 rounded-lg focus:ring-[#701a35] focus:border-[#701a35] shadow-sm text-sm text-gray-700">
                <option value="Semua Status" {{ request('status') == 'Semua Status' ? 'selected' : '' }}>Semua Status</option>
                <option value="Menunggu Reviu" {{ request('status') == 'Menunggu Reviu' ? 'selected' : '' }}>Menunggu Review</option>
                <option value="Revisi" {{ request('status') == 'Revisi' ? 'selected' : '' }}>Revisi</option>
                <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
            </select>
        </div>
        <button type="submit" class="hidden">Cari</button>
    </form>

    <!-- Cards -->
    <div class="space-y-4">
        @forelse($drafSurats as $draf)
        @php
            $latestReviu = $draf->reviuSurat->first();
            $statusText = 'Draft';
            $statusClasses = 'bg-gray-100 text-gray-600';
            
            if ($latestReviu) {
                if ($latestReviu->status == 'disetujui' && $latestReviu->tingkat == 'final') {
                    $statusText = 'Disetujui';
                    $statusClasses = 'bg-[#dcfce7] text-[#166534]';
                } elseif ($latestReviu->status == 'disetujui') {
                    $statusText = 'Disetujui (' . ($latestReviu->tingkat == '1' ? 'Kasubtim' : 'Kabag') . ')';
                    $statusClasses = 'bg-[#d1fae5] text-[#047857]';
                } elseif ($latestReviu->status == 'revisi') {
                    $statusText = 'Revisi';
                    $statusClasses = 'bg-[#fee2e2] text-[#dc2626]';
                } elseif ($latestReviu->status == 'menunggu') {
                    $statusText = 'Direview ' . ($latestReviu->tingkat == '1' ? 'Kasubtim' : ($latestReviu->tingkat == '2' ? 'Kabag' : 'Kabiro'));
                    $statusClasses = 'bg-[#e0e7ff] text-[#3730a3]';
                }
            }
            if ($draf->status === 'selesai') {
                $statusText = 'Selesai';
                $statusClasses = 'bg-[#dcfce7] text-[#166534]';
            }
        @endphp
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex flex-col md:flex-row justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-xs font-bold text-gray-500 tracking-wider">DRAFT-{{ str_pad($draf->id, 3, '0', STR_PAD_LEFT) }}/{{ date('Y') }} &middot; v{{ $draf->reviuSurat->count() ?: 1 }}</span>
                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold {{ $statusClasses }}">{{ $statusText }}</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $draf->suratMasuk->perihal }}</h3>
                    
                    @php
                        $penugasan = \App\Models\Disposisi::where('surat_masuk_id', $draf->surat_masuk_id)->where('ke_user_id', $draf->dibuat_oleh)->first();
                    @endphp
                    <p class="text-sm text-gray-500 mb-4">Terkait: Tugas #{{ $penugasan ? $penugasan->id : $draf->id }}</p>
                    
                    @if($latestReviu && $latestReviu->status == 'revisi' && $draf->status === 'revisi')
                    <div class="bg-[#fef2f2] border-l-4 border-[#dc2626] p-4 rounded-r-lg mb-4">
                        <p class="text-sm font-bold text-[#dc2626] mb-1">Catatan Revisi dari {{ $latestReviu->tingkat == '1' ? 'Kasubtim' : ($latestReviu->tingkat == '2' ? 'Kabag' : 'Kabiro') }}:</p>
                        <p class="text-sm italic text-[#dc2626]">"{{ $latestReviu->catatan ?? 'Mohon perbaiki format dan data surat' }}"</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pt-4 border-t border-gray-100 mt-2 gap-4">
                <span class="text-xs text-gray-400">Dibuat: {{ \Carbon\Carbon::parse($draf->created_at)->translatedFormat('d M Y, H:i') }}</span>
                <div class="flex gap-2">
                    <a href="{{ Storage::url($draf->file_draf) }}" target="_blank" class="px-4 py-1.5 bg-white border border-gray-300 text-gray-700 font-bold rounded text-xs hover:bg-gray-50 transition-colors shadow-sm">
                        Lihat PDF
                    </a>
                    @if($latestReviu && $latestReviu->status == 'revisi' && $draf->status === 'revisi')
                    <a href="{{ route('kasubtim.draf-saya.edit', $draf) }}" class="px-4 py-1.5 bg-[#701a35] text-white font-bold rounded text-xs hover:bg-[#5b152b] transition-colors shadow-sm">
                        Edit Draft
                    </a>
                    @else
                    <a href="{{ route('kasubtim.draf-saya.show', $draf) }}" class="px-4 py-1.5 bg-white border border-[#701a35] text-[#701a35] font-bold rounded text-xs hover:bg-gray-50 transition-colors shadow-sm">
                        Lihat Detail
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-10 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                <i class="ph ph-file-dashed text-3xl text-gray-400"></i>
            </div>
            <p class="text-gray-500 font-medium">Belum ada draft surat yang dibuat.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-6 flex justify-between items-center text-sm text-gray-500">
        <span>Menampilkan {{ $drafSurats->firstItem() ?? 0 }}-{{ $drafSurats->lastItem() ?? 0 }} dari {{ $drafSurats->total() }} draft</span>
        <div>
            {{ $drafSurats->links() }}
        </div>
    </div>
</x-app-layout>
