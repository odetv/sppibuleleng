<x-app-layout title="Profil Pengguna">

    <div class="py-12 p-4">
        <div class="w-full mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- 1. UTAMA: KARTU IDENTITAS --}}
            <div class="bg-white overflow-hidden rounded-xl border border-gray-100">
                <div class="p-8">
                    <div class="flex flex-wrap justify-between items-center mb-8 pb-4 border-b border-gray-50 gap-4">
                        <div class="flex items-center text-[11px] text-gray-400 space-x-6">
                            <span class="flex items-center">
                                <svg class="w-3.5 h-3.5 mr-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Dibuat - {{ Auth::user()->created_at->translatedFormat('d F Y H:i') }} WITA
                            </span>
                            <span class="flex items-center">
                                <svg class="w-3.5 h-3.5 mr-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Diperbarui - {{ Auth::user()->person ? Auth::user()->person->updated_at->translatedFormat('d F Y H:i') : '-' }} WITA
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row items-center space-y-6 md:space-y-0 md:space-x-12">
                        <div class="flex-shrink-0">
                            @if(Auth::user()->person && Auth::user()->person->photo)
                            <img class="h-60 w-40 rounded-2xl object-cover shadow-lg border-4 border-indigo-50"
                                src="{{ asset('storage/' . Auth::user()->person->photo) }}"
                                alt="Foto Profil">
                            @else
                            <div class="h-60 w-40 rounded-2xl bg-indigo-100 flex items-center justify-center shadow-inner border-4 border-white">
                                <svg class="h-24 w-24 text-indigo-200" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            @endif
                        </div>

                        <div class="flex-1 text-center md:text-left">
                            <div class="mb-6">
                                @php
                                $fullName = Auth::user()->person->name ?? Auth::user()->name;
                                if (Auth::user()->person && Auth::user()->person->title_education) {
                                $fullName .= ', ' . Auth::user()->person->title_education;
                                }
                                @endphp

                                <h3 class="text-xl sm:text-3xl font-bold text-gray-900 mb-2">
                                    {{ $fullName }}
                                </h3>
                                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mt-3">
                                    <span class="inline-flex items-center px-3 py-2.5 text-[10px] font-bold rounded-md bg-indigo-50 text-indigo-600 uppercase border border-indigo-100">
                                        {{ Auth::user()->role->name_role ?? '-' }}
                                    </span>
                                    <span class="inline-flex items-center px-3 py-2.5 text-[10px] font-bold rounded-md {{ Auth::user()->status_user == 'active' ? 'bg-green-50 text-green-600 border-green-100' : 'bg-yellow-50 text-yellow-600 border-yellow-100' }} uppercase border">
                                        {{ Auth::user()->status_user }}
                                    </span>
                                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-5 py-2.5 bg-white border border-slate-200 rounded-lg font-bold text-[10px] text-slate-700 uppercase hover:bg-slate-50 transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit Profil
                                    </a>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-5 mt-8 pt-6 border-t border-gray-50 text-sm text-left">
                                <div class="flex items-center space-x-4">
                                    <span class="text-gray-400 w-24 font-medium">NIK</span>
                                    <span class="text-gray-700 font-semibold">{{ Auth::user()->person->nik ?? '-' }}</span>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="text-gray-400 w-24 font-medium">Email</span>
                                    <span class="text-gray-700 font-semibold">{{ Auth::user()->email }}</span>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="text-gray-400 w-24 font-medium">Nomor KK</span>
                                    <span class="text-gray-700 font-semibold">{{ Auth::user()->person->no_kk ?? '-' }}</span>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="text-gray-400 w-24 font-medium">Telepon</span>
                                    <span class="text-gray-700 font-semibold">{{ Auth::user()->phone ?? '-' }}</span>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="text-gray-400 w-24 font-medium">NIP</span>
                                    <span class="text-gray-700 font-semibold">{{ Auth::user()->person->nip ?? '-' }}</span>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="text-gray-400 w-24 font-medium">NPWP</span>
                                    <span class="text-gray-700 font-semibold">{{ Auth::user()->person->npwp ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- 2. INFORMASI PRIBADI (DIBUAT 2 KOLOM) --}}
                <div class="bg-white rounded-xl border border-gray-100 p-8">
                    <h4 class="text-lg font-bold text-gray-800 mb-8 flex items-center uppercase">
                        <div class="p-1.5 bg-indigo-50 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        Informasi Pribadi
                    </h4>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 text-sm">
                        <div class="flex flex-col space-y-1 border-b border-gray-50 pb-2">
                            <dt class="text-gray-400 text-[11px] font-bold uppercase tracking-widest leading-none mb-1">Tempat Lahir</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person->place_birthday ?? '-' }}</dd>
                        </div>
                        <div class="flex flex-col space-y-1 border-b border-gray-50 pb-2">
                            <dt class="text-gray-400 text-[11px] font-bold uppercase tracking-widest leading-none mb-1">Tanggal Lahir</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person && Auth::user()->person->date_birthday ? \Carbon\Carbon::parse(Auth::user()->person->date_birthday)->translatedFormat('d F Y') : '-' }}</dd>
                        </div>
                        <div class="flex flex-col space-y-1 border-b border-gray-50 pb-2">
                            <dt class="text-gray-400 text-[11px] font-bold uppercase tracking-widest leading-none mb-1">Jenis Kelamin</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person && Auth::user()->person->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                        </div>
                        <div class="flex flex-col space-y-1 border-b border-gray-50 pb-2">
                            <dt class="text-gray-400 text-[11px] font-bold uppercase tracking-widest leading-none mb-1">Usia</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person->age ?? '-' }} Tahun</dd>
                        </div>
                        <div class="flex flex-col space-y-1 border-b border-gray-50 pb-2">
                            <dt class="text-gray-400 text-[11px] font-bold uppercase tracking-widest leading-none mb-1">Agama</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person->religion ?? '-' }}</dd>
                        </div>
                        <div class="flex flex-col space-y-1 border-b border-gray-50 pb-2">
                            <dt class="text-gray-400 text-[11px] font-bold uppercase tracking-widest leading-none mb-1">Status Perkawinan</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person->marital_status ?? '-' }}</dd>
                        </div>
                        <div class="flex flex-col space-y-1 border-b border-gray-50 pb-2">
                            <dt class="text-gray-400 text-[11px] font-bold uppercase tracking-widest leading-none mb-1">Pendidikan Terakhir</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person->last_education ?? '-' }}</dd>
                        </div>
                        <div class="flex flex-col space-y-1 border-b border-gray-50 pb-2">
                            <dt class="text-gray-400 text-[11px] font-bold uppercase tracking-widest leading-none mb-1">Jurusan/Program Studi</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person->major_education ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- 3. STATUS KERJA & SERAGAM --}}
                <div class="bg-white rounded-xl border border-gray-100 p-8">
                    <h4 class="text-lg font-bold text-gray-800 mb-8 flex items-center uppercase">
                        <div class="p-1.5 bg-indigo-50 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        Informasi Kedinasan
                    </h4>
                    <dl class="space-y-6 text-sm">
                        <div class="flex flex-col space-y-1.5 border-b border-gray-50 pb-4">
                            <dt class="text-gray-400 text-[11px] font-bold uppercase tracking-widest leading-none mb-1">Unit Penugasan</dt>
                            <dd class="font-semibold text-indigo-600">
                                {{ Auth::user()->person->workAssignment->sppgUnit->name ?? '-' }}
                                <span class="block text-[11px] text-slate-400 font-normal mt-0.5 uppercase">No. SK: {{ Auth::user()->person->workAssignment->decree->no_sk ?? 'Belum Ada SK' }}</span>
                            </dd>
                        </div>
                        <div class="flex flex-col space-y-1.5 border-b border-gray-50 pb-4">
                            <dt class="text-gray-400 text-[11px] font-bold uppercase tracking-widest leading-none mb-1">Jabatan</dt>
                            <dd class="font-semibold">
                                {{ Auth::user()->person->position->name_position ?? 'Belum Menjabat' }}
                            </dd>
                        </div>
                        <div class="grid grid-cols-2 gap-4 border-b border-gray-50 pb-4">
                            <div class="flex flex-col space-y-1.5">
                                <dt class="text-gray-400 text-[11px] font-bold uppercase tracking-widest leading-none mb-1">Batch</dt>
                                <dd class="font-semibold text-gray-800">{{ Auth::user()->person->batch ?? '-' }}</dd>
                            </div>
                            <div class="flex flex-col space-y-1.5">
                                <dt class="text-gray-400 text-[11px] font-bold uppercase tracking-widest leading-none mb-1">Status Kerja</dt>
                                <dd class="font-semibold text-gray-800">{{ Auth::user()->person->employment_status ?? '-' }}</dd>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex flex-col space-y-1.5 border-b border-gray-50 pb-4 md:border-none md:pb-0">
                                <dt class="text-gray-400 text-[11px] font-bold uppercase tracking-widest leading-none mb-1">Ukuran Baju</dt>
                                <dd class="font-semibold text-gray-800">{{ Auth::user()->person->clothing_size ?? '-' }}</dd>
                            </div>
                            <div class="flex flex-col space-y-1.5 border-b border-gray-50 pb-4 md:border-none md:pb-0">
                                <dt class="text-gray-400 text-[11px] font-bold uppercase tracking-widest leading-none mb-1">Ukuran Sepatu</dt>
                                <dd class="font-semibold text-gray-800">{{ Auth::user()->person->shoe_size ?? '-' }}</dd>
                            </div>
                        </div>
                    </dl>
                </div>

                {{-- 4. INFORMASI PAYROLL --}}
                <div class="bg-white rounded-xl border border-gray-100 p-8">
                    <h4 class="text-lg font-bold text-gray-800 mb-8 flex items-center uppercase">
                        <div class="p-1.5 bg-indigo-50 rounded-lg mr-3 text-indigo-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        Informasi Payroll
                    </h4>
                    <dl class="space-y-6 text-sm">
                        <div class="flex flex-col space-y-1.5 border-b border-gray-50 pb-4">
                            <dt class="text-gray-400 text-[11px] font-bold uppercase tracking-widest leading-none mb-1">Nama Bank</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person->payroll_bank_name ?? '-' }}</dd>
                        </div>
                        <div class="flex flex-col space-y-1.5 border-b border-gray-50 pb-4">
                            <dt class="text-gray-400 text-[11px] font-bold uppercase tracking-widest leading-none mb-1">Nomor Rekening</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person->payroll_bank_account_number ?? '-' }}</dd>
                        </div>
                        <div class="flex flex-col space-y-1.5 pb-4">
                            <dt class="text-gray-400 text-[11px] font-bold uppercase tracking-widest leading-none mb-1">Nama Pemilik Rekening</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person->payroll_bank_account_name ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- 5. ALAMAT DOMISILI --}}
                <div class="bg-white rounded-xl border border-gray-100 p-8">
                    <h4 class="text-lg font-bold text-gray-800 mb-8 flex items-center uppercase">
                        <div class="p-1.5 bg-indigo-50 rounded-lg mr-3 text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                        </div>
                        Informasi Alamat
                    </h4>
                    <dl class="space-y-5 text-sm">
                        <div class="grid grid-cols-2 gap-8 border-b border-gray-50 pb-3">
                            <div class="flex flex-col space-y-1.5">
                                <dt class="text-gray-400 text-[11px] font-bold uppercase tracking-widest">Provinsi</dt>
                                <dd class="font-semibold text-gray-800">{{ Auth::user()->person->province ?? 'Bali' }}</dd>
                            </div>
                            <div class="flex flex-col space-y-1.5">
                                <dt class="text-gray-400 text-[11px] font-bold uppercase tracking-widest">Kabupaten</dt>
                                <dd class="font-semibold text-gray-800">{{ Auth::user()->person->regency ?? 'Buleleng' }}</dd>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-8 border-b border-gray-50 pb-3">
                            <div class="flex flex-col space-y-1.5">
                                <dt class="text-gray-400 text-[11px] font-bold uppercase tracking-widest">Kecamatan</dt>
                                <dd class="font-semibold text-gray-800">{{ Auth::user()->person->district ?? '-' }}</dd>
                            </div>
                            <div class="flex flex-col space-y-1.5">
                                <dt class="text-gray-400 text-[11px] font-bold uppercase tracking-widest">Desa/Kelurahan</dt>
                                <dd class="font-semibold text-gray-800">{{ Auth::user()->person->village ?? '-' }}</dd>
                            </div>
                        </div>
                        <div class="flex flex-col space-y-1.5">
                            <dt class="text-gray-400 text-[11px] font-bold uppercase tracking-widest">Alamat Lengkap</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person->address ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- 6. PETA GPS --}}
                <div class="md:col-span-2 bg-white rounded-xl border border-gray-100 p-8">
                    <div class="flex flex-wrap justify-between items-center mb-8 gap-4">
                        <h4 class="text-lg font-bold text-gray-800 flex items-center uppercase">
                            <div class="p-1.5 bg-indigo-50 rounded-lg mr-3 text-indigo-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                </svg>
                            </div>
                            Lokasi Koordinat GPS
                        </h4>
                        @if(Auth::user()->person && Auth::user()->person->gps_coordinates)
                        <a href="https://www.google.com/maps/search/?api=1&query={{ Auth::user()->person->gps_coordinates }}"
                            target="_blank"
                            class="inline-flex items-center px-5 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-[11px] text-white uppercase tracking-widest hover:bg-indigo-700 transition shadow-md">
                            <svg class="w-3.5 h-3.5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 010-5 2.5 2.5 0 010 5z" />
                            </svg>
                            Lihat di Maps
                        </a>
                        @endif
                    </div>

                    <div id="map-preview" class="h-120 rounded-2xl border-4 border-white shadow-lg ring-1 ring-gray-100 z-0 mb-6"></div>

                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-3 bg-slate-50 rounded-2xl border border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse ring-4 ring-emerald-100"></div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest leading-none">Koordinat Terpilih</span>
                        </div>
                        <span class="font-mono text-indigo-600 font-semibold text-sm px-6 py-2 bg-white rounded-xl shadow-sm">
                            {{ Auth::user()->person->gps_coordinates ?? 'Data belum tersedia' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const coords = "{{ Auth::user()->person->gps_coordinates ?? '' }}";
            const mapContainer = document.getElementById('map-preview');

            if (coords && coords.includes(',')) {
                const latlng = coords.split(',').map(c => parseFloat(c.trim()));
                if (!isNaN(latlng[0]) && !isNaN(latlng[1])) {
                    const map = L.map('map-preview', {
                        zoomControl: true,
                        attributionControl: false
                    }).setView(latlng, 15);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                    L.marker(latlng).addTo(map);
                }
            } else {
                mapContainer.innerHTML =
                    '<div class="flex flex-col items-center justify-center h-full text-gray-300 space-y-2 bg-gray-50 rounded-2xl"><svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg><span class="text-xs font-bold uppercase tracking-widest italic">Data koordinat GPS belum tersedia</span></div>';
            }
        });
    </script>
</x-app-layout>