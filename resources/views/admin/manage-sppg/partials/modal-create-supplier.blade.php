<div x-show="showCreateSupplierModal" 
    class="fixed inset-0 z-[105] flex items-center justify-center p-4 text-left" 
    x-cloak>
    
    <div x-show="showCreateSupplierModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showCreateSupplierModal = false"></div>

    <div x-show="showCreateSupplierModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative w-full max-w-4xl bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden font-sans text-sm">
        
        <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center text-slate-800">
            <div class="flex items-center gap-3">
                <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
                <h3 class="text-[14px] font-bold uppercase tracking-widest">Tambah Supplier Baru</h3>
            </div>
            <button @click="showCreateSupplierModal = false" class="text-slate-400 hover:text-slate-600 text-2xl duration-200">&times;</button>
        </div>

        <form action="{{ route('admin.manage-supplier.store') }}" method="POST" id="createSupplierFormIntegrated" @submit.prevent="window.submitCreateSupplierIntegrated($el)">
            @csrf
            
            <div class="p-8 max-h-[70vh] overflow-y-auto space-y-8 custom-scrollbar">
                
                {{-- SECTION 1: INFORMASI DASAR --}}
                <div class="space-y-6">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-indigo-600">Informasi Dasar</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Jenis Supplier <span class="text-rose-500">*</span></label>
                            <select name="type_supplier" id="fs_type" class="w-full mt-1.5 text-xs bg-gray-50 border-slate-200 rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                <option value="">Pilih Jenis</option>
                                @foreach($supplierTypes as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Nama Instansi/Toko <span class="text-rose-500">*</span></label>
                            <input type="text" name="name_supplier" id="fs_name" class="w-full mt-1.5 text-xs bg-gray-50 border-slate-200 rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="Misal: PT. Sembako Jaya">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Nama Pimpinan <span class="text-rose-500">*</span></label>
                            <input type="text" name="leader_name" id="fs_leader" class="w-full mt-1.5 text-xs bg-gray-50 border-slate-200 rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="Nama lengkap">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">No. HP/Telepon <span class="text-rose-500">*</span></label>
                            <input type="text" name="phone" id="fs_phone" 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="w-full mt-1.5 text-xs bg-gray-50 border-slate-200 rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Komoditas Utama <span class="text-rose-500">*</span></label>
                            <textarea name="commodities" id="fs_commodities" rows="2" class="w-full mt-1.5 text-xs bg-gray-50 border-slate-200 rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="Contoh: Beras, Telur, Daging Ayam, Sayuran"></textarea>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: LOKASI --}}
                <div class="space-y-6 pt-6 border-t border-slate-100">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-indigo-600">Lokasi Supplier</h3>
                    
                    {{-- Hidden inputs for clean names --}}
                    <input type="hidden" name="province_name" id="fs_prov_name">
                    <input type="hidden" name="regency_name" id="fs_reg_name">
                    <input type="hidden" name="district_name" id="fs_dist_name">
                    <input type="hidden" name="village_name" id="fs_vill_name">

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Provinsi <span class="text-rose-500">*</span></label>
                            <select name="province" id="fs_prov" 
                                class="w-full mt-1.5 text-[11px] bg-gray-50 border-slate-200 rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all capitalize">
                                <option value="">Pilih Provinsi</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Kab/Kota <span class="text-rose-500">*</span></label>
                            <select name="regency" id="fs_reg" disabled
                                class="w-full mt-1.5 text-[11px] border-slate-200 rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all disabled:opacity-50 disabled:cursor-not-allowed capitalize input-disabled">
                                <option value="">Pilih Kabupaten</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Kecamatan <span class="text-rose-500">*</span></label>
                            <select name="district" id="fs_dist" disabled
                                class="w-full mt-1.5 text-[11px] border-slate-200 rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all disabled:opacity-50 disabled:cursor-not-allowed capitalize input-disabled">
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Kelurahan <span class="text-rose-500">*</span></label>
                            <select name="village" id="fs_vill" disabled
                                class="w-full mt-1.5 text-[11px] border-slate-200 rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all disabled:opacity-50 disabled:cursor-not-allowed capitalize input-disabled">
                                <option value="">Pilih Desa/Kelurahan</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Alamat Detail <span class="text-rose-500">*</span></label>
                            <textarea name="address" id="fs_address" rows="1" class="w-full mt-1.5 text-xs bg-gray-50 border-slate-200 rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="Nama jalan, nomor rumah, RT/RW"></textarea>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Kode Pos <span class="text-rose-500">*</span></label>
                            <input type="text" name="postal_code" id="fs_postal" 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="w-full mt-1.5 text-xs bg-gray-50 border-slate-200 rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="811xx">
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-4">
                <button type="button" @click="showCreateSupplierModal = false" class="flex-1 py-3 text-[10px] font-bold uppercase text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all">Batal</button>
                <button type="submit" id="btnSubmitSupplierIntegrated" class="flex-1 py-3 text-[10px] font-bold uppercase text-white bg-indigo-600 rounded-xl shadow-lg hover:bg-indigo-700 transition-all active:scale-95">Simpan & Hubungkan</button>
            </div>
        </form>
    </div>
</div>

<script>
    (function() {
        const apiBase = "/api-wilayah";
        const sel = {
            p: document.getElementById('fs_prov'),
            r: document.getElementById('fs_reg'),
            d: document.getElementById('fs_dist'),
            v: document.getElementById('fs_vill')
        };
        const hid = {
            p: document.getElementById('fs_prov_name'),
            r: document.getElementById('fs_reg_name'),
            d: document.getElementById('fs_dist_name'),
            v: document.getElementById('fs_vill_name')
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

        // Initialize Provinces
        window.initSupplierWilayah = function() {
            if (sel.p && sel.p.options.length <= 1) {
                populate(sel.p, 'provinces', 'Provinsi');
            }
        };

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
            document.querySelectorAll('#createSupplierFormIntegrated .input-error').forEach(el => el.classList.remove('input-error'));
            document.querySelectorAll('#createSupplierFormIntegrated .text-error-custom').forEach(el => {
                el.classList.add('hidden');
                el.innerText = '';
            });
        }

        function showFieldError(id, msg) {
            const el = document.getElementById(id);
            if (!el) return;

            el.classList.add('input-error');

            const errorId = 'error-' + id;
            let errorEl = document.getElementById(errorId);
            if (!errorEl) {
                errorEl = document.createElement('span');
                errorEl.id = errorId;
                errorEl.className = 'text-error-custom';
                el.parentNode.appendChild(errorEl);
            }
            errorEl.innerText = '* ' + msg;
            errorEl.classList.remove('hidden');
        }

        window.submitCreateSupplierIntegrated = async function(formElement) {
            clearErrors();
            const btnSubmit = document.getElementById('btnSubmitSupplierIntegrated');
            
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
                    // Success!
                    window.dispatchEvent(new CustomEvent('supplier-created-integrated', {
                        detail: { supplier: result.supplier }
                    }));
                    formElement.reset();
                    // Reset dropdowns
                    [sel.r, sel.d, sel.v].forEach(s => setSelectState(s, true));
                } else {
                    if (btnSubmit) {
                        btnSubmit.disabled = false;
                        btnSubmit.innerHTML = "Simpan & Hubungkan";
                    }

                    if (result.errors) {
                        Object.keys(result.errors).forEach(key => {
                            let fieldId = 'fs_' + key;
                            if (key === 'province_name') fieldId = 'fs_prov';
                            if (key === 'regency_name')  fieldId = 'fs_reg';
                            if (key === 'district_name') fieldId = 'fs_dist';
                            if (key === 'village_name')  fieldId = 'fs_vill';
                            if (key === 'postal_code')   fieldId = 'fs_postal';
                            if (key === 'type_supplier') fieldId = 'fs_type';
                            if (key === 'name_supplier') fieldId = 'fs_name';
                            if (key === 'leader_name')   fieldId = 'fs_leader';
                            if (key === 'commodities')   fieldId = 'fs_commodities';

                            showFieldError(fieldId, result.errors[key][0]);
                        });
                        
                        const firstErr = document.querySelector('#createSupplierFormIntegrated .input-error');
                        if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            } catch (err) {
                if (btnSubmit) {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = "Simpan & Hubungkan";
                }
                console.error('Submit Error:', err);
            }
        };
    })();
</script>
