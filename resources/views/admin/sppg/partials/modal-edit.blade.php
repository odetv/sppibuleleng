<div x-show="showEditModal" class="fixed inset-0 z-[99] flex items-center justify-center p-4 text-left" x-cloak>
    <style>
        .input-disabled {
            background-color: #f8fafc !important;
            color: #94a3b8 !important;
            cursor: not-allowed !important;
            pointer-events: none;
            border: 1px solid #e2e8f0 !important;
        }
    </style>

    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showEditModal = false"></div>

    <div x-show="showEditModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="relative bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-6xl overflow-hidden transform transition-all font-sans text-sm">

        {{-- Form Action dinamis menggunakan ID dari selectedUnit --}}
        <form :action="`/admin/sppg/${selectedUnit.id_sppg_unit}`" method="POST" enctype="multipart/form-data" id="editUnitForm">
            @csrf
            @method('PUT')

            <div class="p-8 max-h-[75vh] overflow-y-auto space-y-10 custom-scrollbar">

                {{-- SECTION 1: FOTO & IDENTITAS --}}
                <div class="flex flex-col lg:flex-row gap-12">
                    <div class="shrink-0 flex flex-col items-center gap-4">
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Foto Unit (Opsional Ganti)</label>
                        <div class="relative group">
                            <div id="edit-photo-container" class="h-45 w-60 rounded-2xl overflow-hidden bg-slate-100 border-4 border-white shadow-lg ring-1 ring-slate-100 flex items-center justify-center text-center transition-all">
                                {{-- Preview Foto --}}
                                <img id="edit-cropped-preview"
                                    :src="selectedUnit.photo ? `/storage/${selectedUnit.photo}` : ''"
                                    class="h-full w-full object-cover cursor-pointer hover:opacity-90 transition-opacity"
                                    :class="selectedUnit.photo ? '' : 'hidden'" alt="Preview">

                                <div id="edit-initial-placeholder" :class="selectedUnit.photo ? 'hidden' : ''" class="text-slate-300 text-6xl">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                            </div>
                            <label for="edit_photo_input" class="absolute bottom-3 right-3 p-2.5 bg-indigo-400 text-white rounded-xl shadow-xl cursor-pointer hover:bg-indigo-500 transition-all hover:scale-110">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <input type="file" id="edit_photo_input" name="photo" class="hidden" accept="image/*">
                            </label>
                        </div>
                        <p id="error-edit_photo_input" class="hidden text-[10px] text-rose-500 font-bold italic text-center">* Foto wajib dipotong jika mengganti</p>
                    </div>

                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="md:col-span-2">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Unit SPPG</label>
                            <input type="text" name="name" id="e_name" x-model="selectedUnit.name" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Nama unit">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Status Operasional</label>
                            <select name="status" id="e_status" x-model="selectedUnit.status" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="Belum Operasional">Belum Operasional</option>
                                <option value="Operasional">Operasional</option>
                                <option value="Tutup Sementara">Tutup Sementara</option>
                                <option value="Tutup Permanen">Tutup Permanen</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kepala Unit (Leader)</label>
                            <select name="leader_id" id="e_leader" x-model="selectedUnit.leader_id" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="">-- Pilih Kepala Unit --</option>
                                @foreach($leaders as $leader)
                                <option value="{{ $leader->id_person }}">{{ $leader->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: ALAMAT & WILAYAH --}}
                <div class="pt-10 border-t border-gray-100">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Informasi Alamat & Wilayah</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-sm">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Provinsi</label>
                            <select name="province" id="e_prov" class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                <option value="">Pilih Provinsi</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kabupaten</label>
                            <select name="regency" id="e_reg" class="w-full mt-1 px-4 py-2 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                <option value="">Pilih Kabupaten</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kecamatan</label>
                            <select name="district" id="e_dist" class="w-full mt-1 px-4 py-2 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Desa</label>
                            <select name="village" id="e_vill" class="w-full mt-1 px-4 py-2 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                <option value="">Pilih Desa</option>
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Alamat Lengkap</label>
                            <textarea name="address" id="e_address" x-model="selectedUnit.address" rows="2" class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: PETA --}}
                <div class="pt-10 border-t border-gray-100">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Lokasi GPS (Klik Pada Peta)</h3>
                    <div id="map-edit" class="h-64 w-full rounded-xl border-4 border-white shadow-lg ring-1 ring-slate-200" style="min-height: 250px; z-index: 1;"></div>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <input type="text" name="latitude_gps" id="e_lat" x-model="selectedUnit.latitude_gps" readonly class="w-full px-4 py-2.5 bg-slate-100 border-none rounded-lg text-sm text-slate-500" placeholder="Latitude">
                        <input type="text" name="longitude_gps" id="e_lng" x-model="selectedUnit.longitude_gps" readonly class="w-full px-4 py-2.5 bg-slate-100 border-none rounded-lg text-sm text-slate-500" placeholder="Longitude">
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-4">
                <button type="button" @click="showEditModal = false" class="flex-1 py-4 text-[11px] font-bold uppercase text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition-all">Batal</button>
                <button type="submit" class="flex-1 py-4 text-[11px] font-bold uppercase text-white bg-indigo-600 rounded-xl shadow-lg hover:bg-indigo-700 transition-all active:scale-95">Update Unit SPPG</button>
            </div>
        </form>
    </div>
</div>

<script>
    var editCropperInstance = null;
    var editOriginalImageData = null;

    // --- 1. LOGIKA CROPPER EDIT ---
    function initEditCropperLogic() {
        const photoInput = document.getElementById('edit_photo_input');
        const previewImg = document.getElementById('edit-cropped-preview');
        const placeholder = document.getElementById('edit-initial-placeholder');
        const imageToCrop = document.getElementById('image-to-crop');
        const cropperModal = document.getElementById('cropperModal');
        const applyBtn = document.getElementById('apply-crop');

        if (!photoInput) return;

        photoInput.onchange = (e) => {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    editOriginalImageData = event.target.result;
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
            if (editCropperInstance) editCropperInstance.destroy();
            setTimeout(() => {
                editCropperInstance = new Cropper(imageToCrop, {
                    aspectRatio: 4 / 3,
                    viewMode: 2,
                    autoCropArea: 1
                });
            }, 200);

            applyBtn.onclick = () => {
                const canvas = editCropperInstance.getCroppedCanvas({
                    width: 800,
                    height: 600
                });
                canvas.toBlob((blob) => {
                    const file = new File([blob], `edit_unit_${Date.now()}.jpg`, {
                        type: 'image/jpeg'
                    });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    photoInput.files = dataTransfer.files;

                    previewImg.src = URL.createObjectURL(blob);
                    previewImg.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    clearFieldError('edit_photo_input');

                    cropperModal.style.display = 'none';
                    cropperModal.classList.add('hidden');
                }, 'image/jpeg', 0.9);
            };
        }
    }

    // --- 2. LOGIKA PETA EDIT ---
    function initEditMapModal(lat, lng) {
        const container = document.getElementById('map-edit');
        if (!container) return;

        if (window.editMapInstance) {
            window.editMapInstance.remove();
            window.editMapInstance = null;
        }

        const coords = (lat && lng) ? [parseFloat(lat), parseFloat(lng)] : [-8.1127, 115.0911];
        window.editMapInstance = L.map('map-edit').setView(coords, 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(window.editMapInstance);

        window.editMarkerInstance = L.marker(coords, {
            draggable: true
        }).addTo(window.editMapInstance);

        window.editMapInstance.on('click', function(e) {
            window.editMarkerInstance.setLatLng(e.latlng);
            document.getElementById('e_lat').value = e.latlng.lat.toFixed(8);
            document.getElementById('e_lng').value = e.latlng.lng.toFixed(8);
            document.getElementById('e_lat').dispatchEvent(new Event('input'));
            document.getElementById('e_lng').dispatchEvent(new Event('input'));
            clearFieldError('e_lat');
        });

        window.editMarkerInstance.on('dragend', function(e) {
            const pos = e.target.getLatLng();
            document.getElementById('e_lat').value = pos.lat.toFixed(8);
            document.getElementById('e_lng').value = pos.lng.toFixed(8);
            document.getElementById('e_lat').dispatchEvent(new Event('input'));
            clearFieldError('e_lat');
        });

        setTimeout(() => {
            window.editMapInstance.invalidateSize();
        }, 400);
    }

    // --- 3. LOGIKA WILAYAH EDIT & FLY-TO ---
    (function() {
        const apiBase = "/api-wilayah";
        const sel = {
            p: document.getElementById('e_prov'),
            r: document.getElementById('e_reg'),
            d: document.getElementById('e_dist'),
            v: document.getElementById('e_vill')
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

        async function moveMapToLocation() {
            await new Promise(r => setTimeout(r, 400));
            const getTxt = (s) => (s && s.selectedIndex > 0) ? s.options[s.selectedIndex].getAttribute('data-name') : '';
            const query = [getTxt(sel.v), getTxt(sel.d), getTxt(sel.r), getTxt(sel.p)].filter(Boolean).join(', ') + ', Indonesia';
            if (!query.includes(',')) return;
            try {
                const response = await fetch(`/api-map-search?q=${encodeURIComponent(query)}`);
                const data = await response.json();
                if (data?.[0] && window.editMapInstance) {
                    const zoom = sel.v.value ? 16 : (sel.d.value ? 14 : (sel.r.value ? 12 : 10));
                    window.editMapInstance.flyTo([data[0].lat, data[0].lon], zoom, {
                        animate: true,
                        duration: 1.5
                    });
                }
            } catch (e) {}
        }

        async function populate(target, path, label, selectedCode = null) {
            try {
                const resp = await fetch(`${apiBase}/${path}.json`);
                const json = await resp.json();
                let h = `<option value="">-- Pilih ${label} --</option>`;
                json.data.forEach(i => {
                    const name = i.name.replace(/^(KABUPATEN|KOTA|KAB\.)\s+/i, "").trim();
                    const selected = i.code == selectedCode ? 'selected' : '';
                    h += `<option value="${i.code}" data-name="${name}" ${selected}>${name}</option>`;
                });
                target.innerHTML = h;
                setSelectState(target, false);
            } catch (e) {
                target.innerHTML = '<option>Gagal</option>';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            initEditCropperLogic();

            const observer = new MutationObserver(() => {
                const modal = document.querySelector('[x-show="showEditModal"]');
                if (modal && window.getComputedStyle(modal).display !== 'none') {
                    const unit = document.querySelector('[x-data]').__x.$data.selectedUnit;

                    initEditMapModal(unit.latitude_gps, unit.longitude_gps);
                    loadAllWilayah(unit);
                }
            });
            observer.observe(document.body, {
                attributes: true,
                subtree: true
            });
        });

        async function loadAllWilayah(unit) {
            await populate(sel.p, 'provinces', 'Provinsi', unit.province);
            if (unit.province) {
                await populate(sel.r, `regencies/${unit.province}`, 'Kabupaten', unit.regency);
                if (unit.regency) {
                    await populate(sel.d, `districts/${unit.regency}`, 'Kecamatan', unit.district);
                    if (unit.district) {
                        await populate(sel.v, `villages/${unit.district}`, 'Desa', unit.village);
                    }
                }
            }
        }

        sel.p.onchange = async function() {
            sel.r.innerHTML = sel.d.innerHTML = sel.v.innerHTML = '<option>Pilih...</option>';
            [sel.r, sel.d, sel.v].forEach(s => setSelectState(s, true));
            if (this.value) await populate(sel.r, `regencies/${this.value}`, 'Kabupaten');
            clearFieldError('e_prov');
            moveMapToLocation();
        };
        sel.r.onchange = async function() {
            sel.d.innerHTML = sel.v.innerHTML = '<option>Pilih...</option>';
            [sel.d, sel.v].forEach(s => setSelectState(s, true));
            if (this.value) await populate(sel.d, `districts/${this.value}`, 'Kecamatan');
            clearFieldError('e_reg');
            moveMapToLocation();
        };
        sel.d.onchange = async function() {
            sel.v.innerHTML = '<option>Pilih...</option>';
            setSelectState(sel.v, true);
            if (this.value) await populate(sel.v, `villages/${this.value}`, 'Desa');
            clearFieldError('e_dist');
            moveMapToLocation();
        };
        sel.v.onchange = () => {
            clearFieldError('e_vill');
            moveMapToLocation();
        };
    })();

    // --- 4. VALIDASI INLINE ---
    function showFieldError(id, msg) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.add('ring-2', 'ring-red-500');
        let errorEl = document.getElementById('error-' + id);
        if (!errorEl) {
            errorEl = document.createElement('p');
            errorEl.id = 'error-' + id;
            errorEl.className = 'text-[10px] text-rose-500 font-bold italic mt-1';
            el.parentNode.appendChild(errorEl);
        }
        errorEl.innerText = '* ' + msg;
        errorEl.classList.remove('hidden');
    }

    function clearFieldError(id) {
        const el = document.getElementById(id);
        if (el) el.classList.remove('ring-2', 'ring-red-500');
        const errorEl = document.getElementById('error-' + id);
        if (errorEl) errorEl.classList.add('hidden');
    }

    document.getElementById('editUnitForm').onsubmit = function(e) {
        let valid = true;
        ['e_name', 'e_status', 'e_prov', 'e_reg', 'e_dist', 'e_vill', 'e_address', 'e_lat'].forEach(id => {
            const el = document.getElementById(id);
            if (!el || !el.value) {
                showFieldError(id, 'Wajib diisi');
                valid = false;
            }
        });
        if (!valid) {
            e.preventDefault();
            const err = document.querySelector('.ring-red-500');
            if (err) err.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    };
</script>