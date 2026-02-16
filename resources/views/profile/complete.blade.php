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
                            <span>Alamat & GPS</span>
                        </div>
                        <div class="flex items-center gap-3 text-left text-[11px] text-blue-200">
                            <div class="w-5 h-5 rounded-full bg-blue-500/20 flex items-center justify-center">6</div>
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
                                    @foreach($workAssignments as $wa)
                                    <option value="{{ $wa->id_work_assignment }}" {{ old('id_work_assignment') == $wa->id_work_assignment ? 'selected' : '' }}>
                                        {{ $wa->sppgUnit->name }} - {{ $wa->decree->no_sk }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Batch</label>
                                <select name="batch" id="batch" required class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field">
                                    <option value="" disabled selected>Pilih Batch</option>
                                    @foreach(['1', '2', '3', 'Non-SPPI'] as $b)
                                    <option value="{{ $b }}" {{ old('batch') == $b ? 'selected' : '' }}>{{ $b }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Status Kerja</label>
                                <select name="employment_status" id="employment_status" required class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field">
                                    <option value="" disabled selected>Pilih Status</option>
                                    <option value="ASN" {{ old('employment_status') == 'ASN' ? 'selected' : '' }}>ASN</option>
                                    <option value="Non-ASN" {{ old('employment_status') == 'Non-ASN' ? 'selected' : '' }}>Non-ASN</option>
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
                            <div>
                                <label class="text-[11px] font-bold text-indigo-500 uppercase">Ukuran Baju</label>
                                <select name="clothing_size" id="clothing_size" required class="w-full mt-2 px-3 py-2.5 bg-indigo-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 persist validate-field">
                                    <option value="" disabled selected>Pilih Ukuran Baju</option>
                                    @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'XXXXL', '4XL', '5XL', '6XL', '7XL', '8XL', '9XL', '10XL'] as $size)
                                    <option value="{{ $size }}" {{ old('clothing_size') == $size ? 'selected' : '' }}>{{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-indigo-500 uppercase">Ukuran Sepatu</label>
                                <select name="shoe_size" id="shoe_size" required class="w-full mt-2 px-3 py-2.5 bg-indigo-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 persist validate-field">
                                    <option value="" disabled selected>Pilih Ukuran Sepatu</option>
                                    @for($i = 35; $i <= 50; $i++)
                                        <option value="{{ $i }}" {{ old('shoe_size') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                </select>
                            </div>
                        </div>
                    </section>

                    {{-- SECTION 5: ALAMAT & GPS --}}
                    <section>
                        <div class="flex items-center gap-2 mb-4 pt-4 border-t border-gray-100">
                            <span class="p-1.5 bg-blue-50 text-darkblue rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                            </span>
                            <h3 class="font-bold text-darkblue uppercase text-xs tracking-widest">Alamat & GPS</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Provinsi</label>
                                <select name="province" id="province" required class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field">
                                    <option value="Bali" selected>Bali</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Kabupaten</label>
                                <select name="regency" id="regency" required class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field">
                                    <option value="Buleleng" selected>Buleleng</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Kecamatan</label>
                                <select name="district" id="district" required class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field">
                                    <option value="" disabled selected>Pilih Kecamatan</option>
                                    @foreach(['Tejakula', 'Kubutambahan', 'Sawan', 'Sukasada', 'Buleleng', 'Banjar', 'Seririt', 'Busungbiu', 'Gerokgak'] as $kec)
                                    <option value="{{ $kec }}" {{ old('district') == $kec ? 'selected' : '' }}>{{ $kec }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Desa/Kelurahan</label>
                                <input id="village" type="text" name="village" value="{{ old('village') }}" required class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field" placeholder="Nama Desa/Kelurahan">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="text-[11px] font-bold text-gray-500 uppercase">Alamat Lengkap</label>
                                <textarea id="address" name="address" rows="2" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 persist validate-field" placeholder="Cth: Jl. Singaraja Bedugul No. 04, Br. Dinas Widya Bali">{{ old('address') }}</textarea>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Koordinat GPS (Klik Pada Peta)</label>
                                <input id="gps_coordinates" type="text" name="gps_coordinates" value="{{ old('gps_coordinates') }}" readonly required class="w-full mt-2 px-4 py-2.5 bg-blue-50 text-blue-700 font-mono text-xs border-none rounded-lg focus:ring-0 validate-field" placeholder="Titik akan muncul saat peta diklik">
                                <div id="map"></div>
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
            const form = document.getElementById('profileForm');
            const validateFields = document.querySelectorAll('.validate-field');
            const persistFields = document.querySelectorAll('.persist');
            const dropArea = document.getElementById('drop-area');
            const photoInput = document.getElementById('photo');
            const dateBirthdayInput = document.getElementById('date_birthday');
            const ageInput = document.getElementById('age');

            // --- 1. PERSISTENCE LOGIC ---
            persistFields.forEach(field => {
                const savedValue = localStorage.getItem('profile_' + (field.id || field.name));
                if (savedValue && !field.value) {
                    field.value = savedValue;
                }
                field.addEventListener('input', () => {
                    localStorage.setItem('profile_' + (field.id || field.name), field.value);
                    if (field.value.trim() !== "") clearError(field);
                });
            });

            // --- 2. MAP LOGIC ---
            const coordsField = document.getElementById('gps_coordinates');
            const savedCoords = localStorage.getItem('profile_gps_coordinates');
            let initialCoords = [-8.112, 115.091];
            if (savedCoords && savedCoords.includes(',')) {
                const parts = savedCoords.split(',');
                initialCoords = [parseFloat(parts[0]), parseFloat(parts[1])];
                coordsField.value = savedCoords;
            }

            const map = L.map('map').setView(initialCoords, 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            let marker;

            function placeMarker(latlng) {
                if (marker) map.removeLayer(marker);
                marker = L.marker(latlng).addTo(map);
                map.panTo(latlng);
            }

            if (savedCoords) placeMarker(initialCoords);

            map.on('click', function(e) {
                const {
                    lat,
                    lng
                } = e.latlng;
                coordsField.value = `${lat}, ${lng}`;
                localStorage.setItem('profile_gps_coordinates', coordsField.value);
                placeMarker(e.latlng);
                clearError(coordsField);
            });

            // --- 3. AUTO AGE CALCULATION ---
            function calculateAge(birthDateValue) {
                if (!birthDateValue) return "";
                const birthDate = new Date(birthDateValue);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                return age;
            }

            dateBirthdayInput.addEventListener('change', function() {
                const age = calculateAge(this.value);
                ageInput.value = age;
                localStorage.setItem('profile_age', age);
            });

            // Sync umur saat load/refresh
            const currentBirthDate = localStorage.getItem('profile_date_birthday') || dateBirthdayInput.value;
            if (currentBirthDate) {
                ageInput.value = calculateAge(currentBirthDate);
            }

            // --- 4. DRAG N DROP LOGIC ---
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, e => {
                    e.preventDefault();
                    e.stopPropagation();
                }, false);
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, () => dropArea.classList.add('drag-over'), false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, () => dropArea.classList.remove('drag-over'), false);
            });

            dropArea.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files.length > 0) {
                    handleFiles(files[0]);
                }
            }, false);

            // --- 5. FILE & CROPPER LOGIC ---
            let cropper;
            const imageToCrop = document.getElementById('image-to-crop');
            const applyBtn = document.getElementById('apply-crop');
            const previewImg = document.getElementById('cropped-preview');
            let originalImageDataUrl = null;
            let lastCropData = null;

            photoInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) handleFiles(e.target.files[0]);
            });

            function handleFiles(file) {
                if (!file.type.startsWith('image/')) {
                    alert('Mohon unggah file gambar saja.');
                    return;
                }
                const reader = new FileReader();
                reader.onload = (event) => {
                    originalImageDataUrl = event.target.result;
                    lastCropData = null;
                    openCropper(originalImageDataUrl);
                };
                reader.readAsDataURL(file);
            }

            function openCropper(imageSrc) {
                imageToCrop.src = imageSrc;
                document.getElementById('cropperModal').classList.remove('hidden');

                if (cropper) cropper.destroy();

                cropper = new Cropper(imageToCrop, {
                    aspectRatio: 2 / 3,
                    viewMode: 2,
                    dragMode: 'move',
                    autoCropArea: 1,
                    responsive: true,
                    restore: false,
                    ready: function() {
                        if (lastCropData) {
                            cropper.setData(lastCropData);
                        }
                    }
                });
            }

            previewImg.addEventListener('click', function() {
                if (originalImageDataUrl) {
                    openCropper(originalImageDataUrl);
                }
            });

            applyBtn.addEventListener('click', () => {
                lastCropData = cropper.getData();
                const canvas = cropper.getCroppedCanvas({
                    width: 400,
                    height: 600
                });

                canvas.toBlob((blob) => {
                    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                    let randomString = '';
                    for (let i = 0; i < 40; i++) {
                        randomString += chars.charAt(Math.floor(Math.random() * chars.length));
                    }
                    const uniqueFileName = `${randomString}.jpg`;
                    const croppedFile = new File([blob], uniqueFileName, {
                        type: "image/jpeg"
                    });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(croppedFile);

                    photoInput.files = dataTransfer.files;
                    const previewImg = document.getElementById('cropped-preview');
                    previewImg.src = URL.createObjectURL(croppedFile);

                    document.getElementById('preview-container').classList.remove('hidden');
                    document.getElementById('cropperModal').classList.add('hidden');
                    clearError(photoInput);
                }, 'image/jpeg');
            });

            document.getElementById('cancel-crop').addEventListener('click', () => {
                document.getElementById('cropperModal').classList.add('hidden');
                photoInput.value = "";
            });

            // --- 6. SMART VALIDATION & ERROR HANDLING ---
            function showError(field, message) {
                // Clear existing error message for this field to prevent duplicates
                clearError(field);

                field.classList.add('is-invalid');
                let labelText = "Field";
                const parentLabel = field.parentElement.querySelector('label');
                const sectionLabel = field.closest('section')?.querySelector('h3');

                if (labelName = field.getAttribute('data-label')) {
                    labelText = labelName;
                } else if (parentLabel) {
                    labelText = parentLabel.innerText;
                } else if (sectionLabel) {
                    labelText = sectionLabel.innerText;
                }

                if (field.id === 'photo') {
                    dropArea.classList.add('is-invalid');
                }

                const errorDisplay = document.createElement('p');
                errorDisplay.className = 'error-msg text-red-500 text-[10px] mt-2 font-bold italic';
                errorDisplay.innerText = `* ${labelText}: ${message}`;

                if (field.id === 'photo') {
                    dropArea.after(errorDisplay);
                } else {
                    field.parentElement.appendChild(errorDisplay);
                }
            }

            function clearError(field) {
                field.classList.remove('is-invalid');
                if (field.id === 'photo') dropArea.classList.remove('is-invalid');

                // Find and remove error message specifically associated with this field
                let existingError;
                if (field.id === 'photo') {
                    existingError = dropArea.nextElementSibling;
                } else {
                    existingError = field.parentElement.querySelector('.error-msg');
                }

                if (existingError && existingError.classList.contains('error-msg')) {
                    existingError.remove();
                }
            }

            form.addEventListener('submit', function(e) {
                let isFormValid = true;

                validateFields.forEach(field => {
                    if (field.type === 'file') {
                        if (field.files.length === 0) {
                            showError(field, 'Wajib diunggah');
                            isFormValid = false;
                        }
                    } else if (!field.value || field.value.trim() === "") {
                        showError(field, 'Wajib diisi');
                        isFormValid = false;
                    } else if (field.minLength > 0 && field.value.length < field.minLength) {
                        showError(field, `Kurang dari ${field.minLength} karakter`);
                        isFormValid = false;
                    }
                });

                if (!isFormValid) {
                    e.preventDefault();
                    const firstError = document.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                } else {
                    persistFields.forEach(field => localStorage.removeItem('profile_' + (field.id || field.name)));
                    localStorage.removeItem('profile_age');
                    localStorage.removeItem('profile_gps_coordinates');
                }
            });
        });
    </script>
</body>

</html>