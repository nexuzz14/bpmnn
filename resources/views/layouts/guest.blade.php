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
    <body class="font-sans text-gray-900 antialiased bg-gray-50 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="w-full sm:max-w-md bg-white shadow-xl overflow-hidden sm:rounded-2xl border border-gray-100 animate-[fadeIn_0.5s_ease-out]">
                <!-- Header / Cover -->
                <div class="bg-[#055a40] px-6 py-8 text-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-full bg-black/10"></div>
                    <div class="relative z-10 flex flex-col items-center">
                        <img src="{{ asset('images/logo-kemenag.png') }}" alt="Logo Kemenag" class="w-16 h-16 mb-4 drop-shadow-md" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/4/44/Logo_Kementerian_Agama_Republik_Indonesia.png'">
                        <h1 class="text-2xl font-bold text-white tracking-tight">SiPersurat</h1>
                        <p class="text-emerald-50 text-sm mt-1 opacity-90">Biro Keuangan & BMN — Setjen Kemenag RI</p>
                    </div>
                </div>

                <!-- Form Section -->
                <div class="px-8 py-8">
                    {{ $slot }}
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center animate-[fadeIn_0.7s_ease-out]">
                <p class="text-xs text-gray-500 font-medium tracking-wide">&copy; {{ date('Y') }} SiPersurat &mdash; Biro Keuangan & BMN Kemenag</p>
            </div>
        </div>

        <style>
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </body>
</html>
