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
    </style>

    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showCreateModal = false"></div>

    <div x-show="showCreateModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="relative bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-6xl overflow-hidden transform transition-all font-sans text-sm">

        <form action="{{ route('admin.sppg.store') }}" method="POST" enctype="multipart/form-data" id="createUnitForm">
            @csrf

            {{-- Hidden Inputs untuk menyimpan NAMA wilayah (Teks) --}}
            <input type="hidden" name="province_name" id="f_prov_name">
            <input type="hidden" name="regency_name" id="f_reg_name">
            <input type="hidden" name="district_name" id="f_dist_name">
            <input type="hidden" name="village_name" id="f_vill_name">

            <div class="p-8 max-h-[75vh] overflow-y-auto space-y-10 custom-scrollbar">

                {{-- SECTION 1: FOTO & IDENTITAS --}}
                <div class="flex flex-col lg:flex-row gap-12">
                    <div class="shrink-0 flex flex-col items-center gap-4">
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Foto Unit SPPG (Wajib)</label>
                        <div class="relative group">
                            <div id="photo-container" class="h-45 w-60 rounded-2xl overflow-hidden bg-indigo-600 border-4 border-white shadow-lg ring-1 ring-slate-100 flex items-center justify-center text-center transition-all">
                                <img id="cropped-preview" class="h-full w-full object-cover hidden cursor-pointer hover:opacity-90 transition-opacity" src="" alt="Preview">
                                <div id="initial-placeholder" class="text-white text-6xl uppercase">
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
                        <p id="error-create_photo" class="hidden text-[10px] text-rose-500 font-bold italic text-center">* Foto unit wajib diunggah & dipotong</p>
                    </div>

                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="md:col-span-2">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Unit SPPG</label>
                            <input type="text" name="name" id="f_name" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Masukkan nama unit lengkap">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">ID Unit (Manual)</label>
                            <input type="text" name="id_sppg_unit" id="f_id" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Contoh: 5108041001">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kode Unit (Manual)</label>
                            <input type="text" name="code_sppg_unit" id="f_code" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="51.08.xx.xxx">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Status Operasional</label>
                            <select name="status" id="f_status" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="" disabled selected>-- Pilih Status --</option>
                                <option value="Belum Operasional">Belum Operasional</option>
                                <option value="Operasional">Operasional</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kepala Unit (Leader)</label>
                            <select name="leader_id" id="f_leader" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="" disabled selected>-- Pilih Kepala Unit --</option>
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
                                <option value="">Pilih Desa</option>
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Alamat Jalan</label>
                            <textarea name="address" id="f_address" rows="2" class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Jl. Raya..."></textarea>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: PETA --}}
                <div class="pt-10 border-t border-gray-100">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Lokasi GPS (Klik Pada Peta)</h3>
                    <div id="map-create" class="rounded-xl border-4 border-white shadow-lg ring-1 ring-slate-200"></div>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <input type="text" name="latitude_gps" id="f_lat" readonly class="w-full px-4 py-2.5 bg-slate-100 border-none rounded-lg text-sm text-slate-500" placeholder="Latitude">
                        <input type="text" name="longitude_gps" id="f_lng" readonly class="w-full px-4 py-2.5 bg-slate-100 border-none rounded-lg text-sm text-slate-500" placeholder="Longitude">
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-4">
                <button type="button" @click="showCreateModal = false" class="flex-1 py-4 text-[11px] font-bold uppercase text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition-all">Batal</button>
                <button type="submit" class="flex-1 py-4 text-[11px] font-bold uppercase text-white bg-slate-800 rounded-xl shadow-lg hover:bg-slate-900 transition-all active:scale-95">Simpan Unit SPPG Baru</button>
            </div>
        </form>
    </div>
</div>

<script>
    var cropperInstance = null;
    var originalImageData = null;
    var lastCropData = null;

    function initCropperLogic() {
        const photoInput = document.getElementById('create_photo');
        const previewImg = document.getElementById('cropped-preview');
        const placeholder = document.getElementById('initial-placeholder');
        const imageToCrop = document.getElementById('image-to-crop');
        const cropperModal = document.getElementById('cropperModal');
        const applyBtn = document.getElementById('apply-crop');
        const cancelBtn = document.getElementById('cancel-crop');

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
                    data: lastCropData,
                    ready() {
                        if (lastCropData) cropperInstance.setData(lastCropData);
                    }
                });
            }, 200);
        }

        applyBtn.onclick = () => {
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
                cropperModal.style.display = 'none';
                cropperModal.classList.add('hidden');
            }, 'image/jpeg', 0.9);
        };
        if (cancelBtn) cancelBtn.onclick = () => {
            cropperModal.style.display = 'none';
            cropperModal.classList.add('hidden');
        };
    }

    function initCreateMapModal() {
        const container = document.getElementById('map-create');
        if (!container) return;
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
            if (window.createMarkerInstance) window.createMarkerInstance.setLatLng(e.latlng);
            else window.createMarkerInstance = L.marker(e.latlng, {
                draggable: true
            }).addTo(window.createMapInstance);
            document.getElementById('f_lat').value = e.latlng.lat.toFixed(8);
            document.getElementById('f_lng').value = e.latlng.lng.toFixed(8);
            clearFieldError('f_lat');
        });
        setTimeout(() => {
            window.createMapInstance.invalidateSize();
        }, 500);
    }

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
                let h = `<option value="">-- Pilih ${label} --</option>`;
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
            await new Promise(r => setTimeout(r, 400));
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
        });

        sel.p.onchange = async function() {
            hid.p.value = this.options[this.selectedIndex].getAttribute('data-name');
            sel.r.innerHTML = sel.d.innerHTML = sel.v.innerHTML = '<option value="">Pilih...</option>';
            [sel.r, sel.d, sel.v].forEach(s => setSelectState(s, true));
            if (this.value) await populate(sel.r, `regencies/${this.value}`, 'Kabupaten');
            clearFieldError('f_prov');
            moveMapToLocation();
        };
        sel.r.onchange = async function() {
            hid.r.value = this.options[this.selectedIndex].getAttribute('data-name');
            sel.d.innerHTML = sel.v.innerHTML = '<option value="">Pilih...</option>';
            [sel.d, sel.v].forEach(s => setSelectState(s, true));
            if (this.value) await populate(sel.d, `districts/${this.value}`, 'Kecamatan');
            clearFieldError('f_reg');
            moveMapToLocation();
        };
        sel.d.onchange = async function() {
            hid.d.value = this.options[this.selectedIndex].getAttribute('data-name');
            sel.v.innerHTML = '<option value="">Pilih...</option>';
            setSelectState(sel.v, true);
            if (this.value) await populate(sel.v, `villages/${this.value}`, 'Desa');
            clearFieldError('f_dist');
            moveMapToLocation();
        };
        sel.v.onchange = () => {
            hid.v.value = sel.v.options[sel.v.selectedIndex].getAttribute('data-name');
            clearFieldError('f_vill');
            moveMapToLocation();
        };
    })();

    document.getElementById('createUnitForm').onsubmit = function(e) {
        let valid = true;
        ['f_name', 'f_id', 'f_status', 'f_prov', 'f_reg', 'f_dist', 'f_vill', 'f_address', 'f_lat'].forEach(id => {
            const el = document.getElementById(id);
            if (!el || !el.value) {
                showFieldError(id, 'Wajib diisi');
                valid = false;
            }
        });
        if (!document.getElementById('create_photo').files.length) {
            showFieldError('create_photo', 'Foto wajib ada');
            valid = false;
        }
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