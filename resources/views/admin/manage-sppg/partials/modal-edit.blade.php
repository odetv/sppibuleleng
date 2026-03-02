<div x-show="showEditModal" class="fixed inset-0 z-[99] flex items-center justify-center p-4 text-left" x-cloak>
    <style>
        .input-disabled {
            background-color: #f8fafc !important;
            color: #94a3b8 !important;
            cursor: not-allowed !important;
            pointer-events: none;
            border: 1px solid #e2e8f0 !important;
        }

        #map-edit {
            height: 350px;
            width: 100%;
            border-radius: 0.75rem;
            margin-top: 0.5rem;
            z-index: 1;
            background: #f8fafc;
        }

        /* Style input saat error */
        .input-error-edit {
            border: 1px solid #ef4444 !important;
            ring: 2px #fef2f2 !important;
            background-color: #fff1f2 !important;
        }

        .error-warning-edit {
            color: #ef4444;
            font-size: 10px;
            font-weight: 700;
            font-style: italic;
            margin-top: 4px;
            display: block;
        }
    </style>

    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showEditModal = false"></div>

    <div x-show="showEditModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="relative bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-6xl overflow-hidden transform transition-all font-sans text-sm">

        <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center text-slate-800">
            <div class="flex items-center gap-3">
                <span class="p-1.5 bg-amber-50 text-amber-600 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M18.364 4.982a2.378 2.378 0 113.359 3.359L10.852 19.531l-4.243.606.606-4.243L18.364 4.982z" />
                    </svg>
                </span>
                <h3 class="text-[14px] font-bold uppercase tracking-widest">Edit SPPG: <span x-text="selectedUnit.name"></span></h3>
            </div>
            <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 text-2xl cursor-pointer">&times;</button>
        </div>

        <form action="#"
            method="POST"
            enctype="multipart/form-data"
            id="editUnitForm"
            @submit.prevent="window.submitUpdateSppg($el)">
            @csrf
            @method('PATCH')

            {{-- Hidden Inputs Wilayah (Akan diisi oleh syncEditWilayah) --}}
            <input type="hidden" name="province_name" id="e_prov_name">
            <input type="hidden" name="regency_name" id="e_reg_name">
            <input type="hidden" name="district_name" id="e_dist_name">
            <input type="hidden" name="village_name" id="e_vill_name">

            <div class="p-8 max-h-[75vh] overflow-y-auto space-y-10 custom-scrollbar" id="editModalScrollContainer">

                {{-- SECTION 1: FOTO & IDENTITAS --}}
                <div class="flex flex-col lg:flex-row gap-12">
                    <div class="shrink-0 flex flex-col items-center gap-4">
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Foto Tampak Depan SPPG</label>
                        <div class="relative group">
                            <div id="edit-photo-container" class="h-45 w-60 rounded-2xl overflow-hidden bg-slate-200 border-4 border-white shadow-lg ring-1 ring-slate-100 flex items-center justify-center text-center transition-all">
                                <img id="edit-cropped-preview" class="h-full w-full object-cover cursor-pointer"
                                    :src="selectedUnit.photo ? '/storage/' + selectedUnit.photo : ''"
                                    :class="selectedUnit.photo ? '' : 'hidden'">
                                <div id="edit-initial-placeholder" :class="selectedUnit.photo ? 'hidden' : 'text-indigo-500 text-6xl'">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                            </div>
                            <label for="edit_photo" class="absolute bottom-3 right-3 p-2.5 bg-amber-400 text-white rounded-xl shadow-xl cursor-pointer hover:bg-amber-500 transition-all hover:scale-110">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <input type="file" id="edit_photo" name="photo" class="hidden" accept="image/*">
                            </label>
                        </div>
                        {{-- DIV Error Foto --}}
                        <div id="error-edit_photo_container"></div>
                    </div>

                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="md:col-span-2">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama SPPG</label>
                            <input type="text" name="name" id="e_name" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Masukkan Nama SPPG">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">ID SPPG</label>
                            <input type="text" name="id_sppg_unit" id="e_id" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Cth: 6UWFOPNM">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kode SPPG</label>
                            <input type="text" name="code_sppg_unit" id="e_code" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Cth: 51.XX.XX.XXXX.XX">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Status Operasional</label>
                            <select name="status" id="e_status" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="" disabled>Pilih Status</option>
                                <option value="Belum Operasional">Belum Operasional</option>
                                <option value="Operasional">Operasional</option>
                                <option value="Tutup Sementara">Tutup Sementara</option>
                                <option value="Tutup Permanen">Tutup Permanen</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tanggal Operasional</label>
                            <input type="date" name="operational_date" id="e_op_date" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm text-slate-600 focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kepala SPPG</label>
                            <select name="leader_id" id="e_leader" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="NULL">Belum Ditugaskan</option>
                                @foreach($leaders as $leader)
                                <option value="{{ $leader->id_person }}">{{ $leader->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Ahli Gizi</label>
                            <select name="nutritionist_id" id="e_nutritionist" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="" disabled>-- Pilih Ahli Gizi --</option>
                                <option value="NULL">Belum Ditugaskan</option>
                                @foreach($nutritionists as $person)
                                <option value="{{ $person->id_person }}">{{ $person->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Akuntan</label>
                            <select name="accountant_id" id="e_accountant" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="" disabled>-- Pilih Akuntan --</option>
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
                            <select name="province" id="e_prov" class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="">Pilih Provinsi</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kabupaten</label>
                            <select name="regency" id="e_reg" class="input-disabled w-full mt-1 px-4 py-2 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" disabled>
                                <option value="">Pilih Kabupaten</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kecamatan</label>
                            <select name="district" id="e_dist" class="input-disabled w-full mt-1 px-4 py-2 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" disabled>
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Desa/Kelurahan</label>
                            <select name="village" id="e_vill" class="input-disabled w-full mt-1 px-4 py-2 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" disabled>
                                <option value="">Pilih Desa/Kelurahan</option>
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Alamat Jalan</label>
                            <textarea name="address" id="e_address" rows="3" class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Cth: Jl. Raya Singaraja Denpasar, No. 99"></textarea>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: PETA --}}
                <div class="pt-10 border-t border-gray-100">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Informasi Lokasi GPS (Klik Untuk Ubah)</h3>
                    <div id="map-edit" class="rounded-xl border-4 border-white shadow-lg ring-1 ring-slate-200"></div>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <input type="text" name="latitude_gps" id="e_lat" readonly class="w-full px-4 py-2.5 bg-slate-100 border-none rounded-lg text-sm text-slate-500" placeholder="Latitude Otomatis">
                        <input type="text" name="longitude_gps" id="e_lng" readonly class="w-full px-4 py-2.5 bg-slate-100 border-none rounded-lg text-sm text-slate-500" placeholder="Longitude Otomatis">
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-4">
                <button type="button" @click="showEditModal = false" class="flex-1 py-4 text-[11px] font-bold uppercase text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition-all">Batal</button>
                <button type="submit" id="btnUpdateSppg" class="flex-1 py-4 text-[11px] font-bold uppercase text-white bg-amber-600 rounded-xl shadow-lg hover:bg-amber-700 transition-all active:scale-95">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    var editMapInstance = null;
    var editMarkerInstance = null;
    var editOriginalImageData = null;
    var lastEditCropData = null;

    // ================================================================
    // 1. LISTENER UTAMA (DIPICU SAAT TOMBOL EDIT DIKLIK)
    // ================================================================
    window.addEventListener('init-edit-sppg', function(e) {
        const unit = e.detail;

        // Reset state & file input
        editOriginalImageData = null;
        lastEditCropData = null;
        const photoInput = document.getElementById('edit_photo');
        if (photoInput) photoInput.value = "";

        // Bersihkan error lama
        clearEditErrors();

        // Populate semua field secara manual (seperti modal create)
        document.getElementById('e_name').value         = unit.name || '';
        document.getElementById('e_id').value           = unit.id_sppg_unit || '';
        document.getElementById('e_code').value         = unit.code_sppg_unit || '';
        document.getElementById('e_op_date').value      = unit.operational_date || '';
        document.getElementById('e_address').value      = unit.address || '';
        document.getElementById('e_lat').value          = unit.latitude_gps || '';
        document.getElementById('e_lng').value          = unit.longitude_gps || '';

        // Set form action dengan ID unit
        const form = document.getElementById('editUnitForm');
        const unitId = unit.id_sppg_unit || unit.original_id || '';
        form.setAttribute('action', `/admin/manage-sppg/${unitId}/update`);

        // Set select status
        const statusEl = document.getElementById('e_status');
        if (statusEl && unit.status) {
            statusEl.value = unit.status;
        }

        // Set select leader, nutritionist, accountant
        const leaderEl = document.getElementById('e_leader');
        if (leaderEl) leaderEl.value = unit.leader_id || 'NULL';
        const nutritionistEl = document.getElementById('e_nutritionist');
        if (nutritionistEl) nutritionistEl.value = unit.nutritionist_id || 'NULL';
        const accountantEl = document.getElementById('e_accountant');
        if (accountantEl) accountantEl.value = unit.accountant_id || 'NULL';

        // Populate Hidden Wilayah Names
        document.getElementById('e_prov_name').value  = unit.province || '';
        document.getElementById('e_reg_name').value   = unit.regency || '';
        document.getElementById('e_dist_name').value  = unit.district || '';
        document.getElementById('e_vill_name').value  = unit.village || '';

        // Init Map & Wilayah
        initEditMapModal();
        initEditCropperLogic();
        syncEditWilayah(unit);

        // Simpan data personil unit ini sebagai referensi pengecekan occupancy
        currentEditUnitPersonnel = {
            leader_id:       unit.leader_id       ? String(unit.leader_id)       : null,
            nutritionist_id: unit.nutritionist_id ? String(unit.nutritionist_id) : null,
            accountant_id:   unit.accountant_id   ? String(unit.accountant_id)   : null,
        };

        // Cek Occupancy Personil
        checkEditOccupancy('e_leader', 'kasppg');
        checkEditOccupancy('e_nutritionist', 'ag');
        checkEditOccupancy('e_accountant', 'ak');
    });

    // ================================================================
    // 2. VALIDASI PERSONIL (OCCUPANCY WARNING)
    // ================================================================
    const editOccupiedPeople = @json($occupiedPeople ?? []);
    let currentEditUnitPersonnel = {}; // Data personil unit yang sedang dibuka

    // Mapping: slug jabatan → kolom di sppg_units
    const editSlugToColumn = { kasppg: 'leader_id', ag: 'nutritionist_id', ak: 'accountant_id' };

    function checkEditOccupancy(id, slug) {
        const el = document.getElementById(id);
        if (!el) return;

        const warnId = id + '-occupancy-warn';
        let warnEl = document.getElementById(warnId);
        if (!warnEl) {
            warnEl = document.createElement('div');
            warnEl.id = warnId;
            warnEl.className = 'text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded border border-amber-100 mt-1 hidden';
            el.after(warnEl);
        }

        const personId = el.value;
        if (!personId || personId === 'NULL') {
            warnEl.classList.add('hidden');
            return;
        }

        // Cek apakah person ini memang sudah menjabat di unit INI (bukan unit lain)
        // Jika ya, tidak perlu tampilkan warning
        const unitColumn = editSlugToColumn[slug];
        if (unitColumn && currentEditUnitPersonnel[unitColumn] === String(personId)) {
            warnEl.classList.add('hidden');
            return;
        }

        if (editOccupiedPeople[slug] && editOccupiedPeople[slug].includes(parseInt(personId))) {
            warnEl.innerHTML = `<i class="fas fa-info-circle mr-1"></i> Terpilih di unit lain. Jika disimpan, ia akan <strong>DIPINDAHKAN</strong> ke unit ini.`;
            warnEl.classList.remove('hidden');
        } else {
            warnEl.classList.add('hidden');
        }
    }

    ['e_leader', 'e_nutritionist', 'e_accountant'].forEach(id => {
        const slug = id === 'e_leader' ? 'kasppg' : (id === 'e_nutritionist' ? 'ag' : 'ak');
        document.addEventListener('change', (e) => {
            if (e.target && e.target.id === id) checkEditOccupancy(id, slug);
        });
    });

    // ================================================================
    // 3. LOGIKA PETA EDIT
    // ================================================================
    function initEditMapModal() {
        if (typeof L === 'undefined') return;
        const container = document.getElementById('map-edit');
        if (!container) return;

        if (editMapInstance) {
            editMapInstance.remove();
            editMapInstance = null;
            editMarkerInstance = null;
        }

        const latInput = document.getElementById('e_lat');
        const lngInput = document.getElementById('e_lng');
        const lat = parseFloat(latInput.value) || -8.1127;
        const lng = parseFloat(lngInput.value) || 115.0911;

        editMapInstance = L.map('map-edit').setView([lat, lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(editMapInstance);

        editMarkerInstance = L.marker([lat, lng], { draggable: true }).addTo(editMapInstance);

        editMapInstance.on('click', function(e) {
            editMarkerInstance.setLatLng(e.latlng);
            latInput.value = e.latlng.lat.toFixed(8);
            lngInput.value = e.latlng.lng.toFixed(8);
        });

        editMarkerInstance.on('dragend', function(ev) {
            const pos = ev.target.getLatLng();
            latInput.value = pos.lat.toFixed(8);
            lngInput.value = pos.lng.toFixed(8);
        });

        setTimeout(() => editMapInstance.invalidateSize(), 500);
    }

    // ================================================================
    // 4. LOGIKA CROPPER EDIT
    // ================================================================
    function initEditCropperLogic() {
        const photoInput  = document.getElementById('edit_photo');
        const previewImg  = document.getElementById('edit-cropped-preview');
        const placeholder = document.getElementById('edit-initial-placeholder');
        const imageToCrop = document.getElementById('image-to-crop');
        const cropperModal = document.getElementById('cropperModal');
        const applyBtn    = document.getElementById('apply-crop');
        const cancelBtn   = document.getElementById('cancel-crop');
        const closeXBtn   = document.getElementById('close-cropper-x');

        if (!photoInput) return;

        photoInput.onchange = (e) => {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    editOriginalImageData = event.target.result;
                    lastEditCropData = null;
                    openEditCropperModal(editOriginalImageData);
                };
                reader.readAsDataURL(file);
            }
        };

        previewImg.onclick = () => {
            if (editOriginalImageData) openEditCropperModal(editOriginalImageData);
        };

        function openEditCropperModal(src) {
            imageToCrop.src = src;
            cropperModal.style.display = 'flex';
            cropperModal.classList.remove('hidden');
            if (window.cropperInstance) window.cropperInstance.destroy();
            setTimeout(() => {
                window.cropperInstance = new Cropper(imageToCrop, {
                    aspectRatio: 4 / 3,
                    viewMode: 2,
                    autoCropArea: 1,
                    ready() {
                        if (lastEditCropData) window.cropperInstance.setData(lastEditCropData);
                    }
                });
            }, 200);
        }

        if (applyBtn) applyBtn.onclick = () => {
            if (!window.cropperInstance) return;
            lastEditCropData = window.cropperInstance.getData();
            const canvas = window.cropperInstance.getCroppedCanvas({ width: 800, height: 600 });
            canvas.toBlob((blob) => {
                const croppedFile = new File([blob], `edit_${Date.now()}.jpg`, { type: 'image/jpeg' });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(croppedFile);
                photoInput.files = dataTransfer.files;
                previewImg.src = URL.createObjectURL(blob);
                previewImg.classList.remove('hidden');
                placeholder.classList.add('hidden');
                if (typeof closeCropper === 'function') closeCropper();
                else { cropperModal.style.display = 'none'; cropperModal.classList.add('hidden'); }
            }, 'image/jpeg', 0.9);
        };

        if (cancelBtn) cancelBtn.onclick = () => { if (typeof closeCropper === 'function') closeCropper(); };
        if (closeXBtn) closeXBtn.onclick = () => { if (typeof closeCropper === 'function') closeCropper(); };
    }

    // ================================================================
    // 5. LOGIKA WILAYAH (API WILAYAH) — identik dg modal create
    // ================================================================
    async function syncEditWilayah(unitData) {
        if (typeof L === 'undefined' && false) return; // hanya guard Leaflet, ini untuk wilayah
        const apiBase = "/api-wilayah";
        const sel = {
            p: document.getElementById('e_prov'),
            r: document.getElementById('e_reg'),
            d: document.getElementById('e_dist'),
            v: document.getElementById('e_vill')
        };
        const hid = {
            p: document.getElementById('e_prov_name'),
            r: document.getElementById('e_reg_name'),
            d: document.getElementById('e_dist_name'),
            v: document.getElementById('e_vill_name')
        };

        const unit = unitData || {};

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

        async function populate(target, path, label, selectedName) {
            target.innerHTML = '<option value="">Memuat...</option>';
            try {
                const resp = await fetch(`${apiBase}/${path}.json`);
                const json = await resp.json();
                let h = `<option value="">Pilih ${label}</option>`;
                let foundCode = null;
                json.data.forEach(i => {
                    const name = i.name.replace(/^(KABUPATEN|KOTA|KAB\.)\s+/i, "").trim();
                    let isSelected = false;
                    if (selectedName && name.toUpperCase() === selectedName.toUpperCase()) {
                        isSelected = true;
                        foundCode = i.code;
                    }
                    const s = isSelected ? 'selected' : '';
                    h += `<option value="${i.code}" data-name="${name}" ${s}>${name}</option>`;
                });
                target.innerHTML = h;
                setSelectState(target, false);
                return foundCode;
            } catch (e) {
                target.innerHTML = '<option value="">Gagal memuat</option>';
                return null;
            }
        }

        // Mulai dengan reset semua ke disabled kecuali provinsi
        setSelectState(sel.r, true);
        setSelectState(sel.d, true);
        setSelectState(sel.v, true);

        // Fetch berantai sesuai data tersimpan
        const pCode = await populate(sel.p, 'provinces', 'Provinsi', unit.province);
        if (pCode) {
            hid.p.value = unit.province;
            const rCode = await populate(sel.r, `regencies/${pCode}`, 'Kabupaten', unit.regency);
            if (rCode) {
                hid.r.value = unit.regency;
                const dCode = await populate(sel.d, `districts/${rCode}`, 'Kecamatan', unit.district);
                if (dCode) {
                    hid.d.value = unit.district;
                    await populate(sel.v, `villages/${dCode}`, 'Desa', unit.village);
                    if (unit.village) hid.v.value = unit.village;
                }
            }
        }

        // Event onchange (sama seperti create)
        sel.p.onchange = async function() {
            hid.p.value = this.options[this.selectedIndex].getAttribute('data-name') || '';
            sel.r.innerHTML = sel.d.innerHTML = sel.v.innerHTML = '<option value="">Pilih...</option>';
            [sel.r, sel.d, sel.v].forEach(s => setSelectState(s, true));
            if (this.value) await populate(sel.r, `regencies/${this.value}`, 'Kabupaten');
            clearEditFieldError('e_prov');
        };
        sel.r.onchange = async function() {
            hid.r.value = this.options[this.selectedIndex].getAttribute('data-name') || '';
            sel.d.innerHTML = sel.v.innerHTML = '<option value="">Pilih...</option>';
            [sel.d, sel.v].forEach(s => setSelectState(s, true));
            if (this.value) await populate(sel.d, `districts/${this.value}`, 'Kecamatan');
            clearEditFieldError('e_reg');
        };
        sel.d.onchange = async function() {
            hid.d.value = this.options[this.selectedIndex].getAttribute('data-name') || '';
            sel.v.innerHTML = '<option value="">Pilih...</option>';
            setSelectState(sel.v, true);
            if (this.value) await populate(sel.v, `villages/${this.value}`, 'Desa');
            clearEditFieldError('e_dist');
        };
        sel.v.onchange = function() {
            hid.v.value = this.options[this.selectedIndex].getAttribute('data-name') || '';
            clearEditFieldError('e_vill');
        };
    }

    // ================================================================
    // 6. LOGIKA SUBMIT EDIT (AJAX — sama seperti create)
    // ================================================================
    window.submitUpdateSppg = async function(formElement) {
        clearEditErrors();

        const btn = document.getElementById('btnUpdateSppg');
        let hasLocalError = false;

        // Validasi Sisi Client
        const fields = [
            { id: 'e_name',    msg: 'Nama SPPG wajib diisi' },
            { id: 'e_id',      msg: 'ID SPPG wajib diisi' },
            { id: 'e_code',    msg: 'Kode SPPG wajib diisi' },
            { id: 'e_status',  msg: 'Pilih status operasional' },
            { id: 'e_prov',    msg: 'Provinsi wajib dipilih' },
            { id: 'e_reg',     msg: 'Kabupaten wajib dipilih' },
            { id: 'e_dist',    msg: 'Kecamatan wajib dipilih' },
            { id: 'e_vill',    msg: 'Desa/Kelurahan wajib dipilih' },
            { id: 'e_address', msg: 'Alamat wajib diisi' },
            { id: 'e_lat',     msg: 'Titik GPS belum ditentukan (Klik pada peta)' },
        ];

        fields.forEach(f => {
            const el = document.getElementById(f.id);
            if (!el || !el.value || el.value.trim() === "") {
                showEditFieldError(f.id, f.msg);
                hasLocalError = true;
            }
        });

        if (hasLocalError) {
            const first = document.querySelector('#editUnitForm .input-error-edit');
            if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        if (btn) {
            btn.disabled = true;
            btn.innerHTML = "Sedang Memproses...";
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
                window.location.reload();
            } else {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = "Simpan Perubahan";
                }

                if (result.errors) {
                    Object.keys(result.errors).forEach(key => {
                        let fieldId = 'e_' + key;
                        if (key === 'id_sppg_unit')     fieldId = 'e_id';
                        if (key === 'code_sppg_unit')   fieldId = 'e_code';
                        if (key === 'operational_date') fieldId = 'e_op_date';
                        if (key === 'photo')            fieldId = 'edit_photo';
                        if (key === 'province_name')    fieldId = 'e_prov';
                        if (key === 'regency_name')     fieldId = 'e_reg';
                        if (key === 'district_name')    fieldId = 'e_dist';
                        if (key === 'village_name')     fieldId = 'e_vill';

                        result.errors[key].forEach(msg => {
                            showEditFieldError(fieldId, msg);
                        });
                    });

                    const firstServerErr = document.querySelector('#editUnitForm .input-error-edit');
                    if (firstServerErr) {
                        firstServerErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            }
        } catch (err) {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = "Simpan Perubahan";
            }
            console.error('Submit Error:', err);
            alert('Terjadi kesalahan jaringan/server: ' + err.message);
        }
    };

    // ================================================================
    // 7. HELPER FUNGSI ERROR
    // ================================================================
    function clearEditErrors() {
        document.querySelectorAll('#editUnitForm .input-error-edit').forEach(el => el.classList.remove('input-error-edit'));
        document.querySelectorAll('#editUnitForm .error-warning-edit').forEach(el => {
            el.classList.add('hidden');
            el.innerText = '';
        });
    }

    function clearEditFieldError(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('input-error-edit');
        const errorEl = document.getElementById('error-edit-' + id);
        if (errorEl) {
            errorEl.classList.add('hidden');
            errorEl.innerText = '';
        }
    }

    function showEditFieldError(id, msg) {
        const el = document.getElementById(id);
        if (!el) return;

        el.classList.add('input-error-edit');

        const errorId = 'error-edit-' + id;
        let errorEl = document.getElementById(errorId);
        if (!errorEl) {
            errorEl = document.createElement('span');
            errorEl.id = errorId;
            errorEl.className = 'error-warning-edit';
            if (id === 'edit_photo') {
                document.getElementById('error-edit_photo_container').appendChild(errorEl);
            } else {
                el.parentNode.appendChild(errorEl);
            }
        }
        errorEl.innerText = '* ' + msg;
        errorEl.classList.remove('hidden');
    }
</script>