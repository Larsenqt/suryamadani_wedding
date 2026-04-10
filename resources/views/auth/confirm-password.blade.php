<x-guest-layout>
    {{-- Logo --}}
    <div class="flex justify-center mb-8">
        <img src="{{ asset('images/navbar.png') }}" alt="Logo" class="h-12 w-auto">
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-2xl shadow-xl border border-blue-100 overflow-hidden">

        {{-- Top accent bar --}}
        <div class="h-1.5 w-full bg-gradient-to-r from-blue-400 via-blue-600 to-blue-800"></div>

        <div class="px-8 py-8">

            {{-- Icon --}}
            <div class="flex justify-center mb-5">
                <div class="w-16 h-16 rounded-full bg-blue-50 border-2 border-blue-200 flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
            </div>

            {{-- Title --}}
            <h2 class="text-center text-2xl font-bold text-blue-900 tracking-tight mb-2">
                Konfirmasi Password
            </h2>
            <p class="text-center text-sm text-blue-500 mb-5 font-medium">
                Area aman — autentikasi diperlukan
            </p>

            {{-- Description --}}
            <p class="text-sm text-gray-600 text-center leading-relaxed mb-6">
                {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
            </p>

            {{-- Form --}}
            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
                @csrf

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-blue-900 mb-1.5">
                        {{ __('Password') }}
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>
                        <x-text-input
                            id="password"
                            class="block w-full pl-10 pr-4 py-2.5 text-sm border border-blue-200 rounded-xl bg-blue-50/40 text-gray-800 placeholder-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                {{-- Submit --}}
                <div class="pt-1">
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-semibold rounded-xl shadow-sm shadow-blue-200 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                        {{ __('Confirm') }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-guest-layout>