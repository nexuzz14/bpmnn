<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Review Surat &mdash; {{ $reviewFinal->drafSurat->suratMasuk->perihal ?? $reviewFinal->drafSurat->judul }}</h2>
        <p class="text-gray-500 mt-1">Review dan persetujuan akhir surat sebelum ditandatangani</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        
        <!-- Left Column -->
        <div class="flex-1 flex flex-col gap-6">
            
            <!-- Top Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900">Surat Menunggu Persetujuan</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-[11px] text-gray-500 bg-white border-b border-gray-100 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 font-medium">PERIHAL</th>
                                <th class="px-6 py-3 font-medium text-center">DIAJUKAN KABAG</th>
                                <th class="px-6 py-3 font-medium text-right">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-900">{{ $reviewFinal->drafSurat->suratMasuk->perihal ?? $reviewFinal->drafSurat->judul }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Paraf: Kasubtim &check; &middot; Kabag &check;</p>
                                </td>
                                <td class="px-6 py-4 text-center text-gray-600">
                                    @php
                                        $kabagReview = \App\Models\ReviuSurat::where('draf_surat_id', $reviewFinal->drafSurat->id)->where('tingkat', '2')->first();
                                    @endphp
                                    {{ $kabagReview->reviewer->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="#review-form" class="inline-flex px-6 py-2 bg-[#3b2c85] text-white font-bold rounded text-xs hover:bg-[#2e2269] transition-colors">
                                        Review
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Ringkasan Surat Masuk -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900">Ringkasan Surat Masuk</h3>
                </div>
                <div class="p-5 flex flex-col gap-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Nomor Surat</p>
                            <p class="text-sm font-medium text-gray-900">{{ $reviewFinal->drafSurat->suratMasuk->nomor_surat }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Tanggal</p>
                            <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($reviewFinal->drafSurat->suratMasuk->tanggal_surat)->translatedFormat('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Pengirim</p>
                            <p class="text-sm font-medium text-gray-900">{{ $reviewFinal->drafSurat->suratMasuk->asal_surat }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Klasifikasi</p>
                            <p class="text-sm font-medium text-gray-900">{{ ucfirst($reviewFinal->drafSurat->suratMasuk->jenis_surat) }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Perihal</p>
                        <p class="text-sm font-medium text-gray-900">{{ $reviewFinal->drafSurat->suratMasuk->perihal }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 mb-2">Ringkasan Isi</p>
                        <div class="bg-[#fffbeb] border border-[#fde68a] p-4 rounded-lg text-sm text-[#92400e]">
                            Ditjen Pendidikan Islam Kemenag meminta Biro Keuangan dan BMN untuk menyediakan data rekap Barang Milik Negara (BMN) semester I tahun anggaran 2026 yang berada di bawah pengelolaan Ditjen Pendis, mencakup data aset tetap, persediaan, dan mutasi BMN. Data diperlukan untuk penyusunan laporan keuangan semester I.
                        </div>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 mb-2">Poin Tindak Lanjut</p>
                        <ol class="list-decimal list-inside text-sm text-gray-700 space-y-1">
                            <li>-</li>
                            <li>Melampirkan data aset tetap dan persediaan terkini</li>
                            <li>Surat balasan dikirim paling lambat 17 Juli 2026</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- PDF Preview -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                        <i class="ph ph-file-pdf text-red-500 text-lg"></i>
                        {{ $reviewFinal->drafSurat->file_draf }}
                    </h3>
                    <a href="{{ Storage::url($reviewFinal->drafSurat->file_draf) }}" target="_blank" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 font-medium rounded text-xs hover:bg-gray-50 transition-colors shadow-sm inline-flex items-center gap-1.5">
                        <i class="ph ph-arrow-square-out"></i> Buka & Unduh
                    </a>
                </div>
                <div class="p-4">
                    <div class="w-full h-[600px] bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                        <embed src="{{ Storage::url($reviewFinal->drafSurat->file_draf) }}" type="application/pdf" width="100%" height="100%" class="rounded-lg">
                    </div>
                </div>
            </div>

            <!-- Form Persetujuan -->
            @if($reviewFinal->status === 'menunggu')
            <form id="review-form" action="{{ route('kabiro.review-final.update', $reviewFinal) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                @csrf
                @method('PUT')
                
                <div class="p-5">
                    <h3 class="text-sm font-bold text-gray-900 mb-2">Catatan / Koreksi <span class="text-red-500">*</span></h3>
                    <textarea name="catatan" id="catatan" rows="4" class="w-full rounded-xl border-gray-200 focus:border-[#5c3a21] focus:ring-[#5c3a21] shadow-sm text-sm" placeholder="Tambahkan catatan atau koreksi jika diperlukan (Wajib jika dikembalikan untuk revisi)...">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="p-5 border-t border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <button type="submit" name="status" value="revisi" class="px-6 py-2.5 bg-white border border-red-500 text-red-600 font-bold rounded-lg hover:bg-red-50 transition-colors shadow-sm text-sm">
                        Kembalikan ke Kabag
                    </button>
                    <button type="submit" name="status" value="disetujui" class="px-6 py-2.5 bg-[#16a34a] text-white font-bold rounded-lg hover:bg-[#15803d] transition-colors shadow-sm text-sm">
                        Setujui Surat
                    </button>
                </div>
            </form>
            @else
            <div id="review-form" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8 p-6 text-center">
                @if($reviewFinal->status === 'disetujui')
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-100 text-green-600 mb-3">
                        <i class="ph ph-check text-2xl font-bold"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Surat Telah Disetujui</h3>
                    <p class="text-gray-500 text-sm">Anda telah menyetujui draf ini pada {{ $reviewFinal->updated_at->translatedFormat('d F Y, H:i') }}.</p>
                @else
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-red-100 text-red-600 mb-3">
                        <i class="ph ph-x text-2xl font-bold"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Surat Telah Dikembalikan</h3>
                    <p class="text-gray-500 text-sm">Anda telah mengembalikan draf ini untuk direvisi pada {{ $reviewFinal->updated_at->translatedFormat('d F Y, H:i') }}.</p>
                    @if($reviewFinal->catatan_reviu)
                        <div class="mt-4 p-4 bg-red-50 border border-red-100 rounded-lg text-left inline-block w-full max-w-lg">
                            <p class="text-xs font-bold text-red-800 mb-1">Catatan Anda:</p>
                            <p class="text-sm text-red-700">{{ $reviewFinal->catatan_reviu }}</p>
                        </div>
                    @endif
                @endif
            </div>
            @endif

        </div>

        <!-- Right Column -->
        <div class="w-full lg:w-80 flex-shrink-0 flex flex-col gap-6">
            
            <!-- Jejak Persetujuan -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-bold text-gray-900 mb-4">Jejak Persetujuan</h3>
                
                @php
                    $draf = $reviewFinal->drafSurat;
                    $kasubtimReview = \App\Models\ReviuSurat::where('draf_surat_id', $draf->id)->where('tingkat', '1')->first();
                    $kabagReview = \App\Models\ReviuSurat::where('draf_surat_id', $draf->id)->where('tingkat', '2')->first();
                @endphp
                
                <div class="relative pl-6 space-y-6">
                    <div class="absolute left-[11px] top-2 bottom-2 w-0.5 bg-gray-200"></div>
                    
                    <!-- Staf -->
                    <div class="relative">
                        <div class="absolute -left-[29px] mt-1 w-3 h-3 rounded-full bg-green-500 border-2 border-white"></div>
                        <p class="text-sm font-bold text-gray-900">Draft dibuat</p>
                        <p class="text-xs text-gray-500 mt-0.5">Staf &mdash; {{ $draf->created_at->format('d M Y') }}</p>
                    </div>
                    
                    <!-- Kasubtim -->
                    <div class="relative">
                        <div class="absolute -left-[29px] mt-1 w-3 h-3 rounded-full bg-green-500 border-2 border-white"></div>
                        <p class="text-sm font-bold text-gray-900">Disetujui Kasubtim</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $kasubtimReview ? $kasubtimReview->updated_at->format('d M Y') : '-' }}</p>
                    </div>
                    
                    <!-- Kabag -->
                    <div class="relative">
                        <div class="absolute -left-[29px] mt-1 w-3 h-3 rounded-full bg-green-500 border-2 border-white"></div>
                        <p class="text-sm font-bold text-gray-900">Disetujui Kabag</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $kabagReview ? $kabagReview->updated_at->format('d M Y') : '-' }}</p>
                    </div>
                    
                    <!-- Kabiro -->
                    <div class="relative">
                        @if($reviewFinal->status === 'menunggu')
                            <div class="absolute -left-[29px] mt-1 w-3 h-3 rounded-full bg-[#3b2c85] border-2 border-white"></div>
                            <p class="text-sm font-bold text-gray-900">Menunggu persetujuan Kepala Biro</p>
                            <p class="text-xs text-gray-400 mt-0.5">Status saat ini</p>
                        @elseif($reviewFinal->status === 'disetujui')
                            <div class="absolute -left-[29px] mt-1 w-3 h-3 rounded-full bg-green-500 border-2 border-white"></div>
                            <p class="text-sm font-bold text-gray-900">Disetujui Kepala Biro</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $reviewFinal->updated_at->format('d M Y') }}</p>
                        @else
                            <div class="absolute -left-[29px] mt-1 w-3 h-3 rounded-full bg-red-500 border-2 border-white"></div>
                            <p class="text-sm font-bold text-gray-900">Dikembalikan Kepala Biro</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $reviewFinal->updated_at->format('d M Y') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Ringkasan Persetujuan -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-bold text-gray-900 mb-4">Ringkasan Persetujuan</h3>
                
                <div class="flex flex-col gap-3 text-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-16 h-6 rounded bg-[#dcfce7] text-[#166534] flex items-center justify-center font-bold text-xs">
                            <i class="ph ph-check"></i>
                        </div>
                        <span class="text-gray-700">Kasubtim</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-16 h-6 rounded bg-[#dcfce7] text-[#166534] flex items-center justify-center font-bold text-xs">
                            <i class="ph ph-check"></i>
                        </div>
                        <span class="text-gray-700">Kabag</span>
                    </div>
                    <div class="flex items-center gap-3">
                        @if($reviewFinal->status === 'menunggu')
                            <div class="w-16 h-6 rounded bg-[#e0e7ff] text-[#3730a3] flex items-center justify-center font-bold text-[10px]">
                                Menunggu
                            </div>
                        @elseif($reviewFinal->status === 'disetujui')
                            <div class="w-16 h-6 rounded bg-[#dcfce7] text-[#166534] flex items-center justify-center font-bold text-xs">
                                <i class="ph ph-check"></i>
                            </div>
                        @else
                            <div class="w-16 h-6 rounded bg-red-100 text-red-600 flex items-center justify-center font-bold text-[10px]">
                                Dikembalikan
                            </div>
                        @endif
                        <span class="text-gray-700">Kepala Biro</span>
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
</x-app-layout>
