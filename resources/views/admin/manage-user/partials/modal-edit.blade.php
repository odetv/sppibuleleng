    {{-- MASTER MODAL EDIT --}}
    <div id="masterModal" class="fixed inset-0 z-[99] hidden flex items-center justify-center p-4 text-left">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeMasterEditModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-6xl overflow-hidden transform transition-all font-sans text-sm">

            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center text-slate-800">
                <div class="flex items-center gap-3">
                    <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </span>
                    <h3 class="text-[14px] font-bold uppercase tracking-widest" id="modal_header_title">Edit Data Profil Pengguna</h3>
                </div>
                <button type="button" onclick="closeMasterEditModal()" class="text-slate-400 hover:text-slate-600 text-2xl cursor-pointer">&times;</button>
            </div>

            <form id="masterForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="method_field"></div>

                <div class="p-8 max-h-[75vh] overflow-y-auto space-y-10">

                    {{-- SECTION 1: FOTO & IDENTITAS UTAMA --}}
                    <div class="flex flex-col lg:flex-row gap-12">
                        <div class="shrink-0 flex flex-col items-center gap-4">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Pas Foto (4x6)</label>
                            <div class="relative group">
                                <div class="h-60 w-40 rounded-2xl overflow-hidden bg-indigo-600 border-4 border-white shadow-lg ring-1 ring-slate-100 bg-gray-50 flex items-center justify-center text-center">
                                    <img id="cropped-preview" class="h-full w-full object-cover cursor-pointer hidden" src="" alt="Preview">
                                    <div id="initial-placeholder" class="text-white text-6xl uppercase"></div>
                                </div>
                                <label for="photo" class="absolute bottom-3 right-3 p-2.5 bg-indigo-400 text-white rounded-xl shadow-xl cursor-pointer hover:bg-indigo-500 transition-all hover:scale-110">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <input type="file" id="photo" name="photo" class="hidden" accept="image/*">
                                </label>
                            </div>
                        </div>

                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            <div class="md:col-span-2">
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Lengkap (Sesuai KTP)</label>
                                <input required type="text" name="name" id="f_name" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Email</label><input required type="email" name="email" id="f_email" class="w-full mt-2 px-4 py-2.5 bg-slate-100 border-none rounded-lg text-sm text-slate-400 cursor-not-allowed" readonly></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Telepon</label><input required type="number" name="phone" id="f_phone" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500"></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">NIK (16 Digit)</label><input required type="number" name="nik" id="f_nik" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm"></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nomor KK (16 Digit)</label><input required type="number" name="no_kk" id="f_no_kk" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm"></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">NIP</label><input required type="number" name="nip" id="f_nip" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500"></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">NPWP</label><input required type="number" name="npwp" id="f_npwp" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500"></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">No. BPJS Kesehatan</label><input type="number" name="no_bpjs_kes" id="f_bpjs_kes" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500"></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">No. BPJS Ketenagakerjaan</label><input type="number" name="no_bpjs_tk" id="f_bpjs_tk" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500"></div>
                        </div>
                    </div>

                    {{-- SECTION: KEDINASAN --}}
                    <div class="pt-10 border-t border-gray-100">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Penugasan & Status Kerja</h3>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                            <div class="col-span-2">
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Unit Penugasan</label>
                                <select name="id_work_assignment" id="f_wa" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm">
                                    <option value="">Belum Penugasan</option>
                                    @foreach($workAssignments as $wa) <option value="{{ $wa->id_work_assignment }}">{{ $wa->sppgUnit?->name ?? 'SPPG Tidak Ditemukan' }} - {{ $wa->decree?->no_sk ?? 'SK Tidak Ditemukan' }}</option> @endforeach
                                </select>
                            </div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Batch</label><select required name="batch" id="f_batch" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm">@foreach(['1', '2', '3', 'Non-SPPI'] as $b) <option value="{{ $b }}">{{ $b }}</option> @endforeach</select></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Hak Akses Sistem</label><select required name="id_ref_role" id="f_role" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm">@foreach($roles as $r)<option value="{{$r->id_ref_role}}">{{$r->name_role}}</option>@endforeach</select></div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Jabatan</label>
                                <select name="id_ref_position" id="f_pos" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                    <option value="">Belum Menjabat</option>
                                    @foreach($positions as $p)
                                    <option value="{{ $p->id_ref_position }}"
                                        {{ (old('id_ref_position', $person->id_ref_position ?? '') == $p->id_ref_position) ? 'selected' : '' }}>
                                        {{ $p->name_position }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION: PERSONAL & PENDIDIKAN --}}
                    <div class="pt-10 border-t border-gray-100">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Detail Personal & Pendidikan</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Pendidikan Terakhir</label><select required name="last_education" id="f_last_edu" class="w-full mt-1 px-3 py-2 bg-gray-50 border-none rounded-lg text-sm">@foreach(['D-III', 'D-IV', 'S-1', 'S-2'] as $edu)<option value="{{ $edu }}">{{ $edu }}</option>@endforeach</select></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Gelar Belakang</label><input required type="text" name="title_education" id="f_title_edu" placeholder="S.Kom." class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm"></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Jurusan / Prodi</label><input required type="text" name="major_education" id="f_major" placeholder="Ilmu Kelautan" class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm"></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Status Kerja</label><select required name="employment_status" id="f_emp" class="w-full mt-1 px-3 py-2 bg-gray-50 border-none rounded-lg text-sm">
                                    <option value="ASN">ASN</option>
                                    <option value="Non-ASN">Non-ASN</option>
                                </select></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tempat Lahir</label><input required type="text" name="place_birthday" id="f_place" class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm"></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tanggal Lahir</label><input required type="date" name="date_birthday" id="f_date" class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm"></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Umur</label><input required type="text" id="f_age" readonly class="w-full mt-1 px-4 py-2 bg-slate-50 border-none rounded-lg text-sm text-slate-400 cursor-not-allowed"></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Agama</label><select required name="religion" id="f_religion" class="w-full mt-1 px-3 py-2 bg-gray-50 border-none rounded-lg text-sm">@foreach(['Islam', 'Kristen', 'Katholik', 'Hindu', 'Buddha', 'Khonghucu'] as $rel)<option value="{{ $rel }}">{{ $rel }}</option>@endforeach</select></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Gender</label><select required name="gender" id="f_gender" class="w-full mt-1 px-3 py-2 bg-gray-50 border-none rounded-lg text-sm">
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Status Pernikahan</label><select required name="marital_status" id="f_marital" class="w-full mt-1 px-3 py-2 bg-gray-50 border-none rounded-lg text-sm">@foreach(['Belum Kawin', 'Kawin', 'Janda', 'Duda'] as $status)<option value="{{ $status }}">{{ $status }}</option>@endforeach</select></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Ukuran Baju</label><select required name="clothing_size" id="f_cloth" class="w-full mt-1 px-3 py-2 bg-gray-50 border-none rounded-lg text-sm">@foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', '4XL', '5XL'] as $size)<option value="{{ $size }}">{{ $size }}</option>@endforeach</select></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Ukuran Sepatu</label><select required name="shoe_size" id="f_shoe" class="w-full mt-1 px-3 py-2 bg-gray-50 border-none rounded-lg text-sm">@for($i=35; $i<=50; $i++)<option value="{{ $i }}">{{ $i }}</option>@endfor</select></div>
                        </div>
                    </div>

                    {{-- SECTION: ALAMAT KTP --}}
                    <div class="pt-10 border-t border-gray-100">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Alamat KTP</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-sm">
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Provinsi</label>
                                <select required name="province_ktp" id="f_ktp_prov" data-selected="{{ $user->province_ktp ?? '' }}" class="w-full px-4 py-2 bg-gray-50 border-none rounded-lg text-sm">
                                    <option value="" disabled selected>Pilih Provinsi</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kabupaten</label>
                                <select required name="regency_ktp" id="f_ktp_reg" data-selected="{{ $user->regency_ktp ?? '' }}" class="w-full px-4 py-2 bg-gray-50 border-none rounded-lg text-sm" disabled>
                                    <option value="" disabled selected>Pilih Kabupaten</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kecamatan</label>
                                <select required name="district_ktp" id="f_ktp_dist" data-selected="{{ $user->district_ktp ?? '' }}" class="w-full px-4 py-2 bg-gray-50 border-none rounded-lg text-sm" disabled>
                                    <option value="" disabled selected>Pilih Kecamatan</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Desa/Kelurahan</label>
                                <select required name="village_ktp" id="f_ktp_vill" data-selected="{{ $user->village_ktp ?? '' }}" class="w-full px-4 py-2 bg-gray-50 border-none rounded-lg text-sm" disabled>
                                    <option value="" disabled selected>Pilih Desa/Kelurahan</option>
                                </select>
                            </div>
                            <div class="md:col-span-4"><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Alamat Jalan/Rumah</label><textarea required name="address_ktp" id="f_ktp_address" rows="2" class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm">{{ $user->address_ktp ?? '' }}</textarea></div>
                        </div>
                    </div>

                    {{-- SECTION: DOMISILI --}}
                    <div class="pt-10 border-t border-gray-100">
                        <div class="flex justify-between items-center mb-6 text-nowrap">
                            <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600">Alamat Domisili & GPS</h3>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" id="sync_address_admin" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest group-hover:text-indigo-600 transition-colors">Gunakan Alamat KTP</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                            <div class="space-y-6">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Provinsi</label>
                                        <select required name="province_domicile" id="f_dom_prov" data-selected="{{ $user->province_domicile ?? '' }}" class="w-full px-4 py-2 bg-gray-50 border-none rounded-lg text-sm">
                                            <option value="" disabled selected>Pilih Provinsi</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kabupaten</label>
                                        <select required name="regency_domicile" id="f_dom_reg" data-selected="{{ $user->regency_domicile ?? '' }}" class="w-full px-4 py-2 bg-gray-50 border-none rounded-lg text-sm" disabled>
                                            <option value="" disabled selected>Pilih Kabupaten</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kecamatan</label>
                                        <select required name="district_domicile" id="f_dom_dist" data-selected="{{ $user->district_domicile ?? '' }}" class="w-full px-4 py-2 bg-gray-50 border-none rounded-lg text-sm" disabled>
                                            <option value="" disabled selected>Pilih Kecamatan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Desa/Kelurahan</label>
                                        <select required name="village_domicile" id="f_dom_vill" data-selected="{{ $user->village_domicile ?? '' }}" class="w-full px-4 py-2 bg-gray-50 border-none rounded-lg text-sm" disabled>
                                            <option value="" disabled selected>Pilih Desa/Kelurahan</option>
                                        </select>
                                    </div>
                                </div>
                                <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Alamat Jalan/Rumah</label><textarea required name="address_domicile" id="f_dom_address" rows="2" class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500"></textarea></div>
                                <div>
                                    <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Koordinat GPS (Klik Pada Peta)</label>
                                    <div class="flex gap-2 mt-2 text-nowrap">
                                        <input required type="text" id="f_dom_lat" name="latitude_gps_domicile" readonly class="w-1/2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm input-disabled">
                                        <input required type="text" id="f_dom_lng" name="longitude_gps_domicile" readonly class="w-1/2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm input-disabled">
                                    </div>
                                </div>
                            </div>
                            <div id="map" style="min-height: 300px; background: #eee;"></div>
                        </div>
                    </div>

                    {{-- SECTION: PAYROLL --}}
                    <div class="pt-10 border-t border-gray-100">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Informasi Payroll</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-sm">
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Bank</label><select required name="payroll_bank_name" id="f_bank_name" class="w-full px-4 py-2 bg-gray-50 border-none rounded-lg text-sm">@foreach(['BNI', 'Mandiri', 'BCA', 'BTN', 'BSI', 'BPD Bali'] as $bank)<option value="{{ $bank }}">{{ $bank }}</option>@endforeach</select></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nomor Rekening</label><input required type="number" name="payroll_bank_account_number" id="f_bank_acc" class="w-full px-4 py-2 bg-gray-50 border-none rounded-lg text-sm"></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Pemilik Rekening</label><input required type="text" name="payroll_bank_account_name" id="f_bank_owner" class="w-full px-4 py-2 bg-gray-50 border-none rounded-lg text-sm"></div>
                        </div>
                    </div>

                    {{-- SECTION: SOSMED --}}
                    <div class="pt-10 border-t border-gray-100">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Tautan Media Sosial</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-sm">
                            {{-- Atribut id (f_fb, f_ig, f_tt) sangat krusial untuk JavaScript --}}
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Facebook</label>
                                <input type="url" name="facebook_url" id="f_fb" class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm" placeholder="https://facebook.com/..">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Instagram</label>
                                <input type="url" name="instagram_url" id="f_ig" class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm" placeholder="https://instagram.com/..">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">TikTok</label>
                                <input type="url" name="tiktok_url" id="f_tt" class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm" placeholder="https://tiktok.com/@..">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-4">
                    <button type="button" onclick="closeMasterEditModal()" class="flex-1 py-4 text-[11px] font-bold uppercase text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-100">Batal</button>
                    <button type="submit" class="flex-1 py-4 text-[11px] font-bold uppercase text-white bg-slate-800 rounded-xl shadow-lg hover:bg-slate-900 transition-all active:scale-95">Simpan Seluruh Perubahan Profil</button>
                </div>
            </form>
        </div>
    </div>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            const apiBase = "/api-wilayah";
            const sleep = (ms) => new Promise(resolve => setTimeout(resolve, ms));

            // 1. Fungsi Inti Fetch & Match
            async function populateSelect(selectId, urlPath, placeholder, targetValue = null) {
                const select = document.getElementById(selectId);
                if (!select) return null;

                // Ambil nilai yang baru saja disuntikkan ke atribut data-selected
                const savedValue = select.getAttribute('data-selected');
                let valueToMatch = (targetValue || savedValue || "").toString().toUpperCase().trim();

                select.innerHTML = `<option value="">Mohon tunggu...</option>`;
                select.disabled = true;

                try {
                    const response = await fetch(`${apiBase}/${urlPath}`);
                    const result = await response.json();
                    const data = result.data;

                    let options = `<option value="">${placeholder}</option>`;
                    data.forEach(item => {
                        /**
                         * LOGIKA PEMBERSIHAN NAMA:
                         * Menghapus kata "KABUPATEN " atau "KOTA " hanya jika berada di awal string.
                         * Regex /^.../i memastikan pengecekan dilakukan di awal dan tidak peka huruf besar/kecil.
                         */
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

            // 2. Fungsi Rantai Loading (Berurutan)
            async function runChain(prefix) {
                const provCode = await populateSelect(`f_${prefix}_prov`, 'provinces.json', "Pilih Provinsi");
                if (provCode) {
                    const regCode = await populateSelect(`f_${prefix}_reg`, `regencies/${provCode}.json`, "Pilih Kabupaten");
                    if (regCode) {
                        const distCode = await populateSelect(`f_${prefix}_dist`, `districts/${regCode}.json`, "Pilih Kecamatan");
                        if (distCode) {
                            await populateSelect(`f_${prefix}_vill`, `villages/${distCode}.json`, "Pilih Desa/Kelurahan");
                        }
                    }
                }
            }

            // 3. Integrasi ke Fungsi Modal Global
            const oldOpenModal = window.openMasterEditModal;
            window.openMasterEditModal = function(user, person, isApprove) {
                // Jalankan fungsi pengisian field biasa (Nama, NIK, dll)
                if (oldOpenModal) oldOpenModal(user, person, isApprove);

                // Suntikkan data wilayah person ke atribut data-selected masing-masing select
                const mapping = {
                    'f_ktp_prov': person?.province_ktp,
                    'f_ktp_reg': person?.regency_ktp,
                    'f_ktp_dist': person?.district_ktp,
                    'f_ktp_vill': person?.village_ktp,
                    'f_dom_prov': person?.province_domicile,
                    'f_dom_reg': person?.regency_domicile,
                    'f_dom_dist': person?.district_domicile,
                    'f_dom_vill': person?.village_domicile
                };

                Object.entries(mapping).forEach(([id, val]) => {
                    const el = document.getElementById(id);
                    if (el) el.setAttribute('data-selected', val || '');
                });

                // Jalankan loading wilayah secara otomatis setelah modal terbuka
                setTimeout(() => {
                    runChain('ktp');
                    runChain('dom');
                }, 500);
            };

            // 4. Listener Perubahan Manual (User klik sendiri)
            const bindEvents = (p) => {
                ['prov', 'reg', 'dist'].forEach(f => {
                    const el = document.getElementById(`f_${p}_${f}`);
                    if (!el) return;

                    el.addEventListener('change', async function() {
                        const isSyncActive = document.getElementById('sync_address_admin').checked;
                        const code = this.options[this.selectedIndex]?.getAttribute('data-code');

                        const nextMap = {
                            'prov': ['reg', 'dist', 'vill'],
                            'reg': ['dist', 'vill'],
                            'dist': ['vill']
                        };

                        // --- FUNGSI RESET TOTAL ---
                        const resetLevel = (prefix, fields) => {
                            fields.forEach(target => {
                                const targetEl = document.getElementById(`f_${prefix}_${target}`);
                                if (targetEl) {
                                    targetEl.innerHTML = '<option value="">Pilih...</option>';
                                    // Jika dom & sync aktif, pastikan tetap terkunci
                                    if (prefix === 'dom' && isSyncActive) {
                                        targetEl.classList.add('input-disabled');
                                        targetEl.disabled = true;
                                    }
                                }
                            });
                        };

                        // Reset level bawah untuk prefix yang sedang diubah (KTP atau Dom)
                        resetLevel(p, nextMap[f]);

                        // Jika ubah KTP dan centang aktif, reset juga level bawah di Domisili
                        if (p === 'ktp' && isSyncActive) {
                            resetLevel('dom', nextMap[f]);
                            // Samakan nilai dropdown yang baru saja dipilih
                            const domSameLevel = document.getElementById(`f_dom_${f}`);
                            if (domSameLevel) domSameLevel.value = this.value;
                        }

                        if (code) {
                            const nextField = f === 'prov' ? 'reg' : (f === 'reg' ? 'dist' : 'vill');
                            const path = f === 'prov' ? 'regencies' : (f === 'reg' ? 'districts' : 'villages');

                            // Isi dropdown level berikutnya
                            await populateSelect(`f_${p}_${nextField}`, `${path}/${code}.json`, "Pilih...");

                            // Jika sync aktif, isi juga dropdown level berikutnya di domisili
                            if (p === 'ktp' && isSyncActive) {
                                await populateSelect(`f_dom_${nextField}`, `${path}/${code}.json`, "Pilih...");
                                const domNextEl = document.getElementById(`f_dom_${nextField}`);
                                if (domNextEl) {
                                    domNextEl.classList.add('input-disabled');
                                    domNextEl.disabled = true;
                                }
                            }
                        }

                        if (p === 'dom' || (p === 'ktp' && isSyncActive)) {
                            setTimeout(autoMoveMap, 300);
                        }
                    });
                });
            };

            bindEvents('ktp');
            bindEvents('dom');

            // 5. Fitur Sync (Gunakan Alamat KTP)
            const syncBtn = document.getElementById('sync_address_admin');
            if (syncBtn) {
                syncBtn.addEventListener('change', async function() {
                    if (this.checked) {
                        const ktpData = {
                            'f_dom_prov': document.getElementById('f_ktp_prov').value,
                            'f_dom_reg': document.getElementById('f_ktp_reg').value,
                            'f_dom_dist': document.getElementById('f_ktp_dist').value,
                            'f_dom_vill': document.getElementById('f_ktp_vill').value
                        };

                        // Suntikkan nilai KTP ke data-selected Domisili
                        Object.entries(ktpData).forEach(([id, val]) => {
                            const el = document.getElementById(id);
                            if (el) el.setAttribute('data-selected', val);
                        });

                        const ktpAddr = document.getElementById('f_ktp_address');
                        const domAddr = document.getElementById('f_dom_address');
                        if (ktpAddr && domAddr) domAddr.value = ktpAddr.value;

                        // Jalankan ulang chain khusus untuk domisili
                        runChain('dom');
                    }
                });
            }
        });


        // --- LOGIC SINKRONISASI ALAMAT DI ADMIN ---
        const syncBtnAdmin = document.getElementById('sync_address_admin');
        const addrMap = {
            'f_ktp_prov': 'f_dom_prov',
            'f_ktp_reg': 'f_dom_reg',
            'f_ktp_dist': 'f_dom_dist',
            'f_ktp_vill': 'f_dom_vill',
            'f_ktp_address': 'f_dom_address'
        };

        function performSync() {
            const isChecked = document.getElementById('sync_address_admin').checked;
            const fields = ['prov', 'reg', 'dist', 'vill', 'address'];

            fields.forEach(field => {
                const ktpEl = document.getElementById(`f_ktp_${field}`);
                const domEl = document.getElementById(`f_dom_${field}`);

                if (domEl && ktpEl) {
                    if (isChecked) {
                        // SINKRONKAN DATA
                        domEl.value = ktpEl.value;
                        domEl.classList.add('input-disabled');

                        if (domEl.tagName === 'SELECT') {
                            domEl.disabled = true;
                        } else {
                            domEl.readOnly = true;
                        }
                    } else {
                        // LEPASKAN KUNCI
                        domEl.classList.remove('input-disabled');
                        domEl.disabled = false;
                        domEl.readOnly = false;
                    }
                }
            });

            if (isChecked) {
                runChain('dom'); // Muat ulang semua dropdown domisili agar sinkron
                autoMoveMap();
            }
        }

        document.getElementById('f_ktp_address').addEventListener('input', function() {
            if (document.getElementById('sync_address_admin').checked) {
                const domAddr = document.getElementById('f_dom_address');
                domAddr.value = this.value;
            }
        });

        syncBtnAdmin.addEventListener('change', performSync);

        // Agar ketika admin mengetik di field KTP, field domisili langsung terupdate (jika centang aktif)
        Object.keys(addrMap).forEach(ktpId => {
            document.getElementById(ktpId).addEventListener('input', () => {
                if (syncBtnAdmin.checked) performSync();
            });
        });


        let mapObj, markerObj, cropperObj;
        const photoInputEl = document.getElementById('photo');

        // 1. Fungsi Asli Anda (TIDAK ADA YANG DIUBAH)
        function setupMap(lat, lng) {
            if (mapObj) mapObj.remove();

            let initialLat = lat || -8.112;
            let initialLng = lng || 115.091;

            mapObj = L.map('map').setView([initialLat, initialLng], 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapObj);

            if (lat && lng) {
                markerObj = L.marker([lat, lng]).addTo(mapObj);
            }

            mapObj.on('click', function(e) {
                if (markerObj) mapObj.removeLayer(markerObj);
                markerObj = L.marker(e.latlng).addTo(mapObj);

                // Update input latitude dan longitude menggunakan ID f_dom_...
                const latEl = document.getElementById('f_dom_lat');
                const lngEl = document.getElementById('f_dom_lng');
                if (latEl) latEl.value = e.latlng.lat.toFixed(8);
                if (lngEl) lngEl.value = e.latlng.lng.toFixed(8);
            });
        }

        // 2. Fungsi Tambahan agar Peta Dinamis mengikuti Dropdown
        async function autoMoveMap() {
            const prov = document.getElementById('f_dom_prov')?.value;
            const reg = document.getElementById('f_dom_reg')?.value;
            const dist = document.getElementById('f_dom_dist')?.value;
            const vill = document.getElementById('f_dom_vill')?.value;

            let queryParts = [vill, dist, reg, prov].filter(Boolean);
            let address = queryParts.join(", ");

            if (!address || !mapObj) return;

            try {
                const resp = await fetch(`/api-map-search?q=${encodeURIComponent(address)}`);
                const data = await resp.json();

                if (data && data.length > 0) {
                    const newLat = parseFloat(data[0].lat);
                    const newLng = parseFloat(data[0].lon);

                    let zoomLevel = 9;
                    if (vill) zoomLevel = 16;
                    else if (dist) zoomLevel = 14;
                    else if (reg) zoomLevel = 12;

                    mapObj.setView([newLat, newLng], zoomLevel, {
                        animate: true
                    });
                }
            } catch (e) {
                console.error("Gagal koordinasi peta:", e);
            }
        }
        window.openMasterEditModal = function(user, person, isApprove) {
            const form = document.getElementById('masterForm');
            if (!form) return;

            // Helper function to safely set values without crashing on missing IDs
            const setVal = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.value = val || '';
            };

            const methodField = document.getElementById('method_field');
            form.action = isApprove ? `/admin/manage-user/${user.id_user}/approve` : `/admin/manage-user/${user.id_user}/update`;
            if (methodField) methodField.innerHTML = isApprove ? '' : '<input type="hidden" name="_method" value="PATCH">';

            // 1. Identitas Utama
            setVal('f_email', user.email);
            setVal('f_phone', user.phone);
            setVal('f_name', person?.name);
            setVal('f_nik', person?.nik);
            setVal('f_no_kk', person?.no_kk);
            setVal('f_bpjs_kes', person?.no_bpjs_kes);
            setVal('f_bpjs_tk', person?.no_bpjs_tk);

            // 2. Kerja & Penempatan
            setVal('f_wa', person?.id_work_assignment);
            setVal('f_batch', person?.batch);
            setVal('f_emp', person?.employment_status);
            setVal('f_role', user.id_ref_role);
            setVal('f_pos', person?.id_ref_position);

            // 3. Alamat KTP
            setVal('f_ktp_prov', person?.province_ktp);
            setVal('f_ktp_reg', person?.regency_ktp);
            setVal('f_ktp_dist', person?.district_ktp);
            setVal('f_ktp_vill', person?.village_ktp);
            setVal('f_ktp_address', person?.address_ktp);

            // 4. Alamat Domisili
            setVal('f_dom_prov', person?.province_domicile);
            setVal('f_dom_reg', person?.regency_domicile);
            setVal('f_dom_dist', person?.district_domicile);
            setVal('f_dom_vill', person?.village_domicile);
            setVal('f_dom_address', person?.address_domicile);

            // 5. GPS & Social Media
            setVal('f_dom_lat', person?.latitude_gps_domicile);
            setVal('f_dom_lng', person?.longitude_gps_domicile);
            setVal('f_fb', person?.social_media?.facebook_url);
            setVal('f_ig', person?.social_media?.instagram_url);
            setVal('f_tt', person?.social_media?.tiktok_url);

            // 6. Info Pribadi & Payroll
            setVal('f_last_edu', person?.last_education);
            setVal('f_title_edu', person?.title_education);
            setVal('f_major', person?.major_education);
            setVal('f_place', person?.place_birthday);
            setVal('f_date', person?.date_birthday);
            setVal('f_age', person?.age);
            setVal('f_gender', person?.gender);
            setVal('f_religion', person?.religion);
            setVal('f_marital', person?.marital_status);
            setVal('f_cloth', person?.clothing_size);
            setVal('f_shoe', person?.shoe_size);
            setVal('f_nip', person?.nip);
            setVal('f_npwp', person?.npwp);
            setVal('f_bank_name', person?.payroll_bank_name);
            setVal('f_bank_acc', person?.payroll_bank_account_number);
            setVal('f_bank_owner', person?.payroll_bank_account_name);

            // Image Logic
            const previewImg = document.getElementById('cropped-preview');
            const placeholderDiv = document.getElementById('initial-placeholder');
            if (person?.photo && previewImg) {
                previewImg.src = `/storage/${person.photo}`;
                previewImg.classList.remove('hidden');
                if (placeholderDiv) placeholderDiv.classList.add('hidden');
            } else if (placeholderDiv) {
                if (previewImg) previewImg.classList.add('hidden');
                placeholderDiv.innerText = (person?.name || user.email || '?').substring(0, 1).toUpperCase();
                placeholderDiv.classList.remove('hidden');
            }

            document.getElementById('masterModal').classList.remove('hidden');

            // Setup Map
            setTimeout(() => {
                const lat = parseFloat(person?.latitude_gps_domicile);
                const lng = parseFloat(person?.longitude_gps_domicile);
                setupMap(lat, lng);
                if (mapObj) mapObj.invalidateSize();
            }, 400);

            // Reset checkbox sinkronisasi alamat saat modal dibuka
            const syncBox = document.getElementById('sync_address_admin');
            if (syncBox) {
                syncBox.checked = false;
                // Panggil fungsi performSync untuk melepas status readOnly pada field domisili
                for (const domId of Object.values(addrMap)) {
                    const domEl = document.getElementById(domId);
                    if (domEl) {
                        domEl.classList.remove('input-disabled');
                        domEl.readOnly = false;
                    }
                }
            }
        };
        window.closeMasterEditModal = function() {
            document.getElementById('masterModal').classList.add('hidden');
        }
        document.getElementById('f_date').addEventListener('change', function() {
            const birthDate = new Date(this.value);
            if (isNaN(birthDate)) return;
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            document.getElementById('f_age').value = age;
        });
</script>
