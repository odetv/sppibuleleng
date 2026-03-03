<div x-show="showCreatePmModal" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    class="fixed inset-0 z-[2000] flex items-center justify-center p-4 text-left" 
    @open-create-pm.window="showCreatePmModal = true; setTimeout(() => initCreatePmMap(), 300)"
    x-cloak>
    
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showCreatePmModal = false"></div>

    <div class="relative w-full max-w-5xl bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden transform transition-all font-sans text-sm">
        <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center text-slate-800">
            <div class="flex items-center gap-3">
                <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
                <h3 class="text-[14px] font-bold uppercase tracking-widest text-slate-800">Tambah PM Baru</h3>
            </div>
            <button @click="showCreatePmModal = false" class="text-slate-400 hover:text-slate-600 text-2xl duration-200">&times;</button>
        </div>

        <form action="{{ route('admin.manage-beneficiary.store') }}" method="POST" id="form-create-pm-integrated" @submit.prevent="submitIntegratedPm($el)">
            @csrf
            {{-- Hidden link to the currently edited SPPG unit --}}
            <input type="hidden" name="id_sppg_unit" :value="selectedUnit.id_sppg_unit">
            
            <div class="p-8 max-h-[70vh] overflow-y-auto space-y-10 custom-scrollbar">
                {{-- SECTION 1: IDENTITAS UTAMA --}}
                <div class="space-y-6">
                    <h4 class="text-[11px] font-bold text-indigo-600 uppercase tracking-widest border-b border-indigo-50 pb-2">Identitas Utama</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Penerima Manfaat (PM) <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="Nama Sekolah / Posyandu / Kelompok">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tipe Kelompok <span class="text-rose-500">*</span></label>
                                <select name="group_type" required class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                    <option value="">Pilih Tipe</option>
                                    <option value="Sekolah">Sekolah</option>
                                    <option value="Posyandu">Posyandu</option>
                                    <option value="Kelompok Lainnya">Kelompok Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kategori</label>
                                <input type="text" name="category" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Contoh: PAUD, SD, SMP">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kode PM</label>
                                <input type="text" name="code" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Kode Opsional">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Jenis Kepemilikan</label>
                                <select name="ownership_type" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                    <option value="">Pilih</option>
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
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="province" placeholder="Provinsi" class="px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <input type="text" name="regency" placeholder="Kabupaten" class="px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <input type="text" name="district" placeholder="Kecamatan" class="px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <input type="text" name="village" placeholder="Desa" class="px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-2">
                                    <input type="text" name="address" placeholder="Alamat Jalan Lengkap" class="w-full px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <input type="text" name="postal_code" placeholder="Kode Pos" class="w-full px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
                        <div>
                            <div id="map-create-pm-integrated" style="height: 180px; width: 100%; border-radius: 0.75rem;" class="border-2 border-slate-100 shadow-inner"></div>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <input type="text" name="latitude_gps" id="pm_c_lat" readonly class="px-3 py-2 bg-slate-100 rounded-lg text-[10px] text-slate-500" placeholder="Lat (Klik Peta)">
                                <input type="text" name="longitude_gps" id="pm_c_lng" readonly class="px-3 py-2 bg-slate-100 rounded-lg text-[10px] text-slate-500" placeholder="Lng (Klik Peta)">
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
                            <input type="number" name="small_portion_male" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm" placeholder="0">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Porsi Kecil (P)</label>
                            <input type="number" name="small_portion_female" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm" placeholder="0">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Porsi Besar (L)</label>
                            <input type="number" name="large_portion_male" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm" placeholder="0">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Porsi Besar (P)</label>
                            <input type="number" name="large_portion_female" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm" placeholder="0">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Porsi Guru</label>
                            <input type="number" name="teacher_portion" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm" placeholder="0">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Porsi Staff</label>
                            <input type="number" name="staff_portion" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm" placeholder="0">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Porsi Kader</label>
                            <input type="number" name="cadre_portion" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm" placeholder="0">
                        </div>
                        <div class="flex items-end">
                            <label class="flex items-center gap-3 cursor-pointer p-2.5 bg-slate-50 rounded-lg w-full">
                                <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <span class="text-[11px] font-bold text-slate-600 uppercase">Status Aktif</span>
                            </label>
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
                            <div class="flex mt-2 rounded-lg overflow-hidden ring-1 ring-gray-100 focus-within:ring-2 focus-within:ring-indigo-500">
                                <span class="px-3 bg-gray-100 flex items-center text-xs font-bold text-slate-500">+62</span>
                                <input type="text" name="pic_phone" class="flex-1 px-4 py-2.5 bg-gray-50 border-none text-sm outline-none" placeholder="812xxxxx">
                            </div>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Email PIC</label>
                            <input type="email" name="pic_email" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="email@contoh.com">
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-4">
                <button type="button" @click="showCreatePmModal = false" class="flex-1 py-4 text-[11px] font-bold uppercase text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition-all">Batal</button>
                <button type="submit" class="flex-1 py-4 text-[11px] font-bold uppercase text-white bg-slate-800 rounded-xl shadow-lg hover:bg-slate-900 transition-all active:scale-95">Simpan PM Baru</button>
            </div>
        </form>
    </div>
</div>

<script>
    let mapIntegratedPm = null;
    let markerIntegratedPm = null;

    function initCreatePmMap() {
        if (!mapIntegratedPm) {
            mapIntegratedPm = L.map('map-create-pm-integrated').setView([-8.1127, 115.0911], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapIntegratedPm);

            mapIntegratedPm.on('click', function(e) {
                const { lat, lng } = e.latlng;
                document.getElementById('pm_c_lat').value = lat.toFixed(8);
                document.getElementById('pm_c_lng').value = lng.toFixed(8);

                if (markerIntegratedPm) {
                    markerIntegratedPm.setLatLng(e.latlng);
                } else {
                    markerIntegratedPm = L.marker(e.latlng).addTo(mapIntegratedPm);
                }
            });
        }
        setTimeout(() => mapIntegratedPm.invalidateSize(), 150);
    }

    window.submitIntegratedPm = async function(form) {
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
                window.dispatchEvent(new CustomEvent('pm-created-integrated', { detail: data }));
                form.reset();
                if (markerIntegratedPm) markerIntegratedPm.remove();
                markerIntegratedPm = null;
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
