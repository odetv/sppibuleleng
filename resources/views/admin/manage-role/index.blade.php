<x-app-layout title="Daftar Hak Akses">
    <div class="text-slate-800 text-[14px] py-8 w-full px-4 sm:px-6 lg:px-8 relative">
        <div class="max-w-full mx-auto space-y-6">

            {{-- 1. HEADER SECTION --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm w-full">
                <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 uppercase leading-tight">Kelola Hak Akses</h2>
                        <p class="text-sm text-slate-400 font-medium mt-1">Manajemen hak akses terdaftar di sistem</p>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-4 py-2 text-[10px] font-bold rounded bg-white text-slate-600 uppercase border border-slate-200 tracking-widest shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-indigo-500 mr-2 animate-pulse"></span>
                            {{ $roles->count() }} Hak Akses Tersedia
                        </span>
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

    @include('admin.manage-role.partials.modal-edit')
</x-app-layout>