<x-app-layout title="Manajemen Pengguna">
    {{-- CSS Dependencies --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        .is-invalid {
            border: 2px solid #ef4444 !important;
        }

        #map {
            height: 250px;
            width: 100%;
            border-radius: 0.75rem;
            margin-top: 0.5rem;
            z-index: 1;
            border: 1px solid #e2e8f0;
        }

        .drag-over {
            border-color: #4f46e5 !important;
            background-color: #f5f3ff !important;
        }

        #cropperModal {
            background-color: rgba(0, 0, 0, 0.85);
            z-index: 10000;
        }

        .overflow-x-auto {
            scrollbar-width: thin;
        }

        .preview-box {
            width: 120px;
            height: 160px;
            border: 4px solid white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 0.75rem;
            overflow: hidden;
            background: #f8fafc;
            cursor: pointer;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        /* Font Standardization - Konsistensi Penuh */
        .font-app,
        table,
        button,
        input,
        select,
        textarea,
        span,
        p,
        h3,
        h4,
        div {
            font-family: inherit !important;
            font-size: 14px !important;
        }

        /* Menyeragamkan ukuran teks isi tabel sesuai permintaan */
        .text-table-standard {
            font-size: 0.875rem !important;
        }

        /* text-sm */
        .text-label-caps {
            font-size: 11px !important;
            font-weight: 700;
            letter-spacing: 0.025em;
            text-transform: uppercase;
        }

        .text-timestamp {
            font-size: 12px !important;
            color: #64748b;
            font-weight: 500;
            white-space: nowrap;
        }

        .status-badge {
            font-size: 9px !important;
            font-weight: 800;
            text-transform: uppercase;
            padding: 2px 6px;
            border-radius: 4px;
            border-width: 1px;
        }
    </style>

    <div class="py-10 p-4 font-app text-slate-800">
        <div class="w-full mx-auto sm:px-6 lg:px-8 space-y-10">

            {{-- 1. STATS SECTION --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach([['Total', $stats['total'], 'slate'], ['Aktif', $stats['active'], 'emerald'], ['Pending', $stats['pending'], 'amber']] as $st)
                <div class="bg-white p-6 rounded-xl border border-slate-200 flex items-center shadow-sm">
                    <div class="p-3 bg-{{$st[2]}}-50 rounded-lg mr-4 text-{{$st[2]}}-600 border border-{{$st[2]}}-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-label-caps text-slate-400">Pengguna {{$st[0]}}</p>
                        <h4 class="text-xl font-bold text-slate-800">{{$st[1]}}</h4>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- 2. DAFTAR TUNGGU VERIFIKASI --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="font-bold uppercase tracking-wider text-slate-700">Antrian Verifikasi</h3>
                    @php $pendingUsers = $allUsers->where('status_user', 'pending'); @endphp
                    @if($pendingUsers->count() > 0)
                    <form action="{{ route('admin.users.approve-all') }}" method="POST" class="flex items-center gap-2" onsubmit="return confirm('Verifikasi semua pendaftar sekarang?')">
                        @csrf
                        <select name="id_ref_role" required class="text-[10px] font-bold border-slate-200 rounded-lg focus:ring-emerald-500 py-1 cursor-pointer">
                            <option value="">Pilih Role</option>
                            @foreach($roles as $r) <option value="{{$r->id_ref_role}}" {{ $r->name_role == 'Guest' ? 'selected' : '' }}>{{$r->name_role}}</option> @endforeach
                        </select>
                        <button type="submit" class="text-label-caps text-emerald-600 bg-white border border-emerald-200 px-4 py-2 rounded-lg hover:bg-emerald-600 hover:text-white transition-all cursor-pointer">Approve Semua</button>
                    </form>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-table-standard">
                        <thead>
                            <tr class="bg-slate-50/50 text-label-caps text-slate-400 border-b border-slate-100">
                                <th class="px-6 py-3">Pendaftar</th>
                                <th class="px-6 py-3 text-center">Waktu Bergabung</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($pendingUsers as $user)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-slate-700">{{ $user->email }}</span> <br>
                                    <span class="text-xs text-slate-400">{{ $user->phone }}</span>
                                </td>
                                <td class="px-6 py-4 text-center text-timestamp">
                                    {{ $user->created_at->translatedFormat('d F Y H:i') }} WITA
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('admin.users.approve', $user->id_user) }}" method="POST" class="flex justify-end items-center gap-2">
                                        @csrf
                                        <select name="id_ref_role" required class="text-[10px] font-bold border-slate-200 rounded-md py-1 cursor-pointer">
                                            @foreach($roles as $r) <option value="{{$r->id_ref_role}}" {{ $r->name_role == 'Guest' ? 'selected' : '' }}>{{$r->name_role}}</option> @endforeach
                                        </select>
                                        <button type="submit" class="px-4 py-1.5 bg-emerald-600 text-white text-label-caps rounded hover:bg-emerald-700 transition-all cursor-pointer shadow-sm">Approve</button>
                                        <button type="button" onclick="confirmDelete('{{ $user->id_user }}', '{{ $user->email }}', false)" class="p-2 text-rose-600 hover:bg-rose-50 rounded cursor-pointer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-16 text-center opacity-30 text-label-caps tracking-[2px]">Antrian Kosong</td>
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
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-table-standard">
                        <thead>
                            <tr class="bg-slate-50 text-label-caps text-slate-400 border-b border-slate-100">
                                <th class="px-6 py-4">Nama & Email</th>
                                <th class="px-6 py-4 text-center">Role</th>
                                <th class="px-6 py-4 text-center">Dibuat Pada</th>
                                <th class="px-6 py-4 text-center">Update Pada</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($allUsers as $u)
                            @php $hasPerson = !is_null($u->id_person); @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors {{ !$hasPerson ? 'bg-slate-50/50 opacity-60' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full overflow-hidden bg-slate-100 shrink-0">
                                            <img src="{{ ($u->person && $u->person->photo) ? asset('storage/' . $u->person->photo) : 'https://ui-avatars.com/api/?name='.urlencode($u->person->name ?? $u->email).'&background=random&color=fff' }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-700">{{ $u->person->name ?? '-' }}</span>
                                            <span class="text-sm text-slate-400">{{ $u->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-sm px-6 py-4 text-center font-bold text-indigo-600">
                                    {{ $u->role->name_role ?? '-' }}
                                </td>
                                <td class="text-sm px-6 py-4 text-center text-timestamp">
                                    {{ $u->created_at->translatedFormat('d F Y H:i') }} WITA
                                </td>
                                <td class="text-sm px-6 py-4 text-center text-timestamp">
                                    {{ $u->updated_at->translatedFormat('d F Y H:i') }} WITA
                                </td>
                                <td class="text-sm px-6 py-4 text-center">
                                    <span class="status-badge {{ $u->status_user == 'active' ? 'text-emerald-600 bg-emerald-50 border-emerald-100' : 'text-rose-600 bg-rose-50 border-rose-100' }}">
                                        {{ $u->status_user == 'active' ? 'Aktif' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="text-sm px-6 py-4 text-right">
                                    <div class="flex justify-end items-center gap-1">
                                        @if($hasPerson)
                                        <button onclick='openMasterEditModal(@json($u), @json($u->person), false)' class="p-2 text-slate-400 hover:text-indigo-600 cursor-pointer"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg></button>
                                        @else
                                        <button disabled class="p-2 text-slate-200 cursor-not-allowed"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg></button>
                                        @endif
                                        @if($u->id_user !== Auth::id())
                                        <button onclick="confirmDelete('{{ $u->id_user }}', '{{ $u->email }}', false)" class="p-2 text-rose-600 hover:bg-rose-100 rounded transition-all cursor-pointer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

            {{-- 4. TABEL TEMPAT SAMPAH --}}
            <div class="bg-rose-50/20 rounded-xl border border-rose-100 overflow-hidden shadow-sm text-table-standard">
                <div class="p-6 border-b border-rose-100 bg-rose-50 flex justify-between items-center text-rose-800">
                    <h3 class="font-bold uppercase tracking-wider" style="font-size: 14px !important;">Keranjang Sampah</h3>
                    @if($trashedUsers->count() > 0)
                    <button onclick="confirmDeleteAll()" class="text-label-caps text-rose-600 bg-white border border-rose-200 px-4 py-2 rounded-lg hover:bg-rose-600 hover:text-white transition-all cursor-pointer">Kosongkan Sampah</button>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-rose-50/50 text-label-caps text-rose-400 border-b border-rose-100">
                                <th class="px-6 py-3">User</th>
                                <th class="px-6 py-3 text-center">Role</th>
                                <th class="px-6 py-3 text-center">Dihapus Pada</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-rose-50">
                            @forelse($trashedUsers as $tu)
                            <tr class="opacity-75">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col grayscale opacity-60">
                                        <span class="text-slate-700 font-bold leading-tight">{{ $tu->person->name ?? 'User Baru' }}</span>
                                        <span class="text-xs font-mono text-slate-400">{{ $tu->email }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-slate-400 uppercase" style="font-size: 11px;">
                                    {{ $tu->role->name_role ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-center text-timestamp uppercase">
                                    {{ $tu->deleted_at->translatedFormat('d F Y H:i') }} WITA
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end items-center gap-2">
                                    <form action="{{ route('admin.users.restore', $tu->id_user) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded cursor-pointer" title="Pulihkan">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    <button onclick="confirmDelete('{{ $tu->id_user }}', '{{ $tu->email }}', true)" class="p-2 text-rose-600 hover:bg-rose-50 rounded cursor-pointer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center opacity-20 text-label-caps">Sampah Kosong</td>
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
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-sm w-full p-8 text-center border border-slate-100 font-app">
            <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 uppercase tracking-widest mb-2" id="delete_modal_title">Hapus Pengguna?</h3>
            <p class="text-sm text-slate-500 mb-8 leading-relaxed" id="delete_modal_info"></p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 py-3 text-label-caps text-slate-500 bg-slate-50 rounded-xl hover:bg-slate-100 cursor-pointer">Batal</button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" id="delete_btn_text" class="w-full py-3 text-label-caps text-white bg-rose-600 rounded-xl shadow-lg hover:bg-rose-700 cursor-pointer font-bold">Hapus Sekarang</button>
                </form>
            </div>
        </div>
    </div>

    {{-- MASTER MODAL EDIT --}}
    <div id="masterModal" class="fixed inset-0 z-[99] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeMasterEditModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-6xl overflow-hidden transform transition-all font-app">
            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center text-slate-800">
                <h3 class="text-sm font-bold uppercase tracking-widest" id="modal_header_title">Edit Master Pengguna</h3>
                <button type="button" onclick="closeMasterEditModal()" class="text-slate-400 hover:text-slate-600 text-2xl cursor-pointer">&times;</button>
            </div>
            <form id="masterForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="method_field"></div>
                <div class="p-6 md:p-8 grid grid-cols-1 lg:grid-cols-3 gap-8 max-h-[75vh] overflow-y-auto text-left font-app">
                    {{-- Form Fields (Grid tetap 1 mobile, 3 desktop) --}}
                    <div class="space-y-5">
                        <p class="text-label-caps text-indigo-500 border-b pb-2 tracking-widest">Foto & Akun</p>
                        <div class="flex flex-col items-center bg-gray-50 rounded-xl p-4 border-2 border-dashed border-gray-200">
                            <div class="preview-box mb-4" onclick="document.getElementById('photo').click()">
                                <img id="cropped-preview" src="" class="w-full h-full object-cover">
                            </div>
                            <label for="photo" class="cursor-pointer text-center group">
                                <span class="text-label-caps text-indigo-600 font-bold group-hover:text-indigo-800">Ganti Pas Foto</span>
                                <input id="photo" name="photo" type="file" class="hidden" accept="image/*" />
                            </label>
                        </div>
                        <div><label class="text-label-caps text-slate-500 block">Email Sistem</label><input type="email" name="email" id="f_email" class="w-full mt-2 px-4 py-2 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all"></div>
                        <div><label class="text-label-caps text-slate-500 block">WhatsApp</label><input type="text" name="phone" id="f_phone" class="w-full mt-2 px-4 py-2 bg-slate-50 border-none rounded-lg outline-none"></div>
                        <div><label class="text-label-caps text-slate-500 block">Nama Lengkap</label><input type="text" name="name" id="f_name" class="w-full mt-2 px-4 py-2 bg-slate-50 border-none rounded-lg outline-none"></div>
                    </div>
                    <div class="space-y-5">
                        <p class="text-label-caps text-indigo-500 border-b pb-2 tracking-widest">Informasi Pribadi</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div><label class="text-label-caps text-slate-500">NIK</label><input type="text" name="nik" id="f_nik" class="w-full mt-2 px-4 py-2 bg-slate-50 border-none rounded-lg outline-none"></div>
                            <div><label class="text-label-caps text-slate-500">No. KK</label><input type="text" name="no_kk" id="f_no_kk" class="w-full mt-2 px-4 py-2 bg-slate-50 border-none rounded-lg outline-none"></div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div><label class="text-label-caps text-slate-500">Tgl Lahir</label><input type="date" name="date_birthday" id="f_date" class="w-full mt-2 px-3 py-2 bg-slate-50 border-none rounded-lg outline-none"></div>
                            <div><label class="text-label-caps text-slate-500">Gender</label><select name="gender" id="f_gender" class="w-full mt-2 px-3 py-2 bg-slate-50 border-none rounded-lg outline-none">
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select></div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div><label class="text-label-caps text-slate-500">Agama</label>
                                <select name="religion" id="f_religion" class="w-full mt-2 px-3 py-2 bg-slate-50 border-none rounded-lg outline-none">
                                    @foreach(['Islam', 'Kristen', 'Katholik', 'Hindu', 'Buddha', 'Khonghucu'] as $rel) <option value="{{ $rel }}">{{ $rel }}</option> @endforeach
                                </select>
                            </div>
                            <div><label class="text-label-caps text-slate-500">NPWP</label><input type="text" name="npwp" id="f_npwp" class="w-full mt-2 px-4 py-2 bg-slate-50 border-none rounded-lg outline-none"></div>
                        </div>
                        <div><label class="text-label-caps text-slate-500 block">Tempat Lahir</label><input type="text" name="place_birthday" id="f_place" class="w-full mt-2 px-4 py-2 bg-slate-50 border-none rounded-lg outline-none"></div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div><label class="text-label-caps text-slate-500">Umur</label><input type="number" name="age" id="f_age" class="w-full mt-2 px-4 py-2 bg-slate-50 border-none rounded-lg outline-none"></div>
                            <div><label class="text-label-caps text-slate-500">Status Nikah</label>
                                <select name="marital_status" id="f_marital" class="w-full mt-2 px-3 py-2 bg-slate-50 border-none rounded-lg outline-none">
                                    @foreach(['Belum Kawin', 'Kawin', 'Janda', 'Duda'] as $status) <option value="{{ $status }}">{{ $status }}</option> @endforeach
                                </select>
                            </div>
                        </div>
                        <div><label class="text-label-caps text-slate-500 block">Pendidikan</label><input type="text" name="title_education" id="f_edu" class="w-full mt-2 px-4 py-2 bg-slate-50 border-none rounded-lg outline-none"></div>
                    </div>
                    <div class="space-y-5">
                        <p class="text-label-caps text-indigo-500 border-b pb-2 tracking-widest">Domisili & Jabatan</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div><label class="text-label-caps text-slate-500">Provinsi</label><select name="province" id="f_prov" class="w-full mt-2 px-3 py-2 bg-slate-50 border-none rounded-lg outline-none">
                                    <option value="Bali">Bali</option>
                                </select></div>
                            <div><label class="text-label-caps text-slate-500">Kabupaten</label><select name="regency" id="f_reg" class="w-full mt-2 px-3 py-2 bg-slate-50 border-none rounded-lg outline-none">
                                    <option value="Buleleng">Buleleng</option>
                                </select></div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div><label class="text-label-caps text-slate-500">Kecamatan</label>
                                <select name="district" id="f_dist" class="w-full mt-2 px-3 py-2 bg-slate-50 border-none rounded-lg outline-none">
                                    @foreach(['Tejakula', 'Kubutambahan', 'Sawan', 'Sukasada', 'Buleleng', 'Banjar', 'Seririt', 'Busungbiu', 'Gerokgak'] as $kec) <option value="{{ $kec }}">{{ $kec }}</option> @endforeach
                                </select>
                            </div>
                            <div><label class="text-label-caps text-slate-500">Desa</label><input type="text" name="village" id="f_vill" class="w-full mt-2 px-3 py-2 bg-slate-50 border-none rounded-lg outline-none"></div>
                        </div>
                        <div><label class="text-label-caps text-indigo-600 block font-black">GPS (Klik Peta)</label>
                            <input type="text" name="gps_coordinates" id="f_gps" readonly class="w-full px-3 py-2 bg-indigo-50 text-indigo-700 font-mono text-xs border-none rounded-lg outline-none shadow-inner">
                        </div>
                        <div id="map"></div>
                        <div><label class="text-label-caps text-slate-500 block">Alamat Lengkap</label><textarea name="address" id="f_address" rows="1" class="w-full mt-2 px-3 py-2 bg-slate-50 border-none rounded-lg text-sm outline-none"></textarea></div>
                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <div><label class="text-label-caps text-indigo-500 block">Akses Role</label><select name="id_ref_role" id="f_role" class="w-full mt-2 px-2 py-2 bg-white border border-slate-200 rounded text-xs font-bold outline-none text-indigo-600 cursor-pointer">@foreach($roles as $r)<option value="{{$r->id_ref_role}}">{{$r->name_role}}</option>@endforeach</select></div>
                            <div><label class="text-label-caps text-indigo-500 block">Jabatan</label><select name="id_ref_position" id="f_pos" class="w-full mt-2 px-2 py-2 bg-white border border-slate-200 rounded text-xs font-bold outline-none text-indigo-600 cursor-pointer">@foreach($positions as $p)<option value="{{$p->id_ref_position}}">{{$p->name_position}}</option>@endforeach</select></div>
                        </div>
                    </div>
                </div>
                <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-4">
                    <button type="button" onclick="closeMasterEditModal()" class="flex-1 py-4 text-label-caps text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 cursor-pointer transition-all">Batal</button>
                    <button type="submit" id="submit_btn" class="flex-1 py-4 text-label-caps text-white bg-slate-800 rounded-xl shadow-lg hover:bg-slate-900 cursor-pointer transition-all active:scale-[0.98]">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- CROPPER MODAL --}}
    <div id="cropperModal" class="fixed inset-0 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden font-app">
            <div class="p-6 border-b bg-gray-50 flex justify-between items-center text-slate-800 font-bold uppercase tracking-widest">
                <h3>Potong Pas Foto</h3>
            </div>
            <div class="p-6 bg-gray-200 flex justify-center">
                <div class="max-w-full max-h-[60vh]"><img id="image-to-crop" class="block max-w-full"></div>
            </div>
            <div class="p-6 border-t flex justify-end space-x-3 text-label-caps font-bold">
                <button onclick="document.getElementById('cropperModal').classList.add('hidden')" type="button" class="px-6 py-2 text-gray-500 cursor-pointer hover:text-gray-400">Batal</button>
                <button id="apply-crop-btn" type="button" class="px-8 py-2 bg-slate-800 text-white rounded-lg shadow-lg cursor-pointer hover:bg-indigo-700 transition-colors uppercase">Gunakan</button>
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
                document.getElementById('modal_header_title').innerText = "Verifikasi: " + (person?.name || user.email);
            } else {
                form.action = `/admin/users/${user.id_user}/update`;
                methodField.innerHTML = '<input type="hidden" name="_method" value="PATCH">';
                document.getElementById('modal_header_title').innerText = "Master Edit: " + (person?.name || user.email);
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
                btn.innerText = "Hapus Permanen";
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
    </script>
</x-app-layout>