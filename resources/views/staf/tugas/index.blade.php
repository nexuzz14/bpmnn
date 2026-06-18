<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Tugas Saya</h2>
        <p class="text-gray-500 mt-1">Daftar tugas yang diberikan oleh Kasubtim</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Tugas Aktif</p>
            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['tugas_aktif'] }}</h3>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Sedang Dikerjakan</p>
            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['sedang_dikerjakan'] }}</h3>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Menunggu Review</p>
            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['menunggu_review'] }}</h3>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Selesai Bulan Ini</p>
            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['selesai_bulan_ini'] }}</h3>
        </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="flex-1">
            <form action="{{ route('staf.tugas.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 w-full">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul tugas..." class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#701a35] focus:border-[#701a35] shadow-sm text-sm">
                <select name="status" onchange="this.form.submit()" class="px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#701a35] focus:border-[#701a35] shadow-sm text-sm min-w-[150px]">
                    <option value="Semua Status" {{ request('status') == 'Semua Status' ? 'selected' : '' }}>Semua Status</option>
                    <option value="Tugas Baru" {{ request('status') == 'Tugas Baru' ? 'selected' : '' }}>Tugas Baru</option>
                    <option value="Sedang Dikerjakan" {{ request('status') == 'Sedang Dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                    <option value="Menunggu Review" {{ request('status') == 'Menunggu Review' ? 'selected' : '' }}>Menunggu Review</option>
                </select>
                <button type="submit" class="hidden">Cari</button>
            </form>
        </div>
    </div>

    <!-- Tugas List -->
    <div class="space-y-4">
        @forelse($tugas as $index => $t)
            @php
                $latestDraft = $t->suratMasuk->drafSurat->first();
                $isWaitingReview = $latestDraft && $latestDraft->status == 'menunggu_reviu';
                $isRevisi = $latestDraft && $latestDraft->reviuSurat->first() && $latestDraft->reviuSurat->first()->status == 'revisi';
                
                // Determine styling based on mockup
                // Sedang Dikerjakan has red border, Menunggu Review doesn't.
                $borderClass = ($t->status == 'menunggu' || $t->status == 'diproses' || $isRevisi) ? 'border-l-4 border-l-[#dc2626]' : '';
                
                // Progress berdasarkan status tugas
                if ($isWaitingReview || $t->status == 'selesai') {
                    $progressPercent = 100;
                } elseif ($t->status == 'diproses' || $isRevisi || $t->status == 'ditindaklanjuti') {
                    $progressPercent = 60;
                } else {
                    // Status menunggu / dibaca = sudah ditugaskan, minimal 20%
                    $progressPercent = 20;
                }
            @endphp
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 {{ $borderClass }}">
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-sm text-gray-400 font-medium">Tugas #{{ $tugas->firstItem() + $index }}</span>
                    
                    @if($isWaitingReview)
                        <span class="inline-flex px-2 py-1 rounded bg-[#dbeafe] text-[#1e40af] text-[10px] font-bold">Menunggu Review</span>
                    @elseif($isRevisi)
                        <span class="inline-flex px-2 py-1 rounded bg-[#fef2f2] text-[#dc2626] text-[10px] font-bold">Revisi</span>
                    @elseif($t->status == 'menunggu')
                        <span class="inline-flex px-2 py-1 rounded bg-[#fefce8] text-[#92400e] text-[10px] font-bold">Tugas Baru</span>
                    @else
                        <span class="inline-flex px-2 py-1 rounded bg-[#fefce8] text-[#92400e] text-[10px] font-bold">Sedang Dikerjakan</span>
                    @endif
                    
                    @if(\Carbon\Carbon::parse($t->tenggat_waktu)->diffInDays(now()) <= 1 && !$isWaitingReview)
                        <span class="inline-flex px-2 py-1 rounded bg-[#fee2e2] text-[#b91c1c] text-[10px] font-bold">Prioritas Tinggi</span>
                    @endif
                </div>
                
                <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $t->suratMasuk->perihal }}</h3>
                <p class="text-gray-500 text-sm mb-4">Dari: {{ $t->pengirim->name ?? 'Kasubtim' }} ({{ \Str::title(str_replace('_', ' ', $t->pengirim->role ?? 'Kasubtim')) }})</p>
                
                <div class="bg-gray-50 rounded-lg p-4 italic text-sm text-gray-600 mb-6">
                    @if($t->catatan)
                        "{{ $t->catatan }}"
                    @else
                        <span class="text-gray-400 italic">Tidak ada catatan dari Kasubtim.</span>
                    @endif
                </div>
                
                <!-- Progress Bar -->
                <div class="mb-6">
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-500 font-medium">Progress</span>
                        <span class="text-gray-900 font-bold">{{ $progressPercent }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-[#701a35] h-2 rounded-full" style="width: {{ $progressPercent }}%"></div>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="flex gap-4">
                        <p class="text-sm font-medium text-gray-500">Diterima: {{ \Carbon\Carbon::parse($t->created_at)->translatedFormat('d M Y') }}</p>
                        <p class="text-sm font-bold text-[#dc2626]">Deadline: {{ \Carbon\Carbon::parse($t->tenggat_waktu)->translatedFormat('d M Y') }}</p>
                    </div>
                    
                    @if($isWaitingReview)
                        <a href="{{ route('staf.draf-surat.show', $latestDraft->id) }}" class="inline-flex px-6 py-2 bg-[#701a35] text-white font-bold rounded-lg text-sm hover:bg-[#5b152b] transition-colors shadow-sm w-full sm:w-auto justify-center">
                            Lihat Detail
                        </a>
                    @elseif($isRevisi)
                        <a href="{{ route('staf.draf-surat.edit', $latestDraft->id) }}" class="inline-flex px-6 py-2 bg-[#701a35] text-white font-bold rounded-lg text-sm hover:bg-[#5b152b] transition-colors shadow-sm w-full sm:w-auto justify-center">
                            Revisi Draft
                        </a>
                    @else
                        <a href="{{ route('staf.draf-surat.create', ['surat_masuk_id' => $t->suratMasuk->id, 'disposisi_id' => $t->id]) }}" class="inline-flex px-6 py-2 bg-[#701a35] text-white font-bold rounded-lg text-sm hover:bg-[#5b152b] transition-colors shadow-sm w-full sm:w-auto justify-center">
                            {{ $t->status == 'menunggu' ? 'Mulai Draf' : 'Lanjutkan' }}
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-10 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                    <i class="ph ph-check-circle text-3xl"></i>
                </div>
                <h3 class="text-gray-900 font-bold mb-1">Belum Ada Tugas</h3>
                <p class="text-gray-500 text-sm">Anda belum memiliki tugas disposisi untuk dikerjakan.</p>
            </div>
        @endforelse
    </div>

    @if($tugas->hasPages())
    <div class="mt-6">
        {{ $tugas->links() }}
    </div>
    @endif
</x-app-layout>
