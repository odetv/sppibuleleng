<div x-show="showEditBeneficiaryModal" 
    class="fixed inset-0 z-[2000] flex items-center justify-center p-4 text-left" 
    @open-edit-beneficiary.window="
        selectedPM = JSON.parse(JSON.stringify($event.detail.beneficiary));
        showEditBeneficiaryModal = true; 
        setTimeout(() => {
            initEditBeneficiaryMap(selectedPM);
        }, 300)
    "
    x-cloak>
    
    <div x-show="showEditBeneficiaryModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showEditBeneficiaryModal = false"></div>

    <div x-show="showEditBeneficiaryModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative w-full max-w-5xl bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden font-sans text-sm">
        <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center text-slate-800">
            <div class="flex items-center gap-3">
                <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </span>
                <h3 class="text-[14px] font-bold uppercase tracking-widest text-slate-800">Ubah Data PM</h3>
            </div>
            <button @click="showEditBeneficiaryModal = false" class="text-slate-400 hover:text-slate-600 text-2xl duration-200">&times;</button>
        </div>

        <form :action="'{{ route('admin.manage-beneficiary.index') }}/' + selectedPM.id_beneficiary + '/update'" method="POST" id="form-edit-beneficiary-integrated" @submit.prevent="submitEditBeneficiary($el, selectedPM)" x-data="{ categories: { 'Sekolah': ['Paud', 'TK', 'SD', 'SMP', 'SMA', 'SMK', 'Pesantren', 'RA', 'MI', 'MA', 'Pratama Widyalaya', 'Madyama Widyalaya', 'Utama Widyalaya', 'Utama Widyalaya Kejuruan'], 'Posyandu': ['Ibu Hamil', 'Ibu Menyusui', 'Balita'] } }">
            @csrf
            @method('PATCH')
            
            <div class="p-8 max-h-[70vh] overflow-y-auto space-y-10 custom-scrollbar">
                {{-- SECTION 1: IDENTITAS UTAMA --}}
                <div class="space-y-6">
                    <h4 class="text-[11px] font-bold text-indigo-600 uppercase tracking-widest border-b border-indigo-50 pb-2">Identitas Utama</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Penerima Manfaat (PM) <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" x-model="selectedPM.name" required class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="Nama Sekolah / Posyandu / Kelompok">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tipe Kelompok <span class="text-rose-500">*</span></label>
                                <select name="group_type" x-model="selectedPM.group_type" @change="selectedPM.category = ''" required class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                    <option value="" disabled>Pilih Tipe</option>
                                    <option value="Sekolah">Sekolah</option>
                                    <option value="Posyandu">Posyandu</option>
                                </select>
                            </div>
                             <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kategori <span class="text-rose-500">*</span></label>
                                <select required name="category" x-model="selectedPM.category" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    <template x-if="selectedPM.group_type && categories[selectedPM.group_type]">
                                        <template x-for="cat in categories[selectedPM.group_type]" :key="cat">
                                            <option :value="cat" x-text="cat"></option>
                                        </template>
                                    </template>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kode PM <span class="text-rose-500">*</span></label>
                                <input type="text" name="code" x-model="selectedPM.code" required class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="Kode PM">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tipe Kepemilikan <span class="text-rose-500">*</span></label>
                                <select name="ownership_type" x-model="selectedPM.ownership_type" required class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                    <option value="" disabled>Pilih</option>
                                    <option value="Negeri">Negeri</option>
                                    <option value="Swasta">Swasta</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: ALAMAT & LOKASI --}}
                <div class="space-y-6">
                    <h4 class="text-[11px] font-bold text-indigo-600 uppercase tracking-widest border-b border-indigo-50 pb-2">Informasi Alamat & Lokasi</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <input type="hidden" name="province" id="eb_prov_name" :value="selectedPM.province">
                                <input type="hidden" name="regency" id="eb_reg_name" :value="selectedPM.regency">
                                <input type="hidden" name="district" id="eb_dist_name" :value="selectedPM.district">
                                <input type="hidden" name="id_sppg_unit" x-model="selectedPM.id_sppg_unit">
                                <input type="hidden" name="is_active" :value="(selectedPM.is_active == 1 || selectedPM.is_active == true || selectedPM.is_active === '1') ? 1 : 0">
                                <input type="hidden" name="village" id="eb_vill_name" :value="selectedPM.village">

                                 <select required name="province_code" id="eb_prov" class="px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                                <select required name="regency_code" id="eb_reg" disabled class="input-disabled px-4 py-2.5 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                    <option value="">Pilih Kabupaten</option>
                                </select>
                                <select required name="district_code" id="eb_dist" disabled class="input-disabled px-4 py-2.5 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                                <select required name="village_code" id="eb_vill" disabled class="input-disabled px-4 py-2.5 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                    <option value="">Pilih Desa</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="sm:col-span-2">
                                    <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Alamat Jalan Lengkap <span class="text-rose-500">*</span></label>
                                    <textarea name="address" x-model="selectedPM.address" required rows="2" placeholder="Alamat Jalan Lengkap" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all"></textarea>
                                </div>
                                 <div>
                                    <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kode Pos <span class="text-rose-500">*</span></label>
                                    <input required type="text" name="postal_code" x-model="selectedPM.postal_code" placeholder="Kode Pos" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
                        <div>
                            <div id="map-edit-beneficiary-integrated" style="height: 300px; width: 100%; border-radius: 0.75rem; border: 4px solid white; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);"></div>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Latitude <span class="text-rose-500">*</span></label>
                                    <input type="text" name="latitude_gps" id="beneficiary_e_lat" required x-model="selectedPM.latitude_gps" readonly class="w-full mt-2 px-3 py-2 bg-slate-100 rounded-lg text-[10px] text-slate-500 focus:outline-none cursor-not-allowed" placeholder="Lat (Klik Peta)">
                                </div>
                                <div>
                                    <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Longitude <span class="text-rose-500">*</span></label>
                                    <input type="text" name="longitude_gps" id="beneficiary_e_lng" required x-model="selectedPM.longitude_gps" readonly class="w-full mt-2 px-3 py-2 bg-slate-100 rounded-lg text-[10px] text-slate-500 focus:outline-none cursor-not-allowed" placeholder="Lng (Klik Peta)">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: RINCIAN PORSI --}}
                <div class="space-y-6">
                    <h4 class="text-[11px] font-bold text-indigo-600 uppercase tracking-widest border-b border-indigo-50 pb-2">Rincian Porsi Penerimaan</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                         <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Porsi Kecil (L) <span class="text-rose-500">*</span></label>
                            <input type="number" name="small_portion_male" required x-model="selectedPM.small_portion_male" min="0" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm" placeholder="0">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Porsi Kecil (P) <span class="text-rose-500">*</span></label>
                            <input type="number" name="small_portion_female" required x-model="selectedPM.small_portion_female" min="0" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm" placeholder="0">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Porsi Besar (L) <span class="text-rose-500">*</span></label>
                            <input type="number" name="large_portion_male" required x-model="selectedPM.large_portion_male" min="0" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm" placeholder="0">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Porsi Besar (P) <span class="text-rose-500">*</span></label>
                            <input type="number" name="large_portion_female" required x-model="selectedPM.large_portion_female" min="0" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm" placeholder="0">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Porsi Guru <span class="text-rose-500">*</span></label>
                            <input type="number" name="teacher_portion" required x-model="selectedPM.teacher_portion" min="0" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm" placeholder="0">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Porsi Staff <span class="text-rose-500">*</span></label>
                            <input type="number" name="staff_portion" required x-model="selectedPM.staff_portion" min="0" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm" placeholder="0">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Porsi Kader <span class="text-rose-500">*</span></label>
                            <input type="number" name="cadre_portion" required x-model="selectedPM.cadre_portion" min="0" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm" placeholder="0">
                        </div>
                    </div>
                </div>

                {{-- SECTION 4: PIC --}}
                <div class="space-y-6">
                    <h4 class="text-[11px] font-bold text-indigo-600 uppercase tracking-widest border-b border-indigo-50 pb-2">Informasi Penanggung Jawab (PIC)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                         <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama PIC <span class="text-rose-500">*</span></label>
                            <input type="text" name="pic_name" required x-model="selectedPM.pic_name" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Nama Lengkap">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Telepon PIC <span class="text-rose-500">*</span></label>
                            <input type="text" name="pic_phone" required x-model="selectedPM.pic_phone" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="812xxxxx">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Email PIC <span class="text-rose-500">*</span></label>
                            <input type="email" name="pic_email" required x-model="selectedPM.pic_email" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="email@contoh.com">
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-4">
                <button type="button" @click="showEditBeneficiaryModal = false" class="flex-1 py-4 text-[11px] font-bold uppercase text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition-all">Batal</button>
                <button type="submit" class="flex-1 py-4 text-[11px] font-bold uppercase text-white bg-slate-800 rounded-xl shadow-lg hover:bg-slate-900 transition-all active:scale-95">Simpan Perubahan PM</button>
            </div>
        </form>
    </div>
</div>

<script>
    let mapEditBeneficiary = null;
    let markerEditBeneficiary = null;

    function initEditBeneficiaryMap(pm) {
        if (!mapEditBeneficiary) {
            mapEditBeneficiary = L.map('map-edit-beneficiary-integrated').setView([-8.1127, 115.0911], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(mapEditBeneficiary);

            mapEditBeneficiary.on('click', function(e) {
                const { lat, lng } = e.latlng;
                document.getElementById('beneficiary_e_lat').value = lat.toFixed(8);
                document.getElementById('beneficiary_e_lng').value = lng.toFixed(8);
                
                // Trigger Alpine input manually to be safe
                document.getElementById('beneficiary_e_lat').dispatchEvent(new Event('input'));
                document.getElementById('beneficiary_e_lng').dispatchEvent(new Event('input'));

                if (markerEditBeneficiary) {
                    markerEditBeneficiary.setLatLng(e.latlng);
                } else {
                    markerEditBeneficiary = L.marker(e.latlng).addTo(mapEditBeneficiary);
                }
            });
        }

        const lat = parseFloat(pm.latitude_gps) || -8.1127;
        const lng = parseFloat(pm.longitude_gps) || 115.0911;

        mapEditBeneficiary.setView([lat, lng], 15);
        if (markerEditBeneficiary) {
            markerEditBeneficiary.setLatLng([lat, lng]);
        } else {
            markerEditBeneficiary = L.marker([lat, lng]).addTo(mapEditBeneficiary);
        }
        
        setTimeout(() => mapEditBeneficiary.invalidateSize(), 150);

        // Chain populate address fields from name to code logic
        populateEditAddressFields(pm);
    }

    async function populateEditAddressFields(pm) {
        const apiBase = "/api-wilayah";
        const sel = {
            p: document.getElementById('eb_prov'),
            r: document.getElementById('eb_reg'),
            d: document.getElementById('eb_dist'),
            v: document.getElementById('eb_vill')
        };
        const hid = {
            p: document.getElementById('eb_prov_name'),
            r: document.getElementById('eb_reg_name'),
            d: document.getElementById('eb_dist_name'),
            v: document.getElementById('eb_vill_name')
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

        // Reset
        [sel.r, sel.d, sel.v].forEach(s => {
            s.innerHTML = '<option value="">Pilih...</option>';
            setSelectState(s, true);
        });

        // Chain fetch based on NAMES stored in model
        const pCode = await populate(sel.p, 'provinces', 'Provinsi', pm.province);
        if (pCode) {
            const rCode = await populate(sel.r, `regencies/${pCode}`, 'Kabupaten', pm.regency);
            if (rCode) {
                const dCode = await populate(sel.d, `districts/${rCode}`, 'Kecamatan', pm.district);
                if (dCode) {
                    await populate(sel.v, `villages/${dCode}`, 'Desa', pm.village);
                }
            }
        }

        // Event Handlers
        sel.p.onchange = async function() {
            const name = this.options[this.selectedIndex].getAttribute('data-name') || '';
            pm.province = name;
            hid.p.value = name;
            sel.r.innerHTML = sel.d.innerHTML = sel.v.innerHTML = '<option value="">Pilih...</option>';
            [sel.r, sel.d, sel.v].forEach(s => setSelectState(s, true));
            if (this.value) await populate(sel.r, `regencies/${this.value}`, 'Kabupaten');
        };
        sel.r.onchange = async function() {
            const name = this.options[this.selectedIndex].getAttribute('data-name') || '';
            pm.regency = name;
            hid.r.value = name;
            sel.d.innerHTML = sel.v.innerHTML = '<option value="">Pilih...</option>';
            [sel.d, sel.v].forEach(s => setSelectState(s, true));
            if (this.value) await populate(sel.d, `districts/${this.value}`, 'Kecamatan');
        };
        sel.d.onchange = async function() {
            const name = this.options[this.selectedIndex].getAttribute('data-name') || '';
            pm.district = name;
            hid.d.value = name;
            sel.v.innerHTML = '<option value="">Pilih...</option>';
            setSelectState(sel.v, true);
            if (this.value) await populate(sel.v, `villages/${this.value}`, 'Desa');
        };
        sel.v.onchange = function() {
            const name = this.options[this.selectedIndex].getAttribute('data-name') || '';
            pm.village = name;
            hid.v.value = name;
        };
    }

    window.submitEditBeneficiary = async function(form, pm) {
        const formData = new FormData(form);
        const btn = form.querySelector('button[type="submit"]');
        const oldText = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = "Menyimpan...";
        
        try {
            const resp = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const data = await resp.json();
            if (resp.ok) {
                // Beri notifikasi ke modal edit SPPG agar list beneficiary di-refresh
                window.dispatchEvent(new CustomEvent('beneficiary-updated-integrated', { 
                    detail: { beneficiary: pm } 
                }));
            } else {
                alert('Gagal: ' + JSON.stringify(data.errors));
            }
        } catch (e) {
            alert('Error: ' + e.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = oldText;
        }
    }
</script>
