<div x-show="showEditModal" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    class="fixed inset-0 z-[1000] flex items-center justify-center p-4 text-left" 
    @init-edit-beneficiary.window="setTimeout(() => initEditMap($event.detail), 300)"
    x-cloak>
    
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showEditModal = false"></div>

    <div class="relative w-full max-w-6xl bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden transform transition-all font-sans text-sm">
        <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center text-slate-800">
            <div class="flex items-center gap-3">
                <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </span>
                <h3 class="text-[14px] font-bold uppercase tracking-widest">Edit Penerima Manfaat</h3>
            </div>
            <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 text-2xl duration-200">&times;</button>
        </div>

        <form :action="`/admin/manage-beneficiary/${selectedBeneficiary.id_beneficiary}/update`" method="POST" id="form-edit-beneficiary">
            @csrf
            @method('PATCH')
            
            <div class="p-8 max-h-[75vh] overflow-y-auto space-y-10 custom-scrollbar">
                {{-- SECTION 1: IDENTITAS --}}
                <div class="space-y-6">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Identitas PM</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-1">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kode</label>
                            <input type="text" name="code" x-model="selectedBeneficiary.code" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div class="md:col-span-1">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tipe Kelompok</label>
                            <select name="group_type" x-model="selectedBeneficiary.group_type" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                <option value="">Pilih Tipe</option>
                                <option value="Sekolah">Sekolah</option>
                                <option value="Posyandu">Posyandu</option>
                                <option value="Kelompok Lainnya">Kelompok Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama PM <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" x-model="selectedBeneficiary.name" required class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kategori</label>
                            <input type="text" name="category" x-model="selectedBeneficiary.category" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">SPPG Unit</label>
                        <select name="id_sppg_unit" x-model="selectedBeneficiary.id_sppg_unit" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                            <option value="">-- Hubungkan ke Unit --</option>
                            @foreach($sppgUnits as $unit)
                                <option value="{{ $unit->id_sppg_unit }}">{{ $unit->name }} ({{ $unit->id_sppg_unit }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr class="border-slate-100">

                {{-- SECTION 2: PIC --}}
                <div class="pt-2">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">PIC (Penanggung Jawab)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama PIC</label>
                            <input type="text" name="pic_name" x-model="selectedBeneficiary.pic_name" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Telepon PIC</label>
                            <input type="text" name="pic_phone" x-model="selectedBeneficiary.pic_phone" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Email PIC</label>
                            <input type="email" name="pic_email" x-model="selectedBeneficiary.pic_email" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100">

                {{-- SECTION 3: LOKASI --}}
                <div class="pt-2">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Informasi Lokasi & Alamat</h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Provinsi</label>
                            <input type="text" name="province" x-model="selectedBeneficiary.province" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kabupaten</label>
                            <input type="text" name="regency" x-model="selectedBeneficiary.regency" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kecamatan</label>
                            <input type="text" name="district" x-model="selectedBeneficiary.district" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Desa/Kelurahan</label>
                            <input type="text" name="village" x-model="selectedBeneficiary.village" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Alamat Lengkap</label>
                        <textarea name="address" x-model="selectedBeneficiary.address" rows="2" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all"></textarea>
                    </div>

                    <div class="mt-10 border-t border-slate-100 pt-6">
                        <h4 class="text-[11px] font-bold text-indigo-600 uppercase tracking-widest mb-4">Ubah Titik Lokasi GPS (Klik Peta)</h4>
                        <div id="map-edit" style="height: 300px; width: 100%; border-radius: 0.75rem; border: 4px solid white; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);"></div>
                        
                        <div class="grid grid-cols-2 gap-6 mt-4">
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Latitude</label>
                                <input type="text" name="latitude_gps" x-model="selectedBeneficiary.latitude_gps" id="e_lat" readonly class="w-full mt-2 px-4 py-2.5 bg-slate-100 border-none rounded-lg text-sm text-slate-500 focus:outline-none cursor-not-allowed" placeholder="Otomatis dari peta">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Longitude</label>
                                <input type="text" name="longitude_gps" x-model="selectedBeneficiary.longitude_gps" id="e_lng" readonly class="w-full mt-2 px-4 py-2.5 bg-slate-100 border-none rounded-lg text-sm text-slate-500 focus:outline-none cursor-not-allowed" placeholder="Otomatis dari peta">
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100">

                {{-- SECTION 4: PORSI --}}
                <div class="pt-2">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Data Porsi (Jumlah)</h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Porsi Kecil (L)</label>
                            <input type="number" name="small_portion_male" x-model="selectedBeneficiary.small_portion_male" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Porsi Kecil (P)</label>
                            <input type="number" name="small_portion_female" x-model="selectedBeneficiary.small_portion_female" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Porsi Besar (L)</label>
                            <input type="number" name="large_portion_male" x-model="selectedBeneficiary.large_portion_male" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Porsi Besar (P)</label>
                            <input type="number" name="large_portion_female" x-model="selectedBeneficiary.large_portion_female" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Guru</label>
                            <input type="number" name="teacher_portion" x-model="selectedBeneficiary.teacher_portion" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Staff</label>
                            <input type="number" name="staff_portion" x-model="selectedBeneficiary.staff_portion" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kader</label>
                            <input type="number" name="cadre_portion" x-model="selectedBeneficiary.cadre_portion" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-4">
                <button type="button" @click="showEditModal = false" class="flex-1 py-4 text-[11px] font-bold uppercase text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition-all">Batal</button>
                <button type="submit" class="flex-1 py-4 text-[11px] font-bold uppercase text-white bg-slate-800 rounded-xl shadow-lg hover:bg-slate-900 transition-all active:scale-95">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    let mapEdit = null;
    let markerEdit = null;

    function initEditMap(data) {
        if (!mapEdit) {
            mapEdit = L.map('map-edit').setView([-8.1127, 115.0911], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(mapEdit);

            mapEdit.on('click', function(e) {
                const { lat, lng } = e.latlng;
                document.getElementById('e_lat').value = lat.toFixed(8);
                document.getElementById('e_lng').value = lng.toFixed(8);

                // Update Alpine data manually since inputs are readonly and might not trigger x-model on programmatic change in some setups
                // but usually Alpine handles it if we use dispatch or if it's already bound.
                // To be safe, we can trigger an input event
                document.getElementById('e_lat').dispatchEvent(new Event('input'));
                document.getElementById('e_lng').dispatchEvent(new Event('input'));

                if (markerEdit) {
                    markerEdit.setLatLng(e.latlng);
                } else {
                    markerEdit = L.marker(e.latlng).addTo(mapEdit);
                }
            });
        }

        const lat = parseFloat(data.latitude_gps) || -8.1127;
        const lng = parseFloat(data.longitude_gps) || 115.0911;

        mapEdit.setView([lat, lng], 15);
        if (markerEdit) {
            markerEdit.setLatLng([lat, lng]);
        } else {
            markerEdit = L.marker([lat, lng]).addTo(mapEdit);
        }

        setTimeout(() => mapEdit.invalidateSize(), 150);
    }
</script>
