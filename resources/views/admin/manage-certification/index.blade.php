<x-app-layout title="Manajemen Sertifikasi">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        id="certificationRoot"
        x-data="{ 
            showCreateModal: false, 
            showEditModal: false,
            showDeleteModal: false,
            selectedCertification: {},
            deleteInfo: { id: '', name: '', unit: '' },
            units: {{ $sppgUnits->map(fn($u) => [
                'id' => $u->id_sppg_unit,
                'name' => $u->name,
                'certs' => $u->certifications->pluck('name_certification')->toArray()
            ])->toJson() }},
            isCertTypeUsed(unitId, type, currentCertType = null) {
                if (!unitId) return false;
                const unit = this.units.find(u => u.id == unitId);
                if (!unit) return false;
                
                // If we are in Edit mode and the 'type' matches the 'currentCertType', it's allowed.
                if (currentCertType && type === currentCertType) return false;

                return unit.certs.includes(type); 
            }
        }"
        @open-delete-modal.window="
            showDeleteModal = true;
            deleteInfo = $event.detail;
        ">

        <div class="max-w-full mx-auto space-y-6">
            {{-- 1. HEADER SECTION --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 uppercase leading-tight">Manajemen Sertifikasi</h2>
                        <p class="text-sm text-slate-400 font-medium mt-1">Kelola data sertifikasi (Halal, SLHS, HACCP, Chef, dll) untuk seluruh unit SPPG.</p>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-4 py-2 text-[10px] font-bold rounded bg-white text-slate-600 uppercase border border-slate-200 tracking-widest shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-indigo-500 mr-2 animate-pulse"></span>
                            {{ $certifications->total() }} Sertifikasi Terdaftar
                        </span>
                    </div>
                </div>
            </div>

            {{-- 2. STATISTICS CARDS --}}
            @include('admin.manage-certification.partials.statistics-cards')

            {{-- 3. TABLE SECTION --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                    <h3 class="font-bold text-slate-700 uppercase tracking-wider text-[14px]">Daftar Sertifikasi</h3>
                    <div class="flex flex-col md:flex-row items-stretch md:items-center gap-4">
                        <div class="flex items-center gap-2 overflow-x-auto pb-1 md:pb-0 hide-scrollbar shrink-0">
                            {{-- Tombol Tambah --}}
                            <button @click="resetForm(); showCreateModal = true" class="flex items-center justify-center p-2.5 md:px-4 text-[11px] font-bold uppercase tracking-wider text-indigo-600 bg-white border border-indigo-200 rounded-lg hover:bg-indigo-600 hover:text-white transition-all cursor-pointer shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="hidden sm:inline ml-2 text-nowrap">Tambah</span>
                            </button>
                        </div>

                        <div class="flex items-center gap-2">
                            {{-- Search Input --}}
                            <div class="relative grow md:w-72 lg:w-80 text-slate-800">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </span>
                                <input type="text"
                                    id="cert-search"
                                    value="{{ request('search') }}"
                                    class="live-search-input text-xs border-slate-200 rounded-lg pl-9 pr-3 py-2.5 w-full focus:ring-2 focus:ring-indigo-500 outline-none transition-all bg-white shadow-sm"
                                    placeholder="Cari unit atau nomor..."
                                    autocomplete="off">
                            </div>

                            <button onclick="resetFilters()" type="button" class="flex items-center justify-center p-2.5 text-rose-500 bg-white border border-rose-100 rounded-lg hover:bg-rose-50 transition-all cursor-pointer shadow-sm shrink-0" title="Reset Filter">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- FILTER SECTION --}}
                <div class="p-4 bg-slate-50 border-b border-slate-100 grid grid-cols-3 lg:grid-cols-6 gap-3">
                    <div class="w-full">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1 px-1">Jenis</label>
                        <select id="filter-type" class="filter-input w-full text-[11px] border-slate-200 rounded-lg py-1.5 focus:ring-1 focus:ring-indigo-500 bg-white">
                            <option value="">Semua Jenis</option>
                            <option value="SLHS" {{ request('type') == 'SLHS' ? 'selected' : '' }}>SLHS</option>
                            <option value="Halal" {{ request('type') == 'Halal' ? 'selected' : '' }}>Halal</option>
                            <option value="HACCP" {{ request('type') == 'HACCP' ? 'selected' : '' }}>HACCP</option>
                            <option value="Chef" {{ request('type') == 'Chef' ? 'selected' : '' }}>Chef</option>
                        </select>
                    </div>

                    <div class="w-full">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1 px-1">Status</label>
                        <select id="filter-status" class="filter-input w-full text-[11px] border-slate-200 rounded-lg py-1.5 focus:ring-1 focus:ring-indigo-500 bg-white">
                            <option value="">Semua Status</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>
                </div>


                <div id="cert-table-container">
                    <div class="overflow-x-auto scrollbar-thin">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                    <th class="px-6 py-4">UNIT SPPG</th>
                                    <th class="px-6 py-4">SERTIFIKASI</th>
                                    <th class="px-6 py-4">DITERBITKAN OLEH</th>
                                    <th class="px-6 py-4 text-center">TGL TERBIT</th>
                                    <th class="px-6 py-4 text-center">TGL MULAI</th>
                                    <th class="px-6 py-4 text-center">TGL AKHIR</th>
                                    <th class="px-6 py-4 text-center">SISA MASA BERLAKU</th>
                                    <th class="px-6 py-4 text-center">STATUS</th>
                                    <th class="px-6 py-4 text-center">FILE</th>
                                    <th class="px-6 py-4 text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($certifications as $cert)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-700 capitalize">{{ $cert->sppgUnit->name }}</span>
                                            <span class="text-xs text-slate-500 font-medium font-sans">ID: {{ $cert->sppgUnit->id_sppg_unit }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-700 capitalize text-nowrap">{{ $cert->name_certification }}</span>
                                            <span class="text-[11px] text-slate-500 font-medium tracking-tight">{{ $cert->certification_number ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-xs text-slate-600 font-medium">{{ $cert->issued_by ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-xs text-slate-600 font-medium">{{ $cert->issued_date ? \Carbon\Carbon::parse($cert->issued_date)->translatedFormat('d F Y') : '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-xs text-slate-600 font-medium">{{ $cert->start_date ? \Carbon\Carbon::parse($cert->start_date)->translatedFormat('d F Y') : '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-xs text-slate-600 font-medium">{{ $cert->expiry_date ? \Carbon\Carbon::parse($cert->expiry_date)->translatedFormat('d F Y') : '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if(!$cert->expiry_date)
                                            <div class="flex justify-center" title="Infinity">
                                                <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-full">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                        <path d="M18.178 8c5.096 0 5.096 8 0 8-1.748 0-3.147-.9-4.425-2.274a1.023 1.023 0 00-1.506 0c-1.278 1.374-2.677 2.274-4.425 2.274-5.096 0-5.096-8 0-8 1.748 0 3.147.9 4.425 2.274a1.023 1.023 0 001.506 0C15.031 8.9 16.43 8 18.178 8z" />
                                                    </svg>
                                                </span>
                                            </div>
                                        @else
                                            @php 
                                                $expiry = \Carbon\Carbon::parse($cert->expiry_date)->startOfDay();
                                                $today = \Carbon\Carbon::now()->startOfDay();
                                                $diff = $today->diff($expiry);
                                                $isExpired = $today->gt($expiry);
                                                
                                                $years = $diff->y;
                                                $months = $diff->m;
                                                $days = $diff->d;
                                            @endphp

                                            @if($isExpired)
                                                <span class="px-2.5 py-1 bg-rose-50 text-rose-600 rounded-full text-[10px] font-bold uppercase tracking-wider">Kadaluwarsa</span>
                                            @elseif($years == 0 && $months == 0 && $days == 0)
                                                <span class="px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full text-[10px] font-bold uppercase tracking-wider underline">Hari Ini</span>
                                            @elseif($years == 0 && $months == 0 && $days <= 30)
                                                <span class="px-2.5 py-1 bg-amber-50 text-amber-600 rounded-full text-[10px] font-bold uppercase tracking-wider">{{ $days }} Hari Lagi</span>
                                            @else
                                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-bold uppercase tracking-wider whitespace-nowrap">
                                                    @if($years > 0) {{ $years }} Tahun @endif
                                                    @if($months > 0) {{ $months }} Bulan @endif
                                                    @if($days > 0) {{ $days }} Hari @endif
                                                </span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded border uppercase {{ $cert->status ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}">
                                            {{ $cert->status ? 'Aktif' : 'Non-Aktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($cert->file_certification)
                                            <a href="{{ asset('storage/' . $cert->file_certification) }}" target="_blank" class="inline-flex items-center justify-center p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Lihat File">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            </a>
                                        @else
                                            <span class="text-slate-300">
                                                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center items-center gap-1">
                                            <button type="button" @click="selectedCertification = {{ $cert->toJson() }}; selectedCertification.original_type = '{{ $cert->name_certification }}'; selectedCertification.sppg_unit = {{ $cert->sppgUnit->toJson() }}; showEditModal = true" title="Edit" class="p-2 text-slate-400 hover:text-indigo-600 cursor-pointer transition-colors hover:bg-indigo-50 rounded-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                            <button type="button" onclick="confirmDeleteCertification('{{ $cert->id_certification }}', '{{ addslashes($cert->name_certification) }}', '{{ addslashes($cert->sppgUnit->name) }}')" title="Hapus" class="p-2 text-rose-600 hover:bg-rose-50 cursor-pointer rounded-lg opacity-80 hover:opacity-100 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="p-3 bg-slate-50 rounded-full mb-3">
                                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">
                                                Belum ada data sertifikasi terdaftar
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINATION --}}
                    <div class="px-6 py-4 bg-white border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 text-xs
    [&_nav]:flex [&_nav]:justify-between [&_nav]:items-center [&_nav]:w-full md:[&_nav]:w-auto
    [&_a]:bg-white [&_a]:text-slate-600 [&_a]:border-slate-200 [&_a]:rounded-lg [&_a]:hover:bg-slate-50
    [&_span]:bg-white [&_span]:text-slate-600 [&_span]:border-slate-200 [&_span]:rounded-lg
    [&_.bg-gray-800]:bg-indigo-600 [&_.bg-gray-800]:text-white [&_.bg-gray-800]:border-indigo-600
    [&_.dark\:bg-gray-800]:bg-white [&_.dark\:text-gray-400]:text-slate-600">

                        <div class="flex items-center gap-2">
                            <span class="text-sm text-slate-600">Tampilkan</span>
                            <select id="per_page" class="per-page-select border-slate-200 rounded-lg text-sm py-1.5 pl-3 pr-8 focus:ring-indigo-500 text-slate-600 font-medium cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors">
                                <option value="5" {{ request('per_page') == '5' ? 'selected' : '' }}>5</option>
                                <option value="15" {{ request('per_page') == '15' ? 'selected' : '' }}>15</option>
                                <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                            </select>
                            <span class="text-sm text-slate-600 hidden sm:inline">Baris</span>
                        </div>

                        <div class="w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
                            {{ $certifications->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.manage-certification.partials.modal-create')
        @include('admin.manage-certification.partials.modal-edit')
        @include('admin.manage-certification.partials.modal-delete')
    </div>

    <script>
        let searchTimer;

        function getCurrentUrlModifiers(inputEl = null) {
            let currentUrl = new URL(window.location.href);

            // 1. Search keyword
            const activeSearch = document.getElementById('cert-search');
            if (activeSearch) {
                if (activeSearch.value) currentUrl.searchParams.set('search', activeSearch.value);
                else currentUrl.searchParams.delete('search');
            }

            // 2. Per-page
            const activePerPage = document.getElementById('per_page');
            if (activePerPage) currentUrl.searchParams.set('per_page', activePerPage.value);

            // 3. Filters
            const type = document.getElementById('filter-type');
            if (type && type.value !== '') currentUrl.searchParams.set('type', type.value); else currentUrl.searchParams.delete('type');
            
            const status = document.getElementById('filter-status');
            if (status && status.value !== '') currentUrl.searchParams.set('status', status.value); else currentUrl.searchParams.delete('status');

            // Back to page 1 if triggered by input/filter change
            if (inputEl) currentUrl.searchParams.delete('page');

            return currentUrl.toString();
        }

        function refreshTable(url, focusId = null) {
            const container = document.getElementById('cert-table-container');
            if (!container) return;

            fetch(url, {
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(response => response.text())
            .then(html => {
                let parser = new DOMParser();
                let doc = parser.parseFromString(html, 'text/html');

                const newTable = doc.getElementById('cert-table-container');
                if (newTable) container.innerHTML = newTable.innerHTML;

                window.history.pushState({}, '', url);

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

        // Event Listeners
        document.addEventListener('DOMContentLoaded', () => {
            // Live Search (Debounce)
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('live-search-input')) {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => {
                        refreshTable(getCurrentUrlModifiers(e.target), e.target.id);
                    }, 600);
                }
            });

            // Enter Key Search
            document.addEventListener('keydown', function(e) {
                if (e.target.classList.contains('live-search-input')) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        clearTimeout(searchTimer);
                        refreshTable(getCurrentUrlModifiers(e.target), e.target.id);
                    }
                }
            });

            // Filter & Per Page Changes
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('filter-input') || e.target.classList.contains('per-page-select')) {
                    refreshTable(getCurrentUrlModifiers(e.target), 'cert-search');
                }
            });

            // Pagination Clicks
            document.addEventListener('click', function(e) {
                let anchor = e.target.closest('#cert-table-container nav a');
                if (anchor && anchor.getAttribute('href')) {
                    let url = new URL(anchor.getAttribute('href'));
                    const modUrl = new URL(getCurrentUrlModifiers());
                    modUrl.searchParams.set('page', url.searchParams.get('page'));

                    if (!url.toString().startsWith('javascript')) {
                        e.preventDefault();
                        refreshTable(modUrl.toString(), 'cert-search');
                    }
                }
            });
        });

        function resetFilters() {
            const url = new URL(window.location.origin + window.location.pathname);
            window.location.href = url.toString();
        }

        function resetForm() {
            window.dispatchEvent(new CustomEvent('reset-create-modal'));
        }

        window.confirmDeleteCertification = function(id, name, unit) {
            window.dispatchEvent(new CustomEvent('open-delete-modal', { 
                detail: { id, name, unit } 
            }));
        }

        window.closeDeleteModal = function() {
            window.dispatchEvent(new CustomEvent('close-delete-modal'));
        }
    </script>
</x-app-layout>
