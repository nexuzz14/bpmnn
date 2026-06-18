<x-app-layout>
    <div class="mb-6">
        <a href="{{ route('staf.tugas.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 mb-4">
            <i class="ph ph-arrow-left"></i> Kembali
        </a>
        <h2 class="text-2xl font-bold text-gray-900">Buat Draft Surat</h2>
        <p class="text-gray-500 mt-1">Unggah draft surat untuk direview oleh Kabag</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 text-red-800 p-4 rounded-xl border border-red-200 flex items-start gap-3 mb-6">
            <i class="ph ph-warning-circle text-xl mt-0.5 text-red-600"></i>
            <div>
                <h4 class="text-sm font-bold mb-1">Periksa kembali form</h4>
                <ul class="text-xs list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Pilih Template & Editor -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="ph ph-file-text text-[#701a35] text-lg"></i>
                Pilih Template & Tulis Surat
            </h3>
        </div>
        <div class="p-6">
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">Gunakan Template</label>
                <select id="templateSelector" name="template_id" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#701a35] focus:border-[#701a35] shadow-sm text-sm">
                    <option value="">-- Pilih Template Surat --</option>
                    @foreach($templates as $template)
                        <option value="{{ $template->id }}" {{ request('template') == $template->id ? 'selected' : '' }}>{{ $template->nama_template }}</option>
                    @endforeach
                </select>
            </div>
            
            <div id="template-download-wrapper" class="{{ request('template') && request('template') !== 'blank' ? '' : 'hidden' }} mb-4 p-4 bg-[#fdf2f8] rounded-lg border border-[#fce7f3]">
                <h4 class="font-bold text-[#701a35] mb-2 text-sm">Template Terpilih</h4>
                <p class="text-xs text-gray-600 mb-3">Silakan unduh template ini, isi secara manual di komputer Anda, lalu unggah file jadinya di form bawah.</p>
                <a id="downloadTemplateBtn" href="{{ request('template') && request('template') !== 'blank' ? route('kasubtim.draf-saya.download-template', request('template')) : '#' }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-[#701a35] hover:bg-[#5b152b] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#701a35]">
                    <i class="ph ph-download-simple mr-2"></i> Download Template
                </a>
            </div>
        </div>
    </div>

    <!-- Form Draft -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <h3 class="font-bold text-gray-900">Form Draft</h3>
        </div>
        
        <div class="p-6">
            <form id="form-draf-surat" action="{{ route('kasubtim.draf-saya.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tautkan ke Surat Masuk</label>
                    <select name="surat_masuk_id" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#701a35] focus:border-[#701a35] shadow-sm text-sm" required>
                        <option value="">-- Pilih Surat Masuk Terkait --</option>
                        @foreach($suratMasuks as $sm)
                            <option value="{{ $sm->id }}" {{ old('surat_masuk_id') == $sm->id ? 'selected' : '' }}>{{ $sm->nomor_surat }} - {{ Str::limit($sm->perihal, 50) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Perihal Draft</label>
                    <input type="text" name="perihal" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#701a35] focus:border-[#701a35] shadow-sm text-sm" value="{{ old('perihal') }}" placeholder="Masukkan perihal surat">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Draft</label>
                        <input type="text" name="nomor_draft" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#701a35] focus:border-[#701a35] shadow-sm text-sm" value="DRAFT-{{ str_pad(rand(1,999), 3, '0', STR_PAD_LEFT) }}/{{ date('Y') }}" placeholder="DRAFT-XXX/XXXX">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal</label>
                        <input type="date" name="tanggal" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#701a35] focus:border-[#701a35] shadow-sm text-sm" value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Ringkasan</label>
                    <textarea name="ringkasan" rows="3" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#701a35] focus:border-[#701a35] shadow-sm text-sm" placeholder="Ringkasan singkat..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Catatan untuk Kabag</label>
                    <textarea name="catatan" rows="3" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-[#701a35] focus:border-[#701a35] shadow-sm text-sm" placeholder="Catatan tambahan..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Unggah Draft (PDF/DOCX)</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-10 text-center hover:bg-gray-50 transition-colors relative">
                        <input type="file" name="file_draf" id="file_draf" accept=".pdf,.doc,.docx" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="document.getElementById('file-name').textContent = this.files[0].name">
                        <p class="text-sm font-medium text-gray-900 mb-3">Seret & lepas file di sini</p>
                        <span class="inline-flex px-4 py-2 bg-white border border-gray-300 text-[#701a35] font-bold rounded-lg text-sm shadow-sm pointer-events-none mb-3">
                            Pilih File
                        </span>
                        <p class="text-xs text-gray-500">PDF/DOCX maks. 10 MB</p>
                        <p id="file-name" class="mt-4 text-sm font-bold text-[#701a35]"></p>
                    </div>
                    @error('file_draf')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 flex justify-end gap-4 border-t border-gray-100 pt-6">
                    <button type="button" onclick="submitDraft()" class="px-6 py-2.5 bg-[#701a35] text-white font-bold rounded-lg shadow-sm hover:bg-[#5b152b]" id="submitBtn">
                        Ajukan ke Kabag
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('templateSelector').addEventListener('change', function() {
            const templateId = this.value;
            const downloadWrapper = document.getElementById('template-download-wrapper');
            const downloadBtn = document.getElementById('downloadTemplateBtn');
            
            if (!templateId) {
                downloadWrapper.classList.add('hidden');
                return;
            }

            // Set href for download button
            downloadBtn.href = `/staf/template-surat/${templateId}/download`;
            downloadWrapper.classList.remove('hidden');
        });

        function submitDraft() {
            // Cek apakah file draf sudah diisi
            const fileInput = document.getElementById('file_draf');
            if (!fileInput.value) {
                alert('Silakan unggah File Draf Surat (PDF/DOC/DOCX) terlebih dahulu.');
                fileInput.classList.add('border-red-500');
                return;
            }
            
            document.getElementById('form-draf-surat').submit();
        }
    </script>
    @endpush
</x-app-layout>
