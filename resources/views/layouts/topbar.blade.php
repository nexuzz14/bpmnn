@php
    $role = auth()->user()->role;
    
    // Tentukan warna navbar berdasarkan role
    $bgColor = match($role) {
        'admin' => 'bg-[#055a40]',
        'tata_usaha' => 'bg-[#0088cc]',
        'kepala_bagian' => 'bg-[#3b2c85]',
        'kepala_sub_tim' => 'bg-[#5c3a21]',
        'staf' => 'bg-[#701a35]',
        'kepala_biro' => 'bg-[#3b2c85]',
        default => 'bg-gray-800'
    };
    
    // Tentukan nama role untuk tampilan
    $roleName = match($role) {
        'admin' => 'Admin Persuratan',
        'tata_usaha' => 'TU Biro',
        'kepala_bagian' => 'Kepala Bagian',
        'kepala_sub_tim' => 'Kepala Sub Tim',
        'staf' => 'Staf',
        'kepala_biro' => 'Kepala Biro',
        default => 'User'
    };
@endphp

<header class="{{ $bgColor }} text-white h-16 flex items-center justify-between px-6 sticky top-0 z-20 shadow-md">
    <!-- Brand -->
    <div class="flex items-center gap-3">
        <h1 class="text-lg font-bold tracking-tight">SiPersurat <span class="font-normal opacity-80 mx-1">&mdash;</span> <span class="font-medium text-[15px]">Biro Keuangan & BMN Kemenag</span></h1>
    </div>

    <!-- Right Side (User Profile & Notification) -->
    <div class="flex items-center gap-6">
        
        <!-- Notifications Dropdown (Alpine.js) -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" @click.away="open = false" class="relative p-2 text-white/90 hover:text-white hover:bg-black/10 rounded-full transition-colors focus:outline-none">
                <i class="ph ph-bell text-xl"></i>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="absolute top-1.5 right-1.5 flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                    </span>
                @endif
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50 text-gray-800"
                 style="display: none;">
                
                <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 rounded-t-xl">
                    <h3 class="font-semibold text-sm">Notifikasi</h3>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <form method="POST" action="{{ route('notifications.read-all') }}">
                            @csrf
                            <button type="submit" class="text-xs text-blue-600 hover:text-blue-800 font-medium">Tandai semua dibaca</button>
                        </form>
                    @endif
                </div>

                <div class="max-h-80 overflow-y-auto">
                    @forelse(auth()->user()->unreadNotifications as $notification)
                        <a href="{{ route('notifications.read', $notification->id) }}" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-50 transition-colors relative">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500"></div>
                            <p class="text-sm font-medium text-gray-900">{{ $notification->data['title'] ?? 'Notifikasi Baru' }}</p>
                            <p class="text-xs text-gray-600 mt-0.5">{{ $notification->data['message'] ?? '' }}</p>
                            <p class="text-[10px] text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </a>
                    @empty
                        <div class="px-4 py-6 text-center text-gray-500">
                            <i class="ph ph-bell-z text-3xl mb-2 text-gray-300"></i>
                            <p class="text-sm">Tidak ada notifikasi baru.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- User Profile Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 focus:outline-none pl-4 border-l border-white/20">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-medium leading-tight text-white">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-white/80 uppercase tracking-wider font-medium mt-0.5">{{ $roleName }}</p>
                </div>
                <div class="h-9 w-9 rounded-full bg-white/20 flex items-center justify-center border border-white/30">
                    <i class="ph ph-user text-lg text-white"></i>
                </div>
                <i class="ph ph-caret-down text-white/70 text-xs"></i>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50 text-gray-800"
                 style="display: none;">
                
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="ph ph-user-circle text-lg text-gray-400"></i>
                    Profil Saya
                </a>
                
                <hr class="my-1 border-gray-100">
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                        <i class="ph ph-sign-out text-lg text-red-400"></i>
                        Keluar
                    </button>
                </form>
            </div>
        </div>

    </div>
</header>
