<x-app-layout>
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('kabag.review.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-lg flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors shadow-sm">
                <i class="ph ph-arrow-left text-lg"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Review Draft &mdash; {{ $review->drafSurat->suratMasuk->perihal ?? '-' }}</h2>
                <p class="text-gray-500 mt-1">Review dan paraf draft surat dari Kasubtim</p>
            </div>
        </div>
    </div>

    <form action="{{ route('kabag.review.update', $review) }}" method="POST" id="form-review">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" id="status_input" value="">
        
        <div class="flex flex-col lg:flex-row gap-6 mb-24">
            <!-- Kolom Kiri (PDF & Komentar) -->
            <div class="flex-1 space-y-6">
                <!-- PDF Viewer -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 flex flex-col h-[600px] overflow-hidden">
                    <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-white">
                        <span class="text-sm font-bold text-gray-900">{{ $review->drafSurat->file_draf ? basename($review->drafSurat->file_draf) : 'File Draf PDF' }}</span>
                        <a href="{{ $review->drafSurat->file_draf ? Storage::url($review->drafSurat->file_draf) : '#' }}" target="_blank" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded text-xs hover:bg-gray-50 transition-colors shadow-sm">
                            Buka & Unduh PDF
                        </a>
                    </div>
                    @if($review->drafSurat->file_draf)
                        <iframe src="{{ Storage::url($review->drafSurat->file_draf) }}" class="w-full flex-1 bg-gray-100"></iframe>
                    @else
                        <div class="flex-1 bg-gray-50 flex items-center justify-center text-gray-400">
                            Pratinjau PDF
                        </div>
                    @endif
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
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <h3 class="text-sm font-bold text-gray-700 mb-4">Jejak Persetujuan</h3>
                    
                    <div class="relative pl-6 space-y-6 before:content-[''] before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-px before:bg-gray-200">
                        <!-- Step 1 -->
                        <div class="relative">
                            <div class="absolute -left-[27px] top-1 w-3 h-3 rounded-full bg-green-500 ring-4 ring-white"></div>
                            <h4 class="text-sm font-bold text-gray-900">Draft dibuat</h4>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $review->drafSurat->pembuat->name ?? '-' }} &mdash; {{ \Carbon\Carbon::parse($review->drafSurat->created_at)->translatedFormat('d M Y') }}</p>
                        </div>
                        <!-- Step 2 -->
                        <div class="relative">
                            <div class="absolute -left-[27px] top-1 w-3 h-3 rounded-full bg-green-500 ring-4 ring-white"></div>
                            <h4 class="text-sm font-bold text-gray-900">Disetujui Kasubtim</h4>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $review->drafSurat->penugasan->dariUser->name ?? '-' }} &mdash; {{ \Carbon\Carbon::parse($review->created_at)->translatedFormat('d M Y') }}</p>
                        </div>
                        <!-- Step 3 -->
                        <div class="relative">
                            <div class="absolute -left-[27px] top-1 w-3 h-3 rounded-full bg-[#312e81] ring-4 ring-white"></div>
                            <h4 class="text-sm font-bold text-gray-900">Menunggu review Kabag</h4>
                            <p class="text-xs text-gray-400 mt-0.5">Status saat ini</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan (Meta Surat) -->
            <div class="lg:w-[400px] space-y-6">
                <!-- Ringkasan Surat Masuk -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4">Ringkasan Surat Masuk</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Nomor Surat</p>
                            <p class="text-sm text-gray-900">{{ $review->drafSurat->suratMasuk->nomor_surat ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Tanggal</p>
                            <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($review->drafSurat->suratMasuk->tanggal_surat ?? now())->translatedFormat('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Pengirim</p>
                            <p class="text-sm text-gray-900">{{ $review->drafSurat->suratMasuk->asal_surat ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Klasifikasi</p>
                            <p class="text-sm text-gray-900">{{ $review->drafSurat->suratMasuk->klasifikasi ?? 'Surat Dinas' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Perihal</p>
                            <p class="text-sm text-gray-900">{{ $review->drafSurat->suratMasuk->perihal ?? '-' }}</p>
                        </div>
                        
                        <div class="pt-1">
                            <p class="text-xs text-gray-500 mb-1.5">Ringkasan Isi</p>
                            <div class="bg-gray-50 p-3 rounded border border-gray-100 text-xs text-gray-700">
                                {{ $review->drafSurat->suratMasuk->keterangan ?? 'Tidak ada ringkasan.' }}
                            </div>
                        </div>
                        
                        <div class="pt-1">
                            <p class="text-xs text-gray-500 mb-1.5">Instruksi TU</p>
                            <div class="text-xs text-gray-900 space-y-1">
                                <p>{{ $review->drafSurat->suratMasuk->disposisi->first()->instruksi ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Surat -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4">Info Surat</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Perihal</p>
                            <p class="text-sm text-gray-900">{{ $review->drafSurat->suratMasuk->perihal ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Versi</p>
                            <p class="text-sm text-gray-900 font-medium">v1</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Diunggah Oleh</p>
                            <p class="text-sm text-gray-900">{{ $review->drafSurat->pembuat->name ?? '-' }} (Staf)</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Disetujui Kasubtim</p>
                            <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($review->created_at)->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Persetujuan -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4">Ringkasan Persetujuan</h3>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-bold rounded flex items-center gap-1 w-[85px] justify-center">
                                Disetujui <i class="ph ph-check text-[10px]"></i>
                            </div>
                            <span class="text-sm text-gray-600">Kasubtim</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="px-2 py-0.5 bg-purple-100 text-purple-700 text-xs font-bold rounded w-[85px] text-center">
                                Menunggu
                            </div>
                            <span class="text-sm text-gray-900 font-medium">Kabag</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="px-2 py-0.5 bg-gray-100 text-gray-500 text-xs font-bold rounded w-[85px] text-center">
                                Belum
                            </div>
                            <span class="text-sm text-gray-600">Kepala Biro</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Footer Actions -->
        <div class="fixed bottom-0 left-0 right-0 lg:left-64 bg-white border-t border-gray-200 p-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-50">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <button type="button" onclick="submitRevisi()" class="px-6 py-2.5 bg-white border border-red-500 text-red-600 font-medium rounded hover:bg-red-50 transition-colors">
                    Kembalikan untuk Revisi
                </button>
                <button type="button" onclick="submitSetuju()" class="px-6 py-2.5 bg-[#312e81] text-white font-medium rounded hover:bg-[#1e1b4b] transition-colors shadow-sm flex items-center gap-2">
                    Setujui & Teruskan ke Kepala Biro
                </button>
            </div>
        </div>
    </form>

    <script>
        function submitRevisi() {
            if(!document.getElementById('catatan').value) {
                alert('Silakan isi catatan/koreksi sebelum mengembalikan draf!');
                document.getElementById('catatan').focus();
                return;
            }
            if(confirm('Anda yakin ingin mengembalikan draf ini untuk direvisi?')) {
                document.getElementById('status_input').value = 'revisi';
                document.getElementById('form-review').submit();
            }
        }
        function submitSetuju() {
            if(confirm('Anda yakin ingin menyetujui draf ini dan meneruskan ke Kepala Biro?')) {
                document.getElementById('status_input').value = 'disetujui';
                document.getElementById('form-review').submit();
            }
        }
    </script>
</x-app-layout>
