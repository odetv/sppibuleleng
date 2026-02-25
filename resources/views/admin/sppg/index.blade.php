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
                        <span class="inline-flex items-center px-4 py-2 text-[10px] font-bold rounded bg-slate-50 text-slate-600 uppercase border border-slate-200 tracking-widest shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-indigo-500 mr-2 animate-pulse"></span>
                            {{ $units->total() }} SPPG Terdaftar
                        </span>
                    </div>
                </div>
            </div>

            {{-- 2. KARTU STATISTIK --}}
            @include('admin.sppg.partials.statistics-cards')

            {{-- 3. TABLE SECTION --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <h3 class="font-bold text-slate-700 uppercase text-sm tracking-wide">Daftar Unit SPPG</h3>
                    <div class="flex items-center gap-2">
                        <button @click="showCreateModal = true" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition-all flex items-center gap-2 shadow-sm shadow-indigo-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            TAMBAH UNIT
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white">
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Informasi Unit</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Status & Tanggal Ops</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Kepala SPPG</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($units as $unit)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                {{-- INFORMASI UNIT --}}
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-10 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center">
                                            @if($unit->photo)
                                            <img src="{{ asset('storage/' . $unit->photo) }}" class="w-full h-full object-cover">
                                            @else
                                            <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-extrabold text-slate-700 text-[15px] uppercase leading-tight">{{ $unit->name }}</div>
                                            <div class="text-[12px] text-slate-400 font-medium mt-0.5 uppercase tracking-tight">
                                                ID: {{ $unit->id_sppg_unit }} | Kode: {{ $unit->code_sppg_unit ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- STATUS & TANGGAL OPS --}}
                                <td class="px-6 py-5 text-center">
                                    @php
                                    // Menentukan warna badge berdasarkan status
                                    $statusStyles = match($unit->status) {
                                    'Active', 'Operasional' => 'bg-emerald-50 text-emerald-500 border-emerald-100',
                                    'Pending', 'Belum Operasional' => 'bg-amber-50 text-amber-500 border-amber-100',
                                    'Inactive', 'Tutup Sementara' => 'bg-rose-50 text-rose-500 border-rose-100',
                                    default => 'bg-slate-50 text-slate-500 border-slate-100'
                                    };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-bold border uppercase {{ $statusStyles }}">
                                        {{ $unit->status }}
                                    </span>
                                    <div class="text-[12px] text-slate-400 mt-1 font-medium italic">
                                        {{ $unit->operational_date ?? '-' }}
                                    </div>
                                </td>

                                {{-- KEPALA SPPG --}}
                                <td class="px-6 py-5 text-center">
                                    <div class="text-[13px] font-bold text-slate-600 uppercase tracking-tight">
                                        {{ $unit->leader->name ?? 'Belum Ditugaskan' }}
                                    </div>
                                </td>

                                {{-- AKSI --}}
                                <td class="px-6 py-5 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        <button @click="selectedUnit = {{ json_encode($unit->load('socialMedia')) }}; showEditModal = true"
                                            class="p-2 text-slate-300 hover:text-indigo-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M18.364 4.982a2.378 2.378 0 113.359 3.359L10.852 19.531l-4.243.606.606-4.243L18.364 4.982z" />
                                            </svg>
                                        </button>

                                        <form action="{{ route('admin.sppg.destroy', $unit->id_sppg_unit) }}" method="POST" class="inline" onsubmit="return confirm('Hapus unit ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-300 hover:text-red-400 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic font-medium">
                                    Belum ada data unit SPPG terdaftar.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($units->hasPages())
                <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
                    {{ $units->links() }}
                </div>
                @endif
            </div>
        </div>

        {{-- MODALS --}}
        @include('admin.sppg.partials.modal-create')
        @include('admin.sppg.partials.modal-edit')
        @include('admin.sppg.partials.modal-cropper')
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
    </script>
</x-app-layout>