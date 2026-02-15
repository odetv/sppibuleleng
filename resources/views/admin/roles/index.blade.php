<x-app-layout title="Daftar Hak Akses">
    <div class="py-10 p-4 text-slate-800 text-[14px]">
        <div class="w-full mx-auto sm:px-6 lg:px-8 space-y-10">

            {{-- 1. HEADER SECTION --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 uppercase tracking-wider">
                            Daftar Hak Akses
                        </h2>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                            Konfigurasi tingkat otoritas pendaftar dalam sistem
                        </p>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-4 py-2 text-[10px] font-bold rounded bg-white text-slate-600 uppercase border border-slate-200 tracking-widest shadow-sm">
                            {{ $roles->count() }} Role Terdaftar
                        </span>
                    </div>
                </div>
            </div>

            {{-- 2. SINGLE STATS CARD (Full Width) --}}
            <div class="w-full">
                <div class="bg-white p-6 rounded-xl border border-slate-200 flex items-center shadow-sm w-full transition-all hover:border-indigo-300">
                    <div class="p-3 bg-slate-50 rounded-lg mr-4 text-indigo-600 border border-slate-100 shadow-sm">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[2px] text-slate-400">Total Otoritas Tersedia</p>
                        <h4 class="text-2xl font-black text-slate-800">{{ $roles->count() }} <span class="text-xs font-bold text-slate-400 ml-1">Tingkatan Hak Akses</span></h4>
                    </div>
                </div>
            </div>

            {{-- 3. DATABASE TABLE --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center text-slate-700">
                    <h3 class="font-bold uppercase tracking-wider">Daftar Otoritas Sistem</h3>
                </div>
                <div class="overflow-x-auto scrollbar-thin">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                <th class="px-8 py-4">Identitas Sistem (Slug)</th>
                                <th class="px-8 py-4 text-center">Nama Tampilan Role</th>
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
                                        title="Edit Role">
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

    {{-- 4. EDIT MODAL (Style Seragam Manajemen User) --}}
    <div id="editRoleModal" class="fixed inset-0 z-[99] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeEditModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-lg overflow-hidden transform transition-all font-sans">
            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center text-slate-800 font-bold uppercase tracking-widest text-[14px]">
                <div>
                    <h3>Update Hak Akses</h3>
                    <p class="text-[10px] text-indigo-500 mt-1" id="modal_slug_display"></p>
                </div>
                <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 text-2xl cursor-pointer">&times;</button>
            </div>

            <form id="editRoleForm" method="POST">
                @csrf
                @method('PATCH')

                <div class="p-8">
                    <label class="text-[11px] font-bold uppercase text-slate-500 block mb-2 tracking-widest">Nama Tampilan Role</label>
                    <input type="text" id="input_name_role" name="name_role"
                        class="w-full px-4 py-3 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm transition-all font-sans"
                        required>
                </div>

                <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-4">
                    <button type="button" onclick="closeEditModal()"
                        class="flex-1 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 cursor-pointer transition-all">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 py-4 text-[11px] font-bold uppercase tracking-wider text-white bg-slate-800 rounded-xl shadow-lg hover:bg-slate-900 cursor-pointer transition-all active:scale-[0.98]">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, name, slug) {
            const modal = document.getElementById('editRoleModal');
            const form = document.getElementById('editRoleForm');
            const input = document.getElementById('input_name_role');
            const slugDisplay = document.getElementById('modal_slug_display');

            form.action = `/admin/roles/${id}`;
            input.value = name;
            slugDisplay.innerText = "System Key: " + slug;

            modal.classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editRoleModal').classList.add('hidden');
        }
    </script>
</x-app-layout>