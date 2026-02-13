<div x-show="mobileSidebar"
    class="fixed inset-0 z-50 bg-black/50 lg:hidden"
    @click="mobileSidebar = false" x-cloak></div>

<aside
    id="sidebar"
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
            <img src="{{ asset('assets/images/logo-sppi.png') }}" alt="Logo SPPI" class="w-10 h-auto">
            <span x-show="sidebarExpanded || isHovered || mobileSidebar"
                class="text-xl font-bold text-slate-800 whitespace-nowrap">Admin Panel</span>
        </a>

        <button @click="mobileSidebar = false" class="lg:hidden">
            <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 flex-1">
        <nav class="mt-5 px-3">
            <div>
                <div class="flex items-center mb-4 transition-all duration-300"
                    :class="(sidebarExpanded || isHovered || mobileSidebar) ? 'ml-4' : 'justify-center'">

                    <h3 x-show="sidebarExpanded || isHovered || mobileSidebar"
                        class="text-[11px] font-semibold text-slate-400 uppercase tracking-[2px]">
                        MENU
                    </h3>

                    <div x-show="!(sidebarExpanded || isHovered || mobileSidebar)" class="text-slate-400 font-bold tracking-[2px]">
                        ...
                    </div>
                </div>

                <ul class="mb-6 flex flex-col gap-1.5">
                    <li>
                        <a href="{{ route('dashboard') }}"
                            :class="(sidebarExpanded || isHovered || mobileSidebar) ? 'px-4' : 'justify-center'"
                            class="group relative flex items-center gap-3 rounded-lg py-2.5 text-[14px] font-medium text-slate-600 transition-all duration-200 hover:bg-indigo-50 hover:text-indigo-600 {{ request()->routeIs('dashboard') ? 'bg-indigo-100 text-indigo-600 font-bold' : '' }}">

                            <div class="shrink-0 flex items-center justify-center">
                                <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                </svg>
                            </div>
                            <span x-show="sidebarExpanded || isHovered || mobileSidebar" class="whitespace-nowrap">Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('profile.show') }}"
                            :class="(sidebarExpanded || isHovered || mobileSidebar) ? 'px-4' : 'justify-center'"
                            class="group relative flex items-center gap-3 rounded-lg py-2.5 text-[14px] font-medium text-slate-600 transition-all duration-200 hover:bg-indigo-50 hover:text-indigo-600 {{ request()->routeIs('profile.show') ? 'bg-indigo-50 text-indigo-600 font-bold' : '' }}">
                            <div class="shrink-0 flex items-center justify-center">
                                <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <span x-show="sidebarExpanded || isHovered || mobileSidebar" class="whitespace-nowrap">Profil Pengguna</span>
                        </a>
                    </li>

                    @if(auth()->user()->role->slug_role === 'admin' || auth()->user()->role->slug_role === 'superadmin')
                    <li>
                        <a href="{{ route('admin.users.index') }}"
                            :class="(sidebarExpanded || isHovered || mobileSidebar) ? 'px-4' : 'justify-center'"
                            class="group relative flex items-center gap-3 rounded-lg py-2.5 text-[14px] font-medium text-slate-600 transition-all duration-200 hover:bg-indigo-50 hover:text-indigo-600 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-600 font-bold' : '' }}">

                            <div class="shrink-0 flex items-center justify-center">
                                <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <span x-show="sidebarExpanded || isHovered || mobileSidebar" class="whitespace-nowrap">Verifikasi User</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </nav>
    </div>
</aside>