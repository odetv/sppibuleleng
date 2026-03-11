<div x-show="showEditModal" 
    class="fixed inset-0 z-[1000] flex items-center justify-center p-4 text-left" 
    x-cloak>
    
    <div x-show="showEditModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showEditModal = false"></div>

    <div x-show="showEditModal"
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </span>
                <h3 class="text-[14px] font-bold uppercase tracking-widest">Edit Data Supplier</h3>
            </div>
            <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 text-2xl duration-200">&times;</button>
        </div>

        <form x-bind:action="selectedSupplier ? `/admin/manage-supplier/${selectedSupplier.id_supplier}/update` : ''" 
            method="POST" id="editSupplierForm" @submit.prevent="window.submitS_UpdateSupplier($el)">
            @csrf
            @method('PATCH')
            
            <div class="p-8 max-h-[75vh] overflow-y-auto space-y-8 custom-scrollbar">
                
                {{-- SECTION 1: INFORMASI DASAR --}}
                <div class="space-y-6">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-indigo-600">Informasi Dasar</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="lg:col-span-2">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Nama Instansi/Toko <span class="text-rose-500">*</span></label>
                            <input type="text" name="name_supplier" id="s_e_name" x-model="selectedSupplier.name_supplier" class="w-full mt-1.5 text-xs bg-gray-50 border-slate-200 rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Jenis Supplier <span class="text-rose-500">*</span></label>
                            <select name="type_supplier" id="s_e_type" x-model="selectedSupplier.type_supplier" class="w-full mt-1.5 text-xs bg-gray-50 border-slate-200 rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                <option value="">Pilih Jenis</option>
                                @foreach($supplierTypes as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">No. HP/Telepon <span class="text-rose-500">*</span></label>
                            <input type="text" name="phone" id="s_e_phone" 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                x-model="selectedSupplier.phone" class="w-full mt-1.5 text-xs bg-gray-50 border-slate-200 rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div class="lg:col-span-2">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Nama Pimpinan <span class="text-rose-500">*</span></label>
                            <input type="text" name="leader_name" id="s_e_leader" x-model="selectedSupplier.leader_name" class="w-full mt-1.5 text-xs bg-gray-50 border-slate-200 rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div class="lg:col-span-2">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Komoditas Utama <span class="text-rose-500">*</span></label>
                            <textarea name="commodities" id="s_e_commodities" rows="1" x-model="selectedSupplier.commodities" class="w-full mt-1.5 text-xs bg-gray-50 border-slate-200 rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all"></textarea>
                        </div>
                    </div>
                </div>

                        {{-- SECTION 2: LOKASI --}}
                <div class="space-y-6 pt-6 border-t border-slate-100">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-indigo-600">Lokasi Supplier</h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        {{-- LEFT COLUMN: FORM --}}
                        <div class="space-y-6">
                            {{-- Hidden inputs for clean names --}}
                            <input type="hidden" name="province_name" id="s_e_prov_name">
                            <input type="hidden" name="regency_name" id="s_e_reg_name">
                            <input type="hidden" name="district_name" id="s_e_dist_name">
                            <input type="hidden" name="village_name" id="s_e_vill_name">

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Provinsi <span class="text-rose-500">*</span></label>
                                    <select name="province" id="s_e_prov" 
                                        class="w-full mt-1.5 text-[11px] bg-gray-50 border-slate-200 rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all capitalize">
                                        <option value="">Pilih Provinsi</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kab/Kota <span class="text-rose-500">*</span></label>
                                    <select name="regency" id="s_e_reg" disabled
                                        class="w-full mt-1.5 text-[11px] border-slate-200 rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all disabled:opacity-50 disabled:cursor-not-allowed capitalize input-disabled">
                                        <option value="">Pilih Kabupaten</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kecamatan <span class="text-rose-500">*</span></label>
                                    <select name="district" id="s_e_dist" disabled
                                        class="w-full mt-1.5 text-[11px] border-slate-200 rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all disabled:opacity-50 disabled:cursor-not-allowed capitalize input-disabled">
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kelurahan <span class="text-rose-500">*</span></label>
                                    <select name="village" id="s_e_vill" disabled
                                        class="w-full mt-1.5 text-[11px] border-slate-200 rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all disabled:opacity-50 disabled:cursor-not-allowed capitalize input-disabled">
                                        <option value="">Pilih Desa/Kelurahan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-2">
                                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Alamat Lengkap <span class="text-rose-500">*</span></label>
                                    <textarea name="address" id="s_e_address" rows="1" x-model="selectedSupplier.address" class="w-full mt-1.5 text-xs bg-gray-50 border-slate-200 rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="Nama Jalan, Blok, No Rumah..."></textarea>
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Kode Pos <span class="text-rose-500">*</span></label>
                                    <input type="text" name="postal_code" id="s_e_postal" 
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                        x-model="selectedSupplier.postal_code" class="w-full mt-1.5 text-xs bg-gray-50 border-slate-200 rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                                <div>
                                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Latitude GPS <span class="text-rose-500">*</span></label>
                                    <input type="text" name="latitude_gps" id="s_e_lat" 
                                        x-model="selectedSupplier.latitude_gps" class="w-full mt-1.5 text-xs bg-gray-50 border-slate-200 rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="-8.xxxxx">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Longitude GPS <span class="text-rose-500">*</span></label>
                                    <input type="text" name="longitude_gps" id="s_e_lng" 
                                        x-model="selectedSupplier.longitude_gps" class="w-full mt-1.5 text-xs bg-gray-50 border-slate-200 rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="115.xxxxx">
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT COLUMN: MAP --}}
                        <div class="space-y-4">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pilih Lokasi Di Peta <span class="text-rose-500">*</span></label>
                            <div id="map-edit-supplier" class="w-full rounded-xl border-2 border-slate-100 shadow-inner z-10" style="height: 380px; background: #f8fafc;"></div>
                            <p class="text-[9px] text-slate-400 italic font-medium px-1">Seret penanda atau klik pada peta untuk menentukan koordinat yang akurat.</p>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: PENUGASAN UNIT SPPG --}}
                <div x-show="!hideAssignments" class="space-y-6 pt-6 border-t border-slate-100">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-indigo-600">Penugasan Unit SPPG</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($sppgUnits as $unit)
                        <label class="relative flex items-center p-3 rounded-xl border border-slate-100 bg-slate-50/30 hover:bg-white hover:shadow-md hover:border-indigo-100 transition-all cursor-pointer group">
                            <input type="checkbox" name="sppg_units[]" value="{{ $unit->id_sppg_unit }}"
                                x-model="selectedSupplier.sppg_units"
                                class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 focus:ring-offset-0 transition-all">
                            <span class="ml-3 text-xs font-semibold text-slate-700 group-hover:text-indigo-600 transition-colors">{{ $unit->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    <p class="text-[10px] text-slate-400 italic font-medium px-1">Daftar ini menunjukkan unit SPPG mana saja yang dilayani oleh supplier ini.</p>
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-4">
                <button type="button" @click="showEditModal = false" class="flex-1 py-3 text-[10px] font-bold uppercase text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all">Batal</button>
                <button type="submit" id="btnUpdateSupplier" class="flex-1 py-3 text-[10px] font-bold uppercase text-white bg-indigo-600 rounded-xl shadow-lg hover:bg-indigo-700 transition-all active:scale-95">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    (function() {
        async function syncS_EditWilayah(supplier) {
            const apiBase = "/api-wilayah";
            const sel = {
                p: document.getElementById('s_e_prov'),
                r: document.getElementById('s_e_reg'),
                d: document.getElementById('s_e_dist'),
                v: document.getElementById('s_e_vill')
            };
            const hid = {
                p: document.getElementById('s_e_prov_name'),
                r: document.getElementById('s_e_reg_name'),
                d: document.getElementById('s_e_dist_name'),
                v: document.getElementById('s_e_vill_name')
            };

            function setSelectState(el, isDisabled) {
                if(!el) return;
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
                if(!target) return null;
                target.innerHTML = '<option value="">Memuat...</option>';
                try {
                    const resp = await fetch(`${apiBase}/${path}.json`);
                    const json = await resp.json();
                    let h = `<option value="">Pilih ${label}</option>`;
                    let foundCode = null;
                    
                    // Improved matching logic: normalize whitespace and casing
                    const normalize = (s) => (s || "").toString().toUpperCase().replace(/[^A-Z0-9]/g, "").trim();
                    const targetNorm = normalize(selectedName);

                    json.data.forEach(i => {
                        const name = i.name.replace(/^(KABUPATEN|KOTA|KAB\.)\s+/i, "").trim();
                        const itemNorm = normalize(name);
                        
                        let isSelected = false;
                        if (targetNorm && itemNorm === targetNorm) {
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
                    target.innerHTML = `<option value="">Gagal Memuat ${label}</option>`;
                    return null;
                }
            }

            // Reset states
            [sel.p, sel.r, sel.d, sel.v].forEach(s => setSelectState(s, true));

            // Chain population
            const pCode = await populate(sel.p, 'provinces', 'Provinsi', supplier.province);
            if (pCode) {
                if (hid.p) hid.p.value = supplier.province || '';
                const rCode = await populate(sel.r, `regencies/${pCode}`, 'Kabupaten', supplier.regency);
                if (rCode) {
                    if (hid.r) hid.r.value = supplier.regency || '';
                    const dCode = await populate(sel.d, `districts/${rCode}`, 'Kecamatan', supplier.district);
                    if (dCode) {
                        if (hid.d) hid.d.value = supplier.district || '';
                        const vCode = await populate(sel.v, `villages/${dCode}`, 'Desa', supplier.village);
                        if (hid.v) hid.v.value = supplier.village || '';
                    }
                }
            }

            // Event handlers for manual changes
            sel.p.onchange = async function() {
                const opt = this.options[this.selectedIndex];
                if (hid.p) hid.p.value = opt.getAttribute('data-name') || '';
                sel.r.innerHTML = sel.d.innerHTML = sel.v.innerHTML = '<option value="">Pilih...</option>';
                [sel.r, sel.d, sel.v].forEach(s => setSelectState(s, true));
                if (this.value) await populate(sel.r, `regencies/${this.value}`, 'Kabupaten');
            };
            sel.r.onchange = async function() {
                const opt = this.options[this.selectedIndex];
                if (hid.r) hid.r.value = opt.getAttribute('data-name') || '';
                sel.d.innerHTML = sel.v.innerHTML = '<option value="">Pilih...</option>';
                [sel.d, sel.v].forEach(s => setSelectState(s, true));
                if (this.value) await populate(sel.d, `districts/${this.value}`, 'Kecamatan');
            };
            sel.d.onchange = async function() {
                const opt = this.options[this.selectedIndex];
                if (hid.d) hid.d.value = opt.getAttribute('data-name') || '';
                sel.v.innerHTML = '<option value="">Pilih...</option>';
                setSelectState(sel.v, true);
                if (this.value) await populate(sel.v, `villages/${this.value}`, 'Desa');
            };
            sel.v.onchange = function() {
                const opt = this.options[this.selectedIndex];
                if (hid.v) hid.v.value = opt.getAttribute('data-name') || '';
            };
        }
        window.syncS_EditWilayah = syncS_EditWilayah;

        function initS_EditMapModal(supplier) {
            if (typeof L === 'undefined') return;
            const container = document.getElementById('map-edit-supplier');
            if (!container) return;

            const lat = parseFloat(supplier.latitude_gps);
            const lng = parseFloat(supplier.longitude_gps);
            const coords = (lat && lng) ? [lat, lng] : [-8.1127, 115.0911];

            // Ensure previous instances are cleaned up or reused
            if (window.s_editMapInstance) {
                window.s_editMapInstance.setView(coords, (lat && lng) ? 15 : 12);
                if (window.s_editMarkerInstance) {
                    window.s_editMarkerInstance.setLatLng(coords);
                } else if (lat && lng) {
                    window.s_editMarkerInstance = L.marker(coords, { draggable: true }).addTo(window.s_editMapInstance);
                    setupMarkerEvents(window.s_editMarkerInstance);
                }
                
                // CRITICAL: Leaflet needs a small delay after visibility change
                setTimeout(() => {
                    window.s_editMapInstance.invalidateSize(true);
                }, 400);
                return;
            }

            // Create new instance
            window.s_editMapInstance = L.map('map-edit-supplier', {
                attributionControl: false
            });
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19
            }).addTo(window.s_editMapInstance);

            window.s_editMapInstance.setView(coords, (lat && lng) ? 15 : 12);

            if (lat && lng) {
                window.s_editMarkerInstance = L.marker(coords, { draggable: true }).addTo(window.s_editMapInstance);
                setupMarkerEvents(window.s_editMarkerInstance);
            }

            window.s_editMapInstance.on('click', function(e) {
                if (window.s_editMarkerInstance) {
                    window.s_editMarkerInstance.setLatLng(e.latlng);
                } else {
                    window.s_editMarkerInstance = L.marker(e.latlng, { draggable: true }).addTo(window.s_editMapInstance);
                    setupMarkerEvents(window.s_editMarkerInstance);
                }
                updateCoords(e.latlng.lat, e.latlng.lng);
            });

            function setupMarkerEvents(marker) {
                marker.on('dragend', function(ev) {
                    const pos = ev.target.getLatLng();
                    updateCoords(pos.lat, pos.lng);
                });
            }

            function updateCoords(lat, lng) {
                const latEl = document.getElementById('s_e_lat');
                const lngEl = document.getElementById('s_e_lng');
                if (latEl) latEl.value = lat.toFixed(8);
                if (lngEl) lngEl.value = lng.toFixed(8);
                
                // Trigger Alpine.js models
                if(latEl) latEl.dispatchEvent(new Event('input'));
                if(lngEl) lngEl.dispatchEvent(new Event('input'));
                
                if (typeof clearS_EditErrors === 'function') clearS_EditErrors('s_e_lat');
            }

            // Initial invalidation after creation
            setTimeout(() => {
                if(window.s_editMapInstance) {
                    window.s_editMapInstance.invalidateSize();
                    window.s_editMapInstance.setView(coords, (lat && lng) ? 15 : 12);
                    // Force a re-render of tiles
                    window.s_editMapInstance.fire('resize');
                }
            }, 800);
        }

        // Global Event Listener for integrated use
        window.addEventListener('init-edit-supplier', (e) => {
            syncS_EditWilayah(e.detail);
            // Longer delay for map to ensure modal transition and display is solid
            setTimeout(() => initS_EditMapModal(e.detail), 1000);
        });

        function clearS_EditErrors() {
            document.querySelectorAll('#editSupplierForm .is-invalid').forEach(el => el.classList.remove('is-invalid', 'ring-rose-500', 'bg-rose-50'));
            document.querySelectorAll('#editSupplierForm .error-warning').forEach(el => el.remove());
        }

        function showS_EditFieldError(id, msg) {
            const el = document.getElementById(id);
            if (!el) return;

            el.classList.add('is-invalid', 'ring-2', 'ring-rose-500', 'bg-rose-50');

            const errorEl = document.createElement('span');
            errorEl.className = 'error-warning text-[10px] text-rose-500 font-bold mt-1 block px-1';
            errorEl.innerText = '* ' + msg;
            el.parentNode.appendChild(errorEl);
        }

        window.submitS_UpdateSupplier = async function(formElement) {
            clearS_EditErrors();
            const btnSubmit = document.getElementById('btnUpdateSupplier');
            const originalHtml = btnSubmit ? btnSubmit.innerHTML : "";
            
            if (btnSubmit) {
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = `<svg class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Data supplier berhasil diperbarui.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    if (btnSubmit) {
                        btnSubmit.disabled = false;
                        btnSubmit.innerHTML = originalHtml;
                    }

                    if (result.errors) {
                        Object.keys(result.errors).forEach(key => {
                            // Map backend keys to NEW frontend IDs
                            const mapping = {
                                'province_name': 's_e_prov',
                                'regency_name':  's_e_reg',
                                'district_name': 's_e_dist',
                                'village_name':  's_e_vill',
                                'postal_code':   's_e_postal',
                                'type_supplier': 's_e_type',
                                'name_supplier': 's_e_name',
                                'leader_name':   's_e_leader',
                                'commodities':   's_e_commodities',
                                'phone':         's_e_phone',
                                'address':       's_e_address',
                                'latitude_gps':  's_e_lat',
                                'longitude_gps': 's_e_lng'
                            };
                            
                            const fieldId = mapping[key] || ('s_e_' + key);
                            showS_EditFieldError(fieldId, result.errors[key][0]);
                        });
                        
                        // Scroll to first error
                        const firstErr = document.querySelector('#editSupplierForm .is-invalid');
                        if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            } catch (err) {
                if (btnSubmit) {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = originalHtml;
                }
                console.error('Submit Error:', err);
            }
        };
    })();
</script>
