<div x-show="mobileSidebar"
    class="fixed inset-0 z-50 bg-black/50 lg:hidden"
    @click="mobileSidebar = false" x-cloak></div>

<aside
    id="sidebar"
    x-data="{ 
        selected: '{{ request()->routeIs('profile.*') ? 'profil' : (request()->routeIs('admin.*') ? 'admin_user' : (request()->routeIs('sppg.*') ? 'sppg' : '')) }}' 
    }"
    :class="[
        (sidebarExpanded || isHovered || mobileSidebar) ? 'w-72' : 'w-20',
        mobileSidebar ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
    ]"
    @mouseenter="isHovered = true"
    @mouseleave="isHovered = false"
    class="absolute left-0 top-0 z-99 flex h-screen flex-col overflow-y-hidden border-r border-gray-200 bg-white duration-300 ease-in-out lg:static shadow-sm">

    {{-- LOGO SECTION --}}
    <div :class="(sidebarExpanded || isHovered || mobileSidebar) ? 'px-6' : 'justify-center'"
        class="flex items-center justify-between py-6 shrink-0 transition-all duration-300">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 shrink-0">
            <img src="{{ asset('assets/images/logo-sppi.png') }}" alt="Logo" class="w-10 h-auto">
            <img src="{{ asset('assets/images/logo-bgn.png') }}" alt="Logo" class="w-25 h-auto" x-show="sidebarExpanded || isHovered || mobileSidebar">
        </a>
    </div>

    {{-- MIDDLE CONTENT (SCROLLABLE MENU) --}}
    <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 flex-1">
        <nav class="mt-2 px-3">
            {{-- 1. DASHBOARD --}}
            <ul class="mb-6 flex flex-col gap-1.5">
                <li>
                    <a href="{{ route('dashboard') }}"
                        @click="selected = ''"
                        :class="(sidebarExpanded || isHovered || mobileSidebar) ? 'px-4' : 'justify-center'"
                        class="group relative flex items-center gap-3 rounded-lg py-2.5 text-[14px] font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 font-bold shadow-sm' : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
                        <div class="shrink-0 flex items-center justify-center">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </div>
                        <span x-show="sidebarExpanded || isHovered || mobileSidebar" class="whitespace-nowrap">Dashboard</span>
                    </a>
                </li>
            </ul>

            @if(auth()->user()->status_user === 'active')
            {{-- 2. MENU ADMIN --}}
            @if(auth()->user()->role->slug_role === 'administrator')
            <div>
                <div class="flex items-center mb-2 transition-all duration-300"
                    :class="(sidebarExpanded || isHovered || mobileSidebar) ? 'ml-4' : 'justify-center'">
                    <h3 x-show="sidebarExpanded || isHovered || mobileSidebar" class="text-[11px] font-semibold text-slate-400 uppercase tracking-[2px]">ADMIN</h3>
                    <hr x-show="!(sidebarExpanded || isHovered || mobileSidebar)" class="w-5 border-slate-200">
                </div>
                <ul class="mb-6 flex flex-col gap-1.5">
                    <li>
                        <button @click="selected = (selected === 'admin_user' ? '' : 'admin_user')"
                            :class="(sidebarExpanded || isHovered || mobileSidebar) ? 'px-4' : 'justify-center'"
                            class="w-full group relative flex items-center gap-3 rounded-lg py-2.5 text-[14px] font-medium transition-all duration-200 {{ request()->routeIs('admin.users.*', 'admin.roles.*', 'admin.positions.*') ? 'bg-indigo-50/50 text-indigo-600' : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
                            <div class="shrink-0 flex items-center justify-center">
                                <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <span x-show="sidebarExpanded || isHovered || mobileSidebar" class="flex-1 text-left whitespace-nowrap">Manajemen Pengguna</span>
                            <svg x-show="sidebarExpanded || isHovered || mobileSidebar" class="w-4 h-4 transition-transform duration-200" :class="selected === 'admin_user' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <ul x-show="selected === 'admin_user' && (sidebarExpanded || isHovered || mobileSidebar)" x-transition class="ml-9 mt-1 flex flex-col gap-1 border-l border-slate-100">
                            <li><a href="{{ route('admin.users.index') }}" class="block py-2 px-3 text-[13px] transition-colors {{ request()->routeIs('admin.users.index') ? 'text-indigo-600 font-bold' : 'text-slate-500 hover:text-indigo-600' }}">Daftar Pengguna</a></li>
                            <li><a href="{{ route('admin.roles.index') }}" class="block py-2 px-3 text-[13px] transition-colors {{ request()->routeIs('admin.roles.index') ? 'text-indigo-600 font-bold' : 'text-slate-500 hover:text-indigo-600' }}">Daftar Hak Akses</a></li>
                            <li><a href="{{ route('admin.positions.index') }}" class="block py-2 px-3 text-[13px] transition-colors {{ request()->routeIs('admin.positions.index') ? 'text-indigo-600 font-bold' : 'text-slate-500 hover:text-indigo-600' }}">Daftar Jabatan</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            @endif

            {{-- 3. MENU UNIT --}}
            @php
            $allowedPositions = ['kasppg', 'ag', 'ak'];
            $userPosition = auth()->user()->person?->position?->slug_position;
            $isAdmin = auth()->user()->role->slug_role === 'administrator';
            @endphp

            @if($isAdmin || in_array($userPosition, $allowedPositions))
            <div>
                <div class="flex items-center mb-2 transition-all duration-300"
                    :class="(sidebarExpanded || isHovered || mobileSidebar) ? 'ml-4' : 'justify-center'">
                    <h3 x-show="sidebarExpanded || isHovered || mobileSidebar" class="text-[11px] font-semibold text-slate-400 uppercase tracking-[2px]">UNIT</h3>
                    <hr x-show="!(sidebarExpanded || isHovered || mobileSidebar)" class="w-5 border-slate-200">
                </div>
                <ul class="mb-6 flex flex-col gap-1.5">
                    <li>
                        <a href="{{ route('sppg.yayasan') }}"
                            :class="(sidebarExpanded || isHovered || mobileSidebar) ? 'px-4' : 'justify-center'"
                            class="group relative flex items-center gap-3 rounded-lg py-2.5 text-[14px] font-medium transition-all duration-200 {{ request()->routeIs('sppg.yayasan') ? 'bg-indigo-50 text-indigo-600 font-bold shadow-sm' : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
                            <div class="shrink-0 flex items-center justify-center">
                                <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <span x-show="sidebarExpanded || isHovered || mobileSidebar" class="whitespace-nowrap">Yayasan</span>
                        </a>
                    </li>
                    {{-- Dropdown SPPG --}}
                    <li>
                        <button @click="selected = (selected === 'sppg' ? '' : 'sppg')"
                            :class="(sidebarExpanded || isHovered || mobileSidebar) ? 'px-4' : 'justify-center'"
                            class="w-full group relative flex items-center gap-3 rounded-lg py-2.5 text-[14px] font-medium transition-all duration-200 {{ request()->routeIs('sppg.pks', 'sppg.sertifikasi', 'sppg.petugas', 'sppg.pm', 'sppg.supplier') ? 'bg-indigo-50/50 text-indigo-600' : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
                            <div class="shrink-0 flex items-center justify-center">
                                <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <span x-show="sidebarExpanded || isHovered || mobileSidebar" class="flex-1 text-left whitespace-nowrap">SPPG</span>
                            <svg x-show="sidebarExpanded || isHovered || mobileSidebar" class="w-4 h-4 transition-transform duration-200" :class="selected === 'sppg' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <ul x-show="selected === 'sppg' && (sidebarExpanded || isHovered || mobileSidebar)" x-transition class="ml-9 mt-1 flex flex-col gap-1 border-l border-slate-100">
                            <li><a href="{{ route('sppg.pks') }}" class="block py-2 px-3 text-[13px] transition-colors {{ request()->routeIs('sppg.pks') ? 'text-indigo-600 font-bold' : 'text-slate-500 hover:text-indigo-600' }}">Kerjasama (PKS)</a></li>
                            <li><a href="{{ route('sppg.sertifikasi') }}" class="block py-2 px-3 text-[13px] transition-colors {{ request()->routeIs('sppg.sertifikasi') ? 'text-indigo-600 font-bold' : 'text-slate-500 hover:text-indigo-600' }}">Sertifikasi SPPG</a></li>
                            <li><a href="{{ route('sppg.petugas') }}" class="block py-2 px-3 text-[13px] transition-colors {{ request()->routeIs('sppg.petugas') ? 'text-indigo-600 font-bold' : 'text-slate-500 hover:text-indigo-600' }}">Petugas SPPG</a></li>
                            <li><a href="{{ route('sppg.pm') }}" class="block py-2 px-3 text-[13px] transition-colors {{ request()->routeIs('sppg.pm') ? 'text-indigo-600 font-bold' : 'text-slate-500 hover:text-indigo-600' }}">Kelompok PM</a></li>
                            <li><a href="{{ route('sppg.supplier') }}" class="block py-2 px-3 text-[13px] transition-colors {{ request()->routeIs('sppg.supplier') ? 'text-indigo-600 font-bold' : 'text-slate-500 hover:text-indigo-600' }}">Supplier MBG</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            @endif
            @endif
        </nav>
    </div>

    {{-- BOTTOM SECTION (PENGATURAN & KELUAR) --}}
    <div class="mt-auto border-t border-slate-100 px-3 py-4 shrink-0">
        <div class="flex items-center mb-2 transition-all duration-300"
            :class="(sidebarExpanded || isHovered || mobileSidebar) ? 'ml-4' : 'justify-center'">
            <h3 x-show="sidebarExpanded || isHovered || mobileSidebar" class="text-[11px] font-semibold text-slate-400 uppercase tracking-[2px]">PENGATURAN</h3>
            <hr x-show="!(sidebarExpanded || isHovered || mobileSidebar)" class="w-5 border-slate-200">
        </div>
        <ul class="flex flex-col gap-1.5">
            <li>
                <button @click="selected = (selected === 'profil' ? '' : 'profil')"
                    :class="(sidebarExpanded || isHovered || mobileSidebar) ? 'px-4' : 'justify-center'"
                    class="w-full group relative flex items-center gap-3 rounded-lg py-2.5 text-[14px] font-medium transition-all duration-200 {{ request()->routeIs('profile.*') ? 'bg-indigo-50/50 text-indigo-600' : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
                    <div class="shrink-0 flex items-center justify-center">
                        <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <span x-show="sidebarExpanded || isHovered || mobileSidebar" class="flex-1 text-left whitespace-nowrap">Profil</span>
                    <svg x-show="sidebarExpanded || isHovered || mobileSidebar" class="w-4 h-4 transition-transform duration-200" :class="selected === 'profil' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <ul x-show="selected === 'profil' && (sidebarExpanded || isHovered || mobileSidebar)" x-transition class="ml-9 mt-1 flex flex-col gap-1 border-l border-slate-100">
                    <li><a href="{{ route('profile.show') }}" class="block py-2 px-3 text-[13px] transition-colors {{ request()->routeIs('profile.show') ? 'text-indigo-600 font-bold' : 'text-slate-500 hover:text-indigo-600' }}">Biodata</a></li>
                    <li><a href="{{ route('profile.edit') }}" class="block py-2 px-3 text-[13px] transition-colors {{ request()->routeIs('profile.edit') ? 'text-indigo-600 font-bold' : 'text-slate-500 hover:text-indigo-600' }}">Edit Profil</a></li>
                </ul>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        :class="(sidebarExpanded || isHovered || mobileSidebar) ? 'px-4 w-full' : 'justify-center w-full'"
                        class="group relative flex items-center gap-3 py-2.5 text-[14px] font-medium text-red-600 duration-300 ease-in-out hover:bg-red-50 rounded-lg text-left cursor-pointer border-none bg-transparent">
                        <div class="shrink-0 flex items-center justify-center">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </div>
                        <span x-show="sidebarExpanded || isHovered || mobileSidebar" class="whitespace-nowrap font-medium">Keluar</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</aside>