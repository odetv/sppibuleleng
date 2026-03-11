    {{-- MODAL EXPORT SUPPLIER --}}
    <div id="exportModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeExportModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-4xl overflow-hidden transform transition-all font-sans text-[13px]">

            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center text-slate-800">
                <div class="flex items-center gap-3">
                    <span class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                            <path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <h3 class="font-bold uppercase tracking-widest">Export Data Supplier</h3>
                </div>
                <button type="button" onclick="closeExportModal()" class="text-slate-400 hover:text-slate-600 text-2xl duration-200">&times;</button>
            </div>

            <form action="{{ route('admin.manage-supplier.export') }}" method="POST" id="formExportExcel">
                @csrf
                <div class="p-8 max-h-[70vh] overflow-y-auto custom-scrollbar">
                    <div class="flex justify-between items-center mb-6">
                        <p class="text-[12px] font-bold text-slate-500 uppercase tracking-widest">Pilih Kolom Data Untuk File Excel</p>
                        <div class="flex gap-4">
                            <button type="button" onclick="toggleAllCheckboxes(true)" class="text-[11px] font-semibold text-indigo-600 hover:underline uppercase">Pilih Semua</button>
                            <button type="button" onclick="toggleAllCheckboxes(false)" class="text-[11px] font-semibold text-rose-600 hover:underline uppercase">Hapus Semua</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-4">
                        @php
                        $columns = [
                            'type_supplier' => 'JENIS SUPPLIER',
                            'name_supplier' => 'NAMA SUPPLIER',
                            'leader_name'   => 'NAMA PIMPINAN',
                            'phone'         => 'TELEPON',
                            'commodities'   => 'KOMODITAS',
                            'province'      => 'PROVINSI',
                            'regency'       => 'KABUPATEN',
                            'district'      => 'KECAMATAN',
                            'village'       => 'DESA/KELURAHAN',
                            'address'       => 'ALAMAT JALAN',
                            'postal_code'   => 'KODE POS',
                            'latitude_gps'  => 'LATITUDE GPS',
                            'longitude_gps' => 'LONGITUDE GPS',
                            'linked_sppg'   => 'UNIT SPPG TERKAIT',
                        ];
                        @endphp

                        @foreach($columns as $key => $label)
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="columns[]" value="{{ $key }}" checked
                                class="export-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-[13px] text-slate-600 group-hover:text-indigo-600 leading-tight">
                                {{ $label }}
                            </span>
                        </label>
                        @endforeach
                    </div>

                    <div id="no-column-warning" class="mt-6 p-3 bg-rose-50 text-rose-600 rounded-lg border border-rose-100 hidden flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-[11px] font-semibold uppercase italic">Pilih minimal satu kolom!</span>
                    </div>
                </div>

                <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-3">
                    <button type="button" onclick="closeExportModal()" class="flex-1 py-4 text-[11px] font-semibold uppercase tracking-wider text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all">Batal</button>
                    <button type="button" id="btn-submit-export" onclick="submitAndClose()" class="flex-1 py-4 text-[11px] font-semibold uppercase tracking-wider text-white bg-emerald-600 rounded-xl shadow-lg hover:bg-emerald-700 transition-all active:scale-[0.98]">Export (.xlsx)</button>
                </div>
            </form>
        </div>
    </div>

<script>
        window.openExportModal = function() {
            toggleAllCheckboxes(true);
            document.getElementById('exportModal').classList.remove('hidden');
        }

        window.closeExportModal = function() {
            document.getElementById('exportModal').classList.add('hidden');
        };

        window.submitAndClose = function() {
            const form = document.getElementById('formExportExcel');
            const checkboxes = document.querySelectorAll('.export-checkbox:checked');

            if (checkboxes.length === 0) {
                alert('Silakan pilih minimal satu kolom untuk diexport!');
                return;
            }

            form.submit();
            setTimeout(() => { closeExportModal(); }, 500);
        };

        window.toggleAllCheckboxes = function(status) {
            const checkboxes = document.querySelectorAll('.export-checkbox');
            checkboxes.forEach((checkbox) => { checkbox.checked = status; });
            validateExportSelection();
        }

        function validateExportSelection() {
            const checkboxes = document.querySelectorAll('.export-checkbox:checked');
            const btnSubmit = document.getElementById('btn-submit-export');
            const warning = document.getElementById('no-column-warning');

            if (checkboxes.length === 0) {
                btnSubmit.disabled = true;
                btnSubmit.classList.add('opacity-50', 'cursor-not-allowed', 'grayscale');
                warning.classList.remove('hidden');
            } else {
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed', 'grayscale');
                warning.classList.add('hidden');
            }
        }

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('export-checkbox')) {
                validateExportSelection();
            }
        });
</script>
