<x-auth-layout :title="$title">
    <div class="min-h-screen flex items-center justify-center p-6 bg-slate-50">
        <div class="max-w-md w-full text-center">
            <div class="mb-8 flex justify-center gap-4">
                <img src="{{ asset('assets/images/logo-sppi.png') }}" class="h-16 w-auto">
                <img src="{{ asset('assets/images/logo-bgn.png') }}" class="h-16 w-auto">
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-xl border border-slate-100 relative overflow-hidden">
                {{-- Efek Cahaya Latar Belakang --}}
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-32 {{ $colorBg ?? 'bg-indigo-500/10' }} rounded-full blur-3xl animate-pulse"></div>

                <div class="relative">
                    {{-- Ikon Animasi --}}
                    <div class="w-20 h-20 {{ $colorIconBg ?? 'bg-indigo-50' }} {{ $colorIconText ?? 'text-indigo-600' }} rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner animate-bounce">
                        @yield('icon')
                    </div>

                    <h1 class="text-6xl font-black text-slate-200 mb-2">@yield('code')</h1>
                    <h2 class="text-2xl font-bold text-slate-800 mb-3 tracking-tight">@yield('title')</h2>
                    <p class="text-slate-500 text-sm leading-relaxed mb-8">
                        @yield('message')
                    </p>

                    <div class="flex flex-col gap-4 items-center">
                        <a href="{{ url('/') }}" class="group inline-flex items-center gap-2 text-xs font-bold text-indigo-500 hover:text-indigo-600 transition-all uppercase tracking-widest">
                            <span class="transition-transform group-hover:-translate-x-1">←</span> Kembali ke Beranda
                        </a>
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