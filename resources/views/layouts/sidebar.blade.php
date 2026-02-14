<div x-show="mobileSidebar"
    class="fixed inset-0 z-50 bg-black/50 lg:hidden"
    @click="mobileSidebar = false" x-cloak></div>

<aside
    id="sidebar"
    {{-- Inisialisasi State Accordion di sini --}}
    x-data="{ 
        selected: '{{ request()->routeIs('profile.*') ? 'profil' : (request()->routeIs('admin.users.*') ? 'admin_user' : '') }}' 
    }"
    :class="[
        (sidebarExpanded || isHovered || mobileSidebar) ? 'w-72' : 'w-20',
        mobileSidebar ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
    ]"
    @mouseenter="isHovered = true"
    @mouseleave="isHovered = false"
    class="absolute left-0 top-0 z-99 flex h-screen flex-col overflow-y-hidden border-r border-gray-200 bg-white duration-300 ease-in-out lg:static shadow-sm">

    <div :class="(sidebarExpanded || isHovered || mobileSidebar) ? 'px-6' : 'justify-center'"
        class="flex items-center justify-between py-6 shrink-0 transition-all duration-300">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 shrink-0">
            <img src="{{ asset('assets/images/logo-sppi.png') }}" alt="Logo" class="w-10 h-auto">
            <img src="{{ asset('assets/images/logo-bgn.png') }}" alt="Logo" class="w-25 h-auto" x-show="sidebarExpanded || isHovered || mobileSidebar">
            <!-- <span x-show="sidebarExpanded || isHovered || mobileSidebar"
                class="text-xl font-bold text-slate-800 whitespace-nowrap uppercase">SPPI BGN Buleleng</span> -->
        </a>
    </div>

    <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 flex-1">
        <nav class="mt-2 px-3">

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
                            class="w-full group relative flex items-center gap-3 rounded-lg py-2.5 text-[14px] font-medium text-slate-600 transition-all duration-200 hover:bg-indigo-50 hover:text-indigo-600">
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
                            <li><a href="#" class="block py-2 px-3 text-[13px] text-slate-500 hover:text-indigo-600 transition-colors">Daftar Role</a></li>
                            <li><a href="{{ route('admin.users.index') }}" class="block py-2 px-3 text-[13px] text-slate-500 hover:text-indigo-600 transition-colors">Daftar Pengguna</a></li>
                        </ul>
                    </li>
                    <li>
                        <button @click="selected = (selected === 'admin_sppg' ? '' : 'admin_sppg')"
                            :class="(sidebarExpanded || isHovered || mobileSidebar) ? 'px-4' : 'justify-center'"
                            class="w-full group relative flex items-center gap-3 rounded-lg py-2.5 text-[14px] font-medium text-slate-600 transition-all duration-200 hover:bg-indigo-50 hover:text-indigo-600">
                            <div class="shrink-0 flex items-center justify-center">
                                <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <span x-show="sidebarExpanded || isHovered || mobileSidebar" class="flex-1 text-left whitespace-nowrap">Manajemen SPPG</span>
                            <svg x-show="sidebarExpanded || isHovered || mobileSidebar" class="w-4 h-4 transition-transform duration-200" :class="selected === 'admin_sppg' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <ul x-show="selected === 'admin_sppg' && (sidebarExpanded || isHovered || mobileSidebar)" x-transition class="ml-9 mt-1 flex flex-col gap-1 border-l border-slate-100">
                            <li><a href="#" class="block py-2 px-3 text-[13px] text-slate-500 hover:text-indigo-600 transition-colors">Daftar SPPG</a></li>
                            <li><a href="#" class="block py-2 px-3 text-[13px] text-slate-500 hover:text-indigo-600 transition-colors">Daftar PM</a></li>
                            <li><a href="#" class="block py-2 px-3 text-[13px] text-slate-500 hover:text-indigo-600 transition-colors">Daftar Supplier MBG</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

            <div>
                <div class="flex items-center mb-2 transition-all duration-300"
                    :class="(sidebarExpanded || isHovered || mobileSidebar) ? 'ml-4' : 'justify-center'">
                    <h3 x-show="sidebarExpanded || isHovered || mobileSidebar" class="text-[11px] font-semibold text-slate-400 uppercase tracking-[2px]">SPPG</h3>
                    <hr x-show="!(sidebarExpanded || isHovered || mobileSidebar)" class="w-5 border-slate-200">
                </div>
                <ul class="mb-6 flex flex-col gap-1.5">
                    <li>
                        <a href="#" :class="(sidebarExpanded || isHovered || mobileSidebar) ? 'px-4' : 'justify-center'"
                            class="group relative flex items-center gap-3 rounded-lg py-2.5 text-[14px] font-medium text-slate-600 transition-all duration-200 hover:bg-indigo-50 hover:text-indigo-600">
                            <div class="shrink-0 flex items-center justify-center">
                                <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <span x-show="sidebarExpanded || isHovered || mobileSidebar" class="whitespace-nowrap">Yayasan</span>
                        </a>
                    </li>
                    <li>
                        <button @click="selected = (selected === 'sppg' ? '' : 'sppg')"
                            :class="(sidebarExpanded || isHovered || mobileSidebar) ? 'px-4' : 'justify-center'"
                            class="w-full group relative flex items-center gap-3 rounded-lg py-2.5 text-[14px] font-medium text-slate-600 transition-all duration-200 hover:bg-indigo-50 hover:text-indigo-600">
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
                            <li><a href="#" class="block py-2 px-3 text-[13px] text-slate-500 hover:text-indigo-600 transition-colors">Kerjasama (PKS)</a></li>
                            <li><a href="#" class="block py-2 px-3 text-[13px] text-slate-500 hover:text-indigo-600 transition-colors">Sertifikasi SPPG</a></li>
                            <li><a href="#" class="block py-2 px-3 text-[13px] text-slate-500 hover:text-indigo-600 transition-colors">Petugas SPPG</a></li>
                            <li><a href="#" class="block py-2 px-3 text-[13px] text-slate-500 hover:text-indigo-600 transition-colors">Kelompok PM</a></li>
                            <li><a href="#" class="block py-2 px-3 text-[13px] text-slate-500 hover:text-indigo-600 transition-colors">Supplier MBG</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

            <div>
                <div class="flex items-center mb-2 transition-all duration-300"
                    :class="(sidebarExpanded || isHovered || mobileSidebar) ? 'ml-4' : 'justify-center'">
                    <h3 x-show="sidebarExpanded || isHovered || mobileSidebar" class="text-[11px] font-semibold text-slate-400 uppercase tracking-[2px]">PENGATURAN</h3>
                    <hr x-show="!(sidebarExpanded || isHovered || mobileSidebar)" class="w-5 border-slate-200">
                </div>
                <ul class="mb-6 flex flex-col gap-1.5">
                    <li>
                        <button @click="selected = (selected === 'profil' ? '' : 'profil')"
                            :class="(sidebarExpanded || isHovered || mobileSidebar) ? 'px-4' : 'justify-center'"
                            class="w-full group relative flex items-center gap-3 rounded-lg py-2.5 text-[14px] font-medium transition-all duration-200 {{ request()->routeIs('profile.*') ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
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
                            <li><a href="{{ route('profile.show') }}" class="block py-2 px-3 text-[13px] {{ request()->routeIs('profile.show') ? 'text-indigo-600 font-bold' : 'text-slate-500 hover:text-indigo-600' }}">Biodata</a></li>
                            <li><a href="{{ route('profile.edit') }}" class="block py-2 px-3 text-[13px] {{ request()->routeIs('profile.edit') ? 'text-indigo-600 font-bold' : 'text-slate-500 hover:text-indigo-600' }}">Edit Profil</a></li>
                        </ul>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" @click="selected = ''"
                                :class="(sidebarExpanded || isHovered || mobileSidebar) ? 'px-4 w-full' : 'justify-center w-full'"
                                class="group relative flex items-center gap-3 py-2.5 text-[14px] font-medium text-red-600 duration-300 ease-in-out hover:bg-red-50 rounded-lg text-left cursor-pointer">
                                <div class="shrink-0 flex items-center justify-center">
                                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </div>
                                <span x-show="sidebarExpanded || isHovered || mobileSidebar" class="whitespace-nowrap">Keluar</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</aside>