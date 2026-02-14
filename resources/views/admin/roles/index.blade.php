<x-app-layout title="Manajemen Hak Akses">
    <div class="py-10 p-4">
        <div class="w-full mx-auto sm:px-6 lg:px-8 space-y-10">

            {{-- 1. Header Section (Sleek & Clean) --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-8">
                    <div class="flex flex-wrap justify-between items-center gap-4">
                        <div>
                            <h2 class="text-xl font-bold text-slate-800 uppercase tracking-wider">
                                Manajemen Hak Akses
                            </h2>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                                Konfigurasi tingkat otoritas pendaftar dalam sistem
                            </p>
                        </div>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-4 py-1.5 text-[10px] font-bold rounded bg-slate-50 text-slate-600 uppercase border border-slate-200 tracking-widest">
                                {{ $roles->count() }} Role Terdaftar
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Table Section (Minimalist) --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                <th class="px-8 py-5">Identitas Sistem (Slug)</th>
                                <th class="px-8 py-5">Nama Tampilan</th>
                                <th class="px-8 py-5 text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($roles as $role)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 rounded-full bg-emerald-500 mr-4 border border-emerald-100"></div>
                                        <span class="font-mono text-xs font-bold text-indigo-600 px-2 py-1 bg-indigo-50/50 rounded border border-indigo-100 tracking-tighter">
                                            {{ $role->slug_role }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-sm font-medium text-slate-700">
                                        {{ $role->name_role }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <button
                                        onclick="openEditModal('{{ $role->id_ref_role }}', '{{ $role->name_role }}', '{{ $role->slug_role }}')"
                                        class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded text-[10px] font-bold text-slate-600 uppercase tracking-widest hover:bg-slate-50 hover:text-slate-900 hover:border-slate-300 transition-all">
                                        <svg class="w-3.5 h-3.5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
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

    {{-- 3. Edit Modal (Sleek & Simple) --}}
    <div id="editRoleModal" class="fixed inset-0 z-[999] hidden">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeEditModal()"></div>

        {{-- Content --}}
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden transform transition-all shadow-2xl" id="modalContainer">
                <div class="p-8">
                    <div class="mb-6 pb-4 border-b border-slate-100 flex justify-between items-center">
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest">Update Label Role</h3>
                            <p class="text-[10px] text-slate-400 font-medium uppercase mt-0.5" id="modal_slug_display"></p>
                        </div>
                        <button onclick="closeEditModal()" class="text-slate-300 hover:text-slate-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form id="editRoleForm" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-6">
                            <div class="flex flex-col space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Tampilan Baru</label>
                                <input type="text" id="input_name_role" name="name_role"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded text-slate-700 text-sm font-medium focus:ring-1 focus:ring-slate-400 focus:bg-white outline-none transition-all"
                                    required>
                            </div>
                        </div>

                        <div class="mt-8 flex gap-3">
                            <button type="button" onclick="closeEditModal()"
                                class="flex-1 px-4 py-2.5 bg-slate-50 text-slate-500 text-[10px] font-bold uppercase tracking-widest rounded border border-slate-200 hover:bg-slate-100 transition-all">
                                Batal
                            </button>
                            <button type="submit"
                                class="flex-1 px-4 py-2.5 bg-slate-800 text-white text-[10px] font-bold uppercase tracking-widest rounded hover:bg-slate-900 transition-all shadow-sm">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, name, slug) {
            const modal = document.getElementById('editRoleModal');
            const container = document.getElementById('modalContainer');
            const form = document.getElementById('editRoleForm');
            const input = document.getElementById('input_name_role');
            const slugDisplay = document.getElementById('modal_slug_display');

            form.action = `/admin/roles/${id}`;
            input.value = name;
            slugDisplay.innerText = "System Key: " + slug;

            modal.classList.remove('hidden');
            setTimeout(() => {
                container.style.opacity = "1";
                container.style.transform = "scale(1)";
            }, 10);
        }

        function closeEditModal() {
            const modal = document.getElementById('editRoleModal');
            modal.classList.add('hidden');
        }
    </script>
</x-app-layout>