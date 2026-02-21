<nav id="main-navbar" class="fixed inset-x-0 top-0 z-50 transition-colors duration-300 bg-transparent" x-data="{ profileOpen: false }">
    <div id="nav-desktop">
        <div id="nav-content" class="flex items-center justify-between p-4 lg:px-8">
            <div class="flex lg:flex-1">
                <a href="#" class="-m-1.5 p-1.5 flex items-center gap-3">
                    <img src="{{ asset('assets/images/logo-sppi.png') }}" alt="" class="h-12 w-12" />
                    <img src="{{ asset('assets/images/logo-bgn.png') }}" alt="" class="h-12 w-auto" />
                    <span class="sr-only font-semibold">SPPI Buleleng</span>
                </a>
            </div>
            <div class="flex lg:hidden">
                <button type="button" command="show-modal" commandfor="mobile-menu" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700 cursor-pointer">
                    <span class="sr-only">Open main menu</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                        <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>

            <div class="hidden lg:flex lg:gap-x-12">
                <a href="#" class="text-sm/6 font-semibold text-darkblue hover:text-gold transition-colors">Beranda</a>
                <a href="#overview" class="text-sm/6 font-semibold text-darkblue hover:text-gold transition-colors">Sasaran</a>
                <div class="relative group">
                    <button type="button" class="flex items-center gap-x-1 text-sm/6 font-semibold text-darkblue hover:text-gold transition-colors focus:outline-none cursor-pointer">
                        Informasi
                        <svg class="size-5 flex-none text-gray-400 transition-transform group-hover:rotate-180" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div class="absolute -left-8 top-full z-10 mt-3 w-56 overflow-hidden rounded-3xl bg-white shadow-lg ring-1 ring-gray-900/5 transition-all opacity-0 translate-y-1 invisible group-hover:opacity-100 group-hover:translate-y-0 group-hover:visible">
                        <div class="p-4">
                            <a href="#services" class="block rounded-lg px-3 py-2 text-sm font-semibold text-darkblue hover:text-gold transition-colors hover:bg-gray-50">Layanan BGN</a>
                            <a href="#quick-info" class="block rounded-lg px-3 py-2 text-sm font-semibold text-darkblue hover:text-gold transition-colors hover:bg-gray-50">Informasi SPPG</a>
                            <a href="#geospatial" class="block rounded-lg px-3 py-2 text-sm font-semibold text-darkblue hover:text-gold transition-colors hover:bg-gray-50">Peta Geospasial</a>
                        </div>
                    </div>
                </div>
                <div class="relative group">
                    <button type="button" class="flex items-center gap-x-1 text-sm/6 font-semibold text-darkblue hover:text-gold transition-colors focus:outline-none cursor-pointer">
                        SPPI
                        <svg class="size-5 flex-none text-gray-400 transition-transform group-hover:rotate-180" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div class="absolute -left-8 top-full z-10 mt-3 w-56 overflow-hidden rounded-3xl bg-white shadow-lg ring-1 ring-gray-900/5 transition-all opacity-0 translate-y-1 invisible group-hover:opacity-100 group-hover:translate-y-0 group-hover:visible">
                        <div class="p-4">
                            <a href="#base-camp" class="block rounded-lg px-3 py-2 text-sm font-semibold text-darkblue hover:text-gold transition-colors hover:bg-gray-50">Posko SPPI</a>
                            <a href="#administration" class="block rounded-lg px-3 py-2 text-sm font-semibold text-darkblue hover:text-gold transition-colors hover:bg-gray-50">Administrasi SPPI</a>
                        </div>
                    </div>
                </div>
                <a href="#faq" class="text-sm/6 font-semibold text-darkblue hover:text-gold transition-colors">FAQ</a>
            </div>

            <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                @auth
                <div class="relative" @click.away="profileOpen = false">
                    <button @click.prevent="profileOpen = !profileOpen" class="flex items-center gap-4 focus:outline-none cursor-pointer">
                        <span class="hidden text-right lg:block">
                            <span class="block text-sm font-semibold text-darkblue leading-none">{{ Auth::user()->person->name }}</span>
                        </span>
                        <div class="h-11 w-11 rounded-full border border-gray-200 p-0.5 overflow-hidden">
                            @if(Auth::user()->person && Auth::user()->person->photo)
                            <img src="{{ asset('storage/' . Auth::user()->person->photo) }}" class="h-full w-full rounded-full object-cover">
                            @else
                            <div class="flex h-full w-full items-center justify-center rounded-full bg-indigo-100 text-indigo-600 font-medium uppercase text-[14px]">
                                {{ substr(Auth::user()->person->name, 0, 1) }}
                            </div>
                            @endif
                        </div>
                        <svg :class="profileOpen ? 'rotate-180' : ''" class="hidden fill-current text-slate-400 sm:block h-4 w-4 transition-transform duration-200" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-show="profileOpen"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-4 w-64 rounded-xl border border-slate-200 bg-white shadow-xl z-[999] overflow-hidden"
                        x-cloak>

                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 text-left">
                            <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->person->name }}</p>
                            <p class="text-xs text-slate-500 truncate mt-0.5">{{ Auth::user()->email }}</p>
                            <div class="mt-2 flex flex-wrap gap-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700 uppercase tracking-wider">
                                    {{ auth()->user()->person->position->name_position ?? 'Belum Menjabat' }} </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700 uppercase tracking-wider">
                                    {{ Auth::user()->role->name_role ?? 'User' }} </span>
                            </div>
                        </div>

                        <ul class="flex flex-col gap-1 p-2">
                            <li>
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-3.5 px-4 py-2.5 text-[13px] font-medium text-slate-600 duration-300 ease-in-out hover:text-indigo-600 hover:bg-slate-50 rounded-lg text-left">
                                    <svg class="w-4.5 h-4.5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('profile.show') }}" class="flex items-center gap-3.5 px-4 py-2.5 text-[13px] font-medium text-slate-600 duration-300 ease-in-out hover:text-indigo-600 hover:bg-slate-50 rounded-lg text-left">
                                    <svg class="w-4.5 h-4.5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Profil
                                </a>
                            </li>
                        </ul>

                        <div class="p-2 mt-1 border-t border-slate-100 text-left">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-3.5 px-4 py-2.5 text-[13px] font-medium text-red-600 duration-300 ease-in-out hover:bg-red-50 rounded-lg text-left cursor-pointer">
                                    <svg class="w-4.5 h-4.5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="text-sm/6 font-semibold bg-blue-500 hover:bg-gold text-white px-4 py-2 rounded-md transition-colors">Masuk</a>
                @endauth
            </div>
        </div>
    </div>

    <div id="nav-mobile">
        <dialog id="mobile-menu" class="backdrop:bg-transparent lg:hidden">
            <div tabindex="0" class="fixed inset-0 focus:outline-none">
                <el-dialog-panel class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white p-4 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                    <div class="flex items-center justify-between">
                        <a href="#" class="-m-1.5 p-1.5 flex items-center gap-3">
                            <img src="{{ asset('assets/images/logo-sppi.png') }}" alt="" class="h-12 w-12" />
                            <img src="{{ asset('assets/images/logo-bgn.png') }}" alt="" class="h-12 w-auto" />
                        </a>
                        <button type="button" command="close" commandfor="mobile-menu" class="-m-2.5 rounded-md p-2.5 text-gray-700 cursor-pointer">
                            <span class="sr-only">Close menu</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                                <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>

                    <div class="mt-6 flow-root">
                        <div class="-my-6 divide-y divide-gray-500/10">
                            @auth
                            <div class="py-6">
                                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 shadow-sm">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-full border border-white shadow-sm overflow-hidden flex-shrink-0">
                                            @if(Auth::user()->person && Auth::user()->person->photo)
                                            <img src="{{ asset('storage/' . Auth::user()->person->photo) }}" class="h-full w-full object-cover">
                                            @else
                                            <div class="flex h-full w-full items-center justify-center bg-indigo-100 text-indigo-600 font-bold uppercase">
                                                {{ substr(Auth::user()->person->name, 0, 1) }}
                                            </div>
                                            @endif
                                        </div>
                                        <div class="overflow-hidden text-left">
                                            <p class="text-sm font-bold text-darkblue truncate">{{ Auth::user()->person->name }}</p>
                                            <p class="text-[11px] text-gray-500 truncate leading-tight">{{ Auth::user()->email }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-3 flex flex-wrap gap-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-green-100 text-green-700 uppercase tracking-wider">
                                            {{ auth()->user()->person->position->name_position ?? 'Belum Menjabat' }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-indigo-100 text-indigo-700 uppercase tracking-wider">
                                            {{ Auth::user()->role->name_role ?? 'User' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endauth

                            <div class="space-y-2 py-6">
                                <a href="#" class="-mx-3 flex items-center gap-3 rounded-lg px-3 py-2 text-base/7 font-semibold text-darkblue hover:text-gold hover:bg-gray-50 transition-colors">
                                    <svg class="size-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Beranda
                                </a>
                                <a href="#overview" class="-mx-3 flex items-center gap-3 rounded-lg px-3 py-2 text-base/7 font-semibold text-darkblue hover:text-gold hover:bg-gray-50 transition-colors">
                                    <svg class="size-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    Sasaran
                                </a>
                                <div class="-mx-3">
                                    <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.chevron').classList.toggle('rotate-180')"
                                        class="flex w-full items-center justify-between rounded-lg py-2 pl-3 pr-3.5 text-base/7 font-semibold text-darkblue hover:text-gold hover:bg-gray-50 cursor-pointer">
                                        <div class="flex items-center gap-3">
                                            <svg class="size-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Informasi
                                        </div>
                                        <svg class="chevron size-5 flex-none text-gray-400 transition-transform" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div class="hidden mt-2 space-y-2 pl-11">
                                        <a href="#services" class="block rounded-lg py-2 text-sm font-semibold text-darkblue hover:text-gold">Layanan BGN</a>
                                        <a href="#quick-info" class="block rounded-lg py-2 text-sm font-semibold text-darkblue hover:text-gold">Informasi SPPG</a>
                                        <a href="#geospatial" class="block rounded-lg py-2 text-sm font-semibold text-darkblue hover:text-gold">Peta Geospasial</a>
                                    </div>
                                </div>
                                <div class="-mx-3">
                                    <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.chevron-sppi').classList.toggle('rotate-180')"
                                        class="flex w-full items-center justify-between rounded-lg py-2 pl-3 pr-3.5 text-base/7 font-semibold text-darkblue hover:text-gold hover:bg-gray-50 cursor-pointer">
                                        <div class="flex items-center gap-3">
                                            <svg class="size-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            SPPI
                                        </div>
                                        <svg class="chevron-sppi size-5 flex-none text-gray-400 transition-transform" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div class="hidden mt-2 space-y-2 pl-11">
                                        <a href="#base-camp" class="block rounded-lg py-2 text-sm font-semibold text-darkblue hover:text-gold">Posko SPPI</a>
                                        <a href="#administration" class="block rounded-lg py-2 text-sm font-semibold text-darkblue hover:text-gold">Administrasi SPPI</a>
                                    </div>
                                </div>
                                <a href="#faq" class="-mx-3 flex items-center gap-3 rounded-lg px-3 py-2 text-base/7 font-semibold text-darkblue hover:text-gold hover:bg-gray-50 transition-colors">
                                    <svg class="size-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    FAQ
                                </a>
                            </div>

                            <div class="py-6 border-t border-gray-100">
                                @auth
                                <div class="space-y-1">
                                    <a href="{{ route('dashboard') }}" class="-mx-3 flex items-center gap-3 rounded-lg px-3 py-2 text-base/7 font-semibold text-darkblue hover:bg-gray-50">
                                        <svg class="size-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                        </svg>
                                        Dashboard
                                    </a>
                                    <a href="{{ route('profile.show') }}" class="-mx-3 flex items-center gap-3 rounded-lg px-3 py-2 text-base/7 font-semibold text-darkblue hover:bg-gray-50">
                                        <svg class="size-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Profil
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="-mx-3 flex w-full items-center gap-3 rounded-lg px-3 py-2 text-base/7 font-semibold text-red-600 hover:bg-red-50 cursor-pointer text-left">
                                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                                @else
                                <a href="{{ route('login') }}" class="-mx-3 flex items-center gap-3 rounded-lg px-3 py-2 text-base/7 font-semibold text-darkblue hover:bg-gray-50">
                                    <svg class="size-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Masuk
                                </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </el-dialog-panel>
            </div>
        </dialog>
    </div>
</nav>