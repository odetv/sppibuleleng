<x-app-layout title="Profil Pengguna">
    <div class="py-12 p-4">
        <div class="w-full mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- 1. UTAMA: KARTU IDENTITAS --}}
            <div class="bg-white overflow-hidden rounded-xl border border-gray-100 shadow-sm">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8 gap-4 border-b border-gray-50 pb-6 md:pb-4">
                        {{-- Container Utama: flex-wrap agar bisa turun ke bawah jika tidak muat --}}
                        <div class="flex flex-wrap items-center text-[9px] md:text-[10px] text-gray-400 gap-x-6 gap-y-2 uppercase tracking-widest">

                            {{-- Waktu Dibuat --}}
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5 mr-2 text-gray-300 shrink-0">
                                    <path d="M12.75 12.75a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM7.5 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM8.25 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM9.75 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM10.5 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM12.75 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM14.25 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM15 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM16.5 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM15 12.75a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM16.5 13.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" />
                                    <path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 0 1 7.5 3v1.5h9V3A.75.75 0 0 1 18 3v1.5h.75a3 3 0 0 1 3 3v11.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V7.5a3 3 0 0 1 3-3H6V3a.75.75 0 0 1 .75-.75Zm13.5 9h-16.5v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5Z" clip-rule="evenodd" />
                                </svg>
                                {{-- Teks Label: Sembunyi di mobile (hidden), muncul di desktop (md:inline) --}}
                                <span class="hidden md:inline mr-1">Waktu Dibuat:</span>
                                <span>{{ Auth::user()->created_at->translatedFormat('d F Y H:i:s') }} WITA</span>
                            </div>

                            {{-- Waktu Diperbarui --}}
                            <div class="flex items-center">
                                <svg class="w-3.5 h-3.5 mr-2 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                {{-- Teks Label: Sembunyi di mobile (hidden), muncul di desktop (md:inline) --}}
                                <span class="hidden md:inline mr-1">Waktu Diperbarui:</span>
                                <span>{{ Auth::user()->person ? Auth::user()->person->updated_at->translatedFormat('d F Y H:i:s') . ' WITA' : '-' }}</span>
                            </div>

                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row items-center space-y-6 md:space-y-0 md:space-x-12">
                        <div class="flex-shrink-0">
                            @if(Auth::user()->person && Auth::user()->person->photo)
                            <img class="h-60 w-40 rounded-2xl object-cover shadow-lg border-4 border-indigo-50" src="{{ asset('storage/' . Auth::user()->person->photo) }}" alt="Foto Profil">
                            @else
                            <div class="h-60 w-40 rounded-2xl bg-indigo-100 flex items-center justify-center border-4 border-white shadow-inner">
                                <svg class="h-24 w-24 text-indigo-200" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            @endif
                        </div>

                        <div class="flex-1 text-left">
                            <div class="mb-6">
                                @php
                                $fullName = Auth::user()->person->name ?? Auth::user()->name;
                                if (Auth::user()->person && Auth::user()->person->title_education) {
                                $fullName .= ', ' . Auth::user()->person->title_education;
                                }
                                @endphp
                                <h3 class="text-lg sm:text-2xl font-bold text-gray-900 mb-2">{{ $fullName }}</h3>
                                <div class="flex flex-row items-center justify-center md:justify-start gap-3 mt-3">
                                    <span class="px-3 py-2 text-[10px] font-bold rounded-md bg-indigo-50 text-indigo-600 uppercase border border-indigo-100">{{ Auth::user()->role->name_role ?? '-' }}</span>
                                    <span class="px-3 py-2 text-[10px] font-bold rounded-md {{ Auth::user()->status_user == 'active' ? 'bg-green-50 text-green-600 border-green-100' : 'bg-yellow-50 text-yellow-600 border-yellow-100' }} uppercase border">{{ Auth::user()->status_user }}</span>
                                    <a href="{{ route('profile.edit') }}"
                                        class="inline-flex items-center gap-2.5 px-4 py-2 bg-white border border-slate-200 rounded-md font-bold text-[10px] text-slate-700 uppercase hover:bg-slate-50 hover:border-indigo-200 transition-all group">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            class="w-4 h-4 text-slate-400 group-hover:text-indigo-600 transition-colors">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>

                                        <span>Edit</span>
                                    </a>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-12 gap-y-4 text-sm border-t border-gray-50 pt-6">
                                <div class="flex justify-between md:justify-start gap-4">
                                    <span class="text-gray-400 font-bold w-24 uppercase text-[10px] tracking-widest">NIK</span>
                                    <span class="text-gray-700 font-semibold">{{ Auth::user()->person->nik ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between md:justify-start gap-4">
                                    <span class="text-gray-400 font-bold w-24 uppercase text-[10px] tracking-widest text-nowrap">Nomor KK</span>
                                    <span class="text-gray-700 font-semibold">{{ Auth::user()->person->no_kk ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between md:justify-start gap-4">
                                    <span class="text-gray-400 font-bold w-24 uppercase text-[10px] tracking-widest uppercase">NIP</span>
                                    <span class="text-gray-700 font-semibold">{{ Auth::user()->person->nip ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between md:justify-start gap-4">
                                    <span class="text-gray-400 font-bold w-24 uppercase text-[10px] tracking-widest uppercase text-nowrap">NPWP</span>
                                    <span class="text-gray-700 font-semibold">{{ Auth::user()->person->npwp ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between md:justify-start gap-4">
                                    <span class="text-gray-400 font-bold w-24 uppercase text-[10px] tracking-widest">BPJS Kes</span>
                                    <span class="text-gray-700 font-semibold">{{ Auth::user()->person->no_bpjs_kes ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between md:justify-start gap-4">
                                    <span class="text-gray-400 font-bold w-24 uppercase text-[10px] tracking-widest text-nowrap">BPJS TK</span>
                                    <span class="text-gray-700 font-semibold">{{ Auth::user()->person->no_bpjs_tk ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between md:justify-start gap-4">
                                    <span class="text-gray-400 font-bold w-24 uppercase text-[10px] tracking-widest">Email</span>
                                    <span class="text-gray-700 font-semibold lowercase">{{ Auth::user()->email }}</span>
                                </div>
                                <div class="flex justify-between md:justify-start gap-4">
                                    <span class="text-gray-400 font-bold w-24 uppercase text-[10px] tracking-widest">Telepon</span>
                                    <span class="text-gray-700 font-semibold">{{ Auth::user()->phone ?? '-' }}</span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- 2. DETAIL PERSONAL --}}
                <div class="bg-white rounded-xl border border-gray-100 p-8 shadow-sm">
                    <h4 class="text-sm font-bold text-indigo-600 mb-8 uppercase tracking-widest flex items-center border-b border-indigo-50 pb-4">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Detail Personal
                    </h4>
                    <dl class="grid grid-cols-2 gap-x-8 gap-y-6 text-sm text-left">
                        <div>
                            <dt class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Tempat Lahir</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person->place_birthday ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Tanggal Lahir</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person && Auth::user()->person->date_birthday ? \Carbon\Carbon::parse(Auth::user()->person->date_birthday)->translatedFormat('d F Y') : '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Jenis Kelamin</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person && Auth::user()->person->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Usia</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person->age ?? '-' }} Tahun</dd>
                        </div>
                        <div>
                            <dt class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Agama</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person->religion ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Status Perkawinan</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person->marital_status ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Pendidikan Terakhir</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person->last_education ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Jurusan/Program Studi</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person->major_education ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- 3. KEDINASAN --}}
                <div class="bg-white rounded-xl border border-gray-100 p-8 shadow-sm text-left">
                    <h4 class="text-sm font-bold text-blue-600 mb-8 uppercase tracking-widest flex items-center border-b border-blue-50 pb-4">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Informasi Kedinasan
                    </h4>
                    <dl class="space-y-6 text-sm">
                        <div class="p-4 bg-blue-50/50 rounded-xl border border-blue-100">
                            <dt class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Unit Penugasan</dt>
                            <dd class="font-bold text-indigo-700 text-base">{{ Auth::user()->person->workAssignment->sppgUnit->name ?? 'Belum Penugasan' }}</dd>
                            <dd class="text-[11px] text-indigo-400 font-medium uppercase mt-1">No. SK: {{ Auth::user()->person->workAssignment->decree->no_sk ?? '-' }}</dd>
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <dt class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Jabatan</dt>
                                <dd class="font-semibold text-gray-800">{{ Auth::user()->person->position->name_position ?? 'Belum Menjabat' }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1 text-nowrap">Batch</dt>
                                <dd class="font-semibold text-gray-800">{{ Auth::user()->person->batch ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Status Kerja</dt>
                                <dd class="font-semibold text-gray-800">{{ Auth::user()->person->employment_status ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1 text-nowrap">Ukuran Baju/Sepatu</dt>
                                <dd class="font-semibold text-gray-800">{{ Auth::user()->person->clothing_size ?? '-' }}/{{ Auth::user()->person->shoe_size ?? '-' }}</dd>
                            </div>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- 4. ALAMAT LENGKAP --}}
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm text-left">
                <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-gray-100">
                    {{-- ALAMAT KTP --}}
                    <div class="p-8">
                        <h4 class="text-sm font-bold text-indigo-600 mb-6 uppercase tracking-widest flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Alamat KTP
                        </h4>
                        <div class="grid grid-cols-2 gap-6 text-sm">
                            <div>
                                <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Provinsi</dt>
                                <dd class="font-semibold text-gray-800 mt-1">{{ Auth::user()->person->province_ktp ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kabupaten</dt>
                                <dd class="font-semibold text-gray-800 mt-1">{{ Auth::user()->person->regency_ktp ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kecamatan</dt>
                                <dd class="font-semibold text-gray-800 mt-1">{{ Auth::user()->person->district_ktp ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Desa/Kelurahan</dt>
                                <dd class="font-semibold text-gray-800 mt-1">{{ Auth::user()->person->village_ktp ?? '-' }}</dd>
                            </div>
                            <div class="col-span-2">
                                <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Alamat Jalan/Rumah</dt>
                                <dd class="font-semibold text-gray-800 mt-1">{{ Auth::user()->person->address_ktp ?? '-' }}</dd>
                            </div>
                        </div>
                    </div>

                    {{-- ALAMAT DOMISILI --}}
                    <div class="p-8 bg-white font-sans">
                        <h4 class="text-sm font-bold text-indigo-600 mb-6 uppercase tracking-widest flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                            Alamat Domisili
                        </h4>
                        <div class="grid grid-cols-2 gap-6 text-sm">
                            <div>
                                <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Provinsi</dt>
                                <dd class="font-semibold text-gray-800 mt-1">{{ Auth::user()->person->province_domicile ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kabupaten</dt>
                                <dd class="font-semibold text-gray-800 mt-1">{{ Auth::user()->person->regency_domicile ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kecamatan</dt>
                                <dd class="font-semibold text-gray-800 mt-1">{{ Auth::user()->person->district_domicile ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Desa/Kelurahan</dt>
                                <dd class="font-semibold text-gray-800 mt-1">{{ Auth::user()->person->village_domicile ?? '-' }}</dd>
                            </div>
                            <div class="col-span-2">
                                <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Alamat Jalan/Rumah</dt>
                                <dd class="font-semibold text-gray-800 mt-1">{{ Auth::user()->person->address_domicile ?? '-' }}</dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. PAYROLL & MEDIA SOSIAL --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 text-left">
                <div class="bg-white rounded-xl border border-gray-100 p-8 shadow-sm">
                    <h4 class="text-sm font-bold text-indigo-600 mb-8 uppercase tracking-widest flex items-center border-b border-amber-50 pb-4">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Informasi Payroll
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                        <div class="flex flex-col p-4 bg-gray-50 rounded-xl">
                            <dt class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Nama Bank</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person->payroll_bank_name ?? '-' }}</dd>
                        </div>
                        <div class="flex flex-col p-4 bg-gray-50 rounded-xl">
                            <dt class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1 text-nowrap">Nomor Rekening</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person->payroll_bank_account_number ?? '-' }}</dd>
                        </div>
                        <div class="flex flex-col col-span-2 px-4 bg-gray-50 rounded-xl p-4">
                            <dt class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1 text-nowrap">Nama Pemilik Rekening</dt>
                            <dd class="font-semibold text-gray-800">{{ Auth::user()->person->payroll_bank_account_name ?? '-' }}</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 p-8 shadow-sm">
                    <h4 class="text-sm font-bold text-indigo-600 mb-8 uppercase tracking-widest flex items-center border-b border-amber-50 pb-4">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                        </svg>
                        Media Sosial
                    </h4>
                    <div class="space-y-4">
                        @php $sosmed = Auth::user()->person ? Auth::user()->person->socialMedia : null; @endphp

                        {{-- FACEBOOK --}}
                        <div class="flex items-center justify-between p-4 bg-white border border-gray-100 rounded-2xl {{ !$sosmed || !$sosmed->facebook_url ? 'opacity-40 grayscale select-none' : 'shadow-sm' }}">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                </div>
                                <span class="ml-3 text-xs font-bold text-gray-700 tracking-widest">Facebook</span>
                            </div>
                            @if($sosmed && $sosmed->facebook_url)
                            <a href="{{ $sosmed->facebook_url }}" target="_blank" class="text-[10px] font-black text-indigo-600 bg-indigo-50 px-4 py-1.5 rounded-full hover:bg-indigo-600 hover:text-white transition-all">Kunjungi</a>
                            @else
                            <span class="text-[10px] font-bold text-gray-300 tracking-widest">Belum Diisi</span>
                            @endif
                        </div>

                        {{-- INSTAGRAM --}}
                        <div class="flex items-center justify-between p-4 bg-white border border-gray-100 rounded-2xl {{ !$sosmed || !$sosmed->instagram_url ? 'opacity-40 grayscale select-none' : 'shadow-sm' }}">
                            <div class="flex items-center">
                                <div class="p-2 bg-pink-50 text-pink-600 rounded-lg">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                    </svg>
                                </div>
                                <span class="ml-3 text-xs font-bold text-gray-700 tracking-widest">Instagram</span>
                            </div>
                            @if($sosmed && $sosmed->instagram_url)
                            <a href="{{ $sosmed->instagram_url }}" target="_blank" class="text-[10px] font-black text-pink-600 bg-pink-50 px-4 py-1.5 rounded-full hover:bg-pink-600 hover:text-white transition-all">Kunjungi</a>
                            @else
                            <span class="text-[10px] font-bold text-gray-300 tracking-widest">Belum Diisi</span>
                            @endif
                        </div>

                        {{-- TIKTOK --}}
                        <div class="flex items-center justify-between p-4 bg-white border border-gray-100 rounded-2xl {{ !$sosmed || !$sosmed->tiktok_url ? 'opacity-40 grayscale select-none' : 'shadow-sm' }}">
                            <div class="flex items-center">
                                <div class="p-2 bg-slate-900 text-white rounded-lg">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-4.17.07-8.33.07-12.5z" />
                                    </svg>
                                </div>
                                <span class="ml-3 text-xs font-bold text-gray-700 tracking-widest">TikTok</span>
                            </div>
                            @if($sosmed && $sosmed->tiktok_url)
                            <a href="{{ $sosmed->tiktok_url }}" target="_blank" class="text-[10px] font-black text-slate-800 bg-slate-100 px-4 py-1.5 rounded-full hover:bg-slate-800 hover:text-white transition-all">Kunjungi</a>
                            @else
                            <span class="text-[10px] font-bold text-gray-300 tracking-widest">Belum Diisi</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- 6. PETA GPS --}}
            <div class="bg-white rounded-xl border border-gray-100 p-8 shadow-sm">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 border-b border-gray-50 pb-4 gap-4">
                    <h4 class="text-sm font-bold text-indigo-600 flex items-center uppercase tracking-wider">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                        </svg>
                        Lokasi Koordinat GPS
                    </h4>
                    @if(Auth::user()->person && Auth::user()->person->latitude_gps_domicile)
                    <a href="https://www.google.com/maps/search/?api=1&query={{ Auth::user()->person->latitude_gps_domicile }},{{ Auth::user()->person->longitude_gps_domicile }}"
                        target="_blank"
                        class="text-[10px] font-black text-indigo-600 hover:underline uppercase tracking-widest">Buka di Maps</a>
                    @endif
                </div>

                <div id="map-preview" class="h-96 rounded-2xl border-4 border-white shadow-lg ring-1 ring-gray-100 z-0 mb-6"></div>

                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse ring-4 ring-emerald-100"></div>
                        <span class="text-[11px] font-bold text-slate-400 tracking-widest leading-none">Titik Koordinat GPS</span>
                    </div>
                    <div class="flex gap-4 text-[11px]">
                        <span class="text-indigo-600 font-semibold px-4 py-2 bg-white rounded-xl shadow-sm border border-indigo-50">Lat: {{ Auth::user()->person->latitude_gps_domicile ?? '-' }}</span>
                        <span class="text-indigo-600 font-semibold px-4 py-2 bg-white rounded-xl shadow-sm border border-indigo-50">Lon: {{ Auth::user()->person->longitude_gps_domicile ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lat = parseFloat("{{ Auth::user()->person->latitude_gps_domicile ?? 0 }}");
            const lng = parseFloat("{{ Auth::user()->person->longitude_gps_domicile ?? 0 }}");
            const mapContainer = document.getElementById('map-preview');

            if (lat !== 0 && lng !== 0) {
                const map = L.map('map-preview', {
                    zoomControl: true,
                    attributionControl: false
                }).setView([lat, lng], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                L.marker([lat, lng]).addTo(map);
            } else {
                mapContainer.innerHTML =
                    '<div class="flex flex-col items-center justify-center h-full text-gray-300 space-y-2 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-100"><svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg><span class="text-xs font-bold uppercase tracking-widest italic text-center px-4">Data koordinat lokasi domisili belum tersedia pada sistem</span></div>';
            }
        });
    </script>
</x-app-layout>