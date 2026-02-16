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
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 mx-4">
        <strong>Error Validasi:</strong>
        <ul class="text-xs">
            @foreach ($errors->all() as $error)
            <li>- {{ $error }}</li>
            @endforeach
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
    </style>

    <div class="py-12 p-4">
        <div class="w-full mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-50 px-8 py-5 bg-slate-50/50 flex items-center gap-3">
                    <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </span>
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest">Identitas Resmi & Pas Foto</h3>
                </div>

                <form id="profileForm" method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="p-8 space-y-10">
                    @csrf
                    @method('patch')

                    <div class="flex flex-col lg:flex-row gap-12">
                        {{-- BAGIAN FOTO --}}
                        <div class="shrink-0 flex flex-col items-center gap-4">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Pas Foto (4x6)</label>
                            <div class="relative group">
                                <div class="h-60 w-40 rounded-2xl overflow-hidden border-4 border-white shadow-lg ring-1 ring-slate-100 bg-gray-50">
                                    <img id="cropped-preview"
                                        class="h-full w-full object-cover cursor-pointer hover:opacity-90 transition-all"
                                        src="{{ Auth::user()->person && Auth::user()->person->photo ? asset('storage/' . Auth::user()->person->photo) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->person->name ?? Auth::user()->name).'&size=512' }}"
                                        alt="Preview">
                                </div>
                                <label for="photo-input" class="absolute bottom-3 right-3 p-2.5 bg-indigo-600 text-white rounded-xl shadow-xl cursor-pointer hover:bg-indigo-700 transition-all hover:scale-110">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <input type="file" id="photo-input" name="photo" class="hidden" accept="image/*">
                                </label>
                            </div>
                            <p class="text-[10px] text-slate-400 italic text-center max-w-[160px]">Format: JPG, PNG. Maks 2MB.</p>
                        </div>

                        {{-- BAGIAN INPUT UTAMA --}}
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Email Akun</label>
                                <input type="email" name="email" value="{{ Auth::user()->email }}"
                                    class="w-full mt-2 px-4 py-2.5 bg-slate-100 border-none rounded-lg text-sm text-slate-400 cursor-not-allowed"
                                    readonly title="Email tidak dapat diubah">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Lengkap (Sesuai KTP)</label>
                                <input type="text" name="name" value="{{ old('name', Auth::user()->person->name ?? Auth::user()->name) }}" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all" required>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">NIK (16 Digit)</label>
                                <input type="number" name="nik" value="{{ old('nik', Auth::user()->person->nik ?? '') }}" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nomor KK (16 Digit)</label>
                                <input type="number" name="no_kk" value="{{ old('no_kk', Auth::user()->person->no_kk ?? '') }}" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">NIP</label>
                                <input type="number" name="nip" value="{{ old('nip', Auth::user()->person->nip ?? '') }}" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">NPWP</label>
                                <input type="number" name="npwp" value="{{ old('npwp', Auth::user()->person->npwp ?? '') }}" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Gelar Belakang</label>
                                <input type="text" name="title_education" value="{{ old('title_education', Auth::user()->person->title_education ?? '') }}" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all" placeholder="Cth: S.Kom">
                            </div>
                            {{-- TAMBAHAN: PENDIDIKAN --}}
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Pendidikan Terakhir</label>
                                <select name="last_education" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                    @foreach(['D-III', 'D-IV', 'S-1', 'S-2'] as $edu)
                                    <option value="{{ $edu }}" {{ old('last_education', Auth::user()->person->last_education ?? '') == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Jurusan</label>
                                <input type="text" name="major_education" value="{{ old('major_education', Auth::user()->person->major_education ?? '') }}" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all" placeholder="Teknik Informatika">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nomor Telepon/WA</label>
                                <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            {{-- TAMBAHAN: UNIT PENEMPATAN --}}
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Unit Penugasan</label>
                                <select name="id_work_assignment" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                    <option value="">Pilih Penugasan</option>
                                    @foreach($workAssignments as $wa)
                                    <option value="{{ $wa->id_work_assignment }}" {{ old('id_work_assignment', Auth::user()->person->id_work_assignment ?? '') == $wa->id_work_assignment ? 'selected' : '' }}>
                                        {{ $wa->sppgUnit->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- TAMBAHAN: INFORMASI PRIBADI & ATRIBUT --}}
                    <div class="pt-10 border-t border-gray-100">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Agama</label>
                                <select name="religion" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                    @foreach(['Islam', 'Kristen', 'Katholik', 'Hindu', 'Buddha', 'Khonghucu'] as $rel)
                                    <option value="{{ $rel }}" {{ old('religion', Auth::user()->person->religion ?? '') == $rel ? 'selected' : '' }}>{{ $rel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Status Perkawinan</label>
                                <select name="marital_status" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                    @foreach(['Belum Kawin', 'Kawin', 'Janda', 'Duda'] as $status)
                                    <option value="{{ $status }}" {{ old('marital_status', Auth::user()->person->marital_status ?? '') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Jenis Kelamin</label>
                                <select name="gender" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                    <option value="L" {{ old('gender', Auth::user()->person->gender ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('gender', Auth::user()->person->gender ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Batch</label>
                                <select name="batch" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                    @foreach(['1', '2', '3', 'Non-SPPI'] as $b)
                                    <option value="{{ $b }}" {{ old('batch', Auth::user()->person->batch ?? '') == $b ? 'selected' : '' }}>{{ $b }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Status Kerja</label>
                                <select name="employment_status" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                    <option value="ASN" {{ old('employment_status', Auth::user()->person->employment_status ?? '') == 'ASN' ? 'selected' : '' }}>ASN</option>
                                    <option value="Non-ASN" {{ old('employment_status', Auth::user()->person->employment_status ?? '') == 'Non-ASN' ? 'selected' : '' }}>Non-ASN</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Ukuran Baju</label>
                                <select name="clothing_size" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                    @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', '4XL', '5XL'] as $size)
                                    <option value="{{ $size }}" {{ old('clothing_size', Auth::user()->person->clothing_size ?? '') == $size ? 'selected' : '' }}>{{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Ukuran Sepatu</label>
                                <select name="shoe_size" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                    @for($i=35; $i<=50; $i++)
                                    <option value="{{ $i }}" {{ old('shoe_size', Auth::user()->person->shoe_size ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tempat Lahir</label>
                                <input type="text" name="place_birthday" value="{{ old('place_birthday', Auth::user()->person->place_birthday ?? '') }}" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tanggal Lahir</label>
                                <input type="date" name="date_birthday" value="{{ old('date_birthday', Auth::user()->person->date_birthday ?? '') }}" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                        </div>
                    </div>

                    {{-- TAMBAHAN: PAYROLL INFO --}}
                    <div class="pt-10 border-t border-gray-100">
                        <div class="flex items-center gap-2 mb-6 text-indigo-600">
                            <span class="p-1.5 bg-indigo-50 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </span>
                            <h3 class="text-sm font-bold uppercase tracking-widest text-slate-800">Informasi Payroll</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Bank</label>
                                <select name="payroll_bank_name" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                    <option value="" disabled>Pilih Bank</option>
                                    @foreach(['BNI', 'Mandiri', 'BCA', 'BTN', 'BSI', 'BPD Bali'] as $bank)
                                    <option value="{{ $bank }}" {{ old('payroll_bank_name', Auth::user()->person->payroll_bank_name ?? '') == $bank ? 'selected' : '' }}>{{ $bank }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nomor Rekening</label>
                                <input type="number" name="payroll_bank_account_number" value="{{ old('payroll_bank_account_number', Auth::user()->person->payroll_bank_account_number ?? '') }}" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Pemilik Rekening</label>
                                <input type="text" name="payroll_bank_account_name" value="{{ old('payroll_bank_account_name', Auth::user()->person->payroll_bank_account_name ?? '') }}" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                        </div>
                    </div>

                    {{-- DOMISILI & MAP --}}
                    <div class="pt-10 border-t border-gray-100">
                        <div class="flex items-center gap-2 mb-6 text-emerald-600">
                            <span class="p-1.5 bg-emerald-50 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                            </span>
                            <h3 class="text-sm font-bold uppercase tracking-widest text-slate-800">Domisili & Lokasi GPS</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Provinsi</label>
                                        <select name="province" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                            <option value="Bali" selected>Bali</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Kabupaten</label>
                                        <select name="regency" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                            <option value="Buleleng" selected>Buleleng</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Kecamatan</label>
                                        <select name="district" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                            @foreach(['Tejakula', 'Kubutambahan', 'Sawan', 'Sukasada', 'Buleleng', 'Banjar', 'Seririt', 'Busungbiu', 'Gerokgak'] as $kec)
                                            <option value="{{ $kec }}" {{ old('district', Auth::user()->person->district ?? '') == $kec ? 'selected' : '' }}>{{ $kec }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Desa/Kelurahan</label>
                                        <input type="text" name="village" value="{{ old('village', Auth::user()->person->village ?? '') }}" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                    </div>
                                </div>
                                <div>
                                    <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Alamat Lengkap (KTP)</label>
                                    <textarea name="address" rows="3" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">{{ old('address', Auth::user()->person->address ?? '') }}</textarea>
                                </div>
                                <div>
                                    <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Koordinat GPS (Klik Pada Peta)</label>
                                    <input type="text" id="gps_coordinates" name="gps_coordinates" value="{{ old('gps_coordinates', Auth::user()->person->gps_coordinates ?? '') }}" readonly class="w-full mt-2 px-4 py-2 bg-indigo-50 text-indigo-700 font-mono text-[11px] rounded-lg border-none focus:ring-0 cursor-default">
                                </div>
                            </div>
                            <div id="map"></div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-8 border-t border-gray-50">
                        <button type="submit" class="px-10 py-3 bg-indigo-600 text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-xl transition-all active:scale-95 cursor-pointer">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-50 px-8 py-5 bg-slate-50/50 flex items-center gap-3">
                    <span class="p-1.5 bg-amber-50 text-amber-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest">Keamanan Kata Sandi</h3>
                </div>

                <form method="post" action="{{ route('password.update') }}" class="p-8 space-y-8">
                    @csrf
                    @method('put')
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Sandi Saat Ini</label>
                            <input type="password" name="current_password" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all" autocomplete="current-password">
                            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Sandi Baru</label>
                            <input type="password" name="password" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all" autocomplete="new-password">
                            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Konfirmasi Sandi</label>
                            <input type="password" name="password_confirmation" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all" autocomplete="new-password">
                        </div>
                    </div>
                    <div class="flex justify-end pt-4">
                        @if (session('status') === 'password-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-emerald-600 mr-4 font-bold">✓ Berhasil diperbarui</p>
                        @endif
                        <button type="submit" class="px-8 py-2.5 bg-slate-800 text-white rounded-xl font-bold text-[11px] uppercase tracking-widest hover:bg-slate-900 shadow-md transition-all active:scale-95 cursor-pointer">Perbarui Kata Sandi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL CROPPER --}}
    <div id="cropperModal" class="fixed inset-0 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden">
            <div class="p-6 border-b bg-gray-50 flex justify-between items-center">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest">Sesuaikan Foto (4:6)</h3>
            </div>
            <div class="p-6 bg-slate-100 flex justify-center">
                <div class="max-w-full max-h-[60vh]"><img id="image-to-crop" class="block max-w-full"></div>
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
            // MAP LOGIC
            const coordsField = document.getElementById('gps_coordinates');
            let initialCoords = [-8.112, 115.091];
            if (coordsField.value && coordsField.value.includes(',')) {
                initialCoords = coordsField.value.split(',').map(c => parseFloat(c.trim()));
            }
            const map = L.map('map').setView(initialCoords, 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
            let marker = L.marker(initialCoords).addTo(map);
            map.on('click', function(e) {
                const {
                    lat,
                    lng
                } = e.latlng;
                coordsField.value = `${lat.toFixed(7)}, ${lng.toFixed(7)}`;
                if (marker) map.removeLayer(marker);
                marker = L.marker(e.latlng).addTo(map);
            });

            // CROPPER LOGIC
            const photoInput = document.getElementById('photo-input');
            const imageToCrop = document.getElementById('image-to-crop');
            const cropperModal = document.getElementById('cropperModal');
            const previewImg = document.getElementById('cropped-preview');
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