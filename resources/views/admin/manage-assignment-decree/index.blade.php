<x-app-layout title="Manajemen SK">
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

        .dropdown-sppg-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .dropdown-sppg-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .dropdown-sppg-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
    </style>

    <div class="py-8 w-full px-4 sm:px-6 lg:px-8 relative"
        x-data="{ 
            showCreateModal: false, 
            showEditModal: false, 
            showDeleteModal: false,
            deleteUrl: '',
            selectedDecree: { sppg_units: [] }
         }">

        <div class="max-w-full mx-auto space-y-6">
            {{-- 1. HEADER SECTION --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 uppercase leading-tight">Kelola SK Penugasan</h2>
                        <p class="text-sm text-slate-400 font-medium mt-1">Manajemen Surat Keputusan & BA Verifikasi Validasi</p>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-4 py-2 text-[10px] font-bold rounded bg-white text-slate-600 uppercase border border-slate-200 tracking-widest shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                            {{ $decrees->total() }} Dokumen SK
                        </span>
                    </div>
                </div>
            </div>

            {{-- 3. TABLE SECTION --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                    <h3 class="font-bold text-slate-700 uppercase tracking-wider text-[14px]">Daftar Seluruh Dokumen SK</h3>
                    <div class="flex flex-wrap items-center gap-3">

                        {{-- Tombol Export --}}
                        <button type="button" onclick="openExportModal()" class="flex items-center justify-center p-2.5 md:px-4 text-[11px] font-bold uppercase tracking-wider text-emerald-600 bg-white border border-emerald-200 rounded-lg hover:bg-emerald-600 hover:text-white transition-all cursor-pointer shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                <path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                            </svg>
                            <span class="hidden md:inline ml-2">Export</span>
                        </button>

                        {{-- Tombol Import --}}
                        <button type="button" onclick="openImportModal()" class="flex items-center justify-center p-2.5 md:px-4 text-[11px] font-bold uppercase tracking-wider text-amber-600 bg-white border border-amber-200 rounded-lg hover:bg-amber-600 hover:text-white transition-all cursor-pointer shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                <path fill-rule="evenodd" d="M11.47 2.47a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 1 1-1.06 1.06l-3.22-3.22V16.5a.75.75 0 0 1-1.5 0V4.81L8.03 8.03a.75.75 0 0 1-1.06-1.06l4.5-4.5ZM3 15.75a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                            </svg>
                            <span class="hidden md:inline ml-2">Import</span>
                        </button>

                        {{-- Tombol Tambah --}}
                        <button @click="showCreateModal = true" class="flex items-center justify-center p-2.5 md:px-4 text-[11px] font-bold uppercase tracking-wider text-indigo-600 bg-white border border-indigo-200 rounded-lg hover:bg-indigo-600 hover:text-white transition-all cursor-pointer shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
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
                                id="sk-search"
                                class="live-search-input text-xs border-slate-200 rounded-lg pl-9 pr-3 py-2.5 w-full focus:ring-2 focus:ring-indigo-500 outline-none transition-all bg-white shadow-sm"
                                placeholder="Cari Nomor SK..." value="{{ request('search') }}"
                                autocomplete="off">
                        </div>
                    </div>
                </div>

                <div id="sk-table-container">
                    <div class="overflow-x-auto scrollbar-thin">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                    <th class="px-6 py-4">NOMOR SK</th>
                                    <th class="px-6 py-4">TANGGAL SK</th>
                                    <th class="px-6 py-4">NOMOR BA VERVAL</th>
                                    <th class="px-6 py-4">TANGGAL BA VERVAL</th>
                                    <th class="px-6 py-4">SPPG TERKAIT</th>
                                    <th class="px-6 py-4">FILE SK</th>
                                    <th class="px-6 py-4 text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($decrees as $decree)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    {{-- NOMOR SK --}}
                                    <td class="px-6 py-4 font-bold text-slate-700 text-[13px]">
                                        {{ $decree->no_sk }}
                                    </td>

                                    {{-- TANGGAL SK --}}
                                    <td class="px-6 py-4 text-[13px] text-slate-600 font-medium whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($decree->date_sk)->translatedFormat('d F Y') }}
                                    </td>

                                    {{-- NOMOR BA VERVAL --}}
                                    <td class="px-6 py-4 text-[13px] text-slate-600 font-medium whitespace-nowrap">
                                        {{ $decree->no_ba_verval ?? '-' }}
                                    </td>

                                    {{-- TANGGAL BA VERVAL --}}
                                    <td class="px-6 py-4 text-[13px] text-slate-600 font-medium whitespace-nowrap">
                                        {{ $decree->date_ba_verval ? \Carbon\Carbon::parse($decree->date_ba_verval)->translatedFormat('d F Y') : '-' }}
                                    </td>

                                    {{-- SPPG TERKAIT --}}
                                    <td class="px-6 py-4">
                                        <div class="inline-flex flex-col bg-indigo-50/80 text-indigo-700 border border-indigo-100 rounded-xl p-3 w-max max-w-[320px]">
                                            <div class="flex items-center gap-2" title="{{ $decree->workAssignments->map(fn($wa) => $wa->sppgUnit?->name ?? 'SPPG Dihapus')->implode(', ') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <span class="text-[11px] font-bold uppercase tracking-wider">{{ $decree->workAssignments->count() }} SPPG</span>
                                            </div>

                                            @if($decree->workAssignments->count() > 1)
                                                <div class="mt-2.5 pt-2.5 border-t border-indigo-200/60 w-full">
                                                    <ul class="text-[11px] font-medium text-left space-y-1.5 list-none text-indigo-900/80">
                                                        @foreach($decree->workAssignments as $wa)
                                                            <li class="leading-tight relative pl-2.5 whitespace-normal">
                                                                <span class="absolute left-0 top-0 opacity-60 font-medium">-</span>
                                                                {{ $wa->sppgUnit?->name ?? 'SPPG Dihapus' }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @elseif($decree->workAssignments->count() == 1)
                                                <div class="mt-2.5 pt-2.5 border-t border-indigo-200/60 w-full">
                                                    <p class="text-[11px] font-medium text-left leading-tight whitespace-normal text-indigo-900/80">
                                                        {{ $decree->workAssignments->first()->sppgUnit?->name ?? 'SPPG Dihapus' }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- FILE SK --}}
                                    <td class="px-6 py-4">
                                        @if($decree->file_sk && $decree->workAssignments->isNotEmpty())
                                            @php
                                                $firstSppg = $decree->workAssignments->first()->id_sppg_unit;
                                                $sppgHash = md5($firstSppg . config('app.key'));
                                                $skHash = md5($decree->id_assignment_decree . config('app.key'));
                                                $skFileUrl = asset("storage/sppgunits/{$sppgHash}/files/{$skHash}/{$decree->file_sk}");
                                            @endphp
                                            <a href="{{ $skFileUrl }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[11px] font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 hover:text-indigo-800 rounded-lg transition-colors border border-indigo-100/50">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                Lihat SK
                                            </a>
                                        @else
                                            <span class="text-[11px] font-medium text-slate-400 bg-slate-50 px-2 py-1 rounded-md border border-slate-100">Tanpa Berkas</span>
                                        @endif
                                    </td>

                                    {{-- AKSI --}}
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center items-center gap-1">
                                            <button type="button" @click="
                                                @php
                                                    $skFileUrl = null;
                                                    if ($decree->workAssignments->isNotEmpty() && $decree->file_sk) {
                                                         $firstSppg = $decree->workAssignments->first()->id_sppg_unit;
                                                         $sppgHash = md5($firstSppg . config('app.key'));
                                                         $skHash = md5($decree->id_assignment_decree . config('app.key'));
                                                         $skFileUrl = asset('storage/sppgunits/' . $sppgHash . '/files/' . $skHash . '/' . $decree->file_sk);
                                                    }
                                                @endphp
                                                selectedDecree = eval(<?php echo htmlspecialchars(json_encode([
                                                'id_assignment_decree' => $decree->id_assignment_decree,
                                                'no_sk' => $decree->no_sk,
                                                'file_sk_url' => $skFileUrl,
                                                'date_sk' => $decree->date_sk,
                                                'no_ba_verval' => $decree->no_ba_verval,
                                                'date_ba_verval' => $decree->date_ba_verval,
                                                'sppg_units' => $decree->workAssignments->pluck('id_sppg_unit')->toArray()
                                            ])); ?>); showEditModal = true; setTimeout(() => window.dispatchEvent(new CustomEvent('init-edit-sk', { detail: selectedDecree })), 100)"
                                                title="Edit" class="p-2 text-slate-400 hover:text-emerald-600 cursor-pointer transition-colors hover:bg-emerald-50 rounded-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>

                                            <button type="button" @click="deleteUrl = '{{ route('admin.manage-assignment-decree.destroy', $decree->id_assignment_decree) }}'; showDeleteModal = true" title="Hapus" class="p-2 text-rose-600 hover:bg-rose-50 cursor-pointer rounded-lg opacity-80 hover:opacity-100 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="p-3 bg-slate-50 rounded-full mb-3">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                        </div>
                                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">
                                            Belum ada data SK terdaftar
                                        </p>
                                        <p class="text-[10px] text-slate-400 mt-1 italic">Coba gunakan kata kunci yang berbeda</p>
                                    </div>
                                </td>
                            </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($decrees->total() > 0)
                    <div class="px-6 py-4 bg-white border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 text-xs
        [&_nav]:flex [&_nav]:justify-between [&_nav]:items-center [&_nav]:w-full md:[&_nav]:w-auto
        [&_a]:bg-white [&_a]:text-slate-600 [&_a]:border-slate-200 [&_a]:rounded-lg [&_a]:hover:bg-slate-50
        [&_span]:bg-white [&_span]:text-slate-600 [&_span]:border-slate-200 [&_span]:rounded-lg
        [&_.bg-gray-800]:bg-emerald-600 [&_.bg-gray-800]:text-white [&_.bg-gray-800]:border-emerald-600
        [&_.dark\:bg-gray-800]:bg-white [&_.dark\:text-gray-400]:text-slate-600">
                        
                        {{-- DROPDOWN PER PAGE --}}
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-slate-600">Tampilkan</span>
                            <select id="sk-per-page" class="per-page-select border-slate-200 rounded-lg text-sm py-1.5 pl-3 pr-8 focus:ring-emerald-500 text-slate-600 font-medium cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors">
                                <option value="5" {{ request('per_page', 5) == '5' ? 'selected' : '' }}>5</option>
                                <option value="15" {{ request('per_page', 5) == '15' ? 'selected' : '' }}>15</option>
                                <option value="50" {{ request('per_page', 5) == '50' ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page', 5) == '100' ? 'selected' : '' }}>100</option>
                            </select>
                            <span class="text-sm text-slate-600 hidden sm:inline">Baris</span>
                        </div>

                        {{-- Pagination --}}
                        <div class="w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
                            {{ $decrees->links() }}
                        </div>

                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- MODALS --}}
        @include('admin.manage-assignment-decree.partials.modal-create')
        @include('admin.manage-assignment-decree.partials.modal-edit')
        @include('admin.manage-assignment-decree.partials.modal-delete')
    </div>

    {{-- SCRIPTS UTAMA --}}
    <script>
        // ==========================
        // FITUR LIVE SEARCH & PAGINATION AJAX
        // ==========================
        let searchTimer;

        function getCurrentUrlModifiers(inputEl = null) {
            let currentUrl = new URL(window.location.href);
            
            // 1. Ambil keyword search terbaru
            const activeSearch = document.getElementById('sk-search');
            if(activeSearch) currentUrl.searchParams.set('search', activeSearch.value);
            
            // 2. Ambil nilai per-page terbaru
            const activePerPage = document.getElementById('sk-per-page');
            if(activePerPage) currentUrl.searchParams.set('per_page', activePerPage.value);

            // Jauhkan parameter page (kembali ke hlmn 1) jika pemicunya BUKAN memencet link pagination
            if(inputEl) currentUrl.searchParams.delete('page');

            return currentUrl.toString();
        }

        function refreshTable(url, focusId = null) {
            const container = document.getElementById('sk-table-container');
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
                    let newContainer = doc.getElementById('sk-table-container');

                    if(newContainer) {
                        // Update konten (Gunakan outerHTML agar div utamanya ikut ter-replace utuh)
                        container.outerHTML = newContainer.outerHTML;
                    }

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

        // 1. Event Listener untuk Mengetik (Enter Key)
        document.addEventListener('keydown', function(e) {
            if (e.target.classList.contains('live-search-input')) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(searchTimer);
                    refreshTable(getCurrentUrlModifiers(e.target), e.target.id);
                }
            }
        });

        // 2. Event Listener untuk Timer Otomatis (Debounce saat mengetik lambat)
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('live-search-input')) {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    refreshTable(getCurrentUrlModifiers(e.target), e.target.id);
                }, 600);
            }
        });

        // 3. Listener untuk Dropdown Per Page
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('per-page-select')) {
                refreshTable(getCurrentUrlModifiers(e.target));
            }
        });

        // 4. Listener Pagination AJAX Tanpa Refresh
        document.addEventListener('click', function(e) {
            let anchor = e.target.closest('#sk-table-container nav a');
            if (anchor && anchor.getAttribute('href')) {
                let url = new URL(anchor.getAttribute('href'));
                
                // Pastikan nilai per_page terbawa saat memencet next page
                const activePerPage = document.getElementById('sk-per-page');
                if(activePerPage) url.searchParams.set('per_page', activePerPage.value);

                if (url.toString().includes('page=') && !url.toString().startsWith('javascript')) {
                    e.preventDefault();
                    refreshTable(url.toString());
                }
            }
        });
    </script>
    
    @include('admin.manage-assignment-decree.partials.modal-import')
    @include('admin.manage-assignment-decree.partials.modal-export')
</x-app-layout>
