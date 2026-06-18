<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-display font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Daftar Surat Masuk') }}
            </h2>
            <a href="{{ route('admin.surat-masuk.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-role-admin text-white rounded-lg hover:bg-role-admin/90 transition-all font-medium text-sm shadow-sm">
                <i class="ph ph-plus-circle text-lg"></i>
                Input Surat Masuk
            </a>
        </div>
    </x-slot>

    <form action="{{ route('admin.surat-masuk.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="flex-1">
            <div class="relative">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor atau perihal surat..." class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#055a40] focus:border-[#055a40] shadow-sm">
            </div>
        </div>
        <div class="flex gap-4">
            <select name="status" onchange="this.form.submit()" class="py-2 pl-4 pr-10 bg-white border border-gray-200 rounded-lg focus:ring-[#055a40] focus:border-[#055a40] shadow-sm text-gray-700">
                <option value="">Semua Status</option>
                <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="menunggu_reviu" {{ request('status') == 'menunggu_reviu' ? 'selected' : '' }}>Menunggu Reviu</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
            <button type="submit" class="hidden">Filter</button>
        </div>
    </form>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if(session('success'))
            <div class="bg-green-50 text-green-800 p-4 border-b border-green-100 flex items-center gap-2">
                <i class="ph ph-check-circle text-xl"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/50 text-gray-500 font-medium border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-medium">NO. AGENDA</th>
                        <th class="px-6 py-4 font-medium">NOMOR SURAT</th>
                        <th class="px-6 py-4 font-medium">PERIHAL</th>
                        <th class="px-6 py-4 font-medium">PENGIRIM</th>
                        <th class="px-6 py-4 font-medium">TANGGAL TERIMA</th>
                        <th class="px-6 py-4 font-medium">STATUS</th>
                        <th class="px-6 py-4 font-medium text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($suratMasuks as $surat)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                                {{ $surat->nomor_agenda ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-900 font-medium">{{ $surat->nomor_surat }}</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900 min-w-[200px]">
                                {{ $surat->perihal }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $surat->asal_surat }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($surat->tanggal_terima ?? $surat->created_at)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColor = match($surat->status) {
                                        'diterima' => 'bg-[#fef3c7] text-[#b45309]',
                                        'diproses' => 'bg-[#e0e7ff] text-[#3730a3]',
                                        'menunggu_reviu' => 'bg-indigo-100 text-indigo-800',
                                        'revisi' => 'bg-red-100 text-red-800',
                                        'selesai' => 'bg-[#dcfce7] text-[#166534]',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                    $statusText = match($surat->status) {
                                        'diterima' => 'Belum Diproses TU',
                                        'diproses' => 'Diproses',
                                        'menunggu_reviu' => 'Menunggu Review',
                                        'selesai' => 'Selesai',
                                        default => ucfirst($surat->status),
                                    };
                                @endphp
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-bold {{ $statusColor }} {{ $surat->status == 'diterima' ? 'flex-col text-center' : '' }}">
                                    @if($surat->status == 'diterima')
                                        Belum<br>Diproses<br>TU
                                    @else
                                        {{ $statusText }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.surat-masuk.show', $surat) }}" class="inline-block px-3 py-1.5 border border-gray-300 text-gray-700 text-xs font-medium rounded hover:bg-gray-50 transition-colors shadow-sm text-center">
                                        Lihat<br>Detail
                                    </a>
                                    <form action="{{ route('admin.surat-masuk.destroy', $surat->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus surat masuk ini? Seluruh data yang terkait akan ikut terhapus!');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex p-2 border border-red-200 text-red-600 bg-red-50 hover:bg-red-100 rounded transition-colors shadow-sm h-full items-center justify-center" title="Hapus Surat" style="min-height: 42px;">
                                            <i class="ph ph-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="ph ph-envelope-simple text-4xl text-gray-300"></i>
                                    <p>Belum ada data surat masuk.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $suratMasuks->links() }}
        </div>
    </div>
</x-app-layout>
