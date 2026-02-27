    {{-- MODAL IMPORT SPPG --}}
    <div id="importModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeImportModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-4xl overflow-hidden font-sans">
            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <span class="p-1.5 bg-amber-50 text-amber-600 rounded-lg shadow-sm border border-amber-100">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                            <path fill-rule="evenodd" d="M11.47 2.47a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 1 1-1.06 1.06l-3.22-3.22V16.5a.75.75 0 0 1-1.5 0V4.81L8.03 8.03a.75.75 0 0 1-1.06-1.06l4.5-4.5ZM3 15.75a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <h3 class="text-[14px] font-bold uppercase tracking-widest text-slate-700">Import Massal SPPG</h3>
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
                        <a href="{{ route('admin.sppg.template') }}" class="px-4 py-2 bg-indigo-600 text-white text-[10px] font-bold rounded-lg uppercase shadow-md hover:bg-indigo-700 transition-all">Unduh Template</a>
                    </div>

                    <div class="space-y-4">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Pilih File (.xlsx):</label>
                        <input type="file" id="excel_file" accept=".xlsx" onchange="previewExcel()" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-[11px] file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-indigo-50 cursor-pointer">
                    </div>
                </div>

                {{-- Tahap 2: Preview Tabel & Opsi Import --}}
                <div id="import-step-2" class="hidden space-y-6">
                    <div class="overflow-x-auto border border-slate-100 rounded-lg max-h-[300px]">
                        <table class="w-full text-[12px] text-left border-collapse">
                            <thead class="bg-slate-50 sticky top-0 shadow-sm">
                                <tr>
                                    <th class="p-3 border-b font-bold text-slate-500 uppercase">ID SPPG</th>
                                    <th class="p-3 border-b font-bold text-slate-500 uppercase">Nama SPPG</th>
                                    <th class="p-3 border-b font-bold text-slate-500 uppercase">Status</th>
                                    <th class="p-3 border-b font-bold text-slate-500 uppercase">Tgl Operasional</th>
                                    <th class="p-3 border-b font-bold text-slate-500 uppercase">Catatan Sistem</th>
                                </tr>
                            </thead>
                            <tbody id="preview-body">
                                {{-- Baris data diisi via JavaScript --}}
                            </tbody>
                        </table>
                    </div>

                    <div id="summary-text" class="p-3 bg-slate-50 rounded-lg border border-slate-100"></div>

                    {{-- FORM UTAMA: Membungkus Opsi Database & Data JSON --}}
                    <form action="{{ route('admin.sppg.import') }}" method="POST" id="final-import-form">
                        @csrf
                        <div class="space-y-3 mb-6">
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Opsi Database (Wajib Pilih):</p>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="p-4 border rounded-xl cursor-pointer hover:bg-slate-50 flex flex-col gap-1 transition-all border-slate-200 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/30">
                                    <input type="radio" name="import_mode" value="append" checked onchange="revalidatePreview()" class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="text-xs font-bold uppercase text-slate-700">Tambah Data</span>
                                    <span class="text-[9px] text-slate-500 italic">Hanya menambah unit baru. Data dengan ID/Kode SPPG yang sudah ada akan dilewati.</span>
                                </label>

                                <label class="p-4 border rounded-xl cursor-pointer hover:bg-rose-50 flex flex-col gap-1 transition-all border-slate-200 has-[:checked]:border-rose-600 has-[:checked]:bg-rose-50/50">
                                    <input type="radio" name="import_mode" value="replace" onchange="revalidatePreview()" class="text-rose-600 focus:ring-rose-500">
                                    <span class="text-xs font-bold uppercase text-rose-700">Buat Ulang</span>
                                    <span class="text-[9px] text-rose-400 italic font-medium">Hapus & Ganti semua data SPPG.</span>
                                </label>
                            </div>
                        </div>

                        <input type="hidden" name="json_data" id="json_data">

                        <div class="flex gap-3">
                            <button type="button" onclick="resetImport()" class="flex-1 py-4 text-[11px] font-bold uppercase text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all">Ganti File</button>
                            <button type="submit" id="btn-save-import" class="flex-1 py-4 text-[11px] font-bold uppercase text-white bg-indigo-600 rounded-xl shadow-lg hover:bg-indigo-700 transition-all active:scale-[0.98]">Simpan Ke Database</button>
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
                // Reset ke langkah pertama setiap kali dibuka
                resetImport();
                modal.classList.remove('hidden');
            } else {
                console.error("Elemen dengan ID 'importModal' tidak ditemukan!");
            }
        };

        // Fungsi untuk menutup modal Import
        window.closeImportModal = function() {
            const modal = document.getElementById('importModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        };

        // Fungsi Reset (Penting agar tampilan kembali ke awal pilih file)
        window.resetImport = function() {
            const step1 = document.getElementById('import-step-1');
            const step2 = document.getElementById('import-step-2');
            const fileInput = document.getElementById('excel_file');

            if (step1 && step2) {
                step1.classList.remove('hidden');
                step2.classList.add('hidden');
            }
            if (fileInput) fileInput.value = '';
        };

        // Fungsi Revalidate (dipanggil ketika opsi database berubah)
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
            // 1. Ambil elemen yang dibutuhkan
            const fileInput = document.getElementById('excel_file');
            const formatErrorContainer = document.getElementById('format-error-msg');

            // 2. Reset tampilan error setiap kali tombol ditekan
            if (formatErrorContainer) {
                formatErrorContainer.classList.add('hidden');
                formatErrorContainer.innerHTML = '';
            }

            // 3. Cek Library XLSX
            if (typeof XLSX === 'undefined') {
                alert('Library Excel belum siap. Mohon tunggu sebentar atau refresh halaman.');
                return;
            }

            // 4. VALIDASI: Cek jika file tidak ada
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
                    formatErrorContainer.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                }
                return; // BERHENTI DI SINI jika file tidak ada
            }

            // 5. PROSES PEMBACAAN FILE (Hanya jalan jika file ada)
            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, {
                        type: 'array'
                    });

                    const firstSheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[firstSheetName];
                    const jsonData = XLSX.utils.sheet_to_json(worksheet);

                    // Cek jika data kosong
                    if (jsonData.length === 0) {
                        if (formatErrorContainer) {
                            formatErrorContainer.innerHTML = `<div class="p-4 bg-amber-50 text-amber-600 rounded-xl border border-amber-100 text-[11px] font-bold uppercase">File Excel kosong atau tidak memiliki data.</div>`;
                            formatErrorContainer.classList.remove('hidden');
                        }
                        return;
                    }

                    // Panggil fungsi render preview
                    renderPreview(jsonData);

                } catch (error) {
                    console.error("Error membaca Excel:", error);
                    alert('Gagal membaca file. Pastikan file adalah format .xlsx yang benar.');
                }
            };

            reader.readAsArrayBuffer(fileInput.files[0]);
        };

        let currentRenderSppgId = 0;

        async function renderPreview(data) {
            const renderSppgId = ++currentRenderSppgId;
            const tbody = document.getElementById('preview-body');
            const step1 = document.getElementById('import-step-1');
            const step2 = document.getElementById('import-step-2');
            const btnSave = document.getElementById('btn-save-import');
            const formatErrorContainer = document.getElementById('format-error-msg');
            let hasError = false;
            let errorMessage = '';

            tbody.innerHTML = ''; // Clear table

            // Header Validation
            const firstRow = data[0] || {};
            const requiredHeaders = ['ID SPPG', 'NAMA SPPG', 'STATUS (Operasional/Belum Operasional/Tutup Sementara/Tutup Permanen)'];
            
            const missingHeaders = requiredHeaders.filter(header => !(header in firstRow));

            if (missingHeaders.length > 0) {
                 if (formatErrorContainer) {
                    formatErrorContainer.innerHTML = `
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <div>
                                <p class="font-bold uppercase tracking-tight text-[11px]">Format Kolom Tidak Sesuai!</p>
                                <p class="text-[10px] opacity-90 mt-1">Kolom berikut tidak ditemukan di file Anda: <br> <span class="font-bold">${missingHeaders.join(', ')}</span></p>
                                <p class="text-[10px] opacity-90 mt-2 italic">Pastikan Anda menggunakan Template yang diunduh dari tombol "Unduh Template".</p>
                            </div>
                        </div>
                    `;
                    formatErrorContainer.classList.remove('hidden');
                    formatErrorContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                 }
                 return; // Hentikan proses jika header tidak sesuai
            }

            // Render Rows
            let globalHasError = false;
            let countValid = 0;
            let countError = 0;
            
            // Collect ID & Code validation for within-file uniqueness
            const seenIds = new Set();
            const seenCodes = new Set();

            for (let index = 0; index < data.length; index++) {
                if (renderSppgId !== currentRenderSppgId) return; // Batal jika ada render baru

                const row = data[index];
                const tr = document.createElement('tr');

                const id = row['ID SPPG'] ? row['ID SPPG'].substring(0, 15) + (row['ID SPPG'].length > 15 ? '...' : '') : '<span class="text-rose-500 italic">Kosong</span>';
                const name = row['NAMA SPPG'] ? row['NAMA SPPG'].substring(0, 25) + (row['NAMA SPPG'].length > 25 ? '...' : '') : '<span class="text-rose-500 italic">Kosong</span>';
                const status = row['STATUS (Operasional/Belum Operasional/Tutup Sementara/Tutup Permanen)'] || '-';
                const tgl = row['TANGGAL OPERASIONAL (YYYY-MM-DD)'] || '-';
                const rawId = (row['ID SPPG'] || '').toString().trim();
                const rawCode = (row['KODE SPPG'] || '').toString().trim();

                let errors = [];

                if (!rawId) errors.push('ID SPPG kosong');
                if (!row['NAMA SPPG']) errors.push('NAMA SPPG kosong');

                // Validasi Internal (Wajib di semua mode)
                if (rawId && seenIds.has(rawId)) errors.push('ID SPPG ganda di file ini');
                if (rawCode && seenCodes.has(rawCode)) errors.push('KODE SPPG ganda di file ini');

                if (rawId) seenIds.add(rawId);
                if (rawCode) seenCodes.add(rawCode);

                const importModeInput = document.querySelector('input[name="import_mode"]:checked');
                const importMode = importModeInput ? importModeInput.value : 'append';

                // Validasi Eksternal
                if (errors.length === 0 && importMode === 'append') {
                    try {
                        const check = await fetch(`/admin/manage-sppg/check-availability?id_sppg=${encodeURIComponent(rawId)}&code=${encodeURIComponent(rawCode)}`).then(r => r.json());
                        if (renderSppgId !== currentRenderSppgId) return; // Batal jika ada render baru saat menunggu response

                        if (check.id_duplicate) errors.push('ID SPPG sudah terdaftar');
                        if (check.code_duplicate) errors.push('KODE SPPG sudah terdaftar');
                    } catch (e) {
                        errors.push('Gagal memvalidasi ke server');
                    }
                }

                const isRowError = errors.length > 0;
                if (isRowError) {
                    globalHasError = true;
                    countError++;
                } else {
                    countValid++;
                }

                tr.className = `${isRowError ? 'bg-rose-50/50' : 'hover:bg-slate-50'} transition-all text-[12px]`;

                const parseStatus = (s) => {
                    const st = s.toLowerCase();
                    if(st.includes('tutup permanen')) return '<span class="text-xs font-medium px-1.5 py-0.5 rounded border capitalize bg-black/50 text-white border-black/20">Tutup Permanen</span>';
                    if(st.includes('tutup sementara')) return '<span class="text-xs font-medium px-1.5 py-0.5 rounded border capitalize bg-rose-100 text-rose-600 border-rose-200">Tutup Sementara</span>';
                    if(st.includes('belum operasional')) return '<span class="text-xs font-medium px-1.5 py-0.5 rounded border capitalize bg-amber-100 text-amber-600 border-amber-200">Belum Operasional</span>';
                    if(st.includes('operasional')) return '<span class="text-xs font-medium px-1.5 py-0.5 rounded border capitalize bg-[#e0fdef] text-[#047857] border-emerald-200">Operasional</span>';
                    return '<span class="text-xs font-medium px-1.5 py-0.5 rounded border capitalize bg-slate-100 text-slate-500 border-slate-200">-</span>';
                }

                tr.innerHTML = `
                    <td class="p-3 border-b border-slate-100 text-slate-600 font-medium">${id}</td>
                    <td class="p-3 border-b border-slate-100 text-slate-600 font-medium">${name}</td>
                    <td class="p-3 border-b border-slate-100">${parseStatus(status)}</td>
                    <td class="p-3 border-b border-slate-100 text-slate-600">${tgl}</td>
                    <td class="p-3 border-b border-slate-100 text-slate-600">
                        ${isRowError 
                            ? `<div class="flex flex-col gap-0.5">${errors.map(msg => `<div class="flex items-center gap-1.5 text-rose-600 font-bold uppercase text-[9px]"><span>•</span> ${msg}</div>`).join('')}</div>` 
                            : '<div class="flex items-center gap-1.5 text-emerald-600 font-bold uppercase text-[10px]">✓ Valid</div>'
                        }
                    </td>
                `;
                tbody.appendChild(tr);
            }

            // Tampilkan Step 2
            step1.classList.add('hidden');
            step2.classList.remove('hidden');

            // Set Data JSON ke Input Hidden
            document.getElementById('json_data').value = JSON.stringify(data);

            const summary = document.getElementById('summary-text');

            if (globalHasError) {
                btnSave.disabled = true;
                btnSave.className = "flex-1 py-4 text-[11px] font-bold uppercase text-slate-400 bg-slate-100 rounded-xl cursor-not-allowed border border-slate-200";
                summary.innerHTML = `
                    <div class="flex items-center gap-2 text-rose-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span class="text-[11px] font-bold uppercase">Gagal Membaca Detail</span>
                    </div>
                    <p class="text-[11px] text-rose-500 mt-1">Terdapat ${countError} baris data yang error atau tidak valid. Mohon perbaiki.</p>
                `;
            } else {
                btnSave.disabled = false;
                btnSave.className = "flex-1 py-4 text-[11px] font-bold uppercase text-white bg-indigo-600 rounded-xl shadow-lg hover:bg-indigo-700 transition-all active:scale-[0.98]";
                summary.innerHTML = `
                    <div class="flex items-center gap-2 text-indigo-700">
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                         <span class="text-[11px] font-bold uppercase">Siap Diimpor (${countValid} Baris Data Valid)</span>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1">Langkah Terakhir: Pilih "Opsi Database" di bawah ini, lalu klik tombol "Simpan Ke Database".</p>
                `;
            }
        }
</script>
