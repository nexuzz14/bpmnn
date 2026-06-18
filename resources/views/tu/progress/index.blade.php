<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ $header['title'] }}</h2>
        <p class="text-gray-500 mt-1">{{ $header['subtitle'] }}</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">Surat Masuk Diproses</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total_aktif'] }}</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">Menunggu Upload TTD</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['menunggu_ttd'] }}</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">Terdistribusi Bulan Ini</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['terdistribusi'] }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900">Daftar Surat Aktif</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-[11px] text-gray-500 bg-gray-50 border-b border-gray-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">NO. AGENDA</th>
                        <th class="px-6 py-4 font-medium">NOMOR SURAT</th>
                        <th class="px-6 py-4 font-medium">PERIHAL</th>
                        <th class="px-6 py-4 font-medium text-center">STATUS SAAT INI</th>
                        <th class="px-6 py-4 font-medium">PROGRESS</th>
                        <th class="px-6 py-4 font-medium text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($drafSurats as $draf)
                    @php
                        $status = $draf->status;
                        $reviu = $draf->reviuSurat->sortByDesc('created_at')->first();
                        
                        $step1 = true;
                        $step2 = $reviu && in_array($reviu->tingkat, ['1', '2', 'final']);
                        $step3 = $reviu && in_array($reviu->tingkat, ['2', 'final']);
                        $step4 = $reviu && $reviu->tingkat == 'final';
                        $step5 = $status == 'selesai';

                        $status_text = 'Draft Sedang Disusun';
                        $status_bg = 'bg-[#e0e7ff] text-[#3730a3]';
                        
                        if ($status == 'menunggu_reviu') {
                            if ($reviu) {
                                if ($reviu->tingkat == '1') {
                                    $status_text = 'Review Kasubtim';
                                } elseif ($reviu->tingkat == '2') {
                                    $status_text = 'Review Kabag';
                                } elseif ($reviu->tingkat == 'final') {
                                    $status_text = 'Review Kabiro';
                                }
                            }
                        } elseif ($status == 'menunggu_ttd' || $status == 'selesai_direviu') {
                            $status_text = 'Menunggu Upload TTD';
                            $status_bg = 'bg-[#fef3c7] text-[#92400e]';
                            $step5 = false;
                        } elseif ($status == 'revisi') {
                            $status_text = 'Perlu Revisi';
                            $status_bg = 'bg-red-100 text-red-800';
                        } elseif ($status == 'selesai') {
                            $status_text = 'Selesai';
                            $status_bg = 'bg-green-100 text-green-800';
                        }
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            #{{ $draf->suratMasuk->nomor_agenda ?? $draf->nomor_agenda ?? date('Y', strtotime($draf->created_at)) . '/' . $draf->id }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            {{ $draf->suratMasuk->nomor_surat ?? '-' }}
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-900 min-w-[200px]">
                            {{ $draf->suratMasuk->perihal ?? $draf->judul }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($status_text === 'Menunggu Upload TTD')
                                <span class="inline-flex px-3 py-1.5 rounded text-[11px] font-bold bg-[#fef3c7] text-[#92400e] flex-col text-center">
                                    Menunggu<br>Upload TTD
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1.5 rounded text-[11px] font-bold {{ $status_bg }} flex-col text-center">
                                    {{ $status_text }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1.5">
                                <span class="px-2.5 py-1 rounded text-[10px] font-bold {{ $step1 ? 'bg-[#0f766e] text-white' : 'bg-[#e5e7eb] text-[#9ca3af]' }}">Konsep</span>
                                <div class="w-4 h-0.5 {{ $step2 ? 'bg-[#0f766e]' : 'bg-[#e5e7eb]' }}"></div>
                                <span class="px-2.5 py-1 rounded text-[10px] font-bold {{ $step2 ? 'bg-[#0f766e] text-white' : 'bg-[#e5e7eb] text-[#9ca3af]' }}">Review<br>Kasubtim</span>
                                <div class="w-4 h-0.5 {{ $step3 ? 'bg-[#0f766e]' : 'bg-[#e5e7eb]' }}"></div>
                                <span class="px-2.5 py-1 rounded text-[10px] font-bold {{ $step3 ? 'bg-[#0f766e] text-white' : 'bg-[#e5e7eb] text-[#9ca3af]' }}">Review<br>Kabag</span>
                                <div class="w-4 h-0.5 {{ $step4 ? 'bg-[#0f766e]' : 'bg-[#e5e7eb]' }}"></div>
                                <span class="px-2.5 py-1 rounded text-[10px] font-bold {{ $step4 ? 'bg-[#0f766e] text-white' : 'bg-[#e5e7eb] text-[#9ca3af]' }}">Kabiro</span>
                                <div class="w-4 h-0.5 {{ $step5 ? 'bg-[#0f766e]' : 'bg-[#e5e7eb]' }}"></div>
                                <span class="px-2.5 py-1 rounded text-[10px] font-bold {{ $step5 ? 'bg-[#0f766e] text-white' : 'bg-[#e5e7eb] text-[#9ca3af]' }}">Selesai</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($status == 'menunggu_ttd' || $status == 'selesai_direviu')
                                <a href="{{ route('tu.upload-ttd.create', $draf->id) }}" class="inline-flex px-4 py-2 bg-[#0284c7] text-white text-[11px] font-bold rounded hover:bg-[#0369a1] transition-colors shadow-sm flex-col text-center">
                                    Upload<br>TTD
                                </a>
                            @else
                                <a href="{{ route('tu.progress.show', $draf->id) }}" class="text-gray-500 hover:text-gray-900 text-sm font-medium">Lihat Detail</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada progress surat aktif.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $drafSurats->links() }}
        </div>
    </div>

    <!-- Riwayat Selesai -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900">Riwayat Selesai</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-[11px] text-gray-500 bg-gray-50 border-b border-gray-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">NO. AGENDA</th>
                        <th class="px-6 py-4 font-medium">NOMOR SURAT</th>
                        <th class="px-6 py-4 font-medium">PERIHAL</th>
                        <th class="px-6 py-4 font-medium">TANGGAL SELESAI</th>
                        <th class="px-6 py-4 font-medium text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($riwayatSelesai as $draf)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            #{{ $draf->suratMasuk->nomor_agenda ?? $draf->nomor_agenda ?? date('Y', strtotime($draf->created_at)) . '/' . $draf->id }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            {{ $draf->suratMasuk->nomor_surat ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-gray-900">
                            {{ $draf->suratMasuk->perihal ?? $draf->judul }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            {{ $draf->updated_at->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('tu.progress.show', $draf->id) }}" class="text-gray-500 hover:text-gray-900 text-sm font-medium">Lihat Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada surat yang selesai.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
