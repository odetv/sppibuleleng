<x-app-layout title="Daftar Jabatan">
    <div class="text-slate-800 text-[14px] py-8 w-full px-4 sm:px-6 lg:px-8 relative">
        <div class="max-w-full mx-auto space-y-6">

            {{-- 2. SINGLE STATS CARD (Full Width) --}}
            <div class="w-full">
                <div class="bg-white p-6 rounded-xl border border-slate-200 flex items-center shadow-sm w-full transition-all hover:border-indigo-300">
                    <div class="p-3 bg-slate-50 rounded-lg mr-4 text-indigo-600 border border-slate-100 shadow-sm">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold uppercase text-slate-400">Kelola Jabatan</p>
                        <h4 class="text-2xl font-black text-slate-800">{{ $positions->count() }} <span class="text-sm font-normal text-slate-400 ml-1">Kategori Tersedia</span></h4>
                    </div>
                </div>
            </div>

            {{-- 3. DATABASE TABLE --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center text-slate-700">
                    <h3 class="font-bold uppercase tracking-wider">Daftar Jabatan Pengguna</h3>
                </div>
                <div class="overflow-x-auto scrollbar-thin">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                <th class="px-8 py-4">Slug Jabatan</th>
                                <th class="px-8 py-4 text-center">Nama Jabatan</th>
                                <th class="px-8 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($positions as $pos)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2.5 h-2.5 rounded-full bg-indigo-500 shadow-sm"></div>
                                        <span class="font-bold text-slate-700 font-sans tracking-tight">{{ $pos->slug_position }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-4 text-center">
                                    <span class="text-slate-600 font-medium">
                                        {{ $pos->name_position }}
                                    </span>
                                </td>
                                <td class="px-8 py-4 text-right">
                                    <button
                                        onclick="openEditPositionModal('{{ $pos->id_ref_position }}', '{{ $pos->name_position }}', '{{ $pos->slug_position }}')"
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

    @include('admin.positions.partials.modal-edit')
</x-app-layout>