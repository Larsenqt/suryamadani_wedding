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
                            d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
            </div>

            {{-- Title --}}
            <h2 class="text-center text-2xl font-bold text-blue-900 tracking-tight mb-2">
                Reset Password
            </h2>
            <p class="text-center text-sm text-blue-500 mb-7 font-medium">
                Buat password baru untuk akun Anda
            </p>

            {{-- Form --}}
            <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                @csrf

                {{-- Token --}}
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-blue-900 mb-1.5">
                        {{ __('Email') }}
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5H4.5a2.25 2.25 0 00-2.25 2.25m19.5 0l-9.75 6.75L2.25 6.75" />
                            </svg>
                        </div>
                        <x-text-input
                            id="email"
                            class="block w-full pl-10 pr-4 py-2.5 text-sm border border-blue-200 rounded-xl bg-blue-50/40 text-gray-800 placeholder-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            type="email"
                            name="email"
                            :value="old('email', $request->email)"
                            required autofocus autocomplete="username" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

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
                            required autocomplete="new-password" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-blue-900 mb-1.5">
                        {{ __('Confirm Password') }}
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <x-text-input
                            id="password_confirmation"
                            class="block w-full pl-10 pr-4 py-2.5 text-sm border border-blue-200 rounded-xl bg-blue-50/40 text-gray-800 placeholder-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            type="password"
                            name="password_confirmation"
                            required autocomplete="new-password" />
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                {{-- Submit --}}
                <div class="pt-1">
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-semibold rounded-xl shadow-sm shadow-blue-200 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        {{ __('Reset Password') }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-guest-layout>