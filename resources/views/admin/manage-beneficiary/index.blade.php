<x-app-layout title="Manajemen PM">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        #map-create,
        #map-edit {
            height: 350px;
            width: 100%;
            border-radius: 0.75rem;
            margin-top: 0.5rem;
            z-index: 1;
            background: #f8fafc;
        }

        .is-invalid {
            border: 2px solid #ef4444 !important;
            --tw-ring-color: #ef4444 !important;
        }

        .error-warning {
            color: #ef4444;
            font-size: 10px;
            font-weight: 700;
            font-style: italic;
            margin-top: 4px;
            display: block;
        }
    </style>

    <div class="py-8 w-full px-4 sm:px-6 lg:px-8 relative"
        x-data="{ 
            showCreateModal: false, 
            showEditModal: false, 
            selectedBeneficiary: {} 
         }">

        <div class="max-w-full mx-auto space-y-6">
            {{-- 1. HEADER SECTION --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 uppercase leading-tight">Manajemen PM</h2>
                        <p class="text-sm text-slate-400 font-medium mt-1">Manajemen data PM (Penerima Manfaat) terdaftar</p>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-4 py-2 text-[10px] font-bold rounded bg-white text-slate-600 uppercase border border-slate-200 tracking-widest shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-indigo-500 mr-2 animate-pulse"></span>
                            {{ $beneficiaries->total() }} PM Terdaftar
                        </span>
                    </div>
                </div>
            </div>

            {{-- 2. STATISTICS (Optional, can be added later) --}}
            {{-- @include('admin.manage-beneficiary.partials.statistics-cards') --}}

            {{-- 3. TABLE SECTION --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                    <h3 class="font-bold text-slate-700 uppercase tracking-wider text-[14px]">Daftar Seluruh PM</h3>
                    <div class="flex flex-col md:flex-row items-stretch md:items-center gap-4">
                        <div class="flex items-center gap-2 overflow-x-auto pb-1 md:pb-0 hide-scrollbar shrink-0">
                            {{-- Tombol Export --}}
                            <button type="button" onclick="openExportModal()" class="flex items-center justify-center p-2.5 md:px-4 text-[11px] font-bold uppercase tracking-wider text-emerald-600 bg-white border border-emerald-200 rounded-lg hover:bg-emerald-600 hover:text-white transition-all cursor-pointer shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                    <path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                </svg>
                                <span class="hidden sm:inline ml-2 text-nowrap">Export</span>
                            </button>

                            {{-- Tombol Import --}}
                            <button type="button" onclick="openImportModal()" class="flex items-center justify-center p-2.5 md:px-4 text-[11px] font-bold uppercase tracking-wider text-amber-600 bg-white border border-amber-200 rounded-lg hover:bg-amber-600 hover:text-white transition-all cursor-pointer shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                    <path fill-rule="evenodd" d="M11.47 2.47a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 1 1-1.06 1.06l-3.22-3.22V16.5a.75.75 0 0 1-1.5 0V4.81L8.03 8.03a.75.75 0 0 1-1.06-1.06l4.5-4.5ZM3 15.75a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                </svg>
                                <span class="hidden sm:inline ml-2 text-nowrap">Import</span>
                            </button>

                            {{-- Tombol Tambah --}}
                            <button @click="showCreateModal = true" class="flex items-center justify-center p-2.5 md:px-4 text-[11px] font-bold uppercase tracking-wider text-indigo-600 bg-white border border-indigo-200 rounded-lg hover:bg-indigo-600 hover:text-white transition-all cursor-pointer shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="hidden sm:inline ml-2 text-nowrap">Tambah</span>
                            </button>
                        </div>

                        <div class="flex items-center gap-2">
                            {{-- Search Input (Live Search) --}}
                            <div class="relative grow md:w-72 lg:w-80 text-slate-800">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </span>
                                <input type="text"
                                    id="beneficiary-search"
                                    class="live-search-input text-xs border-slate-200 rounded-lg pl-9 pr-3 py-2.5 w-full focus:ring-2 focus:ring-indigo-500 outline-none transition-all bg-white shadow-sm"
                                    placeholder="Cari kode atau nama..." value="{{ request('search') }}"
                                    autocomplete="off">
                            </div>

                            <button type="button" onclick="resetFilters()" class="flex items-center justify-center p-2.5 text-rose-500 bg-white border border-rose-100 rounded-lg hover:bg-rose-50 transition-all cursor-pointer shadow-sm shrink-0" title="Reset Filter">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- FILTER SECTION --}}
                <div class="p-4 bg-slate-50 border-b border-slate-100 grid grid-cols-3 lg:grid-cols-9 gap-3">
                    <div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1 px-1">Status</label>
                        <select id="filter-status" class="filter-input w-full text-[11px] border-slate-200 rounded-lg py-1.5 focus:ring-1 focus:ring-indigo-500 bg-white">
                            <option value="">Semua Status</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1 px-1">Unit SPPG</label>
                        <select id="filter-sppg-unit" class="filter-input w-full text-[11px] border-slate-200 rounded-lg py-1.5 focus:ring-1 focus:ring-indigo-500 bg-white">
                            <option value="">Semua Unit</option>
                            <option value="unassigned" {{ request('sppg_unit') === 'unassigned' ? 'selected' : '' }}>Belum Diberikan</option>
                            @foreach($sppgUnits as $unit)
                            <option value="{{ $unit->id_sppg_unit }}" {{ request('sppg_unit') == $unit->id_sppg_unit ? 'selected' : '' }}>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1 px-1">Kelompok</label>
                        <select id="filter-group-type" class="filter-input w-full text-[11px] border-slate-200 rounded-lg py-1.5 focus:ring-1 focus:ring-indigo-500 bg-white">
                            <option value="">Semua</option>
                            <option value="Sekolah" {{ request('group_type') === 'Sekolah' ? 'selected' : '' }}>Sekolah</option>
                            <option value="Posyandu" {{ request('group_type') === 'Posyandu' ? 'selected' : '' }}>Posyandu</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1 px-1">Kategori</label>
                        <select id="filter-category" class="filter-input w-full text-[11px] border-slate-200 rounded-lg py-1.5 focus:ring-1 focus:ring-indigo-500 bg-white">
                            <option value="">Semua</option>
                            @foreach($filterData['categories'] ?? [] as $cat)
                            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1 px-1">Kepemilikan</label>
                        <select id="filter-ownership-type" class="filter-input w-full text-[11px] border-slate-200 rounded-lg py-1.5 focus:ring-1 focus:ring-indigo-500 bg-white">
                            <option value="">Semua</option>
                            <option value="Negeri" {{ request('ownership_type') === 'Negeri' ? 'selected' : '' }}>Negeri</option>
                            <option value="Swasta" {{ request('ownership_type') === 'Swasta' ? 'selected' : '' }}>Swasta</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1 px-1">Provinsi</label>
                        <select id="filter-province" class="filter-input w-full text-[11px] border-slate-200 rounded-lg py-1.5 focus:ring-1 focus:ring-indigo-500 bg-white">
                            <option value="">Semua Provinsi</option>
                            @foreach($filterData['provinces'] as $p)
                            <option value="{{ $p }}" {{ request('province') === $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1 px-1">Kabupaten/Kota</label>
                        <select id="filter-regency" {{ empty($filterData['regencies']) ? 'disabled' : '' }} class="filter-input w-full text-[11px] border-slate-200 rounded-lg py-1.5 focus:ring-1 focus:ring-indigo-500 bg-white disabled:bg-slate-100 disabled:text-slate-400">
                            <option value="">Semua</option>
                            @foreach($filterData['regencies'] as $r)
                            <option value="{{ $r }}" {{ request('regency') === $r ? 'selected' : '' }}>{{ $r }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1 px-1">Kecamatan</label>
                        <select id="filter-district" {{ empty($filterData['districts']) ? 'disabled' : '' }} class="filter-input w-full text-[11px] border-slate-200 rounded-lg py-1.5 focus:ring-1 focus:ring-indigo-500 bg-white disabled:bg-slate-100 disabled:text-slate-400">
                            <option value="">Semua</option>
                            @foreach($filterData['districts'] as $d)
                            <option value="{{ $d }}" {{ request('district') === $d ? 'selected' : '' }}>{{ $d }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1 px-1">Desa/Kelurahan</label>
                        <select id="filter-village" {{ empty($filterData['villages']) ? 'disabled' : '' }} class="filter-input w-full text-[11px] border-slate-200 rounded-lg py-1.5 focus:ring-1 focus:ring-indigo-500 bg-white disabled:bg-slate-100 disabled:text-slate-400">
                            <option value="">Semua</option>
                            @foreach($filterData['villages'] as $v)
                            <option value="{{ $v }}" {{ request('village') === $v ? 'selected' : '' }}>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="beneficiary-table-container">
                    <div class="overflow-x-auto scrollbar-thin">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                    <th class="px-6 py-4 whitespace-nowrap">NAMA PM</th>
                                    <th class="px-6 py-4 whitespace-nowrap">KELOMPOK</th>
                                    <th class="px-6 py-4 whitespace-nowrap">KATEGORI</th>
                                    <th class="px-6 py-4 whitespace-nowrap">KEPEMILIKAN</th>
                                    <th class="px-6 py-4 text-center whitespace-nowrap">SPPG UNIT</th>
                                    <th class="px-6 py-4 whitespace-nowrap">ALAMAT</th>
                                    <th class="px-6 py-4 text-center whitespace-nowrap">RINCIAN PORSI</th>
                                    <th class="px-6 py-4 text-center whitespace-nowrap">STATUS</th>
                                    <th class="px-6 py-4 text-center whitespace-nowrap">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($beneficiaries as $beneficiary)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-700 capitalize text-sm">{{ $beneficiary->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $beneficiary->code ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-slate-500 text-xs block capitalize font-medium">{{ $beneficiary->group_type }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-slate-500 text-xs block capitalize font-medium">{{ $beneficiary->category }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-slate-500 text-xs block capitalize font-medium">{{ $beneficiary->ownership_type ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-slate-500 text-xs block capitalize font-medium">{{ $beneficiary->sppgUnit->name ?? 'Belum Diberikan' }}</span>
                                    </td>
                                    <td class="px-6 py-4 min-w-[200px] max-w-sm whitespace-normal break-words">
                                        <div class="text-[12px] text-slate-600 font-medium leading-relaxed">
                                            {{ $beneficiary->address }}<br>
                                            <span class="text-slate-400 capitalize">{{ $beneficiary->village }}, {{ $beneficiary->district }}, {{ $beneficiary->regency }}, {{ $beneficiary->province }}, {{ $beneficiary->postal_code }}</span>

                                            @if($beneficiary->latitude_gps && $beneficiary->longitude_gps)
                                            <div class="mt-1.5 flex items-center gap-1 text-slate-400">
                                                <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                <a href="https://www.google.com/maps/search/?api=1&query={{ $beneficiary->latitude_gps }},{{ $beneficiary->longitude_gps }}" target="_blank" class="text-[10px] hover:text-indigo-600 transition-colors uppercase font-medium mt-px" title="Lihat di Google Maps">
                                                    {{ $beneficiary->latitude_gps }}, {{ $beneficiary->longitude_gps }}
                                                </a>
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1.5 min-w-[150px]">
                                            <div class="flex items-center justify-between text-[10px] bg-slate-50 px-2 py-0.5 rounded border border-slate-100">
                                                <span class="font-bold text-slate-500 uppercase">Kecil (L/P)</span>
                                                <span class="text-slate-700 font-bold bg-white px-1.5 rounded shadow-sm text-[11px]">{{ $beneficiary->small_portion_male }} / {{ $beneficiary->small_portion_female }}</span>
                                            </div>
                                            <div class="flex items-center justify-between text-[10px] bg-slate-50 px-2 py-0.5 rounded border border-slate-100">
                                                <span class="font-bold text-slate-500 uppercase">Besar (L/P)</span>
                                                <span class="text-slate-700 font-bold bg-white px-1.5 rounded shadow-sm text-[11px]">{{ $beneficiary->large_portion_male }} / {{ $beneficiary->large_portion_female }}</span>
                                            </div>
                                            <div class="flex items-center justify-between text-[10px] bg-slate-50 px-2 py-0.5 rounded border border-slate-100">
                                                <span class="font-bold text-slate-500 uppercase whitespace-nowrap">Guru</span>
                                                <span class="text-slate-700 font-bold bg-white px-1.5 rounded shadow-sm text-[11px] whitespace-nowrap">{{ $beneficiary->teacher_portion }}</span>
                                            </div>
                                            <div class="flex items-center justify-between text-[10px] bg-slate-50 px-2 py-0.5 rounded border border-slate-100">
                                                <span class="font-bold text-slate-500 uppercase whitespace-nowrap">Tenaga Kependidikan</span>
                                                <span class="text-slate-700 font-bold bg-white px-1.5 rounded shadow-sm text-[11px] whitespace-nowrap">{{ $beneficiary->staff_portion }}</span>
                                            </div>
                                            <div class="flex items-center justify-between text-[10px] bg-slate-50 px-2 py-0.5 rounded border border-slate-100">
                                                <span class="font-bold text-slate-500 uppercase whitespace-nowrap">Kader</span>
                                                <span class="text-slate-700 font-bold bg-white px-1.5 rounded shadow-sm text-[11px] whitespace-nowrap">{{ $beneficiary->cadre_portion }}</span>
                                            </div>
                                            <div class="flex items-center justify-between text-[10px] bg-indigo-50 px-2 py-1 rounded border border-indigo-100 mt-1">
                                                <span class="font-bold text-indigo-700 uppercase whitespace-nowrap">Total Porsi</span>
                                                <span class="text-indigo-800 font-bold bg-white px-1.5 rounded shadow-sm text-[11px] whitespace-nowrap">{{ array_sum([$beneficiary->small_portion_male, $beneficiary->small_portion_female, $beneficiary->large_portion_male, $beneficiary->large_portion_female, $beneficiary->teacher_portion, $beneficiary->staff_portion, $beneficiary->cadre_portion]) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-xs font-medium px-1.5 py-0.5 rounded border capitalize {{ $beneficiary->is_active ? 'bg-emerald-100 text-emerald-600 border-emerald-200' : 'bg-rose-100 text-rose-600 border-rose-200' }}">
                                            {{ $beneficiary->is_active ? 'Aktif' : 'Non-Aktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center items-center gap-1">
                                            <button @click="selectedBeneficiary = {{ json_encode($beneficiary) }}; showEditModal = true; $dispatch('init-edit-beneficiary', selectedBeneficiary)"
                                                title="Edit" class="p-2 text-slate-400 hover:text-indigo-600 cursor-pointer transition-colors hover:bg-indigo-50 rounded-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                            <button onclick="confirmDeleteBeneficiary('{{ $beneficiary->id_beneficiary }}', '{{ addslashes($beneficiary->name) }}')"
                                                title="Hapus" class="p-2 text-rose-600 hover:bg-rose-50 cursor-pointer rounded-lg opacity-80 hover:opacity-100 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="p-3 bg-slate-50 rounded-full mb-3">
                                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                            </div>
                                            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">
                                                Belum ada data PM terdaftar
                                            </p>
                                            <p class="text-[10px] text-slate-400 mt-1 italic">Coba gunakan kata kunci yang berbeda</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($beneficiaries->hasPages() || request('per_page') > 5)
                    <div class="px-6 py-4 bg-white border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 text-xs
        [&_nav]:flex [&_nav]:justify-between [&_nav]:items-center [&_nav]:w-full md:[&_nav]:w-auto
        [&_a]:bg-white [&_a]:text-slate-600 [&_a]:border-slate-200 [&_a]:rounded-lg [&_a]:hover:bg-slate-50
        [&_span]:bg-white [&_span]:text-slate-600 [&_span]:border-slate-200 [&_span]:rounded-lg
        [&_.bg-gray-800]:bg-emerald-600 [&_.bg-gray-800]:text-white [&_.bg-gray-800]:border-emerald-600
        [&_.dark\:bg-gray-800]:bg-white [&_.dark\:text-gray-400]:text-slate-600">

                        {{-- DROPDOWN PER PAGE --}}
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-slate-600">Tampilkan</span>
                            <select id="beneficiary-per-page" class="per-page-select border-slate-200 rounded-lg text-sm py-1.5 pl-3 pr-8 focus:ring-emerald-500 text-slate-600 font-medium cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors">
                                <option value="5" {{ (request('per_page') == '5' || !request('per_page')) ? 'selected' : '' }}>5</option>
                                <option value="15" {{ request('per_page') == '15' ? 'selected' : '' }}>15</option>
                                <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                            </select>
                            <span class="text-sm text-slate-600 hidden sm:inline">Baris</span>
                        </div>

                        {{-- LARAVEL PAGINATION --}}
                        <div class="w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
                            {{ $beneficiaries->links() }}
                        </div>

                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- MODALS --}}
        @include('admin.manage-beneficiary.partials.modal-create')
        @include('admin.manage-beneficiary.partials.modal-edit')
        @include('admin.manage-beneficiary.partials.modal-delete')
        @include('admin.manage-beneficiary.partials.modal-import')
        @include('admin.manage-beneficiary.partials.modal-export')
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        window.confirmDeleteBeneficiary = function(id, name) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            const info = document.getElementById('delete_modal_info');
            form.action = `/admin/manage-beneficiary/${id}`;
            info.innerHTML = `Data Penerima Manfaat <b>${name}</b> akan dihapus secara permanen.`;
            modal.classList.remove('hidden');
        }
        window.closeDeleteModal = function() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Live search and pagination logic (AJAX)
        let searchTimer;

        function getCurrentUrlModifiers(inputEl = null) {
            let currentUrl = new URL(window.location.href);

            // 1. Search keyword
            const activeSearch = document.getElementById('beneficiary-search');
            if (activeSearch) {
                if (activeSearch.value) currentUrl.searchParams.set('search', activeSearch.value);
                else currentUrl.searchParams.delete('search');
            }

            // 2. Per-page
            const activePerPage = document.getElementById('beneficiary-per-page');
            if (activePerPage) currentUrl.searchParams.set('per_page', activePerPage.value);

            // 3. Filters
            const filters = {
                'status': 'filter-status',
                'sppg_unit': 'filter-sppg-unit',
                'group_type': 'filter-group-type',
                'category': 'filter-category',
                'ownership_type': 'filter-ownership-type',
                'province': 'filter-province',
                'regency': 'filter-regency',
                'district': 'filter-district',
                'village': 'filter-village'
            };

            Object.keys(filters).forEach(key => {
                const el = document.getElementById(filters[key]);
                if (el && el.value !== '') {
                    currentUrl.searchParams.set(key, el.value);
                } else {
                    currentUrl.searchParams.delete(key);
                }
            });

            // Back to page 1 if triggered by input/filter change
            if (inputEl) currentUrl.searchParams.delete('page');

            return currentUrl.toString();
        }

        function refreshTable(url, focusId = null) {
            const container = document.getElementById('beneficiary-table-container');
            if (!container) return;

            fetch(url, {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                .then(response => response.text())
                .then(html => {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, 'text/html');

                    // Update Table Content
                    const newTable = doc.getElementById('beneficiary-table-container');
                    if (newTable) container.innerHTML = newTable.innerHTML;

                    // Update Filter Dropdowns (Cascading Logic)
                    const filterArea = doc.querySelector('.p-4.bg-slate-50.border-b');
                    if (filterArea) {
                        const currentFilters = [
                            'filter-group-type', 'filter-category', 'filter-ownership-type',
                            'filter-province', 'filter-regency', 'filter-district', 'filter-village'
                        ];
                        currentFilters.forEach(id => {
                            const oldEl = document.getElementById(id);
                            const newEl = doc.getElementById(id);
                            if (oldEl && newEl) {
                                const currentVal = oldEl.value;
                                oldEl.innerHTML = newEl.innerHTML;
                                oldEl.disabled = newEl.disabled;
                                // Attempt to restore value, or reset if no longer present
                                oldEl.value = Array.from(oldEl.options).some(opt => opt.value === currentVal) ? currentVal : '';
                            }
                        });
                    }

                    // Sync URL
                    window.history.pushState({}, '', url);

                    // Restore focus
                    if (focusId) {
                        requestAnimationFrame(() => {
                            const activeInput = document.getElementById(focusId);
                            if (activeInput) {
                                activeInput.focus();
                                const val = activeInput.value;
                                activeInput.value = '';
                                activeInput.value = val;
                            }
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // 1. Search Input logic
        const searchInput = document.getElementById('beneficiary-search');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    refreshTable(getCurrentUrlModifiers(this), this.id);
                }, 600);
            });

            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(searchTimer);
                    refreshTable(getCurrentUrlModifiers(this), this.id);
                }
            });
        }

        // 2. Filter logic
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('filter-input') || e.target.id === 'beneficiary-per-page') {
                refreshTable(getCurrentUrlModifiers(e.target), 'beneficiary-search');
            }
        });

        // 3. Reset Filter
        window.resetFilters = function() {
            const url = new URL(window.location.origin + window.location.pathname);
            window.location.href = url.toString();
        }

        // 4. Pagination Links AJAX
        document.addEventListener('click', function(e) {
            let anchor = e.target.closest('#beneficiary-table-container nav a');
            if (anchor && anchor.getAttribute('href')) {
                let url = new URL(anchor.getAttribute('href'));

                // Ensure per_page and filters are preserved
                const modUrl = new URL(getCurrentUrlModifiers());
                modUrl.searchParams.set('page', url.searchParams.get('page'));

                if (!url.toString().startsWith('javascript')) {
                    e.preventDefault();
                    refreshTable(modUrl.toString(), 'beneficiary-search');
                }
            }
        });

        async function validateBeneficiaryCode(input, excludeId = null) {
            const code = input.value.trim();
            const isEdit = excludeId !== null && excludeId !== '';
            const errorSpan = isEdit ? document.getElementById('edit_code_error') : document.getElementById('create_code_error');
            const submitBtn = isEdit ? document.getElementById('btn_submit_edit') : document.getElementById('btn_submit_create');

            if (!code) {
                if (errorSpan) errorSpan.classList.add('hidden');
                if (submitBtn) submitBtn.disabled = false;
                return;
            }

            try {
                let url = `/admin/manage-beneficiary/check-availability?code=${encodeURIComponent(code)}`;
                if (isEdit) {
                    url += `&id_beneficiary=${encodeURIComponent(excludeId)}`;
                }

                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const data = await response.json();

                if (data.code_duplicate) {
                    if (errorSpan) errorSpan.classList.remove('hidden');
                    if (submitBtn) submitBtn.disabled = true;
                } else {
                    if (errorSpan) errorSpan.classList.add('hidden');
                    if (submitBtn) submitBtn.disabled = false;
                }
            } catch (error) {
                console.error('Error checking code availability:', error);
            }
        }
    </script>
</x-app-layout>