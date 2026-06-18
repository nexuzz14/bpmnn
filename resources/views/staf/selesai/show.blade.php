<x-app-layout>
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('staf.selesai.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-lg flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Detail Surat Selesai</h2>
                <p class="text-gray-500 mt-1">Lacak riwayat draf Anda yang telah selesai diproses</p>
            </div>
        </div>
    </div>

    <!-- The rest of the layout is similar to progress.show but tailored for Selesai -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-900">Detail Surat Masuk</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">ASAL SURAT</span>
                        <p class="text-gray-900">{{ $drafSurat->suratMasuk->asal_surat }}</p>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">TANGGAL TERIMA</span>
                        <p class="text-gray-900">{{ \Carbon\Carbon::parse($drafSurat->suratMasuk->tanggal_terima)->translatedFormat('d F Y') }}</p>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">PERIHAL</span>
                        <p class="text-gray-900">{{ $drafSurat->suratMasuk->perihal }}</p>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">NOMOR DRAF</span>
                        <p class="text-gray-900">DRAFT - {{ str_pad($drafSurat->id, 3, '0', STR_PAD_LEFT) }}/{{ now()->year }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Jejak Aktivitas (Timeline)</h3>
                <div class="relative pl-6 space-y-8 before:content-[''] before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-px before:bg-gray-200">
                    <div class="relative">
                        <div class="absolute -left-[29px] top-1 w-3 h-3 rounded-full bg-green-500 ring-4 ring-white"></div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2">
                            <h4 class="font-bold text-gray-900 text-sm">Konsep Draft Selesai</h4>
                            <span class="text-xs text-gray-400 mt-1 sm:mt-0 font-medium">{{ \Carbon\Carbon::parse($drafSurat->created_at)->translatedFormat('d M Y H:i') }}</span>
                        </div>
                        <div class="bg-gray-50 border border-gray-100 rounded p-4">
                            <p class="text-sm text-gray-700">Draft surat berhasil Anda susun.</p>
                        </div>
                    </div>
                    @foreach($drafSurat->reviuSurat as $reviu)
                        <div class="relative">
                            @if($reviu->status === 'disetujui')
                                <div class="absolute -left-[29px] top-1 w-3 h-3 rounded-full bg-green-500 ring-4 ring-white"></div>
                            @elseif($reviu->status === 'revisi')
                                <div class="absolute -left-[29px] top-1 w-3 h-3 rounded-full bg-red-500 ring-4 ring-white"></div>
                            @else
                                <div class="absolute -left-[29px] top-1 w-3 h-3 rounded-full bg-[#312e81] ring-4 ring-white"></div>
                            @endif
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2">
                                <h4 class="font-bold text-gray-900 text-sm">
                                    Review @if($reviu->tingkat == '1') Kasubtim @elseif($reviu->tingkat == '2') Kepala Bagian @else Kepala Biro @endif
                                    @if($reviu->status === 'disetujui') <span class="ml-2 inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700">Disetujui</span>
                                    @elseif($reviu->status === 'revisi') <span class="ml-2 inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700">Revisi</span>
                                    @else <span class="ml-2 inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-[#e0e7ff] text-[#4f46e5]">Menunggu</span> @endif
                                </h4>
                                <span class="text-xs text-gray-400 mt-1 sm:mt-0 font-medium">{{ \Carbon\Carbon::parse($reviu->updated_at)->translatedFormat('d M Y H:i') }}</span>
                            </div>
                            @if($reviu->status !== 'menunggu')
                                <div class="bg-gray-50 border border-gray-100 rounded p-4">
                                    @if($reviu->catatan_reviu) <p class="text-sm text-gray-700 italic border-l-2 border-gray-300 pl-3">"{{ $reviu->catatan_reviu }}"</p>
                                    @else <p class="text-sm text-gray-700">Telah diperiksa dan disetujui tanpa catatan tambahan.</p> @endif
                                    <div class="mt-3 text-xs text-gray-500">Oleh: <span class="font-medium text-gray-900">{{ $reviu->user->name ?? '-' }}</span></div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-[600px]">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h3 class="font-bold text-gray-900">Preview Draft PDF</h3>
                    <a href="{{ asset('storage/' . $drafSurat->file_draf) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-gray-200 rounded text-xs font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Buka di Tab Baru
                    </a>
                </div>
                <div class="flex-1 bg-gray-100 p-4">
                    <iframe src="{{ asset('storage/' . $drafSurat->file_draf) }}" class="w-full h-full rounded border border-gray-200 shadow-sm bg-white"></iframe>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
