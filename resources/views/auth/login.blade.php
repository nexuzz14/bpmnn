<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block font-medium text-sm text-gray-700">Email / NIP</label>
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <i class="ph ph-envelope-simple text-lg"></i>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                       class="pl-10 block w-full border-gray-300 focus:border-[#055a40] focus:ring-[#055a40] rounded-lg shadow-sm transition-colors text-sm py-2.5">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block font-medium text-sm text-gray-700">Kata Sandi</label>
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <i class="ph ph-lock-key text-lg"></i>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="pl-10 block w-full border-gray-300 focus:border-[#055a40] focus:ring-[#055a40] rounded-lg shadow-sm transition-colors text-sm py-2.5">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-[#055a40] hover:bg-[#044a35] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#055a40] transition-colors">
                Masuk ke Sistem
            </button>
        </div>
    </form>
</x-guest-layout>
