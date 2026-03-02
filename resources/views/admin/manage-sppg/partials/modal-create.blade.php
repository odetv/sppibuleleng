<div x-show="showCreateModal" class="fixed inset-0 z-[99] flex items-center justify-center p-4 text-left" x-cloak>
    <style>
        .input-disabled {
            background-color: #f8fafc !important;
            color: #94a3b8 !important;
            cursor: not-allowed !important;
            pointer-events: none;
            border: 1px solid #e2e8f0 !important;
        }

        #map-create {
            min-height: 250px;
            width: 100%;
            z-index: 1;
        }

        .cropper-container {
            z-index: 110 !important;
        }

        /* Style input saat error */
        .input-error {
            border: 1px solid #ef4444 !important;
            ring: 2px #fef2f2 !important;
            background-color: #fff1f2 !important;
        }

        .text-error-custom {
            color: #ef4444;
            font-size: 10px;
            font-weight: 700;
            font-style: italic;
            margin-top: 4px;
            display: block;
        }
    </style>

    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showCreateModal = false"></div>

    <div x-show="showCreateModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="relative bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-6xl overflow-hidden transform transition-all font-sans text-sm">

        <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center text-slate-800">
            <div class="flex items-center gap-3">
                <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </span>
                <h3 class="text-[14px] font-bold uppercase tracking-widest" id="modal_header_title">Tambah SPPG</h3>
            </div>
            <button type="button" @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600 text-2xl cursor-pointer">&times;</button>
        </div>

        <form action="{{ route('admin.manage-sppg.store') }}" 
            method="POST" 
            enctype="multipart/form-data" 
            id="createUnitForm"
            @submit.prevent="window.submitCreateSppg($el)">
            @csrf

            {{-- Hidden Inputs Wilayah --}}
            <input type="hidden" name="province_name" id="f_prov_name">
            <input type="hidden" name="regency_name" id="f_reg_name">
            <input type="hidden" name="district_name" id="f_dist_name">
            <input type="hidden" name="village_name" id="f_vill_name">

            <div class="p-8 max-h-[75vh] overflow-y-auto space-y-10 custom-scrollbar" id="modalScrollContainer">

                {{-- SECTION 1: FOTO & IDENTITAS --}}
                <div class="flex flex-col lg:flex-row gap-12">
                    <div class="shrink-0 flex flex-col items-center gap-4">
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Foto Tampak Depan SPPG</label>
                        <div class="relative group">
                            <div id="photo-container" class="h-45 w-60 rounded-2xl overflow-hidden bg-slate-200 border-4 border-white shadow-lg ring-1 ring-slate-100 flex items-center justify-center text-center transition-all">
                                <img id="cropped-preview" class="h-full w-full object-cover hidden cursor-pointer" src="" alt="Preview">
                                <div id="initial-placeholder" class="text-indigo-500 text-6xl">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                            </div>
                            <label for="create_photo" class="absolute bottom-3 right-3 p-2.5 bg-indigo-400 text-white rounded-xl shadow-xl cursor-pointer hover:bg-indigo-500 transition-all hover:scale-110">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <input type="file" id="create_photo" name="photo" class="hidden" accept="image/*">
                            </label>
                        </div>
                        {{-- DIV Error Foto --}}
                        <div id="error-create_photo_container"></div>
                    </div>

                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="md:col-span-2">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama SPPG</label>
                            <input type="text" name="name" id="f_name" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Masukkan Nama SPPG">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">ID SPPG</label>
                            <input type="text" name="id_sppg_unit" id="f_id" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Cth: 6UWFOPNM">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kode SPPG</label>
                            <input type="text" name="code_sppg_unit" id="f_code" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Cth: 51.XX.XX.XXXX.XX">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Status Operasional</label>
                            <select name="status" id="f_status" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="" disabled selected>Pilih Status</option>
                                <option value="Belum Operasional">Belum Operasional</option>
                                <option value="Operasional">Operasional</option>
                                <option value="Tutup Sementara">Tutup Sementara</option>
                                <option value="Tutup Permanen">Tutup Permanen</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tanggal Operasional</label>
                            <input type="date" name="operational_date" id="f_op_date" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm text-slate-600 focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kepala SPPG</label>
                            <select name="leader_id" id="f_leader" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="" disabled selected>Pilih Kepala SPPG</option>
                                <option value="NULL">Belum Ditugaskan</option>
                                @foreach($leaders as $leader)
                                <option value="{{ $leader->id_person }}">{{ $leader->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Ahli Gizi</label>
                            <select name="nutritionist_id" id="f_nutritionist" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="" disabled selected>-- Pilih Ahli Gizi --</option>
                                <option value="NULL">Belum Ditugaskan</option>
                                @foreach($nutritionists as $person)
                                <option value="{{ $person->id_person }}">{{ $person->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Akuntan</label>
                            <select name="accountant_id" id="f_accountant" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="" disabled selected>-- Pilih Akuntan --</option>
                                <option value="NULL">Belum Ditugaskan</option>
                                @foreach($accountants as $person)
                                <option value="{{ $person->id_person }}">{{ $person->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: ALAMAT --}}
                <div class="pt-10 border-t border-gray-100">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Informasi Alamat</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-sm">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Provinsi</label>
                            <select name="province" id="f_prov" class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="">Pilih Provinsi</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kabupaten</label>
                            <select name="regency" id="f_reg" disabled class="input-disabled w-full mt-1 px-4 py-2 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="">Pilih Kabupaten</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kecamatan</label>
                            <select name="district" id="f_dist" disabled class="input-disabled w-full mt-1 px-4 py-2 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Desa/Kelurahan</label>
                            <select name="village" id="f_vill" disabled class="input-disabled w-full mt-1 px-4 py-2 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="">Pilih Desa/Kelurahan</option>
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Alamat Jalan</label>
                            <textarea name="address" id="f_address" rows="3" class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Cth: Jl. Raya Singaraja Denpasar, No. 99"></textarea>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: PETA --}}
                <div class="pt-10 border-t border-gray-100">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Informasi Lokasi GPS (Klik Pada Peta)</h3>
                    <div id="map-create" class="rounded-xl border-4 border-white shadow-lg ring-1 ring-slate-200"></div>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <input type="text" name="latitude_gps" id="f_lat" readonly class="w-full px-4 py-2.5 bg-slate-100 border-none rounded-lg text-sm text-slate-500" placeholder="Latitude Otomatis">
                        <input type="text" name="longitude_gps" id="f_lng" readonly class="w-full px-4 py-2.5 bg-slate-100 border-none rounded-lg text-sm text-slate-500" placeholder="Longitude Otomatis">
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-4">
                <button type="button" @click="showCreateModal = false" class="flex-1 py-4 text-[11px] font-bold uppercase text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition-all">Batal</button>
                <button type="submit" id="btnSubmitSppg" class="flex-1 py-4 text-[11px] font-bold uppercase text-white bg-slate-800 rounded-xl shadow-lg hover:bg-slate-900 transition-all active:scale-95">Simpan SPPG</button>
            </div>
        </form>
    </div>
</div>

<script>
    // 1. Fungsi Membersihkan Semua State Error (Hanya berjalan saat Submit)
    function clearAllErrors() {
        document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));
        document.querySelectorAll('.text-error-custom').forEach(el => {
            el.classList.add('hidden');
            el.innerText = '';
        });
    }

    // 2. Fungsi Menampilkan Error di Bawah Input (Sesuai gaya gambar)
    function showFieldError(id, msg) {
        const el = document.getElementById(id);
        if (!el) return;

        el.classList.add('input-error');

        let errorEl = document.getElementById('error-' + id);
        if (!errorEl) {
            errorEl = document.createElement('span');
            errorEl.id = 'error-' + id;
            errorEl.className = 'text-error-custom';

            // Penempatan khusus untuk foto
            if (id === 'create_photo') {
                document.getElementById('error-create_photo_container').appendChild(errorEl);
            } else {
                el.parentNode.appendChild(errorEl);
            }
        }
        errorEl.innerText = '* ' + msg;
        errorEl.classList.remove('hidden');
    }

    // 2.5 Fungsi membersihkan satu state error spesifik
    function clearFieldError(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('input-error');

        let errorEl = document.getElementById('error-' + id);
        if (errorEl) {
            errorEl.classList.add('hidden');
            errorEl.innerText = '';
        }
    }

    // 3. Logika Submit (PINDAH KE WINDOW FUNCTION)
    window.submitCreateSppg = async function(formElement) {
        clearAllErrors();

        const btnSubmit = document.getElementById('btnSubmitSppg');
        let hasLocalError = false;

        // Validasi Sisi Client
        const fields = [
            { id: 'f_name', msg: 'Nama SPPG wajib diisi' },
            { id: 'f_id', msg: 'ID SPPG wajib diisi' },
            { id: 'f_code', msg: 'Kode SPPG wajib diisi' },
            { id: 'f_leader', msg: 'Pilihan Kepala SPPG tidak valid' },
            { id: 'f_status', msg: 'Pilih status operasional' },
            { id: 'f_prov', msg: 'Provinsi wajib dipilih' },
            { id: 'f_reg', msg: 'Kabupaten wajib dipilih' },
            { id: 'f_dist', msg: 'Kecamatan wajib dipilih' },
            { id: 'f_vill', msg: 'Desa/Kelurahan wajib dipilih' },
            { id: 'f_address', msg: 'Alamat wajib diisi' },
            { id: 'f_lat', msg: 'Titik GPS belum ditentukan (Klik pada peta)' }
        ];

        fields.forEach(f => {
            const el = document.getElementById(f.id);
            if (!el || !el.value || el.value.trim() === "") {
                showFieldError(f.id, f.msg);
                hasLocalError = true;
            }
        });

        if (!document.getElementById('create_photo').files.length) {
            showFieldError('create_photo', 'Foto SPPG wajib ada');
            hasLocalError = true;
        }

        if (hasLocalError) {
            const first = document.querySelector('.input-error');
            if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        if (btnSubmit) {
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = "Sedang Memproses...";
        }

        try {
            const formData = new FormData(formElement);
            const response = await fetch(formElement.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (response.ok) {
                window.location.href = result.redirect || "{{ route('admin.manage-sppg.index') }}";
            } else {
                if (btnSubmit) {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = "Simpan Unit SPPG Baru";
                }

                if (result.errors) {
                    Object.keys(result.errors).forEach(key => {
                        let fieldId = 'f_' + key;
                        if (key === 'id_sppg_unit') fieldId = 'f_id';
                        if (key === 'code_sppg_unit') fieldId = 'f_code';
                        if (key === 'operational_date') fieldId = 'f_op_date';
                        if (key === 'photo') fieldId = 'create_photo';

                        result.errors[key].forEach(msg => {
                            showFieldError(fieldId, msg);
                        });
                    });

                    const firstServerErr = document.querySelector('.input-error');
                    if (firstServerErr) firstServerErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        } catch (err) {
            if (btnSubmit) {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = "Simpan SPPG Baru";
            }
            console.error('Submit Error:', err);
        }
    };
    const occupiedPeople = @json($occupiedPeople ?? []);
    
    function checkSppgPersonnelOccupancy(id, slug) {
        const el = document.getElementById(id);
        if (!el) return;

        const warnElId = id + '-occupancy-warn';
        let warnEl = document.getElementById(warnElId);
        if (!warnEl) {
            warnEl = document.createElement('div');
            warnEl.id = warnElId;
            warnEl.className = 'text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded border border-amber-100 mt-1 hidden';
            el.after(warnEl);
        }

        const personId = el.value;
        if (!personId || personId === 'NULL') {
            warnEl.classList.add('hidden');
            return;
        }
        
        if (occupiedPeople[slug] && occupiedPeople[slug].includes(parseInt(personId))) {
            warnEl.innerHTML = `<i class="fas fa-info-circle mr-1"></i> Terpilih di unit lain. Jika disimpan, ia akan <strong>DIPINDAHKAN</strong> ke unit ini.`;
            warnEl.classList.remove('hidden');
        } else {
            warnEl.classList.add('hidden');
        }
    }

    ['f_leader', 'f_nutritionist', 'f_accountant'].forEach(id => {
        const slug = id === 'f_leader' ? 'kasppg' : (id === 'f_nutritionist' ? 'ag' : 'ak');
        const el = document.getElementById(id);
        if (el) el.addEventListener('change', () => checkSppgPersonnelOccupancy(id, slug));
    });
</script>

<script>
    var cropperInstance = null;
    var originalImageData = null;
    var lastCropData = null;

    // Fungsi untuk menutup modal cropper secara bersih
    function closeCropper() {
        const cropperModal = document.getElementById('cropperModal');
        if (cropperModal) {
            cropperModal.style.display = 'none';
            cropperModal.classList.add('hidden');
        }
        if (cropperInstance) {
            cropperInstance.destroy();
            cropperInstance = null;
        }
    }

    function initCropperLogic() {
        const photoInput = document.getElementById('create_photo');
        const previewImg = document.getElementById('cropped-preview');
        const placeholder = document.getElementById('initial-placeholder');
        const imageToCrop = document.getElementById('image-to-crop');
        const cropperModal = document.getElementById('cropperModal');
        const applyBtn = document.getElementById('apply-crop');
        const cancelBtn = document.getElementById('cancel-crop');
        const closeXBtn = document.getElementById('close-cropper-x'); // Pastikan ID ini ada di tombol X modal cropper

        if (!photoInput) return;

        photoInput.onchange = (e) => {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    originalImageData = event.target.result;
                    lastCropData = null;
                    openCropperModal(originalImageData);
                };
                reader.readAsDataURL(file);
            }
        };

        previewImg.onclick = () => {
            if (originalImageData) openCropperModal(originalImageData);
        };

        function openCropperModal(src) {
            imageToCrop.src = src;
            cropperModal.style.display = 'flex';
            cropperModal.classList.remove('hidden');
            if (cropperInstance) cropperInstance.destroy();

            setTimeout(() => {
                cropperInstance = new Cropper(imageToCrop, {
                    aspectRatio: 4 / 3,
                    viewMode: 2,
                    autoCropArea: 1,
                    ready() {
                        if (lastCropData) cropperInstance.setData(lastCropData);
                    }
                });
            }, 200);
        }

        if (applyBtn) applyBtn.onclick = () => {
            if (!cropperInstance) return;
            lastCropData = cropperInstance.getData();
            const canvas = cropperInstance.getCroppedCanvas({
                width: 800,
                height: 600
            });
            canvas.toBlob((blob) => {
                const croppedFile = new File([blob], `unit_${Date.now()}.jpg`, {
                    type: 'image/jpeg'
                });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(croppedFile);
                photoInput.files = dataTransfer.files;
                previewImg.src = URL.createObjectURL(blob);
                previewImg.classList.remove('hidden');
                placeholder.classList.add('hidden');
                clearFieldError('create_photo');
                closeCropper();
            }, 'image/jpeg', 0.9);
        };

        if (cancelBtn) cancelBtn.onclick = closeCropper;
        if (closeXBtn) closeXBtn.onclick = closeCropper;
    }

    function initCreateMapModal() {
        if (typeof L === 'undefined') return;
        const container = document.getElementById('map-create');
        if (!container) return;

        // Cek jika sudah ada instance, tinggal resize
        if (window.createMapInstance) {
            setTimeout(() => {
                window.createMapInstance.invalidateSize();
            }, 300);
            return;
        }

        const bulelengCoords = [-8.1127, 115.0911];
        window.createMapInstance = L.map('map-create').setView(bulelengCoords, 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(window.createMapInstance);

        window.createMapInstance.on('click', function(e) {
            if (window.createMarkerInstance) {
                window.createMarkerInstance.setLatLng(e.latlng);
            } else {
                window.createMarkerInstance = L.marker(e.latlng, {
                    draggable: true
                }).addTo(window.createMapInstance);
                window.createMarkerInstance.on('dragend', function(ev) {
                    const pos = ev.target.getLatLng();
                    document.getElementById('f_lat').value = pos.lat.toFixed(8);
                    document.getElementById('f_lng').value = pos.lng.toFixed(8);
                });
            }
            document.getElementById('f_lat').value = e.latlng.lat.toFixed(8);
            document.getElementById('f_lng').value = e.latlng.lng.toFixed(8);
            clearFieldError('f_lat');
        });

        setTimeout(() => {
            window.createMapInstance.invalidateSize();
        }, 500);
    }

    (function() {
        const apiBase = "/api-wilayah";
        const sel = {
            p: document.getElementById('f_prov'),
            r: document.getElementById('f_reg'),
            d: document.getElementById('f_dist'),
            v: document.getElementById('f_vill')
        };
        const hid = {
            p: document.getElementById('f_prov_name'),
            r: document.getElementById('f_reg_name'),
            d: document.getElementById('f_dist_name'),
            v: document.getElementById('f_vill_name')
        };

        function setSelectState(el, isDisabled) {
            el.disabled = isDisabled;
            if (isDisabled) {
                el.classList.add('input-disabled');
                el.classList.remove('bg-gray-50');
            } else {
                el.classList.remove('input-disabled');
                el.classList.add('bg-gray-50');
            }
        }

        async function populate(target, path, label) {
            target.innerHTML = '<option value="">Memuat...</option>';
            try {
                const resp = await fetch(`${apiBase}/${path}.json`);
                const json = await resp.json();
                let h = `<option value="">Pilih ${label}</option>`;
                json.data.forEach(i => {
                    const name = i.name.replace(/^(KABUPATEN|KOTA|KAB\.)\s+/i, "").trim();
                    h += `<option value="${i.code}" data-name="${name}">${name}</option>`;
                });
                target.innerHTML = h;
                setSelectState(target, false);
            } catch (e) {
                target.innerHTML = '<option value="">Gagal</option>';
            }
        }

        async function moveMapToLocation() {
            const getTxt = (s) => (s && s.selectedIndex > 0) ? s.options[s.selectedIndex].getAttribute('data-name') : '';
            const query = [getTxt(sel.v), getTxt(sel.d), getTxt(sel.r), getTxt(sel.p)].filter(Boolean).join(', ') + ', Indonesia';
            if (!query.includes(',')) return;
            try {
                const response = await fetch(`/api-map-search?q=${encodeURIComponent(query)}`);
                const data = await response.json();
                if (data?.[0] && window.createMapInstance) {
                    window.createMapInstance.flyTo([data[0].lat, data[0].lon], 14, {
                        animate: true,
                        duration: 1.5
                    });
                }
            } catch (e) {}
        }

        document.addEventListener('DOMContentLoaded', () => {
            populate(sel.p, 'provinces', 'Provinsi');
            initCropperLogic();
            // Panggil init map saat modal alpine terbuka (Opsional tergantung struktur pemanggilan Anda)
            setTimeout(initCreateMapModal, 1000);
        });

        sel.p.onchange = async function() {
            hid.p.value = this.options[this.selectedIndex].getAttribute('data-name') || '';
            sel.r.innerHTML = sel.d.innerHTML = sel.v.innerHTML = '<option value="">Pilih...</option>';
            [sel.r, sel.d, sel.v].forEach(s => setSelectState(s, true));
            if (this.value) await populate(sel.r, `regencies/${this.value}`, 'Kabupaten');
            clearFieldError('f_prov');
            moveMapToLocation();
        };
        sel.r.onchange = async function() {
            hid.r.value = this.options[this.selectedIndex].getAttribute('data-name') || '';
            sel.d.innerHTML = sel.v.innerHTML = '<option value="">Pilih...</option>';
            [sel.d, sel.v].forEach(s => setSelectState(s, true));
            if (this.value) await populate(sel.d, `districts/${this.value}`, 'Kecamatan');
            clearFieldError('f_reg');
            moveMapToLocation();
        };
        sel.d.onchange = async function() {
            hid.d.value = this.options[this.selectedIndex].getAttribute('data-name') || '';
            sel.v.innerHTML = '<option value="">Pilih...</option>';
            setSelectState(sel.v, true);
            if (this.value) await populate(sel.v, `villages/${this.value}`, 'Desa');
            clearFieldError('f_dist');
            moveMapToLocation();
        };
        sel.v.onchange = function() {
            hid.v.value = this.options[this.selectedIndex].getAttribute('data-name') || '';
            clearFieldError('f_vill');
            moveMapToLocation();
        };
    })();

    window.closeAddModal = function() {
        document.getElementById('addModal').classList.add('hidden');
    }
</script>