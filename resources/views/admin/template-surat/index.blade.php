<x-app-layout>
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Template Surat</h2>
            <p class="text-gray-500 mt-1">Kelola template dokumen untuk digunakan oleh staf.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Upload -->
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-900 mb-4">Unggah Template Baru</h3>
                <form action="{{ route('admin.template-surat.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Template</label>
                        <input type="text" name="nama_template" class="w-full rounded-lg border-gray-300 focus:border-[#3b2c85] focus:ring focus:ring-[#3b2c85] focus:ring-opacity-50" required placeholder="Cth: Template Surat Keluar">
                        @error('nama_template')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">File Template (DOC/DOCX/RTF)</label>
                        <input type="file" name="file_template" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#fdf2f8] file:text-[#701a35] hover:file:bg-[#fce7f3]" required>
                        @error('file_template')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-[#701a35] hover:bg-[#5b152b] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#701a35]">
                        Unggah Template
                    </button>
                </form>
            </div>
        </div>

        <!-- List Template -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900">Daftar Template Tersedia</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 bg-gray-50 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 font-medium">NAMA TEMPLATE</th>
                                <th class="px-6 py-3 font-medium">DITAMBAHKAN</th>
                                <th class="px-6 py-3 font-medium text-right">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($templates as $template)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded bg-sky-50 text-sky-600 flex items-center justify-center">
                                                <i class="ph ph-file-text text-lg"></i>
                                            </div>
                                            {{ $template->nama_template }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">{{ $template->created_at->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @if($template->file_path)
                                                <a href="{{ Storage::url($template->file_path) }}" target="_blank" class="p-2 text-sky-600 hover:bg-sky-50 rounded-lg transition-colors" title="Unduh File Lama">
                                                    <i class="ph ph-download-simple text-lg"></i>
                                                </a>
                                            @endif
                                            <form action="{{ route('admin.template-surat.destroy', $template->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus template ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                                    <i class="ph ph-trash text-lg"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="ph ph-file-dashed text-4xl mb-2 text-gray-300"></i>
                                            <p>Belum ada template surat yang diunggah.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
