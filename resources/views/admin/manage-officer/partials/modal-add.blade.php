<div x-show="showCreateModal" class="fixed inset-0 z-99 flex items-center justify-center p-4 text-left" x-cloak>
    <style>
        /* Style input saat error */
        .input-error {
            border: 1px solid #ef4444 !important;
            box-shadow: 0 0 0 2px #fef2f2 !important;
            background-color: #fff1f2 !important;
        }

        .text-error-custom {
            color: #ef4444;
            font-size: 10px;
            font-weight: 700;
            font-style: italic;
            margin-top: 4px;
            display: block;
        }
    </style>

    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showCreateModal = false"></div>

    <div x-show="showCreateModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="relative bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-4xl overflow-hidden transform transition-all font-sans text-sm">

        <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center text-slate-800">
            <div class="flex items-center gap-3">
                <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </span>
                <h3 class="text-[14px] font-bold uppercase tracking-widest">Tambah Petugas Baru</h3>
            </div>
            <button type="button" @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600 text-2xl cursor-pointer">&times;</button>
        </div>

        <form action="{{ route('admin.manage-officer.store') }}"
            method="POST"
            id="createOfficerForm"
            @submit.prevent="window.submitCreateOfficer($el)">
            @csrf

            <div class="p-8 max-h-[75vh] overflow-y-auto space-y-8 custom-scrollbar">

                {{-- SECTION: IDENTITAS DASAR --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Lengkap (Sesuai KTP) <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" id="f_name" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Masukkan Nama Lengkap">
                    </div>

                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">No. Kartu Keluarga (KK) <span class="text-rose-500">*</span></label>
                        <input type="text" name="no_kk" id="f_no_kk" maxlength="16" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Masukkan Nomor KK">
                    </div>

                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">No. BPJS Kesehatan</label>
                        <input type="text" name="no_bpjs_kes" id="f_no_bpjs_kes" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Masukkan No. BPJS Kesehatan">
                    </div>

                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">No. BPJS Ketenagakerjaan</label>
                        <input type="text" name="no_bpjs_tk" id="f_no_bpjs_tk" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Masukkan No. BPJS Ketenagakerjaan">
                    </div>

                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nomor Induk Kependudukan (NIK) <span class="text-rose-500">*</span></label>
                        <input type="text" name="nik" id="f_nik" maxlength="16" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Masukkan NIK">
                    </div>


                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <select name="gender" id="f_gender" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            <option value="" disabled selected>Pilih Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tempat Lahir <span class="text-rose-500">*</span></label>
                        <input type="text" name="place_birthday" id="f_place_birthday" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Sesuai KTP">
                    </div>

                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tanggal Lahir <span class="text-rose-500">*</span></label>
                        <input type="date" name="date_birthday" id="f_date_birthday" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Agama <span class="text-rose-500">*</span></label>
                        <select name="religion" id="f_religion" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            <option value="" disabled selected>Pilih Agama</option>
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
                        <select name="marital_status" id="f_marital_status" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            <option value="" disabled selected>Pilih Status</option>
                            <option value="Belum Kawin">Belum Kawin</option>
                            <option value="Kawin">Kawin</option>
                            <option value="Janda">Janda</option>
                            <option value="Duda">Duda</option>
                        </select>
                    </div>
                </div>

                {{-- SECTION: ALAMAT KTP --}}
                <div class="pt-8 border-t border-gray-100">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Alamat KTP</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Provinsi <span class="text-rose-500">*</span></label>
                            <select name="province_ktp" id="f_province_ktp" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="" disabled selected>Pilih Provinsi</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kabupaten <span class="text-rose-500">*</span></label>
                            <select name="regency_ktp" id="f_regency_ktp" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" disabled>
                                <option value="" disabled selected>Pilih Kabupaten/Kota</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kecamatan <span class="text-rose-500">*</span></label>
                            <select name="district_ktp" id="f_district_ktp" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" disabled>
                                <option value="" disabled selected>Pilih Kecamatan</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Desa/Kelurahan <span class="text-rose-500">*</span></label>
                            <select name="village_ktp" id="f_village_ktp" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" disabled>
                                <option value="" disabled selected>Pilih Desa/Kelurahan</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Alamat Jalan <span class="text-rose-500">*</span></label>
                            <textarea name="address_ktp" id="f_address_ktp" rows="2" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Masukkan alamat lengkap sesuai KTP"></textarea>
                        </div>
                    </div>
                </div>

                {{-- SECTION: ALAMAT DOMISILI --}}
                <div class="pt-8 border-t border-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600">Alamat Domisili</h3>
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" id="sync_address" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest group-hover:text-indigo-600 transition-colors">Gunakan Alamat KTP</span>
                        </label>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Provinsi <span class="text-rose-500">*</span></label>
                            <select name="province_domicile" id="f_province_domicile" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="" disabled selected>Pilih Provinsi</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kabupaten <span class="text-rose-500">*</span></label>
                            <select name="regency_domicile" id="f_regency_domicile" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" disabled>
                                <option value="" disabled selected>Pilih Kabupaten/Kota</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kecamatan <span class="text-rose-500">*</span></label>
                            <select name="district_domicile" id="f_district_domicile" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" disabled>
                                <option value="" disabled selected>Pilih Kecamatan</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Desa/Kelurahan <span class="text-rose-500">*</span></label>
                            <select name="village_domicile" id="f_village_domicile" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" disabled>
                                <option value="" disabled selected>Pilih Desa/Kelurahan</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Alamat Jalan <span class="text-rose-500">*</span></label>
                            <textarea name="address_domicile" id="f_address_domicile" rows="2" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Masukkan alamat lengkap domisili saat ini"></textarea>
                        </div>
                    </div>
                </div>

                {{-- SECTION: PENUGASAN & KONTAK --}}
                <div class="pt-8 border-t border-gray-100">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Informasi Penugasan & Kontak</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Jabatan <span class="text-rose-500">*</span></label>
                            <select name="id_ref_position" id="f_id_ref_position" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="" disabled selected>Pilih Jabatan</option>
                                @foreach($positions as $pos)
                                <option value="{{ $pos->id_ref_position }}" data-slug="{{ $pos->slug_position }}">{{ $pos->name_position }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="f_wa_wrapper" class="hidden">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Penugasan SK (Work Assignment) <span class="text-rose-500">*</span></label>
                            <select name="id_work_assignment" id="f_id_work_assignment" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
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

                        <div id="f_unit_wrapper">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Unit SPPG <span class="text-rose-500">*</span></label>
                            <select name="id_sppg_unit" id="f_id_sppg_unit" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                <option value="none" disabled selected>Pilih Jabatan Terlebih Dahulu...</option>
                                @foreach($sppgUnits as $unit)
                                <option value="{{ $unit->id_sppg_unit }}"
                                    data-leader="{{ $unit->leader_id }}"
                                    data-nutritionist="{{ $unit->nutritionist_id }}"
                                    data-pharmacist="{{ $unit->pharmacist_id }}">
                                    {{ $unit->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nomor Telepon <span class="text-rose-500">*</span></label>
                            <input type="text" name="phone" id="f_phone" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Cth: 08xxxxxxxxxx">
                        </div>

                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Hak Akses Sistem <span class="text-rose-500">*</span></label>
                            <select name="id_ref_role" id="f_id_ref_role" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="" disabled selected>Pilih Hak Akses</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->id_ref_role }}">{{ $role->name_role }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Honor per Hari (Rp)</label>
                            <input type="number" name="daily_honor" id="f_daily_honor" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="0" value="0" min="0" onblur="if(this.value === '' || this.value < 0) this.value = '0';">
                        </div>

                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-4">
                <button type="button" @click="showCreateModal = false" class="flex-1 py-4 text-[11px] font-bold uppercase text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition-all">Batal</button>
                <button type="submit" id="btnSubmitOfficer" class="flex-1 py-4 text-[11px] font-bold uppercase text-white bg-slate-800 rounded-xl shadow-lg hover:bg-slate-900 transition-all active:scale-95">Simpan Petugas</button>
            </div>
        </form>
    </div>
</div>

<script>
    function clearCreateOfficerErrors() {
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.error-warning').forEach(el => {
            el.classList.add('hidden');
            el.innerText = '';
        });
    }

    function showCreateOfficerFieldError(id, msg) {
        const el = document.getElementById(id);
        if (!el) return;

        el.classList.add('is-invalid');

        let errorEl = document.getElementById('error-' + id);
        if (!errorEl) {
            errorEl = document.createElement('span');
            errorEl.id = 'error-' + id;
            errorEl.className = 'error-warning';
            el.parentNode.appendChild(errorEl);
        }
        errorEl.innerText = '* ' + msg;
        errorEl.classList.remove('hidden');
    }

    window.submitCreateOfficer = async function(formElement) {
        clearCreateOfficerErrors();
        const btnSubmit = document.getElementById('btnSubmitOfficer');

        if (btnSubmit) {
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = "Memproses...";
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
                if (btnSubmit) {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = "Simpan Petugas";
                }

                if (result.errors) {
                    Object.keys(result.errors).forEach(key => {
                        showCreateOfficerFieldError('f_' + key, result.errors[key][0]);
                    });
                }
            }
        } catch (err) {
            if (btnSubmit) {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = "Simpan Petugas";
            }
            console.error('Submit Error:', err);
        }
    };

    // --- LOGIKA WILAYAH ---
    const apiWilayah = "/api-wilayah";

    async function populateSelect(selectId, urlPath, placeholder) {
        const select = document.getElementById(selectId);
        if (!select) return;

        select.innerHTML = `<option value="">Mohon tunggu...</option>`;
        select.disabled = true;

        try {
            const resp = await fetch(`${apiWilayah}/${urlPath}`);
            const result = await resp.json();
            const data = result.data;

            let options = `<option value="" disabled selected>${placeholder}</option>`;
            data.forEach(item => {
                let cleanName = item.name.replace(/^(KABUPATEN|KOTA)\s+/i, "");
                options += `<option value="${cleanName}" data-code="${item.code}">${cleanName}</option>`;
            });
            select.innerHTML = options;
            select.disabled = false;
        } catch (e) {
            console.error("Error loading " + selectId, e);
            select.innerHTML = `<option value="">Gagal memuat</option>`;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const prefixes = [{
                key: 'ktp',
                province: 'province_ktp',
                regency: 'regency_ktp',
                district: 'district_ktp',
                village: 'village_ktp'
            },
            {
                key: 'dom',
                province: 'province_domicile',
                regency: 'regency_domicile',
                district: 'district_domicile',
                village: 'village_domicile'
            }
        ];

        prefixes.forEach(p => {
            const provEl = document.getElementById(`f_${p.province}`);
            const regEl = document.getElementById(`f_${p.regency}`);
            const distEl = document.getElementById(`f_${p.district}`);
            const villEl = document.getElementById(`f_${p.village}`);

            if (provEl) {
                populateSelect(`f_${p.province}`, 'provinces.json', "Pilih Provinsi");
                provEl.addEventListener('change', function() {
                    const code = this.options[this.selectedIndex].getAttribute('data-code');
                    populateSelect(`f_${p.regency}`, `regencies/${code}.json`, "Pilih Kabupaten/Kota");
                    if (regEl) {
                        regEl.innerHTML = '<option value="" disabled selected>Pilih Kabupaten/Kota</option>';
                    }
                    if (distEl) {
                        distEl.innerHTML = '<option value="" disabled selected>Pilih Kecamatan</option>';
                        distEl.disabled = true;
                    }
                    if (villEl) {
                        villEl.innerHTML = '<option value="" disabled selected>Pilih Desa/Kelurahan</option>';
                        villEl.disabled = true;
                    }
                    if (p.key === 'ktp' && document.getElementById('sync_address') && document.getElementById('sync_address').checked) {
                        triggerSync('prov');
                    }
                });
            }

            if (regEl) {
                regEl.addEventListener('change', function() {
                    const code = this.options[this.selectedIndex].getAttribute('data-code');
                    populateSelect(`f_${p.district}`, `districts/${code}.json`, "Pilih Kecamatan");
                    if (villEl) {
                        villEl.innerHTML = '<option value="" disabled selected>Pilih Desa/Kelurahan</option>';
                        villEl.disabled = true;
                    }
                    if (p.key === 'ktp' && document.getElementById('sync_address') && document.getElementById('sync_address').checked) {
                        triggerSync('reg');
                    }
                });
            }

            if (distEl) {
                distEl.addEventListener('change', function() {
                    const code = this.options[this.selectedIndex].getAttribute('data-code');
                    populateSelect(`f_${p.village}`, `villages/${code}.json`, "Pilih Desa/Kelurahan");
                    if (p.key === 'ktp' && document.getElementById('sync_address') && document.getElementById('sync_address').checked) {
                        triggerSync('dist');
                    }
                });
            }

            if (villEl) {
                villEl.addEventListener('change', function() {
                    if (p.key === 'ktp' && document.getElementById('sync_address') && document.getElementById('sync_address').checked) {
                        triggerSync('vill');
                    }
                });
            }
        });

        if (document.getElementById('f_address_ktp')) {
            document.getElementById('f_address_ktp').addEventListener('input', function() {
                if (document.getElementById('sync_address') && document.getElementById('sync_address').checked) {
                    if (document.getElementById('f_address_domicile')) {
                        document.getElementById('f_address_domicile').value = this.value;
                    }
                }
            });
        }

        if (document.getElementById('sync_address')) {
            document.getElementById('sync_address').addEventListener('change', function() {
                if (this.checked) {
                    performFullSync();
                } else {
                    unlockDomFields();
                }
            });
        }
    });

    function triggerSync(level) {
        performFullSync();
    }

    async function performFullSync() {
        const fields = [{
                ktp: 'province_ktp',
                dom: 'province_domicile'
            },
            {
                ktp: 'regency_ktp',
                dom: 'regency_domicile'
            },
            {
                ktp: 'district_ktp',
                dom: 'district_domicile'
            },
            {
                ktp: 'village_ktp',
                dom: 'village_domicile'
            }
        ];
        fields.forEach(f => {
            const ktpEl = document.getElementById(`f_${f.ktp}`);
            const domEl = document.getElementById(`f_${f.dom}`);
            if (ktpEl && domEl) {
                domEl.innerHTML = ktpEl.innerHTML;
                domEl.value = ktpEl.value;
                domEl.disabled = true;
                domEl.classList.add('bg-gray-200', 'cursor-not-allowed');
            }
        });
        const ktpAddr = document.getElementById('f_address_ktp');
        const domAddr = document.getElementById('f_address_domicile');
        if (ktpAddr && domAddr) {
            domAddr.value = ktpAddr.value;
            domAddr.readOnly = true;
            domAddr.classList.add('bg-gray-200', 'cursor-not-allowed');
        }
    }

    function unlockDomFields() {
        const fields = ['province_domicile', 'regency_domicile', 'district_domicile', 'village_domicile'];
        fields.forEach(f => {
            const domEl = document.getElementById(`f_${f}`);
            if (domEl) {
                domEl.disabled = false;
                domEl.classList.remove('bg-gray-200', 'cursor-not-allowed');
            }
        });
        const domAddr = document.getElementById('f_address_domicile');
        if (domAddr) {
            domAddr.readOnly = false;
            domAddr.classList.remove('bg-gray-200', 'cursor-not-allowed');
        }
    }
    // --- LOGIKA PENUGASAN (Pilih Unit vs Pilih SK) ---
    function updateAssignmentUIAdd(isChanging = false) {
        const fPosSelect = document.getElementById('f_id_ref_position');
        const fUnitSelect = document.getElementById('f_id_sppg_unit');
        const fWaSelect = document.getElementById('f_id_work_assignment');
        const fUnitWrapper = document.getElementById('f_unit_wrapper');
        const fWaWrapper = document.getElementById('f_wa_wrapper');

        if (!fPosSelect || !fUnitSelect || !fWaSelect) return;

        const selectedOpt = fPosSelect.options[fPosSelect.selectedIndex];
        if (!selectedOpt || selectedOpt.value === "") {
            fUnitSelect.disabled = true;
            fWaSelect.disabled = true;
            return;
        }

        const slug = selectedOpt.getAttribute('data-slug');
        const posId = fPosSelect.value;
        const corePositionSlugs = ['kasppg', 'ag', 'ak', 'korwil', 'sppi', 'korcam', 'kasppg-pengganti'];
        const isCore = corePositionSlugs.includes(slug);

        if (isCore) {
            // Tampilkan SK (Work Assignment)
            fWaWrapper.classList.remove('hidden');
            fUnitWrapper.classList.add('hidden');
            fWaSelect.disabled = false;
            fUnitSelect.disabled = true;
            fUnitSelect.value = "none";

            if (isChanging) fWaSelect.value = "none";

            // Filter opsi SK berdasarkan jabatan & cek keterisian unit
            Array.from(fWaSelect.options).forEach(opt => {
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

                        // Cek jika sudah terisi (kecuali jika orang itu sendiri, tapi di modal Add pasti orang baru)
                        if (col && occupiedPositions[unitId] && occupiedPositions[unitId][col]) {
                            opt.disabled = true;
                            opt.classList.add('text-gray-300', 'bg-gray-50', 'italic');
                            opt.text += ' (Sudah Terisi)';
                        }
                    }
                }
            });
        } else {
            // Tampilkan Unit SPPG Langsung
            fUnitWrapper.classList.remove('hidden');
            fWaWrapper.classList.add('hidden');
            fUnitSelect.disabled = false;
            fWaSelect.disabled = true;
            fWaSelect.value = "none";

            if (isChanging) fUnitSelect.value = "none";
            fUnitSelect.options[0].text = "Pilih Unit SPPG";

            // Reset unit options
            Array.from(fUnitSelect.options).forEach(opt => {
                if (!opt.value || opt.value === 'none') return;
                opt.disabled = false;
                opt.classList.remove('text-gray-300', 'bg-gray-50', 'italic');
                opt.text = opt.text.replace(' (Sudah Terisi)', '');
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const fPosSelect = document.getElementById('f_id_ref_position');
        if (fPosSelect) {
            fPosSelect.addEventListener('change', function() {
                updateAssignmentUIAdd(true);
            });
        }

        // Add listeners for SK/Unit change for immediate feedback
        ['f_id_work_assignment', 'f_id_sppg_unit'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('change', () => updateAssignmentUIAdd(false));
        });
    });

    const setupMapAdd = (lat, lng) => {
        // Map logic placeholder
    }
</script>