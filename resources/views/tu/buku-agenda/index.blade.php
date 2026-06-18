<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Buku Agenda TU Biro</h2>
        <p class="text-gray-500 mt-1">Catatan agenda surat masuk yang telah diproses TU Biro</p>
    </div>

    <!-- Filters & Search -->
    <form method="GET" action="{{ route('tu.buku-agenda.index') }}" class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor agenda atau perihal..." class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#0284c7] focus:border-[#0284c7] shadow-sm text-sm">
        </div>
        <div class="flex gap-4">
            <select name="tahun" onchange="this.form.submit()" class="py-2 pl-4 pr-10 bg-white border border-gray-200 rounded-lg focus:ring-[#0284c7] focus:border-[#0284c7] shadow-sm text-sm text-gray-700">
                <option value="">Semua Tahun</option>
                <option value="2026" {{ request('tahun') == '2026' ? 'selected' : '' }}>2026</option>
                <option value="2025" {{ request('tahun') == '2025' ? 'selected' : '' }}>2025</option>
                <option value="2024" {{ request('tahun') == date('Y') ? 'selected' : '' }}>2024</option>
            </select>
            <select name="bulan" onchange="this.form.submit()" class="py-2 pl-4 pr-10 bg-white border border-gray-200 rounded-lg focus:ring-[#0284c7] focus:border-[#0284c7] shadow-sm text-sm text-gray-700">
                <option value="">Semua Bulan</option>
                @foreach(range(1, 12) as $m)
                    <option value="{{ sprintf('%02d', $m) }}" {{ request('bulan') == sprintf('%02d', $m) ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endforeach
            </select>
            <button type="submit" class="hidden">Cari</button>
            <a href="{{ route('tu.buku-agenda.export') }}" class="px-6 py-2 bg-white text-[#0284c7] border border-[#0284c7] font-medium rounded-lg hover:bg-sky-50 transition-colors shadow-sm whitespace-nowrap text-sm">
                Unduh Agenda (Excel)
            </a>
        </div>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-[11px] text-gray-500 bg-gray-50 border-b border-gray-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">NO. AGENDA TU</th>
                        <th class="px-6 py-4 font-medium">NOMOR SURAT</th>
                        <th class="px-6 py-4 font-medium">TANGGAL TERIMA</th>
                        <th class="px-6 py-4 font-medium">PENGIRIM</th>
                        <th class="px-6 py-4 font-medium">PERIHAL</th>
                        <th class="px-6 py-4 font-medium">DISPOSISI KE</th>
                        <th class="px-6 py-4 font-medium">TGL DISPOSISI</th>
                        <th class="px-6 py-4 font-medium text-center">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($agendas as $index => $agenda)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $agenda->nomor_agenda ?? '#' . date('Y', strtotime($agenda->created_at)) . '/' . $agenda->id }}</td>
                        <td class="px-6 py-4 text-gray-900 whitespace-nowrap">{{ $agenda->nomor_surat }}</td>
                        <td class="px-6 py-4 text-gray-600 whitespace-nowrap">{{ \Carbon\Carbon::parse($agenda->tanggal_terima ?? $agenda->created_at)->translatedFormat('d M Y') }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $agenda->asal_surat }}</td>
                        <td class="px-6 py-4 text-gray-800 min-w-[200px]">{{ $agenda->perihal }}</td>
                        <td class="px-6 py-4 text-gray-600">
                            @if($agenda->disposisi->count() > 0)
                                {{ $agenda->disposisi->first()->keUser->name ?? '-' }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                            @if($agenda->disposisi->count() > 0 && $agenda->disposisi->first()->created_at)
                                {{ \Carbon\Carbon::parse($agenda->disposisi->first()->created_at)->translatedFormat('d M Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusColor = match($agenda->status) {
                                    'selesai' => 'bg-green-100 text-green-800',
                                    'diproses' => 'bg-blue-100 text-blue-800',
                                    'menunggu_reviu', 'revisi', 'menunggu_ttd' => 'bg-yellow-100 text-yellow-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full {{ $statusColor }} capitalize">
                                {{ str_replace('_', ' ', $agenda->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-10 text-center text-gray-500">Belum ada agenda surat masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col md:flex-row items-center justify-between gap-4">
            <span class="text-sm text-gray-600">Total: <strong>{{ $agendas->total() }}</strong> surat tercatat di agenda</span>
            <div class="w-full md:w-auto overflow-x-auto">
                {{ $agendas->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
