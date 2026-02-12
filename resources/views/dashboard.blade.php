<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">

                        <div class="flex-shrink-0">
                            @if(Auth::user()->person && Auth::user()->person->photo)
                            <img class="h-40 w-40 rounded-xl object-cover shadow-md border-4 border-white"
                                src="{{ asset('storage/' . Auth::user()->person->photo) }}"
                                alt="Foto Profil">
                            @else
                            <div class="h-40 w-40 rounded-xl bg-indigo-100 flex items-center justify-center shadow-inner">
                                <svg class="h-20 w-20 text-indigo-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            @endif
                        </div>

                        <div class="flex-1 text-center md:text-left">
                            <h3 class="text-2xl font-extrabold text-gray-900">
                                {{ Auth::user()->person->name ?? 'User' }}
                            </h3>
                            <p class="text-indigo-600 font-semibold">
                                {{ Auth::user()->role->name_role ?? 'Guest' }}
                            </p>

                            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <span class="text-gray-500 block italic">NIK</span>
                                    <span class="font-bold text-gray-800">{{ Auth::user()->person->nik ?? '-' }}</span>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <span class="text-gray-500 block italic">Email</span>
                                    <span class="font-bold text-gray-800">{{ Auth::user()->email }}</span>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <span class="text-gray-500 block italic">Status Akun</span>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 uppercase">
                                        {{ Auth::user()->status_user }}
                                    </span>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <span class="text-gray-500 block italic">Usia</span>
                                    <span class="font-bold text-gray-800">{{ Auth::user()->person->age ?? '-' }} Tahun</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>