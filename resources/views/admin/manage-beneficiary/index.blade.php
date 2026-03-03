<x-app-layout title="Manajemen PM">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        #map-create, #map-edit { height: 350px; width: 100%; border-radius: 0.75rem; margin-top: 0.5rem; z-index: 1; background: #f8fafc; }
        .is-invalid { border: 2px solid #ef4444 !important; --tw-ring-color: #ef4444 !important; }
        .error-warning { color: #ef4444; font-size: 10px; font-weight: 700; font-style: italic; margin-top: 4px; display: block; }
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
                        <h2 class="text-xl font-bold text-slate-800 uppercase leading-tight">Kelola Penerima Manfaat (PM)</h2>
                        <p class="text-sm text-slate-400 font-medium mt-1">Manajemen data penerima manfaat terdaftar</p>
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
                    <div class="flex flex-wrap items-center gap-3">
                        <button @click="showCreateModal = true" class="flex items-center justify-center p-2.5 md:px-4 text-[11px] font-bold uppercase tracking-wider text-indigo-600 bg-white border border-indigo-200 rounded-lg hover:bg-indigo-600 hover:text-white transition-all cursor-pointer shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="hidden md:inline ml-2 text-nowrap">Tambah PM</span>
                        </button>

                        <div class="relative flex-grow md:flex-initial md:w-64 text-slate-800">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input type="text"
                                id="beneficiary-search"
                                class="live-search-input text-xs border-slate-200 rounded-lg pl-9 pr-3 py-2.5 w-full focus:ring-2 focus:ring-indigo-500 outline-none transition-all bg-white shadow-sm"
                                placeholder="Cari ID, kode, atau nama..." value="{{ request('search') }}"
                                autocomplete="off">
                        </div>
                    </div>
                </div>

                <div id="beneficiary-table-container">
                    <div class="overflow-x-auto scrollbar-thin">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                    <th class="px-6 py-4">INFORMASI PM</th>
                                    <th class="px-6 py-4 text-center">SPPG UNIT / PIC</th>
                                    <th class="px-6 py-4 text-center">STATUS</th>
                                    <th class="px-6 py-4 text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($beneficiaries as $pm)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div>
                                                <div class="font-bold text-slate-700 capitalize">{{ $pm->name }}</div>
                                                <div class="text-xs text-slate-500 font-medium whitespace-nowrap">
                                                    ID: {{ $pm->id_beneficiary }} <span class="mx-1">-</span> Kode: <span>{{ $pm->code ?? '-' }}</span>
                                                </div>
                                                <div class="text-[10px] text-slate-400 italic">{{ $pm->group_type }} ({{ $pm->category }})</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-slate-700 text-xs block font-bold capitalize">{{ $pm->sppgUnit->name ?? 'Belum Terhubung' }}</span>
                                        <span class="text-xs text-slate-500 font-medium">{{ $pm->pic_name ?? '-' }} ({{ $pm->pic_phone ?? '-' }})</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-xs font-medium px-1.5 py-0.5 rounded border capitalize {{ $pm->is_active ? 'bg-emerald-100 text-emerald-600 border-emerald-200' : 'bg-rose-100 text-rose-600 border-rose-200' }}">
                                            {{ $pm->is_active ? 'Aktif' : 'Non-Aktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center items-center gap-1">
                                            <button @click="selectedBeneficiary = {{ json_encode($pm) }}; showEditModal = true; $dispatch('init-edit-beneficiary', selectedBeneficiary)"
                                                title="Edit" class="p-2 text-slate-400 hover:text-indigo-600 cursor-pointer transition-colors hover:bg-indigo-50 rounded-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                            <button onclick="confirmDeleteBeneficiary('{{ $pm->id_beneficiary }}', '{{ addslashes($pm->name) }}')"
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
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <p class="text-slate-400 italic">Belum ada data penerima manfaat.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($beneficiaries->hasPages())
                    <div class="px-6 py-4 bg-white border-t border-slate-100">
                        {{ $beneficiaries->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- MODALS --}}
        @include('admin.manage-beneficiary.partials.modal-create')
        @include('admin.manage-beneficiary.partials.modal-edit')
        @include('admin.manage-beneficiary.partials.modal-delete')
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

        // Live search and pagination logic (simplified for now)
        let searchTimer;
        const searchInput = document.getElementById('beneficiary-search');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    const url = new URL(window.location.href);
                    url.searchParams.set('search', this.value);
                    url.searchParams.delete('page');
                    window.location.href = url.toString();
                }, 600);
            });
        }
    </script>
</x-app-layout>
