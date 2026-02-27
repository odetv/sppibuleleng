<x-app-layout title="Manajemen SPPG">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

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

        /* Style Peta Sesuai Mekanisme Referensi */
        #map-create {
            height: 350px;
            width: 100%;
            border-radius: 0.75rem;
            margin-top: 0.5rem;
            z-index: 1;
            background: #f8fafc;
        }

        /* Style Cropper Modal Sesuai Referensi */
        #cropperModal {
            background-color: rgba(0, 0, 0, 0.8);
        }

        /* Style Validasi & Drag Over */
        .is-invalid {
            border: 2px solid #ef4444 !important;
            --tw-ring-color: #ef4444 !important;
        }

        .drag-over {
            border-color: #4f46e5 !important;
            background-color: #f5f3ff !important;
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
            selectedUnit: { social_media: {} } 
         }">

        <div class="max-w-full mx-auto space-y-6">
            {{-- 1. HEADER SECTION --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 uppercase leading-tight">Kelola SPPG</h2>
                        <p class="text-sm text-slate-400 font-medium mt-1">Manajemen Satuan Pelayanan Pemenuhan Gizi terdaftar</p>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-4 py-2 text-[10px] font-bold rounded bg-white text-slate-600 uppercase border border-slate-200 tracking-widest shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-indigo-500 mr-2 animate-pulse"></span>
                            {{ $units->total() }} SPPG Terdaftar
                        </span>
                    </div>
                </div>
            </div>

            {{-- 2. KARTU STATISTIK --}}
            @include('admin.sppg.partials.statistics-cards')

            {{-- 3. TABLE SECTION --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                    <h3 class="font-bold text-slate-700 uppercase tracking-wider text-[14px]">Daftar Seluruh SPPG</h3>
                    <div class="flex flex-wrap items-center gap-3">
                        {{-- Tombol Export --}}
                            <button type="button" class="flex items-center justify-center p-2.5 md:px-4 text-[11px] font-bold uppercase tracking-wider text-emerald-600 bg-white border border-emerald-200 rounded-lg hover:bg-emerald-600 hover:text-white transition-all cursor-pointer shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                    <path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                </svg>
                                <span class="hidden md:inline ml-2">Export</span>
                            </button>

                            {{-- Tombol Import --}}
                            <button type="button" class="flex items-center justify-center p-2.5 md:px-4 text-[11px] font-bold uppercase tracking-wider text-amber-600 bg-white border border-amber-200 rounded-lg hover:bg-amber-600 hover:text-white transition-all cursor-pointer shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                    <path fill-rule="evenodd" d="M11.47 2.47a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 1 1-1.06 1.06l-3.22-3.22V16.5a.75.75 0 0 1-1.5 0V4.81L8.03 8.03a.75.75 0 0 1-1.06-1.06l4.5-4.5ZM3 15.75a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                </svg>
                                <span class="hidden md:inline ml-2">Import</span>
                            </button>

                            {{-- Tombol Tambah --}}
                            <button @click="showCreateModal = true" class="flex items-center justify-center p-2.5 md:px-4 text-[11px] font-bold uppercase tracking-wider text-indigo-600 bg-white border border-indigo-200 rounded-lg hover:bg-indigo-600 hover:text-white transition-all cursor-pointer shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                                <span class="hidden md:inline ml-2 text-nowrap">Tambah</span>
                            </button>

                        {{-- Search Input (Live Search) --}}
                        <div class="relative flex-grow md:flex-initial md:w-64 text-slate-800">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input type="text"
                                id="sppg-search"
                                data-table="sppg"
                                class="live-search-input text-xs border-slate-200 rounded-lg pl-9 pr-3 py-2.5 w-full focus:ring-2 focus:ring-indigo-500 outline-none transition-all bg-white shadow-sm"
                                placeholder="Cari ID, kode, atau nama..." value="{{ request('search') }}"
                                autocomplete="off">
                        </div>
                    </div>
                </div>

                <div id="sppg-table-container">
                    <div class="overflow-x-auto scrollbar-thin">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                <th class="px-6 py-4">INFORMASI UNIT</th>
                                <th class="px-6 py-4 text-center">KEPALA SPPG / TANGGAL OPS</th>
                                <th class="px-6 py-4 text-center">STATUS</th>
                                <th class="px-6 py-4 text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($units as $unit)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                {{-- INFORMASI UNIT --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-12 rounded-md bg-indigo-600 text-white flex justify-center items-center text-sm font-bold shadow-sm shrink-0 overflow-hidden">
                                            @if($unit->photo)
                                            <img src="{{ asset('storage/' . $unit->photo) }}" class="w-full h-full object-cover">
                                            @else
                                            <svg class="w-6 h-6 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-700 capitalize">{{ $unit->name }}</div>
                                            <div class="text-xs text-slate-500 font-medium whitespace-nowrap">
                                                ID: {{ $unit->id_sppg_unit }} <span class="mx-1">-</span> Kode: <span>{{ $unit->code_sppg_unit ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- KEPALA SPPG & TGL OPS --}}
                                <td class="px-6 py-4 text-center">
                                    <span class="text-slate-500 text-xs block capitalize font-medium">{{ $unit->leader->name ?? 'Belum Ditugaskan' }}</span>
                                    <span class="text-xs text-slate-500 capitalize font-medium">{{ $unit->operational_date ? \Carbon\Carbon::parse($unit->operational_date)->translatedFormat('d F Y') : '-' }}</span>
                                </td>

                                {{-- STATUS & TGL OPS --}}
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusStyles = match($unit->status) {
                                            'Operasional' => 'bg-[#e0fdef] text-[#047857] border-emerald-200',
                                            'Belum Operasional' => 'bg-amber-100 text-amber-600 border-amber-200',
                                            'Tutup Sementara' => 'bg-rose-100 text-rose-600 border-rose-200',
                                            'Tutup Permanen' => 'bg-black/50 text-white border-black/20',
                                            default => 'bg-slate-100 text-slate-500 border-slate-200'
                                        };
                                    @endphp
                                    <span class="text-xs font-medium px-1.5 py-0.5 rounded border capitalize {{ $statusStyles }}">
                                        {{ $unit->status }}
                                    </span>
                                </td>

                                {{-- AKSI --}}
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center items-center gap-1">
                                        <button type="button" @click="selectedUnit = {{ json_encode($unit->load('socialMedia')) }}; selectedUnit.original_id = '{{ $unit->id_sppg_unit }}'; showEditModal = true; setTimeout(() => window.dispatchEvent(new CustomEvent('init-edit-sppg', { detail: selectedUnit })), 300)"
                                            title="Edit" class="p-2 text-slate-400 hover:text-indigo-600 cursor-pointer transition-colors hover:bg-indigo-50 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>

                                        <button type="button" title="Hapus" onclick="confirmDeleteSppg('{{ $unit->id_sppg_unit }}', '{{ addslashes($unit->name) }}')" class="p-2 text-rose-600 hover:bg-rose-50 cursor-pointer rounded-lg opacity-80 hover:opacity-100 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="p-3 bg-slate-50 rounded-full mb-3">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                                        </div>
                                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">
                                            Belum ada data SPPG terdaftar
                                        </p>
                                        <p class="text-[10px] text-slate-400 mt-1 italic">Coba gunakan kata kunci yang berbeda</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($units->hasPages() || request('per_page') > 5)
                <div class="px-6 py-4 bg-white border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 text-xs
    [&_nav]:flex [&_nav]:justify-between [&_nav]:items-center [&_nav]:w-full md:[&_nav]:w-auto
    [&_a]:bg-white [&_a]:text-slate-600 [&_a]:border-slate-200 [&_a]:rounded-lg [&_a]:hover:bg-slate-50
    [&_span]:bg-white [&_span]:text-slate-600 [&_span]:border-slate-200 [&_span]:rounded-lg
    [&_.bg-gray-800]:bg-emerald-600 [&_.bg-gray-800]:text-white [&_.bg-gray-800]:border-emerald-600
    [&_.dark\:bg-gray-800]:bg-white [&_.dark\:text-gray-400]:text-slate-600">
                    
                    {{-- DROPDOWN PER PAGE --}}
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-slate-600">Tampilkan</span>
                        <select id="sppg-per-page" class="per-page-select border-slate-200 rounded-lg text-sm py-1.5 pl-3 pr-8 focus:ring-emerald-500 text-slate-600 font-medium cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors">
                            <option value="5" {{ request('per_page') == '5' ? 'selected' : '' }}>5</option>
                            <option value="15" {{ request('per_page') == '15' ? 'selected' : '' }}>15</option>
                            <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                        </select>
                        <span class="text-sm text-slate-600 hidden sm:inline">Baris</span>
                    </div>

                    {{-- LARAVEL PAGINATION --}}
                    <div class="w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
                        {{ $units->links() }}
                    </div>

                </div>
                @endif
                </div>
            </div>
        </div>

        {{-- MODALS --}}
        @include('admin.sppg.partials.modal-create')
        @include('admin.sppg.partials.modal-edit')
        @include('admin.sppg.partials.modal-cropper')
        @include('admin.sppg.partials.modal-delete')
    </div>

    {{-- SCRIPTS UTAMA --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Observer untuk memicu init Peta dan inisialisasi Cropper saat modal muncul
            const observer = new MutationObserver(() => {
                const modal = document.querySelector('[x-show="showCreateModal"]');
                if (modal && window.getComputedStyle(modal).display !== 'none' && window.getComputedStyle(modal).opacity === '1') {
                    // Panggil fungsi init dari modal-create
                    if (typeof initCreateMapModal === 'function') initCreateMapModal();
                    if (typeof initCropperLogic === 'function') initCropperLogic();
                }
            });

            observer.observe(document.body, {
                attributes: true,
                subtree: true,
                childList: true
            });
        });

        // ==========================
        // MODAL KLIK & DELETE LOGIC
        // ==========================
        window.confirmDeleteSppg = function(unitId, unitName) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            const info = document.getElementById('delete_modal_info');
            
            // Route asli Laravel Anda untuk SPPG adalah /admin/manage-sppg/{id}
            form.action = `/admin/manage-sppg/${unitId}`;
            info.innerHTML = `Data SPPG <b>${unitName}</b> akan dihapus secara permanen beserta foto dan media sosialnya.`;
            
            modal.classList.remove('hidden');
        }

        window.closeDeleteModal = function() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // ==========================
        // FITUR LIVE SEARCH & PAGINATION AJAX
        // ==========================
        let searchTimer;

        function getCurrentUrlModifiers(inputEl = null) {
            let currentUrl = new URL(window.location.href);
            
            // 1. Ambil keyword search terbaru
            const activeSearch = document.getElementById('sppg-search');
            if(activeSearch) currentUrl.searchParams.set('search', activeSearch.value);
            
            // 2. Ambil nilai per-page terbaru
            const activePerPage = document.getElementById('sppg-per-page');
            if(activePerPage) currentUrl.searchParams.set('per_page', activePerPage.value);

            // Jauhkan parameter page (kembali ke hlmn 1) jika pemicunya BUKAN memencet link pagination
            if(inputEl) currentUrl.searchParams.delete('page');

            return currentUrl.toString();
        }

        function refreshTable(url, focusId = null) {
            const container = document.getElementById('sppg-table-container');
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
                    let newContent = doc.getElementById('sppg-table-container').innerHTML;

                    // Update konten
                    container.innerHTML = newContent;

                    // Sinkronisasi URL tanpa reload
                    window.history.pushState({}, '', url);

                    // Kembalikan fokus kursor
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

        // 3. Event Listener untuk Timer Otomatis (Debounce saat mengetik lambat)
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('live-search-input')) {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    refreshTable(getCurrentUrlModifiers(e.target), e.target.id);
                }, 600);
            }
        });

        // 4. Listener untuk Dropdown Per Page
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('per-page-select')) {
                refreshTable(getCurrentUrlModifiers(e.target));
            }
        });

        // 5. Listener Pagination AJAX Tanpa Refresh
        document.addEventListener('click', function(e) {
            let anchor = e.target.closest('#sppg-table-container nav a');
            if (anchor && anchor.getAttribute('href')) {
                let url = new URL(anchor.getAttribute('href'));
                
                // Pastikan nilai per_page terbawa saat memencet next page
                const activePerPage = document.getElementById('sppg-per-page');
                if(activePerPage) url.searchParams.set('per_page', activePerPage.value);

                if (url.toString().includes('page=') && !url.toString().startsWith('javascript')) {
                    e.preventDefault();
                    refreshTable(url.toString());
                }
            }
        });
    </script>
</x-app-layout>