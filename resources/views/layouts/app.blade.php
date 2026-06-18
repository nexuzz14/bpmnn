<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>SiPersurat - Kementerian Agama RI</title>
        <link rel="icon" type="image/png" href="{{ asset('images/logo-kemenag.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Icons -->
        <script src="https://unpkg.com/@phosphor-icons/web"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50 flex flex-col h-screen overflow-hidden">
        
        <!-- Topbar (Full Width) -->
        @include('layouts.topbar')

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex overflow-hidden">
            
            <!-- Sidebar -->
            <div class="w-64 flex-shrink-0 hidden md:flex flex-col border-r border-gray-100 bg-white">
                @include('layouts.sidebar')
            </div>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto bg-gray-50/50 p-6 relative">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
                        <div class="flex justify-between items-start">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="ph ph-check-circle text-xl text-green-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700 font-medium">
                                        {{ session('success') }}
                                    </p>
                                </div>
                            </div>
                            <button @click="show = false" class="text-green-500 hover:text-green-700">
                                <i class="ph ph-x text-lg"></i>
                            </button>
                        </div>
                    </div>
                @endif
                
                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                        <div class="flex justify-between items-start">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="ph ph-x-circle text-xl text-red-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700 font-medium">
                                        {{ session('error') }}
                                    </p>
                                </div>
                            </div>
                            <button @click="show = false" class="text-red-500 hover:text-red-700">
                                <i class="ph ph-x text-lg"></i>
                            </button>
                        </div>
                    </div>
                @endif

                {{ $slot }}
            </main>
            

        </div>
        
        <style>
            [x-cloak] { display: none !important; }
        </style>
        
        @stack('scripts')
    </body>
</html>
