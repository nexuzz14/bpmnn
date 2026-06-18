<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Selamat Datang, {{ auth()->user()->name }}</h2>
        <p class="text-gray-500 mt-1">Kepala Biro &mdash; {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ \App\Models\ReviuSurat::where('tingkat', 'final')->where('status', 'menunggu')->count() }}</h3>
            <p class="text-sm font-medium text-gray-500">Menunggu Review Saya</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ \App\Models\ReviuSurat::where('tingkat', 'final')->where('status', 'disetujui')->whereMonth('updated_at', now()->month)->count() }}</h3>
            <p class="text-sm font-medium text-gray-500">Disetujui Bulan Ini</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ \App\Models\SuratFinal::where('status', 'terdistribusi')->count() }}</h3>
            <p class="text-sm font-medium text-gray-500">Terdistribusi</p>
        </div>
    </div>

    <!-- Surat Menunggu Review Saya -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-gray-900">Surat Menunggu Review Saya</h3>
            <a href="{{ route('kabiro.review-final.index') }}" class="text-sm text-[#3b2c85] hover:underline font-medium">Lihat Semua &rarr;</a>
        </div>
        
        @php
            $revius = \App\Models\ReviuSurat::with(['drafSurat.suratMasuk', 'drafSurat.pembuat.unitKerja'])
                        ->where('tingkat', 'final')
                        ->where('status', 'menunggu')
                        ->latest()
                        ->take(1)
                        ->get();
        @endphp
        
        @forelse($revius as $reviu)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-sm font-medium text-gray-500">DRAFT-{{ str_pad($reviu->drafSurat->id, 3, '0', STR_PAD_LEFT) }}/{{ date('Y') }}</span>
                    <span class="inline-flex px-2 py-1 rounded text-[10px] font-bold bg-[#e0e7ff] text-[#3730a3]">
                        Menunggu Review
                    </span>
                </div>
                <h4 class="text-lg font-bold text-gray-900 mb-1">{{ $reviu->drafSurat->suratMasuk->perihal ?? $reviu->drafSurat->judul }}</h4>
                <p class="text-sm text-gray-600">Diajukan oleh: {{ $reviu->drafSurat->pembuat->name ?? '-' }} ({{ $reviu->drafSurat->pembuat->unitKerja->nama ?? 'Kabag' }})</p>
            </div>
            
            <hr class="border-gray-100">
            
            <div class="flex justify-between items-center">
                <div class="flex gap-4 text-xs font-bold text-[#16a34a]">
                    <span>&check; Kasubtim</span>
                    <span>&check; Kabag</span>
                </div>
                <a href="{{ route('kabiro.review-final.show', $reviu) }}" class="px-6 py-2 bg-[#3b2c85] text-white font-bold rounded-lg hover:bg-[#2e2269] transition-colors shadow-sm text-sm">
                    Review Sekarang
                </a>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center text-gray-500">
            Tidak ada draf yang menunggu review Anda saat ini.
        </div>
        @endforelse
    </div>

    <!-- Aktivitas Terbaru -->
    <div>
        <h3 class="font-bold text-gray-900 mb-4">Aktivitas Terbaru</h3>
        
        @php
            $aktivitas = \App\Models\ReviuSurat::with('drafSurat.suratMasuk')
                            ->where('tingkat', 'final')
                            ->where('status', '!=', 'menunggu')
                            ->latest('updated_at')
                            ->take(2)
                            ->get();
        @endphp
        
        <div class="flex flex-col gap-4">
            @forelse($aktivitas as $item)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-[#3b2c85]"></div>
                        <h4 class="font-bold text-gray-900 text-sm">Menyetujui Surat</h4>
                        @if($item->status === 'disetujui')
                            <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-[#dcfce7] text-[#166534]">
                                Disetujui
                            </span>
                        @elseif($item->status === 'revisi')
                            <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-[#fef2f2] text-[#dc2626]">
                                Revisi
                            </span>
                        @endif
                    </div>
                    <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($item->updated_at)->translatedFormat('d M Y, H:i') }}</span>
                </div>
                <p class="text-sm text-gray-500 mb-1 ml-4">
                    Surat "{{ $item->drafSurat->suratMasuk->perihal ?? $item->drafSurat->judul }}" 
                    @if($item->status === 'disetujui')
                        disetujui dan diteruskan ke TU untuk upload TTD
                    @else
                        dikembalikan untuk revisi
                    @endif
                </p>
                <p class="text-xs text-gray-400 ml-4">DRAFT-{{ str_pad($item->drafSurat->id, 3, '0', STR_PAD_LEFT) }}/{{ date('Y') }}</p>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center text-gray-500">
                Belum ada aktivitas terbaru.
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
