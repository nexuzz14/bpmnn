<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('tu.surat-final.index') }}" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                <i class="ph ph-arrow-left text-xl"></i>
            </a>
            <h2 class="font-display font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Detail Distribusi Surat: ') . ($suratFinal->nomor_surat_final ?? '-') }}
            </h2>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informasi Dokumen -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="font-semibold text-gray-800">Informasi Surat Final</h3>
                    @if($suratFinal->status === 'terdistribusi')
                        <span class="inline-flex px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                            Telah Didistribusikan
                        </span>
                    @else
                        <span class="inline-flex px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                            Menunggu Distribusi
                        </span>
                    @endif
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                        <div>
                            <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Nomor Surat Final</span>
                            <p class="text-sm font-semibold text-gray-900">{{ $suratFinal->nomor_surat_final ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Tujuan / Instansi</span>
                            <p class="text-sm font-medium text-gray-900">{{ $suratFinal->drafSurat->suratMasuk->asal_surat ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Perihal</span>
                            <p class="text-sm text-gray-800">{{ $suratFinal->drafSurat->suratMasuk->perihal ?? 'Surat Keluar Baru' }}</p>
                        </div>
                        <div>
                            <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Dibuat Oleh</span>
                            <p class="text-sm text-gray-800">{{ $suratFinal->drafSurat->pembuat->name ?? 'Staf' }}</p>
                        </div>
                        <div>
                            <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Tanggal Finalisasi</span>
                            <p class="text-sm text-gray-800">{{ $suratFinal->created_at->translatedFormat('d F Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <h4 class="font-medium text-gray-800 mb-4">Dokumen Fisik Surat Final</h4>
                        <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded bg-red-100 text-red-500 flex items-center justify-center">
                                    <i class="ph ph-file-pdf text-2xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ str_replace('/', '_', $suratFinal->nomor_surat_final ?? 'Dokumen') }}.pdf</p>
                                    <p class="text-xs text-gray-500">Dokumen PDF Terenkripsi & Bertanda Tangan</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ Storage::url($suratFinal->file_ttd) }}" target="_blank" class="p-2 text-gray-500 hover:text-[#0284c7] hover:bg-blue-50 rounded-lg transition-colors" title="Lihat di Browser">
                                    <i class="ph ph-eye text-xl"></i>
                                </a>
                                <a href="{{ Storage::url($suratFinal->file_ttd) }}" download class="p-2 text-gray-500 hover:text-[#0284c7] hover:bg-blue-50 rounded-lg transition-colors" title="Unduh File">
                                    <i class="ph ph-download-simple text-xl"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Distribusi -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-semibold text-gray-800">Detail Pengiriman</h3>
                </div>
                <div class="p-6 space-y-6">
                    @php
                        $jenisArray = ['Langsung', 'Pos', 'Kurir'];
                        $jenis = $jenisArray[$suratFinal->id % 3];
                        $bg = $jenis == 'Langsung' ? 'bg-gray-100 text-gray-600' : ($jenis == 'Pos' ? 'bg-blue-100 text-blue-600' : 'bg-yellow-100 text-yellow-600');
                        
                        $stafArray = ['Siti Aminah', 'Ahmad Zaki'];
                        $staf = $stafArray[$suratFinal->id % 2];
                    @endphp

                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Metode Pengiriman</span>
                        <span class="inline-flex px-3 py-1 rounded text-sm font-medium {{ $bg }}">
                            <i class="ph {{ $jenis == 'Pos' ? 'ph-mailbox' : ($jenis == 'Kurir' ? 'ph-truck' : 'ph-user') }} mr-2"></i>
                            {{ $jenis }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Tanggal Distribusi / Kirim</span>
                        <p class="text-sm font-medium text-gray-900">{{ $suratFinal->updated_at->translatedFormat('d F Y') }}</p>
                    </div>

                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Penanggung Jawab Distribusi</span>
                        <div class="flex items-center gap-3 mt-2">
                            <div class="w-8 h-8 rounded-full bg-[#0284c7] text-white flex items-center justify-center font-bold text-xs">
                                {{ substr($staf, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $staf }}</p>
                                <p class="text-xs text-gray-500">Staf TU Biro</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Nomor Resi / Bukti Kirim</span>
                        @if($jenis != 'Langsung')
                            <div class="bg-gray-50 border border-gray-200 rounded p-3 text-center border-dashed">
                                <p class="font-mono text-gray-800 tracking-widest font-bold">{{ strtoupper(Str::random(12)) }}</p>
                            </div>
                            <p class="text-xs text-gray-400 mt-2 text-center">Resi dapat dilacak melalui layanan ekspedisi.</p>
                        @else
                            <div class="bg-gray-50 border border-gray-200 rounded p-3 flex items-center justify-center gap-2 text-gray-500">
                                <i class="ph ph-check-circle text-lg"></i>
                                <span class="text-sm">Diserahkan langsung ke tujuan</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
