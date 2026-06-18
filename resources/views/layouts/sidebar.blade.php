<nav x-data="{ open: false }" class="bg-white shadow-sm w-full h-full flex flex-col transition-all duration-300">
    <!-- Role specific menu groups -->
    <div class="flex-1 overflow-y-auto py-4 px-3">
        @php
            $role = auth()->user()->role;
        @endphp

        <!-- ADMIN PERSURATAN -->
        @if($role === 'admin')
            <div class="mb-6">
                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Utama</p>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-squares-four text-lg"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.surat-masuk.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.surat-masuk.*') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-envelope-open text-lg"></i>
                    Surat Masuk
                </a>
                <a href="{{ route('admin.template-surat.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.template-surat.*') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-file-text text-lg"></i>
                    Template Surat
                </a>
            </div>
            <div class="mb-6">
                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Kelola</p>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-users text-lg"></i>
                    Pengguna
                </a>
                <a href="{{ route('admin.units.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.units.*') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-buildings text-lg"></i>
                    Unit Kerja
                </a>
            </div>
            <div class="mb-6">
                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Laporan</p>
                <a href="{{ route('admin.rekap.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.rekap.*') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-chart-bar text-lg"></i>
                    Rekap Surat
                </a>
                <a href="{{ route('admin.arsip.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.arsip.*') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-archive text-lg"></i>
                    Arsip
                </a>
            </div>
        @endif

        <!-- TU BIRO -->
        @if($role === 'tata_usaha')
            <div class="mb-6">
                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Utama</p>
                <a href="{{ route('tu.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('tu.dashboard') ? 'bg-sky-50 text-sky-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-squares-four text-lg"></i>
                    Dashboard
                </a>
                <a href="{{ route('tu.surat-masuk.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('tu.surat-masuk.*') ? 'bg-sky-50 text-sky-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-envelope-open text-lg"></i>
                    Surat Masuk
                </a>
                <a href="{{ route('tu.buku-agenda.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('tu.buku-agenda.*') ? 'bg-sky-50 text-sky-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-book-open text-lg"></i>
                    Buku Agenda
                </a>
                <a href="{{ route('tu.progress.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('tu.progress.*') ? 'bg-sky-50 text-sky-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-chart-line-up text-lg"></i>
                    Progress Surat
                </a>
            </div>
            <div class="mb-6">
                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Disposisi</p>
                <a href="{{ route('tu.disposisi.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs(['tu.disposisi.index', 'tu.disposisi.create', 'tu.disposisi.store']) ? 'bg-sky-50 text-sky-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-paper-plane-tilt text-lg"></i>
                    Buat Disposisi
                </a>
                <a href="{{ route('tu.disposisi.riwayat') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('tu.disposisi.riwayat') ? 'bg-sky-50 text-sky-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-clock-counter-clockwise text-lg"></i>
                    Riwayat
                </a>
            </div>
            <div class="mb-6">
                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Surat Keluar</p>
                <a href="{{ route('tu.upload-ttd.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('tu.upload-ttd.*') ? 'bg-sky-50 text-sky-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-upload-simple text-lg"></i>
                    Upload TTD
                </a>
                <a href="{{ route('tu.surat-final.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('tu.surat-final.*') ? 'bg-sky-50 text-sky-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-share-network text-lg"></i>
                    Distribusi
                </a>
            </div>
        @endif

        <!-- KEPALA BAGIAN -->
        @if($role === 'kepala_bagian')
            <div class="mb-6">
                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Utama</p>
                <a href="{{ route('kabag.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('kabag.dashboard') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-squares-four text-lg"></i>
                    Dashboard
                </a>
                <a href="{{ route('kabag.disposisi.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('kabag.disposisi.*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-tray-arrow-down text-lg"></i>
                    Disposisi Masuk
                </a>
                <a href="{{ route('kabag.review.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('kabag.review.*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-files text-lg"></i>
                    Perlu Review
                </a>
                <a href="{{ route('kabag.progress.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('kabag.progress.*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-chart-line-up text-lg"></i>
                    Progress Surat
                </a>
            </div>
            <div class="mb-6">
                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Buat Surat</p>
                <a href="{{ route('kabag.buat-surat.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('kabag.buat-surat.*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-pencil-simple text-lg"></i>
                    Buat Surat
                </a>
                <a href="{{ route('kabag.draf-saya.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('kabag.draf-saya.*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-files text-lg"></i>
                    Draf Saya
                </a>
            </div>
            <div class="mb-6">
                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Surat</p>
                <a href="{{ route('kabag.menunggu-kabiro.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('kabag.menunggu-kabiro.*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-hourglass-medium text-lg"></i>
                    Menunggu Kabiro
                </a>
                <a href="{{ route('kabag.terdistribusi.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('kabag.terdistribusi.*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-check-circle text-lg"></i>
                    Terdistribusi
                </a>
            </div>
            <div class="mb-6">
                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Laporan</p>
                <a href="{{ route('kabag.rekap.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('kabag.rekap.*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-chart-bar text-lg"></i>
                    Rekap Bagian
                </a>
            </div>
        @endif

        <!-- KEPALA SUB TIM -->
        @if($role === 'kepala_sub_tim')
            <div class="mb-6">
                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Utama</p>
                <a href="{{ route('kasubtim.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('kasubtim.dashboard') ? 'bg-yellow-50 text-yellow-800 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-squares-four text-lg"></i>
                    Dashboard
                </a>
                <a href="{{ route('kasubtim.penugasan.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('kasubtim.penugasan.*') ? 'bg-yellow-50 text-yellow-800 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-tray-arrow-down text-lg"></i>
                    Disposisi Masuk
                </a>
                <a href="{{ route('kasubtim.draft.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('kasubtim.draft.*') ? 'bg-yellow-50 text-yellow-800 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-file-text text-lg"></i>
                    Draft Surat
                </a>
                <a href="{{ route('kasubtim.progress.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('kasubtim.progress.*') ? 'bg-yellow-50 text-yellow-800 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-chart-line-up text-lg"></i>
                    Progress Surat
                </a>
            </div>
            <div class="mb-6">
                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Buat Surat</p>
                <a href="{{ route('kasubtim.buat-surat.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('kasubtim.buat-surat.*') ? 'bg-yellow-50 text-yellow-800 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-pencil-simple text-lg"></i>
                    Buat Surat
                </a>
                <a href="{{ route('kasubtim.draf-saya.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('kasubtim.draf-saya.*') ? 'bg-yellow-50 text-yellow-800 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-files text-lg"></i>
                    Draf Saya
                </a>
            </div>
            <div class="mb-6">
                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Review</p>
                <a href="{{ route('kasubtim.review.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('kasubtim.review.*') ? 'bg-yellow-50 text-yellow-800 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-files text-lg"></i>
                    Perlu Saya Review
                </a>
                <a href="{{ route('kasubtim.riwayat.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('kasubtim.riwayat.*') ? 'bg-yellow-50 text-yellow-800 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-clock-counter-clockwise text-lg"></i>
                    Riwayat
                </a>
            </div>
        @endif

        <!-- STAF -->
        @if($role === 'staf')
            <div class="mb-6">
                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Utama</p>
                <a href="{{ route('staf.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('staf.dashboard') ? 'bg-rose-50 text-rose-800 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-squares-four text-lg"></i>
                    Dashboard
                </a>
                <a href="{{ route('staf.tugas.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('staf.tugas.*') ? 'bg-rose-50 text-rose-800 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-clipboard-text text-lg"></i>
                    Tugas Saya
                </a>
                <a href="{{ route('staf.buat-surat.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('staf.buat-surat.*') ? 'bg-rose-50 text-rose-800 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-pencil-simple text-lg"></i>
                    Buat Surat
                </a>
            </div>
            <div class="mb-6">
                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Riwayat</p>
                <a href="{{ route('staf.draf-surat.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('staf.draf-surat.*') ? 'bg-rose-50 text-rose-800 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-files text-lg"></i>
                    Draft Saya
                </a>
                <a href="{{ route('staf.progress.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('staf.progress.*') ? 'bg-rose-50 text-rose-800 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-chart-line-up text-lg"></i>
                    Progress Surat
                </a>
                <a href="{{ route('staf.selesai.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('staf.selesai.*') ? 'bg-rose-50 text-rose-800 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-check-circle text-lg"></i>
                    Selesai
                </a>
            </div>
        @endif

        <!-- KEPALA BIRO -->
        @if($role === 'kepala_biro')
            <div class="mb-6">
                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Utama</p>
                <a href="{{ route('kabiro.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('kabiro.dashboard') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-squares-four text-lg"></i>
                    Dashboard
                </a>
                <a href="{{ route('kabiro.review-final.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('kabiro.review-final.*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-seal-check text-lg"></i>
                    Perlu Persetujuan
                </a>
                <a href="{{ route('kabiro.progress.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('kabiro.progress.*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-chart-line-up text-lg"></i>
                    Progress Surat
                </a>
            </div>
            <div class="mb-6">
                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Surat Keluar</p>
                <a href="{{ route('kabiro.terdistribusi.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('kabiro.terdistribusi.*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-check-circle text-lg"></i>
                    Terdistribusi
                </a>
            </div>
            <div class="mb-6">
                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Laporan</p>
                <a href="{{ route('kabiro.rekap.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('kabiro.rekap.*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="ph ph-chart-bar text-lg"></i>
                    Rekap Bulanan
                </a>
            </div>
        @endif
    </div>

    <div class="p-4 border-t border-gray-100">
        <!-- Settings/Logout -->
    </div>
</nav>

