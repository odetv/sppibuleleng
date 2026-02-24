<x-app-layout title="Dashboard">
    <div class="py-10 p-4">
        <div class="w-full mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- 1. HEADER DASHBOARD --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800 uppercase tracking-wider">Ringkasan Sistem</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Status operasional dan statistik terkini</p>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Waktu Server</span>
                    <p id="realtime-clock" class="text-xs font-bold text-slate-700">
                        {{ now()->translatedFormat('d F Y | H:i:s') }} WITA
                    </p>
                </div>
            </div>

            @php
            $isMaintenance = \App\Models\Setting::get('is_maintenance', '0') === '1';
            @endphp
            <form action="{{ route('admin.maintenance.toggle') }}" method="POST">
                @csrf
                <button type="submit"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-xl font-bold text-[10px] uppercase tracking-wider transition-all shadow-lg 
        {{ $isMaintenance ? 'bg-rose-500 text-white shadow-rose-200' : 'bg-emerald-500 text-white shadow-emerald-200' }}">

                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>

                    {{ $isMaintenance ? 'Matikan Mode Pemeliharaan' : 'Aktifkan Mode Pemeliharaan' }}
                </button>
            </form>

            {{-- LOGIKA TAMBAHAN: JIKA PENDING TAMPILKAN PESAN VERIFIKASI --}}
            @if(auth()->user()->status_user === 'pending')
            <div class="bg-white rounded-2xl border-2 border-dashed border-amber-200 p-8 shadow-sm text-center">
                <div class="w-20 h-20 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 uppercase tracking-widest mb-3">Akun Menunggu Verifikasi</h3>
                <p class="text-slate-500 max-w-lg mx-auto mb-8 leading-relaxed text-sm">
                    Hai, {{ auth()->user()->person->name ?? 'Pengguna' }}.<br>
                    Saat ini akun Anda sedang dalam proses peninjauan oleh Administrator. Silakan hubungi admin terkait untuk mempercepat proses verifikasi.
                </p>
                <div class="flex flex-row gap-4 justify-center">
                    <a href="{{ route('profile.show') }}" class="px-8 py-3 bg-slate-800 text-white rounded-xl font-bold text-[11px] uppercase tracking-widest hover:bg-slate-900 transition-all shadow-lg">
                        Lihat Profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-8 py-3 bg-white border border-slate-200 text-slate-500 rounded-xl font-bold text-[11px] uppercase tracking-widest hover:bg-slate-50 transition-all w-full">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
            @else
            {{-- 2. MAIN LAYOUT: Split Grid --}}
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

                {{-- KOLOM KIRI: STATS CARDS (xl:col-span-7) --}}
                <div class="xl:col-span-7 grid grid-cols-1 md:grid-cols-2 gap-6 content-start">

                    {{-- CARD: PENGGUNA (TIRU STYLE PETUGAS SPPG) --}}
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm hover:border-indigo-300 transition-all">
                        <div class="p-5 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                            <h3 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Manajemen Pengguna</h3>
                            <span class="text-lg font-black text-indigo-600">65</span>
                        </div>
                        <div class="p-5 grid grid-cols-2 gap-3 text-xs">
                            <div class="flex flex-col border-b border-slate-50 pb-1">
                                <span class="text-slate-400 text-[9px] uppercase font-bold tracking-tighter">SPPI</span>
                                <span class="font-bold text-slate-700">20</span>
                            </div>
                            <div class="flex flex-col border-b border-slate-50 pb-1">
                                <span class="text-slate-400 text-[9px] uppercase font-bold tracking-tighter">Korwil</span>
                                <span class="font-bold text-slate-700">5</span>
                            </div>
                            <div class="flex flex-col border-b border-slate-50 pb-1 md:border-b-0 md:pb-0">
                                <span class="text-slate-400 text-[9px] uppercase font-bold tracking-tighter">Korcam</span>
                                <span class="font-bold text-slate-700">15</span>
                            </div>
                            <div class="flex flex-col border-b border-slate-50 pb-1 md:border-b-0 md:pb-0">
                                <span class="text-slate-400 text-[9px] uppercase font-bold tracking-tighter">KaSPPG</span>
                                <span class="font-bold text-slate-700">10</span>
                            </div>
                            <div class="flex flex-col pt-1">
                                <span class="text-slate-400 text-[9px] uppercase font-bold tracking-tighter">AG</span>
                                <span class="font-bold text-slate-700">15</span>
                            </div>
                            <div class="flex flex-col pt-1">
                                <span class="text-slate-400 text-[9px] uppercase font-bold tracking-tighter">AK</span>
                                <span class="font-bold text-slate-700">15</span>
                            </div>
                        </div>
                    </div>

                    {{-- CARD: SPPG --}}
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm hover:border-emerald-300 transition-all">
                        <div class="p-5 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                            <h3 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Unit SPPG</h3>
                            <span class="text-lg font-black text-emerald-600">15</span>
                        </div>
                        <div class="p-5 space-y-3 text-xs">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">Operasional</span>
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded font-bold">12</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">Tutup Sementara</span>
                                <span class="px-2 py-0.5 bg-amber-50 text-amber-600 rounded font-bold">2</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">Tutup Permanen</span>
                                <span class="px-2 py-0.5 bg-rose-50 text-rose-600 rounded font-bold">1</span>
                            </div>
                        </div>
                    </div>

                    {{-- CARD: SERTIFIKASI --}}
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm hover:border-blue-300 transition-all">
                        <div class="p-5 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                            <h3 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Sertifikasi</h3>
                            <span class="text-lg font-black text-blue-600">28</span>
                        </div>
                        <div class="p-5 space-y-3 text-xs">
                            <div class="flex justify-between items-center"><span class="text-slate-500">SLHS (Sertifikat Laik Higiene Sanitasi)</span><span class="font-bold text-slate-700">10</span></div>
                            <div class="flex justify-between items-center"><span class="text-slate-500">Halal</span><span class="font-bold text-slate-700">12</span></div>
                            <div class="flex justify-between items-center"><span class="text-slate-500">HACCP (Hazard Analysis and Critical Control Points)</span><span class="font-bold text-slate-700">6</span></div>
                        </div>
                    </div>

                    {{-- CARD: PETUGAS SPPG --}}
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm hover:border-slate-400 transition-all">
                        <div class="p-5 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                            <h3 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Petugas SPPG</h3>
                            <span class="text-lg font-black text-slate-800">56</span>
                        </div>
                        <div class="p-5 grid grid-cols-2 gap-3 text-xs">
                            <div class="flex flex-col border-b border-slate-50 pb-1"><span class="text-slate-400 text-[9px] uppercase font-bold">KaSPPG</span><span class="font-bold text-slate-700">8</span></div>
                            <div class="flex flex-col border-b border-slate-50 pb-1"><span class="text-slate-400 text-[9px] uppercase font-bold">Akuntansi</span><span class="font-bold text-slate-700">12</span></div>
                            <div class="flex flex-col"><span class="text-slate-400 text-[9px] uppercase font-bold">Ahli Gizi</span><span class="font-bold text-slate-700">6</span></div>
                            <div class="flex flex-col"><span class="text-slate-400 text-[9px] uppercase font-bold">Relawan</span><span class="font-bold text-slate-700">30</span></div>
                        </div>
                    </div>

                    {{-- CARD: PENERIMA MANFAAT --}}
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm hover:border-purple-300 transition-all">
                        <div class="p-5 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                            <h3 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Penerima Manfaat</h3>
                            <span class="text-lg font-black text-purple-600">1,240</span>
                        </div>
                        <div class="p-5 space-y-2.5 text-xs">
                            <div class="flex justify-between items-center"><span class="text-slate-500">Siswa</span><span class="font-bold text-slate-700">1,100</span></div>
                            <div class="flex justify-between items-center"><span class="text-slate-500">Keluarga 3B</span><span class="font-bold text-slate-700">85</span></div>
                            <div class="flex justify-between items-center"><span class="text-slate-500 font-medium">Pendidik & Tenaga Kependidikan</span><span class="font-bold text-slate-700">55</span></div>
                        </div>
                    </div>

                    {{-- CARD: SUPPLIER MBG --}}
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm hover:border-orange-300 transition-all">
                        <div class="p-5 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                            <h3 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Supplier MBG</h3>
                            <span class="text-lg font-black text-orange-600">24</span>
                        </div>
                        <div class="p-5 space-y-3 text-xs">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-orange-400"></div><span>UMKM</span>
                                </div><span class="font-bold text-slate-700">18</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-slate-300"></div><span>Non-UMKM</span>
                                </div><span class="font-bold text-slate-700">6</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: GEOSPATIAL MAPS (xl:col-span-5) --}}
                <div class="xl:col-span-5">
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm h-full min-h-[400px] flex flex-col transition-all">
                        <div class="p-6 border-b border-slate-50 bg-slate-50/50">
                            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Informasi Geospasial</h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Sebaran Unit Wilayah Operasional</p>
                        </div>
                        <div class="flex-1 bg-slate-50 flex items-center justify-center border-b border-slate-100 min-h-[400px]">
                            <div class="text-center space-y-3 opacity-40">
                                <svg class="w-12 h-12 text-slate-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                </svg>
                                <p class="text-[10px] font-bold uppercase tracking-[2px]">Geo-Location Map Placeholder</p>
                            </div>
                        </div>
                        <div class="p-5 bg-white grid grid-cols-2 gap-4 border-t border-slate-50">
                            <div class="p-3 rounded-lg bg-slate-50 border border-slate-100 text-center">
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Titik Terpantau</p>
                                <p class="text-sm font-bold text-slate-700">128 Lokasi</p>
                            </div>
                            <div class="p-3 rounded-lg bg-slate-50 border border-slate-100 text-center">
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Status GPS</p>
                                <p class="text-sm font-bold text-emerald-600 italic">Aktif</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- 3. BOTTOM INFO --}}
            <div class="p-2 bg-slate-50 rounded-xl border border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-xs text-slate-600 font-medium leading-relaxed">Data terintegrasi secara otomatis melalui koordinat GPS petugas lapangan SPPI BGN Buleleng.</p>
                </div>
            </div>
            @endif

        </div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const day = String(now.getDate()).padStart(2, '0');
            const month = now.toLocaleString('id-ID', {
                month: 'long'
            });
            const year = now.getFullYear();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            const formattedDate = `${day} ${month} ${year} ${hours}:${minutes}:${seconds}`;
            document.getElementById('realtime-clock').innerText = formattedDate + ' WITA';
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</x-app-layout>