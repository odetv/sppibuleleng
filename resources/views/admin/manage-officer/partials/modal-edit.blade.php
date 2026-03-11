<div x-show="showEditModal" class="fixed inset-0 z-99 flex items-center justify-center p-4 text-left" x-cloak>
    <style>
        /* Style input saat error */
        .input-error-edit {
            border: 1px solid #ef4444 !important;
            box-shadow: 0 0 0 2px #fef2f2 !important;
            background-color: #fff1f2 !important;
        }

        .text-error-edit {
            color: #ef4444;
            font-size: 10px;
            font-weight: 700;
            font-style: italic;
            margin-top: 4px;
            display: block;
        }
    </style>

    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showEditModal = false"></div>

    <div x-show="showEditModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="relative bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-4xl overflow-hidden transform transition-all font-sans text-sm"
        @open-edit-modal.window="
            selectedOfficer = $event.detail;
            showEditModal = true;
        ">

        <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center text-slate-800">
            <div class="flex items-center gap-3">
                <span class="p-1.5 bg-amber-50 text-amber-600 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </span>
                <h3 class="text-[14px] font-bold uppercase tracking-widest">Edit Data Petugas</h3>
            </div>
            <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 text-2xl cursor-pointer">&times;</button>
        </div>

        <form x-bind:action="`/admin/manage-officer/${selectedOfficer.id_sppg_officer}/update`" 
            method="POST" 
            id="editOfficerForm"
            @submit.prevent="window.submitEditOfficer($el)">
            @csrf
            @method('PATCH')

            <div class="p-8 max-h-[75vh] overflow-y-auto space-y-8 custom-scrollbar">
                
                {{-- SECTION: IDENTITAS DASAR --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <input type="hidden" name="id_person" id="e_id_person" x-model="selectedOfficer.id_person">

                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Lengkap (Sesuai KTP) <span class="text-rose-500">*</span></label>
                        <input type="text" name="name_person" id="e_name_person" x-model="selectedOfficer.person.name" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500" placeholder="Masukkan Nama Lengkap">
                    </div>

                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">No. Kartu Keluarga (KK) <span class="text-rose-500">*</span></label>
                        <input type="text" name="no_kk" id="e_no_kk" x-model="selectedOfficer.person.no_kk" maxlength="16" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500" placeholder="Masukkan Nomor KK">
                    </div>

                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">No. BPJS Kesehatan</label>
                        <input type="text" name="no_bpjs_kes" id="e_no_bpjs_kes" x-model="selectedOfficer.person.no_bpjs_kes" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500" placeholder="Masukkan No. BPJS Kesehatan">
                    </div>

                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">No. BPJS Ketenagakerjaan</label>
                        <input type="text" name="no_bpjs_tk" id="e_no_bpjs_tk" x-model="selectedOfficer.person.no_bpjs_tk" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500" placeholder="Masukkan No. BPJS Ketenagakerjaan">
                    </div>
                    
                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">NIK (16 Digit) <span class="text-rose-500">*</span></label>
                        <input type="text" name="nik_person" id="e_nik_person" x-model="selectedOfficer.person.nik" maxlength="16" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500" placeholder="Masukkan NIK">
                    </div>


                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <select name="gender_person" id="e_gender_person" x-model="selectedOfficer.person.gender" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nomor Telepon <span class="text-rose-500">*</span></label>
                        <input type="text" name="phone_person" id="e_phone_person" x-model="selectedOfficer.person.user.phone" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500" placeholder="Cth: 08xxxxxxxxxx">
                    </div>

                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tempat Lahir <span class="text-rose-500">*</span></label>
                        <input type="text" name="place_birthday_person" id="e_place_birthday_person" x-model="selectedOfficer.person.place_birthday" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500" placeholder="Cth: Singaraja">
                    </div>

                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tanggal Lahir <span class="text-rose-500">*</span></label>
                        <input type="date" name="date_birthday_person" id="e_date_birthday_person" x-model="selectedOfficer.person.date_birthday" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Agama <span class="text-rose-500">*</span></label>
                        <select name="religion" id="e_religion" x-model="selectedOfficer.person.religion" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                            <option value="" disabled>Pilih Agama</option>
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Budha">Budha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Status Pernikahan <span class="text-rose-500">*</span></label>
                        <select name="marital_status" id="e_marital_status" x-model="selectedOfficer.person.marital_status" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                            <option value="" disabled>Pilih Status</option>
                            <option value="Belum Kawin">Belum Kawin</option>
                            <option value="Kawin">Kawin</option>
                            <option value="Janda">Janda</option>
                            <option value="Duda">Duda</option>
                        </select>
                    </div>
                </div>

                {{-- SECTION: ALAMAT KTP --}}
                <div class="pt-8 border-t border-gray-100">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-amber-600 mb-6">Alamat KTP</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Provinsi <span class="text-rose-500">*</span></label>
                            <select name="province_ktp" id="e_province_ktp" x-bind:data-selected="selectedOfficer.person.province_ktp" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                                <option value="" disabled selected>Pilih Provinsi</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kabupaten <span class="text-rose-500">*</span></label>
                            <select name="regency_ktp" id="e_regency_ktp" x-bind:data-selected="selectedOfficer.person.regency_ktp" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500" disabled>
                                <option value="" disabled selected>Pilih Kabupaten/Kota</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kecamatan <span class="text-rose-500">*</span></label>
                            <select name="district_ktp" id="e_district_ktp" x-bind:data-selected="selectedOfficer.person.district_ktp" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500" disabled>
                                <option value="" disabled selected>Pilih Kecamatan</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Desa/Kelurahan <span class="text-rose-500">*</span></label>
                            <select name="village_ktp" id="e_village_ktp" x-bind:data-selected="selectedOfficer.person.village_ktp" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500" disabled>
                                <option value="" disabled selected>Pilih Desa/Kelurahan</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Alamat Jalan <span class="text-rose-500">*</span></label>
                            <textarea name="address_ktp" id="e_address_ktp" x-model="selectedOfficer.person.address_ktp" rows="2" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500" placeholder="Masukkan alamat lengkap sesuai KTP"></textarea>
                        </div>
                    </div>
                </div>

                {{-- SECTION: ALAMAT DOMISILI --}}
                <div class="pt-8 border-t border-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-amber-600">Alamat Domisili</h3>
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" id="sync_address_edit" class="w-4 h-4 text-amber-600 border-gray-300 rounded focus:ring-amber-500">
                            <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest group-hover:text-amber-600 transition-colors">Gunakan Alamat KTP</span>
                        </label>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Provinsi <span class="text-rose-500">*</span></label>
                            <select name="province_domicile" id="e_province_domicile" x-bind:data-selected="selectedOfficer.person.province_domicile" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                                <option value="" disabled selected>Pilih Provinsi</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kabupaten <span class="text-rose-500">*</span></label>
                            <select name="regency_domicile" id="e_regency_domicile" x-bind:data-selected="selectedOfficer.person.regency_domicile" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500" disabled>
                                <option value="" disabled selected>Pilih Kabupaten/Kota</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kecamatan <span class="text-rose-500">*</span></label>
                            <select name="district_domicile" id="e_district_domicile" x-bind:data-selected="selectedOfficer.person.district_domicile" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500" disabled>
                                <option value="" disabled selected>Pilih Kecamatan</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Desa/Kelurahan <span class="text-rose-500">*</span></label>
                            <select name="village_domicile" id="e_village_domicile" x-bind:data-selected="selectedOfficer.person.village_domicile" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500" disabled>
                                <option value="" disabled selected>Pilih Desa/Kelurahan</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Alamat Jalan <span class="text-rose-500">*</span></label>
                            <textarea name="address_domicile" id="e_address_domicile" x-model="selectedOfficer.person.address_domicile" rows="2" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500" placeholder="Masukkan alamat lengkap domisili saat ini"></textarea>
                        </div>
                    </div>
                </div>

                {{-- SECTION: PENUGASAN UTAMA --}}
                <div class="pt-8 border-t border-gray-100">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-amber-600 mb-6">Unit Penugasan Petugas</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Jabatan <span class="text-rose-500">*</span></label>
                        <select name="id_ref_position" id="e_id_ref_position" x-model="selectedOfficer.id_ref_position" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                            <option value="" disabled>Pilih Jabatan</option>
                            @foreach($positions as $pos)
                                <option value="{{ $pos->id_ref_position }}" data-slug="{{ $pos->slug_position }}">{{ $pos->name_position }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="e_wa_wrapper" class="hidden">
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Penugasan SK (Work Assignment) <span class="text-rose-500">*</span></label>
                        <select name="id_work_assignment" id="e_id_work_assignment" x-model="selectedOfficer.person.id_work_assignment" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                            <option value="none" selected>Pilih Penugasan...</option>
                            @foreach($workAssignments as $wa)
                            <option value="{{ $wa->id_work_assignment }}" 
                                data-pos="{{ $wa->decree?->type_sk ?? '' }}"
                                data-unit="{{ $wa->id_sppg_unit }}">
                                {{ $wa->sppgUnit?->name ?? 'Unit Tidak Ditemukan' }} - {{ $wa->decree?->no_sk ?? 'SK Tidak Ditemukan' }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="e_unit_wrapper">
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Unit SPPG <span class="text-rose-500">*</span></label>
                        <select name="id_sppg_unit" id="e_id_sppg_unit" x-model="selectedOfficer.id_sppg_unit" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            <option value="none" disabled selected>Pilih Jabatan Terlebih Dahulu...</option>
                            @foreach($sppgUnits as $unit)
                                <option value="{{ $unit->id_sppg_unit }}"
                                    data-leader="{{ $unit->leader_id }}"
                                    data-nutritionist="{{ $unit->nutritionist_id }}"
                                    data-accountant="{{ $unit->accountant_id }}">
                                    {{ $unit->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                </div>

                {{-- SECTION: STATUS & HONORARIUM --}}
                <div class="pt-8 border-t border-gray-100">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-amber-600 mb-6">Status & Honorarium</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2 block">Status Keaktifan Petugas <span class="text-rose-500">*</span></label>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 cursor-pointer p-4 bg-gray-50 rounded-xl border border-transparent hover:border-amber-300 transition-all flex-1">
                                    <input type="radio" name="is_active" value="1" x-model="selectedOfficer.is_active" class="w-5 h-5 text-amber-600 focus:ring-amber-500 border-slate-300">
                                    <span class="text-sm font-bold text-slate-700">AKTIF</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer p-4 bg-gray-50 rounded-xl border border-transparent hover:border-rose-300 transition-all flex-1">
                                    <input type="radio" name="is_active" value="0" x-model="selectedOfficer.is_active" class="w-5 h-5 text-rose-600 focus:ring-rose-500 border-slate-300">
                                    <span class="text-sm font-bold text-slate-700">NON-AKTIF</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Honor per Hari (Rp)</label>
                            <div class="relative mt-2">
                                <input type="number" name="daily_honor" id="e_daily_honor" x-model="selectedOfficer.daily_honor" class="w-full px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500" min="0" x-on:blur="if(selectedOfficer.daily_honor === '' || selectedOfficer.daily_honor < 0 || selectedOfficer.daily_honor === null) selectedOfficer.daily_honor = 0;">
                            </div>
                        </div>

                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Hak Akses Sistem <span class="text-rose-500">*</span></label>
                            <select name="id_ref_role" id="e_id_ref_role" x-model="selectedOfficer.person.user.id_ref_role" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                                <option value="" disabled>Pilih Hak Akses</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id_ref_role }}">{{ $role->name_role }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-4">
                <button type="button" @click="showEditModal = false" class="flex-1 py-4 text-[11px] font-bold uppercase text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition-all">Batal</button>
                <button type="submit" id="btnUpdateOfficer" class="flex-1 py-4 text-[11px] font-bold uppercase text-white bg-amber-600 rounded-xl shadow-lg hover:bg-amber-700 transition-all active:scale-95">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function clearEditOfficerErrors() {
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.error-warning').forEach(el => {
            el.classList.add('hidden');
            el.innerText = '';
        });
    }

    function showEditOfficerFieldError(id, msg) {
        const el = document.getElementById(id);
        if (!el) return;

        el.classList.add('is-invalid');

        let errorEl = document.getElementById('error-edit-' + id);
        if (!errorEl) {
            errorEl = document.createElement('span');
            errorEl.id = 'error-edit-' + id;
            errorEl.className = 'error-warning';
            el.parentNode.appendChild(errorEl);
        }
        errorEl.innerText = '* ' + msg;
        errorEl.classList.remove('hidden');
    }

    window.submitEditOfficer = async function(formElement) {
        clearEditOfficerErrors();
        const btnUpdate = document.getElementById('btnUpdateOfficer');
        
        if (btnUpdate) {
            btnUpdate.disabled = true;
            btnUpdate.innerHTML = "Memproses...";
        }

        // Enable all disabled inputs temporarily to include them in FormData
        const disabledElements = formElement.querySelectorAll(':disabled');
        disabledElements.forEach(el => el.disabled = false);

        try {
            const formData = new FormData(formElement);
            
            // Re-disable what was previously disabled
            disabledElements.forEach(el => el.disabled = true);
            
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
                if (btnUpdate) {
                    btnUpdate.disabled = false;
                    btnUpdate.innerHTML = "Simpan Perubahan";
                }

                if (result.errors) {
                    Object.keys(result.errors).forEach(key => {
                        showEditOfficerFieldError('e_' + key, result.errors[key][0]);
                    });
                }
            }
        } catch (err) {
            if (btnUpdate) {
                btnUpdate.disabled = false;
                btnUpdate.innerHTML = "Simpan Perubahan";
            }
            console.error('Update Error:', err);
        }
    };

    // --- LOGIKA WILAYAH EDIT ---
    const apiWilayahEdit = "/api-wilayah";

    async function populateSelectEdit(selectId, urlPath, placeholder, targetValue = null) {
        const select = document.getElementById(selectId);
        if (!select) return null;

        const savedValue = select.getAttribute('data-selected');
        let valueToMatch = (targetValue || savedValue || "").toString().toUpperCase().trim();

        select.innerHTML = `<option value="">Mohon tunggu...</option>`;
        select.disabled = true;

        try {
            const resp = await fetch(`${apiWilayahEdit}/${urlPath}`);
            const result = await resp.json();
            const data = result.data;

            let options = `<option value="" disabled selected>${placeholder}</option>`;
            data.forEach(item => {
                let cleanName = item.name.replace(/^(KABUPATEN|KOTA)\s+/i, "");
                options += `<option value="${cleanName}" data-code="${item.code}">${cleanName}</option>`;
            });
            select.innerHTML = options;
            select.disabled = false;

            if (valueToMatch) {
                for (let i = 0; i < select.options.length; i++) {
                    if (select.options[i].value.toUpperCase() === valueToMatch) {
                        select.selectedIndex = i;
                        return select.options[i].getAttribute('data-code');
                    }
                }
            }
        } catch (e) {
            console.error("Error loading " + selectId, e);
            select.innerHTML = `<option value="">Gagal memuat</option>`;
        }
        return null;
    }

    async function runChainEdit(prefix) {
        const addrMap = prefix === 'ktp' 
            ? { prov: 'province_ktp', reg: 'regency_ktp', dist: 'district_ktp', vill: 'village_ktp' }
            : { prov: 'province_domicile', reg: 'regency_domicile', dist: 'district_domicile', vill: 'village_domicile' };

        const provCode = await populateSelectEdit(`e_${addrMap.prov}`, 'provinces.json', "Pilih Provinsi");
        if (provCode) {
            const regCode = await populateSelectEdit(`e_${addrMap.reg}`, `regencies/${provCode}.json`, "Pilih Kabupaten/Kota");
            if (regCode) {
                const distCode = await populateSelectEdit(`e_${addrMap.dist}`, `districts/${regCode}.json`, "Pilih Kecamatan");
                if (distCode) {
                    await populateSelectEdit(`e_${addrMap.vill}`, `villages/${distCode}.json`, "Pilih Desa/Kelurahan");
                }
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const addrFields = [
            { key: 'ktp', province: 'province_ktp', regency: 'regency_ktp', district: 'district_ktp', village: 'village_ktp' },
            { key: 'dom', province: 'province_domicile', regency: 'regency_domicile', district: 'district_domicile', village: 'village_domicile' }
        ];

        addrFields.forEach(p => {
            const provEl = document.getElementById(`e_${p.province}`);
            const regEl = document.getElementById(`e_${p.regency}`);
            const distEl = document.getElementById(`e_${p.district}`);

            if (provEl) {
                provEl.addEventListener('change', async function() {
                    const code = this.options[this.selectedIndex].getAttribute('data-code');
                    await populateSelectEdit(`e_${p.regency}`, `regencies/${code}.json`, "Pilih Kabupaten/Kota");
                    if (document.getElementById('sync_address_edit') && document.getElementById('sync_address_edit').checked) performFullSyncEdit();
                });
            }
            if (regEl) {
                regEl.addEventListener('change', async function() {
                    const code = this.options[this.selectedIndex].getAttribute('data-code');
                    await populateSelectEdit(`e_${p.district}`, `districts/${code}.json`, "Pilih Kecamatan");
                    if (document.getElementById('sync_address_edit') && document.getElementById('sync_address_edit').checked) performFullSyncEdit();
                });
            }
            if (distEl) {
                distEl.addEventListener('change', async function() {
                    const code = this.options[this.selectedIndex].getAttribute('data-code');
                    await populateSelectEdit(`e_${p.village}`, `villages/${code}.json`, "Pilih Desa/Kelurahan");
                    if (document.getElementById('sync_address_edit') && document.getElementById('sync_address_edit').checked) performFullSyncEdit();
                });
            }
        });

        const syncEdit = document.getElementById('sync_address_edit');
        if (syncEdit) {
            syncEdit.addEventListener('change', function() {
                if (this.checked) performFullSyncEdit();
                else unlockDomFieldsEdit();
            });
        }
    });

    function performFullSyncEdit() {
        const fields = [
            { ktp: 'province_ktp', dom: 'province_domicile' },
            { ktp: 'regency_ktp', dom: 'regency_domicile' },
            { ktp: 'district_ktp', dom: 'district_domicile' },
            { ktp: 'village_ktp', dom: 'village_domicile' }
        ];
        fields.forEach(f => {
            const ktpEl = document.getElementById(`e_${f.ktp}`);
            const domEl = document.getElementById(`e_${f.dom}`);
            if (ktpEl && domEl) {
                domEl.innerHTML = ktpEl.innerHTML;
                domEl.value = ktpEl.value;
                domEl.disabled = true;
                domEl.classList.add('bg-gray-200', 'cursor-not-allowed');
            }
        });
        const ktpAddr = document.getElementById('e_address_ktp');
        const domAddr = document.getElementById('e_address_domicile');
        if (ktpAddr && domAddr) {
            domAddr.value = ktpAddr.value;
            domAddr.readOnly = true;
            domAddr.classList.add('bg-gray-200', 'cursor-not-allowed');
        }
    }

    function unlockDomFieldsEdit() {
        const fields = ['province_domicile', 'regency_domicile', 'district_domicile', 'village_domicile'];
        fields.forEach(f => {
            const domEl = document.getElementById(`e_${f}`);
            if (domEl) {
                domEl.disabled = false;
                domEl.classList.remove('bg-gray-200', 'cursor-not-allowed');
            }
        });
        const domAddr = document.getElementById('e_address_domicile');
        if (domAddr) {
            domAddr.readOnly = false;
            domAddr.classList.remove('bg-gray-200', 'cursor-not-allowed');
        }
    }

    // --- LOGIKA PENUGASAN (Pilih Unit vs Pilih SK) ---
    function updateAssignmentUIEdit(isChanging = false) {
        const ePosSelect = document.getElementById('e_id_ref_position');
        const eUnitSelect = document.getElementById('e_id_sppg_unit');
        const eWaSelect = document.getElementById('e_id_work_assignment');
        const eUnitWrapper = document.getElementById('e_unit_wrapper');
        const eWaWrapper = document.getElementById('e_wa_wrapper');
        const ePersonIdInput = document.getElementById('e_id_person');

        if (!ePosSelect || !eUnitSelect || !eWaSelect || !ePersonIdInput) return;

        // Jika dipicu oleh perubahan jabatan (isChanging=true), reset pilihan unit/SK
        if (isChanging) {
            eUnitSelect.value = 'none';
            eWaSelect.value = 'none';
            // Paksa event change agar x-model di Alpine ikut ter-update
            eUnitSelect.dispatchEvent(new Event('change'));
            eWaSelect.dispatchEvent(new Event('change'));
        }

        const selectedOpt = ePosSelect.options[ePosSelect.selectedIndex];
        if (!selectedOpt || selectedOpt.value === "") {
            eUnitSelect.disabled = true;
            eWaSelect.disabled = true;
            return;
        }

        const slug = selectedOpt.getAttribute('data-slug');
        const posId = ePosSelect.value;
        const currentPersonId = ePersonIdInput.value;
        const corePositionSlugs = ['kasppg', 'ag', 'ak', 'korwil', 'sppi', 'korcam', 'kasppg-pengganti'];
        const isCore = corePositionSlugs.includes(slug);

        if (isCore) {
            // Tampilkan SK (Work Assignment)
            eWaWrapper.classList.remove('hidden');
            eUnitWrapper.classList.add('hidden');
            eWaSelect.disabled = false;
            eUnitSelect.disabled = true;
            
            // Filter opsi SK berdasarkan jabatan & cek keterisian unit
            Array.from(eWaSelect.options).forEach(opt => {
                if (opt.value === 'none') {
                    opt.hidden = false;
                } else {
                    const waPosId = opt.getAttribute('data-pos');
                    opt.hidden = (waPosId !== posId);

                    if (!opt.hidden) {
                        const unitId = opt.getAttribute('data-unit');
                        const mapping = { 'kasppg': 'kasppg', 'ag': 'ag', 'ak': 'ak' };
                        const col = mapping[slug];
                        
                        // Bersihkan state sebelumnya
                        opt.disabled = false;
                        opt.classList.remove('text-gray-300', 'bg-gray-50', 'italic');
                        opt.text = opt.text.replace(' (Sudah Terisi)', '');

                        // Cek jika sudah terisi (kecuali jika orang itu sendiri)
                        if (col && occupiedPositions[unitId] && occupiedPositions[unitId][col]) {
                            const occupantId = occupiedPositions[unitId][col];
                            if (occupantId && occupantId != currentPersonId) {
                                opt.disabled = true;
                                opt.classList.add('text-gray-300', 'bg-gray-50', 'italic');
                                opt.text += ' (Sudah Terisi)';
                            }
                        }
                    }
                }
            });
        } else {
            // Tampilkan Unit SPPG Langsung
            eUnitWrapper.classList.remove('hidden');
            eWaWrapper.classList.add('hidden');
            eUnitSelect.disabled = false;
            eWaSelect.disabled = true;

            eUnitSelect.options[0].text = "Pilih Unit SPPG";

            // Filter unit (cek ketersediaan slot)
            Array.from(eUnitSelect.options).forEach(opt => {
                if (!opt.value || opt.value === 'none') return;
                
                let isFull = false;
                let occupantId = null;
                if (slug === 'kasppg') occupantId = opt.getAttribute('data-leader');
                if (slug === 'ag') occupantId = opt.getAttribute('data-nutritionist');
                if (slug === 'ak') occupantId = opt.getAttribute('data-accountant');
                
                if (occupantId && occupantId != currentPersonId) {
                    isFull = true;
                }
                
                if (isFull) {
                    opt.disabled = true;
                    opt.classList.add('text-gray-300', 'bg-gray-50', 'italic');
                    opt.text = opt.text.replace(' (Sudah Terisi)', '') + ' (Sudah Terisi)';
                } else {
                    opt.disabled = false;
                    opt.classList.remove('text-gray-300', 'bg-gray-50', 'italic');
                    opt.text = opt.text.replace(' (Sudah Terisi)', '');
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const ePosSelect = document.getElementById('e_id_ref_position');
        if (ePosSelect) {
            ePosSelect.addEventListener('change', function() {
                updateAssignmentUIEdit(true);
            });
        }

        // Add listeners for SK/Unit change for immediate feedback
        ['e_id_work_assignment', 'e_id_sppg_unit'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('change', () => updateAssignmentUIEdit(false));
        });
    });

    window.addEventListener('init-edit-officer-location', function() {
        // Jangan dijalankan langsung, beri waktu Alpine sinkronasi data ke DOM attributes
        setTimeout(async () => {
            updateAssignmentUIEdit(false);
            await runChainEdit('ktp');
            await runChainEdit('dom');
        }, 500); // 500ms lebih aman untuk async chain
    });
</script>
