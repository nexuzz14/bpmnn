<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Tugas Selesai</h2>
        <p class="text-gray-500 mt-1">Riwayat tugas yang telah selesai dan disetujui</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">Selesai Bulan Ini</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['selesai_bulan_ini'] }}</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">Total Selesai</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total_selesai'] }}</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">Rata-rata Waktu Pengerjaan</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['rata_waktu'] }}</h3>
        </div>
    </div>

    <!-- Filters & Search -->
    <form method="GET" action="{{ route('staf.selesai.index') }}" class="flex gap-4 mb-6">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau nomor draft..." class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#701a35] focus:border-[#701a35] shadow-sm text-sm">
        </div>
        <div>
            <select name="waktu" onchange="this.form.submit()" class="py-2 pl-4 pr-10 bg-white border border-gray-200 rounded-lg focus:ring-[#701a35] focus:border-[#701a35] shadow-sm text-sm text-gray-700">
                <option value="Bulan Ini" {{ request('waktu', 'Bulan Ini') == 'Bulan Ini' ? 'selected' : '' }}>Bulan Ini</option>
                <option value="Semua Waktu" {{ request('waktu') == 'Semua Waktu' ? 'selected' : '' }}>Semua Waktu</option>
                <option value="Bulan Lalu" {{ request('waktu') == 'Bulan Lalu' ? 'selected' : '' }}>Bulan Lalu</option>
            </select>
        </div>
        <button type="submit" class="hidden">Cari</button>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-[11px] text-gray-500 bg-gray-50 border-b border-gray-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">NOMOR DRAFT</th>
                        <th class="px-6 py-4 font-medium">JUDUL TUGAS</th>
                        <th class="px-6 py-4 font-medium">TERKAIT TUGAS</th>
                        <th class="px-6 py-4 font-medium">TANGGAL SELESAI</th>
                        <th class="px-6 py-4 font-medium">STATUS AKHIR</th>
                        <th class="px-6 py-4 font-medium text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($drafSurats as $draf)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            DRAFT-{{ str_pad($draf->id, 3, '0', STR_PAD_LEFT) }}/{{ date('Y') }}
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-900">
                            {{ $draf->suratMasuk->perihal ?? $draf->judul }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            @php
                                $tugasId = \App\Models\Disposisi::where('surat_masuk_id', $draf->surat_masuk_id)->where('ke_user_id', auth()->id())->value('id');
                            @endphp
                            Tugas #{{ $tugasId ?? $draf->id }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            {{ $draf->updated_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1.5 rounded text-[11px] font-bold bg-[#dcfce7] text-[#166534]">
                                Disetujui & Diteruskan
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('staf.selesai.show', $draf->id) }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm inline-flex items-center justify-center">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada tugas yang selesai.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($drafSurats->hasPages())
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">
            Menampilkan {{ $drafSurats->firstItem() }}-{{ $drafSurats->lastItem() }} dari {{ $drafSurats->total() }} tugas
        </p>
        <div class="flex gap-2">
            @if($drafSurats->onFirstPage())
                <span class="px-4 py-2 bg-white border border-gray-200 text-gray-400 text-sm font-medium rounded-lg opacity-50 cursor-not-allowed">Sebelumnya</span>
            @else
                <a href="{{ $drafSurats->previousPageUrl() }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">Sebelumnya</a>
            @endif
            
            @if($drafSurats->hasMorePages())
                <a href="{{ $drafSurats->nextPageUrl() }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">Berikutnya</a>
            @else
                <span class="px-4 py-2 bg-white border border-gray-200 text-gray-400 text-sm font-medium rounded-lg opacity-50 cursor-not-allowed">Berikutnya</span>
            @endif
        </div>
    </div>
    @else
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">
            Menampilkan {{ $drafSurats->count() }} tugas
        </p>
    </div>
    @endif
</x-app-layout>
