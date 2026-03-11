<x-app-layout title="Manajemen Supplier">
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

        .input-disabled {
            background-color: #f8fafc !important;
            color: #94a3b8 !important;
            cursor: not-allowed !important;
            pointer-events: none;
            border: 1px solid #e2e8f0 !important;
        }

        .is-invalid {
            border: 1px solid #ef4444 !important;
            box-shadow: 0 0 0 2px #fef2f2 !important;
            background-color: #fff1f2 !important;
        }

        .error-warning {
            color: #ef4444;
            font-size: 10px;
            font-weight: 700;
            font-style: italic;
            margin-top: 4px;
            display: block;
        }

        .validation-hidden {
            display: none !important;
        }
    </style>

    <div class="py-8 w-full px-4 sm:px-6 lg:px-8 relative"
        x-data="{ 
            showCreateModal: false, 
            showEditModal: false, 
            showDeleteModal: false,
            selectedSupplier: { sppg_units: [] },


            openEdit(supplier) {
                // Ensure sppg_units is always an array of strings for proper checkbox matching
                const units = supplier.sppg_units || supplier.sppgUnits || [];
                this.selectedSupplier = { 
                    ...supplier,
                    sppg_units: units.map(u => String(u.id_sppg_unit || u))
                };
                this.showEditModal = true;
                // Dispatch event for regional pre-population (vanilla JS listener in modal-edit)
                setTimeout(() => window.dispatchEvent(new CustomEvent('init-edit-supplier', { detail: this.selectedSupplier })), 100);
            },

            openDelete(supplier) {
                this.selectedSupplier = supplier;
                this.showDeleteModal = true;
            }
        }" x-init="">

        <div class="max-w-full mx-auto space-y-6">
            {{-- 1. HEADER SECTION --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 uppercase leading-tight">Manajemen Supplier</h2>
                        <p class="text-sm text-slate-400 font-medium mt-1">Manajemen data pemasok atau supplier MBG</p>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-4 py-2 text-[10px] font-bold rounded bg-white text-slate-600 uppercase border border-slate-200 tracking-widest shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-indigo-500 mr-2 animate-pulse"></span>
                            {{ $suppliers->total() }} Supplier Terdaftar
                        </span>
                    </div>
                </div>
            </div>

            {{-- 2. TABLE SECTION --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col xl:flex-row xl:justify-between xl:items-center gap-6">
                    <div class="flex items-center gap-3">
                        <h3 class="font-bold text-slate-700 uppercase tracking-wider text-[14px]">Daftar Seluruh Supplier</h3>
                    </div>

                    <div class="flex flex-col md:flex-row items-stretch md:items-center gap-4">
                        <div class="flex items-center gap-2 overflow-x-auto pb-1 md:pb-0 hide-scrollbar">
                            <button onclick="openExportModal()" class="flex items-center justify-center p-2.5 md:px-4 text-[11px] font-bold uppercase tracking-wider text-emerald-600 bg-white border border-emerald-200 rounded-lg hover:bg-emerald-600 hover:text-white transition-all cursor-pointer shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                    <path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                </svg>
                                <span class="hidden sm:inline ml-2 text-nowrap">Export</span>
                            </button>

                            <button onclick="openImportModal()" class="flex items-center justify-center p-2.5 md:px-4 text-[11px] font-bold uppercase tracking-wider text-amber-600 bg-white border border-amber-200 rounded-lg hover:bg-amber-600 hover:text-white transition-all cursor-pointer shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                    <path fill-rule="evenodd" d="M11.47 2.47a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 1 1-1.06 1.06l-3.22-3.22V16.5a.75.75 0 0 1-1.5 0V4.81L8.03 8.03a.75.75 0 0 1-1.06-1.06l4.5-4.5ZM3 15.75a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                </svg>
                                <span class="hidden sm:inline ml-2 text-nowrap">Import</span>
                            </button>

                            <button @click="selectedSupplier = { sppg_units: [] }; showCreateModal = true; $dispatch('open-create-supplier')" class="flex items-center justify-center p-2.5 md:px-4 text-[11px] font-bold uppercase tracking-wider text-indigo-600 bg-white border border-indigo-200 rounded-lg hover:bg-indigo-600 hover:text-white transition-all cursor-pointer shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="hidden sm:inline ml-2 text-nowrap">Tambah</span>
                            </button>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="relative grow md:w-72 lg:w-80 text-slate-800">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </span>
                                <input type="text"
                                    id="supplier-search"
                                    class="live-search-input text-xs border-slate-200 rounded-lg pl-9 pr-3 py-2.5 w-full focus:ring-2 focus:ring-indigo-500 outline-none transition-all bg-white shadow-sm"
                                    placeholder="Cari nama supplier atau pimpinan..." value="{{ request('search') }}"
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
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1 px-1">Jenis Supplier</label>
                        <select id="filter-type" class="filter-input w-full text-[11px] border-slate-200 rounded-lg py-1.5 focus:ring-1 focus:ring-indigo-500 bg-white">
                            <option value="">Semua Jenis</option>
                            @foreach($supplierTypes as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="supplier-table-container">
                    @fragment('supplier-table-container')
                    <div class="w-full overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse min-w-[1000px]">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100">
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Supplier</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pimpinan & Kontak</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Komoditas</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Unit SPPG</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Lokasi</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($suppliers as $supplier)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="text-xs font-bold text-slate-800">{{ $supplier->name_supplier }}</div>
                                            <div class="text-[10px] text-slate-400 font-medium uppercase mt-0.5">{{ $supplier->type_supplier }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs font-semibold text-slate-700">{{ $supplier->leader_name }}</div>
                                        <div class="text-[10px] text-indigo-500 font-bold mt-1 tracking-wider">{{ $supplier->phone }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-[11px] text-slate-600 leading-relaxed max-w-xs line-clamp-2">
                                            {{ $supplier->commodities }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($supplier->sppgUnits->count() > 0)
                                        <div class="inline-flex flex-col bg-indigo-50/80 text-indigo-700 border border-indigo-100 rounded-xl p-3 w-max max-w-[320px]">
                                            <div class="flex items-center gap-2" title="{{ $supplier->sppgUnits->pluck('name')->implode(', ') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <span class="text-[11px] font-bold uppercase tracking-wider">{{ $supplier->sppgUnits->count() }} UNIT</span>
                                            </div>

                                            @if($supplier->sppgUnits->count() > 1)
                                            <div class="mt-2.5 pt-2.5 border-t border-indigo-200/60 w-full">
                                                <ul class="text-[11px] font-medium text-left space-y-1.5 list-none text-indigo-900/80">
                                                    @foreach($supplier->sppgUnits as $unit)
                                                    <li class="leading-tight relative pl-2.5 whitespace-normal">
                                                        <span class="absolute left-0 top-0 opacity-60 font-medium">-</span>
                                                        {{ $unit->name }}
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @elseif($supplier->sppgUnits->count() == 1)
                                            <div class="mt-2.5 pt-2.5 border-t border-indigo-200/60 w-full">
                                                <p class="text-[11px] font-medium text-left leading-tight whitespace-normal text-indigo-900/80">
                                                    {{ $supplier->sppgUnits->first()->name }}
                                                </p>
                                            </div>
                                            @endif
                                        </div>
                                        @else
                                        <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest italic">Belum Ada Unit</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-[11px] text-slate-800 font-bold mb-1 line-clamp-1" title="{{ $supplier->address }}">{{ $supplier->address }}</div>
                                        <div class="text-xs text-slate-600 font-medium capitalize">{{ strtolower($supplier->village) }}, {{ strtolower($supplier->district) }}</div>
                                        <div class="text-[10px] text-slate-400 mt-1 tracking-tight capitalize">{{ strtolower($supplier->regency) }}, {{ strtolower($supplier->province) }}, {{ $supplier->postal_code }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <button @click="openEdit({{ json_encode($supplier) }})" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button @click="openDelete({{ json_encode($supplier) }})" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-4 text-2xl font-light">
                                                &times;
                                            </div>
                                            <div class="text-sm font-bold text-slate-400 uppercase tracking-widest">Tidak ada data supplier</div>
                                            <p class="text-xs text-slate-300 mt-2 font-medium">Coba ubah kata kunci pencarian atau filter Anda</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 text-xs
                        [&_nav]:flex [&_nav]:justify-between [&_nav]:items-center [&_nav]:w-full md:[&_nav]:w-auto
                        [&_a]:bg-white [&_a]:text-slate-600 [&_a]:border-slate-200 [&_a]:rounded-lg [&_a]:hover:bg-slate-50
                        [&_span]:bg-white [&_span]:text-slate-600 [&_span]:border-slate-200 [&_span]:rounded-lg
                        [&_.bg-gray-800]:bg-indigo-600 [&_.bg-gray-800]:text-white [&_.bg-gray-800]:border-indigo-600
                        [&_.dark\:bg-gray-800]:bg-white [&_.dark\:text-gray-400]:text-slate-600">

                        {{-- DROPDOWN PER PAGE --}}
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-slate-600">Tampilkan</span>
                            <select id="supplier-per-page" class="per-page-select border-slate-200 rounded-lg text-sm py-1.5 pl-3 pr-8 focus:ring-indigo-500 text-slate-600 font-medium cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors">
                                <option value="5" {{ request('per_page') == '5' || !request('per_page') ? 'selected' : '' }}>5</option>
                                <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                            </select>
                            <span class="text-sm text-slate-600 hidden sm:inline">Baris</span>
                        </div>

                        {{-- LARAVEL PAGINATION --}}
                        @if($suppliers->hasPages())
                        {{ $suppliers->links() }}
                        @endif
                    </div>
                    @endfragment
                </div>
            </div>
        </div>

        {{-- Modals --}}
        @include('admin.manage-supplier.partials.modal-create')
        @include('admin.manage-supplier.partials.modal-edit')
        @include('admin.manage-supplier.partials.modal-delete')
        @include('admin.manage-supplier.partials.modal-import')
        @include('admin.manage-supplier.partials.modal-export')
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        window.validSppgIds = {
            !!json_encode($validSppgIds) !!
        };
        let searchTimer;

        // 1. Get Current URL Modifiers
        function getCurrentUrlModifiers(triggerEl) {
            const url = new URL(window.location.href);
            const search = document.getElementById('supplier-search').value;
            const type = document.getElementById('filter-type').value;
            const perPage = document.getElementById('supplier-per-page').value;

            if (search) url.searchParams.set('search', search);
            else url.searchParams.delete('search');
            if (type) url.searchParams.set('type', type);
            else url.searchParams.delete('type');
            if (perPage) url.searchParams.set('per_page', perPage);
            else url.searchParams.delete('per_page');

            // Reset page if trigger is not pagination
            if (triggerEl && !triggerEl.closest('nav')) {
                url.searchParams.delete('page');
            }

            return url.toString();
        }

        // 2. Refresh Table Function
        function refreshTable(url, focusId = null) {
            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const oldContent = document.getElementById('supplier-table-container');

                    // If response is just the fragment, doc could be parsed into body.
                    // We'll try to find the container in the response, otherwise just use the body content.
                    const newContent = doc.getElementById('supplier-table-container');

                    if (oldContent) {
                        if (newContent) {
                            oldContent.innerHTML = newContent.innerHTML;
                        } else {
                            // Fallback if Laravel returns only the fragment content
                            oldContent.innerHTML = doc.body.innerHTML;
                        }
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

        // 3. Reset Filters
        window.resetFilters = function() {
            const url = new URL(window.location.origin + window.location.pathname);
            window.location.href = url.toString();
        }

        // 4. Event Listener for Search (Enter Key & Timer)
        document.addEventListener('keydown', function(e) {
            if (e.target.id === 'supplier-search') {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(searchTimer);
                    refreshTable(getCurrentUrlModifiers(e.target), e.target.id);
                }
            }
        });

        document.addEventListener('input', function(e) {
            if (e.target.id === 'supplier-search') {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    refreshTable(getCurrentUrlModifiers(e.target), e.target.id);
                }, 600);
            }
        });

        // 5. Listener for Filter Inputs & Dropdown Per Page
        document.addEventListener('change', function(e) {
            if (e.target.id === 'filter-type' || e.target.id === 'supplier-per-page') {
                refreshTable(getCurrentUrlModifiers(e.target), 'supplier-search');
            }
        });

        // 6. Listener Pagination AJAX
        document.addEventListener('click', function(e) {
            let anchor = e.target.closest('#supplier-table-container nav a');
            if (anchor && anchor.getAttribute('href')) {
                let url = new URL(anchor.getAttribute('href'));

                // Ensure per_page and filters are preserved
                const modUrl = new URL(getCurrentUrlModifiers());
                modUrl.searchParams.set('page', url.searchParams.get('page'));

                if (!url.toString().startsWith('javascript')) {
                    e.preventDefault();
                    refreshTable(modUrl.toString(), 'supplier-search');
                }
            }
        });
    </script>
</x-app-layout>