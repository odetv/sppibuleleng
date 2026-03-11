<div x-show="showCreateModal" 
    class="fixed inset-0 z-[1000] flex items-center justify-center p-4 text-left" 
    x-cloak>
    
    <div x-show="showCreateModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showCreateModal = false"></div>

    <div x-show="showCreateModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative w-full max-w-6xl bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden font-sans text-sm">
        
        <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center text-slate-800">
            <div class="flex items-center gap-3">
                <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
                <h3 class="text-[14px] font-bold uppercase tracking-widest">Tambah Data Supplier</h3>
            </div>
            <button @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600 text-2xl duration-200">&times;</button>
        </div>

        <form action="{{ route('admin.manage-supplier.store') }}" method="POST" id="createSupplierForm" @submit.prevent="window.submitCreateSupplier($el)">
            @csrf
            
            <div class="p-8 max-h-[75vh] overflow-y-auto space-y-10 custom-scrollbar">
                
                {{-- SECTION 1: INFORMASI DASAR --}}
                <div class="space-y-8">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Informasi Dasar</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Jenis Supplier <span class="text-rose-500">*</span></label>
                            <select name="type_supplier" id="f_type" class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                <option value="">Pilih Jenis</option>
                                @foreach($supplierTypes as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Instansi/Toko <span class="text-rose-500">*</span></label>
                            <input type="text" name="name_supplier" id="f_name" class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="Misal: PT. Sembako Jaya">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Pimpinan <span class="text-rose-500">*</span></label>
                            <input type="text" name="leader_name" id="f_leader" class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="Nama lengkap">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">No. HP/Telepon <span class="text-rose-500">*</span></label>
                            <input type="text" name="phone" id="f_phone" 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Komoditas Utama <span class="text-rose-500">*</span></label>
                            <textarea name="commodities" id="f_commodities" rows="2" class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="Contoh: Beras, Telur, Daging Ayam, Sayuran"></textarea>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: LOKASI --}}
                <div class="space-y-8 pt-4 border-t border-slate-50">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Lokasi Supplier</h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        {{-- Form Section --}}
                        <div class="space-y-8">
                            {{-- Hidden inputs for clean names --}}
                            <input type="hidden" name="province_name" id="f_prov_name">
                            <input type="hidden" name="regency_name" id="f_reg_name">
                            <input type="hidden" name="district_name" id="f_dist_name">
                            <input type="hidden" name="village_name" id="f_vill_name">

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Provinsi <span class="text-rose-500">*</span></label>
                                    <select name="province" id="f_prov" 
                                        class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all capitalize">
                                        <option value="">Pilih Provinsi</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kab/Kota <span class="text-rose-500">*</span></label>
                                    <select name="regency" id="f_reg" disabled
                                        class="w-full mt-2 text-sm border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all disabled:opacity-50 disabled:cursor-not-allowed capitalize input-disabled">
                                        <option value="">Pilih Kabupaten</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kecamatan <span class="text-rose-500">*</span></label>
                                    <select name="district" id="f_dist" disabled
                                        class="w-full mt-2 text-sm border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all disabled:opacity-50 disabled:cursor-not-allowed capitalize input-disabled">
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kelurahan <span class="text-rose-500">*</span></label>
                                    <select name="village" id="f_vill" disabled
                                        class="w-full mt-2 text-sm border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all disabled:opacity-50 disabled:cursor-not-allowed capitalize input-disabled">
                                        <option value="">Pilih Desa/Kelurahan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Alamat Detail <span class="text-rose-500">*</span></label>
                                    <textarea name="address" id="f_address" rows="2" class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="Nama jalan, nomor rumah, RT/RW"></textarea>
                                </div>
                                <div>
                                    <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kode Pos <span class="text-rose-500">*</span></label>
                                    <input type="text" name="postal_code" id="f_postal" 
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                        class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="811xx">
                                </div>
                                <div class="hidden">
                                    {{-- Group GPS together --}}
                                </div>
                                <div>
                                    <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Latitude GPS <span class="text-rose-500">*</span></label>
                                    <input type="text" name="latitude_gps" id="f_latitude" 
                                        class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="-8.xxxxx">
                                </div>
                                <div>
                                    <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Longitude GPS <span class="text-rose-500">*</span></label>
                                    <input type="text" name="longitude_gps" id="f_longitude" 
                                        class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="115.xxxxx">
                                </div>
                            </div>
                        </div>

                        {{-- Map Section --}}
                        <div class="space-y-4">
                            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Pilih Lokasi Di Peta <span class="text-rose-500">*</span></label>
                            <div id="map-create" class="w-full h-full min-h-[350px] rounded-xl border-2 border-slate-100 shadow-inner z-10"></div>
                            <p class="text-[10px] text-slate-400 italic font-medium px-1">Klik pada peta untuk menentukan titik koordinat supplier.</p>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: PENUGASAN UNIT SPPG --}}
                <div class="space-y-8 pt-4 border-t border-slate-50">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Penugasan Unit SPPG</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($sppgUnits as $unit)
                        <label class="relative flex items-center p-3 rounded-xl border border-slate-100 bg-slate-50/30 hover:bg-slate-50 transition-all cursor-pointer group">
                            <input type="checkbox" name="sppg_units[]" value="{{ $unit->id_sppg_unit }}"
                                x-model="selectedSupplier.sppg_units"
                                class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 focus:ring-offset-0 transition-all">
                            <span class="ml-3 text-xs font-semibold text-slate-700 group-hover:text-indigo-600 transition-colors">{{ $unit->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    <p class="text-[10px] text-slate-400 italic font-medium px-1">Pilih minimal satu unit SPPG yang akan dilayani oleh supplier ini.</p>
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-4">
                <button type="button" @click="showCreateModal = false" class="flex-1 py-4 text-[11px] font-bold uppercase text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all">Batal</button>
                <button type="submit" id="btnSubmitSupplier" class="flex-1 py-4 text-[11px] font-bold uppercase text-white bg-slate-800 rounded-xl shadow-lg hover:bg-slate-900 transition-all active:scale-95">Simpan Supplier</button>
            </div>
        </form>
    </div>
</div>

<script>
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

        function initCreateMapModal() {
            if (typeof L === 'undefined') return;
            const container = document.getElementById('map-create');
            if (!container) return;

            if (window.createMapInstance) {
                setTimeout(() => window.createMapInstance.invalidateSize(), 150);
                return;
            }

            const bulelengCoords = [-8.1127, 115.0911];
            window.createMapInstance = L.map('map-create').setView(bulelengCoords, 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(window.createMapInstance);

            window.createMapInstance.on('click', function(e) {
                if (window.createMarkerInstance) {
                    window.createMarkerInstance.setLatLng(e.latlng);
                } else {
                    window.createMarkerInstance = L.marker(e.latlng, { draggable: true }).addTo(window.createMapInstance);
                    window.createMarkerInstance.on('dragend', function(ev) {
                        const pos = ev.target.getLatLng();
                        document.getElementById('f_latitude').value = pos.lat.toFixed(8);
                        document.getElementById('f_longitude').value = pos.lng.toFixed(8);
                    });
                }
                document.getElementById('f_latitude').value = e.latlng.lat.toFixed(8);
                document.getElementById('f_longitude').value = e.latlng.lng.toFixed(8);
                if (typeof clearFieldError === 'function') clearFieldError('f_latitude');
            });

            setTimeout(() => window.createMapInstance.invalidateSize(), 150);
        }

        document.addEventListener('DOMContentLoaded', () => {
            populate(sel.p, 'provinces', 'Provinsi');
        });

        window.addEventListener('open-create-supplier', () => {
            setTimeout(initCreateMapModal, 300);
        });

        sel.p.onchange = async function() {
            hid.p.value = this.options[this.selectedIndex].getAttribute('data-name') || '';
            sel.r.innerHTML = sel.d.innerHTML = sel.v.innerHTML = '<option value="">Pilih...</option>';
            [sel.r, sel.d, sel.v].forEach(s => setSelectState(s, true));
            if (this.value) await populate(sel.r, `regencies/${this.value}`, 'Kabupaten');
        };
        sel.r.onchange = async function() {
            hid.r.value = this.options[this.selectedIndex].getAttribute('data-name') || '';
            sel.d.innerHTML = sel.v.innerHTML = '<option value="">Pilih...</option>';
            [sel.d, sel.v].forEach(s => setSelectState(s, true));
            if (this.value) await populate(sel.d, `districts/${this.value}`, 'Kecamatan');
        };
        sel.d.onchange = async function() {
            hid.d.value = this.options[this.selectedIndex].getAttribute('data-name') || '';
            sel.v.innerHTML = '<option value="">Pilih...</option>';
            setSelectState(sel.v, true);
            if (this.value) await populate(sel.v, `villages/${this.value}`, 'Desa');
        };
        sel.v.onchange = function() {
            hid.v.value = this.options[this.selectedIndex].getAttribute('data-name') || '';
        };

        function clearErrors() {
            document.querySelectorAll('#createSupplierForm .is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('#createSupplierForm .error-warning').forEach(el => {
                el.classList.add('validation-hidden');
                el.innerText = '';
            });
        }

        function showFieldError(id, msg) {
            const el = document.getElementById(id);
            if (!el) return;

            el.classList.add('is-invalid');

            const errorId = 'error-' + id;
            let errorEl = document.getElementById(errorId);
            if (!errorEl) {
                errorEl = document.createElement('span');
                errorEl.id = errorId;
                errorEl.className = 'error-warning';
                el.parentNode.appendChild(errorEl);
            }
            errorEl.innerText = '* ' + msg;
            errorEl.classList.remove('validation-hidden');
        }

        window.submitCreateSupplier = async function(formElement) {
            clearErrors();
            const btnSubmit = document.getElementById('btnSubmitSupplier');
            
            if (btnSubmit) {
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = "Memproses...";
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
                    if (btnSubmit) {
                        btnSubmit.disabled = false;
                        btnSubmit.innerHTML = "Simpan Supplier";
                    }

                    if (result.errors) {
                        Object.keys(result.errors).forEach(key => {
                            let fieldId = 'f_' + key;
                            // Map backend keys to frontend IDs
                            if (key === 'province_name') fieldId = 'f_prov';
                            if (key === 'regency_name')  fieldId = 'f_reg';
                            if (key === 'district_name') fieldId = 'f_dist';
                            if (key === 'village_name')  fieldId = 'f_vill';
                            if (key === 'postal_code')   fieldId = 'f_postal';
                            if (key === 'type_supplier') fieldId = 'f_type';
                            if (key === 'name_supplier') fieldId = 'f_name';
                            if (key === 'leader_name')   fieldId = 'f_leader';
                            if (key === 'commodities')   fieldId = 'f_commodities';

                            showFieldError(fieldId, result.errors[key][0]);
                        });
                        
                        // Scroll to first error
                        const firstErr = document.querySelector('#createSupplierForm .is-invalid');
                        if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            } catch (err) {
                if (btnSubmit) {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = "Simpan Supplier";
                }
                console.error('Submit Error:', err);
            }
        };
    })();
</script>
