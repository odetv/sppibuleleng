<x-app-layout title="Supplier MBG">
    <div class="p-4 md:p-6 lg:p-8" x-data="{ 
        showAssignModal: false,
        unitId: '{{ $unit->id_sppg_unit }}'
    }">
        <div class="max-w-full mx-auto space-y-6">
            {{-- 1. HEADER SECTION --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 uppercase leading-tight">Supplier MBG</h2>
                        <p class="text-sm text-slate-400 font-medium mt-1">Daftar mitra pemasok untuk unit: <span class="text-indigo-600">{{ $unit->name }}</span></p>
                    </div>
                </div>
            </div>

            {{-- 2. MY SUPPLIERS SECTION --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:justify-between md:items-center gap-6">
                    <div class="flex items-center gap-3">
                        <h3 class="font-bold text-slate-700 uppercase tracking-wider text-[14px]">Daftar Supplier Unit Kami</h3>
                    </div>

                    <div class="flex flex-col md:flex-row items-stretch md:items-center gap-4">
                        <button @click="showAssignModal = true" class="flex items-center justify-center p-2.5 md:px-4 text-[11px] font-bold uppercase tracking-wider text-white bg-indigo-600 rounded-lg hover:bg-slate-800 transition-all cursor-pointer shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="ml-2">Atur Supplier Unit</span>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jenis / Nama Supplier</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pimpinan / Kontak</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Komoditas</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($mySuppliers as $supplier)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="text-[12px] font-bold text-slate-700">{{ $supplier->name_supplier }}</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5 uppercase tracking-wider">{{ $supplier->type_supplier }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-[12px] font-medium text-slate-600">{{ $supplier->leader_name }}</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">{{ $supplier->phone }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-[11px] text-slate-600 italic whitespace-pre-line">{{ $supplier->commodities }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('sppg.supplier.unassign') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin melepas supplier ini dari unit Anda?')">
                                        @csrf
                                        <input type="hidden" name="id_sppg_unit" value="{{ $unit->id_sppg_unit }}">
                                        <input type="hidden" name="id_supplier" value="{{ $supplier->id_supplier }}">
                                        <button type="submit" class="p-2 text-rose-400 hover:text-rose-600 transition-colors" title="Lepas Supplier">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span class="text-sm font-medium">Belum ada supplier yang terhubung dengan unit Anda.</span>
                                        <p class="text-[11px] mt-1 italic">Klik tombol "Atur Supplier Unit" untuk menambahkan.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ASSIGN MODAL --}}
        <div x-show="showAssignModal" 
            class="fixed inset-0 z-[1000] flex items-center justify-center p-4 text-left" 
            x-cloak>
            
            <div x-show="showAssignModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showAssignModal = false"></div>

            <div x-show="showAssignModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative w-full max-w-2xl bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden font-sans text-sm">
                
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 uppercase tracking-widest text-[12px]">Menandai Supplier untuk Unit</h3>
                    <button @click="showAssignModal = false" class="text-slate-400 hover:text-slate-600 text-2xl font-light">&times;</button>
                </div>
                <div class="p-6">
                    <p class="text-[11px] text-slate-500 mb-6 font-medium italic uppercase tracking-wider">Pilih supplier dari daftar induk untuk dihubungkan ke unit SPPG ini.</p>
                    <div class="space-y-3 max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($availableSuppliers as $avail)
                        <div class="flex items-center justify-between p-4 border border-slate-100 rounded-xl hover:bg-slate-50 transition-colors">
                            <div>
                                <div class="text-[12px] font-bold text-slate-700">{{ $avail->name_supplier }}</div>
                                <div class="text-[10px] text-slate-400 uppercase tracking-widest">{{ $avail->type_supplier }}</div>
                            </div>
                            <form action="{{ route('sppg.supplier.assign') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_sppg_unit" value="{{ $unit->id_sppg_unit }}">
                                <input type="hidden" name="id_supplier" value="{{ $avail->id_supplier }}">
                                <button type="submit" class="px-4 py-2 text-[10px] font-bold text-white bg-indigo-600 rounded-lg hover:bg-slate-800 transition-all uppercase tracking-widest active:scale-95 shadow-sm shadow-indigo-100">PILIH</button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                    @if($availableSuppliers->isEmpty())
                    <div class="text-center py-12">
                        <svg class="w-12 h-12 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                        <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest">Semua supplier aktif sudah terhubung</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
