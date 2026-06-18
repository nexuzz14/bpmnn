<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ $header['title'] }}</h2>
        <p class="text-gray-500 mt-1">{{ $header['subtitle'] }}</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        @if($role === 'kepala_bagian')
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <p class="text-sm text-gray-500 mb-1">Surat Aktif</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ $stats['surat_aktif'] }}</h3>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <p class="text-sm text-gray-500 mb-1">Perlu Review Saya</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ $stats['perlu_review_saya'] }}</h3>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <p class="text-sm text-gray-500 mb-1">Selesai Bulan Ini</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ $stats['selesai_bulan_ini'] }}</h3>
            </div>
        @elseif($role === 'tata_usaha')
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <p class="text-sm text-gray-500 mb-1">Surat Masuk Diproses</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ $stats['surat_masuk_diproses'] }}</h3>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <p class="text-sm text-gray-500 mb-1">Menunggu Upload TTD</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ $stats['menunggu_upload_ttd'] }}</h3>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <p class="text-sm text-gray-500 mb-1">Terdistribusi Bulan Ini</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ $stats['terdistribusi_bulan_ini'] }}</h3>
            </div>
        @elseif($role === 'staf')
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <p class="text-sm text-gray-500 mb-1">Tugas Aktif</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ $stats['tugas_aktif'] ?? 0 }}</h3>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <p class="text-sm text-gray-500 mb-1">Menunggu Review</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ $stats['menunggu_review'] ?? 0 }}</h3>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <p class="text-sm text-gray-500 mb-1">Selesai Bulan Ini</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ $stats['selesai_bulan_ini'] ?? 0 }}</h3>
            </div>
        @else
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <p class="text-sm text-gray-500 mb-1">Perlu Tanda Tangan</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ $stats['perlu_ttd'] ?? 0 }}</h3>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <p class="text-sm text-gray-500 mb-1">Disetujui Bulan Ini</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ $stats['disetujui_bulan_ini'] ?? 0 }}</h3>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <p class="text-sm text-gray-500 mb-1">Total Surat Keluar</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total_surat_keluar'] ?? 0 }}</h3>
            </div>
        @endif
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900">{{ $header['table_title'] }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-[11px] text-gray-500 bg-gray-50 border-b border-gray-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">NO. AGENDA</th>
                        @if($role === 'tata_usaha')
                            <th class="px-6 py-4 font-medium">NOMOR SURAT</th>
                        @endif
                        <th class="px-6 py-4 font-medium">PERIHAL</th>
                        @if($role !== 'tata_usaha' && $role !== 'staf')
                            <th class="px-6 py-4 font-medium">{{ strtoupper($header['col3_title'] ?? '') }}</th>
                        @endif
                        <th class="px-6 py-4 font-medium">STATUS SAAT INI</th>
                        <th class="px-6 py-4 font-medium min-w-[400px]">PROGRESS</th>
                        @if($role !== 'tata_usaha')
                            <th class="px-6 py-4 font-medium text-right">AKSI</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($drafSurats as $draf)
                        @php
                            $status_text = 'Konsep Draft';
                            $status_bg = 'bg-gray-100 text-gray-800';
                            
                            $step1 = true; // Konsep selalu true kalau ada DrafSurat
                            $step2 = false; // Review Kasubtim
                            $step3 = false; // Review Kabag
                            $step4 = false; // Kabiro
                            $step5 = false; // Selesai
                            
                            $latestReview = $draf->reviuSurat->first();
                            
                            if ($latestReview) {
                                if ($latestReview->tingkat == '1') {
                                    $status_text = 'Review Kasubtim';
                                    $status_bg = 'bg-blue-100 text-blue-800';
                                    if ($latestReview->status == 'disetujui') {
                                        $step2 = true;
                                    }
                                } elseif ($latestReview->tingkat == '2') {
                                    $step2 = true;
                                    $status_text = 'Review Kabag';
                                    $status_bg = 'bg-indigo-100 text-indigo-800';
                                    if ($latestReview->status == 'disetujui') {
                                        $step3 = true;
                                    }
                                } elseif ($latestReview->tingkat == 'final') {
                                    $step2 = true;
                                    $step3 = true;
                                    $status_text = 'Menunggu Persetujuan';
                                    $status_bg = 'bg-[#f3e8ff] text-[#3b2c85]';
                                    if ($latestReview->status == 'disetujui') {
                                        $step4 = true;
                                        $status_text = 'Menunggu Upload TTD';
                                        $status_bg = 'bg-[#e0e7ff] text-[#3730a3]';
                                    } else {
                                        if(auth()->user()->role == 'kepala_biro') {
                                            $status_text = 'Menunggu Persetujuan Saya';
                                        }
                                    }
                                }
                            }
                            
                            if ($draf->suratFinal()->exists()) {
                                $step2 = true; $step3 = true; $step4 = true; $step5 = true;
                                $status_text = 'Terdistribusi';
                                $status_bg = 'bg-green-100 text-green-800';
                            }
                        @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            #{{ $draf->suratMasuk->nomor_agenda ?? $draf->nomor_agenda ?? '-' }}
                        </td>
                        @if($role === 'tata_usaha')
                            <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                                {{ $draf->suratMasuk->nomor_surat ?? '-' }}
                            </td>
                        @endif
                        <td class="px-6 py-4 font-bold text-gray-900 min-w-[200px]">
                            <p>{{ $draf->suratMasuk->perihal ?? $draf->judul }}</p>
                            @if($role === 'tata_usaha' && $draf->suratMasuk)
                                <p class="text-[11px] font-normal text-gray-500 mt-1">
                                    Dari: {{ $draf->suratMasuk->asal_surat ?? $draf->suratMasuk->pengirim }} &middot; {{ $draf->suratMasuk->created_at->format('d M Y') }}
                                </p>
                            @endif
                        </td>
                        @if($role !== 'tata_usaha' && $role !== 'staf')
                            <td class="px-6 py-4 text-gray-600">
                                {{ $draf->pembuat->unitKerja->nama ?? 'Bagian Keuangan' }}
                            </td>
                        @endif
                        <td class="px-6 py-4">
                            @if($status_text === 'Menunggu Upload TTD')
                                <span class="inline-flex px-3 py-1.5 rounded text-[11px] font-bold bg-[#fef3c7] text-[#92400e] flex-col text-center">
                                    Menunggu<br>Upload<br>TTD
                                </span>
                            @elseif(str_contains($status_text, 'Review'))
                                <span class="inline-flex px-3 py-1.5 rounded text-[11px] font-bold bg-[#e0e7ff] text-[#3730a3] flex-col text-center">
                                    {{ explode(' ', $status_text)[0] }}<br>{{ explode(' ', $status_text)[1] ?? '' }}
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1.5 rounded text-[11px] font-bold {{ $status_bg }}">
                                    {{ $status_text }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1.5">
                                <span class="px-2.5 py-1 rounded text-[10px] font-bold {{ $step1 ? 'bg-[#065f46] text-white' : 'bg-[#e5e7eb] text-[#9ca3af]' }}">Konsep</span>
                                <div class="w-4 h-0.5 {{ $step2 ? 'bg-[#065f46]' : 'bg-[#e5e7eb]' }}"></div>
                                <span class="px-2.5 py-1 rounded text-[10px] font-bold {{ $step2 ? 'bg-[#065f46] text-white' : 'bg-[#e5e7eb] text-[#9ca3af]' }}">Review Kasubtim</span>
                                <div class="w-4 h-0.5 {{ $step3 ? 'bg-[#065f46]' : 'bg-[#e5e7eb]' }}"></div>
                                <span class="px-2.5 py-1 rounded text-[10px] font-bold {{ $step3 ? 'bg-[#065f46] text-white' : 'bg-[#e5e7eb] text-[#9ca3af]' }}">Review Kabag</span>
                                <div class="w-4 h-0.5 {{ $step4 ? 'bg-[#065f46]' : 'bg-[#e5e7eb]' }}"></div>
                                <span class="px-2.5 py-1 rounded text-[10px] font-bold {{ $step4 ? 'bg-[#065f46] text-white' : 'bg-[#e5e7eb] text-[#9ca3af]' }}">Kabiro</span>
                                <div class="w-4 h-0.5 {{ $step5 ? 'bg-[#065f46]' : 'bg-[#e5e7eb]' }}"></div>
                                <span class="px-2.5 py-1 rounded text-[10px] font-bold {{ $step5 ? 'bg-[#065f46] text-white' : 'bg-[#e5e7eb] text-[#9ca3af]' }}">Selesai</span>
                            </div>
                        </td>
                        @if($role !== 'tata_usaha')
                            <td class="px-6 py-4 text-right">
                                @if(auth()->user()->role == 'kepala_biro' && $status_text == 'Menunggu Persetujuan Saya')
                                    <a href="{{ route('kabiro.review.show', $draf->id) }}" class="inline-flex px-5 py-2 bg-[#3b2c85] text-white text-sm font-medium rounded-lg hover:bg-[#2e2269] transition-colors shadow-sm">
                                        Tinjau
                                    </a>
                                @elseif($role === 'staf')
                                    <a href="{{ route('staf.draf-surat.show', $draf->id) }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm inline-flex items-center justify-center min-w-[80px]">
                                        Lihat<br>Detail
                                    </a>
                                @else
                                    <a href="{{ url()->current() }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium">Lihat</a>
                                @endif
                            </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">Belum ada progress surat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $drafSurats->links() }}
        </div>
    </div>

    @if($role === 'staf' && isset($riwayatSelesai))
    <!-- Riwayat Surat Selesai (Staf) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center cursor-pointer">
            <h3 class="font-bold text-gray-900">Riwayat Surat Selesai</h3>
            <i class="ph ph-caret-down text-gray-400"></i>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-[11px] text-gray-500 bg-gray-50 border-b border-gray-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">NO. AGENDA</th>
                        <th class="px-6 py-4 font-medium">PERIHAL</th>
                        <th class="px-6 py-4 font-medium">TANGGAL SELESAI</th>
                        <th class="px-6 py-4 font-medium text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($riwayatSelesai as $draf)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            #{{ $draf->suratMasuk->nomor_agenda ?? $draf->nomor_agenda ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-gray-900">
                            {{ $draf->suratMasuk->perihal ?? $draf->judul }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ $draf->updated_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('staf.draf-surat.show', $draf->id) }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium">Lihat</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada surat yang selesai.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
</x-app-layout>
