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

        <form x-bind:action="selectedSupplier ? `/admin/manage-supplier/${selectedSupplier.id_supplier}/update` : ''" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="p-8 max-h-[75vh] overflow-y-auto space-y-10 custom-scrollbar">
                
                {{-- SECTION 1: INFORMASI DASAR --}}
                <div class="space-y-8">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Informasi Dasar</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Jenis Supplier <span class="text-rose-500">*</span></label>
                            <select name="type_supplier" required x-model="selectedSupplier.type_supplier" class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                <option value="">Pilih Jenis</option>
                                @foreach($supplierTypes as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Instansi/Toko <span class="text-rose-500">*</span></label>
                            <input type="text" name="name_supplier" required x-model="selectedSupplier.name_supplier" class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Pimpinan <span class="text-rose-500">*</span></label>
                            <input type="text" name="leader_name" required x-model="selectedSupplier.leader_name" class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">No. HP/Telepon <span class="text-rose-500">*</span></label>
                            <input type="text" name="phone" required x-model="selectedSupplier.phone" class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Komoditas Utama <span class="text-rose-500">*</span></label>
                            <textarea name="commodities" required rows="2" x-model="selectedSupplier.commodities" class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all"></textarea>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: LOKASI --}}
                <div class="space-y-8 pt-4 border-t border-slate-50">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Lokasi Supplier</h3>
                    
                    {{-- Hidden inputs for clean names --}}
                    <input type="hidden" name="province" x-ref="edit_prov_name" x-model="selectedSupplier.province">
                    <input type="hidden" name="regency" x-ref="edit_reg_name" x-model="selectedSupplier.regency">
                    <input type="hidden" name="district" x-ref="edit_dist_name" x-model="selectedSupplier.district">
                    <input type="hidden" name="village" x-ref="edit_vill_name" x-model="selectedSupplier.village">

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Provinsi <span class="text-rose-500">*</span></label>
                            <select name="province_code" required 
                                x-model="selectedSupplier.province_code"
                                @change="
                                    $refs.edit_prov_name.value = $event.target.options[$event.target.selectedIndex].text;
                                    selectedSupplier.province = $refs.edit_prov_name.value;
                                    fetchRegencies($event.target.value);
                                    selectedSupplier.regency_code = '';
                                    selectedSupplier.district_code = '';
                                    selectedSupplier.village_code = '';
                                "
                                class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all capitalize">
                                <option value="">Pilih</option>
                                <template x-for="p in provinces" :key="p.code">
                                    <option :value="p.code" x-text="p.name.toLowerCase()" :selected="selectedSupplier.province_code === p.code"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kab/Kota <span class="text-rose-500">*</span></label>
                            <select name="regency_code" required :disabled="!regencies.length"
                                x-model="selectedSupplier.regency_code"
                                @change="
                                    $refs.edit_reg_name.value = $event.target.options[$event.target.selectedIndex].text;
                                    selectedSupplier.regency = $refs.edit_reg_name.value;
                                    fetchDistricts($event.target.value);
                                    selectedSupplier.district_code = '';
                                    selectedSupplier.village_code = '';
                                "
                                class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all disabled:opacity-50 disabled:cursor-not-allowed capitalize">
                                <option value="">Pilih</option>
                                <template x-for="r in regencies" :key="r.code">
                                    <option :value="r.code" x-text="r.name.toLowerCase()" :selected="selectedSupplier.regency_code === r.code"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kecamatan <span class="text-rose-500">*</span></label>
                            <select name="district_code" required :disabled="!districts.length"
                                x-model="selectedSupplier.district_code"
                                @change="
                                    $refs.edit_dist_name.value = $event.target.options[$event.target.selectedIndex].text;
                                    selectedSupplier.district = $refs.edit_dist_name.value;
                                    fetchVillages($event.target.value);
                                    selectedSupplier.village_code = '';
                                "
                                class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all disabled:opacity-50 disabled:cursor-not-allowed capitalize">
                                <option value="">Pilih</option>
                                <template x-for="d in districts" :key="d.code">
                                    <option :value="d.code" x-text="d.name.toLowerCase()" :selected="selectedSupplier.district_code === d.code"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kelurahan <span class="text-rose-500">*</span></label>
                            <select name="village_code" required :disabled="!villages.length"
                                x-model="selectedSupplier.village_code"
                                @change="
                                    $refs.edit_vill_name.value = $event.target.options[$event.target.selectedIndex].text;
                                    selectedSupplier.village = $refs.edit_vill_name.value;
                                "
                                class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all disabled:opacity-50 disabled:cursor-not-allowed capitalize">
                                <option value="">Pilih</option>
                                <template x-for="v in villages" :key="v.code">
                                    <option :value="v.code" x-text="v.name.toLowerCase()" :selected="selectedSupplier.village_code === v.code"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Alamat Detail <span class="text-rose-500">*</span></label>
                            <textarea name="address" required rows="2" x-model="selectedSupplier.address" class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all"></textarea>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kode Pos</label>
                            <input type="text" name="postal_code" x-model="selectedSupplier.postal_code" class="w-full mt-2 text-sm bg-gray-50 border-none rounded-lg py-2.5 px-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
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
                <button type="submit" class="flex-1 py-4 text-[11px] font-bold uppercase text-white bg-slate-800 rounded-xl shadow-lg hover:bg-slate-900 transition-all active:scale-95">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
