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
                            d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5H4.5a2.25 2.25 0 00-2.25 2.25m19.5 0l-9.75 6.75L2.25 6.75" />
                    </svg>
                </div>
            </div>

            {{-- Title --}}
            <h2 class="text-center text-2xl font-bold text-blue-900 tracking-tight mb-2">
                Verifikasi Email Anda
            </h2>
            <p class="text-center text-sm text-blue-500 mb-6 font-medium">
                Satu langkah lagi sebelum memulai
            </p>

            {{-- Description --}}
            <p class="text-sm text-gray-600 text-center leading-relaxed mb-6">
                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </p>

            {{-- Success Alert --}}
            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 flex items-start gap-3 px-4 py-3 bg-blue-50 border border-blue-200 rounded-xl">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-medium text-blue-700">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </p>
                </div>
            @endif

            {{-- Actions --}}
            <div class="flex items-center justify-between gap-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-semibold rounded-xl shadow-sm shadow-blue-200 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        {{ __('Resend Verification Email') }}
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="text-sm text-blue-500 hover:text-blue-800 font-medium underline underline-offset-2 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-1 rounded">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-guest-layout>