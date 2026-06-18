<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Review Draft — {{ $review->drafSurat->suratMasuk->perihal }}</h2>
        <p class="text-gray-500 mt-1">Review dan paraf draft surat dari Staf</p>
    </div>

    <form action="{{ route('kasubtim.review.update', $review) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-24">
            <!-- Left Column: PDF, Catatan, Jejak -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- PDF Viewer -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-[600px]">
                    <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <span class="text-sm font-medium text-gray-700">{{ basename($review->drafSurat->file_draf) }}</span>
                        <a href="{{ Storage::url($review->drafSurat->file_draf) }}" target="_blank" class="px-3 py-1.5 bg-white border border-gray-200 rounded text-xs font-medium text-gray-600 hover:bg-gray-50 transition-colors shadow-sm">
                            Buka PDF
                        </a>
                    </div>
                    <div class="flex-1 bg-gray-100 p-4">
                        @if($review->drafSurat->file_draf)
                            <iframe src="{{ Storage::url($review->drafSurat->file_draf) }}" class="w-full h-full border border-gray-200 rounded bg-white" frameborder="0"></iframe>
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 bg-white border border-gray-200 rounded">
                                <p>File draft tidak ditemukan</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Catatan / Koreksi -->
                <div>
                    <h3 class="text-sm font-bold text-gray-900 mb-2">Catatan / Koreksi <span class="text-red-500">*</span></h3>
                    <textarea name="catatan" id="catatan" rows="4" class="w-full rounded-xl border-gray-200 focus:border-[#5c3a21] focus:ring-[#5c3a21] shadow-sm text-sm" placeholder="Tambahkan catatan atau koreksi jika diperlukan (Wajib jika dikembalikan untuk revisi)...">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jejak Persetujuan -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-4">Jejak Persetujuan</h3>
                    <div class="space-y-4">
                        <!-- Draft Dibuat -->
                        <div class="flex gap-3">
                            <div class="mt-1">
                                <div class="w-2.5 h-2.5 rounded-full bg-green-500 mt-1"></div>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">Draft dibuat oleh Staf</p>
                                <p class="text-xs text-gray-500">{{ $review->drafSurat->pembuat->name ?? '-' }} — {{ \Carbon\Carbon::parse($review->drafSurat->created_at)->translatedFormat('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Info Cards -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Ringkasan Surat Masuk -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Ringkasan Surat Masuk</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-500">Nomor Surat</p>
                            <p class="text-sm font-medium text-gray-900">{{ $review->drafSurat->suratMasuk->nomor_surat }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Tanggal</p>
                            <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($review->drafSurat->suratMasuk->tanggal_terima)->translatedFormat('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Pengirim</p>
                            <p class="text-sm font-medium text-gray-900">{{ $review->drafSurat->suratMasuk->asal_surat }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Klasifikasi</p>
                            <p class="text-sm font-medium text-gray-900">Surat Dinas</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Perihal</p>
                            <p class="text-sm font-medium text-gray-900">{{ $review->drafSurat->suratMasuk->perihal }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Ringkasan Isi</p>
                            <div class="mt-1 bg-gray-50 p-3 rounded text-sm text-gray-700">
                                {{ $review->drafSurat->suratMasuk->ringkasan ?? '-' }}
                            </div>
                        </div>
                        @php
                            $disposisi = $review->drafSurat->suratMasuk->disposisi()->where('ke_user_id', auth()->id())->first();
                        @endphp
                        @if($disposisi && $disposisi->catatan)
                        <div>
                            <p class="text-xs text-gray-500">Poin Tindak Lanjut</p>
                            <div class="mt-1 text-sm text-gray-700 space-y-1">
                                {!! nl2br(e($disposisi->catatan)) !!}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Info Surat -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Info Surat</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-500">Perihal</p>
                            <p class="text-sm font-medium text-gray-900">{{ $review->drafSurat->suratMasuk->perihal }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Versi</p>
                            <p class="text-sm font-medium text-gray-900">v{{ $review->drafSurat->versi ?? '1' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Diunggah Oleh</p>
                            <p class="text-sm font-medium text-gray-900">{{ $review->drafSurat->pembuat->name ?? '-' }} (Staf)</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Waktu Unggah</p>
                            <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($review->drafSurat->created_at)->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Persetujuan -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Ringkasan Persetujuan</h3>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <span class="w-16 text-[10px] font-bold px-2 py-1 rounded bg-[#fefce8] text-[#92400e] text-center">Menunggu</span>
                            <span class="text-sm text-gray-700">Kasubtim</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-16 text-[10px] font-bold px-2 py-1 rounded bg-gray-100 text-gray-500 text-center">Belum</span>
                            <span class="text-sm text-gray-700">Kabag</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-16 text-[10px] font-bold px-2 py-1 rounded bg-gray-100 text-gray-500 text-center">Belum</span>
                            <span class="text-sm text-gray-700">Kepala Biro</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Sticky Bottom Bar for Actions -->
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-40 lg:pl-64">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <!-- Kembalikan untuk Revisi -->
                <button type="submit" name="status" value="revisi" class="px-6 py-2.5 bg-white border border-red-500 text-red-500 font-bold rounded hover:bg-red-50 transition-colors shadow-sm">
                    Kembalikan untuk Revisi
                </button>

                <!-- Setujui & Teruskan -->
                <button type="submit" name="status" value="disetujui" class="px-6 py-2.5 bg-[#5c3a21] text-white font-bold rounded hover:bg-[#4a2e1a] transition-colors shadow-sm">
                    Setujui & Teruskan ke Kabag
                </button>
            </div>
        </div>
    </form>
</x-app-layout>
