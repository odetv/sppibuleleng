<x-auth-layout title="Verifikasi Email">
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
                            Verifikasi Email <br>
                            <span class="text-blue-300">Langkah Terakhir</span>
                        </h1>

                        <p class="text-gray-300 text-xs md:text-sm max-w-xs mx-auto leading-relaxed">
                            Terima kasih telah mendaftar! Silakan periksa kotak masuk email Anda untuk melakukan verifikasi akun
                        </p>
                    </div>
                </div>

                <div class="w-full md:w-1/2 bg-white p-8 md:p-12 flex flex-col justify-center text-center md:text-left">
                    <div class="mb-8">
                        <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-6 mx-auto md:mx-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-darkblue animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-darkblue mb-4">Cek Email Anda</h2>
                        <p class="text-gray-500 text-sm leading-relaxed">
                            Kami telah mengirimkan tautan verifikasi ke email Anda. Jika Anda tidak menerimanya, kami dapat mengirim ulang tautan tersebut
                        </p>
                    </div>

                    @if (session('status') == 'verification-link-sent')
                        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg">
                            <p class="text-sm text-green-700 font-medium text-left">
                                {{ __('Tautan verifikasi baru telah dikirimkan ke alamat email yang Anda berikan saat pendaftaran.') }}
                            </p>
                        </div>
                    @endif

                    <div class="flex flex-col gap-4">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="cursor-pointer w-full bg-darkblue text-white py-3 rounded-lg font-bold flex items-center justify-center gap-2 hover:bg-gold active:scale-[0.98] transition-all shadow-lg shadow-blue-900/20">
                                Kirim Ulang Email Verifikasi
                            </button>
                        </form>

                        <form method="POST" action="{{ route('logout') }}" class="text-center">
                            @csrf
                            <button type="submit" class="cursor-pointer text-sm text-gray-400 hover:text-red-500 font-medium transition-colors underline decoration-gray-300 underline-offset-4">
                                {{ __('Keluar') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-auth-layout>