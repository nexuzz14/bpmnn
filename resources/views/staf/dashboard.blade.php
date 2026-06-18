<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Selamat Datang, {{ auth()->user()->name }}</h2>
        <p class="text-gray-500 mt-1">Staf &mdash; {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
            <h3 class="text-3xl font-bold text-gray-900">{{ $tugasBaru }}</h3>
            <p class="text-sm font-medium text-gray-500 mt-2">Tugas Baru</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
            <h3 class="text-3xl font-bold text-gray-900">{{ $sedangDikerjakan }}</h3>
            <p class="text-sm font-medium text-gray-500 mt-2">Sedang Dikerjakan</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
            <h3 class="text-3xl font-bold text-gray-900">{{ $menungguReview }}</h3>
            <p class="text-sm font-medium text-gray-500 mt-2">Menunggu Review</p>
        </div>
    </div>

    <!-- Tugas Aktif Saya -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-gray-900">Tugas Aktif Saya</h3>
            <a href="{{ route('staf.tugas.index') }}" class="text-sm text-[#701a35] hover:underline font-medium flex items-center gap-1">Lihat Semua <i class="ph ph-arrow-right"></i></a>
        </div>
        
        <div class="space-y-4">
            @forelse($tugasAktif as $tugas)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 border-l-4 border-l-[#dc2626]">
                    <div class="flex items-center gap-3 mb-3">
                        @if($tugas->status == 'menunggu')
                            <span class="inline-flex px-2 py-1 rounded bg-[#fefce8] text-[#92400e] text-[10px] font-bold">Tugas Baru</span>
                        @else
                            <span class="inline-flex px-2 py-1 rounded bg-[#fefce8] text-[#92400e] text-[10px] font-bold">Sedang Dikerjakan</span>
                        @endif
                        
                        @if(\Carbon\Carbon::parse($tugas->tenggat_waktu)->isPast() || \Carbon\Carbon::parse($tugas->tenggat_waktu)->diffInDays(now()) <= 1)
                            <span class="inline-flex px-2 py-1 rounded bg-[#fee2e2] text-[#b91c1c] text-[10px] font-bold">Prioritas Tinggi</span>
                        @endif
                    </div>
                    
                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $tugas->suratMasuk->perihal }}</h3>
                    <p class="text-gray-500 text-sm mb-4">Dari: {{ $tugas->pengirim->name ?? 'Kasubtim' }} ({{ \Str::title(str_replace('_', ' ', $tugas->pengirim->role ?? 'Kasubtim')) }})</p>
                    
                    <div class="bg-gray-50 rounded-lg p-4 italic text-sm text-gray-600 mb-4">
                        "{{ $tugas->catatan ?? 'Tolong tindaklanjuti dan siapkan draft surat untuk agenda ini.' }}"
                    </div>
                    
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <p class="text-sm font-bold text-[#dc2626]">Deadline: {{ \Carbon\Carbon::parse($tugas->tenggat_waktu)->translatedFormat('d M Y') }}</p>
                        <a href="{{ route('staf.draf-surat.create', ['surat_masuk_id' => $tugas->suratMasuk->id, 'disposisi_id' => $tugas->id]) }}" class="inline-flex px-6 py-2 bg-[#701a35] text-white font-bold rounded-lg text-sm hover:bg-[#5b152b] transition-colors shadow-sm w-full sm:w-auto justify-center">
                            {{ $tugas->status == 'menunggu' ? 'Mulai Draf' : 'Lanjutkan Draft' }}
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center text-gray-500">
                    <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4 text-green-500">
                        <i class="ph ph-check-circle text-3xl"></i>
                    </div>
                    <h3 class="text-gray-900 font-bold mb-1">Semua Selesai</h3>
                    <p class="text-gray-500 text-sm">Tidak ada tugas baru atau aktif saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Draft Terbaru -->
    <div>
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-gray-900">Draft Terbaru</h3>
            <a href="{{ route('staf.draf-surat.index') }}" class="text-sm text-[#701a35] hover:underline font-medium flex items-center gap-1">Lihat Semua <i class="ph ph-arrow-right"></i></a>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-[11px] text-gray-500 bg-white border-b border-gray-100 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4 font-bold">NOMOR DRAFT</th>
                            <th class="px-6 py-4 font-bold">JUDUL DRAFT</th>
                            <th class="px-6 py-4 font-bold">TANGGAL</th>
                            <th class="px-6 py-4 font-bold text-center">STATUS</th>
                            <th class="px-6 py-4 font-bold text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($draftTerbaru as $draf)
                            @php
                                $latestReviu = $draf->reviuSurat->first();
                                $statusText = 'Draft';
                                $statusClasses = 'bg-gray-100 text-gray-600 border border-gray-200';
                                
                                if ($latestReviu) {
                                    if ($latestReviu->status == 'disetujui') {
                                        $statusText = 'Disetujui';
                                        $statusClasses = 'bg-[#dcfce7] text-[#166534] border border-[#bbf7d0]';
                                    } elseif ($latestReviu->status == 'revisi') {
                                        $statusText = 'Revisi';
                                        $statusClasses = 'bg-[#fef2f2] text-[#dc2626] border border-[#fecaca]';
                                    } elseif ($latestReviu->status == 'menunggu') {
                                        $statusText = 'Menunggu Review';
                                        $statusClasses = 'bg-[#fefce8] text-[#92400e] border border-[#fef08a]';
                                    }
                                } elseif ($draf->status == 'menunggu_reviu') {
                                    $statusText = 'Menunggu Review';
                                    $statusClasses = 'bg-[#fefce8] text-[#92400e] border border-[#fef08a]';
                                } elseif ($draf->status == 'selesai') {
                                    $statusText = 'Selesai';
                                    $statusClasses = 'bg-[#dcfce7] text-[#166534] border border-[#bbf7d0]';
                                }
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-gray-500 font-mono text-xs whitespace-nowrap uppercase">DRAFT-{{ str_pad($draf->id, 3, '0', STR_PAD_LEFT) }}/{{ date('Y') }}</td>
                                <td class="px-6 py-4 text-gray-900 font-bold min-w-[200px]">{{ $draf->suratMasuk->perihal }}</td>
                                <td class="px-6 py-4 text-gray-500 whitespace-nowrap">{{ \Carbon\Carbon::parse($draf->created_at)->translatedFormat('d M Y') }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="inline-flex px-3 py-1 rounded text-[10px] font-bold {{ $statusClasses }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('staf.draf-surat.edit', $draf->id) }}" class="inline-flex px-4 py-1.5 bg-white border border-gray-200 text-gray-700 font-medium rounded text-xs hover:bg-gray-50 hover:border-gray-300 transition-colors shadow-sm whitespace-nowrap">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    Belum ada draf yang dibuat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
