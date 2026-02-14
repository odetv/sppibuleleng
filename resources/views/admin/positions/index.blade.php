<x-app-layout title="Manajemen Jabatan">
    <div class="py-10 p-4">
        <div class="w-full mx-auto sm:px-6 lg:px-8 space-y-10">

            {{-- 1. Header Section (Minimalist & Clean) --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-8">
                    <div class="flex flex-wrap justify-between items-center gap-4">
                        <div>
                            <h2 class="text-xl font-bold text-slate-800 uppercase tracking-wider">
                                Manajemen Jabatan
                            </h2>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                                Pengaturan struktur organisasi dan penamaan posisi fungsional
                            </p>
                        </div>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-4 py-1.5 text-[10px] font-bold rounded bg-slate-50 text-slate-600 uppercase border border-slate-200 tracking-widest">
                                {{ $positions->count() }} Jabatan Terdaftar
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Table Section (Focused & Clean) --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                <th class="px-8 py-5">Slug Posisi</th>
                                <th class="px-8 py-5">Nama Jabatan</th>
                                <th class="px-8 py-5 text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($positions as $pos)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 rounded-full bg-indigo-500 mr-4 border border-indigo-100"></div>
                                        <span class="font-mono text-xs font-bold text-slate-600 px-2 py-1 bg-slate-100 rounded border border-slate-200 tracking-tighter uppercase">
                                            {{ $pos->slug_position }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-sm font-medium text-slate-700 group-hover:text-indigo-600 transition-colors">
                                        {{ $pos->name_position }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <button
                                        onclick="openEditPositionModal('{{ $pos->id_ref_position }}', '{{ $pos->name_position }}', '{{ $pos->slug_position }}')"
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

    {{-- 3. Simple Premium Modal --}}
    <div id="editPositionModal" class="fixed inset-0 z-[999] hidden">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeEditPositionModal()"></div>

        {{-- Modal Container --}}
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden transform transition-all shadow-xl" id="posModalContainer">
                <div class="p-8">
                    <div class="mb-6 pb-4 border-b border-slate-100 flex justify-between items-center">
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest">Perbarui Jabatan</h3>
                            <p class="text-[10px] text-slate-400 font-medium uppercase mt-0.5" id="pos_slug_display"></p>
                        </div>
                        <button onclick="closeEditPositionModal()" class="text-slate-300 hover:text-slate-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form id="editPositionForm" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-6">
                            <div class="flex flex-col space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Jabatan Baru</label>
                                <input type="text" id="input_name_position" name="name_position"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded text-slate-700 text-sm font-medium focus:ring-1 focus:ring-slate-400 focus:bg-white outline-none transition-all"
                                    placeholder="Contoh: Staff Akuntansi" required>
                            </div>
                        </div>

                        <div class="mt-8 flex gap-3">
                            <button type="button" onclick="closeEditPositionModal()"
                                class="flex-1 px-4 py-2.5 bg-slate-50 text-slate-500 text-[10px] font-bold uppercase tracking-widest rounded border border-slate-200 hover:bg-slate-100 transition-all">
                                Batal
                            </button>
                            <button type="submit"
                                class="flex-1 px-4 py-2.5 bg-slate-800 text-white text-[10px] font-bold uppercase tracking-widest rounded hover:bg-slate-900 shadow-sm transition-all">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditPositionModal(id, name, slug) {
            const modal = document.getElementById('editPositionModal');
            const container = document.getElementById('posModalContainer');
            const form = document.getElementById('editPositionForm');
            const input = document.getElementById('input_name_position');
            const slugDisplay = document.getElementById('pos_slug_display');

            form.action = `/admin/positions/${id}`;
            input.value = name;
            slugDisplay.innerText = "System Identity: " + slug;

            modal.classList.remove('hidden');
            setTimeout(() => {
                container.style.opacity = "1";
                container.style.transform = "scale(1)";
            }, 10);
        }

        function closeEditPositionModal() {
            const modal = document.getElementById('editPositionModal');
            modal.classList.add('hidden');
        }
    </script>
</x-app-layout>