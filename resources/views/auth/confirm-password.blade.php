<x-auth-layout title="Konfirmasi Kata Sandi">
    <div class="font-sans antialiased text-gray-900 bg-gray-50 overflow-x-hidden">
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
                            Area Keamanan <br>
                            <span class="text-blue-300">SPPI Buleleng</span>
                        </h1>

                        <p class="text-gray-300 text-xs md:text-sm max-w-xs mx-auto leading-relaxed">
                            Demi keamanan akun Anda, silakan konfirmasi kata sandi sebelum melanjutkan akses ke fitur ini
                        </p>
                    </div>
                </div>

                <div class="w-full md:w-1/2 bg-white p-8 md:p-12 flex flex-col justify-center">
                    <div class="mb-6">
                        <h2 class="text-2xl pb-2 font-bold text-darkblue">Konfirmasi Sandi</h2>
                        <p class="text-gray-500 text-sm leading-relaxed">
                            Ini adalah area aplikasi yang aman. Silakan masukkan kata sandi Anda
                        </p>
                    </div>

                    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="password" class="block text-xs font-semibold text-gray-600 mb-2">Kata Sandi</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </span>
                                <input id="password" type="password" name="password" required autocomplete="current-password"
                                    class="w-full pl-10 pr-4 py-3 bg-[#eef4ff] border-none rounded-lg focus:ring-2 focus:ring-blue-500 text-sm text-gray-700"
                                    placeholder="Masukkan Kata Sandi Anda">
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <button type="submit" class="w-full bg-darkblue text-white py-3 rounded-lg font-bold flex items-center justify-center gap-2 hover:bg-gold active:scale-[0.98] transition-all shadow-lg shadow-blue-900/20 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Konfirmasi Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-auth-layout>