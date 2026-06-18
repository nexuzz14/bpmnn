<x-app-layout>
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Selamat Datang, {{ auth()->user()->name }}</h2>
        <p class="text-gray-500 mt-1">Kepala Sub Tim — {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-center">
            <h3 class="text-4xl font-bold text-gray-900 mb-2">{{ $disposisiBaru }}</h3>
            <p class="text-sm font-medium text-gray-500">Disposisi Belum Dibaca</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-center">
            <h3 class="text-4xl font-bold text-gray-900 mb-2">{{ $draftPerluReview }}</h3>
            <p class="text-sm font-medium text-gray-500">Draft Perlu Review</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-center">
            <h3 class="text-4xl font-bold text-gray-900 mb-2">{{ $suratSelesai }}</h3>
            <p class="text-sm font-medium text-gray-500">Selesai Bulan Ini</p>
        </div>
    </div>

    <!-- Disposisi Masuk dari Kabag -->
    <h3 class="font-bold text-gray-900 mb-4">Disposisi Masuk dari Kabag</h3>
    <div class="space-y-4 mb-8">
        @php
            $disposisis = \App\Models\Disposisi::with('suratMasuk')
                            ->where('ke_user_id', auth()->id())
                            ->whereIn('status', ['menunggu', 'dibaca'])
                            ->latest()
                            ->take(5)
                            ->get();
        @endphp
        @forelse($disposisis as $disposisi)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col sm:flex-row justify-between items-start gap-4 border-l-4 border-l-[#5c3a21]">
                <div class="flex-1">
                    <h4 class="font-bold text-gray-900 text-base mb-1">Disposisi {{ $disposisi->suratMasuk->nomor_surat }} — {{ $disposisi->suratMasuk->perihal }}</h4>
                    <p class="text-sm text-gray-500 mb-2">Dari: Kabag</p>
                    <p class="text-sm text-gray-600 italic mb-4">"{{ $disposisi->catatan ?? '-' }}"</p>
                    <p class="text-xs font-medium text-gray-400">Deadline: {{ \Carbon\Carbon::parse($disposisi->tenggat_waktu)->translatedFormat('d M Y') }}</p>
                </div>
                <div class="flex flex-col items-end gap-12 sm:min-h-[100px] justify-between">
                    @if($disposisi->status === 'menunggu')
                        <span class="inline-flex px-3 py-1 rounded text-[10px] font-bold bg-[#fef3c7] text-[#92400e]">Belum Dibaca</span>
                    @else
                        <span class="inline-flex px-3 py-1 rounded text-[10px] font-bold bg-gray-100 text-gray-600">Dibaca</span>
                    @endif
                    
                    <a href="{{ route('kasubtim.penugasan.show', $disposisi) }}" class="inline-flex px-6 py-2 bg-[#5c3a21] text-white font-medium rounded text-xs hover:bg-[#4a2e1a] transition-colors shadow-sm">
                        Buka
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center text-gray-500">
                Tidak ada disposisi baru.
            </div>
        @endforelse
    </div>

    <!-- Draft Perlu Saya Review -->
    <h3 class="font-bold text-gray-900 mb-4">Draft Perlu Saya Review</h3>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        @php
            $revius = \App\Models\ReviuSurat::with(['drafSurat.suratMasuk', 'drafSurat.pembuat'])
                        ->where('tingkat', '1')
                        ->where('status', 'menunggu')
                        ->latest()
                        ->take(5)
                        ->get();
        @endphp
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-[11px] text-gray-500 bg-white border-b border-gray-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">JUDUL DRAFT</th>
                        <th class="px-6 py-4 font-medium">OLEH STAF</th>
                        <th class="px-6 py-4 font-medium text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($revius as $reviu)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-900 font-medium whitespace-nowrap truncate max-w-[400px]">Balasan {{ $reviu->drafSurat->suratMasuk->perihal }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $reviu->drafSurat->pembuat->name ?? '-' }} (Staf)</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('kasubtim.review.show', $reviu) }}" class="inline-flex px-6 py-2 bg-[#5c3a21] text-white font-medium rounded text-xs hover:bg-[#4a2e1a] transition-colors shadow-sm">
                                    Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">Tidak ada draft yang perlu direview.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
