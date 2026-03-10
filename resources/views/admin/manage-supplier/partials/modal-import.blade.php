    {{-- MODAL IMPORT SUPPLIER --}}
    <div id="importModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeImportModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-5xl overflow-hidden font-sans">
            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center text-slate-800">
                <div class="flex items-center gap-3">
                    <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg shadow-sm border border-indigo-100">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                            <path fill-rule="evenodd" d="M11.47 2.47a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 1 1-1.06 1.06l-3.22-3.22V16.5a.75.75 0 0 1-1.5 0V4.81L8.03 8.03a.75.75 0 0 1-1.06-1.06l4.5-4.5ZM3 15.75a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <h3 class="text-[14px] font-bold uppercase tracking-widest">Import Massal Supplier</h3>
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
                        <a href="{{ route('admin.manage-supplier.template') }}" class="px-4 py-2 bg-indigo-600 text-white text-[10px] font-bold rounded-lg uppercase shadow-md hover:bg-indigo-700 transition-all">Unduh Template</a>
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
                            <div class="flex items-center gap-2">
                                <div>
                                    Tampilkan
                                    <select onchange="changeImportPerPage(this)" class="text-[11px] text-slate-500 font-medium px-3 py-1 mx-1 border border-slate-200 rounded-md bg-white text-slate-700 outline-none focus:border-indigo-500">
                                        <option value="5" selected>5</option>
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    Baris
                                </div>
                                <div id="import_pagination_info" class="text-[9px] text-slate-400 font-medium normal-case ml-2 border-l border-slate-200 pl-3">Menampilkan 0 dari 0 data</div>
                            </div>
                        </div>

                        <div class="overflow-x-auto border border-slate-100 rounded-lg max-h-[300px]">
                            <table class="w-full text-[12px] text-left border-collapse">
                                <thead class="bg-slate-50 sticky top-0 shadow-sm">
                                    <tr>
                                        <th class="p-3 border-b font-bold text-slate-500 uppercase">Nama Supplier</th>
                                        <th class="p-3 border-b font-bold text-slate-500 uppercase">Jenis</th>
                                        <th class="p-3 border-b font-bold text-slate-500 uppercase">Pimpinan</th>
                                        <th class="p-3 border-b font-bold text-slate-500 uppercase">Telepon</th>
                                        <th class="p-3 border-b font-bold text-slate-500 uppercase">SPPG</th>
                                        <th class="p-3 border-b font-bold text-slate-500 uppercase">Catatan Sistem</th>
                                    </tr>
                                </thead>
                                <tbody id="preview-body">
                                    {{-- Baris data diisi via JavaScript --}}
                                </tbody>
                            </table>
                        </div>

                        <div class="flex justify-end mt-2">
                            <div id="import_pagination_controls" class="flex gap-1">
                                {{-- Pagination buttons dipanggil via JS --}}
                            </div>
                        </div>
                    </div>

                    <div id="summary-text" class="p-3 bg-slate-50 rounded-lg border border-slate-100"></div>

                    <form action="{{ route('admin.manage-supplier.import') }}" method="POST" id="final-import-form">
                        @csrf
                        <div class="space-y-3 mb-6" id="import_db_options_container">
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Opsi Database (Wajib Pilih):</p>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="p-4 border rounded-xl cursor-pointer hover:bg-slate-50 flex flex-col gap-1 transition-all border-slate-200 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/30">
                                    <input type="radio" name="import_mode" value="append" checked onchange="revalidatePreview()" class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="text-xs font-bold uppercase text-slate-700">Tambah Data</span>
                                    <span class="text-[9px] text-slate-500 italic">Hanya menambah supplier baru. Data dengan nama yang sama akan dilewati.</span>
                                </label>

                                <label class="p-4 border rounded-xl cursor-pointer hover:bg-rose-50 flex flex-col gap-1 transition-all border-slate-200 has-[:checked]:border-rose-600 has-[:checked]:bg-rose-50/50">
                                    <input type="radio" name="import_mode" value="replace" onchange="revalidatePreview()" class="text-rose-600 focus:ring-rose-500">
                                    <span class="text-xs font-bold uppercase text-rose-700">Buat Ulang</span>
                                    <span class="text-[9px] text-rose-400 italic font-medium">Hapus & Ganti semua data supplier.</span>
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
                            
                            <p id="import_progress_desc" class="text-[10px] text-slate-400 font-medium text-center italic mt-2">Menyimpan data supplier ke dalam sistem. Mohon bersabar.</p>

                            <div id="import_error_container" class="hidden w-full mt-4 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs overflow-y-auto max-h-48 leading-relaxed"></div>

                            <button type="button" id="btn_import_finish" onclick="window.location.reload()" class="hidden w-full mt-2 py-4 text-[11px] font-bold uppercase text-white bg-slate-800 rounded-xl shadow-lg hover:bg-slate-900 transition-all active:scale-[0.98]">Tutup & Muat Ulang Halaman</button>
                        </div>
                    </form>
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
            }
        };

        // Fungsi untuk menutup modal Import
        window.closeImportModal = function() {
            const modal = document.getElementById('importModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        };

        // Fungsi Reset
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
            document.querySelectorAll('input[name="import_mode"]').forEach(el => el.disabled = false);
            
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

            if (!fileInput || !fileInput.files[0]) {
                if (formatErrorContainer) {
                    formatErrorContainer.innerHTML = `
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <div>
                                <p class="font-bold uppercase tracking-tight text-[11px]">File Excel tidak ditemukan!</p>
                                <p class="text-[10px] opacity-90">Pilih file Excel (.xlsx) terlebih dahulu.</p>
                            </div>
                        </div>
                    `;
                    formatErrorContainer.classList.remove('hidden');
                }
                return;
            }

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

                    renderPreview(jsonData);
                } catch (error) {
                    console.error("Error membaca Excel:", error);
                    alert('Gagal membaca file. Pastikan file adalah format .xlsx yang benar.');
                }
            };
            reader.readAsArrayBuffer(fileInput.files[0]);
        };

        let currentRenderId = 0;
        let processedRowsHTML = [];
        let importCurrentPage = 1;
        let importPerPage = 5;

        async function renderPreview(data) {
            const renderId = ++currentRenderId;
            const tbody = document.getElementById('preview-body');
            const step1 = document.getElementById('import-step-1');
            const step2 = document.getElementById('import-step-2');
            const btnSave = document.getElementById('btn-save-import');
            const formatErrorContainer = document.getElementById('format-error-msg');

            tbody.innerHTML = '';

            // Header Validation
            const firstRow = data[0] || {};
            const requiredHeaders = [
                'JENIS SUPPLIER (Koperasi Desa Merah Putih/Koperasi/Bumdes/Bumdesma/UMKM/Supplier Lain)',
                'NAMA SUPPLIER',
                'NAMA PIMPINAN',
                'TELEPON (Hanya Angka)',
                'KOMODITAS (Contoh: Beras, Sayuran, Daging)',
                'PROVINSI',
                'KABUPATEN',
                'KECAMATAN',
                'DESA/KELURAHAN',
                'ALAMAT JALAN',
                'KODE POS',
                'ID UNIT SPPG TERKAIT (Pisahkan dengan koma jika banyak)'
            ];
            
            const missingHeaders = requiredHeaders.filter(header => !(header in firstRow));

            if (missingHeaders.length > 0) {
                 if (formatErrorContainer) {
                    formatErrorContainer.innerHTML = `
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <div>
                                <p class="font-bold uppercase tracking-tight text-[11px]">Format Kolom Tidak Sesuai!</p>
                                <p class="text-[10px] opacity-90 mt-1">Kolom berikut tidak ditemukan di file Anda: <br> <span class="font-bold">${missingHeaders.join(', ')}</span></p>
                            </div>
                        </div>
                    `;
                    formatErrorContainer.classList.remove('hidden');
                 }
                 return;
            }

            processedRowsHTML = [];
            importCurrentPage = 1;

            let globalHasError = false;
            let countValid = 0;
            let countError = 0;
            
            const seenNames = new Set();

            for (let index = 0; index < data.length; index++) {
                if (renderId !== currentRenderId) return;

                const row = data[index];
                const type = (row['JENIS SUPPLIER (Koperasi Desa Merah Putih/Koperasi/Bumdes/Bumdesma/UMKM/Supplier Lain)'] || '').toString();
                const name = (row['NAMA SUPPLIER'] || '').toString().trim();
                const leader = (row['NAMA PIMPINAN'] || '').toString();
                const phone = (row['TELEPON (Hanya Angka)'] || '').toString();
                const sppgIds = (row['ID UNIT SPPG TERKAIT (Pisahkan dengan koma jika banyak)'] || '').toString();

                let errors = [];

                if (!name) errors.push('NAMA SUPPLIER kosong');
                if (name && seenNames.has(name)) errors.push('NAMA SUPPLIER ganda di file ini');
                if (name) seenNames.add(name);

                const importModeInput = document.querySelector('input[name="import_mode"]:checked');
                const importMode = importModeInput ? importModeInput.value : 'append';

                if (errors.length === 0 && importMode === 'append') {
                    try {
                        const check = await fetch(`/admin/manage-supplier/check-availability?name=${encodeURIComponent(name)}`).then(r => r.json());
                        if (renderId !== currentRenderId) return;
                        if (check.duplicate) errors.push('NAMA sudah terdaftar');
                    } catch (e) {
                        errors.push('Gagal memvalidasi ke server');
                    }
                }

                // New: SPPG ID Validation
                if (sppgIds) {
                    const ids = sppgIds.split(',').map(id => id.trim()).filter(id => id !== '');
                    const invalidIds = ids.filter(id => !window.validSppgIds.includes(id));
                    if (invalidIds.length > 0) {
                        errors.push(`ID SPPG Tidak Valid: ${invalidIds.join(', ')}`);
                    }
                }

                const isRowError = errors.length > 0;
                if (isRowError) {
                    globalHasError = true;
                    countError++;
                } else {
                    countValid++;
                }

                processedRowsHTML.push(`
                <tr class="${isRowError ? 'bg-rose-50/50' : 'hover:bg-slate-50'} transition-all text-[12px]">
                    <td class="p-3 border-b border-slate-100 text-slate-600 font-bold">${name || '-'}</td>
                    <td class="p-3 border-b border-slate-100 text-slate-600 font-medium">${type || '-'}</td>
                    <td class="p-3 border-b border-slate-100 text-slate-600 font-medium">${leader || '-'}</td>
                    <td class="p-3 border-b border-slate-100 text-slate-600">${phone || '-'}</td>
                    <td class="p-3 border-b border-slate-100 text-slate-400 font-bold tracking-tight">${sppgIds || '-'}</td>
                    <td class="p-3 border-b border-slate-100 text-slate-600">
                        ${isRowError 
                            ? `<div class="flex flex-col gap-0.5">${errors.map(msg => `<div class="flex items-center gap-1.5 text-rose-600 font-bold uppercase text-[9px]"><span>•</span> ${msg}</div>`).join('')}</div>` 
                            : '<div class="flex items-center gap-1.5 text-emerald-600 font-bold uppercase text-[10px]">✓ Valid</div>'
                        }
                    </td>
                </tr>
                `);

                // Update preview periodically for better UX
                if (index < 5 || index % 10 === 0) {
                    renderTablePage();
                }
            }

            step1.classList.add('hidden');
            step2.classList.remove('hidden');
            document.getElementById('import_table_container').classList.remove('hidden');
            document.getElementById('summary-text').classList.remove('hidden');
            document.querySelectorAll('input[name="import_mode"]').forEach(el => el.disabled = false);

            document.getElementById('json_data').value = JSON.stringify(data);
            renderTablePage();

            const summary = document.getElementById('summary-text');
            if (globalHasError) {
                btnSave.disabled = true;
                btnSave.className = "flex-1 py-4 text-[11px] font-bold uppercase text-slate-400 bg-slate-100 rounded-xl cursor-not-allowed border border-slate-200";
                summary.innerHTML = `<p class="text-[11px] text-rose-500 font-bold uppercase">Terdapat ${countError} baris data yang error. Mohon perbaiki.</p>`;
            } else {
                btnSave.disabled = false;
                btnSave.className = "flex-1 py-4 text-[11px] font-bold uppercase text-white bg-slate-800 rounded-xl shadow-lg hover:bg-slate-900 transition-all active:scale-[0.98]";
                summary.innerHTML = `<p class="text-[11px] text-indigo-700 font-bold uppercase">Siap Diimpor (${countValid} Baris Data Valid)</p>`;
            }
        }

        window.renderTablePage = function() {
            const tbody = document.getElementById('preview-body');
            const startIndex = (importCurrentPage - 1) * importPerPage;
            const endIndex = startIndex + importPerPage;
            const pageRows = processedRowsHTML.slice(startIndex, endIndex);

            tbody.innerHTML = pageRows.join('');
            
            const info = document.getElementById('import_pagination_info');
            const total = processedRowsHTML.length;
            const startDisplay = total === 0 ? 0 : startIndex + 1;
            const endDisplay = Math.min(endIndex, total);
            
            if (info) info.innerText = `Menampilkan ${startDisplay} - ${endDisplay} dari ${total} data`;
            renderPaginationControls();
        };

        window.changeImportPage = function(page) {
            importCurrentPage = page;
            renderTablePage();
        };

        window.changeImportPerPage = function(select) {
            importPerPage = parseInt(select.value);
            importCurrentPage = 1;
            renderTablePage();
        };

        window.renderPaginationControls = function() {
            const container = document.getElementById('import_pagination_controls');
            if (!container) return;
            const totalPages = Math.ceil(processedRowsHTML.length / importPerPage);
            
            if (totalPages <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = '';
            
            // Prev Button
            const prevDisabled = importCurrentPage === 1;
            html += `<button type="button" onclick="changeImportPage(${importCurrentPage - 1})" class="px-3 py-1 bg-white border border-slate-200 shadow-sm rounded-md text-slate-500 font-bold hover:bg-slate-50 text-[10px] uppercase transition-all ${prevDisabled ? 'opacity-50 cursor-not-allowed' : ''}" ${prevDisabled ? 'disabled' : ''}>&laquo; Prev</button>`;
            
            for (let i = 1; i <= totalPages; i++) {
                if (totalPages > 7) {
                    if (i !== 1 && i !== totalPages && Math.abs(i - importCurrentPage) > 1) {
                        if (i === 2 && importCurrentPage > 3) html += `<span class="px-2 py-1 text-slate-400">...</span>`;
                        if (i === totalPages - 1 && importCurrentPage < totalPages - 2) html += `<span class="px-2 py-1 text-slate-400">...</span>`;
                        continue;
                    }
                }
                const activeCls = i === importCurrentPage ? 'bg-indigo-600 text-white border-indigo-600 shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50';
                html += `<button type="button" onclick="changeImportPage(${i})" class="px-3 py-1 border rounded-md font-bold text-[10px] transition-all ${activeCls}">${i}</button>`;
            }
            
            // Next Button
            const nextDisabled = importCurrentPage === totalPages;
            html += `<button type="button" onclick="changeImportPage(${importCurrentPage + 1})" class="px-3 py-1 bg-white border border-slate-200 shadow-sm rounded-md text-slate-500 font-bold hover:bg-slate-50 text-[10px] uppercase transition-all ${nextDisabled ? 'opacity-50 cursor-not-allowed' : ''}" ${nextDisabled ? 'disabled' : ''}>Next &raquo;</button>`;
            
            container.innerHTML = html;
        };

        document.addEventListener('DOMContentLoaded', function() {
            const finalForm = document.getElementById('final-import-form');
            if (finalForm) {
                finalForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    executeAjaxImport(this);
                });
            }
        });

        function executeAjaxImport(form) {
            document.getElementById('import_action_buttons').classList.add('hidden');
            document.getElementById('import_user_progress').classList.remove('hidden');
            document.getElementById('import_table_container').classList.add('hidden');
            document.getElementById('summary-text').classList.add('hidden');
            document.getElementById('import_db_options_container').classList.add('hidden');
            
            let progress = 0;
            const bar = document.getElementById('import_progress_bar_fill');
            const statusText = document.getElementById('import_progress_text');
            const descText = document.getElementById('import_progress_desc');
            const spinner = document.getElementById('import_spinner');
            const finishIcon = document.getElementById('import_finish_icon');
            const warningIcon = document.getElementById('import_warning_icon');
            const errorContainer = document.getElementById('import_error_container');
            const btnFinish = document.getElementById('btn_import_finish');

            bar.style.width = '0%';
            bar.className = 'bg-emerald-500 h-2.5 rounded-full transition-all duration-300 ease-out';
            spinner.classList.remove('hidden');
            finishIcon.classList.add('hidden');
            warningIcon.classList.add('hidden');
            errorContainer.classList.add('hidden');
            errorContainer.innerHTML = '';
            btnFinish.classList.add('hidden');
            descText.classList.remove('hidden');

            const interval = setInterval(() => {
                if (progress >= 90) {
                    clearInterval(interval);
                    statusText.innerText = "Menyiapkan Database...";
                } else {
                    progress += 10;
                    bar.style.width = progress + '%';
                }
            }, 500);

            const formData = new FormData(form);
            document.querySelectorAll('input[name="import_mode"]').forEach(el => el.disabled = true);
            
            fetch(form.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                body: formData
            })
            .then(response => response.json())
            .then(res => {
                clearInterval(interval);
                bar.style.width = '100%';
                
                if (res.success) {
                    if (res.errorDetails && res.errorDetails.length > 0) {
                        spinner.classList.add('hidden');
                        warningIcon.classList.remove('hidden');
                        bar.classList.replace('bg-emerald-500', 'bg-rose-500');
                        statusText.innerText = "Selesai Dengan Catatan!";
                        let errorHtml = `<div class="mb-3">${res.message}</div><ul class="list-disc list-inside space-y-1">`;
                        res.errorDetails.forEach(err => { errorHtml += `<li>${err}</li>`; });
                        errorHtml += `</ul>`;
                        errorContainer.innerHTML = errorHtml;
                        errorContainer.classList.remove('hidden');
                        btnFinish.classList.remove('hidden');
                    } else {
                        spinner.classList.add('hidden');
                        finishIcon.classList.remove('hidden');
                        statusText.innerText = res.message || "Berhasil Diimpor!";
                        setTimeout(() => { window.location.reload(); }, 2000);
                    }
                } else {
                    showCriticalError(res.message || "Gagal mengimpor file data.");
                }
            })
            .catch(error => {
                clearInterval(interval);
                showCriticalError("Terjadi kesalahan jaringan.");
            });
        }

        function showCriticalError(messageStr) {
            const bar = document.getElementById('import_progress_bar_fill');
            const spinner = document.getElementById('import_spinner');
            const warningIcon = document.getElementById('import_warning_icon');
            const errorContainer = document.getElementById('import_error_container');
            const btnFinish = document.getElementById('btn_import_finish');
            const statusText = document.getElementById('import_progress_text');

            bar.style.width = '100%';
            bar.classList.replace('bg-emerald-500', 'bg-rose-500');
            spinner.classList.add('hidden');
            warningIcon.classList.remove('hidden');
            statusText.innerText = "Gagal Sistem!";
            errorContainer.innerHTML = `<div>${messageStr}</div>`;
            errorContainer.classList.remove('hidden');
            btnFinish.classList.remove('hidden');
        }
</script>
