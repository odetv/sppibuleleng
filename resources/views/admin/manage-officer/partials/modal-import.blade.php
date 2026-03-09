    {{-- MODAL IMPORT PETUGAS --}}
    <div id="importModal" class="fixed inset-0 z-100 hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeImportModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-4xl overflow-hidden font-sans">

            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg shadow-sm border border-indigo-100">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                            <path fill-rule="evenodd" d="M11.47 2.47a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 1 1-1.06 1.06l-3.22-3.22V16.5a.75.75 0 0 1-1.5 0V4.81L8.03 8.03a.75.75 0 0 1-1.06-1.06l4.5-4.5ZM3 15.75a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <h3 class="text-[14px] font-bold uppercase tracking-widest text-slate-700">Import Massal Data Petugas</h3>
                </div>
                <button type="button" onclick="closeImportModal()" class="text-slate-400 hover:text-slate-600 text-2xl transition-colors">&times;</button>
            </div>

            <div class="p-8">
                {{-- Tahap 1: Pilih File & Export Template --}}
                <div id="import-step-1" class="space-y-6">
                    <div id="format-error-msg" class="hidden p-4 bg-rose-50 text-rose-600 border border-rose-200 rounded-xl"></div>

                    <div class="flex items-center justify-between p-4 bg-indigo-50 border border-indigo-100 rounded-xl">
                        <div class="text-[11px] text-indigo-700">
                            <p class="font-bold uppercase">Gunakan Template Standar</p>
                            <p>Pastikan format kolom sesuai agar sistem tidak menolak.</p>
                        </div>
                        <a href="{{ route('admin.manage-officer.template') }}" class="px-4 py-2 bg-indigo-600 text-white text-[10px] font-bold rounded-lg uppercase shadow-md hover:bg-indigo-700 transition-all">Unduh Template</a>
                    </div>

                    <div class="space-y-4">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Pilih File (.xlsx):</label>
                        <input type="file" id="excel_file" accept=".xlsx" onchange="previewExcel()" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-[11px] file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-indigo-50 cursor-pointer">
                    </div>
                </div>

                {{-- Tahap 2: Preview Tabel & Opsi Import --}}
                <div id="import-step-2" class="hidden space-y-6">
                    <div id="import_table_container" class="space-y-4">
                        <div class="flex justify-between items-center text-[11px] text-slate-500 font-medium">
                            <div>
                                Tampilkan
                                <select onchange="changeImportPerPage(this)" class="text-[11px] font-medium px-3 py-1 mx-1 border border-slate-200 rounded-md bg-white text-slate-700 outline-none focus:border-indigo-500">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                </select>
                                Baris
                            </div>
                        </div>

                        <div class="overflow-x-auto border border-slate-100 rounded-lg max-h-[300px]">
                            <table class="w-full text-[12px] text-left border-collapse">
                                <thead class="bg-slate-50 sticky top-0 shadow-sm">
                                    <tr>
                                        <th class="p-3 border-b font-bold text-slate-500 uppercase">NIK</th>
                                        <th class="p-3 border-b font-bold text-slate-500 uppercase">Nama</th>
                                        <th class="p-3 border-b font-bold text-slate-500 uppercase">Jabatan/Unit</th>
                                        <th class="p-3 border-b font-bold text-slate-500 uppercase">Catatan Sistem</th>
                                    </tr>
                                </thead>
                                <tbody id="preview-body">
                                    {{-- Baris data diisi via JavaScript --}}
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="flex justify-between items-center mt-2">
                            <div id="import_pagination_info" class="text-[10px] text-slate-500 font-medium">Menampilkan 0 dari 0 data</div>
                            <div id="import_pagination_controls" class="flex justify-end gap-1">
                                {{-- Pagination buttons dipanggil via JS --}}
                            </div>
                        </div>
                    </div>

                    <div id="summary-text" class="p-3 bg-slate-50 rounded-lg border border-slate-100"></div>

                    {{-- FORM UTAMA: Membungkus Opsi Database & Data JSON --}}
                    <form action="{{ route('admin.manage-officer.import') }}" method="POST" id="final-import-form">
                        @csrf
                        <div class="space-y-3 mb-6" id="import_db_options_container">
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Opsi Database (Wajib Pilih):</p>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="p-4 border rounded-xl cursor-pointer hover:bg-slate-50 flex flex-col gap-1 transition-all border-slate-200 has-checked:border-indigo-600 has-checked:bg-indigo-50/30">
                                    <input type="radio" name="import_mode" value="append" checked onchange="revalidatePreview()" class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="text-xs font-bold uppercase text-slate-700">Tambah Data</span>
                                    <span class="text-[9px] text-slate-500 italic">Menambah atau memperbarui data petugas.</span>
                                </label>

                                <label class="p-4 border rounded-xl cursor-pointer hover:bg-rose-50 flex flex-col gap-1 transition-all border-slate-200 has-checked:border-rose-600 has-checked:bg-rose-50/50">
                                    <input type="radio" name="import_mode" value="replace" onchange="revalidatePreview()" class="text-rose-600 focus:ring-rose-500">
                                    <span class="text-xs font-bold uppercase text-rose-700">Buat Ulang</span>
                                    <span class="text-[9px] text-rose-400 italic font-medium">Hapus & Ganti semua data petugas (kecuali Anda).</span>
                                </label>
                            </div>
                        </div>

                        <input type="hidden" name="json_data" id="json_data">

                        <div class="flex gap-3" id="import_action_buttons">
                            <button type="button" onclick="resetImport()" class="flex-1 py-4 text-[11px] font-bold uppercase text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all">Ganti File</button>
                            <button type="submit" id="btn-save-import" class="flex-1 py-4 text-[11px] font-bold uppercase text-white bg-slate-800 rounded-xl shadow-lg hover:bg-slate-900 transition-all active:scale-[0.98]">Simpan Ke Database</button>
                        </div>

                        {{-- PROGRESS BAR UI --}}
                        <div id="import_user_progress" class="hidden py-6 flex flex-col justify-center items-center space-y-4">
                            {{-- Animations / Icons --}}
                            <div id="import_spinner" class="w-16 h-16 rounded-full border-4 border-slate-100 border-t-emerald-500 animate-spin mb-2"></div>
                            
                            <div id="import_finish_icon" class="hidden w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mb-2">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            
                            <div id="import_warning_icon" class="hidden w-16 h-16 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mb-2">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>

                            <h4 id="import_progress_text" class="text-sm font-bold text-slate-700 uppercase tracking-widest text-center">Menyiapkan Data...</h4>
                            
                            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                              <div id="import_progress_bar_fill" class="bg-emerald-500 h-2.5 rounded-full transition-all duration-300 ease-out" style="width: 0%"></div>
                            </div>
                            
                            <p id="import_progress_desc" class="text-[10px] text-slate-400 font-medium text-center italic mt-2">Mohon bersabar. Sistem sedang mengimpor data petugas.</p>

                            {{-- Error Container (Muncul di Bawah Progress Bar) --}}
                            <div id="import_error_container" class="hidden w-full mt-4 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs overflow-y-auto max-h-48 leading-relaxed"></div>

                            {{-- Tombol Tutup/Muat Ulang (Muncul Paling Bawah) --}}
                            <button type="button" id="btn_import_finish" onclick="window.location.reload()" class="hidden w-full mt-2 py-4 text-[11px] font-bold uppercase text-white bg-slate-800 rounded-xl shadow-lg hover:bg-slate-900 transition-all active:scale-[0.98]">Tutup & Muat Ulang Halaman</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL KONFIRMASI REPLACE --}}
    <div id="confirmReplaceModal" class="fixed inset-0 z-110 hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeConfirmModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl border border-rose-100 w-full max-w-md overflow-hidden font-sans transform transition-all">
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-6 border border-rose-100">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 uppercase tracking-tight mb-2">Konfirmasi Hapus Total?</h3>
                <p class="text-[13px] text-slate-500 leading-relaxed mb-8 px-4">Anda memilih opsi <span class="font-bold text-rose-600 italic">"Buat Ulang"</span>. Ini akan menghapus seluruh data petugas yang ada secara permanen dan menggantinya dengan data baru dari file ini.</p>
                <div class="flex flex-col gap-3">
                    <button type="button" onclick="finalSubmitImport()" class="w-full py-4 bg-rose-600 text-white text-[11px] font-bold rounded-xl uppercase tracking-widest hover:bg-rose-700 shadow-lg hover:shadow-rose-200 transition-all active:scale-[0.98]">Ya, Hapus & Import Baru</button>
                    <button type="button" onclick="closeConfirmModal()" class="w-full py-4 text-[11px] font-bold uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">Batalkan</button>
                </div>
            </div>
        </div>
    </div>

<script>
    // Fungsi untuk membuka modal Import
    window.openImportModal = function() {
        const modal = document.getElementById('importModal');
        if (modal) {
            resetImport();
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    };

    window.closeImportModal = function() {
        const modal = document.getElementById('importModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    };

    window.resetImport = function() {
        const step1 = document.getElementById('import-step-1');
        const step2 = document.getElementById('import-step-2');
        const fileInput = document.getElementById('excel_file');

        if (step1 && step2) {
            step1.classList.remove('hidden');
            step2.classList.add('hidden');
        }
        if (fileInput) fileInput.value = '';

        document.getElementById('import_action_buttons').classList.remove('hidden');
        document.getElementById('import_user_progress').classList.add('hidden');
        document.getElementById('import_finish_icon').classList.add('hidden');
        document.getElementById('import_warning_icon').classList.add('hidden');
        document.getElementById('import_spinner').classList.remove('hidden');
        document.getElementById('import_error_container').classList.add('hidden');
        document.getElementById('btn_import_finish').classList.add('hidden');
        document.getElementById('import_progress_desc').classList.remove('hidden');
        document.getElementById('import_db_options_container').classList.remove('hidden');
        
        const btnSave = document.getElementById('btn-save-import');
        if (btnSave) {
            btnSave.disabled = false;
            btnSave.innerText = "Simpan Ke Database";
        }
    };

    window.revalidatePreview = function() {
        const jsonDataInput = document.getElementById('json_data');
        if (jsonDataInput && jsonDataInput.value) {
            try {
                const data = JSON.parse(jsonDataInput.value);
                if (data && data.length > 0) {
                    renderPreview(data);
                }
            } catch (e) {
                console.error('Failed to parse json_data for revalidation', e);
            }
        }
    };

    window.previewExcel = function() {
        const fileInput = document.getElementById('excel_file');
        const formatErrorContainer = document.getElementById('format-error-msg');

        if (formatErrorContainer) {
            formatErrorContainer.classList.add('hidden');
            formatErrorContainer.innerHTML = '';
        }

        if (typeof XLSX === 'undefined') {
            alert('Library Excel belum siap. Mohon tunggu sebentar atau refresh halaman.');
            return;
        }

        if (!fileInput || !fileInput.files[0]) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            try {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, { type: 'array' });
                const firstSheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[firstSheetName];
                const jsonData = XLSX.utils.sheet_to_json(worksheet);

                if (jsonData.length === 0) {
                    if (formatErrorContainer) {
                        formatErrorContainer.innerHTML = `<div class="p-4 bg-amber-50 text-amber-600 rounded-xl border border-amber-100 text-[11px] font-bold uppercase">File Excel kosong atau tidak memiliki data.</div>`;
                        formatErrorContainer.classList.remove('hidden');
                    }
                    return;
                }
                
                const firstRow = jsonData[0] || {};
                const headersInFile = Object.keys(firstRow);
                
                const requiredHeaders = [
                    'HAK AKSES SISTEM (Administrator/Editor/Author/Subscriber/Guest)',
                    'NIK',
                    'NO. KK',
                    'NAMA LENGKAP',
                    'JENIS KELAMIN (L/P)',
                    'TEMPAT LAHIR',
                    'TGL LAHIR (DD-MM-YYYY)',
                    'AGAMA (Islam/Kristen/Katolik/Hindu/Budha/Konghucu)',
                    'STATUS PERNIKAHAN (Belum Kawin/Kawin/Janda/Duda)',
                    'TELEPON',
                    'JABATAN', // We check start-with for this one due to dynamic examples
                    'UNIT SPPG',
                    'HONOR PER HARI',
                    'STATUS KEAKTIFAN (Aktif/Non-Aktif)',
                    'NO. BPJS KESEHATAN',
                    'NO. BPJS KETENAGAKERJAAN',
                    'PROVINSI (KTP)',
                    'KABUPATEN (KTP)',
                    'KECAMATAN (KTP)',
                    'DESA/KELURAHAN (KTP)',
                    'ALAMAT JALAN (KTP)',
                    'PROVINSI (DOMISILI)',
                    'KABUPATEN (DOMISILI)',
                    'KECAMATAN (DOMISILI)',
                    'DESA/KELURAHAN (DOMISILI)',
                    'ALAMAT JALAN (DOMISILI)'
                ];

                const missingHeaders = [];
                requiredHeaders.forEach(req => {
                    const found = headersInFile.some(h => {
                        if (req === 'JABATAN') return h.startsWith('JABATAN');
                        return h === req;
                    });
                    if (!found) missingHeaders.push(req);
                });

                if (missingHeaders.length > 0) {
                    if (formatErrorContainer) {
                        formatErrorContainer.innerHTML = `
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <div>
                                    <p class="font-bold uppercase tracking-tight text-[11px] text-rose-600">Format Kolom Tidak Sesuai!</p>
                                    <p class="text-[10px] text-rose-500 mt-1">Kolom berikut tidak ditemukan atau penamaannya salah: <br> <span class="font-bold text-rose-700">${missingHeaders.join(', ')}</span></p>
                                    <p class="text-[10px] text-slate-500 mt-2 italic">Gunakan file template yang diunduh dari tombol "Unduh Template" di atas.</p>
                                </div>
                            </div>
                        `;
                        formatErrorContainer.classList.remove('hidden');
                    }
                    return;
                }

                // Panggil fungsi render preview
                renderPreview(jsonData);
            } catch (error) {
                console.error("Error membaca Excel:", error);
            }
        };
        reader.readAsArrayBuffer(fileInput.files[0]);
    };

    let currentRenderId = 0;
    let processedRowsHTML = [];
    let importCurrentPage = 1;
    let importPerPage = 5;

    function isValidDate(dateString) {
        // First check for the pattern DD-MM-YYYY
        const regex = /^(\d{2})-(\d{2})-(\d{4})$/;
        const match = dateString.match(regex);

        if (!match) return false;

        const day = parseInt(match[1], 10);
        const month = parseInt(match[2], 10);
        const year = parseInt(match[3], 10);

        // Check the ranges of month and year
        if (year < 1000 || year > 3000 || month == 0 || month > 12) return false;

        const monthLength = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];

        // Adjust for leap years
        if (year % 400 == 0 || (year % 100 != 0 && year % 4 == 0)) monthLength[1] = 29;

        // Check the range of the day
        return day > 0 && day <= monthLength[month - 1];
    }

    async function renderPreview(data) {
        const renderId = ++currentRenderId;
        const tbody = document.getElementById('preview-body');
        const step1 = document.getElementById('import-step-1');
        const step2 = document.getElementById('import-step-2');
        const btnSave = document.getElementById('btn-save-import');
        const summaryText = document.getElementById('summary-text');
        const formatErrorContainer = document.getElementById('format-error-msg');

        // Required columns for validation
        const requiredColumnsStarts = [
            'HAK AKSES SISTEM',
            'NIK',
            'NO. KK',
            'NAMA LENGKAP',
            'JENIS KELAMIN (L/P)',
            'TEMPAT LAHIR',
            'TGL LAHIR (DD-MM-YYYY)',
            'AGAMA',
            'STATUS PERNIKAHAN',
            'TELEPON',
            'JABATAN',
            'UNIT SPPG'
        ];

        if (data.length > 0) {
            const firstRow = data[0];
            const keys = Object.keys(firstRow);
            const missing = requiredColumnsStarts.filter(colStart => !keys.some(k => k.startsWith(colStart)));
            if (missing.length > 0) {
                if (formatErrorContainer) {
                    formatErrorContainer.innerHTML = `
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <div>
                                <p class="font-bold uppercase tracking-tight text-[11px] text-rose-600">Format Kolom Tidak Sesuai!</p>
                                <p class="text-[10px] text-rose-500 mt-1">Kolom hilang atau berbeda judul: <span class="font-bold text-rose-700">${missing.join(', ')}</span></p>
                                <p class="text-[10px] text-slate-500 mt-2 italic">Gunakan file template yang diunduh dari tombol "Unduh Template" di atas.</p>
                            </div>
                        </div>
                    `;
                    formatErrorContainer.classList.remove('hidden');
                }
                return;
            }
        }

        tbody.innerHTML = '<tr><td colspan="4" class="p-8 text-center text-slate-400 italic text-[11px]">Memvalidasi data...</td></tr>';
        
        // UI Loading state
        btnSave.disabled = true;
        btnSave.className = "flex-1 py-4 text-[11px] font-bold uppercase text-slate-400 bg-slate-100 rounded-xl cursor-not-allowed border border-slate-200";
        summaryText.innerHTML = `
            <div class="flex items-center gap-2 text-slate-600">
                <div class="w-4 h-4 border-2 border-slate-300 border-t-slate-600 rounded-full animate-spin"></div>
                <span class="text-[11px] font-bold uppercase tracking-wider">Mengecek Data & Master Data...</span>
            </div>
        `;

        processedRowsHTML = [];
        importCurrentPage = 1;

        let countValid = 0;
        let countError = 0;
        const seenNiks = new Set();
        const seenPhones = new Set();
        
        const mode = document.querySelector('input[name="import_mode"]:checked').value;

        // Fetch master data once
        const master = await fetch(`/admin/manage-officer/check-availability?nik=none&phone=none`).then(r => r.json());

        for (const row of data) {
            if (renderId !== currentRenderId) return;
            let errors = [];
            const keys = Object.keys(row);
            const getVal = (start) => {
                const k = keys.find(key => key.startsWith(start));
                return (row[k] || '').toString().trim();
            };

            const nik = getVal('NIK');
            const name = getVal('NAMA LENGKAP');
            const phone = getVal('TELEPON') || getVal('NOMOR WHATSAPP');
            const pos = getVal('JABATAN');
            const unit = getVal('UNIT SPPG');
            const roleFull = getVal('HAK AKSES SISTEM');
            const role = roleFull.toLowerCase();
            const birthVal = getVal('TGL LAHIR');
            const honor = getVal('HONOR PER HARI');

            // 1. Mandatory & Format Validation
            if (!nik || nik.length !== 16) errors.push('NIK wajib 16 digit');
            if (!name) errors.push('Nama wajib diisi');
            if (!phone) errors.push('Telepon wajib diisi');
            if (!pos) errors.push('Jabatan wajib diisi');
            if (!unit) errors.push('Unit wajib diisi');
            if (!role) errors.push('Role wajib diisi');
            
            if (birthVal && !isValidDate(birthVal)) {
                errors.push('Format Tanggal Lahir harus (DD-MM-YYYY)');
            }
            
            if (honor && !/^\d+$/.test(honor)) {
                errors.push('Format Honor hanya angka (tanpa titik/koma)');
            }

            // 2. Cross-row Uniqueness
            if (nik && seenNiks.has(nik)) errors.push('NIK ganda di file ini');
            if (phone && seenPhones.has(phone)) errors.push('Nomor Telepon ganda di file ini');
            
            if (nik) seenNiks.add(nik);
            if (phone) seenPhones.add(phone);

            // 3. Database Check & Master Data Sync
            if (errors.length === 0) {
                try {
                    const check = await fetch(`/admin/manage-officer/check-availability?nik=${nik}&phone=${phone}`).then(r => r.json());
                    
                    if (mode === 'append') {
                        if (check.nik_duplicate) errors.push('NIK sudah ada di database');
                        if (check.phone_duplicate) errors.push('Nomor Telepon sudah ada di database');
                    }
                    
                    if (role && !master.valid_roles.includes(role)) errors.push(`Role "${role}" tidak valid`);
                    
                    const unitExists = check.valid_units.includes(unit);
                    const posExists = check.valid_positions.includes(pos);
                    
                    if (!unitExists) errors.push(`Unit "${unit}" tidak terdaftar`);
                    if (!posExists) errors.push(`Jabatan "${pos}" tidak terdaftar`);

                    // 4. Core Position & SK Check
                    if (unitExists && posExists && mode === 'append') {
                        const slug = check.pos_slugs[pos];
                        const coreMapping = { 'kasppg': 'kasppg', 'ag': 'ag', 'ak': 'ak' };
                        
                        if (slug && coreMapping[slug]) {
                            // Check Occupancy
                            const isOccupied = check.occupied_cores[unit] && check.occupied_cores[unit][coreMapping[slug]];
                            if (isOccupied) {
                                errors.push(`Unit "${unit}" sudah memiliki ${pos} aktif.`);
                            }
                            
                            // Check SK Existence
                            const hasSk = check.sk_mapping[unit] && check.sk_mapping[unit][coreMapping[slug]];
                            if (!hasSk) {
                                errors.push(`Unit "${unit}" belum memiliki SK untuk ${pos}.`);
                            }
                        }
                    }
                } catch (e) {
                    errors.push('Gagal validasi ke server');
                }
            }

            const isErr = errors.length > 0;
            if (isErr) countError++; else countValid++;

            processedRowsHTML.push(`
                <tr class="${isErr ? 'bg-rose-50/50' : 'hover:bg-slate-50'} transition-all text-[11px]">
                    <td class="p-3 border-b border-slate-100">${nik || '<span class="text-rose-400 italic">Kosong</span>'}</td>
                    <td class="p-3 border-b border-slate-100 font-bold text-slate-700">${name || '-'}</td>
                    <td class="p-3 border-b border-slate-100 uppercase text-slate-500">${pos || '-'} / ${unit || '-'}</td>
                    <td class="p-3 border-b border-slate-100">
                        ${isErr 
                            ? `<div class="flex flex-col gap-0.5">${errors.map(e => `<div class="text-rose-600 font-bold uppercase text-[9px]">• ${e}</div>`).join('')}</div>` 
                            : '<div class="flex items-center gap-1.5 text-emerald-600 font-bold uppercase text-[10px]">✓ VALID</div>'}
                    </td>
                </tr>
            `);
        }

        // Finalize Summary & Step Visibility
        if (countError > 0) {
            btnSave.disabled = true;
            btnSave.className = "flex-1 py-4 text-[11px] font-bold uppercase text-slate-400 bg-slate-100 rounded-xl cursor-not-allowed border border-slate-200";
            summaryText.innerHTML = `
                <div class="flex items-center gap-2 text-rose-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span class="text-[11px] font-bold uppercase">Validasi Gagal!</span>
                </div>
                <p class="text-[11px] text-rose-500 mt-1">Terdapat ${countError} baris data yang bermasalah. Silakan perbaiki file Excel Anda.</p>
            `;
        } else {
            btnSave.disabled = false;
            btnSave.className = "flex-1 py-4 text-[11px] font-bold uppercase text-white bg-slate-800 rounded-xl shadow-lg hover:bg-slate-900 transition-all active:scale-[0.98]";
            summaryText.innerHTML = `
                <div class="flex items-center gap-2 text-indigo-700">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                     <span class="text-[11px] font-bold uppercase">Data Valid (${countValid} Baris)</span>
                </div>
                <p class="text-[11px] text-slate-500 mt-1">Pilih "Opsi Database" di bawah ini, lalu klik "Simpan Ke Database".</p>
            `;
        }

        step1.classList.add('hidden');
        step2.classList.remove('hidden');
        document.getElementById('json_data').value = JSON.stringify(data);
        renderTablePage();
    }

    window.renderTablePage = function() {
        const tbody = document.getElementById('preview-body');
        const start = (importCurrentPage - 1) * importPerPage;
        tbody.innerHTML = processedRowsHTML.slice(start, start + importPerPage).join('');
        document.getElementById('import_pagination_info').innerText = `Menampilkan ${Math.min(start + 1, processedRowsHTML.length)} - ${Math.min(start + importPerPage, processedRowsHTML.length)} dari ${processedRowsHTML.length} data`;
        renderPagination();
    };

    window.changeImportPage = function(p) { importCurrentPage = p; renderTablePage(); };
    window.changeImportPerPage = function(s) { importPerPage = parseInt(s.value); importCurrentPage = 1; renderTablePage(); };

    function renderPagination() {
        const container = document.getElementById('import_pagination_controls');
        const totalPages = Math.ceil(processedRowsHTML.length / importPerPage);
        let html = '';
        for (let i = 1; i <= totalPages; i++) {
            const act = i === importCurrentPage ? 'bg-indigo-600 text-white' : 'bg-white text-slate-600';
            html += `<button type="button" onclick="changeImportPage(${i})" class="px-2 py-1 border rounded text-[10px] font-bold ${act}">${i}</button>`;
        }
        container.innerHTML = html;
    }

    document.getElementById('final-import-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const mode = document.querySelector('input[name="import_mode"]:checked').value;
        if (mode === 'replace') {
            const confirmModal = document.getElementById('confirmReplaceModal');
            confirmModal.classList.remove('hidden');
            confirmModal.classList.add('flex');
        } else {
            executeImport(this);
        }
    });

    window.closeConfirmModal = function() { 
        const confirmModal = document.getElementById('confirmReplaceModal');
        confirmModal.classList.add('hidden'); 
        confirmModal.classList.remove('flex');
    };
    window.finalSubmitImport = function() { closeConfirmModal(); executeImport(document.getElementById('final-import-form')); };

    function executeImport(form) {
        document.getElementById('import_action_buttons').classList.add('hidden');
        const progressContainer = document.getElementById('import_user_progress');
        progressContainer.classList.remove('hidden');
        progressContainer.classList.add('flex');
        document.getElementById('import_db_options_container').classList.add('hidden');
        document.getElementById('import_table_container').classList.add('hidden');
        document.getElementById('summary-text').classList.add('hidden');

        const bar = document.getElementById('import_progress_bar_fill');
        const statusText = document.getElementById('import_progress_text');
        const finishIcon = document.getElementById('import_finish_icon');
        const warningIcon = document.getElementById('import_warning_icon');
        const errorContainer = document.getElementById('import_error_container');
        const btnFinish = document.getElementById('btn_import_finish');

        let progress = 0;
        const interval = setInterval(() => {
            progress = Math.min(progress + 5, 95);
            bar.style.width = progress + '%';
            if (progress > 30) statusText.innerText = "Memproses Data...";
            if (progress > 70) statusText.innerText = "Sinkronisasi SPPG...";
        }, 500);

        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            clearInterval(interval);
            bar.style.width = '100%';
            document.getElementById('import_spinner').classList.add('hidden');
            
            if (res.status === 200) {
                if (res.body.errorDetails && res.body.errorDetails.length > 0) {
                    warningIcon.classList.remove('hidden');
                    warningIcon.classList.add('flex');
                    bar.classList.replace('bg-emerald-500', 'bg-rose-500');
                    statusText.innerText = "Selesai dengan Catatan";
                    errorContainer.innerHTML = `<ul class="list-disc pl-4">${res.body.errorDetails.map(e => `<li>${e}</li>`).join('')}</ul>`;
                    errorContainer.classList.remove('hidden');
                    btnFinish.classList.remove('hidden');
                } else if (res.body.success) {
                    finishIcon.classList.remove('hidden');
                    finishIcon.classList.add('flex');
                    statusText.innerText = res.body.message || "Berhasil Diimpor!";
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    showCriticalError(res.body.message || "Gagal mengimpor data.");
                }
            } else {
                showCriticalError(res.body.message || "Gagal mengimpor data.");
            }
        })
        .catch(err => {
            clearInterval(interval);
            showCriticalError("Koneksi terputus atau server lambat merespon.");
        });
    }

    function showCriticalError(msg) {
        const bar = document.getElementById('import_progress_bar_fill');
        const statusText = document.getElementById('import_progress_text');
        const warningIcon = document.getElementById('import_warning_icon');
        const errorContainer = document.getElementById('import_error_container');
        const btnFinish = document.getElementById('btn_import_finish');

        bar.style.width = '100%';
        bar.classList.replace('bg-emerald-500', 'bg-rose-500');
        document.getElementById('import_spinner').classList.add('hidden');
        warningIcon.classList.remove('hidden');
        warningIcon.classList.add('flex');
        statusText.innerText = "Gagal Import!";
        errorContainer.innerHTML = `<div>${msg}</div>`;
        errorContainer.classList.remove('hidden');
        btnFinish.classList.remove('hidden');
    }
</script>
