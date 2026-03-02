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


                        {{-- Social Media pindah ke Kiri --}}
                        @if($sppg->socialMedia && ($sppg->socialMedia->instagram || $sppg->socialMedia->facebook || $sppg->socialMedia->website))
                        <div class="w-full mt-auto pt-4 border-t border-slate-100">
                            <div class="flex flex-wrap justify-center gap-2">
                                @if($sppg->socialMedia->instagram)
                                <a href="https://instagram.com/{{ $sppg->socialMedia->instagram }}" target="_blank" class="p-2 text-slate-400 hover:text-pink-600 bg-slate-50 rounded-lg transition-colors" title="Instagram">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c2.717 0 3.056.01 4.122.06 1.065.05 1.79.217 2.428.465.66.254 1.216.598 1.772 1.153a4.908 4.908 0 0 1 1.153 1.772c.247.637.415 1.363.465 2.428.047 1.066.06 1.405.06 4.122 0 2.717-.01 3.056-.06 4.122-.05 1.065-.218 1.79-.465 2.428a4.883 4.883 0 0 1-1.153 1.772 4.915 4.915 0 0 1-1.772 1.153c-.637.247-1.363.415-2.428.465-1.066.047-1.405.06-4.122.06-2.717 0-3.056-.01-4.122-.06-1.065-.05-1.79-.218-2.428-.465a4.89 4.89 0 0 1-1.772-1.153 4.904 4.904 0 0 1-1.153-1.772c-.248-.637-.415-1.363-.465-2.428C2.013 15.056 2 14.717 2 12c0-2.717.01-3.056.06-4.122.05-1.066.217-1.79.465-2.428a4.88 4.88 0 0 1 1.153-1.772A4.897 4.897 0 0 1 5.45 2.525c.638-.248 1.362-.415 2.428-.465C8.944 2.013 9.283 2 12 2zm0 5a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm6.5-.25a1.25 1.25 0 0 0-2.5 0 1.25 1.25 0 0 0 2.5 0zM12 9a3 3 0 1 1 0 6 3 3 0 0 1 0-6z"/></svg>
                                </a>
                                @endif
                                @if($sppg->socialMedia->facebook)
                                <a href="{{ $sppg->socialMedia->facebook }}" target="_blank" class="p-2 text-slate-400 hover:text-blue-600 bg-slate-50 rounded-lg transition-colors" title="Facebook">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                                @endif
                                @if($sppg->socialMedia->website)
                                <a href="{{ $sppg->socialMedia->website }}" target="_blank" class="p-2 text-slate-400 hover:text-emerald-600 bg-slate-50 rounded-lg transition-colors" title="Website">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                </a>
                                @endif
                            </div>
                        </div>
                        @endif
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
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm p-12 flex flex-col items-center justify-center text-center">
                <div class="bg-rose-50 w-24 h-24 flex items-center justify-center rounded-full mb-6">
                    <svg class="w-12 h-12 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800">SPPG Belum Ditugaskan</h3>
                <p class="text-slate-500 max-w-lg mt-2 font-medium">
                    Saat ini Anda belum terhubung atau bertugas pada unit Satuan Pelayanan Pemenuhan Gizi manapun.
                    Silakan hubungi administrator atau petugas kepegawaian untuk informasi penugasan Anda.
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
