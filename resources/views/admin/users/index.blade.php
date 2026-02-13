<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Verifikasi Pengguna Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                    <div class="p-3 bg-indigo-100 rounded-lg mr-4 text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase">Total User</p>
                        <h4 class="text-xl font-bold text-gray-800">{{ $stats['total'] }}</h4>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg mr-4 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase">Aktif</p>
                        <h4 class="text-xl font-bold text-gray-800">{{ $stats['active'] }}</h4>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg mr-4 text-yellow-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase">Siap Verifikasi</p>
                        <h4 class="text-xl font-bold text-gray-800">{{ $stats['pending'] - $stats['incomplete'] }}</h4>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-red-100 flex items-center">
                    <div class="p-3 bg-red-100 rounded-lg mr-4 text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase text-red-600">Belum Isi Profil</p>
                        <h4 class="text-xl font-bold text-gray-800">{{ $stats['incomplete'] }}</h4>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-3">Foto</th>
                                    <th class="px-6 py-3">Identitas</th>
                                    <th class="px-6 py-3">Info Pribadi</th>
                                    <th class="px-6 py-3">Pilih Role & Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                <tr class="bg-white border-b hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        @if($user->person && $user->person->photo)
                                        <img src="{{ asset('storage/' . $user->person->photo) }}" class="w-12 h-12 rounded-lg object-cover shadow-sm border">
                                        @else
                                        <div class="w-12 h-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                            <span class="text-[10px] text-gray-400 italic">No Data</span>
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $user->person->name ?? 'Belum Isi Nama' }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->person->nik ?? '-' }}</div>
                                        <div class="text-[10px] text-indigo-500 font-mono">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        <div>{{ ($user->person->gender ?? '') == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                        <div>{{ $user->person->age ?? '?' }} Tahun</div>
                                        <div class="italic text-gray-400">{{ $user->person->district ?? '' }}, {{ $user->person->province ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('admin.users.approve', $user->id_user) }}" method="POST" class="flex items-center space-x-2">
                                            @csrf
                                            <select name="id_ref_person_role" required class="text-xs rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 py-1 shadow-sm">
                                                <option value="" disabled selected>Pilih Role</option>
                                                @foreach($roles as $role)
                                                <option value="{{ $role->id_ref_person_role }}" {{ $user->id_ref_person_role == $role->id_ref_person_role ? 'selected' : '' }}>
                                                    {{ $role->name_role }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-1.5 px-4 rounded-lg text-xs transition transform hover:scale-105 shadow-md">
                                                Verifikasi
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">
                                        Tidak ada pengguna yang menunggu verifikasi profil.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>