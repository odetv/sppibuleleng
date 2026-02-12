<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Profil Pengguna') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="py-12 p-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-8">
                    <div class="flex flex-wrap justify-between items-center mb-8 pb-4 border-b border-gray-50 gap-4">
                        <div class="flex items-center text-[10px] text-gray-400 uppercase tracking-widest space-x-6">
                            <span class="flex items-center">
                                <svg class="w-3.5 h-3.5 mr-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Terdaftar: {{ Auth::user()->created_at->translatedFormat('d F Y H:i') }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-3.5 h-3.5 mr-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Diperbaharui: {{ Auth::user()->person ? Auth::user()->person->updated_at->translatedFormat('d F Y H:i') : '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-12">
                        <div class="flex-shrink-0">
                            @if(Auth::user()->person && Auth::user()->person->photo)
                            <img class="h-44 w-44 rounded-2xl object-cover shadow-lg border-4 border-indigo-50"
                                src="{{ asset('storage/' . Auth::user()->person->photo) }}"
                                alt="Foto Profil">
                            @else
                            <div class="h-44 w-44 rounded-2xl bg-indigo-100 flex items-center justify-center shadow-inner border-4 border-white">
                                <svg class="h-24 w-24 text-indigo-200" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            @endif
                        </div>

                        <div class="flex-1 text-center md:text-left pt-2">
                            <div class="mb-6">
                                @php
                                $fullName = Auth::user()->person->name ?? Auth::user()->name;
                                if (Auth::user()->person->title_education) {
                                $fullName .= ', ' . Auth::user()->person->title_education;
                                }
                                @endphp

                                <h3 class="text-3xl font-bold text-gray-900 tracking-tight mb-2">
                                    {{ $fullName }}
                                </h3>
                                <div class="flex flex-wrap justify-center md:justify-start gap-3 mt-3">
                                    <span class="px-3 py-1 text-[10px] font-bold rounded-md bg-indigo-50 text-indigo-600 uppercase border border-indigo-100 tracking-wider">
                                        {{ Auth::user()->role->name_role ?? '-' }}
                                    </span>
                                    <span class="px-3 py-1 text-[10px] font-bold rounded-md {{ Auth::user()->status_user == 'active' ? 'bg-green-50 text-green-600 border-green-100' : 'bg-yellow-50 text-yellow-600 border-yellow-100' }} uppercase border tracking-wider">
                                        {{ Auth::user()->status_user }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-5 mt-8 pt-6 border-t border-gray-50 text-sm">
                                <div class="flex items-center space-x-4">
                                    <span class="text-gray-400 w-24 font-medium italic">NIK</span>
                                    <span class="font-semibold text-gray-800 tracking-wide">{{ Auth::user()->person->nik ?? '-' }}</span>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="text-gray-400 w-24 font-medium italic">Email</span>
                                    <span class="font-semibold text-gray-700">{{ Auth::user()->email }}</span>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="text-gray-400 w-24 font-medium italic">Nomor KK</span>
                                    <span class="font-semibold text-gray-700">{{ Auth::user()->person->no_kk ?? '-' }}</span>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="text-gray-400 w-24 font-medium italic">Telepon</span>
                                    <span class="font-semibold text-gray-700">{{ Auth::user()->phone ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 p-8">
                    <h4 class="text-lg font-bold text-gray-800 mb-8 flex items-center uppercase tracking-tight">
                        <div class="p-1.5 bg-indigo-50 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        Informasi Pribadi
                    </h4>
                    <dl class="space-y-6 text-sm">
                        <div class="flex flex-col space-y-1.5 border-b border-gray-50 pb-4">
                            <dt class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Tempat, Tanggal Lahir</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person->place_birthday ?? '-' }}, {{ Auth::user()->person ? \Carbon\Carbon::parse(Auth::user()->person->date_birthday)->translatedFormat('d F Y') : '-' }}</dd>
                        </div>
                        <div class="flex flex-col space-y-1.5 border-b border-gray-50 pb-4">
                            <dt class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Jenis Kelamin</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                        </div>
                        <div class="flex flex-col space-y-1.5 border-b border-gray-50 pb-4">
                            <dt class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Agama</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person->religion ?? '-' }}</dd>
                        </div>
                        <div class="flex flex-col space-y-1.5">
                            <dt class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Status Perkawinan</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person->marital_status ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 p-8">
                    <h4 class="text-lg font-bold text-gray-800 mb-8 flex items-center uppercase tracking-tight">
                        <div class="p-1.5 bg-indigo-50 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                        </div>
                        Alamat Domisili
                    </h4>
                    <dl class="space-y-5 text-sm">
                        <div class="grid grid-cols-2 gap-8">
                            <div class="flex flex-col space-y-1.5 border-b border-gray-50 pb-3">
                                <dt class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Provinsi</dt>
                                <dd class="font-semibold text-gray-800">{{ Auth::user()->person->city ?? 'Bali' }}</dd>
                            </div>
                            <div class="flex flex-col space-y-1.5 border-b border-gray-50 pb-3">
                                <dt class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Kabupaten</dt>
                                <dd class="font-semibold text-gray-800">{{ Auth::user()->person->regency ?? 'Buleleng' }}</dd>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-8">
                            <div class="flex flex-col space-y-1.5 border-b border-gray-50 pb-3">
                                <dt class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Kecamatan</dt>
                                <dd class="font-semibold text-gray-800">{{ Auth::user()->person->district ?? '-' }}</dd>
                            </div>
                            <div class="flex flex-col space-y-1.5 border-b border-gray-50 pb-3">
                                <dt class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Desa/Kelurahan</dt>
                                <dd class="font-semibold text-gray-800">{{ Auth::user()->person->village ?? '-' }}</dd>
                            </div>
                        </div>
                        <div class="pt-4">
                            <dt class="text-indigo-400 text-[10px] font-bold uppercase tracking-[0.2em] mb-3 text-center md:text-left">Alamat Lengkap Sesuai KTP</dt>
                            <dd class="font-medium text-gray-700 leading-relaxed bg-indigo-50/40 p-5 rounded-xl border border-indigo-100/50 text-center md:text-left">
                                {{ Auth::user()->person->address ?? '-' }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="md:col-span-2 bg-white shadow-sm sm:rounded-xl border border-gray-100 p-8">
                    <div class="flex flex-wrap justify-between items-center mb-8 gap-4">
                        <h4 class="text-lg font-bold text-gray-800 flex items-center uppercase tracking-tight">
                            <div class="p-1.5 bg-indigo-50 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                </svg>
                            </div>
                            Lokasi Titik Koordinat GPS
                        </h4>
                        @if(Auth::user()->person && Auth::user()->person->gps_coordinates)
                        <a href="https://www.google.com/maps/search/?api=1&query={{ Auth::user()->person->gps_coordinates }}"
                            target="_blank"
                            class="inline-flex items-center px-6 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-bold text-[10px] text-white uppercase tracking-widest hover:bg-indigo-700 transition shadow-md">
                            <svg class="w-3.5 h-3.5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 010-5 2.5 2.5 0 010 5z" />
                            </svg>
                            Lihat di Google Maps
                        </a>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 flex flex-col justify-center items-center text-center">
                            <span class="text-gray-400 text-[10px] uppercase font-bold mb-2 tracking-tighter">Koordinat Terpilih</span>
                            <span class="text-indigo-600 font-mono font-bold text-sm tracking-tight">{{ Auth::user()->person->gps_coordinates ?? 'Tidak ada data' }}</span>
                        </div>
                        <div class="md:col-span-3">
                            <div id="map-preview" class="h-80 rounded-2xl border-4 border-white shadow-lg ring-1 ring-gray-100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const coords = "{{ Auth::user()->person->gps_coordinates ?? '' }}";
            if (coords) {
                const latlng = coords.split(',').map(c => parseFloat(c.trim()));
                if (latlng.length === 2) {
                    const map = L.map('map-preview', {
                        zoomControl: true,
                        attributionControl: false
                    }).setView(latlng, 16);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                    L.marker(latlng).addTo(map);
                }
            } else {
                document.getElementById('map-preview').innerHTML =
                    '<div class="flex flex-col items-center justify-center h-full text-gray-300 space-y-2 bg-gray-50 rounded-2xl"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg><span class="text-xs italic">Data koordinat belum tersedia</span></div>';
            }
        });
    </script>
</x-app-layout>