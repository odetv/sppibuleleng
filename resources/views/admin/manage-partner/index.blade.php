<x-app-layout title="Manajemen Mitra">
    <div class="py-8 w-full px-4 sm:px-6 lg:px-8 relative">
        <div class="max-w-full mx-auto space-y-6">
            {{-- 1. HEADER SECTION --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 uppercase leading-tight">Manajemen Mitra</h2>
                        <p class="text-sm text-slate-400 font-medium mt-1">Kelola data mitra instansi/organisasi kemitraan</p>
                    </div>
                </div>
            </div>

            {{-- 2. TABLE SECTION --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                    <h3 class="font-bold text-slate-700 uppercase tracking-wider text-[14px]">Daftar Mitra</h3>
                </div>

                <div class="overflow-x-auto scrollbar-thin">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                <th class="px-6 py-4 whitespace-nowrap">NAMA MITRA</th>
                                <th class="px-6 py-4 whitespace-nowrap">TIPE</th>
                                <th class="px-6 py-4 whitespace-nowrap">PIMPINAN</th>
                                <th class="px-6 py-4 whitespace-nowrap">ALAMAT</th>
                                <th class="px-6 py-4 text-center whitespace-nowrap">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($partners as $partner)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-700 capitalize text-sm">{{ $partner->name_partner }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-medium px-1.5 py-0.5 rounded border capitalize bg-indigo-50 text-indigo-600 border-indigo-100">
                                        {{ $partner->type_partner }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-slate-500 text-xs font-medium">{{ $partner->leader_name ?? '-' }}</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">{{ $partner->phone ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 min-w-[200px] max-w-sm whitespace-normal break-words">
                                    <div class="text-[12px] text-slate-600 font-medium leading-relaxed">
                                        {{ $partner->address ?? '-' }}<br>
                                        <span class="text-slate-400 capitalize">{{ $partner->village }}, {{ $partner->district }}, {{ $partner->regency }}, {{ $partner->province }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{-- Action buttons --}}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Belum ada data mitra</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
