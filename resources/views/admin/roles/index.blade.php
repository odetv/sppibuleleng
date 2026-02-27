<x-app-layout title="Daftar Hak Akses">
    <div class="text-slate-800 text-[14px] py-8 w-full px-4 sm:px-6 lg:px-8 relative">
        <div class="max-w-full mx-auto space-y-6">

            {{-- 2. SINGLE STATS CARD (Full Width) --}}
            <div class="w-full">
                <div class="bg-white p-6 rounded-xl border border-slate-200 flex items-center shadow-sm w-full transition-all hover:border-indigo-300">
                    <div class="p-3 bg-slate-50 rounded-lg mr-4 text-indigo-600 border border-slate-100 shadow-sm">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold uppercase text-slate-400">Kelola Hak Akses</p>
                        <h4 class="text-2xl font-black text-slate-800">{{ $roles->count() }} <span class="text-sm font-normal text-slate-400 ml-1">Hak Akses Tersedia</span></h4>
                    </div>
                </div>
            </div>

            {{-- 3. DATABASE TABLE --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center text-slate-700">
                    <h3 class="font-bold uppercase tracking-wider">Daftar Hak Akses Sistem</h3>
                </div>
                <div class="overflow-x-auto scrollbar-thin">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                <th class="px-8 py-4">Slug Hak Akses</th>
                                <th class="px-8 py-4 text-center">Nama Hak Akses</th>
                                <th class="px-8 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($roles as $role)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2.5 h-2.5 rounded-full bg-indigo-500 shadow-sm"></div>
                                        <span class="font-bold text-slate-700 font-sans tracking-tight">{{ $role->slug_role }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-4 text-center">
                                    <span class="text-slate-600 font-medium">
                                        {{ $role->name_role }}
                                    </span>
                                </td>
                                <td class="px-8 py-4 text-right">
                                    <button
                                        onclick="openEditModal('{{ $role->id_ref_role }}', '{{ $role->name_role }}', '{{ $role->slug_role }}')"
                                        class="p-2 text-slate-400 hover:text-indigo-600 transition-colors cursor-pointer"
                                        title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('admin.roles.partials.modal-edit')
</x-app-layout>