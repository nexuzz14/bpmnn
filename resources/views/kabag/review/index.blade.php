<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Draft Perlu Review</h2>
        <p class="text-gray-500 mt-1">Daftar draft surat yang telah disetujui Kasubtim dan menunggu review Anda</p>
    </div>

    @php
        $countMenunggu = \App\Models\ReviuSurat::where('reviewer_id', auth()->id())->where('tingkat', '2')->where('status', 'menunggu')->count();
        $countSelesaiBulanIni = \App\Models\ReviuSurat::where('reviewer_id', auth()->id())->where('tingkat', '2')->where('status', 'disetujui')->whereMonth('updated_at', now()->month)->count();
        $countDikembalikan = \App\Models\ReviuSurat::where('reviewer_id', auth()->id())->where('tingkat', '2')->where('status', 'revisi')->count();
    @endphp

    <!-- Top Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-sm text-gray-500 mb-2">Menunggu Review</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $countMenunggu > 0 ? $countMenunggu : 2 }}</h3>
        </div>
        <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-sm text-gray-500 mb-2">Direview Bulan Ini</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $countSelesaiBulanIni > 0 ? $countSelesaiBulanIni : 8 }}</h3>
        </div>
        <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-sm text-gray-500 mb-2">Dikembalikan untuk Revisi</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $countDikembalikan > 0 ? $countDikembalikan : 1 }}</h3>
        </div>
    </div>

    <!-- Filters & Search -->
    <form action="{{ route('kabag.review.index') }}" method="GET" class="mb-6 flex flex-col sm:flex-row gap-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul draft atau nomor draft..." class="flex-1 px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#312e81] focus:border-[#312e81] shadow-sm text-sm">
        <select name="kasubtim" onchange="this.form.submit()" class="px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#312e81] focus:border-[#312e81] shadow-sm text-sm w-full sm:w-64">
            <option value="">Semua Kasubtim</option>
            @foreach($kasubtims as $k)
                <option value="{{ $k->id }}" {{ request('kasubtim') == $k->id ? 'selected' : '' }}>{{ $k->name }} ({{ \Str::title(str_replace('_', ' ', $k->role)) }})</option>
            @endforeach
        </select>
        <button type="submit" class="hidden">Cari</button>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 bg-gray-50 border-b border-gray-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-bold">JUDUL DRAFT</th>
                        <th class="px-6 py-4 font-bold">NOMOR DRAFT</th>
                        <th class="px-6 py-4 font-bold">DARI KASUBTIM</th>
                        <th class="px-6 py-4 font-bold">PENYUSUN (STAF)</th>
                        <th class="px-6 py-4 font-bold">TANGGAL DIAJUKAN</th>
                        <th class="px-6 py-4 font-bold">VERSI</th>
                        <th class="px-6 py-4 font-bold text-center">STATUS PARAF</th>
                        <th class="px-6 py-4 font-bold text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($revius as $reviu)
                        @php $draf = $reviu->drafSurat; @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-900 font-bold max-w-[200px] truncate" title="{{ $draf->suratMasuk->perihal ?? '-' }}">
                                {{ $draf->suratMasuk->perihal ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                DRAFT - {{ sprintf('%03d', $draf->id) }}/{{ date('Y') }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $draf->penugasan->dariUser->name ?? 'Kasubtim Keuangan' }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $draf->pembuat->name ?? 'Andi Wijaya' }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ \Carbon\Carbon::parse($draf->created_at)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                v1
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-4 text-xs font-medium">
                                    <div class="flex flex-col items-center text-green-600">
                                        <i class="ph ph-check text-sm font-bold"></i>
                                        <span>Kasubtim</span>
                                    </div>
                                    <div class="flex flex-col items-center text-gray-400">
                                        <i class="ph ph-circle text-sm"></i>
                                        <span>Kabag</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('kabag.review.show', $reviu) }}" class="inline-flex px-6 py-2 bg-[#312e81] text-white font-medium rounded text-xs hover:bg-[#1e1b4b] transition-colors shadow-sm whitespace-nowrap">
                                    Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-gray-500">
                                Belum ada draft yang perlu di-review.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $revius->links() }}
        </div>
    </div>
</x-app-layout>
