<x-app-layout title="Manajemen Yayasan">
    <div class="py-8 w-full px-4 sm:px-6 lg:px-8 relative">
        <div class="max-w-full mx-auto space-y-6">
            {{-- 1. HEADER SECTION --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 uppercase leading-tight">Manajemen Yayasan</h2>
                        <p class="text-sm text-slate-400 font-medium mt-1">Kelola data yayasan yang tergabung dalam kemitraan</p>
                    </div>
                </div>
            </div>

            {{-- 2. TABLE SECTION --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                    <h3 class="font-bold text-slate-700 uppercase tracking-wider text-[14px]">Daftar Yayasan</h3>
                </div>

                <div class="overflow-x-auto scrollbar-thin">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                <th class="px-6 py-4 whitespace-nowrap">NAMA YAYASAN</th>
                                <th class="px-6 py-4 whitespace-nowrap">PIMPINAN</th>
                                <th class="px-6 py-4 whitespace-nowrap">KONTAK</th>
                                <th class="px-6 py-4 whitespace-nowrap">ALAMAT</th>
                                <th class="px-6 py-4 text-center whitespace-nowrap">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($foundations as $foundation)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-700 capitalize text-sm">{{ $foundation->name_foundation }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-slate-500 text-xs font-medium">{{ $foundation->leader_name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-[12px] text-slate-600 font-medium">
                                        {{ $foundation->phone ?? '-' }}<br>
                                        <span class="text-slate-400 text-[11px] lowercase">{{ $foundation->email ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 min-w-[200px] max-w-sm whitespace-normal break-words">
                                    <div class="text-[12px] text-slate-600 font-medium leading-relaxed">
                                        {{ $foundation->address ?? '-' }}<br>
                                        <span class="text-slate-400 capitalize">{{ $foundation->village }}, {{ $foundation->district }}, {{ $foundation->regency }}, {{ $foundation->province }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{-- Action buttons --}}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Belum ada data yayasan</p>
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
