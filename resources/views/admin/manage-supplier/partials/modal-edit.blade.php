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
            method="POST" id="editSupplierForm" @submit.prevent="window.submitUpdateSupplier($el)">
            @csrf
            @method('PATCH')
            
            <div class="p-8 max-h-[75vh] overflow-y-auto space-y-10 custom-scrollbar">
                
                {{-- SECTION 1: INFORMASI DASAR --}}
                <div class="space-y-8">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Informasi Dasar</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Jenis Supplier <span class="text-rose-500">*</span></label>
                            <select name="type_supplier" id="e_type" x-model="selectedSupplier.type_supplier" class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                <option value="">Pilih Jenis</option>
                                @foreach($supplierTypes as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Instansi/Toko <span class="text-rose-500">*</span></label>
                            <input type="text" name="name_supplier" id="e_name" x-model="selectedSupplier.name_supplier" class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Pimpinan <span class="text-rose-500">*</span></label>
                            <input type="text" name="leader_name" id="e_leader" x-model="selectedSupplier.leader_name" class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">No. HP/Telepon <span class="text-rose-500">*</span></label>
                            <input type="text" name="phone" id="e_phone" 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                x-model="selectedSupplier.phone" class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Komoditas Utama <span class="text-rose-500">*</span></label>
                            <textarea name="commodities" id="e_commodities" rows="2" x-model="selectedSupplier.commodities" class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all"></textarea>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: LOKASI --}}
                <div class="space-y-8 pt-4 border-t border-slate-50">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Lokasi Supplier</h3>
                    
                    {{-- Hidden inputs for clean names --}}
                    <input type="hidden" name="province_name" id="e_prov_name">
                    <input type="hidden" name="regency_name" id="e_reg_name">
                    <input type="hidden" name="district_name" id="e_dist_name">
                    <input type="hidden" name="village_name" id="e_vill_name">

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Provinsi <span class="text-rose-500">*</span></label>
                            <select name="province" id="e_prov" 
                                class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all capitalize">
                                <option value="">Pilih Provinsi</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kab/Kota <span class="text-rose-500">*</span></label>
                            <select name="regency" id="e_reg" disabled
                                class="w-full mt-2 text-sm border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all disabled:opacity-50 disabled:cursor-not-allowed capitalize input-disabled">
                                <option value="">Pilih Kabupaten</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kecamatan <span class="text-rose-500">*</span></label>
                            <select name="district" id="e_dist" disabled
                                class="w-full mt-2 text-sm border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all disabled:opacity-50 disabled:cursor-not-allowed capitalize input-disabled">
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kelurahan <span class="text-rose-500">*</span></label>
                            <select name="village" id="e_vill" disabled
                                class="w-full mt-2 text-sm border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all disabled:opacity-50 disabled:cursor-not-allowed capitalize input-disabled">
                                <option value="">Pilih Desa/Kelurahan</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Alamat Detail <span class="text-rose-500">*</span></label>
                            <textarea name="address" id="e_address" rows="2" x-model="selectedSupplier.address" class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all"></textarea>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kode Pos <span class="text-rose-500">*</span></label>
                            <input type="text" name="postal_code" id="e_postal" 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                x-model="selectedSupplier.postal_code" class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
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
                <button type="button" @click="showEditModal = false" class="flex-1 py-4 text-[11px] font-bold uppercase text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all">Batal</button>
                <button type="submit" id="btnUpdateSupplier" class="flex-1 py-4 text-[11px] font-bold uppercase text-white bg-slate-800 rounded-xl shadow-lg hover:bg-slate-900 transition-all active:scale-95">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    (function() {
        async function syncEditWilayah(supplier) {
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
                    target.innerHTML = '<option value="">Gagal</option>';
                    return null;
                }
            }

            setSelectState(sel.p, true);
            setSelectState(sel.r, true);
            setSelectState(sel.d, true);
            setSelectState(sel.v, true);

            const pCode = await populate(sel.p, 'provinces', 'Provinsi', supplier.province);
            if (pCode) {
                hid.p.value = supplier.province;
                const rCode = await populate(sel.r, `regencies/${pCode}`, 'Kabupaten', supplier.regency);
                if (rCode) {
                    hid.r.value = supplier.regency;
                    const dCode = await populate(sel.d, `districts/${rCode}`, 'Kecamatan', supplier.district);
                    if (dCode) {
                        hid.d.value = supplier.district;
                        await populate(sel.v, `villages/${dCode}`, 'Desa', supplier.village);
                        if (supplier.village) hid.v.value = supplier.village;
                    }
                }
            }

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
        }

        window.addEventListener('init-edit-supplier', (e) => {
            syncEditWilayah(e.detail);
        });

        function clearEditErrors() {
            document.querySelectorAll('#editSupplierForm .is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('#editSupplierForm .error-warning').forEach(el => {
                el.classList.add('validation-hidden');
                el.innerText = '';
            });
        }

        function showEditFieldError(id, msg) {
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

        window.submitUpdateSupplier = async function(formElement) {
            clearEditErrors();
            const btnSubmit = document.getElementById('btnUpdateSupplier');
            
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
                        btnSubmit.innerHTML = "Simpan Perubahan";
                    }

                    if (result.errors) {
                        Object.keys(result.errors).forEach(key => {
                            let fieldId = 'e_' + key;
                            // Map backend keys to frontend IDs
                            if (key === 'province_name') fieldId = 'e_prov';
                            if (key === 'regency_name')  fieldId = 'e_reg';
                            if (key === 'district_name') fieldId = 'e_dist';
                            if (key === 'village_name')  fieldId = 'e_vill';
                            if (key === 'postal_code')   fieldId = 'e_postal';
                            if (key === 'type_supplier') fieldId = 'e_type';
                            if (key === 'name_supplier') fieldId = 'e_name';
                            if (key === 'leader_name')   fieldId = 'e_leader';
                            if (key === 'commodities')   fieldId = 'e_commodities';

                            showEditFieldError(fieldId, result.errors[key][0]);
                        });
                        
                        // Scroll to first error
                        const firstErr = document.querySelector('#editSupplierForm .is-invalid');
                        if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            } catch (err) {
                if (btnSubmit) {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = "Simpan Perubahan";
                }
                console.error('Submit Error:', err);
            }
        };
    })();
</script>
