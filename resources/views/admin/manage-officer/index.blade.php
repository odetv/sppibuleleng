<x-app-layout title="Manajemen Petugas">
    <script src="https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js"></script>
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

    <div class="py-8 w-full px-4 sm:px-6 lg:px-8"
        x-data="{ 
            showCreateModal: false, 
            showEditModal: false, 
            showSyncModal: false,
            selectedOfficer: { person: {} },
            async openEdit(officer) {
                this.selectedOfficer = officer;
                await this.$nextTick(); // Tunggu Alpine update DOM
                window.dispatchEvent(new CustomEvent('init-edit-officer-location'));
                this.showEditModal = true;
            }
         }">

        <div class="max-w-full mx-auto space-y-6">
            {{-- 1. HEADER SECTION --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 uppercase leading-tight">Manajemen Petugas</h2>
                        <p class="text-sm text-slate-400 font-medium mt-1">Manajemen data staf inti dan relawan unit SPPG</p>
                    </div>
                </div>
            </div>

            {{-- 2. TABLE SECTION --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col xl:flex-row xl:justify-between xl:items-center gap-6">
                    <div class="flex items-center gap-3">
                        <h3 class="font-bold text-slate-700 uppercase tracking-wider text-[14px]">Daftar Seluruh Petugas</h3>
                    </div>

                    <div class="flex flex-col md:flex-row items-stretch md:items-center gap-4">
                        {{-- Group 1: Action Buttons --}}
                        <div class="flex items-center gap-2 overflow-x-auto pb-1 md:pb-0 hide-scrollbar">
                            <button type="button" @click="showSyncModal = true" class="flex items-center justify-center p-2.5 md:px-4 text-[11px] font-bold uppercase tracking-wider text-emerald-600 bg-white border border-emerald-200 rounded-lg hover:bg-emerald-600 hover:text-white transition-all cursor-pointer shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                                </svg>
                                <span class="hidden lg:inline ml-2 text-nowrap">Sinkronisasi</span>
                            </button>

                            <button type="button" onclick="openExportModal()" class="flex items-center justify-center p-2.5 md:px-4 text-[11px] font-bold uppercase tracking-wider text-emerald-600 bg-white border border-emerald-200 rounded-lg hover:bg-emerald-600 hover:text-white transition-all cursor-pointer shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                    <path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                </svg>
                                <span class="hidden lg:inline ml-2 text-nowrap">Export</span>
                            </button>

                            <button type="button" onclick="openImportModal()" class="flex items-center justify-center p-2.5 md:px-4 text-[11px] font-bold uppercase tracking-wider text-amber-600 bg-white border border-amber-200 rounded-lg hover:bg-amber-600 hover:text-white transition-all cursor-pointer shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                    <path fill-rule="evenodd" d="M11.47 2.47a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 1 1-1.06 1.06l-3.22-3.22V16.5a.75.75 0 0 1-1.5 0V4.81L8.03 8.03a.75.75 0 0 1-1.06-1.06l4.5-4.5ZM3 15.75a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                </svg>
                                <span class="hidden lg:inline ml-2 text-nowrap">Import</span>
                            </button>

                            <button @click="showCreateModal = true" class="flex items-center justify-center p-2.5 md:px-4 text-[11px] font-bold uppercase tracking-wider text-indigo-600 bg-white border border-indigo-200 rounded-lg hover:bg-indigo-600 hover:text-white transition-all cursor-pointer shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="hidden lg:inline ml-2 text-nowrap">Tambah</span>
                            </button>
                        </div>

                        {{-- Group 2: Search & Reset --}}
                        <div class="flex items-center gap-2">
                            <div class="relative grow md:w-72 lg:w-80 text-slate-800">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </span>
                                <input type="text"
                                    id="officer-search"
                                    class="live-search-input text-xs border-slate-200 rounded-lg pl-9 pr-3 py-2.5 w-full focus:ring-2 focus:ring-indigo-500 outline-none transition-all bg-white shadow-sm"
                                    placeholder="Cari NIK atau nama petugas..." value="{{ request('search') }}"
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
                <div class="p-4 bg-slate-50 border-b border-slate-100 grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1 px-1">Status</label>
                        <select id="filter-status" class="filter-input w-full text-[11px] border-slate-200 rounded-lg py-1.5 focus:ring-1 focus:ring-indigo-500 bg-white">
                            <option value="">Semua Status</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1 px-1">Hak Akses Sistem</label>
                        <select id="filter-role" class="filter-input w-full text-[11px] border-slate-200 rounded-lg py-1.5 focus:ring-1 focus:ring-indigo-500 bg-white">
                            <option value="">Semua Akses</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id_ref_role }}" {{ request('id_ref_role') == $role->id_ref_role ? 'selected' : '' }}>{{ $role->name_role }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1 px-1">Jabatan</label>
                        <select id="filter-position" class="filter-input w-full text-[11px] border-slate-200 rounded-lg py-1.5 focus:ring-1 focus:ring-indigo-500 bg-white">
                            <option value="">Semua Jabatan</option>
                            @foreach($positions as $pos)
                            <option value="{{ $pos->id_ref_position }}" {{ request('id_ref_position') == $pos->id_ref_position ? 'selected' : '' }}>{{ $pos->name_position }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1 px-1">Unit SPPG</label>
                        <select id="filter-sppg-unit" class="filter-input w-full text-[11px] border-slate-200 rounded-lg py-1.5 focus:ring-1 focus:ring-indigo-500 bg-white">
                            <option value="">Semua Unit</option>
                            @foreach($sppgUnits as $unit)
                            <option value="{{ $unit->id_sppg_unit }}" {{ request('id_sppg_unit') == $unit->id_sppg_unit ? 'selected' : '' }}>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="officers-table-container">
                    <div class="overflow-x-auto scrollbar-thin">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                    <th class="px-6 py-4 whitespace-nowrap">NAMA PETUGAS / NIK</th>
                                    <th class="px-6 py-4 text-center whitespace-nowrap text-nowrap">TELEPON</th>
                                    <th class="px-6 py-4 text-center whitespace-nowrap text-nowrap">JABATAN / UNIT</th>
                                    <th class="px-6 py-4 text-center whitespace-nowrap">HONOR HARIAN</th>
                                    <th class="px-6 py-4 text-center whitespace-nowrap">STATUS</th>
                                    <th class="px-6 py-4 text-center whitespace-nowrap">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($officers as $officer)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            @if($officer->person?->photo && file_exists(public_path('storage/' . $officer->person->photo)))
                                            <img src="{{ asset('storage/' . $officer->person->photo) }}" alt="" class="w-10 h-10 rounded-full object-cover border border-slate-100 shadow-sm">
                                            @else
                                            <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center shadow-sm border border-indigo-200 text-white text-[15px] uppercase tracking-tighter">
                                                {{ strtoupper(substr($officer->person?->name ?? '?', 0, 1)) }}
                                            </div>
                                            @endif
                                            <div class="flex flex-col text-[14px] gap-0.5">
                                                <span class="font-bold text-slate-700 leading-tight">{{ $officer->person?->name ?? '-' }}</span>
                                                <span class="text-xs text-slate-500 font-sans font-medium">
                                                    <span class="">{{ $officer->person?->nik ?? '-' }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-xs font-medium text-slate-500 text-center">
                                        {{ $officer->person?->user?->phone ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="text-xs font-medium text-slate-500">
                                            {{ $officer->position?->name_position ?? '-' }}
                                        </div>
                                        <div class="text-xs font-medium text-slate-500">
                                            {{ $officer->sppgUnit?->name ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-xs font-medium text-slate-500">Rp {{ number_format($officer->daily_honor ?? 0, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($officer->is_active)
                                        <span class="inline-flex items-center px-2 py-1 text-[10px] font-bold rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200">
                                            Aktif
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-2 py-1 text-[10px] font-bold rounded-lg bg-rose-50 text-rose-600 border border-rose-200">
                                            Non-Aktif
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            <button type="button"
                                                data-officer='{!! json_encode($officer) !!}'
                                                @click='openEdit(JSON.parse($el.dataset.officer))'
                                                title="Edit" class="p-2 text-slate-400 hover:text-indigo-600 cursor-pointer transition-colors hover:bg-indigo-50 rounded-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                            <button type="button" title="Hapus" onclick="confirmDeleteOfficer({{ $officer->id_sppg_officer }}, '{{ addslashes($officer->person->name ?? '') }}')"
                                                class="p-2 text-rose-600 hover:bg-rose-50 cursor-pointer rounded-lg opacity-80 hover:opacity-100 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-500 text-sm font-medium">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="p-3 bg-slate-50 rounded-full mb-3">
                                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                                </svg>
                                            </div>
                                            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">
                                                Belum ada data Petugas terdaftar
                                            </p>
                                            <p class="text-[10px] text-slate-400 mt-1 italic">Coba gunakan kata kunci yang berbeda</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($officers->hasPages() || request('per_page') > 5)
                    <div class="px-6 py-4 bg-white border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 text-xs
        [&_nav]:flex [&_nav]:justify-between [&_nav]:items-center [&_nav]:w-full md:[&_nav]:w-auto
        [&_a]:bg-white [&_a]:text-slate-600 [&_a]:border-slate-200 [&_a]:rounded-lg [&_a]:hover:bg-slate-50
        [&_span]:bg-white [&_span]:text-slate-600 [&_span]:border-slate-200 [&_span]:rounded-lg
        [&_.bg-gray-800]:bg-indigo-600 [&_.bg-gray-800]:text-white [&_.bg-gray-800]:border-indigo-600
        [&_.dark\:bg-gray-800]:bg-white [&_.dark\:text-gray-400]:text-slate-600">

                        {{-- DROPDOWN PER PAGE --}}
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-slate-600">Tampilkan</span>
                            <select id="officer-per-page" class="per-page-select border-slate-200 rounded-lg text-sm py-1.5 pl-3 pr-8 focus:ring-indigo-500 text-slate-600 font-medium cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors">
                                <option value="5" {{ request('per_page') == '5' ? 'selected' : '' }}>5</option>
                                <option value="15" {{ request('per_page') == '15' ? 'selected' : '' }}>15</option>
                                <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                            </select>
                            <span class="text-sm text-slate-600 hidden sm:inline">Baris</span>
                        </div>

                        {{-- LARAVEL PAGINATION --}}
                        <div class="w-full md:w-auto overflow-x-auto pb-2 md:pb-0 scrollbar-hide">
                            {{ $officers->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        @include('admin.manage-officer.partials.modal-add')
        @include('admin.manage-officer.partials.modal-edit')
        @include('admin.manage-officer.partials.modal-delete')
        @include('admin.manage-officer.partials.modal-export')
        @include('admin.manage-officer.partials.modal-import')
        @include('admin.manage-officer.partials.modal-sync')

    </div>

    <script>
        const occupiedPositions = @json($occupiedPositions);

        document.addEventListener('alpine:init', () => {
            // Let Alpine initialize state
        });

        // Modal Actions
        window.confirmDeleteOfficer = function(officerId, officerName) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            const info = document.getElementById('delete_modal_info');

            if (form) form.action = `/admin/manage-officer/${officerId}`;
            if (info) info.innerHTML = `Data penugasan profil <b>${officerName}</b> akan dihapus dari unit SPPG terkait.`;

            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        window.closeDeleteModal = function() {
            const modal = document.getElementById('deleteModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        // ==========================
        // FITUR LIVE SEARCH & PAGINATION AJAX
        // ==========================
        let searchTimer;

        function getCurrentUrlModifiers(inputEl = null) {
            let currentUrl = new URL(window.location.href);

            // 1. Search keyword
            const activeSearch = document.getElementById('officer-search');
            if (activeSearch) {
                if (activeSearch.value) currentUrl.searchParams.set('search', activeSearch.value);
                else currentUrl.searchParams.delete('search');
            }

            // 2. Per-page
            const activePerPage = document.getElementById('officer-per-page');
            if (activePerPage) currentUrl.searchParams.set('per_page', activePerPage.value);

            // 3. Filters
            const filters = {
                'id_sppg_unit': 'filter-sppg-unit',
                'id_ref_position': 'filter-position',
                'is_active': 'filter-status',
                'id_ref_role': 'filter-role'
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
            const container = document.getElementById('officers-table-container');
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
                    const newTable = doc.getElementById('officers-table-container');
                    if (newTable) container.innerHTML = newTable.innerHTML;

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

        // 1. Reset Filters
        window.resetFilters = function() {
            const url = new URL(window.location.origin + window.location.pathname);
            window.location.href = url.toString();
        }

        // 2. Event Listener untuk Mengetik (Enter Key)
        document.addEventListener('keydown', function(e) {
            if (e.target.classList.contains('live-search-input')) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(searchTimer);
                    refreshTable(getCurrentUrlModifiers(e.target), e.target.id);
                }
            }
        });

        // 3. Event Listener untuk Timer Otomatis
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('live-search-input')) {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    refreshTable(getCurrentUrlModifiers(e.target), e.target.id);
                }, 600);
            }
        });

        // 4. Listener untuk Filter Inputs
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('filter-input') || e.target.classList.contains('per-page-select')) {
                refreshTable(getCurrentUrlModifiers(e.target), 'officer-search');
            }
        });

        // 5. Listener Pagination AJAX
        document.addEventListener('click', function(e) {
            let anchor = e.target.closest('#officers-table-container nav a');
            if (anchor && anchor.getAttribute('href')) {
                e.preventDefault();
                let url = new URL(anchor.getAttribute('href'));

                // Ensure per_page and filters are preserved
                const modUrl = new URL(getCurrentUrlModifiers());
                modUrl.searchParams.set('page', url.searchParams.get('page'));

                refreshTable(modUrl.toString(), 'officer-search');
            }
        });
    </script>
</x-app-layout>