<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SPPI Buleleng | Lengkapi Profil</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        .drag-over {
            border-color: #4f46e5 !important;
            background-color: #f5f3ff !important;
        }

        #cropperModal {
            background-color: rgba(0, 0, 0, 0.8);
        }

        .is-invalid {
            border: 2px solid #ef4444 !important;
            --tw-ring-color: #ef4444 !important;
        }

        #map {
            height: 300px;
            width: 100%;
            border-radius: 0.75rem;
            margin-top: 0.5rem;
            z-index: 1;
        }

        .sticky-footer {
            position: sticky;
            bottom: 0;
            background-color: white;
            padding-top: 1rem;
            padding-bottom: 0.5rem;
            z-index: 50;
            border-top: 1px solid #f3f4f6;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .input-disabled {
            background-color: #f8fafc !important;
            color: #94a3b8 !important;
            cursor: not-allowed !important;
            pointer-events: none;
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-50 overflow-x-hidden">
    <div class="min-h-screen flex items-center justify-center p-4">

        <div class="flex flex-col md:flex-row w-full max-w-7xl rounded-2xl overflow-hidden shadow-2xl border border-gray-100 bg-white md:max-h-[95vh]">

            <div class="w-full md:w-1/3 bg-darkblue p-8 flex flex-col items-center justify-center text-center text-white relative overflow-hidden">
                <div class="absolute top-0 left-0 w-24 h-24 bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 right-0 w-32 h-32 bg-white/5 rounded-full translate-x-1/4 translate-y-1/4"></div>

                <div class="z-10 w-full">
                    <div class="flex items-center justify-center gap-3 mb-6 bg-white/10 p-3 rounded-xl backdrop-blur-sm inline-flex mx-auto">
                        <img src="{{ asset('assets/images/logo-bgn-circle.png') }}" alt="Logo BGN" class="h-12 w-auto object-contain">
                        <div class="h-8 w-px bg-white/20"></div>
                        <img src="{{ asset('assets/images/logo-sppi.png') }}" alt="Logo SPPI" class="h-12 w-auto object-contain">
                    </div>

                    <h1 class="text-xl md:text-2xl font-bold leading-tight mb-4">
                        Lengkapi Profil <br>
                        <span class="text-blue-300 text-lg">SPPI Buleleng</span>
                    </h1>

                    <p class="text-gray-300 text-xs leading-relaxed px-4">
                        Mohon lengkapi seluruh data untuk keperluan verifikasi akun oleh sistem
                    </p>

                    <div class="mt-8 hidden md:block">
                        <div class="flex items-center gap-3 text-left text-[11px] text-blue-200 mb-2">
                            <div class="w-5 h-5 rounded-full bg-blue-500/20 flex items-center justify-center">1</div>
                            <span>Penempatan & Status Kerja</span>
                        </div>
                        <div class="flex items-center gap-3 text-left text-[11px] text-blue-200 mb-2">
                            <div class="w-5 h-5 rounded-full bg-blue-500/20 flex items-center justify-center">2</div>
                            <span>Informasi Payroll</span>
                        </div>
                        <div class="flex items-center gap-3 text-left text-[11px] text-blue-200 mb-2">
                            <div class="w-5 h-5 rounded-full bg-blue-500/20 flex items-center justify-center">3</div>
                            <span>Identitas & Pendidikan</span>
                        </div>
                        <div class="flex items-center gap-3 text-left text-[11px] text-blue-200 mb-2">
                            <div class="w-5 h-5 rounded-full bg-blue-500/20 flex items-center justify-center">4</div>
                            <span>Informasi Pribadi & Seragam</span>
                        </div>
                        <div class="flex items-center gap-3 text-left text-[11px] text-blue-200 mb-2">
                            <div class="w-5 h-5 rounded-full bg-blue-500/20 flex items-center justify-center">5</div>
                            <span>Alamat Sesuai KTP</span>
                        </div>
                        <div class="flex items-center gap-3 text-left text-[11px] text-blue-200 mb-2">
                            <div class="w-5 h-5 rounded-full bg-blue-500/20 flex items-center justify-center">6</div>
                            <span>Alamat Domisili & GPS</span>
                        </div>
                        <div class="flex items-center gap-3 text-left text-[11px] text-blue-200 mb-2">
                            <div class="w-5 h-5 rounded-full bg-blue-500/20 flex items-center justify-center">7</div>
                            <span>Media Sosial</span>
                        </div>
                        <div class="flex items-center gap-3 text-left text-[11px] text-blue-200">
                            <div class="w-5 h-5 rounded-full bg-blue-500/20 flex items-center justify-center">8</div>
                            <span>Pas Foto</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full md:w-2/3 bg-white p-6 md:p-10 overflow-y-auto">

                @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                    <h3 class="text-xs font-bold text-red-800 uppercase tracking-wider">Mohon Perbaiki:</h3>
                    <ul class="mt-2 text-[11px] text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
                @endif

                <form id="profileForm" method="POST" action="{{ route('profile.store') }}" enctype="multipart/form-data" class="space-y-8" novalidate>
                    @csrf

                    {{-- SECTION 1: PENEMPATAN & STATUS KERJA --}}
                    <section>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="p-1.5 bg-indigo-50 text-darkblue rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </span>
                            <h3 class="font-bold text-darkblue uppercase text-xs tracking-widest">Penempatan & Status Kerja</h3>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Unit Penugasan (Sesuai SK)</label>
                                <select name="id_work_assignment" id="id_work_assignment" required class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field">
                                    <option value="" disabled selected>Pilih Unit Penugasan</option>
                                    <option value="none" {{ old('id_work_assignment', $user->person?->id_work_assignment) == null ? 'selected' : '' }}>Belum Penugasan</option>
                                    @foreach($workAssignments as $wa)
                                    <option value="{{ $wa->id_work_assignment }}" {{ old('id_work_assignment', $user->person?->id_work_assignment) == $wa->id_work_assignment ? 'selected' : '' }}>
                                        {{ $wa->sppgUnit->name }} - {{ $wa->decree->no_sk }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- TAMBAHAN: PILIH JABATAN --}}
                            <div class="sm:col-span-2">
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Jabatan Sekarang</label>
                                <select name="id_ref_position" id="id_ref_position" required class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field">
                                    <option value="" disabled selected>Pilih Jabatan</option>
                                    {{-- Menggunakan value "none" agar sinkron dengan logic Controller kita sebelumnya --}}
                                    <option value="none" {{ old('id_ref_position', $user->person?->id_ref_position) == null ? 'selected' : '' }}>Belum Menjabat</option>
                                    @foreach($positions as $pos)
                                    <option value="{{ $pos->id_ref_position }}" {{ old('id_ref_position', $user->person?->id_ref_position) == $pos->id_ref_position ? 'selected' : '' }}>
                                        {{ $pos->name_position }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Batch</label>
                                <select name="batch" id="batch" required class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field">
                                    <option value="" disabled selected>Pilih Batch</option>
                                    @foreach(['1', '2', '3', 'Non-SPPI'] as $b)
                                    <option value="{{ $b }}" {{ old('batch', $user->person?->batch) == $b ? 'selected' : '' }}>{{ $b }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Status Kerja</label>
                                <select name="employment_status" id="employment_status" required class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field">
                                    <option value="" disabled selected>Pilih Status Kerja</option>
                                    <option value="ASN" {{ old('employment_status', $user->person?->employment_status) == 'ASN' ? 'selected' : '' }}>ASN</option>
                                    <option value="Non-ASN" {{ old('employment_status', $user->person?->employment_status) == 'Non-ASN' ? 'selected' : '' }}>Non-ASN</option>
                                </select>
                            </div>
                        </div>
                    </section>

                    {{-- SECTION 2: INFORMASI PAYROLL --}}
                    <section>
                        <div class="flex items-center gap-2 mb-4 pt-4 border-t border-gray-100">
                            <span class="p-1.5 bg-indigo-50 text-darkblue rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </span>
                            <h3 class="font-bold text-darkblue uppercase text-xs tracking-widest">Informasi Payroll</h3>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Nama Bank</label>
                                <select name="payroll_bank_name" id="payroll_bank_name" required class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field">
                                    <option value="" disabled selected>Pilih Bank</option>
                                    @foreach(['BNI', 'Mandiri', 'BCA', 'BTN', 'BSI', 'BPD Bali'] as $bank)
                                    <option value="{{ $bank }}" {{ old('payroll_bank_name') == $bank ? 'selected' : '' }}>{{ $bank }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Nomor Rekening</label>
                                <input type="number" name="payroll_bank_account_number" id="payroll_bank_account_number" value="{{ old('payroll_bank_account_number') }}" required class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field" placeholder="Sesuai Buku Tabungan">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Nama Rekening</label>
                                <input type="text" name="payroll_bank_account_name" id="payroll_bank_account_name" value="{{ old('payroll_bank_account_name') }}" required class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field" placeholder="Sesuai Buku Tabungan">
                            </div>

                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4">
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">No. BPJS Kesehatan</label>
                                <input type="number" name="no_bpjs_kes" id="no_bpjs_kes" value="{{ old('no_bpjs_kes') }}" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist" placeholder="Masukkan No. BPJS Kesehatan">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">No. BPJS Ketenagakerjaan</label>
                                <input type="number" name="no_bpjs_tk" id="no_bpjs_tk" value="{{ old('no_bpjs_tk') }}" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist" placeholder="Masukkan No. BPJS Ketenagakerjaan">
                            </div>
                        </div>
                    </section>

                    {{-- SECTION 3: IDENTITAS & PENDIDIKAN --}}
                    <section>
                        <div class="flex items-center gap-2 mb-4 pt-4 border-t border-gray-100">
                            <span class="p-1.5 bg-blue-50 text-darkblue rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 012-2h4a2 2 0 012 2v2m-6 0h6"></path>
                                </svg>
                            </span>
                            <h3 class="font-bold text-darkblue uppercase text-xs tracking-widest">Identitas & Pendidikan</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">NIK</label>
                                <input id="nik" type="number" name="nik" value="{{ old('nik') }}" required minlength="16" maxlength="16" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field" placeholder="Masukkan NIK">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Nomor KK</label>
                                <input id="no_kk" type="number" name="no_kk" value="{{ old('no_kk') }}" required minlength="16" maxlength="16" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field" placeholder="Masukkan No. KK">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">NIP</label>
                                <input id="nip" type="number" name="nip" value="{{ old('nip') }}" required class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field" placeholder="Masukkan NIP">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">NPWP</label>
                                <input id="npwp" type="number" name="npwp" value="{{ old('npwp') }}" required class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field" placeholder="Masukkan NPWP">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Pendidikan Terakhir</label>
                                <select name="last_education" id="last_education" required class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field">
                                    <option value="" disabled selected>Pilih Pendidikan Terakhir</option>
                                    @foreach(['D-III', 'D-IV', 'S-1'] as $edu)
                                    <option value="{{ $edu }}" {{ old('last_education') == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Gelar Belakang</label>
                                <input id="title_education" type="text" name="title_education" value="{{ old('title_education') }}" required class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field" placeholder="Cth: S.Kom.">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Lulusan Program Studi</label>
                                <input id="major_education" type="text" name="major_education" value="{{ old('major_education') }}" required class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field" placeholder="Cth: Ilmu Kelautan">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Nama Lengkap (Sesuai KTP)</label>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field" placeholder="Masukkan Nama Tanpa Gelar">
                            </div>
                        </div>
                    </section>

                    {{-- SECTION 4: INFO PRIBADI & SERAGAM --}}
                    <section>
                        <div class="flex items-center gap-2 mb-4 pt-4 border-t border-gray-100">
                            <span class="p-1.5 bg-blue-50 text-darkblue rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </span>
                            <h3 class="font-bold text-darkblue uppercase text-xs tracking-widest">Informasi Pribadi & Seragam</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Jenis Kelamin</label>
                                <select name="gender" id="gender" required class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field">
                                    <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Agama</label>
                                <select name="religion" id="religion" required class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field">
                                    <option value="" disabled selected>Pilih Agama</option>
                                    @foreach(['Islam', 'Kristen', 'Katholik', 'Hindu', 'Buddha', 'Khonghucu'] as $rel)
                                    <option value="{{ $rel }}" {{ old('religion') == $rel ? 'selected' : '' }}>{{ $rel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Status Pernikahan</label>
                                <select name="marital_status" id="marital_status" required class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field">
                                    <option value="" disabled selected>Pilih Status Pernikahan</option>
                                    @foreach(['Belum Kawin', 'Kawin', 'Janda', 'Duda'] as $status)
                                    <option value="{{ $status }}" {{ old('marital_status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Tanggal Lahir</label>
                                <input id="date_birthday" type="date" name="date_birthday" value="{{ old('date_birthday') }}" required class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Umur</label>
                                <input id="age" type="number" name="age" value="{{ old('age') }}" readonly class="w-full mt-2 px-4 py-2.5 bg-gray-100 border-none rounded-lg text-sm focus:ring-0 cursor-not-allowed" placeholder="0">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Tempat Lahir</label>
                                <input id="place_birthday" type="text" name="place_birthday" value="{{ old('place_birthday') }}" required class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field" placeholder="Sesuai KTP">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4">
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Ukuran Baju</label>
                                <select name="clothing_size" id="clothing_size" required class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 persist validate-field">
                                    <option value="" disabled selected>Pilih Ukuran Baju</option>
                                    @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'XXXXL', '4XL', '5XL', '6XL', '7XL', '8XL', '9XL', '10XL'] as $size)
                                    <option value="{{ $size }}" {{ old('clothing_size') == $size ? 'selected' : '' }}>{{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Ukuran Sepatu</label>
                                <select name="shoe_size" id="shoe_size" required class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 persist validate-field">
                                    <option value="" disabled selected>Pilih Ukuran Sepatu</option>
                                    @for($i = 35; $i <= 50; $i++)
                                        <option value="{{ $i }}" {{ old('shoe_size') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                </select>
                            </div>
                        </div>
                    </section>

                    {{-- SECTION 5: ALAMAT & GPS --}}
                    {{-- SECTION ALAMAT KTP --}}
                    <section>
                        <div class="flex items-center gap-2 mb-4 pt-4 border-t border-gray-100">
                            <span class="p-1.5 bg-blue-50 text-darkblue rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </span>
                            <h3 class="font-bold text-darkblue uppercase text-xs tracking-widest">Alamat KTP</h3>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Provinsi</label>
                                <select name="province_ktp" id="province_ktp" required class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm persist validate-field"></select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Kabupaten</label>
                                <select name="regency_ktp" id="regency_ktp" required disabled class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm persist validate-field"></select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Kecamatan</label>
                                <select name="district_ktp" id="district_ktp" required disabled class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm persist validate-field"></select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Desa/Kelurahan</label>
                                <select name="village_ktp" id="village_ktp" required disabled class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm persist validate-field"></select>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Alamat Jalan/Rumah</label>
                                <textarea required name="address_ktp" id="address_ktp" rows="2" placeholder="Cth: Jl. Utama No. 1" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm persist validate-field"></textarea>
                            </div>
                        </div>
                    </section>

                    {{-- SECTION ALAMAT DOMISILI --}}
                    <section>
                        <div class="flex items-center justify-between gap-2 mb-4 pt-4 border-t border-gray-100">
                            <div class="flex items-center gap-2">
                                <span class="p-1.5 bg-blue-50 text-darkblue rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                </span>
                                <h3 class="font-bold text-darkblue uppercase text-xs tracking-widest">Alamat Domisili & GPS</h3>
                            </div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="same_as_ktp" class="w-4 h-4 text-darkblue border-gray-300 rounded focus:ring-darkblue">
                                <span class="text-[10px] font-bold text-gray-500 uppercase">Gunakan Alamat KTP</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <!-- PROVINSI -->
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Provinsi</label>
                                <select
                                    name="province_domicile"
                                    id="province_domicile"
                                    required
                                    class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm persist validate-field dom-input">
                                    <option value="" disabled selected>Pilih Provinsi</option>
                                </select>
                            </div>

                            <!-- KABUPATEN -->
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Kabupaten</label>
                                <select
                                    name="regency_domicile"
                                    id="regency_domicile"
                                    required
                                    class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm persist validate-field dom-input"
                                    disabled>
                                    <option value="" disabled selected>Pilih Kabupaten</option>
                                </select>
                            </div>

                            <!-- KECAMATAN -->
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Kecamatan</label>
                                <select
                                    name="district_domicile"
                                    id="district_domicile"
                                    required
                                    class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm persist validate-field dom-input"
                                    disabled>
                                    <option value="" disabled selected>Pilih Kecamatan</option>
                                </select>
                            </div>

                            <!-- DESA -->
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Desa / Kelurahan</label>
                                <select
                                    name="village_domicile"
                                    id="village_domicile"
                                    required
                                    class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm persist validate-field dom-input"
                                    disabled>
                                    <option value="" disabled selected>Pilih Desa / Kelurahan</option>
                                </select>
                            </div>

                            <!-- ALAMAT -->
                            <div class="sm:col-span-2">
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Alamat Jalan / Rumah</label>
                                <textarea
                                    name="address_domicile"
                                    id="address_domicile"
                                    rows="2"
                                    required
                                    class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm persist validate-field dom-input"
                                    placeholder="Contoh: Jl. Raya Sukasada No. 12"></textarea>
                            </div>

                            <!-- GPS -->
                            <div class="sm:col-span-2">
                                <div class="flex gap-2 mb-2">
                                    <input
                                        id="latitude_gps_domicile"
                                        type="text"
                                        name="latitude_gps_domicile"
                                        readonly
                                        required
                                        class="w-1/2 px-4 py-2.5 bg-blue-50 text-blue-700 font-mono text-xs border-none rounded-lg validate-field persist"
                                        placeholder="Latitude">

                                    <input
                                        id="longitude_gps_domicile"
                                        type="text"
                                        name="longitude_gps_domicile"
                                        readonly
                                        required
                                        class="w-1/2 px-4 py-2.5 bg-blue-50 text-blue-700 font-mono text-xs border-none rounded-lg validate-field persist"
                                        placeholder="Longitude">
                                </div>

                                <div
                                    id="map"
                                    style="height:300px;width:100%;border-radius:12px;border:2px solid #f3f4f6;">
                                </div>
                            </div>

                        </div>
                    </section>

                    {{-- SECTION 5.5: SOSIAL MEDIA (TAMBAHAN) --}}
                    <section>
                        <div class="flex items-center gap-2 mb-4 pt-4 border-t border-gray-100">
                            <span class="p-1.5 bg-indigo-50 text-darkblue rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                            </span>
                            <h3 class="font-bold text-darkblue uppercase text-xs tracking-widest">Media Sosial</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Facebook URL</label>
                                <input type="url" name="facebook_url" id="facebook_url" value="{{ old('facebook_url') }}"
                                    class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist"
                                    placeholder="https://facebook.com/..">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Instagram URL</label>
                                <input type="url" name="instagram_url" id="instagram_url" value="{{ old('instagram_url') }}"
                                    class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist"
                                    placeholder="https://instagram.com/..">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">TikTok URL</label>
                                <input type="url" name="tiktok_url" id="tiktok_url" value="{{ old('tiktok_url') }}"
                                    class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist"
                                    placeholder="https://tiktok.com/@..">
                            </div>
                        </div>
                    </section>

                    {{-- SECTION 6: PAS FOTO --}}
                    <section class="pb-6">
                        <div class="flex items-center gap-2 mb-4 pt-4 border-t border-gray-100">
                            <span class="p-1.5 bg-blue-50 text-darkblue rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </span>
                            <h3 class="font-bold text-darkblue uppercase text-xs tracking-widest">Pas Foto</h3>
                        </div>

                        <div id="drop-area" class="flex flex-col items-center bg-gray-50 rounded-2xl p-6 border-2 border-dashed border-gray-200 transition-all">
                            <div id="preview-container" class="mb-4 hidden text-center">
                                <img id="cropped-preview"
                                    class="w-32 h-48 object-cover rounded-xl shadow-md border-4 border-white mx-auto cursor-pointer hover:opacity-80 transition-all"
                                    title="Klik untuk potong ulang foto">
                                <p class="text-[10px] text-gray-400 mt-2 font-semibold uppercase tracking-wider italic">
                                    * Klik foto untuk potong ulang
                                </p>
                            </div>
                            <label for="photo" class="flex flex-col items-center justify-center cursor-pointer group">
                                <svg class="w-10 h-10 mb-2 text-gray-400 group-hover:text-darkblue transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-[11px] text-gray-500 font-medium">Klik atau seret foto ke sini</p>
                                <input id="photo" name="photo" type="file" class="hidden validate-field" accept="image/*" required />
                            </label>
                        </div>
                    </section>

                    <div class="sticky-footer">
                        <button type="submit" class="w-full bg-darkblue text-white py-4 rounded-xl font-bold text-sm shadow-xl hover:bg-gold active:scale-[0.98] transition-all cursor-pointer">
                            SIMPAN PROFIL
                        </button>
                    </div>

                    <div class="mt-8 md:mt-auto pt-8 pb-4 text-center">
                        <div class="text-xs text-gray-400 border-t pt-4">
                            <p>© {{ now()->format('Y') }} - Tim Data SPPI Buleleng Bali</p>
                            <p class="italic mt-1">Bagimu Negeri Jiwa Raga Kami</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL CROPPER --}}
    <div id="cropperModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden">
            <div class="p-6 border-b bg-gray-50">
                <h3 class="text-xl font-bold text-darkblue">Potong Pas Foto</h3>
            </div>
            <div class="p-6 bg-gray-200 flex justify-center">
                <div class="max-w-full max-h-[60vh]"><img id="image-to-crop" class="block max-w-full"></div>
            </div>
            <div class="p-6 border-t flex justify-end space-x-3">
                <button id="cancel-crop" type="button" class="px-6 py-2 font-bold text-gray-500 cursor-pointer hover:text-gray-400 transition-colors">Batal</button>
                <button id="apply-crop" type="button" class="px-8 py-2 bg-darkblue text-white font-bold rounded-lg shadow-lg cursor-pointer hover:bg-gold transition-colors">Gunakan</button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- 1. GLOBAL VARIABLES & ELEMENTS ---
            const form = document.getElementById('profileForm');
            const apiBase = "/api-wilayah";
            const syncBtn = document.getElementById('same_as_ktp');

            // Elements for Photo/Cropper
            let cropper;
            const photoInput = document.getElementById('photo');
            const imageToCrop = document.getElementById('image-to-crop');
            const previewImg = document.getElementById('cropped-preview');
            const previewContainer = document.getElementById('preview-container');
            const cropperModal = document.getElementById('cropperModal');
            const dropArea = document.getElementById('drop-area');
            let originalImageDataUrl = null;

            // Elements for Map
            let mapObj, markerObj;
            const latField = document.getElementById('latitude_gps_domicile');
            const lngField = document.getElementById('longitude_gps_domicile');

            // Elements for Age
            const dateBirthdayInput = document.getElementById('date_birthday');
            const ageInput = document.getElementById('age');

            const addressFields = ['province', 'regency', 'district', 'village', 'address'];
            const levels = ['province', 'regency', 'district', 'village'];
            const labels = ['Provinsi', 'Kabupaten', 'Kecamatan', 'Desa/Kelurahan'];
            const paths = {
                'province': 'regencies',
                'regency': 'districts',
                'district': 'villages'
            };

            // --- 2. PHOTO CROPPER LOGIC ---
            function handleFiles(file) {
                if (!file || !file.type.startsWith('image/')) {
                    alert('Mohon unggah file gambar saja.');
                    return;
                }
                const reader = new FileReader();
                reader.onload = (e) => {
                    originalImageDataUrl = e.target.result;
                    imageToCrop.src = originalImageDataUrl;
                    cropperModal.classList.remove('hidden');
                    cropperModal.style.setProperty('display', 'flex', 'important');
                    cropperModal.style.zIndex = '9999';

                    if (cropper) cropper.destroy();
                    setTimeout(() => {
                        cropper = new Cropper(imageToCrop, {
                            aspectRatio: 2 / 3,
                            viewMode: 2,
                            autoCropArea: 1,
                            responsive: true,
                            checkOrientation: false
                        });
                    }, 200);
                };
                reader.readAsDataURL(file);
            }

            photoInput.addEventListener('change', (e) => handleFiles(e.target.files[0]));

            // Drag and Drop
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eName => {
                dropArea.addEventListener(eName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                });
            });
            dropArea.addEventListener('drop', (e) => handleFiles(e.dataTransfer.files[0]));

            document.getElementById('apply-crop').addEventListener('click', function() {
                if (!cropper) return;
                const canvas = cropper.getCroppedCanvas({
                    width: 400,
                    height: 600
                });
                canvas.toBlob((blob) => {
                    const croppedFile = new File([blob], `crop_${Date.now()}.jpg`, {
                        type: "image/jpeg"
                    });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(croppedFile);
                    photoInput.files = dataTransfer.files;

                    previewImg.src = URL.createObjectURL(croppedFile);
                    previewContainer.classList.remove('hidden');
                    closeCropper();
                    clearError(photoInput);
                }, 'image/jpeg', 0.9);
            });

            const closeCropper = () => {
                cropperModal.classList.add('hidden');
                cropperModal.style.display = 'none';
            };

            document.getElementById('cancel-crop').addEventListener('click', () => {
                closeCropper();
                if (!previewImg.src) photoInput.value = "";
            });

            previewImg.addEventListener('click', () => {
                if (originalImageDataUrl) {
                    imageToCrop.src = originalImageDataUrl;
                    cropperModal.classList.remove('hidden');
                    cropperModal.style.display = 'flex';
                }
            });

            // --- 3. MAP & GPS LOGIC ---
            function initMap() {
                const savedLat = localStorage.getItem('profile_latitude_gps_domicile');
                const savedLng = localStorage.getItem('profile_longitude_gps_domicile');
                const initialLat = savedLat ? parseFloat(savedLat) : -8.1127;
                const initialLon = savedLng ? parseFloat(savedLng) : 115.0911;

                mapObj = L.map('map').setView([initialLat, initialLon], savedLat ? 15 : 11);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapObj);

                if (savedLat && savedLng) placeMarker(initialLat, initialLon, false);

                mapObj.on('click', (e) => placeMarker(e.latlng.lat, e.latlng.lng, true));
            }

            function placeMarker(lat, lon, updateStorage = true) {
                if (markerObj) markerObj.setLatLng([lat, lon]);
                else {
                    markerObj = L.marker([lat, lon], {
                        draggable: true
                    }).addTo(mapObj);
                    markerObj.on('dragend', () => {
                        const pos = markerObj.getLatLng();
                        updateGpsFields(pos.lat, pos.lng);
                    });
                }
                if (updateStorage) updateGpsFields(lat, lon);
                else {
                    latField.value = lat.toFixed(8);
                    lngField.value = lon.toFixed(8);
                }
            }

            function updateGpsFields(lat, lon) {
                const fLat = lat.toFixed(8);
                const fLng = lon.toFixed(8);
                latField.value = fLat;
                lngField.value = fLng;
                localStorage.setItem('profile_latitude_gps_domicile', fLat);
                localStorage.setItem('profile_longitude_gps_domicile', fLng);
                clearError(latField);
            }

            async function autoMoveMap() {
                const getVal = (id) => {
                    const el = document.getElementById(id);
                    return (el && el.selectedIndex > 0) ? el.options[el.selectedIndex].text : '';
                };
                const query = `${getVal('village_domicile')} ${getVal('district_domicile')} ${getVal('regency_domicile')}`.trim();
                if (query.length < 5) return;
                try {
                    const resp = await fetch(`/api-map-search?q=${encodeURIComponent(query)}`);
                    const data = await resp.json();
                    if (data && data.length > 0) mapObj.setView([data[0].lat, data[0].lon], 14);
                } catch (e) {
                    console.error("Map search error", e);
                }
            }

            // --- 4. REGION DROPDOWN & SYNC LOGIC ---
            async function populateSelect(id, path, placeholder, targetVal = null) {
                const el = document.getElementById(id);
                if (!el) return null;

                const valueToMatch = targetVal || localStorage.getItem('profile_' + id);
                el.innerHTML = '<option value="">Mohon tunggu...</option>';

                try {
                    const resp = await fetch(`${apiBase}/${path}`);
                    const result = await resp.json();
                    let options = `<option value="">${placeholder}</option>`;
                    result.data.forEach(item => {
                        let name = item.name.replace(/^(KABUPATEN|KOTA)\s+/i, "");
                        options += `<option value="${name}" data-code="${item.code}">${name}</option>`;
                    });
                    el.innerHTML = options;
                    el.disabled = false;

                    if (valueToMatch) {
                        for (let i = 0; i < el.options.length; i++) {
                            if (el.options[i].value.toUpperCase() === valueToMatch.toUpperCase()) {
                                el.selectedIndex = i;
                                return el.options[i].getAttribute('data-code');
                            }
                        }
                    }
                } catch (e) {
                    el.innerHTML = '<option value="">Gagal memuat</option>';
                }
                return null;
            }

            function bindWilayahEvents(p) {
                levels.forEach((lv, idx) => {
                    const el = document.getElementById(`${lv}_${p}`);
                    el.addEventListener('change', async function() {
                        const code = this.options[this.selectedIndex]?.getAttribute('data-code');
                        localStorage.setItem('profile_' + this.id, this.value);

                        // SYNC Logic
                        if (p === 'ktp' && syncBtn.checked) {
                            const domEl = document.getElementById(`${lv}_domicile`);
                            localStorage.setItem('profile_' + domEl.id, this.value);
                            // Inject option if not exist
                            if (![...domEl.options].some(o => o.value === this.value)) {
                                const opt = new Option(this.value, this.value, true, true);
                                domEl.add(opt);
                            }
                            domEl.value = this.value;
                        }

                        // Load next level
                        if (idx < levels.length - 1) {
                            const next = levels[idx + 1];
                            // Reset children
                            levels.slice(idx + 1).forEach(l => {
                                const target = document.getElementById(`${l}_${p}`);
                                target.innerHTML = `<option value="">Pilih ${labels[levels.indexOf(l)]}</option>`;
                                target.disabled = true;
                                localStorage.removeItem('profile_' + target.id);
                                if (p === 'ktp' && syncBtn.checked) {
                                    const dTarget = document.getElementById(`${l}_domicile`);
                                    dTarget.innerHTML = target.innerHTML;
                                    dTarget.disabled = true;
                                    localStorage.removeItem('profile_' + dTarget.id);
                                }
                            });

                            if (code) {
                                const nextPath = `${paths[lv]}/${code}.json`;
                                const nextLabel = `Pilih ${labels[idx+1]}`;
                                await populateSelect(`${next}_${p}`, nextPath, nextLabel);
                                if (p === 'ktp' && syncBtn.checked) {
                                    await populateSelect(`${next}_domicile`, nextPath, nextLabel);
                                    document.getElementById(`${next}_domicile`).disabled = true;
                                }
                            }
                        }
                        autoMoveMap();
                    });
                });
            }

            syncBtn.addEventListener('change', async function() {
                const isChecked = this.checked;
                localStorage.setItem('profile_same_as_ktp', isChecked);

                for (const f of addressFields) {
                    const ktpEl = document.getElementById(`${f}_ktp`);
                    const domEl = document.getElementById(`${f}_domicile`);
                    if (isChecked) {
                        domEl.value = ktpEl.value;
                        domEl.classList.add('input-disabled');
                        if (f === 'address') domEl.readOnly = true;
                        else domEl.disabled = true;
                        localStorage.setItem('profile_' + domEl.id, ktpEl.value);
                    } else {
                        domEl.classList.remove('input-disabled');
                        domEl.disabled = false;
                        domEl.readOnly = false;
                    }
                }
                if (isChecked) {
                    // Re-trigger chains to ensure dropdowns are populated
                    restoreChains('domicile', true);
                }
            });

            // Manual Address input sync
            document.getElementById('address_ktp').addEventListener('input', function() {
                localStorage.setItem('profile_address_ktp', this.value);
                if (syncBtn.checked) {
                    const dom = document.getElementById('address_domicile');
                    dom.value = this.value;
                    localStorage.setItem('profile_address_domicile', this.value);
                }
            });

            // --- 5. AGE & PERSISTENCE ---
            function updateAge() {
                if (!dateBirthdayInput.value) return;
                const birth = new Date(dateBirthdayInput.value);
                const today = new Date();
                let age = today.getFullYear() - birth.getFullYear();
                if (today.getMonth() < birth.getMonth() || (today.getMonth() === birth.getMonth() && today.getDate() < birth.getDate())) age--;
                const finalAge = age < 0 ? 0 : age;
                ageInput.value = finalAge;
                localStorage.setItem('profile_age', finalAge);
            }
            dateBirthdayInput.addEventListener('change', updateAge);

            function initPersistence() {
                document.querySelectorAll('.persist').forEach(field => {
                    const saved = localStorage.getItem('profile_' + (field.id || field.name));
                    if (saved !== null) field.value = saved;
                    field.addEventListener('input', () => {
                        localStorage.setItem('profile_' + (field.id || field.name), field.value);
                        if (field.value.trim() !== "") clearError(field);
                    });
                });
            }

            // --- 6. INITIALIZATION & RESTORE ---
            async function restoreChains(suffix = 'ktp', forceSync = false) {
                let code = await populateSelect(`province_${suffix}`, 'provinces.json', "Pilih Provinsi");
                if (code) {
                    code = await populateSelect(`regency_${suffix}`, `regencies/${code}.json`, "Pilih Kabupaten");
                    if (code) {
                        code = await populateSelect(`district_${suffix}`, `districts/${code}.json`, "Pilih Kecamatan");
                        if (code) await populateSelect(`village_${suffix}`, `villages/${code}.json`, "Pilih Desa/Kelurahan");
                    }
                }
            }

            // Main Init
            initPersistence();
            initMap();
            bindWilayahEvents('ktp');
            bindWilayahEvents('domicile');

            (async () => {
                await restoreChains('ktp');
                const isSync = localStorage.getItem('profile_same_as_ktp') === 'true';
                if (isSync) {
                    syncBtn.checked = true;
                    syncBtn.dispatchEvent(new Event('change'));
                } else {
                    await restoreChains('domicile');
                }
                updateAge();
            })();

            // --- 7. VALIDATION & SUBMIT ---
            function showError(field, message) {
                clearError(field);
                field.classList.add('is-invalid');
                if (field.id === 'photo') dropArea.classList.add('is-invalid');
                const err = document.createElement('p');
                err.className = 'error-msg text-red-500 text-[10px] mt-1 font-bold italic';
                err.innerText = `* ${message}`;
                field.closest('div').appendChild(err);
            }

            function clearError(field) {
                field.classList.remove('is-invalid');
                if (field.id === 'photo') dropArea.classList.remove('is-invalid');
                const old = field.closest('div').querySelector('.error-msg');
                if (old) old.remove();
            }

            form.addEventListener('submit', function(e) {
                let isFormValid = true;

                // Unlock for submission
                form.querySelectorAll(':disabled').forEach(el => el.disabled = false);

                document.querySelectorAll('.validate-field').forEach(field => {
                    const val = field.value.trim();
                    if (field.type === 'file' && field.files.length === 0) {
                        showError(field, 'Foto wajib diunggah');
                        isFormValid = false;
                    } else if (!val) {
                        showError(field, 'Wajib diisi');
                        isFormValid = false;
                    } else if ((field.id === 'nik' || field.id === 'no_kk') && val.length !== 16) {
                        showError(field, 'Harus 16 digit');
                        isFormValid = false;
                    }
                });

                if (!latField.value) {
                    showError(latField, 'Pilih lokasi di peta');
                    isFormValid = false;
                }

                if (!isFormValid) {
                    e.preventDefault();
                    // Relock if sync was on
                    if (syncBtn.checked) {
                        addressFields.forEach(f => {
                            if (f !== 'address') document.getElementById(`${f}_domicile`).disabled = true;
                        });
                    }
                }
            });
        });
    </script>
</body>

</html>