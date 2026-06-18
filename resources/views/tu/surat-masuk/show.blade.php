<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('tu.surat-masuk.index') }}" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                <i class="ph ph-arrow-left text-xl"></i>
            </a>
            <h2 class="font-display font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Detail Surat Masuk') }}
            </h2>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Detail Surat -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $suratMasuk->perihal }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $suratMasuk->nomor_surat }}</p>
                    </div>
                    @php
                        $statusColor = match($suratMasuk->status) {
                            'diterima' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            'diproses' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'menunggu_reviu' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                            'revisi' => 'bg-red-100 text-red-800 border-red-200',
                            'selesai' => 'bg-green-100 text-green-800 border-green-200',
                            default => 'bg-gray-100 text-gray-800 border-gray-200',
                        };
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border {{ $statusColor }} capitalize">
                        {{ str_replace('_', ' ', $suratMasuk->status) }}
                    </span>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Asal Surat</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $suratMasuk->asal_surat }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Jenis Surat</dt>
                            <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $suratMasuk->jenis_surat }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Surat</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($suratMasuk->tanggal_surat)->translatedFormat('d F Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Diterima</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($suratMasuk->tanggal_terima)->translatedFormat('d F Y') }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Keterangan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $suratMasuk->keterangan ?: '-' }}</dd>
                        </div>
                        @if($suratMasuk->file_surat)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 mb-2">Dokumen Lampiran</dt>
                            <dd>
                                <a href="{{ Storage::url($suratMasuk->file_surat) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                    <i class="ph ph-file-pdf text-red-500 text-lg"></i>
                                    Lihat Dokumen PDF
                                </a>
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        <!-- Activity Timeline -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="ph ph-clock-counter-clockwise text-xl text-gray-500"></i>
                    Riwayat Aktivitas
                </h3>
                
                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @forelse($activityLogs as $index => $log)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-role-admin/10 flex items-center justify-center ring-8 ring-white">
                                                <i class="ph ph-activity text-role-admin text-sm"></i>
                                            </span>
                                        </div>
                                        <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                            <div>
                                                <p class="text-sm text-gray-900 font-medium">{{ $log->aksi }}</p>
                                                <p class="text-sm text-gray-500 mt-0.5">{{ $log->deskripsi }}</p>
                                                <p class="text-xs text-gray-400 mt-1">Oleh: {{ $log->user->name ?? 'Sistem' }}</p>
                                            </div>
                                            <div class="whitespace-nowrap text-right text-xs text-gray-500">
                                                <time datetime="{{ $log->created_at }}">{{ $log->created_at->diffForHumans() }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <p class="text-sm text-gray-500 text-center py-4">Belum ada riwayat aktivitas.</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
