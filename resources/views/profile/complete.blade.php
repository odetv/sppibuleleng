<x-guest-layout>
    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">

            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight sm:text-4xl">Lengkapi Profil Anda</h2>
                <p class="mt-3 text-lg text-gray-600">Pastikan data yang Anda masukkan sesuai dengan KTP/KK resmi untuk proses verifikasi.</p>
            </div>

            @if ($errors->any())
            <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 rounded-md shadow-sm">
                <div class="flex">
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-red-800">Terdapat kesalahan penginputan:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside grid grid-cols-1 md:grid-cols-2 gap-x-4">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('profile.store') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <div class="bg-white shadow-md border border-gray-100 rounded-xl p-6 sm:p-10">
                    <div class="flex items-center mb-8 pb-3 border-b border-gray-100">
                        <div class="bg-indigo-100 p-2 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 012-2h4a2 2 0 012 2v2m-6 0h6"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Identitas Kependudukan</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <x-input-label for="nik" :value="__('NIK (16 Digit)')" class="text-gray-700 font-medium" />
                            <x-text-input id="nik" class="block mt-2 w-full shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md" type="text" name="nik" :value="old('nik')" required placeholder="Masukkan 16 digit NIK" />
                        </div>
                        <div>
                            <x-input-label for="no_kk" :value="__('Nomor Kartu Keluarga')" class="text-gray-700 font-medium" />
                            <x-text-input id="no_kk" class="block mt-2 w-full shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md" type="text" name="no_kk" :value="old('no_kk')" required placeholder="Nomor KK sesuai dokumen" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="name" :value="__('Nama Lengkap')" class="text-gray-700 font-medium" />
                            <x-text-input id="name" class="block mt-2 w-full shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md" type="text" name="name" :value="old('name')" required placeholder="Nama lengkap sesuai KTP" />
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-md border border-gray-100 rounded-xl p-6 sm:p-10">
                    <div class="flex items-center mb-8 pb-3 border-b border-gray-100">
                        <div class="bg-indigo-100 p-2 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Informasi Pribadi</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div>
                            <x-input-label for="gender" :value="__('Jenis Kelamin')" class="font-medium" />
                            <select name="gender" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5">
                                <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="religion" :value="__('Agama')" class="font-medium" />
                            <x-text-input id="religion" class="block mt-2 w-full" type="text" name="religion" :value="old('religion')" required />
                        </div>
                        <div>
                            <x-input-label for="marital_status" :value="__('Status Perkawinan')" class="font-medium" />
                            <select name="marital_status" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5">
                                <option value="Belum Kawin" {{ old('marital_status') == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                                <option value="Kawin" {{ old('marital_status') == 'Kawin' ? 'selected' : '' }}>Kawin</option>
                                <option value="Cerai" {{ old('marital_status') == 'Cerai' ? 'selected' : '' }}>Cerai</option>
                            </select>
                        </div>
                        <div class="lg:col-span-1">
                            <x-input-label for="date_birthday" :value="__('Tanggal Lahir')" class="font-medium" />
                            <x-text-input id="date_birthday" class="block mt-2 w-full" type="date" name="date_birthday" :value="old('date_birthday')" required />
                        </div>
                        <div class="lg:col-span-2">
                            <x-input-label for="place_birthday" :value="__('Tempat Lahir')" class="font-medium" />
                            <x-text-input id="place_birthday" class="block mt-2 w-full" type="text" name="place_birthday" :value="old('place_birthday')" required />
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-md border border-gray-100 rounded-xl p-6 sm:p-10">
                    <div class="flex items-center mb-8 pb-3 border-b border-gray-100">
                        <div class="bg-indigo-100 p-2 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Domisili</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <x-input-label for="village" :value="__('Desa/Kelurahan')" />
                            <x-text-input id="village" class="block mt-2 w-full" type="text" name="village" :value="old('village')" required />
                        </div>
                        <div>
                            <x-input-label for="district" :value="__('Kecamatan')" />
                            <x-text-input id="district" class="block mt-2 w-full" type="text" name="district" :value="old('district')" required />
                        </div>
                        <div>
                            <x-input-label for="regency" :value="__('Kabupaten')" />
                            <x-text-input id="regency" class="block mt-2 w-full" type="text" name="regency" :value="old('regency')" required />
                        </div>
                        <div>
                            <x-input-label for="city" :value="__('Provinsi')" />
                            <x-text-input id="city" class="block mt-2 w-full" type="text" name="city" :value="old('city')" required />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="address" :value="__('Alamat Lengkap Rumah')" />
                            <textarea id="address" name="address" rows="3" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required placeholder="Jl. Contoh No. 123, RT 01/RW 02">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-md border border-gray-100 rounded-xl p-6 sm:p-10">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="w-6 h-6 text-indigo-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Pas Foto
                    </h3>
                    <div class="mt-2 flex items-center justify-center w-full">
                        <label for="photo" class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition duration-300">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-bold">Klik untuk unggah</span> atau seret foto ke sini</p>
                                <p class="text-xs text-gray-400 uppercase font-semibold">PNG atau JPG (Maks. 2MB)</p>
                            </div>
                            <input id="photo" name="photo" type="file" class="hidden" />
                        </label>
                    </div>
                </div>

                <div class="flex justify-center pt-6 pb-10">
                    <button type="submit" class="w-full md:w-2/3 py-4 px-6 border border-transparent shadow-xl text-lg font-black rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 transition-all duration-300 transform hover:-translate-y-1">
                        SIMPAN DATA PROFIL & LANJUTKAN
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>