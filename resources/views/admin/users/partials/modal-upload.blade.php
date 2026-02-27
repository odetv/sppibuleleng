    {{-- MODAL UPLOAD PENGGUNA --}}
    <div id="uploadModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeUploadModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-4xl overflow-hidden font-sans">

            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <span class="p-1.5 bg-amber-50 text-amber-600 rounded-lg shadow-sm border border-amber-100">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                            <path fill-rule="evenodd" d="M11.47 2.47a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 1 1-1.06 1.06l-3.22-3.22V16.5a.75.75 0 0 1-1.5 0V4.81L8.03 8.03a.75.75 0 0 1-1.06-1.06l4.5-4.5ZM3 15.75a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <h3 class="text-[14px] font-bold uppercase tracking-widest text-slate-700">Import Massal Akun Pengguna</h3>
                </div>
                <button type="button" onclick="closeUploadModal()" class="text-slate-400 hover:text-slate-600 text-2xl transition-colors">&times;</button>
            </div>

            <div class="p-8">
                {{-- Tahap 1: Pilih File & Download Template --}}
                <div id="upload-step-1" class="space-y-6">
                    <div id="format-error-msg" class="hidden p-4 bg-rose-50 text-rose-600 border border-rose-200 rounded-xl"></div>

                    <div class="flex items-center justify-between p-4 bg-indigo-50 border border-indigo-100 rounded-xl">
                        <div class="text-[11px] text-indigo-700">
                            <p class="font-bold uppercase">Gunakan Template Standar</p>
                            <p>Pastikan format kolom sesuai agar sistem tidak menolak.</p>
                        </div>
                        <a href="{{ route('admin.users.template') }}" class="px-4 py-2 bg-indigo-600 text-white text-[10px] font-bold rounded-lg uppercase shadow-md hover:bg-indigo-700 transition-all">Unduh Template</a>
                    </div>

                    <div class="space-y-4">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Pilih File (.xlsx):</label>
                        <input type="file" id="excel_file" accept=".xlsx" onchange="previewExcel()" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-[11px] file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-indigo-50 cursor-pointer">
                    </div>
                </div>

                {{-- Tahap 2: Preview Tabel & Opsi Import --}}
                <div id="upload-step-2" class="hidden space-y-6">
                    <div class="overflow-x-auto border border-slate-100 rounded-lg max-h-[300px]">
                        <table class="w-full text-[12px] text-left border-collapse">
                            <thead class="bg-slate-50 sticky top-0 shadow-sm">
                                <tr>
                                    <th class="p-3 border-b font-bold text-slate-500 uppercase">Email</th>
                                    <th class="p-3 border-b font-bold text-slate-500 uppercase">Telepon</th>
                                    <th class="p-3 border-b font-bold text-slate-500 uppercase text-center">Hak Akses Sistem</th>
                                    <th class="p-3 border-b font-bold text-slate-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody id="preview-body">
                                {{-- Baris data diisi via JavaScript --}}
                            </tbody>
                        </table>
                    </div>

                    <div id="summary-text" class="p-3 bg-slate-50 rounded-lg border border-slate-100"></div>

                    {{-- FORM UTAMA: Membungkus Opsi Database & Data JSON --}}
                    <form action="{{ route('admin.users.import') }}" method="POST" id="final-upload-form">
                        @csrf
                        <div class="space-y-3 mb-6">
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Opsi Database (Wajib Pilih):</p>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="p-4 border rounded-xl cursor-pointer hover:bg-slate-50 flex flex-col gap-1 transition-all border-slate-200 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/30">
                                    <input type="radio" name="import_mode" value="append" checked class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="text-xs font-bold uppercase text-slate-700">Tambah Data</span>
                                    <span class="text-[9px] text-slate-500 italic">Hanya menambah pengguna baru dari Excel.</span>
                                </label>

                                <label class="p-4 border rounded-xl cursor-pointer hover:bg-rose-50 flex flex-col gap-1 transition-all border-slate-200 has-[:checked]:border-rose-600 has-[:checked]:bg-rose-50/50">
                                    <input type="radio" name="import_mode" value="replace" class="text-rose-600 focus:ring-rose-500">
                                    <span class="text-xs font-bold uppercase text-rose-700">Buat Ulang</span>
                                    <span class="text-[9px] text-rose-400 italic font-medium">Hapus & Ganti semua data user selain Anda.</span>
                                </label>
                            </div>
                        </div>

                        <input type="hidden" name="json_data" id="json_data">

                        <div class="flex gap-3">
                            <button type="button" onclick="resetUpload()" class="flex-1 py-4 text-[11px] font-bold uppercase text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all">Ganti File</button>
                            <button type="submit" id="btn-save-import" class="flex-1 py-4 text-[11px] font-bold uppercase text-white bg-indigo-600 rounded-xl shadow-lg hover:bg-indigo-700 transition-all active:scale-[0.98]">Simpan Ke Database</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
        // Fungsi untuk membuka modal Upload
        window.openUploadModal = function() {
            const modal = document.getElementById('uploadModal');
            if (modal) {
                // Reset ke langkah pertama setiap kali dibuka
                resetUpload();
                modal.classList.remove('hidden');
            } else {
                console.error("Elemen dengan ID 'uploadModal' tidak ditemukan!");
            }
        };

        // Fungsi untuk menutup modal Upload
        window.closeUploadModal = function() {
            const modal = document.getElementById('uploadModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        };

        // Fungsi Reset (Penting agar tampilan kembali ke awal pilih file)
        window.resetUpload = function() {
            const step1 = document.getElementById('upload-step-1');
            const step2 = document.getElementById('upload-step-2');
            const fileInput = document.getElementById('excel_file');

            if (step1 && step2) {
                step1.classList.remove('hidden');
                step2.classList.add('hidden');
            }
            if (fileInput) fileInput.value = '';
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

                    // Panggil fungsi render preview Anda
                    renderPreview(jsonData);

                } catch (error) {
                    console.error("Error membaca Excel:", error);
                    alert('Gagal membaca file. Pastikan file adalah format .xlsx yang benar.');
                }
            };

            reader.readAsArrayBuffer(fileInput.files[0]);
        };

        async function renderPreview(data) {
            const tbody = document.getElementById('preview-body');
            const step1 = document.getElementById('upload-step-1');
            const step2 = document.getElementById('upload-step-2');
            const btnSave = document.getElementById('btn-save-import');
            const summaryText = document.getElementById('summary-text');

            // Ambil mode import saat ini
            const importModeInput = document.querySelector('input[name="import_mode"]:checked');
            const importMode = importModeInput ? importModeInput.value : 'append';

            // Elemen penampung pesan error format (Gunakan ID ini di HTML nanti)
            const formatErrorContainer = document.getElementById('format-error-msg');
            if (formatErrorContainer) formatErrorContainer.classList.add('hidden');

            const roleKey = 'HAK AKSES SISTEM (Administrator/Author/Editor/Subscriber/Guest)';

            // 1. Validasi Struktur Kolom (Wajib sesuai template)
            const requiredColumns = [
                'EMAIL PENGGUNA',
                'NOMOR WHATSAPP',
                roleKey
            ];

            if (data.length > 0) {
                const firstRow = data[0];
                const missing = requiredColumns.filter(col => !(col in firstRow));

                if (missing.length > 0) {
                    // Tampilkan pesan error jika kolom tidak cocok
                    if (formatErrorContainer) {
                        formatErrorContainer.innerHTML = `<p class="font-bold text-[11px]">Kolom tidak cocok: ${missing.join(', ')}</p>`;
                        formatErrorContainer.classList.remove('hidden');
                    }
                    return;
                }
            }

            // Role valid sesuai database Anda
            const validRoles = ['administrator', 'author', 'editor', 'subscriber', 'guest'];

            tbody.innerHTML = '<tr><td colspan="4" class="p-8 text-center"><span class="animate-pulse text-slate-400 italic text-[11px]">Memvalidasi baris data...</span></td></tr>';

            step1.classList.add('hidden');
            step2.classList.remove('hidden');

            let html = '';
            let globalHasError = false;
            let countValid = 0;
            let countError = 0;

            const seenEmails = new Set();
            const seenPhones = new Set();

            for (const item of data) {
                const email = (item['EMAIL PENGGUNA'] || '').toString().trim();
                const phone = (item['NOMOR WHATSAPP'] || '').toString().trim();
                const rawRole = (item[roleKey] || '').toString().toLowerCase().trim();
                let errors = [];

                if (!email) errors.push('Email kosong');
                else if (!email.includes('@')) errors.push('Format email salah');
                if (!phone) errors.push('WhatsApp kosong');

                if (!rawRole) errors.push('Hak akses belum diisi');
                else if (!validRoles.includes(rawRole)) errors.push(`Role "${rawRole}" tidak valid`);

                // Validasi Internal (Wajib di semua mode)
                if (email && seenEmails.has(email)) errors.push('Email ganda di file ini');
                if (phone && seenPhones.has(phone)) errors.push('WhatsApp ganda di file ini');

                if (email) seenEmails.add(email);
                if (phone) seenPhones.add(phone);

                // Validasi Eksternal (Hanya jika mode "Tambah Data")
                if (errors.length === 0 && importMode === 'append') {
                    try {
                        const check = await fetch(`/admin/users/check-availability?email=${email}&phone=${phone}`).then(r => r.json());
                        if (check.email_duplicate) errors.push('Email sudah ada di sistem');
                        if (check.phone_duplicate) errors.push('WhatsApp sudah ada di sistem');
                    } catch (e) {
                        errors.push('Koneksi server gagal');
                    }
                }

                const isRowError = errors.length > 0;
                if (isRowError) {
                    globalHasError = true;
                    countError++;
                } else {
                    countValid++;
                }

                html += `
            <tr class="${isRowError ? 'bg-rose-50/50' : 'hover:bg-slate-50'} transition-all text-[12px]">
                <td class="p-3 border-b border-slate-100 text-slate-600 font-medium">${email || '-'}</td>
                <td class="p-3 border-b border-slate-100 text-slate-600 font-medium">${phone || '-'}</td>
                <td class="p-3 border-b border-slate-100 text-center font-bold text-slate-500 uppercase">${rawRole || '-'}</td>
                <td class="p-3 border-b border-slate-100">
                    ${isRowError 
                        ? `<div class="flex flex-col gap-0.5">${errors.map(msg => `<div class="flex items-center gap-1.5 text-rose-600 font-bold uppercase text-[9px]"><span>•</span> ${msg}</div>`).join('')}</div>` 
                        : '<div class="flex items-center gap-1.5 text-emerald-600 font-bold uppercase text-[10px]">✓ Siap Import</div>'
                    }
                </td>
            </tr>
        `;
            }

            tbody.innerHTML = html;
            document.getElementById('json_data').value = JSON.stringify(data);

            summaryText.innerHTML = `
        <div class="flex gap-3 items-center font-bold text-[10px] uppercase tracking-wider">
            <span class="bg-slate-100 text-slate-600 px-3 py-1.5 rounded-lg border border-slate-200">Total: ${data.length}</span>
            <span class="bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-lg border border-emerald-200">Valid: ${countValid}</span>
            <span class="bg-rose-100 text-rose-700 px-3 py-1.5 rounded-lg border border-rose-200">Bermasalah: ${countError}</span>
            <span class="ml-auto text-indigo-600">Mode: ${importMode === 'replace' ? 'Buat Ulang' : 'Tambah Data'}</span>
        </div>
    `;

            btnSave.disabled = (globalHasError || data.length === 0);
            btnSave.classList.toggle('opacity-50', btnSave.disabled);
            btnSave.classList.toggle('cursor-not-allowed', btnSave.disabled);
        }

        // Tambahkan ini di bagian DOMContentLoaded atau bawah script Anda
        document.querySelectorAll('input[name="import_mode"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Jika sudah ada file yang dipilih, jalankan ulang pratinjau
                const fileInput = document.getElementById('excel_file');
                if (fileInput.files.length > 0) {
                    previewExcel();
                }
            });
        });

        // Fungsi saat tombol "Simpan Ke Database" di klik
        document.addEventListener('DOMContentLoaded', function() {
            const finalForm = document.getElementById('final-upload-form');

            if (finalForm) {
                finalForm.addEventListener('submit', function(e) {
                    e.preventDefault(); // Stop form agar tidak langsung kirim

                    // Ambil mode yang dipilih (append/replace)
                    const modeInput = document.querySelector('input[name="import_mode"]:checked');
                    const importMode = modeInput ? modeInput.value : 'append';

                    if (importMode === 'replace') {
                        // Jika pilih buat ulang, tampilkan modal konfirmasi merah
                        document.getElementById('confirmReplaceModal').classList.remove('hidden');
                    } else {
                        // Jika tambah data, langsung eksekusi submit
                        this.submit();
                    }
                });
            }
        });

        // Fungsi untuk menutup modal konfirmasi
        window.closeConfirmModal = function() {
            document.getElementById('confirmReplaceModal').classList.add('hidden');
        }

        // Fungsi eksekusi final dari dalam modal konfirmasi
        window.finalSubmitImport = function() {
            const form = document.getElementById('final-upload-form');
            if (form) {
                // Berikan feedback visual pada tombol
                const btn = document.getElementById('btn-save-import');
                btn.disabled = true;
                btn.innerText = 'MEMPROSES PEMBERSIHAN DATA...';

                form.submit();
            }
        }

</script>
