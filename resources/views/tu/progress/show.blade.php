<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('tu.progress.index') }}" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                <i class="ph ph-arrow-left text-xl"></i>
            </a>
            <h2 class="font-display font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Tracking Surat: ') . $suratMasuk->nomor_surat }}
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
                        <p class="text-sm font-semibold text-gray-900">{{ $suratMasuk->asal_surat }}</p>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Tanggal Terima</span>
                        <p class="text-sm text-gray-800">{{ \Carbon\Carbon::parse($suratMasuk->tanggal_terima)->translatedFormat('d F Y') }}</p>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Perihal</span>
                        <p class="text-sm text-gray-800">{{ $suratMasuk->perihal }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jejak Aktivitas (ActivityLog) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">Jejak Aktivitas (Timeline)</h3>
                
                <div class="relative border-l border-gray-200 ml-3 space-y-8 pb-4">
                    @forelse($logs as $log)
                        <div class="relative pl-6">
                            <!-- Timeline dot -->
                            <div class="absolute w-3 h-3 bg-blue-500 rounded-full -left-1.5 top-1.5 ring-4 ring-white"></div>
                            
                            <div class="flex flex-col sm:flex-row sm:items-baseline gap-2 mb-1">
                                <h4 class="font-semibold text-gray-900 text-sm">{{ $log->aksi }}</h4>
                                <span class="text-xs text-gray-400">
                                    {{ $log->created_at->translatedFormat('d M Y H:i') }} ({{ $log->created_at->diffForHumans() }})
                                </span>
                            </div>
                            
                            <div class="bg-gray-50 border border-gray-100 rounded-lg p-3 mt-2">
                                <p class="text-sm text-gray-600">{{ $log->deskripsi }}</p>
                                <div class="mt-2 text-xs font-medium text-gray-500">
                                    Oleh: <span class="text-gray-700">{{ $log->user->name ?? 'Sistem' }} ({{ $log->user->jabatan ?? '-' }})</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="ph ph-clock text-4xl mb-2 text-gray-300"></i>
                            <p>Belum ada aktivitas tercatat untuk surat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

