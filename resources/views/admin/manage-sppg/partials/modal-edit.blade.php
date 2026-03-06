<div x-show="showEditModal" class="fixed inset-0 z-[99] flex items-center justify-center p-4 text-left" x-cloak>
    <style>
        .input-disabled {
            background-color: #f8fafc !important;
            color: #94a3b8 !important;
            cursor: not-allowed !important;
            pointer-events: none;
            border: 1px solid #e2e8f0 !important;
        }

        #map-edit {
            height: 350px;
            width: 100%;
            border-radius: 0.75rem;
            margin-top: 0.5rem;
            z-index: 1;
            background: #f8fafc;
        }

        /* Style input saat error */
        .input-error-edit {
            border: 1px solid #ef4444 !important;
            ring: 2px #fef2f2 !important;
            background-color: #fff1f2 !important;
        }

        .error-warning-edit {
            color: #ef4444;
            font-size: 10px;
            font-weight: 700;
            font-style: italic;
            margin-top: 4px;
            display: block;
        }
    </style>

    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showEditModal = false"></div>

    <div x-show="showEditModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="relative bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-6xl overflow-hidden transform transition-all font-sans text-sm">

        <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center text-slate-800">
            <div class="flex items-center gap-3">
                <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M18.364 4.982a2.378 2.378 0 113.359 3.359L10.852 19.531l-4.243.606.606-4.243L18.364 4.982z" />
                    </svg>
                </span>
                <h3 class="text-[14px] font-bold uppercase tracking-widest text-slate-800">Edit SPPG Unit</h3>
            </div>
            <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 text-2xl cursor-pointer">&times;</button>
        </div>

        <form action="#"
            method="POST"
            enctype="multipart/form-data"
            id="editUnitForm"
            @submit.prevent="window.submitUpdateSppg($el)">
            @csrf
            @method('PATCH')

            {{-- Hidden Inputs Wilayah (Akan diisi oleh syncEditWilayah) --}}
            <input type="hidden" name="province_name" id="e_prov_name">
            <input type="hidden" name="regency_name" id="e_reg_name">
            <input type="hidden" name="district_name" id="e_dist_name">
            <input type="hidden" name="village_name" id="e_vill_name">

            <div class="p-8 max-h-[75vh] overflow-y-auto space-y-10 custom-scrollbar" id="editModalScrollContainer">

                {{-- SECTION 1: FOTO & IDENTITAS --}}
                <div class="flex flex-col lg:flex-row gap-12">
                    <div class="shrink-0 flex flex-col items-center gap-4">
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Foto Tampak Depan SPPG</label>
                        <div class="relative group">
                            <div id="edit-photo-container" class="h-45 w-60 rounded-2xl overflow-hidden bg-slate-200 border-4 border-white shadow-lg ring-1 ring-slate-100 flex items-center justify-center text-center transition-all">
                                <img id="edit-cropped-preview" class="h-full w-full object-cover cursor-pointer"
                                    :src="selectedUnit.photo ? '/storage/' + selectedUnit.photo : ''"
                                    :class="selectedUnit.photo ? '' : 'hidden'">
                                <div id="edit-initial-placeholder" :class="selectedUnit.photo ? 'hidden' : 'text-indigo-500 text-6xl'">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                            </div>
                            <label for="edit_photo" class="absolute bottom-3 right-3 p-2.5 bg-indigo-500 text-white rounded-xl shadow-xl cursor-pointer hover:bg-indigo-600 transition-all hover:scale-110">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <input type="file" id="edit_photo" name="photo" class="hidden" accept="image/*">
                            </label>
                        </div>
                        {{-- DIV Error Foto --}}
                        <div id="error-edit_photo_container"></div>
                    </div>

                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="md:col-span-2">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama SPPG</label>
                            <input type="text" name="name" id="e_name" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Masukkan Nama SPPG">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">ID SPPG</label>
                            <input type="text" name="id_sppg_unit" id="e_id" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Cth: 6UWFOPNM">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kode SPPG</label>
                            <input type="text" name="code_sppg_unit" id="e_code" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Cth: 51.XX.XX.XXXX.XX">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Status Operasional</label>
                            <select name="status" id="e_status" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="" disabled>Pilih Status</option>
                                <option value="Belum Operasional">Belum Operasional</option>
                                <option value="Operasional">Operasional</option>
                                <option value="Tutup Sementara">Tutup Sementara</option>
                                <option value="Tutup Permanen">Tutup Permanen</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tanggal Operasional</label>
                            <input type="date" name="operational_date" id="e_op_date" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm text-slate-600 focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kepala SPPG</label>
                            <select name="leader_id" id="e_leader" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="" disabled>Pilih Kepala SPPG</option>
                                <option value="NULL">Belum Ditugaskan</option>
                                @foreach($leaders as $leader)
                                <option value="{{ $leader->id_person }}">{{ $leader->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Ahli Gizi</label>
                            <select name="nutritionist_id" id="e_nutritionist" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="" disabled>Pilih Ahli Gizi</option>
                                <option value="NULL">Belum Ditugaskan</option>
                                @foreach($nutritionists as $person)
                                <option value="{{ $person->id_person }}">{{ $person->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Akuntan</label>
                            <select name="accountant_id" id="e_accountant" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="" disabled>Pilih Akuntan</option>
                                <option value="NULL">Belum Ditugaskan</option>
                                @foreach($accountants as $person)
                                <option value="{{ $person->id_person }}">{{ $person->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- SECTION SK --}}
                <div class="pt-10 border-t border-gray-100">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Surat Keputusan (SK)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @php
                            $leaderPos = \App\Models\RefPosition::where('slug_position', 'kasppg')->first();
                            $nutriPos = \App\Models\RefPosition::where('slug_position', 'ag')->first();
                            $accPos = \App\Models\RefPosition::where('slug_position', 'ak')->first();
                        @endphp
                        
                        {{-- SK KEPALA --}}
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">SK Kepala SPPG <span class="text-red-500">*</span></label>
                            <select name="id_sk_leader" id="e_sk_leader" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="" disabled>Pilih SK Kepala</option>
                                @foreach($decrees[$leaderPos?->id_ref_position] ?? [] as $decree)
                                <option value="{{ $decree->id_assignment_decree }}">
                                    {{ $decree->no_sk }} ({{ \Carbon\Carbon::parse($decree->date_sk)->format('d/m/Y') }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- SK AHLI GIZI --}}
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">SK Ahli Gizi</label>
                            <select name="id_sk_nutritionist" id="e_sk_nutritionist" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-emerald-500">
                                <option value="placeholder" disabled>Pilih SK Ahli Gizi</option>
                                <option value="">Belum Ada SK</option>
                                @foreach($decrees[$nutriPos?->id_ref_position] ?? [] as $decree)
                                <option value="{{ $decree->id_assignment_decree }}">
                                    {{ $decree->no_sk }} ({{ \Carbon\Carbon::parse($decree->date_sk)->format('d/m/Y') }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- SK AKUNTAN --}}
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">SK Akuntan</label>
                            <select name="id_sk_accountant" id="e_sk_accountant" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                                <option value="placeholder" disabled>Pilih SK Akuntan</option>
                                <option value="">Belum Ada SK</option>
                                @foreach($decrees[$accPos?->id_ref_position] ?? [] as $decree)
                                <option value="{{ $decree->id_assignment_decree }}">
                                    {{ $decree->no_sk }} ({{ \Carbon\Carbon::parse($decree->date_sk)->format('d/m/Y') }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: ALAMAT --}}
                <div class="pt-10 border-t border-gray-100">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Informasi Alamat</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 text-sm">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Provinsi</label>
                            <select name="province" id="e_prov" class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="">Pilih Provinsi</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kabupaten</label>
                            <select name="regency" id="e_reg" class="input-disabled w-full mt-1 px-4 py-2 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" disabled>
                                <option value="">Pilih Kabupaten</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kecamatan</label>
                            <select name="district" id="e_dist" class="input-disabled w-full mt-1 px-4 py-2 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" disabled>
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Desa/Kelurahan</label>
                            <select name="village" id="e_vill" class="input-disabled w-full mt-1 px-4 py-2 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" disabled>
                                <option value="">Pilih Desa/Kelurahan</option>
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Alamat Jalan</label>
                            <textarea name="address" id="e_address" rows="3" class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Cth: Jl. Raya Singaraja Denpasar, No. 99"></textarea>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: PETA --}}
                <div class="pt-10 border-t border-gray-100">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Informasi Lokasi GPS (Klik Peta Untuk Ubah)</h3>
                    <div id="map-edit" class="rounded-xl border-4 border-white shadow-lg ring-1 ring-slate-200"></div>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <input type="text" name="latitude_gps" id="e_lat" readonly class="w-full px-4 py-2.5 bg-slate-100 border-none rounded-lg text-sm text-slate-500" placeholder="Latitude Otomatis">
                        <input type="text" name="longitude_gps" id="e_lng" readonly class="w-full px-4 py-2.5 bg-slate-100 border-none rounded-lg text-sm text-slate-500" placeholder="Longitude Otomatis">
                    </div>
                </div>

                {{-- SECTION 4: SOSIAL MEDIA --}}
                <div class="pt-10 border-t border-gray-100">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Sosial Media</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Facebook</label>
                            <div class="flex mt-2 rounded-lg overflow-hidden ring-1 ring-gray-200 focus-within:ring-2 focus-within:ring-indigo-400">
                                <span class="px-3 py-2.5 bg-gray-100 text-blue-600 text-xs font-bold flex items-center shrink-0">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                                </span>
                                <input type="url" name="facebook_url" id="e_facebook" class="flex-1 px-3 py-2.5 bg-gray-50 border-none text-sm focus:outline-none focus:ring-0" placeholder="https://facebook.com/...">
                            </div>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Instagram</label>
                            <div class="flex mt-2 rounded-lg overflow-hidden ring-1 ring-gray-200 focus-within:ring-2 focus-within:ring-indigo-400">
                                <span class="px-3 py-2.5 bg-gray-100 text-pink-500 text-xs font-bold flex items-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                                </span>
                                <input type="url" name="instagram_url" id="e_instagram" class="flex-1 px-3 py-2.5 bg-gray-50 border-none text-sm focus:outline-none focus:ring-0" placeholder="https://instagram.com/...">
                            </div>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">TikTok</label>
                            <div class="flex mt-2 rounded-lg overflow-hidden ring-1 ring-gray-200 focus-within:ring-2 focus-within:ring-indigo-400">
                                <span class="px-3 py-2.5 bg-gray-100 text-slate-800 text-xs font-bold flex items-center shrink-0">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.17 8.17 0 004.78 1.52V6.75a4.85 4.85 0 01-1.01-.06z"/></svg>
                                </span>
                                <input type="url" name="tiktok_url" id="e_tiktok" class="flex-1 px-3 py-2.5 bg-gray-50 border-none text-sm focus:outline-none focus:ring-0" placeholder="https://tiktok.com/@...">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 5: DAFTAR PM --}}
                <div class="pt-10 border-t border-gray-100" x-data="{ 
                    stagedBeneficiaries: [], 
                    isLinking: false,
                    searchTerm: '',
                    get filteredAllBeneficiaries() {
                        if (!this.searchTerm) return [];
                        const lower = this.searchTerm.toLowerCase();
                        return allBeneficiaryList.filter(b => 
                            (b.name.toLowerCase().includes(lower) || (b.code && b.code.toLowerCase().includes(lower))) &&
                            !this.stagedBeneficiaries.find(s => s.id_beneficiary === b.id_beneficiary)
                        ).slice(0, 5);
                    },
                    addStagedBeneficiary(beneficiary) {
                        this.stagedBeneficiaries.push(beneficiary);
                        this.searchTerm = '';
                    },
                    removeStagedBeneficiary(beneficiaryId) {
                        this.stagedBeneficiaries = this.stagedBeneficiaries.filter(p => p.id_beneficiary !== beneficiaryId);
                    },
                    async linkStagedBeneficiaries() {
                        if (this.stagedBeneficiaries.length === 0) return;
                        this.isLinking = true;
                        const idsToLink = this.stagedBeneficiaries.map(p => p.id_beneficiary);
                        try {
                            const resp = await fetch('{{ route("admin.manage-beneficiary.batch-link-to-sppg") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ 
                                    id_beneficiary_list: idsToLink, 
                                    id_sppg_unit: selectedUnit.id_sppg_unit 
                                })
                            });
                            const data = await resp.json();
                            if (resp.ok) {
                                selectedUnit.beneficiaries = data.unit_beneficiaries;
                                
                                // Update global allBeneficiaryList to reflect these are now taken
                                allBeneficiaryList.forEach(p => {
                                    if (idsToLink.includes(p.id_beneficiary)) {
                                        p.id_sppg_unit = selectedUnit.id_sppg_unit;
                                    }
                                });

                                this.stagedBeneficiaries = [];
                                this.searchTerm = '';
                            } else {
                                alert('Gagal menautkan: ' + (data.errors ? JSON.stringify(data.errors) : 'Unknown error'));
                            }
                        } catch (err) {
                            console.error(err);
                            alert('Gagal menyambung ke server');
                        } finally {
                            this.isLinking = false;
                        }
                    }
                }"
                @beneficiary-created-integrated.window="
                    selectedUnit.beneficiaries.push($event.detail.beneficiary); 
                    allBeneficiaryList.push($event.detail.beneficiary);
                    showCreateBeneficiaryModal = false
                "
                @beneficiary-updated-integrated.window="
                    const idx = selectedUnit.beneficiaries.findIndex(b => b.id_beneficiary === $event.detail.beneficiary.id_beneficiary);
                    if (idx !== -1) selectedUnit.beneficiaries[idx] = $event.detail.beneficiary;
                    const idxAll = allBeneficiaryList.findIndex(b => b.id_beneficiary === $event.detail.beneficiary.id_beneficiary);
                    if (idxAll !== -1) allBeneficiaryList[idxAll] = $event.detail.beneficiary;
                    showEditBeneficiaryModal = false
                "
                >
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                        <div class="flex items-center gap-3">
                            <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </span>
                            <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600">Penerima Manfaat (PM) Terhubung</h3>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <button type="button" @click="$dispatch('open-create-beneficiary')" class="px-4 py-2 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-emerald-100 transition-all flex items-center">
                                <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <span>Tambah PM Baru</span>
                            </button>
                            <a :href="`/admin/manage-beneficiary?search=${selectedUnit.id_sppg_unit}`" target="_blank" class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-indigo-100 transition-all flex items-center">
                                <span>Kelola Semua PM</span>
                                <svg class="w-3.5 h-3.5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    {{-- Link Existing PM Tool --}}
                    <div class="mb-6 p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block mb-2">Tautkan PM yang Sudah Ada</label>
                        <div class="flex gap-2 relative">
                            <div class="relative flex-1">
                                <input type="text" x-model="searchTerm" placeholder="Cari Nama atau Kode PM..." class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                                <div x-show="searchTerm && filteredAllBeneficiaries.length > 0" class="absolute z-[100] left-0 right-0 mt-1 bg-white border border-slate-200 rounded-lg shadow-xl overflow-hidden max-h-60 overflow-y-auto">
                                    <template x-for="item in filteredAllBeneficiaries" :key="item.id_beneficiary">
                                        <button type="button" 
                                            @click="if(!item.id_sppg_unit) addStagedBeneficiary(item)" 
                                            :disabled="item.id_sppg_unit !== null"
                                            class="w-full text-left px-4 py-3 hover:bg-indigo-50 flex flex-row items-center justify-between border-b border-slate-50 last:border-0 group transition-all"
                                            :class="item.id_sppg_unit ? 'opacity-60 cursor-not-allowed bg-slate-50' : 'cursor-pointer'">
                                            <div class="flex flex-col gap-0.5">
                                                <span class="font-bold text-slate-700" x-text="item.name"></span>
                                                <span class="text-[10px] text-slate-400" x-text="item.group_type + (item.code ? ' • ' + item.code : '')"></span>
                                            </div>
                                            <div class="flex flex-col items-end gap-1">
                                                <template x-if="item.id_sppg_unit === null">
                                                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[9px] font-bold uppercase rounded border border-emerald-100 group-hover:bg-emerald-600 group-hover:text-white transition-colors">Pilih</span>
                                                </template>
                                                <template x-if="item.id_sppg_unit !== null">
                                                    <div class="flex flex-col items-end">
                                                        <span class="px-2 py-0.5 bg-rose-50 text-rose-500 text-[9px] font-bold uppercase rounded border border-rose-100">Sudah Ditautkan</span>
                                                        <span class="text-[8px] text-rose-400 mt-0.5 italic" x-text="item.id_sppg_unit === selectedUnit.id_sppg_unit ? 'Unit ini' : 'Unit lain (' + item.id_sppg_unit + ')'"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </button>
                                    </template>
                                </div>
                                <div x-show="searchTerm && filteredAllBeneficiaries.length === 0" class="absolute z-[100] left-0 right-0 mt-1 bg-white border border-slate-200 rounded-lg shadow-xl p-4 text-center">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">Tidak ada PM ditemukan</p>
                                </div>
                            </div>
                            <button type="button" 
                                @click="linkStagedBeneficiaries()"
                                :disabled="stagedBeneficiaries.length === 0 || isLinking"
                                class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-xs font-bold uppercase transition-all hover:bg-indigo-700 disabled:bg-slate-300">
                                <span x-show="!isLinking">Tautkan <span x-show="stagedBeneficiaries.length > 0" x-text="'(' + stagedBeneficiaries.length + ')'"></span></span>
                                <span x-show="isLinking">...</span>
                            </button>
                        </div>

                        {{-- Staged Beneficiaries List --}}
                        <template x-if="stagedBeneficiaries.length > 0">
                            <div class="mt-4 flex flex-wrap gap-2">
                                <template x-for="beneficiary in stagedBeneficiaries" :key="beneficiary.id_beneficiary">
                                    <div class="px-3 py-1.5 bg-indigo-100 text-indigo-700 rounded-lg text-[10px] font-bold flex items-center gap-2 border border-indigo-200 shadow-sm animate-in fade-in zoom-in duration-200">
                                        <span x-text="beneficiary.name"></span>
                                        <button type="button" @click="removeStagedBeneficiary(beneficiary.id_beneficiary)" class="p-0.5 hover:bg-indigo-200 rounded-full transition-colors text-indigo-400 hover:text-indigo-600">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                    
                    <div class="bg-white rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead>
                                    <tr class="bg-indigo-600/5 text-[11px] font-bold text-indigo-600/60 uppercase tracking-widest border-b border-indigo-600/10">
                                        <th class="px-5 py-4 text-center">Identitas PM</th>
                                        <th class="px-5 py-4 text-center">Rincian Porsi</th>
                                        <th class="px-5 py-4">Alamat & GPS</th>
                                        <th class="px-5 py-4">PIC / Kontak</th>
                                        <th class="px-5 py-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    <template x-if="selectedUnit.beneficiaries && selectedUnit.beneficiaries.length > 0">
                                        <template x-for="beneficiary in selectedUnit.beneficiaries" :key="beneficiary.id_beneficiary">
                                            <tr class="hover:bg-indigo-50/30 transition-colors">
                                                <td class="px-5 py-4 align-top">
                                                    <div class="font-bold text-slate-800 capitalize leading-tight text-sm" x-text="beneficiary.name"></div>
                                                    <div class="text-[11px] font-bold text-indigo-600 mt-1 uppercase" x-text="beneficiary.group_type + (beneficiary.category ? ' • ' + beneficiary.category : '')"></div>
                                                    <div class="text-[10px] text-slate-500 mt-1" x-text="'Kepemilikan: ' + (beneficiary.ownership_type || '-')"></div>
                                                    <div class="text-[10px] text-slate-400 mt-0.5" x-text="beneficiary.code ? 'Kode: ' + beneficiary.code : ''"></div>
                                                    <div class="mt-2">
                                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase border"
                                                              :class="beneficiary.is_active ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100'"
                                                              x-text="beneficiary.is_active ? 'Aktif' : 'Non-Aktif'"></span>
                                                    </div>
                                                </td>
                                                <td class="px-5 py-4 align-top text-slate-600">
                                                    <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-[11px]">
                                                        <span>Kecil (L/P):</span>
                                                        <span class="font-bold text-slate-700" x-text="(beneficiary.small_portion_male || 0) + '/' + (beneficiary.small_portion_female || 0)"></span>
                                                        <span>Besar (L/P):</span>
                                                        <span class="font-bold text-slate-700" x-text="(beneficiary.large_portion_male || 0) + '/' + (beneficiary.large_portion_female || 0)"></span>
                                                        <span>Guru/Staff:</span>
                                                        <span class="font-bold text-slate-700" x-text="(beneficiary.teacher_portion || 0) + '/' + (beneficiary.staff_portion || 0)"></span>
                                                        <span>Kader:</span>
                                                        <span class="font-bold text-slate-700" x-text="beneficiary.cadre_portion || 0"></span>
                                                    </div>
                                                </td>
                                                <td class="px-5 py-4 align-top max-w-[200px]">
                                                    <div class="text-slate-600 line-clamp-2 text-sm" x-text="beneficiary.address || '-'"></div>
                                                    <div class="text-[10px] text-slate-400 mt-1" x-text="(beneficiary.village || '-') + ', ' + (beneficiary.district || '-') + ', ' + (beneficiary.regency || '-')"></div>
                                                    <div class="text-[10px] text-slate-400" x-text="(beneficiary.province || '-') + ' ' + (beneficiary.postal_code || '')"></div>
                                                    <div class="mt-2 flex items-center text-[10px] font-bold text-indigo-500">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 010-5 2.5 2.5 0 010 5z"/></svg>
                                                        <a :href="`https://www.google.com/maps?q=${beneficiary.latitude_gps},${beneficiary.longitude_gps}`" target="_blank" class="hover:underline flex items-center">
                                                            <span x-text="(beneficiary.latitude_gps && beneficiary.longitude_gps) ? beneficiary.latitude_gps.slice(0,10) + ', ' + beneficiary.longitude_gps.slice(0,10) : 'GPS Tidak Ada'"></span>
                                                            <svg class="w-2.5 h-2.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="px-5 py-4 align-top">
                                                    <div class="text-slate-700 font-bold text-sm" x-text="beneficiary.pic_name || '-'"></div>
                                                    <div class="text-[11px] text-indigo-600 font-medium mt-1 flex items-center">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                        <span x-text="beneficiary.pic_phone || '-'"></span>
                                                    </div>
                                                    <div class="text-[10px] text-slate-400 mt-1 break-all" x-text="beneficiary.pic_email || '-'"></div>
                                                </td>
                                                <td class="px-5 py-4 text-center align-top">
                                                    <div class="flex justify-center items-center gap-1">
                                                        <button type="button" 
                                                            @click="$dispatch('open-edit-beneficiary', { beneficiary: beneficiary })"
                                                            class="p-2 text-indigo-500 hover:bg-indigo-50 rounded-lg transition-all" title="Ubah Data PM">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </button>
                                                        <button type="button" 
                                                            @click="$dispatch('open-unlink-modal', { beneficiary: beneficiary })"
                                                            class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-all" title="Lepas Tautan">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </template>
                                    <template x-if="!selectedUnit.beneficiaries || selectedUnit.beneficiaries.length === 0">
                                        <tr>
                                            <td colspan="5" class="px-5 py-12 text-center">
                                                <div class="flex flex-col items-center">
                                                    <div class="p-3 bg-slate-50 rounded-full mb-3">
                                                        <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                        </svg>
                                                    </div>
                                                    <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400 italic">Belum ada PM terhubung</p>
                                                    <p class="text-[10px] text-slate-300 mt-1">Gunakan alat pencarian atau tambah PM baru untuk mengisi daftar ini</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Ringkasan Total PM --}}
                    <template x-if="selectedUnit.beneficiaries && selectedUnit.beneficiaries.length > 0">
                        <div class="mt-4 p-4 bg-indigo-50/60 rounded-xl border border-indigo-100">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span class="text-[11px] font-bold uppercase tracking-widest text-indigo-600">Ringkasan Total</span>
                                <span class="ml-auto px-2.5 py-0.5 bg-indigo-600 text-white text-[11px] font-bold rounded-full" x-text="selectedUnit.beneficiaries.length + ' PM'"></span>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <div class="bg-white rounded-lg p-3 text-center border border-indigo-100/80 shadow-sm">
                                    <div class="text-[10px] font-bold text-slate-400 uppercase mb-1">Porsi Kecil (L)</div>
                                    <div class="text-lg font-bold text-slate-700" x-text="selectedUnit.beneficiaries.reduce((s, b) => s + parseInt(b.small_portion_male || 0), 0)"></div>
                                </div>
                                <div class="bg-white rounded-lg p-3 text-center border border-indigo-100/80 shadow-sm">
                                    <div class="text-[10px] font-bold text-slate-400 uppercase mb-1">Porsi Kecil (P)</div>
                                    <div class="text-lg font-bold text-slate-700" x-text="selectedUnit.beneficiaries.reduce((s, b) => s + parseInt(b.small_portion_female || 0), 0)"></div>
                                </div>
                                <div class="bg-white rounded-lg p-3 text-center border border-indigo-100/80 shadow-sm">
                                    <div class="text-[10px] font-bold text-slate-400 uppercase mb-1">Porsi Besar (L)</div>
                                    <div class="text-lg font-bold text-slate-700" x-text="selectedUnit.beneficiaries.reduce((s, b) => s + parseInt(b.large_portion_male || 0), 0)"></div>
                                </div>
                                <div class="bg-white rounded-lg p-3 text-center border border-indigo-100/80 shadow-sm">
                                    <div class="text-[10px] font-bold text-slate-400 uppercase mb-1">Porsi Besar (P)</div>
                                    <div class="text-lg font-bold text-slate-700" x-text="selectedUnit.beneficiaries.reduce((s, b) => s + parseInt(b.large_portion_female || 0), 0)"></div>
                                </div>
                                <div class="bg-white rounded-lg p-3 text-center border border-indigo-100/80 shadow-sm">
                                    <div class="text-[10px] font-bold text-slate-400 uppercase mb-1">Porsi Guru</div>
                                    <div class="text-lg font-bold text-slate-700" x-text="selectedUnit.beneficiaries.reduce((s, b) => s + parseInt(b.teacher_portion || 0), 0)"></div>
                                </div>
                                <div class="bg-white rounded-lg p-3 text-center border border-indigo-100/80 shadow-sm">
                                    <div class="text-[10px] font-bold text-slate-400 uppercase mb-1">Porsi Staff</div>
                                    <div class="text-lg font-bold text-slate-700" x-text="selectedUnit.beneficiaries.reduce((s, b) => s + parseInt(b.staff_portion || 0), 0)"></div>
                                </div>
                                <div class="bg-white rounded-lg p-3 text-center border border-indigo-100/80 shadow-sm">
                                    <div class="text-[10px] font-bold text-slate-400 uppercase mb-1">Porsi Kader</div>
                                    <div class="text-lg font-bold text-slate-700" x-text="selectedUnit.beneficiaries.reduce((s, b) => s + parseInt(b.cadre_portion || 0), 0)"></div>
                                </div>
                                <div class="bg-indigo-600 rounded-lg p-3 text-center shadow-sm">
                                    <div class="text-[10px] font-bold text-indigo-200 uppercase mb-1">Total Seluruh</div>
                                    <div class="text-lg font-bold text-white" x-text="selectedUnit.beneficiaries.reduce((s, b) => s + parseInt(b.small_portion_male || 0) + parseInt(b.small_portion_female || 0) + parseInt(b.large_portion_male || 0) + parseInt(b.large_portion_female || 0) + parseInt(b.teacher_portion || 0) + parseInt(b.staff_portion || 0) + parseInt(b.cadre_portion || 0), 0)"></div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>



            </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-4">
                <button type="button" @click="showEditModal = false" class="flex-1 py-4 text-[11px] font-bold uppercase text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition-all">Batal</button>
                <button type="submit" id="btnUpdateSppg" class="flex-1 py-4 text-[11px] font-bold uppercase text-white bg-slate-800 rounded-xl shadow-lg hover:bg-slate-900 transition-all active:scale-95">Simpan Perubahan</button>
            </div>

        </form>
    </div>
</div>

<script>
    var editMapInstance = null;
    var editMarkerInstance = null;
    var editOriginalImageData = null;
    var lastEditCropData = null;

    // ================================================================
    // 1. LISTENER UTAMA (DIPICU SAAT TOMBOL EDIT DIKLIK)
    // ================================================================
    window.addEventListener('init-edit-sppg', function(e) {
        const unit = e.detail;

        // Reset state & file input
        editOriginalImageData = null;
        lastEditCropData = null;
        const photoInput = document.getElementById('edit_photo');
        if (photoInput) photoInput.value = "";

        // Bersihkan error lama
        clearEditErrors();

        // Populate semua field secara manual (seperti modal create)
        document.getElementById('e_name').value         = unit.name || '';
        document.getElementById('e_id').value           = unit.id_sppg_unit || '';
        document.getElementById('e_code').value         = unit.code_sppg_unit || '';
        document.getElementById('e_op_date').value      = unit.operational_date || '';
        document.getElementById('e_address').value      = unit.address || '';
        document.getElementById('e_lat').value          = unit.latitude_gps || '';
        document.getElementById('e_lng').value          = unit.longitude_gps || '';

        // Set form action dengan ID unit
        const form = document.getElementById('editUnitForm');
        const unitId = unit.id_sppg_unit || unit.original_id || '';
        form.setAttribute('action', `/admin/manage-sppg/${unitId}/update`);

        // Set select status
        const statusEl = document.getElementById('e_status');
        if (statusEl && unit.status) {
            statusEl.value = unit.status;
        }

        // Set select leader, nutritionist, accountant
        const leaderEl = document.getElementById('e_leader');
        if (leaderEl) leaderEl.value = unit.leader_id || 'NULL';
        const nutritionistEl = document.getElementById('e_nutritionist');
        if (nutritionistEl) nutritionistEl.value = unit.nutritionist_id || 'NULL';
        const accountantEl = document.getElementById('e_accountant');
        if (accountantEl) accountantEl.value = unit.accountant_id || 'NULL';

        // Set select SKs (dari assignedDecreeMap)
        const unitDecrees = @json($assignedDecreeMap)[unit.id_sppg_unit] || {};
        
        const leaderPosId = "{{ \App\Models\RefPosition::where('slug_position', 'kasppg')->first()?->id_ref_position }}";
        const nutriPosId  = "{{ \App\Models\RefPosition::where('slug_position', 'ag')->first()?->id_ref_position }}";
        const accPosId    = "{{ \App\Models\RefPosition::where('slug_position', 'ak')->first()?->id_ref_position }}";

        const skLeaderEl = document.getElementById('e_sk_leader');
        if (skLeaderEl) skLeaderEl.value = unitDecrees[leaderPosId] || '';
        
        const skNutriEl = document.getElementById('e_sk_nutritionist');
        if (skNutriEl) skNutriEl.value = unitDecrees[nutriPosId] || '';
        
        const skAccEl = document.getElementById('e_sk_accountant');
        if (skAccEl) skAccEl.value = unitDecrees[accPosId] || '';

        // Populate Hidden Wilayah Names
        document.getElementById('e_prov_name').value  = unit.province || '';
        document.getElementById('e_reg_name').value   = unit.regency || '';
        document.getElementById('e_dist_name').value  = unit.district || '';
        document.getElementById('e_vill_name').value  = unit.village || '';

        // Populate Sosial Media
        if (unit.social_media) {
            document.getElementById('e_facebook').value  = unit.social_media.facebook_url || '';
            document.getElementById('e_instagram').value = unit.social_media.instagram_url || '';
            document.getElementById('e_tiktok').value    = unit.social_media.tiktok_url || '';
        } else {
            document.getElementById('e_facebook').value  = '';
            document.getElementById('e_instagram').value = '';
            document.getElementById('e_tiktok').value    = '';
        }

        // Init Map & Wilayah
        initEditMapModal();
        initEditCropperLogic();
        syncEditWilayah(unit);

        // Simpan data personil unit ini sebagai referensi pengecekan occupancy
        currentEditUnitPersonnel = {
            leader_id:       unit.leader_id       ? String(unit.leader_id)       : null,
            nutritionist_id: unit.nutritionist_id ? String(unit.nutritionist_id) : null,
            accountant_id:   unit.accountant_id   ? String(unit.accountant_id)   : null,
        };

        // Cek Occupancy Personil
        checkEditOccupancy('e_leader', 'kasppg');
        checkEditOccupancy('e_nutritionist', 'ag');
        checkEditOccupancy('e_accountant', 'ak');
    });

    // ================================================================
    // 2. VALIDASI PERSONIL (OCCUPANCY WARNING)
    // ================================================================
    const editOccupiedPeople = @json($occupiedPeople ?? []);
    let currentEditUnitPersonnel = {}; // Data personil unit yang sedang dibuka

    // Mapping: slug jabatan → kolom di sppg_units
    const editSlugToColumn = { kasppg: 'leader_id', ag: 'nutritionist_id', ak: 'accountant_id' };

    function checkEditOccupancy(id, slug) {
        const el = document.getElementById(id);
        if (!el) return;

        const warnId = id + '-occupancy-warn';
        let warnEl = document.getElementById(warnId);
        if (!warnEl) {
            warnEl = document.createElement('div');
            warnEl.id = warnId;
            warnEl.className = 'text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded border border-indigo-100 mt-1 hidden';
            el.after(warnEl);
        }

        const personId = el.value;
        if (!personId || personId === 'NULL') {
            warnEl.classList.add('hidden');
            return;
        }

        // Cek apakah person ini memang sudah menjabat di unit INI (bukan unit lain)
        // Jika ya, tidak perlu tampilkan warning
        const unitColumn = editSlugToColumn[slug];
        if (unitColumn && currentEditUnitPersonnel[unitColumn] === String(personId)) {
            warnEl.classList.add('hidden');
            return;
        }

        if (editOccupiedPeople[slug] && editOccupiedPeople[slug].includes(parseInt(personId))) {
            warnEl.innerHTML = `<i class="fas fa-info-circle mr-1"></i> Terpilih di unit lain. Jika disimpan, ia akan <strong>DIPINDAHKAN</strong> ke unit ini.`;
            warnEl.classList.remove('hidden');
        } else {
            warnEl.classList.add('hidden');
        }
    }

    ['e_leader', 'e_nutritionist', 'e_accountant'].forEach(id => {
        const slug = id === 'e_leader' ? 'kasppg' : (id === 'e_nutritionist' ? 'ag' : 'ak');
        document.addEventListener('change', (e) => {
            if (e.target && e.target.id === id) checkEditOccupancy(id, slug);
        });
    });

    // ================================================================
    // 3. LOGIKA PETA EDIT
    // ================================================================
    function initEditMapModal() {
        if (typeof L === 'undefined') return;
        const container = document.getElementById('map-edit');
        if (!container) return;

        if (editMapInstance) {
            editMapInstance.remove();
            editMapInstance = null;
            editMarkerInstance = null;
        }

        const latInput = document.getElementById('e_lat');
        const lngInput = document.getElementById('e_lng');
        const lat = parseFloat(latInput.value) || -8.1127;
        const lng = parseFloat(lngInput.value) || 115.0911;

        editMapInstance = L.map('map-edit').setView([lat, lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(editMapInstance);

        editMarkerInstance = L.marker([lat, lng], { draggable: true }).addTo(editMapInstance);

        editMapInstance.on('click', function(e) {
            editMarkerInstance.setLatLng(e.latlng);
            latInput.value = e.latlng.lat.toFixed(8);
            lngInput.value = e.latlng.lng.toFixed(8);
        });

        editMarkerInstance.on('dragend', function(ev) {
            const pos = ev.target.getLatLng();
            latInput.value = pos.lat.toFixed(8);
            lngInput.value = pos.lng.toFixed(8);
        });

        setTimeout(() => editMapInstance.invalidateSize(), 500);
    }

    // ================================================================
    // 4. LOGIKA CROPPER EDIT
    // ================================================================
    function initEditCropperLogic() {
        const photoInput  = document.getElementById('edit_photo');
        const previewImg  = document.getElementById('edit-cropped-preview');
        const placeholder = document.getElementById('edit-initial-placeholder');
        const imageToCrop = document.getElementById('image-to-crop');
        const cropperModal = document.getElementById('cropperModal');
        const applyBtn    = document.getElementById('apply-crop');
        const cancelBtn   = document.getElementById('cancel-crop');
        const closeXBtn   = document.getElementById('close-cropper-x');

        if (!photoInput) return;

        photoInput.onchange = (e) => {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    editOriginalImageData = event.target.result;
                    lastEditCropData = null;
                    openEditCropperModal(editOriginalImageData);
                };
                reader.readAsDataURL(file);
            }
        };

        previewImg.onclick = () => {
            if (editOriginalImageData) openEditCropperModal(editOriginalImageData);
        };

        function openEditCropperModal(src) {
            imageToCrop.src = src;
            cropperModal.style.display = 'flex';
            cropperModal.classList.remove('hidden');
            if (window.cropperInstance) window.cropperInstance.destroy();
            setTimeout(() => {
                window.cropperInstance = new Cropper(imageToCrop, {
                    aspectRatio: 4 / 3,
                    viewMode: 2,
                    autoCropArea: 1,
                    ready() {
                        if (lastEditCropData) window.cropperInstance.setData(lastEditCropData);
                    }
                });
            }, 200);
        }

        if (applyBtn) applyBtn.onclick = () => {
            if (!window.cropperInstance) return;
            lastEditCropData = window.cropperInstance.getData();
            const canvas = window.cropperInstance.getCroppedCanvas({ width: 800, height: 600 });
            canvas.toBlob((blob) => {
                const croppedFile = new File([blob], `edit_${Date.now()}.jpg`, { type: 'image/jpeg' });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(croppedFile);
                photoInput.files = dataTransfer.files;
                previewImg.src = URL.createObjectURL(blob);
                previewImg.classList.remove('hidden');
                placeholder.classList.add('hidden');
                if (typeof closeCropper === 'function') closeCropper();
                else { cropperModal.style.display = 'none'; cropperModal.classList.add('hidden'); }
            }, 'image/jpeg', 0.9);
        };

        if (cancelBtn) cancelBtn.onclick = () => { if (typeof closeCropper === 'function') closeCropper(); };
        if (closeXBtn) closeXBtn.onclick = () => { if (typeof closeCropper === 'function') closeCropper(); };
    }

    // ================================================================
    // 5. LOGIKA WILAYAH (API WILAYAH) — identik dg modal create
    // ================================================================
    async function syncEditWilayah(unitData) {
        if (typeof L === 'undefined' && false) return; // hanya guard Leaflet, ini untuk wilayah
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

        const unit = unitData || {};

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
                target.innerHTML = '<option value="">Gagal memuat</option>';
                return null;
            }
        }

        // Mulai dengan reset semua ke disabled kecuali provinsi
        setSelectState(sel.r, true);
        setSelectState(sel.d, true);
        setSelectState(sel.v, true);

        // Fetch berantai sesuai data tersimpan
        const pCode = await populate(sel.p, 'provinces', 'Provinsi', unit.province);
        if (pCode) {
            hid.p.value = unit.province;
            const rCode = await populate(sel.r, `regencies/${pCode}`, 'Kabupaten', unit.regency);
            if (rCode) {
                hid.r.value = unit.regency;
                const dCode = await populate(sel.d, `districts/${rCode}`, 'Kecamatan', unit.district);
                if (dCode) {
                    hid.d.value = unit.district;
                    await populate(sel.v, `villages/${dCode}`, 'Desa', unit.village);
                    if (unit.village) hid.v.value = unit.village;
                }
            }
        }

        // Event onchange (sama seperti create)
        sel.p.onchange = async function() {
            hid.p.value = this.options[this.selectedIndex].getAttribute('data-name') || '';
            sel.r.innerHTML = sel.d.innerHTML = sel.v.innerHTML = '<option value="">Pilih...</option>';
            [sel.r, sel.d, sel.v].forEach(s => setSelectState(s, true));
            if (this.value) await populate(sel.r, `regencies/${this.value}`, 'Kabupaten');
            clearEditFieldError('e_prov');
        };
        sel.r.onchange = async function() {
            hid.r.value = this.options[this.selectedIndex].getAttribute('data-name') || '';
            sel.d.innerHTML = sel.v.innerHTML = '<option value="">Pilih...</option>';
            [sel.d, sel.v].forEach(s => setSelectState(s, true));
            if (this.value) await populate(sel.d, `districts/${this.value}`, 'Kecamatan');
            clearEditFieldError('e_reg');
        };
        sel.d.onchange = async function() {
            hid.d.value = this.options[this.selectedIndex].getAttribute('data-name') || '';
            sel.v.innerHTML = '<option value="">Pilih...</option>';
            setSelectState(sel.v, true);
            if (this.value) await populate(sel.v, `villages/${this.value}`, 'Desa');
            clearEditFieldError('e_dist');
        };
        sel.v.onchange = function() {
            hid.v.value = this.options[this.selectedIndex].getAttribute('data-name') || '';
            clearEditFieldError('e_vill');
        };
    }

    // ================================================================
    // 6. LOGIKA SUBMIT EDIT (AJAX — sama seperti create)
    // ================================================================
    window.submitUpdateSppg = async function(formElement) {
        clearEditErrors();

        const btnSubmit = document.getElementById('btnUpdateSppg');
        let hasLocalError = false;

        // Validasi Sisi Client
        const fields = [
            { id: 'e_name',    msg: 'Nama SPPG wajib diisi' },
            { id: 'e_id',      msg: 'ID SPPG wajib diisi' },
            { id: 'e_code',    msg: 'Kode SPPG wajib diisi' },
            { id: 'e_status',  msg: 'Pilih status operasional' },
            { id: 'e_sk_leader', msg: 'SK Kepala SPPG wajib dipilih' },
            { id: 'e_prov',    msg: 'Provinsi wajib dipilih' },
            { id: 'e_reg',     msg: 'Kabupaten wajib dipilih' },
            { id: 'e_dist',    msg: 'Kecamatan wajib dipilih' },
            { id: 'e_vill',    msg: 'Desa/Kelurahan wajib dipilih' },
            { id: 'e_address', msg: 'Alamat wajib diisi' },
            { id: 'e_lat',     msg: 'Titik GPS belum ditentukan (Klik pada peta)' }
        ];

        fields.forEach(f => {
            const el = document.getElementById(f.id);
            if (!el || !el.value || el.value.trim() === "") {
                showEditFieldError(f.id, f.msg);
                hasLocalError = true;
            }
        });

        if (hasLocalError) {
            const first = document.querySelector('.input-error-edit');
            if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        if (btnSubmit) {
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = "Sedang Memproses...";
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
                window.location.href = result.redirect || "{{ route('admin.manage-sppg.index') }}";
            } else {
                if (btnSubmit) {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = "Simpan Perubahan";
                }

                if (result.errors) {
                    Object.keys(result.errors).forEach(key => {
                        let fieldId = 'e_' + key;
                        if (key === 'id_sppg_unit')          fieldId = 'e_id';
                        if (key === 'code_sppg_unit')        fieldId = 'e_code';
                        if (key === 'operational_date')      fieldId = 'e_op_date';
                        if (key === 'photo')                 fieldId = 'edit_photo';
                        if (key === 'id_sk_leader')          fieldId = 'e_sk_leader';
                        if (key === 'id_sk_nutritionist')    fieldId = 'e_sk_nutritionist';
                        if (key === 'id_sk_accountant')      fieldId = 'e_sk_accountant';
                        if (key === 'province_name')         fieldId = 'e_prov';
                        if (key === 'regency_name')          fieldId = 'e_reg';
                        if (key === 'district_name')         fieldId = 'e_dist';
                        if (key === 'village_name')          fieldId = 'e_vill';

                        result.errors[key].forEach(msg => {
                            showEditFieldError(fieldId, msg);
                        });
                    });

                    const firstServerErr = document.querySelector('#editUnitForm .input-error-edit');
                    if (firstServerErr) {
                        firstServerErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            }
        } catch (err) {
            if (btnSubmit) {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = "Simpan Perubahan";
            }
            console.error('Submit Error:', err);
            alert('Terjadi kesalahan jaringan/server: ' + err.message);
        }
    };

    // ================================================================
    // 7. HELPER FUNGSI ERROR
    // ================================================================
    function clearEditErrors() {
        document.querySelectorAll('#editUnitForm .input-error-edit').forEach(el => el.classList.remove('input-error-edit'));
        document.querySelectorAll('#editUnitForm .error-warning-edit').forEach(el => {
            el.classList.add('hidden');
            el.innerText = '';
        });
    }

    function clearEditFieldError(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('input-error-edit');
        const errorEl = document.getElementById('error-edit-' + id);
        if (errorEl) {
            errorEl.classList.add('hidden');
            errorEl.innerText = '';
        }
    }

    function showEditFieldError(id, msg) {
        const el = document.getElementById(id);
        if (!el) return;

        el.classList.add('input-error-edit');

        const errorId = 'error-edit-' + id;
        let errorEl = document.getElementById(errorId);
        if (!errorEl) {
            errorEl = document.createElement('span');
            errorEl.id = errorId;
            errorEl.className = 'error-warning-edit';
            if (id === 'edit_photo') {
                document.getElementById('error-edit_photo_container').appendChild(errorEl);
            } else {
                el.parentNode.appendChild(errorEl);
            }
        }
        errorEl.innerText = '* ' + msg;
        errorEl.classList.remove('hidden');
    }

</script>