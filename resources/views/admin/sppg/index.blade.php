<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 tracking-tight">KELOLA UNIT SPPG</h2>
                <p class="text-sm text-slate-500 mt-1">Manajemen seluruh Satuan Pelayanan Pemenuhan Gizi</p>
            </div>
            <div class="bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm text-right">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Waktu Sistem</span>
                <span class="text-sm font-bold text-slate-700 uppercase">{{ now()->format('d M Y, H:i') }} WITA</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 w-full px-4 sm:px-6 lg:px-8">
        <div class="max-w-full mx-auto space-y-6">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Unit</p>
                        <p class="text-xl font-bold text-slate-800">{{ $units->total() }}</p>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Operasional</p>
                        <p class="text-xl font-bold text-slate-800">{{ $units->where('status', 'Operasional')->count() }}</p>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Belum Buka</p>
                        <p class="text-xl font-bold text-slate-800">{{ $units->where('status', 'Belum Operasional')->count() }}</p>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-rose-50 flex items-center justify-center text-rose-500 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tutup Sem.</p>
                        <p class="text-xl font-bold text-slate-800">{{ $units->where('status', 'Tutup Sementara')->count() }}</p>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tutup Perm.</p>
                        <p class="text-xl font-bold text-slate-800">{{ $units->where('status', 'Tutup Permanen')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <h3 class="font-bold text-slate-700 uppercase text-sm tracking-wide">Daftar Unit Seluruh Indonesia</h3>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.sppg.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            TAMBAH UNIT
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Informasi Unit</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Alamat & Lokasi</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Kepala Unit (Leader)</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Status</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @foreach($units as $unit)
                            <tr class="hover:bg-slate-50/30 transition-colors">
                                <td class="px-6 py-5">
                                    <div class="font-bold text-slate-800 uppercase">{{ $unit->name }}</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5 font-medium tracking-tighter uppercase">ID: {{ $unit->id_sppg_unit }} | KODE: {{ $unit->code_sppg_unit ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="text-xs font-bold text-slate-700 uppercase">{{ $unit->regency }}</div>
                                    <div class="text-[11px] text-slate-400 mt-1 line-clamp-1 italic">{{ $unit->address }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    {{-- BAGIAN NAMA LEADER --}}
                                    @if($unit->leader)
                                    <div class="text-xs font-bold text-slate-700 uppercase">{{ $unit->leader->name }}</div>
                                    <div class="text-[10px] text-indigo-500 font-bold mt-0.5 uppercase">AKTIF</div>
                                    @else
                                    <div class="text-xs font-bold text-rose-500 uppercase tracking-tighter">BELUM PENUGASAN</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">ID: {{ $unit->leader_id ?? 'N/A' }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @php
                                    $style = match($unit->status) {
                                    'Operasional' => 'bg-emerald-50 text-emerald-600 ring-emerald-600/20',
                                    'Belum Operasional' => 'bg-amber-50 text-amber-600 ring-amber-600/20',
                                    'Tutup Sementara' => 'bg-rose-50 text-rose-600 ring-rose-600/20',
                                    default => 'bg-slate-100 text-slate-600 ring-slate-600/20',
                                    };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold uppercase ring-1 ring-inset {{ $style }}">
                                        {{ $unit->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.sppg.edit', $unit->id_sppg_unit) }}" class="text-slate-400 hover:text-indigo-600 p-2 hover:bg-indigo-50 rounded-lg transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M18.364 4.982a2.378 2.378 0 113.359 3.359L10.852 19.531l-4.243.606.606-4.243L18.364 4.982z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>