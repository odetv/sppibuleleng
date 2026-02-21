<x-app-layout title="Edit Profil">
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-bold text-slate-800 text-xl tracking-tight uppercase text-[14px]">Edit Profil</h2>
            <nav>
                <ol class="flex items-center gap-2 text-[12px]">
                    <li><a class="font-medium text-slate-400" href="{{ route('dashboard') }}">Dashboard /</a></li>
                    <li><a class="font-medium text-slate-400" href="{{ route('profile.show') }}">Profil /</a></li>
                    <li class="font-medium text-indigo-600 font-bold uppercase">Edit</li>
                </ol>
            </nav>
        </div>
    </x-slot>

    @if ($errors->any())
    <div class="mb-4 mt-4 ml-2 mr-2 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg text-left">
        <h3 class="text-xs font-bold text-red-800 uppercase tracking-wider">Mohon Perbaiki:</h3>
        <ul class="mt-2 text-[11px] text-red-700 list-disc list-inside">
            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </ul>
    </div>
    @endif

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        #map {
            height: 350px;
            width: 100%;
            border-radius: 1rem;
            z-index: 1;
            border: 1px solid #f1f5f9;
        }

        #cropperModal {
            background-color: rgba(0, 0, 0, 0.85);
            z-index: 9999;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        label {
            user-select: none;
        }

        .input-disabled {
            background-color: #f8fafc !important;
            color: #94a3b8 !important;
            cursor: not-allowed !important;
            pointer-events: none;
        }
    </style>

    <div class="py-12 p-4 font-sans text-left">
        <div class="w-full mx-auto sm:px-6 lg:px-8 space-y-8">

            <form id="profileForm" method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('patch')

                {{-- SECTION 1: IDENTITAS & FOTO --}}
                <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden mb-8">
                    <div class="border-b border-gray-50 px-8 py-5 bg-slate-50/50 flex items-center gap-3">
                        <span class="p-1.5 bg-white text-indigo-600 rounded-lg shadow-sm border border-slate-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                            </svg>
                        </span>
                        <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-widest">Identitas Kependudukan</h3>
                    </div>

                    <div class="p-8 flex flex-col lg:flex-row gap-12 text-left">
                        <div class="shrink-0 flex flex-col items-center gap-4">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider text-center">Pas Foto (4x6)</label>
                            <div class="relative group">
                                <div class="h-60 w-40 rounded-2xl overflow-hidden border-4 border-white shadow-lg ring-1 ring-slate-100 bg-indigo-600 flex items-center justify-center">
                                    @php
                                    $userPerson = Auth::user()->person;
                                    $displayInitial = substr($userPerson->name ?? Auth::user()->email, 0, 1);
                                    @endphp
                                    @if($userPerson && $userPerson->photo && Storage::disk('public')->exists($userPerson->photo))
                                    <img id="cropped-preview" class="h-full w-full object-cover cursor-pointer hover:opacity-90 transition-all" src="{{ asset('storage/' . $userPerson->photo) }}" alt="Preview">
                                    <div id="initial-placeholder" class="hidden text-white text-6xl uppercase tracking-tighter">{{ $displayInitial }}</div>
                                    @else
                                    <div id="initial-placeholder" class="text-white text-6xl uppercase tracking-tighter">{{ $displayInitial }}</div>
                                    <img id="cropped-preview" class="h-full w-full object-cover cursor-pointer hover:opacity-90 transition-all hidden" src="" alt="Preview">
                                    @endif
                                </div>
                                <label for="photo-input" class="absolute bottom-3 right-3 p-2.5 bg-indigo-400 text-white rounded-xl shadow-xl cursor-pointer hover:bg-indigo-500 transition-all hover:scale-110">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <input type="file" id="photo-input" name="photo" class="hidden" accept="image/*">
                                </label>
                            </div>
                        </div>

                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 text-sm">
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Lengkap (Sesuai KTP)</label>
                                <input required type="text" name="name" id="ktp_name" value="{{ old('name', $userPerson->name ?? '') }}" class="text-sm w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Telepon</label>
                                <input required type="number" name="phone" id="phone" value="{{ old('phone', $userPerson->user->phone ?? '') }}" class="text-sm w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider text-nowrap">NIK (16 Digit)</label>
                                <input required type="number" name="nik" value="{{ old('nik', $userPerson->nik ?? '') }}" class="text-sm w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider text-nowrap">Nomor KK (16 Digit)</label>
                                <input required type="number" name="no_kk" value="{{ old('no_kk', $userPerson->no_kk ?? '') }}" class="text-sm w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider uppercase">NIP</label>
                                <input required type="number" name="nip" value="{{ old('nip', $userPerson->nip ?? '') }}" class="text-sm w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider uppercase text-nowrap">NPWP</label>
                                <input required type="number" name="npwp" value="{{ old('npwp', $userPerson->npwp ?? '') }}" class="text-sm w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider text-nowrap">No. BPJS Kesehatan</label>
                                <input required type="number" name="no_bpjs_kes" value="{{ old('no_bpjs_kes', $userPerson->no_bpjs_kes ?? '') }}" class="text-sm w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider text-nowrap">No. BPJS Ketenagakerjaan</label>
                                <input required type="number" name="no_bpjs_tk" value="{{ old('no_bpjs_tk', $userPerson->no_bpjs_tk ?? '') }}" class="text-sm w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. DETAIL PERSONAL --}}
                <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden mb-8">
                    <div class="border-b border-gray-50 px-8 py-5 bg-slate-50/50 flex items-center gap-3">
                        <span class="p-1.5 bg-white text-indigo-600 rounded-lg shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </span>
                        <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-widest">Detail Personal & Atribut</h3>
                    </div>
                    <div class="p-8 grid grid-cols-1 md:grid-cols-4 gap-8 text-sm text-left">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tempat Lahir</label>
                            <input required type="text" name="place_birthday" value="{{ old('place_birthday', $userPerson->place_birthday ?? '') }}" class="text-sm w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tanggal Lahir</label>
                            <input required type="date" name="date_birthday" id="date_birthday" value="{{ old('date_birthday', $userPerson->date_birthday ?? '') }}" class="text-sm w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Jenis Kelamin</label>
                            <select required name="gender" class="text-sm w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 transition-all">
                                <option value="L" {{ old('gender', $userPerson->gender ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender', $userPerson->gender ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Agama</label>
                            <select required name="religion" class="text-sm w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 transition-all">
                                @foreach(['Islam', 'Kristen', 'Katholik', 'Hindu', 'Buddha', 'Khonghucu'] as $rel)
                                <option value="{{ $rel }}" {{ old('religion', $userPerson->religion ?? '') == $rel ? 'selected' : '' }}>{{ $rel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Status Pernikahan</label>
                            <select required name="marital_status" class="text-sm w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 transition-all">
                                @foreach(['Belum Kawin', 'Kawin', 'Janda', 'Duda'] as $status)
                                <option value="{{ $status }}" {{ old('marital_status', $userPerson->marital_status ?? '') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Ukuran Baju</label>
                            <select required name="clothing_size" class="text-sm w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 transition-all">
                                @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', '4XL', '5XL'] as $size)
                                <option value="{{ $size }}" {{ old('clothing_size', $userPerson->clothing_size ?? '') == $size ? 'selected' : '' }}>{{ $size }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider text-nowrap">Ukuran Sepatu</label>
                            <select required name="shoe_size" class="text-sm w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 transition-all">
                                @for($i=35; $i<=50; $i++)
                                    <option value="{{ $i }}" {{ old('shoe_size', $userPerson->shoe_size ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Usia</label>
                            <input required type="text" id="age_display" value="{{ $userPerson->age ?? '-' }} Tahun" readonly class="text-sm w-full mt-2 px-4 py-2.5 bg-slate-50 border-none rounded-lg text-slate-400 cursor-not-allowed">
                        </div>
                    </div>
                </div>

                {{-- 3. PENDIDIKAN & KEDINASAN --}}
                <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden mb-8">
                    <div class="border-b border-gray-50 px-8 py-5 bg-slate-50/50 flex items-center gap-3">
                        <span class="p-1.5 bg-white text-indigo-600 rounded-lg shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </span>
                        <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-widest">Pendidikan & Kedinasan</h3>
                    </div>
                    <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8 text-sm">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Pendidikan Terakhir</label>
                            <select required name="last_education" class="text-sm w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg">
                                @foreach(['D-III', 'D-IV', 'S-1', 'S-2'] as $edu)
                                <option value="{{ $edu }}" {{ old('last_education', $userPerson->last_education ?? '') == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Gelar Belakang</label>
                            <input required type="text" name="title_education" value="{{ old('title_education', $userPerson->title_education ?? '') }}" class="text-sm w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Jurusan/Program Studi</label>
                            <input required type="text" name="major_education" value="{{ old('major_education', $userPerson->major_education ?? '') }}" class="text-sm w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Jabatan</label>
                            <select name="id_ref_position" class="text-sm w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500">
                                {{-- Opsi Default / Reset --}}
                                <option value="">Belum Menjabat</option>

                                {{-- Loop Data Jabatan --}}
                                @foreach($positions as $pos)
                                <option value="{{ $pos->id_ref_position }}"
                                    {{ old('id_ref_position', $userPerson->id_ref_position ?? '') == $pos->id_ref_position ? 'selected' : '' }}>
                                    {{ $pos->name_position }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Unit Penugasan</label>
                            <select name="id_work_assignment" class="text-sm w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg">
                                <option value="">Belum Penugasan</option>
                                @foreach($workAssignments as $wa)
                                <option value="{{ $wa->id_work_assignment }}" {{ old('id_work_assignment', $userPerson->id_work_assignment ?? '') == $wa->id_work_assignment ? 'selected' : '' }}>
                                    {{ $wa->sppgUnit->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Batch</label>
                            <select required name="batch" class="text-sm w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg">
                                @foreach(['1', '2', '3', 'Non-SPPI'] as $b)
                                <option value="{{ $b }}" {{ old('batch', $userPerson->batch ?? '') == $b ? 'selected' : '' }}>{{ $b }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider text-nowrap">Status Kerja</label>
                            <select required name="employment_status" class="text-sm w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg">
                                <option value="ASN" {{ old('employment_status', $userPerson->employment_status ?? '') == 'ASN' ? 'selected' : '' }}>ASN</option>
                                <option value="Non-ASN" {{ old('employment_status', $userPerson->employment_status ?? '') == 'Non-ASN' ? 'selected' : '' }}>Non-ASN</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- SECTION 4: ALAMAT KTP --}}
                <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden mb-8 text-left">
                    <div class="border-b border-gray-50 px-8 py-5 bg-slate-50/30 flex items-center gap-3">
                        <span class="p-1.5 bg-white text-indigo-600 rounded-lg shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </span>
                        <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-widest">Alamat KTP</h3>
                    </div>
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Provinsi</label>
                                <select required id="f_ktp_prov" name="province_ktp" data-selected="{{ $userPerson->province_ktp ?? '' }}" class="w-full px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm">
                                    <option value="" disabled selected>Pilih Provinsi</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Kabupaten</label>
                                <select required id="f_ktp_reg" name="regency_ktp" data-selected="{{ $userPerson->regency_ktp ?? '' }}" disabled class="w-full px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm">
                                    <option value="" disabled selected>Pilih Kabupaten</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1 text-nowrap">Kecamatan</label>
                                <select required id="f_ktp_dist" name="district_ktp" data-selected="{{ $userPerson->district_ktp ?? '' }}" disabled class="w-full px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm">
                                    <option value="" disabled selected>Pilih Kecamatan</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Desa/Kelurahan</label>
                                <select required id="f_ktp_vill" name="village_ktp" data-selected="{{ $userPerson->village_ktp ?? '' }}" disabled class="w-full px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm">
                                    <option value="" disabled selected>Pilih Desa</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex flex-col text-left">
                            <label class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Alamat Jalan/Rumah</label>
                            <textarea required id="ktp_address" name="address_ktp" rows="3" class="w-full h-full px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">{{ old('address_ktp', $userPerson->address_ktp ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- SECTION 5: DOMISILI & GPS --}}
                <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden mb-8 text-left">
                    <div class="border-b border-gray-50 px-8 py-5 bg-slate-50/30 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="p-1.5 bg-white text-indigo-600 rounded-lg shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                            </span>
                            <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-widest">Alamat Domisili & GPS</h3>
                        </div>
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" id="sync_address" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest group-hover:text-indigo-600 transition-colors">Gunakan Alamat KTP</span>
                        </label>
                    </div>
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                        <div class="space-y-6 text-left">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Provinsi</label>
                                    <select required id="f_dom_prov" name="province_domicile" data-selected="{{ $userPerson->province_domicile ?? '' }}" class="w-full px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm">
                                        <option value="" disabled selected>Pilih Provinsi</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Kabupaten</label>
                                    <select required id="f_dom_reg" name="regency_domicile" data-selected="{{ $userPerson->regency_domicile ?? '' }}" disabled class="w-full px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm">
                                        <option value="" disabled selected>Pilih Kabupaten</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Kecamatan</label>
                                    <select required id="f_dom_dist" name="district_domicile" data-selected="{{ $userPerson->district_domicile ?? '' }}" disabled class="w-full px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm">
                                        <option value="" disabled selected>Pilih Kecamatan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Desa/Kelurahan</label>
                                    <select required id="f_dom_vill" name="village_domicile" data-selected="{{ $userPerson->village_domicile ?? '' }}" disabled class="w-full px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm">
                                        <option value="" disabled selected>Pilih Desa</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Alamat Jalan/Rumah</label>
                                <textarea required id="dom_address" name="address_domicile" rows="2" class="w-full px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">{{ old('address_domicile', $userPerson->address_domicile ?? '') }}</textarea>
                            </div>
                            <div>
                                <label class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1 leading-none text-nowrap">Koordinat GPS (Klik Pada Peta)</label>
                                <div class="flex gap-2 mt-2">
                                    <input required type="text" id="lat_field" name="latitude_gps_domicile" value="{{ old('latitude_gps_domicile', $userPerson->latitude_gps_domicile ?? '') }}" readonly class="w-1/2 px-4 py-2.5 bg-gray-50 text-sm rounded-lg border-none focus:ring-0 input-disabled">
                                    <input required type="text" id="lng_field" name="longitude_gps_domicile" value="{{ old('longitude_gps_domicile', $userPerson->longitude_gps_domicile ?? '') }}" readonly class="w-1/2 px-4 py-2.5 bg-gray-50 text-sm rounded-lg border-none focus:ring-0 input-disabled">
                                </div>
                            </div>
                        </div>
                        <div id="map"></div>
                    </div>
                </div>

                {{-- SECTION 6: INFORMASI PAYROLL --}}
                <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden mb-8 text-left">
                    <div class="border-b border-gray-50 px-8 py-5 bg-slate-50/50 flex items-center gap-3">
                        <span class="p-1.5 bg-white text-indigo-600 rounded-lg shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </span>
                        <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-widest">Informasi Payroll</h3>
                    </div>
                    <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Bank</label>
                            <select required name="payroll_bank_name" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                @foreach(['BNI', 'Mandiri', 'BCA', 'BTN', 'BSI', 'BPD Bali'] as $bank)
                                <option value="{{ $bank }}" {{ old('payroll_bank_name', $userPerson->payroll_bank_name ?? '') == $bank ? 'selected' : '' }}>{{ $bank }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Nomor Rekening</label>
                            <input required type="number" name="payroll_bank_account_number" value="{{ old('payroll_bank_account_number', $userPerson->payroll_bank_account_number ?? '') }}" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Pemilik Rekening</label>
                            <input required type="text" name="payroll_bank_account_name" value="{{ old('payroll_bank_account_name', $userPerson->payroll_bank_account_name ?? '') }}" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>
                    </div>
                </div>

                {{-- SECTION 7: MEDIA SOSIAL --}}
                <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden mb-8 text-left">
                    <div class="border-b border-gray-50 px-8 py-5 bg-slate-50/30 flex items-center gap-3">
                        <span class="p-1.5 bg-white text-indigo-600 rounded-lg shadow-sm border border-slate-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                            </svg>
                        </span>
                        <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-widest">Media Sosial</h3>
                    </div>
                    <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8 text-sm">
                        @php $sosmed = Auth::user()->person ? Auth::user()->person->socialMedia : null; @endphp
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Facebook URL</label>
                            <input type="url" name="facebook_url" value="{{ old('facebook_url', $sosmed->facebook_url ?? '') }}"
                                class="text-sm w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 transition-all"
                                placeholder="https://facebook.com/username">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Instagram URL</label>
                            <input type="url" name="instagram_url" value="{{ old('instagram_url', $sosmed->instagram_url ?? '') }}"
                                class="text-sm w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 transition-all"
                                placeholder="https://instagram.com/username">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">TikTok URL</label>
                            <input type="url" name="tiktok_url" value="{{ old('tiktok_url', $sosmed->tiktok_url ?? '') }}"
                                class="text-sm w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 transition-all"
                                placeholder="https://tiktok.com/@username">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4 mb-12">
                    <button type="submit" class="w-full md:w-auto px-12 py-4 bg-indigo-600 text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-xl transition-all active:scale-95 cursor-pointer">Simpan Seluruh Perubahan Profil</button>
                </div>
            </form>

            {{-- 7. KATA SANDI --}}
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden text-left">
                <div class="border-b border-gray-50 px-8 py-5 bg-slate-50/50 flex items-center gap-3">
                    <span class="p-1.5 bg-white text-indigo-600 rounded-lg shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-widest">Keamanan Kata Sandi</h3>
                </div>
                <form method="post" action="{{ route('password.update') }}" class="p-8 space-y-8 text-left">
                    @csrf @method('put')
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-sm">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Sandi Saat Ini</label>
                            <input type="password" name="current_password" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 transition-all">
                            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Sandi Baru</label>
                            <input type="password" name="password" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 transition-all">
                            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Konfirmasi Sandi</label>
                            <input type="password" name="password_confirmation" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>
                    </div>
                    <div class="flex justify-end pt-4">
                        @if (session('status') === 'password-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-emerald-600 mr-4 font-bold">✓ Berhasil diperbarui</p>
                        @endif
                        <button type="submit" class="px-8 py-2.5 bg-slate-800 text-white rounded-xl font-bold text-[11px] uppercase tracking-widest hover:bg-slate-900 shadow-md transition-all active:scale-95 cursor-pointer uppercase tracking-widest">Perbarui Kata Sandi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL CROPPER --}}
    <div id="cropperModal" class="fixed inset-0 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden text-center">
            <div class="p-6 border-b bg-gray-50 flex justify-between items-center text-center">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest">Sesuaikan Foto (4:6)</h3>
            </div>
            <div class="p-6 bg-slate-100 flex justify-center">
                <div class="max-w-full max-h-[60vh] text-center"><img id="image-to-crop" class="block max-w-full"></div>
            </div>
            <div class="p-6 border-t flex justify-end gap-3 bg-white">
                <button id="cancel-crop" type="button" class="px-6 py-2 font-bold text-gray-400 hover:text-gray-600 transition-colors uppercase text-[11px] cursor-pointer">Batal</button>
                <button id="apply-crop" type="button" class="px-8 py-2 bg-indigo-600 text-white font-bold rounded-lg shadow-lg hover:bg-indigo-700 transition-colors uppercase text-[11px] cursor-pointer">Potong & Gunakan</button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const apiBase = "/api-wilayah";
            const sleep = (ms) => new Promise(resolve => setTimeout(resolve, ms));
            let mapObj, markerObj;

            // 1. POPULATE DROPDOWN
            async function populateSelect(selectId, urlPath, placeholder, targetValue = null) {
                const select = document.getElementById(selectId);
                if (!select) return null;
                const savedValue = select.getAttribute('data-selected');
                let valueToMatch = (targetValue || savedValue || "").toString().toUpperCase().trim();

                select.innerHTML = `<option value="">Mohon tunggu...</option>`;
                select.disabled = true;

                try {
                    const response = await fetch(`${apiBase}/${urlPath}`);
                    const result = await response.json();
                    const data = result.data;

                    let options = `<option value="" disabled selected>${placeholder}</option>`;
                    data.forEach(item => {
                        let cleanName = item.name.replace(/^(KABUPATEN|KOTA)\s+/i, "");
                        options += `<option value="${cleanName}" data-code="${item.code}">${cleanName}</option>`;
                    });

                    select.innerHTML = options;

                    // Logic Disabled: Jangan buka jika Sync aktif di Domisili
                    const isSyncActive = document.getElementById('sync_address').checked;
                    if (!(selectId.startsWith('f_dom_') && isSyncActive)) {
                        select.disabled = false;
                        select.classList.remove('input-disabled');
                    } else {
                        select.disabled = true;
                        select.classList.add('input-disabled');
                    }

                    if (valueToMatch) {
                        for (let i = 0; i < select.options.length; i++) {
                            if (select.options[i].value.toUpperCase() === valueToMatch) {
                                select.selectedIndex = i;
                                return select.options[i].getAttribute('data-code');
                            }
                        }
                    }
                } catch (e) {
                    select.innerHTML = `<option value="" disabled selected>Gagal memuat</option>`;
                }
                return null;
            }

            // 2. CHAIN LOADING
            async function runChain(prefix) {
                const provCode = await populateSelect(`f_${prefix}_prov`, 'provinces.json', "Pilih Provinsi");
                if (provCode) {
                    const regCode = await populateSelect(`f_${prefix}_reg`, `regencies/${provCode}.json`, "Pilih Kabupaten");
                    if (regCode) {
                        const distCode = await populateSelect(`f_${prefix}_dist`, `districts/${regCode}.json`, "Pilih Kecamatan");
                        if (distCode) {
                            await populateSelect(`f_${prefix}_vill`, `villages/${distCode}.json`, "Pilih Desa");
                        }
                    }
                }
            }

            // 3. MAP AUTO MOVE
            async function autoMoveMap() {
                const prov = document.getElementById('f_dom_prov')?.value;
                const reg = document.getElementById('f_dom_reg')?.value;
                const dist = document.getElementById('f_dom_dist')?.value;
                const vill = document.getElementById('f_dom_vill')?.value;
                let address = [vill, dist, reg, prov].filter(Boolean).join(", ");
                if (!address || !mapObj) return;

                try {
                    const resp = await fetch(`/api-map-search?q=${encodeURIComponent(address)}`);
                    const data = await resp.json();
                    if (data && data.length > 0) {
                        const newLat = parseFloat(data[0].lat);
                        const newLng = parseFloat(data[0].lon);
                        let zoom = vill ? 16 : (dist ? 14 : (reg ? 12 : 9));
                        mapObj.setView([newLat, newLng], zoom, {
                            animate: true
                        });
                    }
                } catch (e) {}
            }

            // 4. BIND EVENTS
            const bindEvents = (p) => {
                ['prov', 'reg', 'dist', 'vill'].forEach(f => {
                    const el = document.getElementById(`f_${p}_${f}`);
                    if (!el) return;

                    el.addEventListener('change', async function() {
                        // Proteksi Sync
                        if (p === 'dom' && document.getElementById('sync_address').checked) return;

                        const code = this.options[this.selectedIndex]?.getAttribute('data-code');
                        const nextMap = {
                            'prov': {
                                next: 'reg',
                                path: 'regencies',
                                reset: ['reg', 'dist', 'vill'],
                                label: 'Kabupaten'
                            },
                            'reg': {
                                next: 'dist',
                                path: 'districts',
                                reset: ['dist', 'vill'],
                                label: 'Kecamatan'
                            },
                            'dist': {
                                next: 'vill',
                                path: 'villages',
                                reset: ['vill'],
                                label: 'Desa'
                            }
                        };

                        if (nextMap[f]) {
                            const cfg = nextMap[f];
                            cfg.reset.forEach(target => {
                                const targetEl = document.getElementById(`f_${p}_${target}`);
                                if (targetEl) {
                                    targetEl.innerHTML = `<option value="" disabled selected>Pilih...</option>`;
                                    targetEl.disabled = true;
                                    targetEl.classList.remove('input-disabled');
                                }
                            });
                            if (code) await populateSelect(`f_${p}_${cfg.next}`, `${cfg.path}/${code}.json`, `Pilih ${cfg.label}`);
                        }
                        if (p === 'dom') setTimeout(autoMoveMap, 200);
                    });
                });
            };

            // 5. SYNC ADDRESS LOGIC
            const syncCheckbox = document.getElementById('sync_address');
            ['prov', 'reg', 'dist', 'vill'].forEach(key => {
                document.getElementById(`f_ktp_${key}`).addEventListener('change', async function() {
                    if (syncCheckbox.checked) {
                        document.getElementById(`f_dom_${key}`).setAttribute('data-selected', this.value);
                        await runChain('dom');

                        // Re-Lock
                        ['prov', 'reg', 'dist', 'vill'].forEach(k => {
                            const el = document.getElementById(`f_dom_${k}`);
                            el.disabled = true;
                            el.classList.add('input-disabled');
                        });
                        setTimeout(autoMoveMap, 500);
                    }
                });
            });

            document.getElementById('ktp_address').addEventListener('input', function() {
                if (syncCheckbox.checked) {
                    const domAddr = document.getElementById('dom_address');
                    domAddr.value = this.value;
                    domAddr.readOnly = true;
                    domAddr.classList.add('input-disabled');
                }
            });

            syncCheckbox.addEventListener('change', async function() {
                const fields = ['prov', 'reg', 'dist', 'vill'];
                const domAddr = document.getElementById('dom_address');
                if (this.checked) {
                    fields.forEach(f => {
                        const ktpVal = document.getElementById(`f_ktp_${f}`).value;
                        const domEl = document.getElementById(`f_dom_${f}`);
                        domEl.setAttribute('data-selected', ktpVal);
                        domEl.disabled = true;
                        domEl.classList.add('input-disabled');
                    });
                    domAddr.value = document.getElementById('ktp_address').value;
                    domAddr.readOnly = true;
                    domAddr.classList.add('input-disabled');
                    await runChain('dom');
                    setTimeout(autoMoveMap, 500);
                } else {
                    fields.forEach(f => {
                        const domEl = document.getElementById(`f_dom_${f}`);
                        domEl.disabled = false;
                        domEl.classList.remove('input-disabled');
                    });
                    domAddr.readOnly = false;
                    domAddr.classList.remove('input-disabled');
                }
            });

            // 6. INITIALIZE MAP (KLIK TETAP BISA)
            function initMap() {
                const latF = document.getElementById('lat_field');
                const lngF = document.getElementById('lng_field');
                let coords = [latF.value || -8.112, lngF.value || 115.091];

                mapObj = L.map('map').setView(coords, 14);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapObj);
                markerObj = L.marker(coords).addTo(mapObj);

                mapObj.on('click', function(e) {
                    // Klik tetap diizinkan meskipun Sync aktif
                    latF.value = e.latlng.lat.toFixed(7);
                    lngF.value = e.latlng.lng.toFixed(7);
                    if (markerObj) mapObj.removeLayer(markerObj);
                    markerObj = L.marker(e.latlng).addTo(mapObj);
                });
            }

            // 7. RUN INITIALIZATION
            initMap();
            bindEvents('ktp');
            bindEvents('dom');
            runChain('ktp');
            runChain('dom');

            // 8. FORM SUBMIT HANDLER (Mencegah Error Required saat Sync)
            const profileForm = document.getElementById('profileForm');
            if (profileForm) {
                profileForm.addEventListener('submit', function() {
                    // Cari semua elemen yang disabled di dalam form ini
                    const disabledElements = profileForm.querySelectorAll('select:disabled, input:disabled');

                    // Aktifkan semuanya tepat sebelum data dikirim ke server
                    disabledElements.forEach(el => {
                        el.disabled = false;
                    });
                });
            }

            // UMUR AUTO UPDATE LOGIC
            const dateInput = document.getElementById('date_birthday');
            const ageDisplay = document.getElementById('age_display');

            dateInput.addEventListener('change', function() {
                if (!this.value) return;
                const birthDate = new Date(this.value);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                ageDisplay.value = age + " Tahun";
            });

            // CROPPER LOGIC
            const photoInput = document.getElementById('photo-input');
            const imageToCrop = document.getElementById('image-to-crop');
            const cropperModal = document.getElementById('cropperModal');
            const previewImg = document.getElementById('cropped-preview');
            const initialPlaceholder = document.getElementById('initial-placeholder');
            let cropper;

            photoInput.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        imageToCrop.src = event.target.result;
                        cropperModal.classList.remove('hidden');
                        if (cropper) cropper.destroy();
                        cropper = new Cropper(imageToCrop, {
                            aspectRatio: 2 / 3,
                            viewMode: 1,
                            dragMode: 'move',
                            autoCropArea: 1
                        });
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });

            document.getElementById('apply-crop').addEventListener('click', () => {
                const canvas = cropper.getCroppedCanvas({
                    width: 400,
                    height: 600
                });
                canvas.toBlob((blob) => {
                    const file = new File([blob], "photo.jpg", {
                        type: "image/jpeg"
                    });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    photoInput.files = dataTransfer.files;
                    previewImg.src = URL.createObjectURL(file);
                    previewImg.classList.remove('hidden');
                    if (initialPlaceholder) initialPlaceholder.classList.add('hidden');
                    cropperModal.classList.add('hidden');
                }, 'image/jpeg');
            });

            document.getElementById('cancel-crop').addEventListener('click', () => {
                cropperModal.classList.add('hidden');
                photoInput.value = "";
            });
        });
    </script>
</x-app-layout>