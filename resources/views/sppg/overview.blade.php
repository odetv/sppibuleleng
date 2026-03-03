<x-app-layout title="Overview SPPG">
    @if($sppg && $sppg->latitude_gps && $sppg->longitude_gps)
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @endif
    <div class="py-8 w-full px-4 sm:px-6 lg:px-8 relative">
        <div class="max-w-full mx-auto space-y-6">



            @if($sppg)
            {{-- SPPG CARD WRAPPER --}}
            <div class="space-y-6">
                
                {{-- MAZE/LABYRINTH GRID SYSTEM --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
                    
                    {{-- KIRI: Foto & Identitas SPPG Singkat --}}
                    <div class="lg:col-span-6 flex flex-col h-full bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm p-6 lg:p-8 items-center text-center relative">
                        <div class="w-full">
                            <div class="w-full flex justify-center mb-5 mt-4 relative group">
                                <div class="aspect-[4/3] w-full max-w-[280px] rounded-xl border-4 border-slate-50 shadow-md overflow-hidden bg-slate-100 flex items-center justify-center relative">
                                    @if($sppg->photo)
                                        <img src="{{ asset('storage/' . $sppg->photo) }}" alt="Foto {{ $sppg->name }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    @endif

                                    {{-- Status Badge (Absolute Top Right ON PHOTO with Blur) --}}
                                    @php
                                        $statusStyles = match($sppg->status) {
                                            'Operasional' => 'bg-emerald-500/60 text-white border-emerald-400/30',
                                            'Belum Operasional' => 'bg-amber-500/60 text-white border-amber-400/30',
                                            'Tutup Sementara' => 'bg-rose-500/60 text-white border-rose-400/30',
                                            'Tutup Permanen' => 'bg-slate-900/60 text-white border-slate-700/30',
                                            default => 'bg-slate-500/60 text-white border-slate-400/30'
                                        };
                                    @endphp
                                    <div class="absolute top-3 right-3 shrink-0 inline-flex items-center px-1.5 py-0.5 rounded-md border text-[8px] font-bold uppercase tracking-wider shadow-sm backdrop-blur-md {{ $statusStyles }}">
                                        @if($sppg->status === 'Operasional')
                                        <span class="w-1 h-1 rounded-full bg-white mr-1 animate-pulse"></span>
                                        @endif
                                        {{ $sppg->status }}
                                    </div>
                                </div>
                            </div>
                            
                            <h3 class="text-lg font-bold text-slate-800 uppercase tracking-tight leading-tight">{{ $sppg->name }}</h3>
                            <div class="flex flex-wrap items-center justify-center gap-x-3 gap-y-1 mt-2 text-[12px] font-medium text-slate-500">
                                <span>ID: <span class="font-semibold text-slate-700">{{ $sppg->id_sppg_unit }}</span></span>
                                <span class="text-slate-300 hidden sm:inline">|</span>
                                <span>Kode: <span class="font-semibold text-slate-700">{{ $sppg->code_sppg_unit ?? '-' }}</span></span>
                            </div>
                        </div>


                        {{-- Social Media Action Row (Moved Below ID/Code) --}}
                            <div class="flex flex-row justify-center gap-3 mt-4 pt-4 border-t border-slate-50">
                                {{-- Facebook --}}
                                @if($sppg->socialMedia && $sppg->socialMedia->facebook_url)
                                <a href="{{ $sppg->socialMedia->facebook_url }}" target="_blank" class="w-10 h-10 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl border border-blue-100 shadow-sm hover:bg-blue-600 hover:text-white transition-all transform hover:scale-110" title="Facebook">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                                @else
                                <div class="w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-300 rounded-xl border border-slate-100 cursor-not-allowed opacity-50" title="Facebook Belum Tersedia">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </div>
                                @endif

                                {{-- Instagram --}}
                                @if($sppg->socialMedia && $sppg->socialMedia->instagram_url)
                                <a href="{{ $sppg->socialMedia->instagram_url }}" target="_blank" class="w-10 h-10 flex items-center justify-center bg-pink-50 text-pink-600 rounded-xl border border-pink-100 shadow-sm hover:bg-pink-600 hover:text-white transition-all transform hover:scale-110" title="Instagram">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                </a>
                                @else
                                <div class="w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-300 rounded-xl border border-slate-100 cursor-not-allowed opacity-50" title="Instagram Belum Tersedia">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                </div>
                                @endif

                                {{-- TikTok --}}
                                @if($sppg->socialMedia && $sppg->socialMedia->tiktok_url)
                                <a href="{{ $sppg->socialMedia->tiktok_url }}" target="_blank" class="w-10 h-10 flex items-center justify-center bg-slate-900 text-white rounded-xl border border-slate-800 shadow-md hover:bg-black transition-all transform hover:scale-110" title="TikTok">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.17 8.17 0 004.78 1.52V6.75a4.85 4.85 0 01-1.01-.06z"/></svg>
                                </a>
                                @else
                                <div class="w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-300 rounded-xl border border-slate-100 cursor-not-allowed opacity-50" title="TikTok Belum Tersedia">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.17 8.17 0 004.78 1.52V6.75a4.85 4.85 0 01-1.01-.06z"/></svg>
                                </div>
                                @endif
                            </div>
                    </div>

                    {{-- DETAIL KEPENGURUSAN --}}
                    <div class="lg:col-span-6 bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm p-6 lg:p-8 flex flex-col justify-center">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="p-2 bg-slate-50 text-slate-500 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </span>
                            <h4 class="text-[13px] font-extrabold text-slate-600 uppercase tracking-widest">Detail Kepengurusan</h4>
                        </div>
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <p class="text-[10px] font-medium text-slate-400 uppercase tracking-wider mb-1">Kepala SPPG</p>
                                <p class="text-[13px] font-medium text-slate-800 capitalize">{{ $sppg->leader->name ?? 'Belum Ditugaskan' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-medium text-slate-400 uppercase tracking-wider mb-1">Ahli Gizi</p>
                                <p class="text-[13px] font-medium text-slate-800 capitalize">{{ $sppg->nutritionist?->name ?? 'Belum Ditugaskan' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-medium text-slate-400 uppercase tracking-wider mb-1">Akuntan</p>
                                <p class="text-[13px] font-medium text-slate-800 capitalize">{{ $sppg->accountant?->name ?? 'Belum Ditugaskan' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-medium text-slate-400 uppercase tracking-wider mb-1">Operasional</p>
                                <p class="text-[13px] font-medium text-slate-800">
                                    {{ $sppg->operational_date ? \Carbon\Carbon::parse($sppg->operational_date)->translatedFormat('d F Y') : '-' }}
                                </p>
                            </div>
                        </div>

                        {{-- SK Info dipindah ke sini --}}
                        <div class="mt-5 pt-5 border-t border-slate-100 flex flex-col gap-3">
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Informasi SK Penugasan</p>
                            @if($decree)
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                                        <p class="text-[9px] font-medium text-slate-400 uppercase tracking-wider mb-1">Nomor SK</p>
                                        <p class="text-[11px] font-semibold text-slate-700 leading-tight">{{ $decree->no_sk ?? '-' }}</p>
                                    </div>
                                    <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                                        <p class="text-[9px] font-medium text-slate-400 uppercase tracking-wider mb-1">Tanggal SK</p>
                                        <p class="text-[11px] font-semibold text-slate-700 leading-tight">{{ $decree->date_sk ? \Carbon\Carbon::parse($decree->date_sk)->translatedFormat('d M Y') : '-' }}</p>
                                    </div>
                                    <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                                        <p class="text-[9px] font-medium text-slate-400 uppercase tracking-wider mb-1">No. BA Verval</p>
                                        <p class="text-[11px] font-semibold text-slate-700 leading-tight">{{ $decree->no_ba_verval ?? '-' }}</p>
                                    </div>
                                    <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                                        <p class="text-[9px] font-medium text-slate-400 uppercase tracking-wider mb-1">Tgl BA Verval</p>
                                        <p class="text-[11px] font-semibold text-slate-700 leading-tight">{{ $decree->date_ba_verval ? \Carbon\Carbon::parse($decree->date_ba_verval)->translatedFormat('d M Y') : '-' }}</p>
                                    </div>
                                </div>
                                @if($decree->file_sk)
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $decree->file_sk) }}" target="_blank" class="w-full flex justify-center items-center text-[10px] font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 px-4 py-2 rounded-lg transition-colors uppercase tracking-widest">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Lihat Dokumen SK
                                    </a>
                                </div>
                                @endif
                            @else
                                <div class="bg-slate-50 py-3 rounded-lg border border-slate-200/60 border-dashed flex items-center justify-center">
                                    <span class="text-[11px] font-medium text-slate-400">SK Belum Ditentukan</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- LOKASI: Alamat & Peta Gabungan --}}
                    <div class="lg:col-span-12 flex flex-col bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm h-full">
                        <div class="grid grid-cols-1 md:grid-cols-12 h-full">
                            {{-- Sisi Alamat --}}
                            <div class="md:col-span-4 p-6 lg:p-8 flex flex-col border-b md:border-b-0 md:border-r border-slate-100 bg-slate-50/30">
                                <div class="flex items-center gap-2 mb-6">
                                    <span class="p-1.5 bg-white text-slate-500 rounded-md border border-slate-100 shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </span>
                                    <h4 class="text-[12px] font-bold text-slate-600 uppercase tracking-widest">Alamat</h4>
                                </div>
                                <div class="space-y-4 flex-grow">
                                    <div class="grid grid-cols-2 gap-3 text-[11px]">
                                        <div><p class="text-slate-400 uppercase text-[8px] font-bold">Prov</p><p class="font-semibold text-slate-700">{{ $sppg->province ?? '-' }}</p></div>
                                        <div><p class="text-slate-400 uppercase text-[8px] font-bold">Kab</p><p class="font-semibold text-slate-700">{{ $sppg->regency ?? '-' }}</p></div>
                                        <div><p class="text-slate-400 uppercase text-[8px] font-bold">Kec</p><p class="font-semibold text-slate-700">{{ $sppg->district ?? '-' }}</p></div>
                                        <div><p class="text-slate-400 uppercase text-[8px] font-bold">Desa</p><p class="font-semibold text-slate-700">{{ $sppg->village ?? '-' }}</p></div>
                                    </div>
                                    <div class="pt-4 border-t border-slate-200/50">
                                        <p class="text-slate-400 uppercase text-[8px] font-bold mb-1">Jalan</p>
                                        <p class="text-[11px] font-medium text-slate-600 leading-relaxed">{{ $sppg->address ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Sisi Peta --}}
                            <div class="md:col-span-8 flex flex-col overflow-hidden relative min-h-[16rem]">
                                <div class="absolute top-4 right-4 z-20">
                                    <div class="bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm flex items-center gap-3">
                                        <div class="flex flex-col items-end">
                                            <span class="text-[8px] font-bold text-slate-400 uppercase leading-none mb-1 tracking-wider">Latitude</span>
                                            <span class="text-[11px] font-bold text-slate-700 leading-none tracking-tight">{{ $sppg->latitude_gps ?? '-' }}</span>
                                        </div>
                                        <div class="w-px h-6 bg-slate-200"></div>
                                        <div class="flex flex-col items-end">
                                            <span class="text-[8px] font-bold text-slate-400 uppercase leading-none mb-1 tracking-wider">Longitude</span>
                                            <span class="text-[11px] font-bold text-slate-700 leading-none tracking-tight">{{ $sppg->longitude_gps ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>

                                @if($sppg->latitude_gps && $sppg->longitude_gps)
                                <div id="map-overview" class="w-full h-full z-10"></div>
                                @else
                                <div class="w-full h-full bg-slate-50 flex items-center justify-center">
                                    <div class="text-center">
                                        <svg class="w-10 h-10 text-slate-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A2 2 0 013 15.483V4.517a2 2 0 011.553-1.943l5.447-1.362a2 2 0 011.1 0l5.447 1.362A2 2 0 0118 4.517v10.966a2 2 0 01-1.553 1.943L11 20z"></path></svg>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Koordinat Kosong</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            
            @else
            {{-- EMPTY STATE --}}
            <div class="bg-white rounded-2xl border-2 border-dashed border-amber-200 p-12 shadow-sm text-center mt-6">
                <div class="w-20 h-20 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 uppercase tracking-widest mb-3">SPPG Belum Ditugaskan</h3>
                <p class="text-slate-500 max-w-xl mx-auto leading-relaxed text-sm font-medium">
                    Hai, {{ auth()->user()->person->name ?? 'Pengguna' }}.<br>
                    Saat ini Anda belum terhubung atau bertugas pada unit Satuan Pelayanan Pemenuhan Gizi (SPPG) manapun. Silakan hubungi administrator atau petugas kepegawaian untuk informasi penugasan Anda.
                </p>
            </div>
            @endif

        </div>
    </div>

    @if($sppg && $sppg->latitude_gps && $sppg->longitude_gps)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var lat = {{ $sppg->latitude_gps }};
            var lon = {{ $sppg->longitude_gps }};
            
            var map = L.map('map-overview').setView([lat, lon], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);
            
            L.marker([lat, lon]).addTo(map);
        });
    </script>
    @endif
</x-app-layout>
