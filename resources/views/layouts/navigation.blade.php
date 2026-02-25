<nav class="sticky top-0 z-40 flex w-full bg-white border-b border-gray-200 shadow-sm">
    <div class="flex grow items-center justify-between px-4 py-4 sm:px-8">

        <div class="flex items-center gap-2 sm:gap-4">
            <button @click.stop="window.innerWidth < 1024 ? mobileSidebar = !mobileSidebar : sidebarExpanded = !sidebarExpanded"
                class="z-50 block rounded-md border border-gray-200 bg-white p-1.5 hover:text-indigo-600 transition-colors cursor-pointer">
                <svg class="h-5.5 w-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <div class="relative" x-data="{ dropdownOpen: false }" @click.away="dropdownOpen = false">
            <button @click.prevent="dropdownOpen = !dropdownOpen" class="flex items-center gap-4 focus:outline-none cursor-pointer">
                <span class="hidden text-right lg:block">
                    <span class="block text-sm font-semibold text-slate-800 leading-none">{{ Auth::user()->person->name ?? 'Profil Belum Dilengkapi' }}</span>
                </span>

                <div class="h-11 w-11 rounded-full border border-gray-200 p-0.5 overflow-hidden">
                    @if(Auth::user()->person && Auth::user()->person->photo)
                    <img src="{{ asset('storage/' . Auth::user()->person->photo) }}" class="h-full w-full rounded-full object-cover">
                    @else
                    <div class="flex h-full w-full items-center justify-center rounded-full bg-indigo-100 text-indigo-600 font-medium uppercase text-[14px]">
                        {{ substr(Auth::user()->person->name ?? Auth::user()->email, 0, 1) }}
                    </div>
                    @endif
                </div>

                <svg :class="dropdownOpen ? 'rotate-180' : ''" class="hidden fill-current text-slate-400 sm:block h-4 w-4 transition-transform duration-200" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>

            <div x-show="dropdownOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 mt-4 w-64 rounded-xl border border-slate-200 bg-white shadow-xl z-999 overflow-hidden"
                x-cloak>

                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 text-left">
                    <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->person->name ?? 'Profil Belum Dilengkapi'}}</p>
                    <p class="text-xs text-slate-500 truncate mt-0.5">{{ Auth::user()->email }}</p>
                    <div class="mt-1">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700 uppercase tracking-wider">
                            {{ auth()->user()->person->position->name_position ?? 'Belum Menjabat' }} </span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700 uppercase tracking-wider">
                            {{ Auth::user()->role->name_role ?? 'User' }} </span>
                    </div>
                </div>

                <ul class="flex flex-col gap-1 p-2">
                    <li>
                        <a href="/" class="flex items-center gap-3.5 px-4 py-2.5 text-[13px] font-medium text-slate-600 duration-300 ease-in-out hover:text-indigo-600 hover:bg-slate-50 rounded-lg">
                            <svg class="w-4.5 h-4.5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('profile.show') }}" class="flex items-center gap-3.5 px-4 py-2.5 text-[13px] font-medium text-slate-600 duration-300 ease-in-out hover:text-indigo-600 hover:bg-slate-50 rounded-lg">
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

    </div>
</nav>