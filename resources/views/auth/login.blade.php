<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SPPI Buleleng | Masuk</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-50 overflow-x-hidden">
    <div class="min-h-screen flex items-center justify-center p-4">

        <div class="flex flex-col md:flex-row w-full max-w-5xl rounded-2xl overflow-hidden shadow-2xl border border-gray-100 bg-white md:max-h-[90vh]">

            <div class="w-full md:w-1/2 bg-darkblue p-8 md:p-10 flex flex-col items-center justify-center text-center text-white relative">
                <div class="absolute top-0 left-0 w-32 h-32 bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 right-0 w-48 h-48 bg-white/5 rounded-full translate-x-1/4 translate-y-1/4"></div>

                <div class="z-10 w-full">
                    <div class="flex items-center justify-center gap-4 mb-6 md:mb-8 bg-white/10 p-4 rounded-2xl backdrop-blur-sm inline-flex mx-auto">
                        <img src="{{ asset('assets/images/logo-bgn-circle.png') }}" alt="Logo BGN" class="h-14 md:h-20 w-auto object-contain">
                        <div class="h-10 md:h-12 w-px bg-white/20"></div>
                        <img src="{{ asset('assets/images/logo-sppi.png') }}" alt="Logo SPPI" class="h-14 md:h-20 w-auto object-contain">
                    </div>

                    <h1 class="text-xl md:text-3xl font-bold leading-tight mb-4">
                        Portal SPPI Buleleng <br>
                        <span class="text-blue-300">Badan Gizi Nasional</span>
                    </h1>

                    <p class="text-gray-300 text-xs md:text-sm max-w-xs mx-auto leading-relaxed">
                        Platform terintegrasi untuk mendukung manajemen SPPI BGN lebih efektif dan efisien
                    </p>
                </div>
            </div>

            <div class="w-full md:w-1/2 bg-white p-8 md:p-12 flex flex-col justify-center md:overflow-y-hidden">
                <div class="mb-8">
                    <h2 class="text-2xl pb-2 font-bold text-darkblue">Masuk ke Portal</h2>
                    <p class="text-gray-500 text-sm">Masuk menggunakan akun Anda yang telah terdaftar</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-semibold text-gray-600 mb-2">Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus
                                class="w-full pl-10 pr-4 py-3 bg-[#eef4ff] border-none rounded-lg focus:ring-2 focus:ring-blue-500 text-sm text-gray-700"
                                placeholder="Masukkan Email">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div x-data="{ show: false }">
                        <label for="password" class="block text-xs font-semibold text-gray-600 mb-2">Kata Sandi</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>

                            <input :type="show ? 'text' : 'password'" id="password" name="password" required
                                class="w-full pl-10 pr-10 py-3 bg-[#eef4ff] border-none rounded-lg focus:ring-2 focus:ring-blue-500 text-sm text-gray-700"
                                placeholder="Masukkan Kata Sandi">

                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600 transition-colors">
                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L1.39 1.39m16.338 16.338L22.61 22.61" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end">
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-blue-600 hover:text-gold transition-colors">
                            Lupa Kata Sandi?
                        </a>
                        @endif
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-darkblue text-white py-3 rounded-lg font-bold flex items-center justify-center gap-2 hover:bg-gold active:scale-[0.98] transition-all shadow-lg shadow-blue-900/20 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Masuk
                        </button>

                        <p class="text-center text-sm text-gray-600 mt-4">
                            Belum memiliki akun?
                            <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:text-gold transition-colors">Daftar Sekarang</a>
                        </p>
                    </div>
                </form>

                <div class="mt-8 md:mt-auto pt-8 pb-4 text-center">
                    <div class="text-xs text-gray-400 border-t pt-4">
                        <p>© {{ now()->format('Y') }} - Tim Data SPPI Buleleng Bali</p>
                        <p class="italic mt-1">Bagimu Negeri Jiwa Raga Kami</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>