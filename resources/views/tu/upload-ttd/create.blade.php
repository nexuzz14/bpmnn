<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Upload Dokumen TTD</h2>
        <p class="text-gray-500 mt-1">Upload file fisik/digital yang telah ditandatangani untuk didistribusikan</p>
    </div>

    <!-- Banner -->
    <div class="bg-[#dcfce7] rounded-xl mb-6 p-4 border border-green-200 flex flex-col md:flex-row justify-between items-start md:items-center relative overflow-hidden">
        <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#166534]"></div>
        <div class="pl-2">
            <h3 class="text-[#166534] font-bold">Nomor Draft: DRAFT-{{ str_pad($drafSurat->id, 3, '0', STR_PAD_LEFT) }}/{{ date('Y') }}</h3>
            <p class="text-[#166534] text-sm mt-1">Perihal: {{ $drafSurat->suratMasuk->perihal ?? 'Surat Keluar' }}</p>
            <p class="text-[#166534] text-sm">Telah disetujui Kepala Biro pada {{ $drafSurat->updated_at->format('d M Y') }}</p>
        </div>
        <div class="mt-4 md:mt-0">
            <span class="inline-flex px-3 py-1 bg-transparent text-[#166534] font-bold text-sm">Siap Upload</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Form Upload -->
        <div class="md:col-span-2 space-y-6">
            <!-- Draft Preview Box -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden p-6">
                <div class="flex justify-between items-center mb-4">
                    <p class="text-gray-900 font-medium">Draft: {{ basename($drafSurat->file_draf) }}</p>
                    <a href="{{ Storage::url($drafSurat->file_draf) }}" target="_blank" class="px-4 py-1.5 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">
                        Buka
                    </a>
                </div>
                <div class="w-full h-80 bg-gray-50 border border-gray-200 rounded flex items-center justify-center">
                    <p class="text-gray-400">Pratinjau Draft PDF</p>
                </div>
            </div>

            <!-- Upload Area -->
            <div>
                <p class="text-gray-700 font-medium mb-3">Unggah surat yang sudah ditandatangani Kepala Biro (PDF scan)</p>
                
                <form action="{{ route('tu.upload-ttd.store', $drafSurat->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div class="mt-1 flex justify-center px-6 py-10 border-2 border-gray-300 border-dashed rounded-xl hover:border-gray-400 transition-colors bg-white relative">
                        <div class="space-y-3 text-center">
                            <p class="text-gray-500 text-sm">Seret & lepas file PDF yang sudah ditandatangani</p>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="file_final" class="relative cursor-pointer bg-white border border-[#0ea5e9] rounded px-4 py-2 font-medium text-[#0ea5e9] hover:bg-blue-50 transition-colors shadow-sm">
                                    <span>Pilih File</span>
                                    <input id="file_final" name="file_final" type="file" class="sr-only" accept="application/pdf" required>
                                </label>
                            </div>
                            <p class="text-xs text-gray-400">PDF scan maks. 10 MB</p>
                            <p class="text-sm text-gray-700 font-medium mt-2" id="file-name-display"></p>
                        </div>
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('file_final')" />


                    <div class="grid grid-cols-2 gap-4 mt-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat Resmi</label>
                            <input type="text" name="nomor_surat_final" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-[#0ea5e9] focus:border-[#0ea5e9] text-gray-500" value="{{ old('nomor_surat_final', 'B-089/SJ/KU.03.2/07/2025') }}">
                            <x-input-error class="mt-2" :messages="$errors->get('nomor_surat_final')" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Surat</label>
                            <input type="date" name="tanggal_surat" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-[#0ea5e9] focus:border-[#0ea5e9] text-gray-500" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row justify-between items-center pt-6 gap-4">
                        <p class="text-sm text-gray-500">Pastikan file PDF yang diunggah adalah surat yang sudah ditandatangani</p>
                        <div class="flex gap-3">
                            <a href="{{ route('tu.upload-ttd.index') }}" class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                Tunda
                            </a>
                            <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-[#9ca3af] rounded-lg cursor-not-allowed transition-colors" id="btn-submit" disabled>
                                Konfirmasi Upload & Lanjut Distribusi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Jejak Persetujuan -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden p-6">
                <h3 class="font-semibold text-gray-900 mb-6">Jejak Persetujuan</h3>
                
                <div class="relative pl-6 space-y-6 before:absolute before:inset-0 before:ml-2 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-300 before:to-transparent">
                    <div class="relative">
                        <div class="absolute -left-6 bg-[#16a34a] w-2 h-2 rounded-full ring-4 ring-white mt-1.5"></div>
                        <p class="text-sm font-bold text-gray-900">Draft dibuat</p>
                        <p class="text-sm text-gray-500">Staf — {{ $drafSurat->created_at->format('d M Y') }}</p>
                    </div>
                    
                    @foreach($drafSurat->reviuSurat as $reviu)
                        <div class="relative">
                            <div class="absolute -left-6 bg-[#16a34a] w-2 h-2 rounded-full ring-4 ring-white mt-1.5"></div>
                            <p class="text-sm font-bold text-gray-900">
                                @if($reviu->tingkat == '1') Disetujui Kasubtim
                                @elseif($reviu->tingkat == '2') Disetujui Kabag
                                @elseif($reviu->tingkat == 'final') Disetujui Kepala Biro
                                @endif
                            </p>
                            <p class="text-sm text-gray-500">{{ $reviu->updated_at->format('d M Y') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('file_final').addEventListener('change', function(e) {
            var fileName = e.target.files[0] ? e.target.files[0].name : '';
            document.getElementById('file-name-display').textContent = fileName;
            
            var btn = document.getElementById('btn-submit');
            if (fileName) {
                btn.disabled = false;
                btn.classList.remove('bg-[#9ca3af]', 'cursor-not-allowed');
                btn.classList.add('bg-[#0ea5e9]', 'hover:bg-[#0284c7]');
            } else {
                btn.disabled = true;
                btn.classList.add('bg-[#9ca3af]', 'cursor-not-allowed');
                btn.classList.remove('bg-[#0ea5e9]', 'hover:bg-[#0284c7]');
            }
        });


    </script>
    @endpush
</x-app-layout>
