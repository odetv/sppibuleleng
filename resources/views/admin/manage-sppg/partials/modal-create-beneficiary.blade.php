<div x-show="showCreateBeneficiaryModal" 
    class="fixed inset-0 z-[2000] flex items-center justify-center p-4 text-left" 
    @open-create-beneficiary.window="showCreateBeneficiaryModal = true; setTimeout(() => initCreateBeneficiaryMap(), 300)"
    x-cloak>
    
    <div x-show="showCreateBeneficiaryModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showCreateBeneficiaryModal = false"></div>

    <div x-show="showCreateBeneficiaryModal"
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
                <h3 class="text-[14px] font-bold uppercase tracking-widest text-slate-800">Tambah PM Baru</h3>
            </div>
            <button @click="showCreateBeneficiaryModal = false" class="text-slate-400 hover:text-slate-600 text-2xl duration-200">&times;</button>
        </div>

        <form action="{{ route('admin.manage-beneficiary.store') }}" method="POST" id="form-create-beneficiary-integrated" @submit.prevent="submitIntegratedBeneficiary($el)" x-data="{ group_type: '', category: '', categories: { 'Sekolah': ['Paud', 'TK', 'SD', 'SMP', 'SMA', 'SMK', 'Pesantren', 'RA', 'MI', 'MA', 'Pratama Widyalaya', 'Madyama Widyalaya', 'Utama Widyalaya', 'Utama Widyalaya Kejuruan'], 'Posyandu': ['Ibu Hamil', 'Ibu Menyusui', 'Balita'] } }">
            @csrf
            {{-- Hidden link to the currently edited SPPG unit --}}
            <input type="hidden" name="id_sppg_unit" :value="selectedUnit.id_sppg_unit">
            <input type="hidden" name="is_active" value="1">
            
            <div class="p-8 max-h-[70vh] overflow-y-auto space-y-10 custom-scrollbar">
                {{-- SECTION 1: IDENTITAS UTAMA --}}
                <div class="space-y-6">
                    <h4 class="text-[11px] font-bold text-indigo-600 uppercase tracking-widest border-b border-indigo-50 pb-2">Identitas Utama</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Penerima Manfaat (PM) <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="Nama Sekolah / Posyandu / Kelompok">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tipe Kelompok <span class="text-rose-500">*</span></label>
                                <select name="group_type" x-model="group_type" @change="category = ''" required class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                    <option value="" disabled selected>Pilih Tipe</option>
                                    <option value="Sekolah">Sekolah</option>
                                    <option value="Posyandu">Posyandu</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kategori <span class="text-rose-500">*</span></label>
                                <select name="category" x-model="category" required class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    <template x-if="group_type && categories[group_type]">
                                        <template x-for="cat in categories[group_type]" :key="cat">
                                            <option :value="cat" x-text="cat"></option>
                                        </template>
                                    </template>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kode PM <span class="text-rose-500">*</span></label>
                                <input type="text" name="code" required class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Kode PM">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tipe Kepemilikan <span class="text-rose-500">*</span></label>
                                <select name="ownership_type" required class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                    <option value="" disabled selected>Pilih</option>
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
                                <input type="hidden" name="province" id="ib_prov_name">
                                <input type="hidden" name="regency" id="ib_reg_name">
                                <input type="hidden" name="district" id="ib_dist_name">
                                <input type="hidden" name="village" id="ib_vill_name">

                                <select name="province_code" id="ib_prov" class="px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                                <select name="regency_code" id="ib_reg" disabled class="input-disabled px-4 py-2.5 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                    <option value="">Pilih Kabupaten</option>
                                </select>
                                <select name="district_code" id="ib_dist" disabled class="input-disabled px-4 py-2.5 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                                <select name="village_code" id="ib_vill" disabled class="input-disabled px-4 py-2.5 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                    <option value="">Pilih Desa</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="sm:col-span-2">
                                    <textarea name="address" required rows="2" placeholder="Alamat Jalan Lengkap" class="w-full px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all"></textarea>
                                </div>
                                <div>
                                    <input type="text" name="postal_code" required placeholder="Kode Pos" class="w-full px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
                        <div>
                            <div id="map-create-beneficiary-integrated" style="height: 300px; width: 100%; border-radius: 0.75rem; border: 4px solid white; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);"></div>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <input type="text" name="latitude_gps" id="beneficiary_c_lat" readonly class="px-3 py-2 bg-slate-100 rounded-lg text-[10px] text-slate-500 focus:outline-none cursor-not-allowed" placeholder="Lat (Klik Peta)">
                                <input type="text" name="longitude_gps" id="beneficiary_c_lng" readonly class="px-3 py-2 bg-slate-100 rounded-lg text-[10px] text-slate-500 focus:outline-none cursor-not-allowed" placeholder="Lng (Klik Peta)">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: RINCIAN PORSI --}}
                <div class="space-y-6">
                    <h4 class="text-[11px] font-bold text-indigo-600 uppercase tracking-widest border-b border-indigo-50 pb-2">Rincian Porsi Penerimaan</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Porsi Kecil (L)</label>
                            <input type="number" name="small_portion_male" min="0" value="0" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm" placeholder="0">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Porsi Kecil (P)</label>
                            <input type="number" name="small_portion_female" min="0" value="0" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm" placeholder="0">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Porsi Besar (L)</label>
                            <input type="number" name="large_portion_male" min="0" value="0" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm" placeholder="0">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Porsi Besar (P)</label>
                            <input type="number" name="large_portion_female" min="0" value="0" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm" placeholder="0">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Porsi Guru</label>
                            <input type="number" name="teacher_portion" min="0" value="0" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm" placeholder="0">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Porsi Staff</label>
                            <input type="number" name="staff_portion" min="0" value="0" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm" placeholder="0">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Porsi Kader</label>
                            <input type="number" name="cadre_portion" min="0" value="0" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm" placeholder="0">
                        </div>
                    </div>
                </div>

                {{-- SECTION 4: PIC --}}
                <div class="space-y-6">
                    <h4 class="text-[11px] font-bold text-indigo-600 uppercase tracking-widest border-b border-indigo-50 pb-2">Informasi Penanggung Jawab (PIC)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama PIC</label>
                            <input type="text" name="pic_name" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Nama Lengkap">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Telepon PIC</label>
                            <input type="text" name="pic_phone" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="812xxxxx">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Email PIC</label>
                            <input type="email" name="pic_email" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="email@contoh.com">
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-4">
                <button type="button" @click="showCreateBeneficiaryModal = false" class="flex-1 py-4 text-[11px] font-bold uppercase text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition-all">Batal</button>
                <button type="submit" class="flex-1 py-4 text-[11px] font-bold uppercase text-white bg-slate-800 rounded-xl shadow-lg hover:bg-slate-900 transition-all active:scale-95">Simpan PM Baru</button>
            </div>
        </form>
    </div>
</div>

<script>
    let mapIntegratedBeneficiary = null;
    let markerIntegratedBeneficiary = null;

    function initCreateBeneficiaryMap() {
        if (!mapIntegratedBeneficiary) {
            mapIntegratedBeneficiary = L.map('map-create-beneficiary-integrated').setView([-8.1127, 115.0911], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapIntegratedBeneficiary);

            mapIntegratedBeneficiary.on('click', function(e) {
                const { lat, lng } = e.latlng;
                document.getElementById('beneficiary_c_lat').value = lat.toFixed(8);
                document.getElementById('beneficiary_c_lng').value = lng.toFixed(8);

                if (markerIntegratedBeneficiary) {
                    markerIntegratedBeneficiary.setLatLng(e.latlng);
                } else {
                    markerIntegratedBeneficiary = L.marker(e.latlng).addTo(mapIntegratedBeneficiary);
                }
            });
        }
        setTimeout(() => mapIntegratedBeneficiary.invalidateSize(), 150);
    }

    (function() {
        const apiBase = "/api-wilayah";
        const sel = {
            p: document.getElementById('ib_prov'),
            r: document.getElementById('ib_reg'),
            d: document.getElementById('ib_dist'),
            v: document.getElementById('ib_vill')
        };
        const hid = {
            p: document.getElementById('ib_prov_name'),
            r: document.getElementById('ib_reg_name'),
            d: document.getElementById('ib_dist_name'),
            v: document.getElementById('ib_vill_name')
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
                if (data?.[0] && mapIntegratedBeneficiary) {
                    mapIntegratedBeneficiary.flyTo([data[0].lat, data[0].lon], 14, {
                        animate: true,
                        duration: 1.5
                    });
                }
            } catch (e) {}
        }

        document.addEventListener('DOMContentLoaded', () => {
            populate(sel.p, 'provinces', 'Provinsi');
        });

        sel.p.onchange = async function() {
            hid.p.value = this.options[this.selectedIndex].getAttribute('data-name') || '';
            sel.r.innerHTML = sel.d.innerHTML = sel.v.innerHTML = '<option value="">Pilih...</option>';
            [sel.r, sel.d, sel.v].forEach(s => setSelectState(s, true));
            if (this.value) await populate(sel.r, `regencies/${this.value}`, 'Kabupaten');
            moveMapToLocation();
        };
        sel.r.onchange = async function() {
            hid.r.value = this.options[this.selectedIndex].getAttribute('data-name') || '';
            sel.d.innerHTML = sel.v.innerHTML = '<option value="">Pilih...</option>';
            [sel.d, sel.v].forEach(s => setSelectState(s, true));
            if (this.value) await populate(sel.d, `districts/${this.value}`, 'Kecamatan');
            moveMapToLocation();
        };
        sel.d.onchange = async function() {
            hid.d.value = this.options[this.selectedIndex].getAttribute('data-name') || '';
            sel.v.innerHTML = '<option value="">Pilih...</option>';
            setSelectState(sel.v, true);
            if (this.value) await populate(sel.v, `villages/${this.value}`, 'Desa');
            moveMapToLocation();
        };
        sel.v.onchange = function() {
            hid.v.value = this.options[this.selectedIndex].getAttribute('data-name') || '';
            moveMapToLocation();
        };
    })();

    window.submitIntegratedBeneficiary = async function(form) {
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
                window.dispatchEvent(new CustomEvent('beneficiary-created-integrated', { 
                    detail: { beneficiary: data.beneficiary } 
                }));
                
                form.reset();
                if (markerIntegratedBeneficiary) markerIntegratedBeneficiary.remove();
                markerIntegratedBeneficiary = null;
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
