<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

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
            border-color: #ef4444 !important;
            --tw-ring-color: #ef4444 !important;
        }

        #map {
            height: 300px;
            width: 100%;
            border-radius: 0.75rem;
            margin-top: 0.5rem;
            z-index: 1;
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased bg-gray-50">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">

            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight sm:text-4xl">Lengkapi Profil Anda</h2>
                <p class="mt-3 text-lg text-gray-600">Semua kolom wajib diisi untuk keperluan verifikasi akun.</p>
            </div>

            @if ($errors->any())
            <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 rounded-md shadow-sm">
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-red-800">Mohon lengkapi data berikut:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside grid grid-cols-1 md:grid-cols-2 gap-x-4">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <form id="profileForm" method="POST" action="{{ route('profile.store') }}" enctype="multipart/form-data" class="space-y-8" novalidate>
                @csrf

                <div class="bg-white shadow-md border border-gray-100 rounded-xl p-6 sm:p-10">
                    <div class="flex items-center mb-8 pb-3 border-b border-gray-100">
                        <div class="bg-indigo-100 p-2 rounded-lg mr-4 text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 012-2h4a2 2 0 012 2v2m-6 0h6"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Identitas Resmi</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <x-input-label for="nik" :value="__('NIK')" />
                            <x-text-input id="nik" class="block mt-2 w-full validate-field persist" type="text" name="nik" :value="old('nik')" required minlength="16" maxlength="16" placeholder="Cth: 510801xxxxxxxxxx" />
                        </div>
                        <div>
                            <x-input-label for="no_kk" :value="__('Nomor KK')" />
                            <x-text-input id="no_kk" class="block mt-2 w-full validate-field persist" type="text" name="no_kk" :value="old('no_kk')" required minlength="16" maxlength="16" placeholder="Cth: 510801xxxxxxxxxx" />
                        </div>
                        <div>
                            <x-input-label for="npwp" :value="__('NPWP')" />
                            <x-text-input id="npwp" class="block mt-2 w-full validate-field persist" type="text" name="npwp" :value="old('npwp')" required minlength="16" maxlength="16" placeholder="Cth: 510801xxxxxxxxxx" />
                        </div>
                        <div>
                            <x-input-label for="title_education" :value="__('Gelar Belakang')" />
                            <x-text-input id="title_education" class="block mt-2 w-full validate-field persist" type="text" name="title_education" :value="old('title_education')" required placeholder="Cth: S.Kom." />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="name" :value="__('Nama Lengkap (Tanpa Gelar)')" />
                            <x-text-input id="name" class="block mt-2 w-full validate-field persist" type="text" name="name" :value="old('name')" required placeholder="Masukkan nama sesuai KTP" />
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-md border border-gray-100 rounded-xl p-6 sm:p-10">
                    <div class="flex items-center mb-8 pb-3 border-b border-gray-100">
                        <div class="bg-indigo-100 p-2 rounded-lg mr-4 text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Informasi Pribadi</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div>
                            <x-input-label for="gender" :value="__('Jenis Kelamin')" />
                            <select name="gender" id="gender" required class="mt-2 block w-full border-gray-300 rounded-md py-2.5 validate-field persist">
                                <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="religion" :value="__('Agama')" />
                            <select name="religion" id="religion" required class="mt-2 block w-full border-gray-300 rounded-md py-2.5 validate-field persist">
                                <option value="" disabled {{ old('religion') ? '' : 'selected' }}>Pilih Agama</option>
                                @foreach(['Islam', 'Kristen', 'Katholik', 'Hindu', 'Buddha', 'Khonghucu'] as $rel)
                                <option value="{{ $rel }}" {{ old('religion') == $rel ? 'selected' : '' }}>{{ $rel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="marital_status" :value="__('Status')" />
                            <select name="marital_status" id="marital_status" required class="mt-2 block w-full border-gray-300 rounded-md py-2.5 validate-field persist">
                                <option value="" disabled {{ old('marital_status') ? '' : 'selected' }}>Pilih Status</option>
                                @foreach(['Belum Kawin', 'Kawin', 'Janda', 'Duda'] as $status)
                                <option value="{{ $status }}" {{ old('marital_status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="date_birthday" :value="__('Tanggal Lahir')" />
                            <x-text-input id="date_birthday" class="block mt-2 w-full validate-field persist" type="date" name="date_birthday" :value="old('date_birthday')" required />
                        </div>
                        <div class="lg:col-span-2">
                            <x-input-label for="place_birthday" :value="__('Tempat Lahir')" />
                            <x-text-input id="place_birthday" class="block mt-2 w-full validate-field persist" type="text" name="place_birthday" :value="old('place_birthday')" required placeholder="Masukkan tempat lahir sesuai KTP" />
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-md border border-gray-100 rounded-xl p-6 sm:p-10">
                    <div class="flex items-center mb-8 pb-3 border-b border-gray-100">
                        <div class="bg-indigo-100 p-2 rounded-lg mr-4 text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Domisili & Lokasi GPS</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <x-input-label for="city" :value="__('Provinsi')" />
                            <select name="city" id="city" class="mt-2 block w-full border-gray-300 rounded-md py-2.5 bg-gray-50">
                                <option value="Bali">Bali</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="regency" :value="__('Kabupaten')" />
                            <select name="regency" id="regency" class="mt-2 block w-full border-gray-300 rounded-md py-2.5 bg-gray-50">
                                <option value="Buleleng">Buleleng</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="district" :value="__('Kecamatan')" />
                            <select name="district" id="district" required class="mt-2 block w-full border-gray-300 rounded-md py-2.5 validate-field persist">
                                <option value="" disabled {{ old('district') ? '' : 'selected' }}>Pilih Kecamatan</option>
                                @foreach(['Tejakula', 'Kubutambahan', 'Sawan', 'Sukasada', 'Buleleng', 'Banjar', 'Seririt', 'Busungbiu', 'Gerokgak'] as $kec)
                                <option value="{{ $kec }}" {{ old('district') == $kec ? 'selected' : '' }}>{{ $kec }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="village" :value="__('Desa/Kelurahan')" />
                            <x-text-input id="village" class="block mt-2 w-full validate-field persist" type="text" name="village" :value="old('village')" required placeholder="Cth: Sangsit" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="address" :value="__('Alamat Lengkap')" />
                            <textarea id="address" name="address" rows="2" class="mt-2 block w-full border-gray-300 rounded-md validate-field persist" required placeholder="Cth: Jalan Mawar No. 10, Banjar Dinas Anyar">{{ old('address') }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="gps_coordinates" :value="__('Titik Koordinat GPS')" />
                            <x-text-input id="gps_coordinates" class="block mt-2 w-full bg-gray-100 validate-field persist" type="text" name="gps_coordinates" :value="old('gps_coordinates')" readonly required placeholder="Silahkan klik pada peta di bawah untuk menentukan lokasi" />
                            <div id="map"></div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-md border border-gray-100 rounded-xl p-6 sm:p-10">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="w-6 h-6 text-indigo-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Pas Foto
                    </h3>
                    <div class="flex flex-col items-center">
                        <div id="preview-container" class="mb-6 hidden flex flex-col items-center">
                            <img id="cropped-preview" class="w-40 h-40 object-cover rounded-xl shadow-lg border-4 border-indigo-100">
                        </div>
                        <label id="drop-area" for="photo" class="relative group flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-indigo-50 transition-all">
                            <div class="flex flex-col items-center justify-center text-center px-4">
                                <svg class="w-12 h-12 mb-3 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-sm text-gray-600"><span class="font-bold">Klik untuk unggah</span> atau seret foto ke sini</p>
                            </div>
                            <input id="photo" name="photo" type="file" class="hidden validate-field" accept="image/*" required />
                        </label>
                    </div>
                </div>

                <div class="flex justify-center pt-6 pb-10">
                    <button type="submit" class="w-full md:w-2/3 py-4 bg-indigo-600 text-white text-lg font-black rounded-xl shadow-xl hover:bg-indigo-700 transition transform hover:-translate-y-1">
                        SIMPAN DATA PROFIL
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="cropperModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden">
            <div class="p-6 border-b bg-gray-50">
                <h3 class="text-xl font-bold text-gray-800">Potong Pas Foto</h3>
            </div>
            <div class="p-6 bg-gray-200 flex justify-center">
                <div class="max-w-full max-h-[60vh]"><img id="image-to-crop" class="block max-w-full"></div>
            </div>
            <div class="p-6 border-t flex justify-end space-x-3">
                <button id="cancel-crop" type="button" class="px-6 py-2 font-bold text-gray-500">Batal</button>
                <button id="apply-crop" type="button" class="px-8 py-2 bg-indigo-600 text-white font-black rounded-lg shadow-lg">Gunakan Foto</button>
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

            // --- 1. PERSISTENCE LOGIC ---
            persistFields.forEach(field => {
                const savedValue = localStorage.getItem('profile_' + field.id || field.name);
                if (savedValue && !field.value) {
                    field.value = savedValue;
                }
                field.addEventListener('input', () => {
                    localStorage.setItem('profile_' + field.id || field.name, field.value);
                });
            });

            // --- 2. MAP LOGIC ---
            const initialCoords = document.getElementById('gps_coordinates').value.split(',');
            const mapCenter = initialCoords.length === 2 ? [initialCoords[0], initialCoords[1]] : [-8.112, 115.091];
            const map = L.map('map').setView(mapCenter, 11);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
            let marker;
            if (initialCoords.length === 2) marker = L.marker(mapCenter).addTo(map);

            map.on('click', function(e) {
                const {
                    lat,
                    lng
                } = e.latlng;
                const coordsField = document.getElementById('gps_coordinates');
                coordsField.value = `${lat}, ${lng}`;
                localStorage.setItem('profile_gps_coordinates', coordsField.value);
                clearError(coordsField);
                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lng]).addTo(map);
            });

            // --- 3. DRAG N DROP LOGIC ---
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, () => dropArea.classList.add('drag-over'), false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, () => dropArea.classList.remove('drag-over'), false);
            });

            dropArea.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files.length > 0) {
                    photoInput.files = files; // Assign files ke input
                    handleFiles(files[0]); // Trigger cropper
                }
            }

            // --- 4. FILE & CROPPER LOGIC ---
            let cropper;
            const imageToCrop = document.getElementById('image-to-crop');
            const applyBtn = document.getElementById('apply-crop');

            photoInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) handleFiles(e.target.files[0]);
            });

            function handleFiles(file) {
                if (!file.type.startsWith('image/')) {
                    alert('Mohon unggah file gambar.');
                    return;
                }
                const reader = new FileReader();
                reader.onload = (event) => {
                    imageToCrop.src = event.target.result;
                    document.getElementById('cropperModal').classList.remove('hidden');
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(imageToCrop, {
                        aspectRatio: 1,
                        viewMode: 2
                    });
                };
                reader.readAsDataURL(file);
            }

            applyBtn.addEventListener('click', () => {
                const canvas = cropper.getCroppedCanvas({
                    width: 600,
                    height: 600
                });
                canvas.toBlob((blob) => {
                    const croppedFile = new File([blob], "photo.jpg", {
                        type: "image/jpeg"
                    });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(croppedFile);
                    photoInput.files = dataTransfer.files;
                    document.getElementById('cropped-preview').src = URL.createObjectURL(croppedFile);
                    document.getElementById('preview-container').classList.remove('hidden');
                    document.getElementById('cropperModal').classList.add('hidden');
                    clearError(photoInput);
                }, 'image/jpeg');
            });

            document.getElementById('cancel-crop').addEventListener('click', () => {
                document.getElementById('cropperModal').classList.add('hidden');
                photoInput.value = "";
            });

            // --- 5. VALIDATION & SUBMIT ---
            function checkFieldValidity(field) {
                if (field.type === 'file') {
                    if (field.files.length === 0) {
                        showError(field.closest('.bg-white'), 'Foto wajib diunggah');
                        return false;
                    } else {
                        clearError(field.closest('.bg-white'));
                        return true;
                    }
                }
                if (!field.value || field.value.trim() === "") {
                    showError(field, 'Kolom ini tidak boleh kosong');
                    return false;
                } else {
                    clearError(field);
                    return true;
                }
            }

            function showError(field, message) {
                field.classList.add('is-invalid');
                let parent = field.id === 'photo' ? field.closest('.bg-white') : field.parentNode;
                let err = parent.querySelector('.error-msg') || document.createElement('p');
                err.className = 'error-msg text-red-500 text-xs mt-1 font-semibold';
                err.innerText = message;
                if (!parent.querySelector('.error-msg')) parent.appendChild(err);
            }

            function clearError(field) {
                field.classList.remove('is-invalid');
                let parent = field.id === 'photo' ? field.closest('.bg-white') : field.parentNode;
                const err = parent.querySelector('.error-msg');
                if (err) err.remove();
            }

            validateFields.forEach(field => {
                field.addEventListener('blur', () => checkFieldValidity(field));
                field.addEventListener('change', () => checkFieldValidity(field));
            });

            form.addEventListener('submit', function(e) {
                let isFormValid = true;
                validateFields.forEach(f => {
                    if (!checkFieldValidity(f)) isFormValid = false;
                });
                if (!isFormValid) {
                    e.preventDefault();
                    const firstError = document.querySelector('.is-invalid');
                    if (firstError) firstError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                } else {
                    persistFields.forEach(field => localStorage.removeItem('profile_' + field.id || field.name));
                }
            });
        });
    </script>
</body>

</html>