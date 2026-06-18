<x-app-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Selamat Datang, {{ auth()->user()->name ?? '-' }}</h2>
        <p class="text-gray-500 mt-1">Kepala Bagian &mdash; {{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-3xl font-bold text-gray-900 mb-2">
                {{ \App\Models\Disposisi::where('ke_user_id', auth()->id())->where('status', 'menunggu')->count() }}
            </h3>
            <p class="text-sm font-medium text-gray-500">Disposisi Belum Ditindaklanjuti</p>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-3xl font-bold text-gray-900 mb-2">
                {{ \App\Models\ReviuSurat::where('tingkat', '2')->where('status', 'menunggu')->count() }}
            </h3>
            <p class="text-sm font-medium text-gray-500">Draft Perlu Review</p>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-3xl font-bold text-gray-900 mb-2">
                {{ \App\Models\ReviuSurat::where('reviewer_id', auth()->id())->where('status', 'disetujui')->whereMonth('updated_at', now()->month)->count() }}
            </h3>
            <p class="text-sm font-medium text-gray-500">Disetujui Bulan Ini</p>
        </div>
    </div>

    <!-- Disposisi Masuk Section -->
    <div class="mb-8">
        <h3 class="font-bold text-gray-900 mb-4">Disposisi Masuk dari TU Biro</h3>
        
        @php
            $disposisis = \App\Models\Disposisi::with('suratMasuk')
                            ->where('ke_user_id', auth()->id())
                            ->where('status', 'menunggu')
                            ->latest()
                            ->take(5)
                            ->get();
        @endphp
        
        @if($disposisis->count() > 0)
            <div class="space-y-4">
                @foreach($disposisis as $disposisi)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 border-l-4 border-l-[#312e81] p-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 transition-all hover:shadow-md">
                    <div class="flex-1 space-y-2">
                        <div class="flex items-center gap-3">
                            <h4 class="font-semibold text-gray-900 text-lg">
                                Disposisi {{ $disposisi->suratMasuk->nomor_surat ?? '-' }} &mdash; {{ $disposisi->suratMasuk->perihal ?? '-' }}
                            </h4>
                            @if($disposisi->status !== 'menunggu')
                                <span class="inline-flex px-2.5 py-1 bg-blue-100 text-blue-700 rounded text-xs font-semibold whitespace-nowrap">Diproses</span>
                            @else
                                <span class="inline-flex px-2.5 py-1 bg-amber-100 text-amber-700 rounded text-xs font-semibold whitespace-nowrap">Belum Dibaca</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500">Dari: TU Biro</p>
                        <p class="text-sm text-gray-500 italic">"{{ $disposisi->catatan ?? $disposisi->instruksi ?? 'Tidak ada catatan.' }}"</p>
                        <p class="text-sm text-gray-400 mt-2">Deadline: {{ \Carbon\Carbon::parse($disposisi->tenggat_waktu)->translatedFormat('d M Y') }}</p>
                    </div>
                    <div>
                        <a href="{{ route('kabag.disposisi.show', $disposisi) }}" class="px-5 py-2.5 bg-[#312e81] text-white font-medium rounded hover:bg-[#1e1b4b] transition-colors text-sm shadow-sm inline-block">
                            Buka
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="p-8 text-center text-gray-500 bg-white rounded-xl shadow-sm border border-gray-100">
                <i class="ph ph-check-circle text-4xl text-green-500 mb-2 block"></i>
                Tidak ada surat baru yang perlu didisposisikan.
            </div>
        @endif
    </div>

    <!-- Draft Perlu Review Section -->
    <div>
        <h3 class="font-bold text-gray-900 mb-4">Draft Perlu Review</h3>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            @php
                $revius = \App\Models\ReviuSurat::with('drafSurat.suratMasuk', 'drafSurat.pembuat')
                            ->where('tingkat', '2')
                            ->where('status', 'menunggu')
                            ->latest()
                            ->take(5)
                            ->get();
            @endphp
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="text-xs text-gray-500 font-medium border-b border-gray-100 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">JUDUL DRAFT</th>
                            <th class="px-6 py-4">DARI KASUBTIM</th>
                            <th class="px-6 py-4 text-right">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($revius as $reviu)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-900">{{ $reviu->drafSurat->suratMasuk->perihal ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $reviu->drafSurat->pembuat->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('kabag.review.show', $reviu) }}" class="inline-block px-5 py-2 bg-[#312e81] text-white font-medium rounded hover:bg-[#1e1b4b] transition-colors shadow-sm">
                                    Review
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                <i class="ph ph-check-circle text-2xl text-green-500 mb-2 block"></i>
                                Tidak ada draft yang menunggu review.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
