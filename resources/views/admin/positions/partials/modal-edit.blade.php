    {{-- 4. EDIT MODAL (Style Seragam Manajemen Pengguna) --}}
    <div id="editPositionModal" class="fixed inset-0 z-[99] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeEditPositionModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-lg overflow-hidden transform transition-all font-sans">
            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center text-slate-800 font-bold uppercase tracking-widest text-[14px]">
                <div>
                    <h3>Perbarui Jabatan</h3>
                    <p class="text-[10px] text-indigo-500 mt-1" id="pos_slug_display"></p>
                </div>
                <button type="button" onclick="closeEditPositionModal()" class="text-slate-400 hover:text-slate-600 text-2xl cursor-pointer">&times;</button>
            </div>

            <form id="editPositionForm" method="POST">
                @csrf
                @method('PATCH')

                <div class="p-8">
                    <label class="text-[11px] font-bold uppercase text-slate-500 block mb-2 tracking-widest">Nama Tampilan Jabatan</label>
                    <input type="text" id="input_name_position" name="name_position"
                        class="w-full px-4 py-3 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm transition-all font-sans"
                        required placeholder="Contoh: Staff Akuntansi">
                </div>

                <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-4">
                    <button type="button" onclick="closeEditPositionModal()"
                        class="flex-1 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 cursor-pointer transition-all font-sans">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 py-4 text-[11px] font-bold uppercase tracking-wider text-white bg-slate-800 rounded-xl shadow-lg hover:bg-slate-900 cursor-pointer transition-all active:scale-[0.98] font-sans">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditPositionModal(id, name, slug) {
            const modal = document.getElementById('editPositionModal');
            const form = document.getElementById('editPositionForm');
            const input = document.getElementById('input_name_position');
            const slugDisplay = document.getElementById('pos_slug_display');

            form.action = `/admin/positions/${id}`;
            input.value = name;
            slugDisplay.innerText = "Slug: " + slug;

            modal.classList.remove('hidden');
        }

        function closeEditPositionModal() {
            document.getElementById('editPositionModal').classList.add('hidden');
        }
    </script>
