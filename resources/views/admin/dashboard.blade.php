<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Selamat Datang, {{ auth()->user()->name }}</h2>
        <p class="text-gray-500 mt-1">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }} &mdash; Ringkasan aktivitas persuratan hari ini</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ \App\Models\SuratMasuk::whereDate('created_at', today())->count() }}</h3>
            <p class="text-sm text-gray-500">Surat Masuk Hari Ini</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ \App\Models\SuratMasuk::where('status', 'diterima')->count() }}</h3>
            <p class="text-sm text-gray-500">Menunggu Diproses TU</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ \App\Models\SuratMasuk::whereMonth('created_at', now()->month)->count() }}</h3>
            <p class="text-sm text-gray-500">Surat Bulan Ini</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ \App\Models\SuratFinal::count() }}</h3>
            <p class="text-sm text-gray-500">Surat Keluar</p>
        </div>
    </div>

    <!-- Latest Surat Masuk Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900">Surat Masuk Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 bg-gray-50 border-b border-gray-100 uppercase">
                    <tr>
                        <th class="px-6 py-4 font-medium">No. Agenda</th>
                        <th class="px-6 py-4 font-medium">Perihal</th>
                        <th class="px-6 py-4 font-medium">Pengirim</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach(\App\Models\SuratMasuk::latest()->take(5)->get() as $surat)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $surat->nomor_agenda ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $surat->perihal }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $surat->pengirim }}</td>
                        <td class="px-6 py-4">
                            @php
                                $statusClasses = match($surat->status) {
                                    'diterima' => 'bg-[#fef3c7] text-[#92400e]',
                                    'diproses' => 'bg-[#dbeafe] text-[#1e40af]',
                                    'selesai' => 'bg-[#dcfce7] text-[#166534]',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                                
                                $statusLabel = match($surat->status) {
                                    'diterima' => 'Belum Diproses TU',
                                    'diproses' => 'Diproses',
                                    'selesai' => 'Selesai',
                                    default => ucfirst($surat->status)
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-bold {{ $statusClasses }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-3">
        <a href="{{ route('admin.surat-masuk.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-[#055a40] text-white rounded-lg hover:bg-[#044733] transition-colors font-bold text-sm">
            + Input Surat Masuk
        </a>
        <a href="{{ route('admin.surat-masuk.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors font-medium text-sm shadow-sm">
            Lihat Semua
        </a>
    </div>
</x-app-layout>
