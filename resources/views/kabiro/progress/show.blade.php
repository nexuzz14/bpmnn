<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('kabiro.progress.index') }}" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                <i class="ph ph-arrow-left text-xl"></i>
            </a>
            <h2 class="font-display font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Tracking Surat: ') . ($drafSurat->suratMasuk->nomor_surat ?? $drafSurat->judul) }}
            </h2>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informasi Surat -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-semibold text-gray-800">Detail Surat</h3>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Asal Surat</span>
                        <p class="text-sm font-semibold text-gray-900">{{ $drafSurat->suratMasuk->asal_surat ?? 'Internal' }}</p>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Tanggal Terima</span>
                        <p class="text-sm text-gray-800">{{ $drafSurat->suratMasuk ? \Carbon\Carbon::parse($drafSurat->suratMasuk->tanggal_terima)->translatedFormat('d F Y') : $drafSurat->created_at->translatedFormat('d F Y') }}</p>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Perihal</span>
                        <p class="text-sm text-gray-800">{{ $drafSurat->suratMasuk->perihal ?? $drafSurat->judul }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jejak Aktivitas (ActivityLog) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">Jejak Aktivitas (Timeline)</h3>
                
                <div class="relative border-l border-gray-200 ml-3 space-y-8 pb-4">
                    <div class="relative pl-6">
                        <div class="absolute w-3 h-3 bg-blue-500 rounded-full -left-1.5 top-1.5 ring-4 ring-white"></div>
                        <div class="flex flex-col sm:flex-row sm:items-baseline gap-2 mb-1">
                            <h4 class="font-semibold text-gray-900 text-sm">Konsep Draf Dibuat</h4>
                            <span class="text-xs text-gray-400">
                                {{ $drafSurat->created_at->translatedFormat('d M Y H:i') }}
                            </span>
                        </div>
                        <div class="bg-gray-50 border border-gray-100 rounded-lg p-3 mt-2">
                            <p class="text-sm text-gray-600">Pembuatan draf surat balasan / konsep</p>
                            <div class="mt-2 text-xs font-medium text-gray-500">
                                Oleh: <span class="text-gray-700">{{ $drafSurat->pembuat->name ?? 'Sistem' }} (Staf Penyusun)</span>
                            </div>
                        </div>
                    </div>

                    @forelse($drafSurat->reviuSurat as $reviu)
                        <div class="relative pl-6">
                            <!-- Timeline dot -->
                            <div class="absolute w-3 h-3 {{ $reviu->status === 'disetujui' ? 'bg-green-500' : ($reviu->status === 'revisi' ? 'bg-red-500' : 'bg-yellow-500') }} rounded-full -left-1.5 top-1.5 ring-4 ring-white"></div>
                            
                            <div class="flex flex-col sm:flex-row sm:items-baseline gap-2 mb-1">
                                <h4 class="font-semibold text-gray-900 text-sm">
                                    Review {{ $reviu->tingkat == '1' ? 'Kasubtim' : ($reviu->tingkat == '2' ? 'Kabag' : 'Kepala Biro') }}
                                    <span class="ml-2 inline-flex px-2 py-0.5 rounded text-[10px] font-bold {{ $reviu->status === 'disetujui' ? 'bg-green-100 text-green-800' : ($reviu->status === 'revisi' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($reviu->status) }}
                                    </span>
                                </h4>
                                <span class="text-xs text-gray-400">
                                    {{ $reviu->updated_at->translatedFormat('d M Y H:i') }}
                                </span>
                            </div>
                            
                            <div class="bg-gray-50 border border-gray-100 rounded-lg p-3 mt-2">
                                <p class="text-sm text-gray-600">
                                    @if($reviu->status == 'menunggu')
                                        Sedang menunggu proses review.
                                    @else
                                        {{ $reviu->catatan_reviu ?? 'Tidak ada catatan.' }}
                                    @endif
                                </p>
                                <div class="mt-2 text-xs font-medium text-gray-500">
                                    Oleh: <span class="text-gray-700">{{ $reviu->user->name ?? 'Sistem' }} ({{ $reviu->user->jabatan ?? '-' }})</span>
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                    
                    @if($drafSurat->suratFinal)
                        <div class="relative pl-6">
                            <div class="absolute w-3 h-3 bg-blue-500 rounded-full -left-1.5 top-1.5 ring-4 ring-white"></div>
                            <div class="flex flex-col sm:flex-row sm:items-baseline gap-2 mb-1">
                                <h4 class="font-semibold text-gray-900 text-sm">Surat Final Diunggah & Terdistribusi</h4>
                                <span class="text-xs text-gray-400">
                                    {{ $drafSurat->suratFinal->created_at->translatedFormat('d M Y H:i') }}
                                </span>
                            </div>
                            <div class="bg-gray-50 border border-gray-100 rounded-lg p-3 mt-2">
                                <p class="text-sm text-gray-600">Tata Usaha telah mengunggah versi bertandatangan fisik dan mendistribusikan surat.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

