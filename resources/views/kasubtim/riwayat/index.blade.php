<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Riwayat Aktivitas</h2>
        <p class="text-gray-500 mt-1">Log aktivitas review draft dan penugasan disposisi</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Total Aktivitas Bulan Ini</p>
            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] }}</h3>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Draft Disetujui</p>
            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['disetujui'] }}</h3>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Draft Dikembalikan</p>
            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['dikembalikan'] }}</h3>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Disposisi Ditugaskan</p>
            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['ditugaskan'] }}</h3>
        </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="flex-1">
            <form action="{{ route('kasubtim.riwayat.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 w-full">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor draft, disposisi, atau detail..." class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#5c3a21] focus:border-[#5c3a21] shadow-sm text-sm">
                <select name="type" onchange="this.form.submit()" class="px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#5c3a21] focus:border-[#5c3a21] shadow-sm text-sm min-w-[150px]">
                    <option value="">Semua Aktivitas</option>
                    <option value="reviu" {{ request('type') == 'reviu' ? 'selected' : '' }}>Review Draft</option>
                    <option value="disposisi" {{ request('type') == 'disposisi' ? 'selected' : '' }}>Disposisi</option>
                </select>
                <select name="time" onchange="this.form.submit()" class="px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#5c3a21] focus:border-[#5c3a21] shadow-sm text-sm min-w-[150px]">
                    <option value="">Semua Waktu</option>
                    <option value="minggu_ini" {{ request('time') == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="bulan_ini" {{ request('time') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                </select>
                <button type="submit" class="hidden">Cari</button>
            </form>
        </div>
    </div>

    <!-- Activity List -->
    <div class="space-y-4">
        @forelse($activities as $act)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex flex-col sm:flex-row justify-between sm:items-start gap-4">
                    <div class="flex gap-4">
                        <div class="mt-1">
                            <div class="w-3 h-3 rounded-full bg-[#5c3a21]"></div>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-base font-bold text-gray-900">{{ $act->action }}</h3>
                                <span class="inline-flex px-2 py-1 rounded text-[10px] font-bold {{ $act->status_color }}">
                                    {{ $act->status_label }}
                                </span>
                            </div>
                            <p class="text-gray-600 text-sm mb-2">{{ $act->description }}</p>
                            <p class="text-xs font-mono text-gray-400 tracking-wider uppercase">{{ $act->ref_number }}</p>
                        </div>
                    </div>
                    <div class="text-right whitespace-nowrap">
                        <p class="text-sm text-gray-400">{{ \Carbon\Carbon::parse($act->date)->translatedFormat('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-10 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                    <i class="ph ph-clock-counter-clockwise text-3xl"></i>
                </div>
                <h3 class="text-gray-900 font-bold mb-1">Belum Ada Aktivitas</h3>
                <p class="text-gray-500 text-sm">Log riwayat penugasan dan review Anda akan muncul di sini.</p>
            </div>
        @endforelse
    </div>

    @if($activities->hasPages())
    <div class="mt-6">
        {{ $activities->links() }}
    </div>
    @endif
</x-app-layout>
