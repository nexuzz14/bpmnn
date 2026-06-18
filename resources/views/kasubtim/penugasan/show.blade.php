<x-app-layout>
    <div class="mb-6">
        <a href="{{ route('kasubtim.penugasan.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 bg-white rounded shadow-sm text-sm font-medium text-gray-600 hover:bg-gray-50 mb-6 transition-colors">
            &larr; Kembali
        </a>
        <h2 class="text-2xl font-bold text-gray-900">Detail Disposisi dari Kabag</h2>
        <p class="text-gray-500 mt-1">Disposisi #{{ $penugasan->suratMasuk->nomor_surat }}</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 text-green-800 p-4 rounded-xl border border-green-100 flex items-center gap-3">
            <i class="ph ph-check-circle text-xl text-green-600"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Alert Block -->
    <div class="bg-[#fefce8] border-l-4 border-l-[#5c3a21] p-4 rounded-r-xl mb-6 flex items-center gap-4 shadow-sm">
        @php
            $isTinggi = $penugasan->suratMasuk->sifat === 'segera' || $penugasan->suratMasuk->sifat === 'sangat_segera';
        @endphp
        @if($isTinggi)
            <span class="font-bold text-red-600 text-sm">Prioritas Tinggi</span>
        @else
            <span class="font-bold text-gray-600 text-sm">Prioritas Normal</span>
        @endif
        <span class="font-bold text-[#92400e] text-sm">Deadline: {{ \Carbon\Carbon::parse($penugasan->tenggat_waktu)->translatedFormat('d M Y') }}</span>
    </div>

    <!-- Main Info -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">NOMOR DISPOSISI</p>
                <p class="text-base text-gray-900 font-medium">{{ $penugasan->suratMasuk->nomor_surat }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">DARI KABAG</p>
                <p class="text-base text-gray-900 font-medium">{{ $penugasan->pengirim ? $penugasan->pengirim->name : '-' }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">TANGGAL DITERIMA</p>
                <p class="text-base text-gray-900 font-medium">{{ \Carbon\Carbon::parse($penugasan->created_at)->translatedFormat('d M Y') }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">DEADLINE</p>
                <p class="text-base font-bold {{ $isTinggi ? 'text-red-600' : 'text-gray-900' }}">{{ \Carbon\Carbon::parse($penugasan->tenggat_waktu)->translatedFormat('d M Y') }}</p>
            </div>
        </div>

        <div class="mb-6">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">PERIHAL</p>
            <p class="text-lg font-bold text-gray-900">{{ $penugasan->suratMasuk->perihal }}</p>
        </div>

        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">INSTRUKSI DARI KABAG</p>
            <div class="bg-gray-50 p-4 rounded-lg text-sm text-gray-600 italic border border-gray-100">
                "{{ $penugasan->catatan ?? '-' }}"
            </div>
        </div>
    </div>

    <!-- Surat Terkait -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-4">Surat Masuk Terkait</h3>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="font-bold text-gray-900 mb-1">{{ $penugasan->suratMasuk->nomor_surat }}</p>
                <p class="text-sm text-gray-500">{{ $penugasan->suratMasuk->perihal }} dari {{ $penugasan->suratMasuk->asal_surat }}</p>
            </div>
            <a href="{{ asset('storage/' . $penugasan->suratMasuk->file_surat) }}" target="_blank" class="px-6 py-2 bg-white border border-[#5c3a21] text-[#5c3a21] text-sm font-medium rounded hover:bg-[#5c3a21] hover:text-white transition-colors text-center">
                Lihat PDF
            </a>
        </div>
    </div>

    @if(in_array($penugasan->status, ['menunggu', 'dibaca']))
        @if(request('action') === 'tugaskan')
        <!-- Tugas ke Staf -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 mb-4">Tugaskan Penyusunan Draft</h3>
            
            <form action="{{ route('kasubtim.penugasan.update', $penugasan->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Staf Penyusun</label>
                    <select name="staf_id" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-[#5c3a21] focus:border-[#5c3a21]" required>
                        <option value="">-- Pilih Staf --</option>
                        @foreach($stafs as $staf)
                            <option value="{{ $staf->id }}">{{ $staf->name }}</option>
                        @endforeach
                    </select>
                    @error('staf_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Catatan untuk Staf (Opsional)</label>
                    <textarea name="instruksi" rows="3" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-[#5c3a21] focus:border-[#5c3a21]" placeholder="Tambahkan catatan atau arahan khusus untuk staf...">Koordinasikan dengan tim untuk menyusun draft balasan atas {{ strtolower($penugasan->suratMasuk->perihal) }}. Pastikan data akurat dan sesuai format yang diminta.</textarea>
                    @error('instruksi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full py-3 bg-[#e5e7eb] text-gray-500 font-bold rounded-lg hover:bg-[#5c3a21] hover:text-white transition-colors" id="btnTugaskan">
                    Tugaskan ke Staf
                </button>
            </form>
        </div>
        @else
        <!-- Action Button Only -->
        <div class="flex justify-end mt-4">
            <a href="{{ route('kasubtim.penugasan.show', ['penugasan' => $penugasan->id, 'action' => 'tugaskan']) }}" class="px-6 py-2.5 bg-[#5c3a21] text-white font-medium rounded hover:bg-[#4a2e1a] transition-colors shadow-sm inline-flex items-center gap-2">
                Tugaskan Penyusunan Draft &rarr;
            </a>
        </div>
        @endif
    @else
    <!-- Sudah Ditugaskan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="ph ph-check-circle text-3xl text-green-600"></i>
        </div>
        <h3 class="font-bold text-gray-900 mb-2">Sudah Didisposisi ke Staf</h3>
        <p class="text-sm text-gray-500">Tugas ini sudah berhasil diteruskan ke staf untuk ditindaklanjuti.</p>
    </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.querySelector('select[name="staf_id"]');
            const btn = document.getElementById('btnTugaskan');
            
            if(select && btn) {
                select.addEventListener('change', function() {
                    if (this.value) {
                        btn.classList.remove('bg-[#e5e7eb]', 'text-gray-500');
                        btn.classList.add('bg-[#5c3a21]', 'text-white');
                    } else {
                        btn.classList.add('bg-[#e5e7eb]', 'text-gray-500');
                        btn.classList.remove('bg-[#5c3a21]', 'text-white');
                    }
                });
            }
        });
    </script>
</x-app-layout>
