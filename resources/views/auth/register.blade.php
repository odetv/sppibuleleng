<x-auth-layout title="Daftar">
    <div class="font-sans antialiased text-gray-900 bg-gray-50 overflow-x-hidden">
        <div class="min-h-screen flex items-center justify-center p-4">

            <div class="flex flex-col md:flex-row w-full max-w-7xl rounded-2xl overflow-hidden shadow-2xl border border-gray-100 bg-white md:max-h-[90vh]">

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
                            Gabung di SPPI Buleleng <br>
                            <span class="text-blue-300">Badan Gizi Nasional</span>
                        </h1>

                        <p class="text-gray-300 text-xs md:text-sm max-w-xs mx-auto leading-relaxed">
                            Daftarkan akun Anda untuk mulai mengelola manajemen SPPI BGN secara terintegrasi
                        </p>
                    </div>
                </div>

                <div class="w-full md:w-1/2 bg-white p-8 md:p-12 flex flex-col justify-center md:overflow-y-auto">
                    <div class="mb-8">
                        <h2 class="text-2xl pb-2 font-bold text-darkblue">Buat Akun Baru</h2>
                        <p class="text-gray-500 text-sm">Lengkapi data di bawah ini untuk mendaftar</p>
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label for="email" class="block text-xs font-semibold text-gray-600 mb-2">Email</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                        </svg>
                                    </span>
                                    <input id="email" type="email" name="email" :value="old('email')" required
                                        class="w-full pl-10 pr-4 py-3 bg-[#eef4ff] border-none rounded-lg focus:ring-2 focus:ring-blue-500 text-sm text-gray-700"
                                        placeholder="Cth: user@email.com">
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <label for="phone" class="block text-xs font-semibold text-gray-600 mb-2">Nomor WhatsApp</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </span>
                                    <input id="phone" type="text" name="phone" :value="old('phone')" required
                                        pattern="[0-9]*" inputmode="numeric"
                                        class="w-full pl-10 pr-4 py-3 bg-[#eef4ff] border-none rounded-lg focus:ring-2 focus:ring-blue-500 text-sm text-gray-700"
                                        placeholder="Cth: 085xxxxxxxxx">
                                </div>
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
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
                                        placeholder="Buat Kata Sandi">
                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">
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

                            <div x-data="{ show: false }">
                                <label for="password_confirmation" class="block text-xs font-semibold text-gray-600 mb-2">Konfirmasi Kata Sandi</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </span>
                                    <input :type="show ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required
                                        class="w-full pl-10 pr-10 py-3 bg-[#eef4ff] border-none rounded-lg focus:ring-2 focus:ring-blue-500 text-sm text-gray-700"
                                        placeholder="Ulangi Kata Sandi">
                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">
                                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L1.39 1.39m16.338 16.338L22.61 22.61" />
                                        </svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full bg-darkblue text-white py-3 rounded-lg font-bold flex items-center justify-center gap-2 hover:bg-gold active:scale-[0.98] transition-all shadow-lg shadow-blue-900/20 cursor-pointer">
                                Daftar
                            </button>

                            <p class="text-center text-sm text-gray-600 mt-4">
                                Sudah memiliki akun?
                                <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:text-gold transition-colors">Masuk</a>
                            </p>
                        </div>

                        <div class="mt-4 text-center">
                            <a href="/" class="text-sm text-gray-400 hover:text-red-600 transition-colors">
                                ← Kembali ke Beranda
                            </a>
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
    </div>
</x-auth-layout>