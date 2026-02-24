<x-auth-layout title="Maintenance">
    <div class="min-h-screen flex items-center justify-center p-6 bg-slate-50">
        <div class="max-w-md w-full text-center">
            <div class="mb-8 flex justify-center gap-4">
                <img src="{{ asset('assets/images/logo-sppi.png') }}" class="h-16 w-auto">
                <img src="{{ asset('assets/images/logo-bgn.png') }}" class="h-16 w-auto">
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-xl border border-slate-100 relative overflow-hidden">
                {{-- Efek cahaya latar belakang yang berdenyut (Pulse) --}}
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl animate-pulse"></div>

                <div class="relative">
                    <a href="{{ route('login') }}" class="block group/icon cursor-default md:cursor-pointer" title="Login Admin">
                        <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner transition-transform duration-500 group-hover/icon:scale-110 group-hover/icon:bg-indigo-100">
                            <svg class="w-10 h-10 animate-spin" style="animation-duration: 2s;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.5 12a7.5 7.5 0 0015 0m-15 0a7.5 7.5 0 1115 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077l1.062-.613m12.795 7.382l1.062-.613M14.713 19.5l-.613-1.062m-7.382-12.795l-.613-1.062m15.795 12.795l-1.062-.613M4.5 4.5l1.062.613m12.795 0l1.062.613M4.5 19.5l1.062-.613" />
                                <circle cx="12" cy="12" r="3" stroke-width="1.5" />
                            </svg>
                        </div>
                    </a>

                    <h1 class="text-2xl font-bold text-slate-800 mb-3 tracking-tight">Sistem Sedang Diperbarui</h1>
                    <p class="text-slate-500 text-sm leading-relaxed mb-8">
                        Kami sedang melakukan pemeliharaan rutin untuk meningkatkan layanan Portal SPPI Buleleng. Silakan coba kembali beberapa saat lagi.
                    </p>

                    <div class="flex flex-col gap-4 items-center">
                        {{-- Tampilkan tombol logout JIKA user sedang login --}}
                        @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="group inline-flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-rose-500 transition-all uppercase tracking-widest cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 transition-transform group-hover:-translate-x-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                </svg>
                                Keluar dari Sesi
                            </button>
                        </form>
                        @endauth

                        {{-- Tampilkan link kembali JIKA user TIDAK login DAN TIDAK sedang di beranda --}}
                        @guest
                        @if(!request()->is('/'))
                        <a href="/" class="group inline-flex items-center gap-2 text-xs font-bold text-indigo-500 hover:text-indigo-600 transition-all uppercase tracking-widest">
                            <span class="transition-transform group-hover:-translate-x-1">←</span> Kembali ke Beranda
                        </a>
                        @endif
                        @endguest
                    </div>
                </div>
            </div>

            <div class="text-xs text-gray-400 pt-6">
                <p>© {{ now()->format('Y') }} - Tim Data SPPI Buleleng Bali</p>
                <p class="italic mt-1">Bagimu Negeri Jiwa Raga Kami</p>
            </div>
        </div>
    </div>
</x-auth-layout>