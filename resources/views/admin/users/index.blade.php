<x-app-layout title="Daftar Pengguna">
    {{-- CSS Dependencies --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="py-10 p-4 text-slate-800 text-[14px]">
        <div class="w-full mx-auto sm:px-6 lg:px-8 space-y-10">

            {{-- 1. HEADER SECTION --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 uppercase tracking-wider">
                            Daftar Pengguna
                        </h2>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                            Kelola akses, jabatan, dan status pengguna dalam sistem
                        </p>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-4 py-2 text-[10px] font-bold rounded bg-white text-slate-600 uppercase border border-slate-200 tracking-widest shadow-sm">
                            {{ $allUsers->count() }} Pengguna Terdaftar
                        </span>
                    </div>
                </div>
            </div>

            {{-- 1. STATS SECTION --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @foreach([
                ['Total', $stats['total'], 'slate', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                ['Terverifikasi', $stats['active'], 'emerald', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['Menunggu Verifikasi', $stats['pending'], 'yellow', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['Telah Dihapus', $trashedUsers->count(), 'rose', 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636']
                ] as $st)
                <div class="bg-white p-6 rounded-xl border border-slate-200 flex items-center shadow-sm">
                    <div class="p-3 bg-{{$st[2]}}-50 rounded-lg mr-4 text-{{$st[2]}}-600 border border-{{$st[2]}}-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{$st[3]}}"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pengguna {{$st[0]}}</p>
                        <h4 class="text-xl font-bold text-slate-800">{{$st[1]}}</h4>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- 2. DAFTAR TUNGGU VERIFIKASI --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="font-bold uppercase tracking-wider text-slate-700">Antrian Verifikasi Pengguna</h3>
                    @php $pendingUsers = $allUsers->where('status_user', 'pending'); @endphp
                    @if($pendingUsers->count() > 0)
                    <button type="button" onclick="openApproveAllModal()" class="text-[11px] font-bold uppercase tracking-wider text-emerald-600 bg-white border border-emerald-200 px-4 py-2 rounded-lg hover:bg-emerald-600 hover:text-white transition-all cursor-pointer">Approve Semua</button>
                    @endif
                </div>
                <div class="overflow-x-auto scrollbar-thin">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50/50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                <th class="px-6 py-3">Pendaftar</th>
                                <th class="px-6 py-3 text-center">Waktu Bergabung</th>
                                <th class="px-6 py-3 text-right">Penentuan Akses & Jabatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($pendingUsers as $user)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-slate-700">{{ $user->email }}</span> <br>
                                    <span class="text-xs text-slate-400 font-sans">{{ $user->phone }}</span>
                                </td>
                                <td class="px-6 py-4 text-center text-[12px] text-slate-500 font-medium whitespace-nowrap">
                                    {{ $user->created_at->translatedFormat('d F Y H:i:s') }} WITA
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('admin.users.approve', $user->id_user) }}" method="POST" class="flex justify-end items-center gap-2">
                                        @csrf
                                        <select name="id_ref_role" required class="text-[11px] font-bold border-slate-200 rounded-md py-1.5 cursor-pointer outline-none focus:ring-1 focus:ring-emerald-500">
                                            <option value="">Pilih Role</option>
                                            @foreach($roles as $r) <option value="{{$r->id_ref_role}}" {{ $r->name_role == 'Guest' ? 'selected' : '' }}>{{$r->name_role}}</option> @endforeach
                                        </select>
                                        <select name="id_ref_position" class="text-[11px] font-bold border-slate-200 rounded-md py-1.5 cursor-pointer outline-none focus:ring-1 focus:ring-emerald-500">
                                            <option value="">Tanpa Jabatan</option>
                                            @foreach($positions as $p) <option value="{{$p->id_ref_position}}">{{$p->name_position}}</option> @endforeach
                                        </select>
                                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-[11px] font-bold uppercase tracking-wider rounded-md hover:bg-emerald-700 transition-all cursor-pointer shadow-sm ml-2">Approve</button>
                                        <button type="button" onclick="confirmDelete('{{ $user->id_user }}', '{{ $user->email }}', false)" class="p-2 text-rose-600 hover:bg-rose-50 rounded cursor-pointer transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-6 text-center opacity-20 text-[11px] font-bold uppercase">Antrian Kosong</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 3. DATABASE SELURUH PENGGUNA --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-700 uppercase tracking-wider">Daftar Semua Pengguna</h3>
                </div>
                <div class="overflow-x-auto scrollbar-thin">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                <th class="px-6 py-4">Nama & Email</th>
                                <th class="px-6 py-4 text-center">Jabatan</th>
                                <th class="px-6 py-4 text-center">Role</th>
                                <th class="px-6 py-4 text-center">Waktu Dibuat</th>
                                <th class="px-6 py-4 text-center">Waktu Diperbarui</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($allUsers->where('status_user', '!=', 'inactive') as $u)
                            @php
                            $person = $u->person;
                            $userName = $person->name ?? ($u->status_user == 'pending' ? 'User Belum Diverifikasi' : 'User Tanpa Profil');
                            $userPhoto = ($person && $person->photo) ? asset('storage/' . $person->photo) : 'https://ui-avatars.com/api/?name='.urlencode($userName).'&background=random&color=fff';
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors {{ !$person ? 'bg-slate-50/50 opacity-60' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full overflow-hidden bg-slate-100 shrink-0">
                                            <img src="{{ $userPhoto }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="flex flex-col text-[14px]">
                                            <span class="font-bold text-slate-700 leading-tight">{{ $userName }}</span>
                                            <span class="text-slate-400 font-sans">{{ $u->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center text-slate-600 font-medium whitespace-nowrap">
                                    {{ $person->position->name_position ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-indigo-600">
                                    {{ $u->role->name_role ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-center text-[12px] text-slate-500 font-medium">
                                    {{ $u->created_at->translatedFormat('d F Y H:i:s') }} WITA
                                </td>
                                <td class="px-6 py-4 text-center text-[12px] text-slate-500 font-medium">
                                    {{ $u->updated_at->translatedFormat('d F Y H:i:s') }} WITA
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded border {{ $u->status_user == 'active' ? 'text-emerald-600 bg-emerald-50 border-emerald-100' : 'text-yellow-600 bg-yellow-50 border-yellow-100' }}">
                                        {{ strtoupper($u->status_user) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center gap-1">
                                        @if($person)
                                        <button onclick='openMasterEditModal(@json($u), @json($person), false)' class="p-2 text-slate-400 hover:text-indigo-600 cursor-pointer transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg></button>
                                        @else
                                        <button disabled class="p-2 text-slate-200 cursor-not-allowed"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg></button>
                                        @endif
                                        @if($u->id_user !== Auth::id())
                                        <button onclick="confirmDelete('{{ $u->id_user }}', '{{ $u->email }}', false)" class="p-2 text-rose-600 hover:bg-rose-100 rounded transition-all cursor-pointer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 4. TABEL TEMPAT SAMPAH (Status Inactive) --}}
            <div class="bg-rose-50/20 rounded-xl border border-rose-100 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-rose-100 bg-rose-50 flex justify-between items-center text-rose-800">
                    <h3 class="font-bold uppercase tracking-wider text-[14px]">Daftar Pengguna yang Telah Dihapus</h3>
                    @if($trashedUsers->count() > 0)
                    <button onclick="confirmDeleteAll()" class="text-[11px] font-bold uppercase tracking-wider text-rose-600 bg-white border border-rose-200 px-4 py-2 rounded-lg hover:bg-rose-600 hover:text-white transition-all cursor-pointer">Kosongkan Sampah</button>
                    @endif
                </div>
                <div class="overflow-x-auto scrollbar-thin">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-rose-50/50 text-[11px] font-bold uppercase tracking-wider text-rose-400 border-b border-rose-100">
                                <th class="px-6 py-4">Nama & Email</th>
                                <th class="px-6 py-4 text-center">Jabatan</th>
                                <th class="px-6 py-4 text-center">Role</th>
                                <th class="px-6 py-4 text-center">Waktu Dibuat</th>
                                <th class="px-6 py-4 text-center">Waktu Dihapus</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-rose-50">
                            @forelse($trashedUsers as $tu)
                            @php
                            $personTrashed = \App\Models\Person::withTrashed()->find($tu->id_person);
                            $trashName = $personTrashed->name ?? ($tu->status_user == 'pending' ? 'User Belum Diverifikasi' : 'User Tanpa Profil');
                            $trashPhoto = ($personTrashed && $personTrashed->photo) ? asset('storage/' . $personTrashed->photo) : 'https://ui-avatars.com/api/?name='.urlencode($trashName).'&background=random&color=fff';
                            @endphp
                            <tr class="hover:bg-rose-50/30 transition-colors opacity-80">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full overflow-hidden bg-slate-100 shrink-0 grayscale">
                                            <img src="{{ $trashPhoto }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="flex flex-col text-[14px]">
                                            <span class="text-slate-700 font-bold leading-tight">{{ $trashName }}</span>
                                            <span class="text-slate-400 font-sans">{{ $tu->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center text-slate-400 font-medium whitespace-nowrap text-[12px]">
                                    {{ $personTrashed->position->name_position ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-slate-400 text-[12px]">
                                    {{ $tu->role->name_role ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-center text-[12px] text-slate-500 font-medium whitespace-nowrap">
                                    {{ $tu->created_at->translatedFormat('d F Y H:i:s') }} WITA
                                </td>
                                <td class="px-6 py-4 text-center text-[12px] text-slate-500 font-medium whitespace-nowrap">
                                    {{ $tu->deleted_at->translatedFormat('d F Y H:i:s') }} WITA
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded border text-rose-600 bg-rose-50 border-rose-100">
                                        INACTIVE
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end items-center gap-2">
                                    <form action="{{ route('admin.users.restore', $tu->id_user) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded cursor-pointer transition-colors" title="Pulihkan">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    <button onclick="confirmDelete('{{ $tu->id_user }}', '{{ $tu->email }}', true)" class="p-2 text-rose-600 hover:bg-rose-50 rounded cursor-pointer transition-colors" title="Hapus Permanen">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center opacity-20 text-[11px] font-bold uppercase">Sampah Kosong</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DELETE --}}
    <div id="deleteModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-sm w-full p-8 text-center border border-slate-100">
            <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 uppercase tracking-widest mb-2" id="delete_modal_title">Hapus Pengguna?</h3>
            <p class="text-sm text-slate-500 mb-8 leading-relaxed" id="delete_modal_info"></p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 py-3 text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50 rounded-xl hover:bg-slate-100 cursor-pointer transition-colors font-sans">Batal</button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" id="delete_btn_text" class="w-full py-3 text-[11px] font-bold uppercase tracking-wider text-white bg-rose-600 rounded-xl shadow-lg hover:bg-rose-700 cursor-pointer transition-colors font-sans font-bold">Hapus Sekarang</button>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL APPROVE ALL --}}
    <div id="approveAllModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeApproveAllModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 border border-slate-100">
            <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 uppercase tracking-widest mb-2 text-center">Approve Semua Pendaftar?</h3>
            <p class="text-sm text-center text-slate-500 mb-6 leading-relaxed">Tentukan Role dan Jabatan default untuk seluruh antrian pendaftar saat ini.</p>

            <form action="{{ route('admin.users.approve-all') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1 block">Role Akses Sistem</label>
                    <select name="id_ref_role" required class="w-full border-slate-200 rounded-xl text-sm py-3 px-4 focus:ring-emerald-500 outline-none cursor-pointer font-sans shadow-sm">
                        <option value="">-- Pilih Role --</option>
                        @foreach($roles as $r) <option value="{{$r->id_ref_role}}">{{$r->name_role}}</option> @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1 block">Jabatan Personel</label>
                    <select name="id_ref_position" class="w-full border-slate-200 rounded-xl text-sm py-3 px-4 focus:ring-emerald-500 outline-none cursor-pointer font-sans shadow-sm">
                        <option value="">Tanpa Jabatan</option>
                        @foreach($positions as $p) <option value="{{$p->id_ref_position}}">{{$p->name_position}}</option> @endforeach
                    </select>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeApproveAllModal()" class="flex-1 py-3 text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50 rounded-xl hover:bg-slate-100 cursor-pointer transition-colors font-sans">Batal</button>
                    <button type="submit" class="flex-1 py-3 text-[11px] font-bold uppercase tracking-wider text-white bg-emerald-600 rounded-xl shadow-lg hover:bg-emerald-700 cursor-pointer transition-colors font-sans font-bold">Verifikasi Semua</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MASTER MODAL EDIT --}}
    <div id="masterModal" class="fixed inset-0 z-[99] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeMasterEditModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-6xl overflow-hidden transform transition-all font-sans text-sm">
            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center text-slate-800">
                <h3 class="text-[14px] font-bold uppercase tracking-widest" id="modal_header_title">Edit Master Pengguna</h3>
                <button type="button" onclick="closeMasterEditModal()" class="text-slate-400 hover:text-slate-600 text-2xl cursor-pointer transition-colors">&times;</button>
            </div>
            <form id="masterForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="method_field"></div>
                <div class="p-6 md:p-8 grid grid-cols-1 lg:grid-cols-3 gap-8 max-h-[75vh] overflow-y-auto text-left">
                    {{-- Form Fields --}}
                    <div class="space-y-5">
                        <p class="text-[11px] font-bold uppercase text-indigo-500 border-b pb-2 tracking-widest">Foto & Akun</p>
                        <div class="flex flex-col items-center bg-gray-50 rounded-xl p-4 border-2 border-dashed border-gray-200">
                            {{-- Preview Box Utility Class Replacement --}}
                            <div class="w-[120px] h-[160px] border-4 border-white shadow-md rounded-xl overflow-hidden bg-slate-50 cursor-pointer mb-4" onclick="document.getElementById('photo').click()">
                                <img id="cropped-preview" src="" class="w-full h-full object-cover">
                            </div>
                            <label for="photo" class="cursor-pointer text-center group">
                                <span class="text-[11px] font-bold uppercase text-indigo-600 group-hover:text-indigo-800 transition-colors">Ganti Pas Foto</span>
                                <input id="photo" name="photo" type="file" class="hidden" accept="image/*" />
                            </label>
                        </div>
                        <div><label class="text-[11px] font-bold uppercase text-slate-500 block">Email Sistem</label><input type="email" name="email" id="f_email" class="w-full mt-2 px-4 py-2 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all"></div>
                        <div><label class="text-[11px] font-bold uppercase text-slate-500 block">WhatsApp</label><input type="text" name="phone" id="f_phone" class="w-full mt-2 px-4 py-2 bg-slate-50 border-none rounded-lg outline-none"></div>
                        <div><label class="text-[11px] font-bold uppercase text-slate-500 block">Nama Lengkap</label><input type="text" name="name" id="f_name" class="w-full mt-2 px-4 py-2 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                    </div>
                    <div class="space-y-5">
                        <p class="text-[11px] font-bold uppercase text-indigo-500 border-b pb-2 tracking-widest">Informasi Pribadi</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div><label class="text-[11px] font-bold uppercase text-slate-500">NIK</label><input type="text" name="nik" id="f_nik" class="w-full mt-2 px-4 py-2 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                            <div><label class="text-[11px] font-bold uppercase text-slate-500">No. KK</label><input type="text" name="no_kk" id="f_no_kk" class="w-full mt-2 px-4 py-2 bg-slate-50 border-none rounded-lg outline-none"></div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div><label class="text-[11px] font-bold uppercase text-slate-500">Tgl Lahir</label><input type="date" name="date_birthday" id="f_date" class="w-full mt-2 px-3 py-2 bg-slate-50 border-none rounded-lg outline-none"></div>
                            <div><label class="text-[11px] font-bold uppercase text-slate-500">Gender</label><select name="gender" id="f_gender" class="w-full mt-2 px-3 py-2 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select></div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div><label class="text-[11px] font-bold uppercase text-slate-500">Agama</label>
                                <select name="religion" id="f_religion" class="w-full mt-2 px-3 py-2 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                                    @foreach(['Islam', 'Kristen', 'Katholik', 'Hindu', 'Buddha', 'Khonghucu'] as $rel) <option value="{{ $rel }}">{{ $rel }}</option> @endforeach
                                </select>
                            </div>
                            <div><label class="text-[11px] font-bold uppercase text-slate-500">NPWP</label><input type="text" name="npwp" id="f_npwp" class="w-full mt-2 px-4 py-2 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                        </div>
                        <div><label class="text-[11px] font-bold uppercase text-slate-500 block">Tempat Lahir</label><input type="text" name="place_birthday" id="f_place" class="w-full mt-2 px-4 py-2 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[11px] font-bold uppercase text-slate-500 block">Umur</label>
                                <input type="number" name="age" id="f_age" readonly class="w-full mt-2 px-4 py-2 bg-slate-100 border-none rounded-lg outline-none cursor-not-allowed text-sm" placeholder="...">
                            </div>
                            <div><label class="text-[11px] font-bold uppercase text-slate-500">Status Nikah</label>
                                <select name="marital_status" id="f_marital" class="w-full mt-2 px-3 py-2 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                                    @foreach(['Belum Kawin', 'Kawin', 'Janda', 'Duda'] as $status) <option value="{{ $status }}">{{ $status }}</option> @endforeach
                                </select>
                            </div>
                        </div>
                        <div><label class="text-[11px] font-bold uppercase text-slate-500 block">Pendidikan</label><input type="text" name="title_education" id="f_edu" class="w-full mt-2 px-4 py-2 bg-slate-50 border-none rounded-lg outline-none"></div>
                    </div>
                    <div class="space-y-5">
                        <p class="text-[11px] font-bold uppercase text-indigo-500 border-b pb-2 tracking-widest">Domisili & Jabatan</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div><label class="text-[11px] font-bold uppercase text-slate-500">Provinsi</label><select name="province" id="f_prov" class="w-full mt-2 px-3 py-2 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                                    <option value="Bali">Bali</option>
                                </select></div>
                            <div><label class="text-[11px] font-bold uppercase text-slate-500">Kabupaten</label><select name="regency" id="f_reg" class="w-full mt-2 px-3 py-2 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                                    <option value="Buleleng">Buleleng</option>
                                </select></div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div><label class="text-[11px] font-bold uppercase text-slate-500">Kecamatan</label>
                                <select name="district" id="f_dist" class="w-full mt-2 px-3 py-2 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                                    @foreach(['Tejakula', 'Kubutambahan', 'Sawan', 'Sukasada', 'Buleleng', 'Banjar', 'Seririt', 'Busungbiu', 'Gerokgak'] as $kec) <option value="{{ $kec }}">{{ $kec }}</option> @endforeach
                                </select>
                            </div>
                            <div><label class="text-[11px] font-bold uppercase text-slate-500">Desa</label><input type="text" name="village" id="f_vill" class="w-full mt-2 px-3 py-2 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"></div>
                        </div>
                        <div><label class="text-[11px] font-black uppercase text-indigo-600 block font-sans">GPS (Klik Peta)</label>
                            <input type="text" name="gps_coordinates" id="f_gps" readonly class="w-full px-3 py-2 bg-indigo-50 text-indigo-700 text-sm border-none rounded-lg outline-none shadow-inner font-sans">
                        </div>
                        {{-- Map Area --}}
                        <div id="map" class="h-[250px] w-full rounded-xl mt-2 z-10 border border-slate-200"></div>
                        <div><label class="text-[11px] font-bold uppercase text-slate-500 block">Alamat Lengkap</label><textarea name="address" id="f_address" rows="1" class="w-full mt-2 px-3 py-2 bg-slate-50 border-none rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-sans"></textarea></div>
                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <div><label class="text-[11px] font-bold uppercase text-indigo-500 block font-sans">Role</label><select name="id_ref_role" id="f_role" class="w-full mt-2 px-2 py-2 bg-white border border-slate-200 rounded text-xs font-bold outline-none text-indigo-600 cursor-pointer font-sans">@foreach($roles as $r)<option value="{{$r->id_ref_role}}">{{$r->name_role}}</option>@endforeach</select></div>
                            <div><label class="text-[11px] font-bold uppercase text-indigo-500 block font-sans">Jabatan</label><select name="id_ref_position" id="f_pos" class="w-full mt-2 px-2 py-2 bg-white border border-slate-200 rounded text-xs font-bold outline-none text-indigo-600 cursor-pointer font-sans">@foreach($positions as $p)<option value="{{$p->id_ref_position}}">{{$p->name_position}}</option>@endforeach</select></div>
                        </div>
                    </div>
                </div>
                <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-4">
                    <button type="button" onclick="closeMasterEditModal()" class="flex-1 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 cursor-pointer transition-all font-sans">Batal</button>
                    <button type="submit" id="submit_btn" class="flex-1 py-4 text-[11px] font-bold uppercase tracking-wider text-white bg-slate-800 rounded-xl shadow-lg hover:bg-slate-900 cursor-pointer transition-all active:scale-[0.98] font-sans">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- CROPPER MODAL --}}
    <div id="cropperModal" class="fixed inset-0 z-[10000] hidden flex items-center justify-center p-4 bg-black/85 transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden font-sans">
            <div class="p-6 border-b bg-gray-50 flex justify-between items-center text-slate-800 font-bold uppercase tracking-widest text-sm">
                <h3>Potong Pas Foto</h3>
            </div>
            <div class="p-6 bg-gray-200 flex justify-center">
                <div class="max-w-full max-h-[60vh]"><img id="image-to-crop" class="block max-w-full"></div>
            </div>
            <div class="p-6 border-t flex justify-end space-x-3 text-[11px] font-bold uppercase">
                <button onclick="document.getElementById('cropperModal').classList.add('hidden')" type="button" class="px-6 py-2 text-gray-500 cursor-pointer hover:text-gray-400 transition-colors">Batal</button>
                <button id="apply-crop-btn" type="button" class="px-8 py-2 bg-slate-800 text-white rounded-lg shadow-lg cursor-pointer hover:bg-indigo-700 transition-colors">Gunakan</button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        let mapObj, markerObj, cropperObj;
        const photoInputEl = document.getElementById('photo');

        function setupMap(lat, lng) {
            if (mapObj) mapObj.remove();
            mapObj = L.map('map').setView([lat, lng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapObj);
            markerObj = L.marker([lat, lng]).addTo(mapObj);
            mapObj.on('click', function(e) {
                if (markerObj) mapObj.removeLayer(markerObj);
                markerObj = L.marker(e.latlng).addTo(mapObj);
                document.getElementById('f_gps').value = `${e.latlng.lat}, ${e.latlng.lng}`;
            });
        }

        window.openMasterEditModal = function(user, person, isApprove) {
            const form = document.getElementById('masterForm');
            const methodField = document.getElementById('method_field');

            if (isApprove) {
                form.action = `/admin/users/${user.id_user}/approve`;
                methodField.innerHTML = '';
            } else {
                form.action = `/admin/users/${user.id_user}/update`;
                methodField.innerHTML = '@method("PATCH")';
            }

            document.getElementById('f_email').value = user.email || '';
            document.getElementById('f_phone').value = user.phone || '';
            document.getElementById('f_role').value = user.id_ref_role || '';
            document.getElementById('f_name').value = person?.name || '';
            document.getElementById('f_nik').value = person?.nik || '';
            document.getElementById('f_no_kk').value = person?.no_kk || '';
            document.getElementById('f_date').value = person?.date_birthday || '';
            document.getElementById('f_gender').value = person?.gender || 'L';
            document.getElementById('f_religion').value = person?.religion || 'Islam';
            document.getElementById('f_npwp').value = person?.npwp || '';
            document.getElementById('f_place').value = person?.place_birthday || '';
            document.getElementById('f_age').value = person?.age || '';
            document.getElementById('f_edu').value = person?.title_education || '';
            document.getElementById('f_marital').value = person?.marital_status || 'Belum Kawin';
            document.getElementById('f_prov').value = person?.province || 'Bali';
            document.getElementById('f_reg').value = person?.regency || 'Buleleng';
            document.getElementById('f_dist').value = person?.district || 'Buleleng';
            document.getElementById('f_vill').value = person?.village || '';
            document.getElementById('f_address').value = person?.address || '';
            document.getElementById('f_gps').value = person?.gps_coordinates || '';
            document.getElementById('f_pos').value = person?.id_ref_position || '';

            const preview = document.getElementById('cropped-preview');
            preview.src = person?.photo ? `/storage/${person.photo}` : `https://ui-avatars.com/api/?name=${encodeURIComponent(person?.name || user.email)}&background=random&color=fff`;

            document.getElementById('masterModal').classList.remove('hidden');

            let lat = -8.112,
                lng = 115.091;
            if (person?.gps_coordinates && person.gps_coordinates.includes(',')) {
                const p = person.gps_coordinates.split(',');
                lat = parseFloat(p[0]);
                lng = parseFloat(p[1]);
            }
            setTimeout(() => {
                setupMap(lat, lng);
                mapObj.invalidateSize();
            }, 400);
        }

        window.closeMasterEditModal = function() {
            document.getElementById('masterModal').classList.add('hidden');
        }

        window.confirmDelete = function(userId, email, isForce) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            const title = document.getElementById('delete_modal_title');
            const info = document.getElementById('delete_modal_info');
            const btn = document.getElementById('delete_btn_text');

            if (isForce) {
                form.action = `/admin/users/${userId}/force`;
                title.innerText = "Hapus Permanen?";
                info.innerText = `Data akun ${email} akan dihapus selamanya.`;
                btn.innerText = "Hapus Sekarang";
            } else {
                form.action = `/admin/users/${userId}`;
                title.innerText = "Hapus Pengguna?";
                info.innerText = `Akun ${email} akan dipindahkan ke tempat sampah.`;
                btn.innerText = "Hapus Sekarang";
            }
            modal.classList.remove('hidden');
        }

        window.confirmDeleteAll = function() {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            const title = document.getElementById('delete_modal_title');
            const info = document.getElementById('delete_modal_info');
            const btn = document.getElementById('delete_btn_text');
            form.action = `{{ route('admin.users.force-delete-all') }}`;
            title.innerText = "Kosongkan Sampah?";
            info.innerText = "Semua data di tempat sampah akan dihapus permanen.";
            btn.innerText = "Kosongkan Semua";
            modal.classList.remove('hidden');
        }

        window.closeDeleteModal = function() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        window.openApproveAllModal = function() {
            document.getElementById('approveAllModal').classList.remove('hidden');
        }

        window.closeApproveAllModal = function() {
            document.getElementById('approveAllModal').classList.add('hidden');
        }

        photoInputEl.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = (ev) => {
                    const imgToCrop = document.getElementById('image-to-crop');
                    imgToCrop.src = ev.target.result;
                    document.getElementById('cropperModal').classList.remove('hidden');
                    if (cropperObj) cropperObj.destroy();
                    cropperObj = new Cropper(imgToCrop, {
                        aspectRatio: 2 / 3,
                        viewMode: 2,
                        dragMode: 'move',
                        autoCropArea: 1,
                        responsive: true
                    });
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        document.getElementById('apply-crop-btn').onclick = function() {
            const canvas = cropperObj.getCroppedCanvas({
                width: 400,
                height: 600
            });
            canvas.toBlob((blob) => {
                const file = new File([blob], "photo.jpg", {
                    type: "image/jpeg"
                });
                const dt = new DataTransfer();
                dt.items.add(file);
                photoInputEl.files = dt.files;
                document.getElementById('cropped-preview').src = URL.createObjectURL(blob);
                document.getElementById('cropperModal').classList.add('hidden');
            }, 'image/jpeg');
        };

        document.getElementById('f_date').addEventListener('change', function() {
            const birthDate = new Date(this.value);
            if (isNaN(birthDate)) return;

            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();

            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            document.getElementById('f_age').value = age;
        });
    </script>
</x-app-layout>