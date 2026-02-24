<x-auth-layout title="Lupa Kata Sandi">
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
                            Pemulihan Kata Sandi <br>
                            <span class="text-blue-300">SPPI Buleleng</span>
                        </h1>

                        <p class="text-gray-300 text-xs md:text-sm max-w-xs mx-auto leading-relaxed">
                            Masukkan email Anda untuk menerima tautan pemulihan kata sandi portal SPPI BGN
                        </p>
                    </div>
                </div>

                <div class="w-full md:w-1/2 bg-white p-8 md:p-12 flex flex-col justify-center">
                    <div class="mb-6">
                        <h2 class="text-2xl pb-2 font-bold text-darkblue">Lupa Kata Sandi?</h2>
                        <p class="text-gray-500 text-sm leading-relaxed">
                            Jangan khawatir. Beritahu kami alamat email Anda dan kami akan mengirimkan tautan pengaturan ulang kata sandi
                        </p>
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="email" class="block text-xs font-semibold text-gray-600 mb-2">Alamat Email</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </span>
                                <input id="email" type="email" name="email" :value="old('email')" required autofocus
                                    class="w-full pl-10 pr-4 py-3 bg-[#eef4ff] border-none rounded-lg focus:ring-2 focus:ring-blue-500 text-sm text-gray-700"
                                    placeholder="Masukkan Email Terdaftar">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <button type="submit" class="cursor-pointer w-full bg-darkblue text-white py-3 rounded-lg font-bold flex items-center justify-center gap-2 hover:bg-gold active:scale-[0.98] transition-all shadow-lg shadow-blue-900/20">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Kirim Tautan Reset
                            </button>

                            <p class="text-center text-sm text-gray-600 mt-4">
                                Ingat kata sandi Anda?
                                <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:text-gold transition-colors">Masuk</a>
                            </p>
                            
                            <div class="mt-4 text-center">
                                <a href="/" class="text-sm text-gray-400 hover:text-red-600 transition-colors">
                                    ← Kembali ke Beranda
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-auth-layout>