<x-app-layout title="Daftar Pengguna">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <script src="https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js"></script>

    <style>
        .input-disabled {
            background-color: #f8fafc !important;
            color: #94a3b8 !important;
            cursor: not-allowed !important;
            pointer-events: none;
        }
    </style>

    <div class="py-10 p-4 text-slate-800 text-[14px]">
        <div id="user-table-container" class="w-full mx-auto sm:px-6 lg:px-8 space-y-10">

            {{-- 1. HEADER SECTION --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 uppercase">
                            Kelola Pengguna
                        </h2>
                        <p class="text-sm text-slate-400 font-medium mt-1">
                            Kelola akun pengguna yang terdaftar dalam sistem
                        </p>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-4 py-2 text-[10px] font-bold rounded bg-white text-slate-600 uppercase border border-slate-200 tracking-widest shadow-sm">
                            {{ $allUsersDisplay->count() }} Pengguna Terdaftar
                        </span>
                    </div>
                </div>
            </div>

            {{-- 2. STATS SECTION --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @foreach([
                ['Total', $stats['total'], 'indigo', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
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

            {{-- 3. DAFTAR TUNGGU VERIFIKASI --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                    {{-- Judul: Tetap di atas pada mobile, di kiri pada desktop --}}
                    <h3 class="font-bold uppercase tracking-wider text-slate-700 text-[14px]">Daftar Verifikasi Pengguna</h3>

                    {{-- Container Aksi: Berjejer (flex-row) baik di mobile maupun desktop --}}
                    <div class="flex flex-row items-center gap-3 w-full md:w-auto">

                        {{-- Tombol Setujui Semua: shrink-0 agar tidak gepeng, teks hidden di mobile kecil --}}
                        @if($pendingUsers->total() > 0)
                        <button type="button"
                            onclick="openApproveAllModal()"
                            class="shrink-0 text-[11px] font-bold uppercase tracking-wider text-emerald-600 bg-white border border-emerald-200 px-4 py-2.5 rounded-lg hover:bg-emerald-600 hover:text-white transition-all cursor-pointer shadow-sm flex items-center gap-2">
                            {{-- Icon SVG Checklist --}}
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0 1 12 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 0 1 3.498 1.307 4.491 4.491 0 0 1 1.307 3.497A4.49 4.49 0 0 1 21.75 12a4.49 4.49 0 0 1-1.549 3.397 4.491 4.491 0 0 1-1.307 3.497 4.491 4.491 0 0 1-3.497 1.307A4.49 4.49 0 0 1 12 21.75a4.49 4.49 0 0 1-3.397-1.549 4.49 4.49 0 0 1-3.498-1.307 4.491 4.491 0 0 1-1.307-3.497A4.49 4.49 0 0 1 2.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 0 1 1.307-3.497 4.49 4.49 0 0 1 3.497-1.307Zm7.007 6.387a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                            </svg>
                            {{-- Teks hanya muncul di desktop (md keatas) --}}
                            <span class="hidden md:inline">Setujui Semua</span>
                        </button>
                        @endif

                        {{-- Input Pencarian: flex-grow di mobile agar lebar, md:w-64 di desktop agar tetap ideal --}}
                        <div class="relative flex-grow md:flex-initial md:w-64 text-slate-800">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input type="text"
                                id="search-pending"
                                data-table="pending"
                                class="live-search-input text-xs border-slate-200 rounded-lg pl-9 pr-3 py-2.5 w-full focus:ring-2 focus:ring-indigo-500 outline-none transition-all bg-white shadow-sm"
                                placeholder="Cari telepon atau email..."
                                value="{{ request('search_pending') }}"
                                autocomplete="off">
                        </div>

                    </div>
                </div>
                <div class="overflow-x-auto scrollbar-thin">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50/50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                <th class="px-6 py-3">Pendaftar</th>
                                <th class="px-6 py-3 text-center">Jabatan / Unit</th>
                                <th class="px-6 py-3 text-center">Batch / Status Kerja</th>
                                <th class="px-6 py-3 text-center">Waktu Bergabung</th>
                                <th class="px-6 py-3 text-center">Status Email</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($pendingUsers as $user)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-slate-700 lowercase">{{ $user->email }}</span> <br>
                                    <span class="text-xs text-slate-500 font-medium whitespace-nowrap">{{ $user->phone }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-medium text-slate-500 text-xs block capitalize">{{ $user->person->position->name_position ?? '-' }}</span>
                                    <span class="text-xs text-slate-500 font-medium capitalize">{{ $user->person->workAssignment->sppgUnit->name ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-xs block text-slate-500 font-medium whitespace-nowrap">
                                        {{ $user->person && $user->person->batch ? 'Batch ' . $user->person->batch : '-' }}
                                    </span>
                                    <span class="text-xs text-slate-500 font-medium whitespace-nowrap">{{ $user->person->employment_status ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-center text-xs text-slate-500 font-medium whitespace-nowrap">
                                    {{ $user->created_at->translatedFormat('d F Y H:i:s') }} WITA
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($user->email_verified_at)
                                    {{-- Icon Terverifikasi (Centang Hijau) --}}
                                    <div class="flex justify-center text-emerald-500" title="Waktu Email Terverifikasi {{ $user->email_verified_at->translatedFormat('d F Y H:i:s') }} WITA">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    @else
                                    {{-- Icon Belum Terverifikasi (X Merah/Abu) --}}
                                    <div class="flex justify-center text-rose-500" title="Email Belum Terverifikasi">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('admin.users.approve', $user->id_user) }}" method="POST" class="flex justify-end items-center gap-2">
                                        @csrf

                                        {{-- 1. PILIH PENUGASAN --}}
                                        <select name="id_work_assignment" required class="text-xs text-slate-500 font-medium whitespace-nowrap border-slate-200 rounded-md py-2 cursor-pointer outline-none focus:ring-1 focus:ring-emerald-500 max-w-48">
                                            {{-- 1. Label Instruksi: Tetap ada, tidak bisa dipilih, dan TIDAK terpilih otomatis saat data null --}}
                                            <option value="" disabled>
                                                Pilih Penugasan
                                            </option>

                                            {{-- 2. Opsi Belum Penugasan: Akan otomatis terpilih (selected) jika data di DB bernilai null --}}
                                            <option value="none" {{ is_null($user->person?->id_work_assignment) ? 'selected' : '' }}>
                                                Belum Penugasan
                                            </option>

                                            {{-- 3. Daftar Penugasan dari Database --}}
                                            @foreach($workAssignments as $wa)
                                            <option value="{{ $wa->id_work_assignment }}"
                                                {{ $user->person?->id_work_assignment == $wa->id_work_assignment ? 'selected' : '' }}>
                                                {{ $wa->sppgUnit->name }} - {{ $wa->decree->no_sk }}
                                            </option>
                                            @endforeach
                                        </select>

                                        {{-- 2. PILIH HAK AKSES --}}
                                        <select name="id_ref_role" required class="text-xs text-slate-500 font-medium whitespace-nowrap border-slate-200 rounded-md py-2 cursor-pointer outline-none focus:ring-1 focus:ring-emerald-500">
                                            @if(!$user->id_ref_role)
                                            <option value="" disabled selected>Pilih Hak Akses</option>
                                            @endif
                                            @foreach($roles as $r)
                                            <option value="{{$r->id_ref_role}}"
                                                {{ ($user->id_ref_role == $r->id_ref_role) ? 'selected' : ($r->name_role == 'Guest' && !$user->id_ref_role ? 'selected' : '') }}>
                                                {{$r->name_role}}
                                            </option>
                                            @endforeach
                                        </select>

                                        {{-- 3. PILIH JABATAN --}}
                                        <select name="id_ref_position" required class="text-xs text-slate-500 font-medium whitespace-nowrap border-slate-200 rounded-md py-2 cursor-pointer outline-none focus:ring-1 focus:ring-emerald-500">
                                            {{-- 1. Label Instruksi: Tetap ada sebagai panduan, namun tidak bisa dikirim (disabled) --}}
                                            <option value="" disabled>
                                                Pilih Jabatan
                                            </option>

                                            {{-- 2. Opsi Belum Menjabat: Otomatis terpilih (selected) jika id_ref_position bernilai null di database --}}
                                            <option value="none" {{ is_null($user->person?->id_ref_position) ? 'selected' : '' }}>
                                                Belum Menjabat
                                            </option>

                                            {{-- 3. Daftar Jabatan dari Database --}}
                                            @foreach($positions as $p)
                                            <option value="{{ $p->id_ref_position }}"
                                                {{ $user->person?->id_ref_position == $p->id_ref_position ? 'selected' : '' }}>
                                                {{ $p->name_position }}
                                            </option>
                                            @endforeach
                                        </select>

                                        <button type="submit"
                                            class="flex items-center justify-center p-2 text-emerald-600 bg-emerald-50 border border-emerald-200 rounded-full hover:bg-emerald-600 hover:text-white transition-all cursor-pointer"
                                            title="Setujui">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                                            </svg>
                                        </button>

                                        <button type="button" title="Hapus Sementara" onclick="confirmDelete('{{ $user->id_user }}', '{{ $user->email }}', false)" class="p-2 text-rose-600 hover:bg-rose-50 rounded cursor-pointer transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="p-3 bg-slate-50 rounded-full mb-3">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">
                                            {{ request('search_pending') ? 'Pengguna Tidak Ditemukan' : 'Antrian Kosong' }}
                                        </p>
                                        @if(request('search_pending'))
                                        <p class="text-[10px] text-slate-400 mt-1 italic">Coba gunakan kata kunci yang berbeda</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-white border-t border-slate-100 
    [&_nav]:flex [&_nav]:justify-between [&_nav]:items-center
    [&_a]:bg-white [&_a]:text-slate-600 [&_a]:border-slate-200 [&_a]:rounded-lg [&_a]:hover:bg-slate-50
    [&_span]:bg-white [&_span]:text-slate-400 [&_span]:border-slate-200 [&_span]:rounded-lg
    [&_.bg-gray-800]:bg-emerald-600 [&_.bg-gray-800]:text-white [&_.bg-gray-800]:border-emerald-600
    [&_.dark\:bg-gray-800]:bg-white [&_.dark\:text-gray-400]:text-slate-500">
                    {{ $pendingUsers->appends(request()->query())->links() }}
                </div>
            </div>

            {{-- 4. DAFTAR SELURUH PENGGUNA --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                    {{-- 1. Judul --}}
                    <h3 class="font-bold text-slate-700 uppercase tracking-wider text-[14px]">Daftar Seluruh Pengguna</h3>

                    {{-- Container Aksi: Flex-row di mobile agar tombol & input sebaris --}}
                    <div class="flex flex-row items-center gap-3 w-full md:w-auto">

                        {{-- 2. Grup Tombol: Berjejer di kiri, teks hilang di layar kecil --}}
                        <div class="flex items-center gap-2 shrink-0">
                            {{-- Tombol Export --}}
                            <button type="button" onclick="openDownloadModal()" class="flex items-center justify-center p-2.5 md:px-4 text-[11px] font-bold uppercase tracking-wider text-emerald-600 bg-white border border-emerald-200 rounded-lg hover:bg-emerald-600 hover:text-white transition-all cursor-pointer shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                    <path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                </svg>
                                <span class="hidden md:inline ml-2">Export</span>
                            </button>

                            {{-- Tombol Import --}}
                            <button type="button" onclick="openUploadModal()" class="flex items-center justify-center p-2.5 md:px-4 text-[11px] font-bold uppercase tracking-wider text-amber-600 bg-white border border-amber-200 rounded-lg hover:bg-amber-600 hover:text-white transition-all cursor-pointer shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                    <path fill-rule="evenodd" d="M11.47 2.47a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 1 1-1.06 1.06l-3.22-3.22V16.5a.75.75 0 0 1-1.5 0V4.81L8.03 8.03a.75.75 0 0 1-1.06-1.06l4.5-4.5ZM3 15.75a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                </svg>
                                <span class="hidden md:inline ml-2">Import</span>
                            </button>

                            {{-- Tombol Tambah --}}
                            <button type="button" onclick="openAddUserModal()" class="flex items-center justify-center p-2.5 md:px-4 text-[11px] font-bold uppercase tracking-wider text-indigo-600 bg-white border border-indigo-200 rounded-lg hover:bg-indigo-600 hover:text-white transition-all cursor-pointer shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                    <path d="M6.25 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM3.25 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM19.75 7.5a.75.75 0 0 0-1.5 0v2.25H16a.75.75 0 0 0 0 1.5h2.25v2.25a.75.75 0 0 0 1.5 0v-2.25H22a.75.75 0 0 0 0-1.5h-2.25V7.5Z" />
                                </svg>
                                <span class="hidden md:inline ml-2 text-nowrap">Tambah</span>
                            </button>
                        </div>

                        {{-- 3. Input Pencarian: Melebar menggunakan flex-grow --}}
                        <div class="relative flex-grow md:flex-initial md:w-64 text-slate-800">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                    <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <input type="text"
                                id="search-all"
                                data-table="all"
                                class="live-search-input text-xs border-slate-200 rounded-lg pl-9 pr-3 py-2.5 w-full focus:ring-2 focus:ring-indigo-500 outline-none transition-all bg-white shadow-sm"
                                placeholder="Cari nama atau email..."
                                value="{{ request('search_all') }}"
                                autocomplete="off">
                        </div>

                    </div>
                </div>
                <div class="overflow-x-auto scrollbar-thin">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                <th class="px-6 py-4">Nama & Email</th>
                                <th class="px-6 py-4 text-center">Jabatan / Unit</th>
                                <th class="px-6 py-4 text-center">Batch / Status Kerja</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($allUsers->where('status_user', '!=', 'inactive') as $u)
                            @php
                            $person = $u->person;
                            $userName = $person->name ?? ($u->status_user == 'pending' ? 'User Belum Diverifikasi' : 'User Tanpa Profil');
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors {{ !$person ? 'bg-slate-50/50 opacity-60' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full overflow-hidden shrink-0 shadow-sm border border-slate-100 flex items-center justify-center bg-indigo-600">
                                            @if($person && $person->photo)
                                            <img src="{{ asset('storage/' . $person->photo) }}" class="w-full h-full object-cover">
                                            @else
                                            <div class="text-white text-[15px] uppercase tracking-tighter">
                                                {{ substr($userName, 0, 1) }}
                                            </div>
                                            @endif
                                        </div>
                                        <div class="flex flex-col text-[14px] gap-0.5">
                                            <span class="font-bold text-slate-700 leading-tight">{{ $userName }}</span>
                                            <span class="text-xs text-slate-500 font-sans font-medium">
                                                <span class="">{{ $u->role->name_role ?? '-' }}</span> - <span class="lowercase">{{ $u->email }}</span>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-medium text-slate-500 text-xs block capitalize">{{ $person->position->name_position ?? '-' }}</span>
                                    <span class="text-xs text-slate-500 font-medium capitalize">{{ $person->workAssignment->sppgUnit->name ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-xs font-medium text-slate-500 block">{{ $u->person && $u->person->batch ? 'Batch ' . $u->person->batch : '-' }}</span>
                                    <span class="text-xs font-medium text-slate-500">{{ $person->employment_status ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-xs font-medium px-1.5 py-0.5 rounded border capitalize {{ $u->status_user == 'active' ? 'text-emerald-600 bg-emerald-50 border-emerald-100' : 'text-yellow-600 bg-yellow-50 border-yellow-100' }}">
                                        {{ $u->status_user }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center gap-1">
                                        {{-- Tombol Edit --}}
                                        <button
                                            @if($person)
                                            onclick='openMasterEditModal(@json($u), @json($person), false)'
                                            title="Edit"
                                            class="p-2 text-slate-400 hover:text-indigo-600 cursor-pointer transition-colors hover:bg-indigo-50 rounded-lg"
                                            @else
                                            disabled
                                            title="Profil Belum Dilengkapi"
                                            class="p-2 text-slate-200 cursor-not-allowed"
                                            @endif>
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>

                                        {{-- Tombol Hapus --}}
                                        @php
                                        $isSelf = $u->id_user === Auth::id();
                                        @endphp

                                        <button
                                            @if(!$isSelf)
                                            onclick="confirmDelete('{{ $u->id_user }}', '{{ $u->email }}', false)"
                                            @endif
                                            class="p-2 transition-all {{ $isSelf ? 'text-rose-200 cursor-not-allowed' : 'text-rose-600 hover:bg-rose-50 cursor-pointer rounded-lg' }}"
                                            {{ $isSelf ? 'disabled' : '' }}
                                            title="{{ $isSelf ? 'Anda tidak dapat menghapus akun sendiri' : 'Hapus Sementara' }}">

                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="p-3 bg-slate-50 rounded-full mb-3">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">
                                            {{ request('search_all') ? 'Pengguna Tidak Ditemukan' : 'Belum Ada Pengguna Terdaftar' }}
                                        </p>
                                        @if(request('search_all'))
                                        <p class="text-[10px] text-slate-400 mt-1 italic">Coba gunakan kata kunci yang berbeda</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-white border-t border-slate-100 
    [&_nav]:flex [&_nav]:justify-between [&_nav]:items-center
    [&_a]:bg-white [&_a]:text-slate-600 [&_a]:border-slate-200 [&_a]:rounded-lg [&_a]:hover:bg-slate-50
    [&_span]:bg-white [&_span]:text-slate-400 [&_span]:border-slate-200 [&_span]:rounded-lg
    [&_.bg-gray-800]:bg-indigo-600 [&_.bg-gray-800]:text-white [&_.bg-gray-800]:border-indigo-600
    [&_.dark\:bg-gray-800]:bg-white [&_.dark\:text-gray-400]:text-slate-500">
                    {{ $allUsers->appends(request()->query())->links() }}
                </div>
            </div>

            {{-- 5. TABEL TEMPAT SAMPAH --}}
            <div class="bg-rose-50/20 rounded-xl border border-rose-100 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-rose-100 bg-rose-50 flex flex-col md:flex-row md:justify-between md:items-center gap-4 text-rose-800">
                    {{-- Judul: Paling atas di mobile --}}
                    <h3 class="font-bold uppercase tracking-wider text-[14px]">Daftar Pengguna Dihapus</h3>

                    {{-- Container Aksi: flex-row agar tombol dan input berjejer di mobile --}}
                    <div class="flex flex-row items-center gap-3 w-full md:w-auto">

                        {{-- Tombol Kosongkan Sampah: shrink-0 agar ukuran tombol tetap stabil --}}
                        @if($trashedUsers->count() > 0)
                        <button onclick="confirmDeleteAll()"
                            class="shrink-0 text-[11px] font-bold uppercase tracking-wider text-rose-600 bg-white border border-rose-200 px-4 py-2.5 rounded-lg hover:bg-rose-600 hover:text-white transition-all cursor-pointer shadow-sm flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span class="hidden md:inline">Kosongkan Sampah</span>
                        </button>
                        @endif

                        {{-- Input Pencarian: flex-grow agar lebih lebar memenuhi ruang --}}
                        <div class="relative flex-grow md:flex-initial md:w-64 text-slate-800">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-rose-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input type="text"
                                id="search-trash"
                                data-table="trash"
                                class="live-search-input text-xs border-rose-100 rounded-lg pl-9 pr-3 py-2.5 w-full focus:ring-2 focus:ring-rose-500 outline-none transition-all bg-white shadow-sm"
                                placeholder="Cari nama atau email..."
                                value="{{ request('search_trash') }}"
                                autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto scrollbar-thin">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-rose-50/50 text-[11px] font-bold uppercase tracking-wider text-rose-400 border-b border-rose-100">
                                <th class="px-6 py-4">Nama & Email</th>
                                <th class="px-6 py-4 text-center">Jabatan / Unit</th>
                                <th class="px-6 py-4 text-center">Batch / Status Kerja</th>
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
                            @endphp
                            <tr class="hover:bg-rose-50/30 transition-colors opacity-80 text-[12px]">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3 grayscale">
                                        <div class="w-10 h-10 rounded-full overflow-hidden shrink-0 shadow-sm border border-slate-200">
                                            @if($personTrashed && $personTrashed->photo)
                                            <img src="{{ asset('storage/' . $personTrashed->photo) }}" class="w-full h-full object-cover">
                                            @else
                                            <div class="w-full h-full bg-slate-400 flex items-center justify-center text-white text-[15px] uppercase tracking-tighter">
                                                {{ substr($trashName, 0, 1) }}
                                            </div>
                                            @endif
                                        </div>
                                        <div class="flex flex-col text-[14px]">
                                            <span class="text-slate-700 font-bold leading-tight">{{ $trashName }}</span>
                                            <span class="text-xs text-slate-500 font-medium">
                                                <span class="">{{ $tu->role->name_role ?? '-' }}</span> - <span class="lowercase">{{ $tu->email }}</span>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-slate-500 text-xs block capitalize font-medium">{{ $personTrashed->position->name_position ?? '-' }}</span>
                                    <span class="text-xs text-slate-500 capitalize font-medium">{{ $personTrashed->workAssignment->sppgUnit->name ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-xs font-medium text-slate-500 block">{{ $tu->personTrashed && $tu->personTrashed->batch ? 'Batch ' . $tu->personTrashed->batch : '-' }}</span>
                                    <span class="text-xs font-medium text-slate-500">{{ $personTrashed->employment_status ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-center text-xs text-slate-500 font-medium whitespace-nowrap">
                                    {{ $tu->deleted_at->translatedFormat('d F Y H:i:s') }} WITA
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-xs font-medium px-1.5 py-0.5 rounded border capitalize text-rose-600 bg-rose-50 border-rose-100">
                                        {{ $tu->status_user }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end items-center gap-2">
                                    <form action="{{ route('admin.users.restore', $tu->id_user) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-2 text-indigo-600 hover:bg-indigo-50 cursor-pointer rounded-lg" title="Pulihkan">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    <button onclick="confirmDelete('{{ $tu->id_user }}', '{{ $tu->email }}', true)" class="p-2 text-rose-600 hover:bg-rose-50 cursor-pointer rounded-lg" title="Hapus Permanen">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="p-3 bg-slate-50 rounded-full mb-3">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">
                                            {{ request('search_trash') ? 'Pengguna Tidak Ditemukan' : 'Sampah Kosong' }}
                                        </p>
                                        @if(request('search_trash'))
                                        <p class="text-[10px] text-slate-400 mt-1 italic">Coba gunakan kata kunci yang berbeda</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-rose-50/20 border-t border-rose-100
    [&_nav]:flex [&_nav]:justify-between [&_nav]:items-center
    [&_a]:bg-white [&_a]:text-slate-600 [&_a]:border-slate-200 [&_a]:rounded-lg [&_a]:hover:bg-rose-50
    [&_span]:bg-white [&_span]:text-slate-400 [&_span]:border-slate-200 [&_span]:rounded-lg
    [&_.bg-gray-800]:bg-rose-600 [&_.bg-gray-800]:text-white [&_.bg-gray-800]:border-rose-600
    [&_.dark\:bg-gray-800]:bg-white [&_.dark\:text-gray-400]:text-slate-500">
                    {{ $trashedUsers->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DOWNLOAD EXCEL LENGKAP --}}
    <div id="downloadModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeDownloadModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-6xl overflow-hidden transform transition-all font-sans text-[13px]">

            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <span class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </span>
                    <h3 class="font-bold uppercase tracking-widest text-slate-700">Export Data Pengguna</h3>
                </div>
                <div class="flex gap-4 items-center">
                    <button type="button" onclick="closeDownloadModal()" class="text-slate-400 hover:text-slate-600 text-2xl">&times;</button>
                </div>
            </div>

            <form action="{{ route('admin.users.export') }}" method="POST" id="formDownloadExcel">
                @csrf
                <div class="p-8 max-h-[70vh] overflow-y-auto scrollbar-thin">
                    <div class="flex justify-between items-center mb-6">
                        <p class="text-[12px] font-bold text-slate-500 uppercase tracking-widest">Pilih Kolom Data Untuk File Excel</p>
                        <div class="flex gap-4">
                            <button type="button" onclick="toggleAllCheckboxes(true)" class="text-[11px] font-semibold text-indigo-600 hover:underline uppercase">Pilih Semua</button>
                            <button type="button" onclick="toggleAllCheckboxes(false)" class="text-[11px] font-semibold text-rose-600 hover:underline uppercase">Hapus Semua</button>
                        </div>
                    </div>

                    @php
                    // Array utama sesuai urutan yang diinginkan
                    $mappingNames = [
                    'status_user' => 'STATUS AKUN',
                    'role' => 'HAK AKSES SISTEM',
                    'name' => 'NAMA LENGKAP',
                    'nik' => 'NIK',
                    'no_kk' => 'NO. KK',
                    'nip' => 'NIP',
                    'npwp' => 'NPWP',
                    'no_bpjs_kes' => 'NO. BPJS KESEHATAN',
                    'no_bpjs_tk' => 'NO. BPJS KETENAGAKERJAAN',
                    'gender' => 'JENIS KELAMIN',
                    'place_birthday' => 'TEMPAT LAHIR',
                    'date_birthday' => 'TANGGAL LAHIR',
                    'age' => 'UMUR',
                    'religion' => 'AGAMA',
                    'marital_status' => 'STATUS PERNIKAHAN',
                    'last_education' => 'PENDIDIKAN TERAKHIR',
                    'title_education' => 'GELAR BELAKANG',
                    'major_education' => 'JURUSAN/PROGRAM STUDI',
                    'clothing_size' => 'UKURAN BAJU',
                    'shoe_size' => 'UKURAN SEPATU',
                    'phone' => 'TELEPON',
                    'email' => 'EMAIL',
                    'province_ktp' => 'PROVINSI (KTP)',
                    'regency_ktp' => 'KABUPATEN (KTP)',
                    'district_ktp' => 'KECAMATAN (KTP)',
                    'village_ktp' => 'DESA/KELURAHAN (KTP)',
                    'address_ktp' => 'ALAMAT JALAN/RUMAH (KTP)',
                    'province_domicile' => 'PROVINSI (DOMISILI)',
                    'regency_domicile' => 'KABUPATEN (DOMISILI)',
                    'district_domicile' => 'KECAMATAN (DOMISILI)',
                    'village_domicile' => 'DESA/KELURAHAN (DOMISILI)',
                    'address_domicile' => 'ALAMAT JALAN/RUMAH (DOMISILI)',
                    'latitude_gps_domicile' => 'LATITUDE GPS (DOMISILI)',
                    'longitude_gps_domicile' => 'LONGITUDE GPS (DOMISILI)',
                    'employment_status' => 'STATUS KERJA',
                    'batch' => 'BATCH',
                    'position' => 'JABATAN',
                    'work_assignment' => 'UNIT PENUGASAN',
                    'payroll_bank_name' => 'NAMA BANK PAYROLL',
                    'payroll_bank_account_number' => 'NOMOR REKENING PAYROLL',
                    'payroll_bank_account_name' => 'NAMA PEMILIK REKENING PAYROLL',
                    'facebook_url' => 'LINK AKUN FACEBOOK',
                    'instagram_url' => 'LINK AKUN INSTAGRAM',
                    'tiktok_url' => 'LINK AKUN TIKTOK',
                    ];

                    // Membagi array menjadi 4 bagian sama rata (chunk)
                    // 44 data / 4 kolom = 11 data per kolom
                    $chunks = array_chunk($mappingNames, ceil(count($mappingNames) / 4), true);
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-4">
                        @foreach($chunks as $chunk)
                        <div class="flex flex-col gap-y-3">
                            @foreach($chunk as $key => $label)
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="columns[]" value="{{ $key }}" checked
                                    class="download-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-[13px] text-slate-600 group-hover:text-indigo-600 leading-tight">
                                    {{ $label }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                        @endforeach
                    </div>

                    <div id="no-column-warning" class="mt-6 p-3 bg-rose-50 text-rose-600 rounded-lg border border-rose-100 hidden flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-[11px] font-semibold uppercase italic">Pilih minimal satu kolom!</span>
                    </div>
                </div>

                <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-3">
                    <button type="button" onclick="closeDownloadModal()" class="flex-1 py-4 text-[11px] font-semibold uppercase tracking-wider text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all">Batal</button>
                    <button type="button" id="btn-submit-download" onclick="submitAndClose()" class="flex-1 py-4 text-[11px] font-semibold uppercase tracking-wider text-white bg-emerald-600 rounded-xl shadow-lg hover:bg-emerald-700 transition-all active:scale-[0.98]">Unduh (.xlsx)</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL UPLOAD PENGGUNA --}}
    <div id="uploadModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeUploadModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-4xl overflow-hidden font-sans">

            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <span class="p-1.5 bg-amber-50 text-amber-600 rounded-lg shadow-sm border border-amber-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                    </span>
                    <h3 class="text-[14px] font-bold uppercase tracking-widest text-slate-700">Import Massal Akun Pengguna</h3>
                </div>
                <button type="button" onclick="closeUploadModal()" class="text-slate-400 hover:text-slate-600 text-2xl transition-colors">&times;</button>
            </div>

            <div class="p-8">
                {{-- Tahap 1: Pilih File & Download Template --}}
                <div id="upload-step-1" class="space-y-6">
                    <div id="format-error-msg" class="hidden p-4 bg-rose-50 text-rose-600 border border-rose-200 rounded-xl"></div>

                    <div class="flex items-center justify-between p-4 bg-indigo-50 border border-indigo-100 rounded-xl">
                        <div class="text-[11px] text-indigo-700">
                            <p class="font-bold uppercase">Gunakan Template Standar</p>
                            <p>Pastikan format kolom sesuai agar sistem tidak menolak.</p>
                        </div>
                        <a href="{{ route('admin.users.template') }}" class="px-4 py-2 bg-indigo-600 text-white text-[10px] font-bold rounded-lg uppercase shadow-md hover:bg-indigo-700 transition-all">Unduh Template</a>
                    </div>

                    <div class="space-y-4">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Pilih File (.xlsx):</label>
                        <input type="file" id="excel_file" accept=".xlsx" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-[11px] file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-indigo-50 cursor-pointer">
                        <button type="button" onclick="previewExcel()" class="w-full py-3 bg-slate-800 text-white text-[11px] font-bold uppercase rounded-xl hover:bg-black transition-all">Tampilkan Pratinjau Data</button>
                    </div>
                </div>

                {{-- Tahap 2: Preview Tabel & Opsi Import --}}
                <div id="upload-step-2" class="hidden space-y-6">
                    <div class="overflow-x-auto border border-slate-100 rounded-lg max-h-[300px]">
                        <table class="w-full text-[12px] text-left border-collapse">
                            <thead class="bg-slate-50 sticky top-0 shadow-sm">
                                <tr>
                                    <th class="p-3 border-b font-bold text-slate-500 uppercase">Email</th>
                                    <th class="p-3 border-b font-bold text-slate-500 uppercase">Telepon</th>
                                    <th class="p-3 border-b font-bold text-slate-500 uppercase text-center">Hak Akses Sistem</th>
                                    <th class="p-3 border-b font-bold text-slate-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody id="preview-body">
                                {{-- Baris data diisi via JavaScript --}}
                            </tbody>
                        </table>
                    </div>

                    <div id="summary-text" class="p-3 bg-slate-50 rounded-lg border border-slate-100"></div>

                    {{-- FORM UTAMA: Membungkus Opsi Database & Data JSON --}}
                    <form action="{{ route('admin.users.import') }}" method="POST" id="final-upload-form">
                        @csrf
                        <div class="space-y-3 mb-6">
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Opsi Database (Wajib Pilih):</p>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="p-4 border rounded-xl cursor-pointer hover:bg-slate-50 flex flex-col gap-1 transition-all border-slate-200 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/30">
                                    <input type="radio" name="import_mode" value="append" checked class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="text-xs font-bold uppercase text-slate-700">Tambah Data</span>
                                    <span class="text-[9px] text-slate-500 italic">Hanya menambah pengguna baru dari Excel.</span>
                                </label>

                                <label class="p-4 border rounded-xl cursor-pointer hover:bg-rose-50 flex flex-col gap-1 transition-all border-slate-200 has-[:checked]:border-rose-600 has-[:checked]:bg-rose-50/50">
                                    <input type="radio" name="import_mode" value="replace" class="text-rose-600 focus:ring-rose-500">
                                    <span class="text-xs font-bold uppercase text-rose-700">Buat Ulang</span>
                                    <span class="text-[9px] text-rose-400 italic font-medium">Hapus & Ganti semua data user selain Anda.</span>
                                </label>
                            </div>
                        </div>

                        <input type="hidden" name="json_data" id="json_data">

                        <div class="flex gap-3">
                            <button type="button" onclick="resetUpload()" class="flex-1 py-4 text-[11px] font-bold uppercase text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all">Ganti File</button>
                            <button type="submit" id="btn-save-import" class="flex-1 py-4 text-[11px] font-bold uppercase text-white bg-indigo-600 rounded-xl shadow-lg hover:bg-indigo-700 transition-all active:scale-[0.98]">Simpan Ke Database</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL KONFIRMASI HAPUS DATA --}}
    <div id="confirmReplaceModal" class="fixed inset-0 z-[110] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-md"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl border border-rose-100 w-full max-w-md overflow-hidden font-sans">
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </div>
                <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight mb-2">Konfirmasi Buat Ulang?</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Tindakan ini akan <b>MENGHAPUS SEMUA DATA PENGGUNA</b> yang ada di database (kecuali akun Anda) dan menggantinya dengan data dari file ini. Data yang dihapus tidak dapat dikembalikan.</p>
            </div>
            <div class="p-6 bg-slate-50 border-t border-slate-100 flex gap-3">
                <button type="button" onclick="closeConfirmModal()" class="flex-1 py-3 text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition-all">Batalkan</button>
                <button type="button" onclick="finalSubmitImport()" class="flex-1 py-3 text-[11px] font-bold uppercase tracking-wider text-white bg-rose-600 rounded-xl shadow-lg shadow-rose-200 hover:bg-rose-700 transition-all">Ya, Hapus & Ganti</button>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH PENGGUNA --}}
    <div id="addUserModal" class="fixed inset-0 z-[99] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeAddUserModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-md overflow-hidden transform transition-all font-sans text-sm">

            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center text-slate-800">
                <div class="flex items-center gap-3">
                    <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </span>
                    <h3 class="text-[14px] font-bold uppercase tracking-widest">Tambah Akun Pengguna</h3>
                </div>
                <button type="button" onclick="closeAddUserModal()" class="text-slate-400 hover:text-slate-600 text-2xl cursor-pointer">&times;</button>
            </div>

            {{-- Form dengan autocomplete off dan id unik agar tidak bentrok --}}
            <form action="{{ route('admin.users.store') }}" method="POST" autocomplete="off" id="formAddUser">
                @csrf
                <div class="p-8 space-y-5">
                    {{-- Email --}}
                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Email</label>
                        <input type="email" name="email" id="add_email" required
                            oninput="checkUniqueness('email', this.value)"
                            placeholder="Cth: user@email.com"
                            class="w-full mt-2 px-4 py-2.5 bg-gray-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        <p id="error_email" class="text-[10px] text-rose-600 mt-1 font-bold hidden uppercase tracking-tight"></p>
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nomor WhatsApp</label>
                        <input type="number" name="phone" id="add_phone" required
                            oninput="checkUniqueness('phone', this.value)"
                            placeholder="Cth: 085xxxxxxxxx"
                            class="w-full mt-2 px-4 py-2.5 bg-gray-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        <p id="error_phone" class="text-[10px] text-rose-600 mt-1 font-bold hidden uppercase tracking-tight"></p>
                    </div>

                    {{-- Role --}}
                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Hak Akses Sistem</label>
                        <select name="id_ref_role" required class="w-full mt-2 px-3 py-2.5 bg-gray-50 border border-slate-200 rounded-lg text-sm outline-none">
                            <option value="" disabled selected>Pilih Role</option>
                            @foreach($roles as $r)
                            <option value="{{$r->id_ref_role}}">{{$r->name_role}}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Password Section --}}
                    <!-- <div class="space-y-3 pt-4 border-t border-slate-50" disabled>
                        <div class="flex justify-between items-center">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kata Sandi</label>
                            <button type="button" onclick="generateStrongPassword()" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800 uppercase bg-indigo-50 px-2 py-1 rounded transition-colors">Dapatkan Acak</button>
                        </div>

                        {{-- Area Copas Password (Muncul setelah klik generate) --}}
                        <div id="copy_area" class="hidden transform transition-all duration-300">
                            <div class="flex items-center gap-2 p-2 bg-emerald-50 border border-emerald-100 rounded-lg">
                                <input type="text" id="pass_display" readonly class="flex-1 bg-transparent border-none text-xs font-mono font-bold text-emerald-700 focus:ring-0 p-0">
                                <button type="button" onclick="copyToClipboard()" id="btn_copy" class="text-[10px] font-bold text-white bg-emerald-600 px-3 py-1 rounded hover:bg-emerald-700 transition-all">Salin</button>
                            </div>
                        </div>

                        <div class="relative group">
                            <input type="password" name="password" id="add_password" required placeholder="Buat Kata Sandi" autocomplete="new-password"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all pr-10">
                            <button type="button" onclick="togglePass('add_password', 'eye_add_1')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-indigo-600">
                                <svg class="h-5 w-5" fill="none" id="eye_add_1" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>

                        <div class="relative group">
                            <input type="password" name="password_confirmation" id="add_password_conf" required placeholder="Ulangi Kata Sandi" autocomplete="new-password"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all pr-10">
                            <button type="button" onclick="togglePass('add_password_conf', 'eye_add_2')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-indigo-600">
                                <svg class="h-5 w-5" fill="none" id="eye_add_2" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div> -->
                </div>

                <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-3">
                    <button type="button" onclick="closeAddUserModal()" class="flex-1 py-3 text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all">Batal</button>
                    <button type="submit" id="btn_submit_add" disabled class="flex-1 py-3 text-[11px] font-bold uppercase tracking-wider text-white bg-indigo-600 rounded-xl shadow-lg hover:bg-indigo-700 transition-all active:scale-[0.98]">Buat Akun</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MASTER MODAL EDIT --}}
    <div id="masterModal" class="fixed inset-0 z-[99] hidden flex items-center justify-center p-4 text-left">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeMasterEditModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-6xl overflow-hidden transform transition-all font-sans text-sm">

            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center text-slate-800">
                <div class="flex items-center gap-3">
                    <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </span>
                    <h3 class="text-[14px] font-bold uppercase tracking-widest" id="modal_header_title">Edit Data Profil Pengguna</h3>
                </div>
                <button type="button" onclick="closeMasterEditModal()" class="text-slate-400 hover:text-slate-600 text-2xl cursor-pointer">&times;</button>
            </div>

            <form id="masterForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="method_field"></div>

                <div class="p-8 max-h-[75vh] overflow-y-auto space-y-10">

                    {{-- SECTION 1: FOTO & IDENTITAS UTAMA --}}
                    <div class="flex flex-col lg:flex-row gap-12">
                        <div class="shrink-0 flex flex-col items-center gap-4">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Pas Foto (4x6)</label>
                            <div class="relative group">
                                <div class="h-60 w-40 rounded-2xl overflow-hidden bg-indigo-600 border-4 border-white shadow-lg ring-1 ring-slate-100 bg-gray-50 flex items-center justify-center text-center">
                                    <img id="cropped-preview" class="h-full w-full object-cover cursor-pointer hidden" src="" alt="Preview">
                                    <div id="initial-placeholder" class="text-white text-6xl uppercase"></div>
                                </div>
                                <label for="photo" class="absolute bottom-3 right-3 p-2.5 bg-indigo-400 text-white rounded-xl shadow-xl cursor-pointer hover:bg-indigo-500 transition-all hover:scale-110">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <input type="file" id="photo" name="photo" class="hidden" accept="image/*">
                                </label>
                            </div>
                        </div>

                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            <div class="md:col-span-2">
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Lengkap (Sesuai KTP)</label>
                                <input required type="text" name="name" id="f_name" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Email</label><input required type="email" name="email" id="f_email" class="w-full mt-2 px-4 py-2.5 bg-slate-100 border-none rounded-lg text-sm text-slate-400 cursor-not-allowed" readonly></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Telepon</label><input required type="number" name="phone" id="f_phone" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500"></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">NIK (16 Digit)</label><input required type="number" name="nik" id="f_nik" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm"></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nomor KK (16 Digit)</label><input required type="number" name="no_kk" id="f_no_kk" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm"></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">NIP</label><input required type="number" name="nip" id="f_nip" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500"></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">NPWP</label><input required type="number" name="npwp" id="f_npwp" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500"></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">No. BPJS Kesehatan</label><input required type="number" name="no_bpjs_kes" id="f_bpjs_kes" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500"></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">No. BPJS Ketenagakerjaan</label><input required type="number" name="no_bpjs_tk" id="f_bpjs_tk" class="w-full mt-2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500"></div>
                        </div>
                    </div>

                    {{-- SECTION: KEDINASAN --}}
                    <div class="pt-10 border-t border-gray-100">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Penugasan & Status Kerja</h3>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                            <div class="col-span-2">
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Unit Penugasan</label>
                                <select name="id_work_assignment" id="f_wa" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm">
                                    <option value="">Belum Penugasan</option>
                                    @foreach($workAssignments as $wa) <option value="{{ $wa->id_work_assignment }}">{{ $wa->sppgUnit->name }} - {{ $wa->decree->no_sk }}</option> @endforeach
                                </select>
                            </div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Batch</label><select required name="batch" id="f_batch" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm">@foreach(['1', '2', '3', 'Non-SPPI'] as $b) <option value="{{ $b }}">{{ $b }}</option> @endforeach</select></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Hak Akses Sistem</label><select required name="id_ref_role" id="f_role" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm">@foreach($roles as $r)<option value="{{$r->id_ref_role}}">{{$r->name_role}}</option>@endforeach</select></div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Jabatan</label>
                                <select name="id_ref_position" id="f_pos" class="w-full mt-2 px-3 py-2.5 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                    <option value="">Belum Menjabat</option>
                                    @foreach($positions as $p)
                                    <option value="{{ $p->id_ref_position }}"
                                        {{ (old('id_ref_position', $person->id_ref_position ?? '') == $p->id_ref_position) ? 'selected' : '' }}>
                                        {{ $p->name_position }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION: PERSONAL & PENDIDIKAN --}}
                    <div class="pt-10 border-t border-gray-100">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Detail Personal & Pendidikan</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Pendidikan Terakhir</label><select required name="last_education" id="f_last_edu" class="w-full mt-1 px-3 py-2 bg-gray-50 border-none rounded-lg text-sm">@foreach(['D-III', 'D-IV', 'S-1', 'S-2'] as $edu)<option value="{{ $edu }}">{{ $edu }}</option>@endforeach</select></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Gelar Belakang</label><input required type="text" name="title_education" id="f_title_edu" placeholder="S.Kom." class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm"></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Jurusan / Prodi</label><input required type="text" name="major_education" id="f_major" placeholder="Ilmu Kelautan" class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm"></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Status Kerja</label><select required name="employment_status" id="f_emp" class="w-full mt-1 px-3 py-2 bg-gray-50 border-none rounded-lg text-sm">
                                    <option value="ASN">ASN</option>
                                    <option value="Non-ASN">Non-ASN</option>
                                </select></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tempat Lahir</label><input required type="text" name="place_birthday" id="f_place" class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm"></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tanggal Lahir</label><input required type="date" name="date_birthday" id="f_date" class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm"></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Umur</label><input required type="text" id="f_age" readonly class="w-full mt-1 px-4 py-2 bg-slate-50 border-none rounded-lg text-sm text-slate-400 cursor-not-allowed"></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Agama</label><select required name="religion" id="f_religion" class="w-full mt-1 px-3 py-2 bg-gray-50 border-none rounded-lg text-sm">@foreach(['Islam', 'Kristen', 'Katholik', 'Hindu', 'Buddha', 'Khonghucu'] as $rel)<option value="{{ $rel }}">{{ $rel }}</option>@endforeach</select></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Gender</label><select required name="gender" id="f_gender" class="w-full mt-1 px-3 py-2 bg-gray-50 border-none rounded-lg text-sm">
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Status Pernikahan</label><select required name="marital_status" id="f_marital" class="w-full mt-1 px-3 py-2 bg-gray-50 border-none rounded-lg text-sm">@foreach(['Belum Kawin', 'Kawin', 'Janda', 'Duda'] as $status)<option value="{{ $status }}">{{ $status }}</option>@endforeach</select></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Ukuran Baju</label><select required name="clothing_size" id="f_cloth" class="w-full mt-1 px-3 py-2 bg-gray-50 border-none rounded-lg text-sm">@foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', '4XL', '5XL'] as $size)<option value="{{ $size }}">{{ $size }}</option>@endforeach</select></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Ukuran Sepatu</label><select required name="shoe_size" id="f_shoe" class="w-full mt-1 px-3 py-2 bg-gray-50 border-none rounded-lg text-sm">@for($i=35; $i<=50; $i++)<option value="{{ $i }}">{{ $i }}</option>@endfor</select></div>
                        </div>
                    </div>

                    {{-- SECTION: ALAMAT KTP --}}
                    <div class="pt-10 border-t border-gray-100">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Alamat KTP</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-sm">
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Provinsi</label>
                                <select required name="province_ktp" id="f_ktp_prov" data-selected="{{ $user->province_ktp ?? '' }}" class="w-full px-4 py-2 bg-gray-50 border-none rounded-lg text-sm">
                                    <option value="" disabled selected>Pilih Provinsi</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kabupaten</label>
                                <select required name="regency_ktp" id="f_ktp_reg" data-selected="{{ $user->regency_ktp ?? '' }}" class="w-full px-4 py-2 bg-gray-50 border-none rounded-lg text-sm" disabled>
                                    <option value="" disabled selected>Pilih Kabupaten</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kecamatan</label>
                                <select required name="district_ktp" id="f_ktp_dist" data-selected="{{ $user->district_ktp ?? '' }}" class="w-full px-4 py-2 bg-gray-50 border-none rounded-lg text-sm" disabled>
                                    <option value="" disabled selected>Pilih Kecamatan</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Desa/Kelurahan</label>
                                <select required name="village_ktp" id="f_ktp_vill" data-selected="{{ $user->village_ktp ?? '' }}" class="w-full px-4 py-2 bg-gray-50 border-none rounded-lg text-sm" disabled>
                                    <option value="" disabled selected>Pilih Desa/Kelurahan</option>
                                </select>
                            </div>
                            <div class="md:col-span-4"><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Alamat Jalan/Rumah</label><textarea required name="address_ktp" id="f_ktp_address" rows="2" class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm">{{ $user->address_ktp ?? '' }}</textarea></div>
                        </div>
                    </div>

                    {{-- SECTION: DOMISILI --}}
                    <div class="pt-10 border-t border-gray-100">
                        <div class="flex justify-between items-center mb-6 text-nowrap">
                            <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600">Alamat Domisili & GPS</h3>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" id="sync_address_admin" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest group-hover:text-indigo-600 transition-colors">Gunakan Alamat KTP</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                            <div class="space-y-6">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Provinsi</label>
                                        <select required name="province_domicile" id="f_dom_prov" data-selected="{{ $user->province_domicile ?? '' }}" class="w-full px-4 py-2 bg-gray-50 border-none rounded-lg text-sm">
                                            <option value="" disabled selected>Pilih Provinsi</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kabupaten</label>
                                        <select required name="regency_domicile" id="f_dom_reg" data-selected="{{ $user->regency_domicile ?? '' }}" class="w-full px-4 py-2 bg-gray-50 border-none rounded-lg text-sm" disabled>
                                            <option value="" disabled selected>Pilih Kabupaten</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kecamatan</label>
                                        <select required name="district_domicile" id="f_dom_dist" data-selected="{{ $user->district_domicile ?? '' }}" class="w-full px-4 py-2 bg-gray-50 border-none rounded-lg text-sm" disabled>
                                            <option value="" disabled selected>Pilih Kecamatan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Desa/Kelurahan</label>
                                        <select required name="village_domicile" id="f_dom_vill" data-selected="{{ $user->village_domicile ?? '' }}" class="w-full px-4 py-2 bg-gray-50 border-none rounded-lg text-sm" disabled>
                                            <option value="" disabled selected>Pilih Desa/Kelurahan</option>
                                        </select>
                                    </div>
                                </div>
                                <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Alamat Jalan/Rumah</label><textarea required name="address_domicile" id="f_dom_address" rows="2" class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-indigo-500"></textarea></div>
                                <div>
                                    <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Koordinat GPS (Klik Pada Peta)</label>
                                    <div class="flex gap-2 mt-2 text-nowrap">
                                        <input required type="text" id="f_dom_lat" name="latitude_gps_domicile" readonly class="w-1/2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm input-disabled">
                                        <input required type="text" id="f_dom_lng" name="longitude_gps_domicile" readonly class="w-1/2 px-4 py-2.5 bg-gray-50 border-none rounded-lg text-sm input-disabled">
                                    </div>
                                </div>
                            </div>
                            <div id="map" style="min-height: 300px; background: #eee;"></div>
                        </div>
                    </div>

                    {{-- SECTION: PAYROLL --}}
                    <div class="pt-10 border-t border-gray-100">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Informasi Payroll</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-sm">
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Bank</label><select required name="payroll_bank_name" id="f_bank_name" class="w-full px-4 py-2 bg-gray-50 border-none rounded-lg text-sm">@foreach(['BNI', 'Mandiri', 'BCA', 'BTN', 'BSI', 'BPD Bali'] as $bank)<option value="{{ $bank }}">{{ $bank }}</option>@endforeach</select></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nomor Rekening</label><input required type="number" name="payroll_bank_account_number" id="f_bank_acc" class="w-full px-4 py-2 bg-gray-50 border-none rounded-lg text-sm"></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Pemilik Rekening</label><input required type="text" name="payroll_bank_account_name" id="f_bank_owner" class="w-full px-4 py-2 bg-gray-50 border-none rounded-lg text-sm"></div>
                        </div>
                    </div>

                    {{-- SECTION: SOSMED --}}
                    <div class="pt-10 border-t border-gray-100">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-600 mb-6">Tautan Media Sosial</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-sm">
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Facebook</label><input type="url" name="facebook_url" id="f_fb" class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm" placeholder="https://facebook.com/.."></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Instagram</label><input type="url" name="instagram_url" id="f_ig" class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm" placeholder="https://instagram.com/.."></div>
                            <div><label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">TikTok</label><input type="url" name="tiktok_url" id="f_tt" class="w-full mt-1 px-4 py-2 bg-gray-50 border-none rounded-lg text-sm" placeholder="https://tiktok.com/@.."></div>
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-4">
                    <button type="button" onclick="closeMasterEditModal()" class="flex-1 py-4 text-[11px] font-bold uppercase text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-100">Batal</button>
                    <button type="submit" class="flex-1 py-4 text-[11px] font-bold uppercase text-white bg-slate-800 rounded-xl shadow-lg hover:bg-slate-900 transition-all active:scale-95">Simpan Seluruh Perubahan Profil</button>
                </div>
            </form>
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
                <button onclick="closeDeleteModal()" class="flex-1 py-3 text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors font-sans">Batal</button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" id="delete_btn_text" class="w-full py-3 text-[11px] font-bold uppercase tracking-wider text-white bg-rose-600 rounded-xl shadow-lg hover:bg-rose-700 transition-colors font-sans font-bold">Hapus Sekarang</button>
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
            <h3 class="text-lg font-bold text-slate-800 uppercase tracking-widest mb-2 text-center">Setujui Semua Pendaftar?</h3>
            <p class="text-sm text-center text-slate-500 mb-6 leading-relaxed">Tentukan penugasan, akses, dan jabatan untuk seluruh antrian pendaftar.</p>

            <form action="{{ route('admin.users.approve-all') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1 block">Penugasan</label>
                    <select name="id_work_assignment" required class="w-full border-slate-200 rounded-xl text-sm py-3 px-4 focus:ring-emerald-500 outline-none font-sans shadow-sm">
                        <option value="" disabled selected>Pilih Penugasan</option>
                        <option value="none">Belum Penugasan</option>
                        @foreach($workAssignments as $wa)
                        <option value="{{$wa->id_work_assignment}}">{{$wa->sppgUnit->name}} (SK: {{$wa->decree->no_sk}})</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1 block">Hak Akses</label>
                        <select name="id_ref_role" required class="w-full border-slate-200 rounded-xl text-sm py-3 px-4 focus:ring-emerald-500 outline-none font-sans shadow-sm">
                            <option value="" disabled selected>Pilih Hak Akses</option>
                            @foreach($roles as $r)
                            <option value="{{$r->id_ref_role}}">{{$r->name_role}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1 block">Jabatan</label>
                        <select name="id_ref_position" required class="w-full border-slate-200 rounded-xl text-sm py-3 px-4 focus:ring-emerald-500 outline-none font-sans shadow-sm">
                            <option value="" disabled selected>Pilih Jabatan</option>
                            <option value="none">Belum Menjabat</option>
                            @foreach($positions as $p)
                            <option value="{{$p->id_ref_position}}">{{$p->name_position}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeApproveAllModal()" class="flex-1 py-3 text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors font-sans">Batal</button>
                    <button type="submit" class="flex-1 py-3 text-[11px] font-bold uppercase tracking-wider text-white bg-emerald-600 rounded-xl shadow-lg hover:bg-emerald-700 transition-colors font-sans font-bold">Setujui Semua</button>
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
        let searchTimer;

        // 1. Fungsi Utama Refresh
        function refreshTable(url, focusId = null) {
            const container = document.getElementById('user-table-container');
            if (!container) return;

            fetch(url, {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                .then(response => response.text())
                .then(html => {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, 'text/html');
                    let newContent = doc.getElementById('user-table-container').innerHTML;

                    // Update konten
                    container.innerHTML = newContent;

                    // Sinkronisasi URL tanpa reload
                    window.history.pushState({}, '', url);

                    // KRUSIAL: Gunakan requestAnimationFrame agar fokus dipicu 
                    // SETELAH browser selesai menggambar ulang (rendering) HTML baru
                    if (focusId) {
                        requestAnimationFrame(() => {
                            const activeInput = document.getElementById(focusId);
                            if (activeInput) {
                                activeInput.focus();
                                // Paksa kursor ke urutan terakhir teks
                                const val = activeInput.value;
                                activeInput.value = '';
                                activeInput.value = val;
                            }
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // 2. Event Listener untuk Mengetik (Otomatis & Enter)
        document.addEventListener('keydown', function(e) {
            if (e.target.classList.contains('live-search-input')) {
                const inputEl = e.target;
                const type = inputEl.getAttribute('data-table');
                const inputId = inputEl.id;

                // JIKA TEKAN ENTER
                if (e.key === 'Enter') {
                    e.preventDefault(); // Cegah reload halaman
                    clearTimeout(searchTimer); // Batalkan timer otomatis karena langsung dieksekusi

                    let currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('search_' + type, inputEl.value);
                    currentUrl.searchParams.delete(type + '_page');

                    refreshTable(currentUrl.toString(), inputId);
                }
            }
        });

        // 3. Event Listener untuk Timer Otomatis (Debounce)
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('live-search-input')) {
                clearTimeout(searchTimer);

                const inputEl = e.target;
                const type = inputEl.getAttribute('data-table');
                const inputId = inputEl.id;

                searchTimer = setTimeout(() => {
                    let currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('search_' + type, inputEl.value);
                    currentUrl.searchParams.delete(type + '_page');

                    refreshTable(currentUrl.toString(), inputId);
                }, 700); // Jeda sedikit lebih lama (700ms) agar tidak terlalu sering request saat mengetik cepat
            }
        });

        // 4. Listener Pagination (Tetap Sama)
        document.addEventListener('click', function(e) {
            let anchor = e.target.closest('#user-table-container nav a');
            if (anchor && anchor.getAttribute('href')) {
                let url = anchor.getAttribute('href');
                if (url.includes('page=') && !url.startsWith('javascript')) {
                    e.preventDefault();
                    refreshTable(url);
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const apiBase = "/api-wilayah";
            const sleep = (ms) => new Promise(resolve => setTimeout(resolve, ms));

            // 1. Fungsi Inti Fetch & Match
            async function populateSelect(selectId, urlPath, placeholder, targetValue = null) {
                const select = document.getElementById(selectId);
                if (!select) return null;

                // Ambil nilai yang baru saja disuntikkan ke atribut data-selected
                const savedValue = select.getAttribute('data-selected');
                let valueToMatch = (targetValue || savedValue || "").toString().toUpperCase().trim();

                select.innerHTML = `<option value="">Mohon tunggu...</option>`;
                select.disabled = true;

                try {
                    const response = await fetch(`${apiBase}/${urlPath}`);
                    const result = await response.json();
                    const data = result.data;

                    let options = `<option value="">${placeholder}</option>`;
                    data.forEach(item => {
                        /**
                         * LOGIKA PEMBERSIHAN NAMA:
                         * Menghapus kata "KABUPATEN " atau "KOTA " hanya jika berada di awal string.
                         * Regex /^.../i memastikan pengecekan dilakukan di awal dan tidak peka huruf besar/kecil.
                         */
                        let cleanName = item.name.replace(/^(KABUPATEN|KOTA)\s+/i, "");

                        options += `<option value="${cleanName}" data-code="${item.code}">${cleanName}</option>`;
                    });
                    select.innerHTML = options;
                    select.disabled = false;

                    if (valueToMatch) {
                        for (let i = 0; i < select.options.length; i++) {
                            if (select.options[i].value.toUpperCase() === valueToMatch) {
                                select.selectedIndex = i;
                                return select.options[i].getAttribute('data-code');
                            }
                        }
                    }
                } catch (e) {
                    console.error("Error loading " + selectId, e);
                    select.innerHTML = `<option value="">Gagal memuat</option>`;
                }
                return null;
            }

            // 2. Fungsi Rantai Loading (Berurutan)
            async function runChain(prefix) {
                const provCode = await populateSelect(`f_${prefix}_prov`, 'provinces.json', "Pilih Provinsi");
                if (provCode) {
                    const regCode = await populateSelect(`f_${prefix}_reg`, `regencies/${provCode}.json`, "Pilih Kabupaten");
                    if (regCode) {
                        const distCode = await populateSelect(`f_${prefix}_dist`, `districts/${regCode}.json`, "Pilih Kecamatan");
                        if (distCode) {
                            await populateSelect(`f_${prefix}_vill`, `villages/${distCode}.json`, "Pilih Desa/Kelurahan");
                        }
                    }
                }
            }

            // 3. Integrasi ke Fungsi Modal Global
            const oldOpenModal = window.openMasterEditModal;
            window.openMasterEditModal = function(user, person, isApprove) {
                // Jalankan fungsi pengisian field biasa (Nama, NIK, dll)
                if (oldOpenModal) oldOpenModal(user, person, isApprove);

                // Suntikkan data wilayah person ke atribut data-selected masing-masing select
                const mapping = {
                    'f_ktp_prov': person?.province_ktp,
                    'f_ktp_reg': person?.regency_ktp,
                    'f_ktp_dist': person?.district_ktp,
                    'f_ktp_vill': person?.village_ktp,
                    'f_dom_prov': person?.province_domicile,
                    'f_dom_reg': person?.regency_domicile,
                    'f_dom_dist': person?.district_domicile,
                    'f_dom_vill': person?.village_domicile
                };

                Object.entries(mapping).forEach(([id, val]) => {
                    const el = document.getElementById(id);
                    if (el) el.setAttribute('data-selected', val || '');
                });

                // Jalankan loading wilayah secara otomatis setelah modal terbuka
                setTimeout(() => {
                    runChain('ktp');
                    runChain('dom');
                }, 500);
            };

            // 4. Listener Perubahan Manual (User klik sendiri)
            const bindEvents = (p) => {
                ['prov', 'reg', 'dist'].forEach(f => {
                    const el = document.getElementById(`f_${p}_${f}`);
                    if (!el) return;

                    el.addEventListener('change', async function() {
                        const isSyncActive = document.getElementById('sync_address_admin').checked;
                        const code = this.options[this.selectedIndex]?.getAttribute('data-code');

                        const nextMap = {
                            'prov': ['reg', 'dist', 'vill'],
                            'reg': ['dist', 'vill'],
                            'dist': ['vill']
                        };

                        // --- FUNGSI RESET TOTAL ---
                        const resetLevel = (prefix, fields) => {
                            fields.forEach(target => {
                                const targetEl = document.getElementById(`f_${prefix}_${target}`);
                                if (targetEl) {
                                    targetEl.innerHTML = '<option value="">Pilih...</option>';
                                    // Jika dom & sync aktif, pastikan tetap terkunci
                                    if (prefix === 'dom' && isSyncActive) {
                                        targetEl.classList.add('input-disabled');
                                        targetEl.disabled = true;
                                    }
                                }
                            });
                        };

                        // Reset level bawah untuk prefix yang sedang diubah (KTP atau Dom)
                        resetLevel(p, nextMap[f]);

                        // Jika ubah KTP dan centang aktif, reset juga level bawah di Domisili
                        if (p === 'ktp' && isSyncActive) {
                            resetLevel('dom', nextMap[f]);
                            // Samakan nilai dropdown yang baru saja dipilih
                            const domSameLevel = document.getElementById(`f_dom_${f}`);
                            if (domSameLevel) domSameLevel.value = this.value;
                        }

                        if (code) {
                            const nextField = f === 'prov' ? 'reg' : (f === 'reg' ? 'dist' : 'vill');
                            const path = f === 'prov' ? 'regencies' : (f === 'reg' ? 'districts' : 'villages');

                            // Isi dropdown level berikutnya
                            await populateSelect(`f_${p}_${nextField}`, `${path}/${code}.json`, "Pilih...");

                            // Jika sync aktif, isi juga dropdown level berikutnya di domisili
                            if (p === 'ktp' && isSyncActive) {
                                await populateSelect(`f_dom_${nextField}`, `${path}/${code}.json`, "Pilih...");
                                const domNextEl = document.getElementById(`f_dom_${nextField}`);
                                if (domNextEl) {
                                    domNextEl.classList.add('input-disabled');
                                    domNextEl.disabled = true;
                                }
                            }
                        }

                        if (p === 'dom' || (p === 'ktp' && isSyncActive)) {
                            setTimeout(autoMoveMap, 300);
                        }
                    });
                });
            };

            bindEvents('ktp');
            bindEvents('dom');

            // 5. Fitur Sync (Gunakan Alamat KTP)
            const syncBtn = document.getElementById('sync_address_admin');
            if (syncBtn) {
                syncBtn.addEventListener('change', async function() {
                    if (this.checked) {
                        const ktpData = {
                            'f_dom_prov': document.getElementById('f_ktp_prov').value,
                            'f_dom_reg': document.getElementById('f_ktp_reg').value,
                            'f_dom_dist': document.getElementById('f_ktp_dist').value,
                            'f_dom_vill': document.getElementById('f_ktp_vill').value
                        };

                        // Suntikkan nilai KTP ke data-selected Domisili
                        Object.entries(ktpData).forEach(([id, val]) => {
                            const el = document.getElementById(id);
                            if (el) el.setAttribute('data-selected', val);
                        });

                        const ktpAddr = document.getElementById('f_ktp_address');
                        const domAddr = document.getElementById('f_dom_address');
                        if (ktpAddr && domAddr) domAddr.value = ktpAddr.value;

                        // Jalankan ulang chain khusus untuk domisili
                        runChain('dom');
                    }
                });
            }
        });


        // --- LOGIC SINKRONISASI ALAMAT DI ADMIN ---
        const syncBtnAdmin = document.getElementById('sync_address_admin');
        const addrMap = {
            'f_ktp_prov': 'f_dom_prov',
            'f_ktp_reg': 'f_dom_reg',
            'f_ktp_dist': 'f_dom_dist',
            'f_ktp_vill': 'f_dom_vill',
            'f_ktp_address': 'f_dom_address'
        };

        function performSync() {
            const isChecked = document.getElementById('sync_address_admin').checked;
            const fields = ['prov', 'reg', 'dist', 'vill', 'address'];

            fields.forEach(field => {
                const ktpEl = document.getElementById(`f_ktp_${field}`);
                const domEl = document.getElementById(`f_dom_${field}`);

                if (domEl && ktpEl) {
                    if (isChecked) {
                        // SINKRONKAN DATA
                        domEl.value = ktpEl.value;
                        domEl.classList.add('input-disabled');

                        if (domEl.tagName === 'SELECT') {
                            domEl.disabled = true;
                        } else {
                            domEl.readOnly = true;
                        }
                    } else {
                        // LEPASKAN KUNCI
                        domEl.classList.remove('input-disabled');
                        domEl.disabled = false;
                        domEl.readOnly = false;
                    }
                }
            });

            if (isChecked) {
                runChain('dom'); // Muat ulang semua dropdown domisili agar sinkron
                autoMoveMap();
            }
        }

        document.getElementById('f_ktp_address').addEventListener('input', function() {
            if (document.getElementById('sync_address_admin').checked) {
                const domAddr = document.getElementById('f_dom_address');
                domAddr.value = this.value;
            }
        });

        syncBtnAdmin.addEventListener('change', performSync);

        // Agar ketika admin mengetik di field KTP, field domisili langsung terupdate (jika centang aktif)
        Object.keys(addrMap).forEach(ktpId => {
            document.getElementById(ktpId).addEventListener('input', () => {
                if (syncBtnAdmin.checked) performSync();
            });
        });


        let mapObj, markerObj, cropperObj;
        const photoInputEl = document.getElementById('photo');

        // 1. Fungsi Asli Anda (TIDAK ADA YANG DIUBAH)
        function setupMap(lat, lng) {
            if (mapObj) mapObj.remove();

            let initialLat = lat || -8.112;
            let initialLng = lng || 115.091;

            mapObj = L.map('map').setView([initialLat, initialLng], 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapObj);

            if (lat && lng) {
                markerObj = L.marker([lat, lng]).addTo(mapObj);
            }

            mapObj.on('click', function(e) {
                if (markerObj) mapObj.removeLayer(markerObj);
                markerObj = L.marker(e.latlng).addTo(mapObj);

                // Update input latitude dan longitude menggunakan ID f_dom_...
                const latEl = document.getElementById('f_dom_lat');
                const lngEl = document.getElementById('f_dom_lng');
                if (latEl) latEl.value = e.latlng.lat.toFixed(8);
                if (lngEl) lngEl.value = e.latlng.lng.toFixed(8);
            });
        }

        // 2. Fungsi Tambahan agar Peta Dinamis mengikuti Dropdown
        async function autoMoveMap() {
            const prov = document.getElementById('f_dom_prov')?.value;
            const reg = document.getElementById('f_dom_reg')?.value;
            const dist = document.getElementById('f_dom_dist')?.value;
            const vill = document.getElementById('f_dom_vill')?.value;

            let queryParts = [vill, dist, reg, prov].filter(Boolean);
            let address = queryParts.join(", ");

            if (!address || !mapObj) return;

            try {
                const resp = await fetch(`/api-map-search?q=${encodeURIComponent(address)}`);
                const data = await resp.json();

                if (data && data.length > 0) {
                    const newLat = parseFloat(data[0].lat);
                    const newLng = parseFloat(data[0].lon);

                    let zoomLevel = 9;
                    if (vill) zoomLevel = 16;
                    else if (dist) zoomLevel = 14;
                    else if (reg) zoomLevel = 12;

                    mapObj.setView([newLat, newLng], zoomLevel, {
                        animate: true
                    });
                }
            } catch (e) {
                console.error("Gagal koordinasi peta:", e);
            }
        }

        // Fungsi untuk membuka modal download
        window.openDownloadModal = function() {
            toggleAllCheckboxes(true);
            document.getElementById('downloadModal').classList.remove('hidden');
        }

        window.submitAndClose = function() {
            const form = document.getElementById('formDownloadExcel');
            const checkboxes = document.querySelectorAll('.download-checkbox:checked');

            // Validasi terakhir: pastikan ada yang dicentang
            if (checkboxes.length === 0) {
                alert('Silakan pilih minimal satu kolom untuk diunduh!');
                return;
            }

            // 1. Jalankan pengiriman form
            form.submit();

            // 2. Langsung tutup modal setelah jeda sangat singkat 
            // agar browser tetap sempat memproses request-nya
            setTimeout(() => {
                closeDownloadModal();
            }, 100);
        };

        // Fungsi tutup modal yang sudah ada
        window.closeDownloadModal = function() {
            const modal = document.getElementById('downloadModal');
            modal.classList.add('hidden');
        };

        // Fungsi Pilih/Hapus Semua
        window.toggleAllCheckboxes = function(status) {
            const checkboxes = document.querySelectorAll('.download-checkbox');
            checkboxes.forEach((checkbox) => {
                checkbox.checked = status;
            });
            validateDownloadSelection(); // Jalankan validasi setelah toggle
        }

        // Fungsi Validasi agar tidak bisa download jika kosong
        function validateDownloadSelection() {
            const checkboxes = document.querySelectorAll('.download-checkbox:checked');
            const btnSubmit = document.getElementById('btn-submit-download');
            const warning = document.getElementById('no-column-warning');

            if (checkboxes.length === 0) {
                // Jika tidak ada yang dicentang
                btnSubmit.disabled = true;
                btnSubmit.classList.add('opacity-50', 'cursor-not-allowed', 'grayscale');
                warning.classList.remove('hidden');
            } else {
                // Jika ada minimal satu yang dicentang
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed', 'grayscale');
                warning.classList.add('hidden');
            }
        }

        // Tambahkan event listener ke semua checkbox saat halaman dimuat
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('download-checkbox')) {
                validateDownloadSelection();
            }
        });


        // Fungsi untuk membuka modal Upload
        window.openUploadModal = function() {
            const modal = document.getElementById('uploadModal');
            if (modal) {
                // Reset ke langkah pertama setiap kali dibuka
                resetUpload();
                modal.classList.remove('hidden');
            } else {
                console.error("Elemen dengan ID 'uploadModal' tidak ditemukan!");
            }
        };

        // Fungsi untuk menutup modal Upload
        window.closeUploadModal = function() {
            const modal = document.getElementById('uploadModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        };

        // Fungsi Reset (Penting agar tampilan kembali ke awal pilih file)
        window.resetUpload = function() {
            const step1 = document.getElementById('upload-step-1');
            const step2 = document.getElementById('upload-step-2');
            const fileInput = document.getElementById('excel_file');

            if (step1 && step2) {
                step1.classList.remove('hidden');
                step2.classList.add('hidden');
            }
            if (fileInput) fileInput.value = '';
        };


        window.previewExcel = function() {
            // 1. Ambil elemen yang dibutuhkan
            const fileInput = document.getElementById('excel_file');
            const formatErrorContainer = document.getElementById('format-error-msg');

            // 2. Reset tampilan error setiap kali tombol ditekan
            if (formatErrorContainer) {
                formatErrorContainer.classList.add('hidden');
                formatErrorContainer.innerHTML = '';
            }

            // 3. Cek Library XLSX
            if (typeof XLSX === 'undefined') {
                alert('Library Excel belum siap. Mohon tunggu sebentar atau refresh halaman.');
                return;
            }

            // 4. VALIDASI: Cek jika file tidak ada
            if (!fileInput || !fileInput.files[0]) {
                if (formatErrorContainer) {
                    formatErrorContainer.innerHTML = `
                <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <div>
                            <p class="font-bold uppercase tracking-tight text-[11px]">File Excel tidak ditemukan!</p>
                            <p class="text-[10px] opacity-90">Pilih file Excel (.xlsx) terlebih dahulu.</p>
                        </div>
                    </div>
            `;
                    formatErrorContainer.classList.remove('hidden');
                    formatErrorContainer.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                }
                return; // BERHENTI DI SINI jika file tidak ada
            }

            // 5. PROSES PEMBACAAN FILE (Hanya jalan jika file ada)
            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, {
                        type: 'array'
                    });

                    const firstSheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[firstSheetName];
                    const jsonData = XLSX.utils.sheet_to_json(worksheet);

                    // Cek jika data kosong
                    if (jsonData.length === 0) {
                        if (formatErrorContainer) {
                            formatErrorContainer.innerHTML = `<div class="p-4 bg-amber-50 text-amber-600 rounded-xl border border-amber-100 text-[11px] font-bold uppercase">File Excel kosong atau tidak memiliki data.</div>`;
                            formatErrorContainer.classList.remove('hidden');
                        }
                        return;
                    }

                    // Panggil fungsi render preview Anda
                    renderPreview(jsonData);

                } catch (error) {
                    console.error("Error membaca Excel:", error);
                    alert('Gagal membaca file. Pastikan file adalah format .xlsx yang benar.');
                }
            };

            reader.readAsArrayBuffer(fileInput.files[0]);
        };

        async function renderPreview(data) {
            const tbody = document.getElementById('preview-body');
            const step1 = document.getElementById('upload-step-1');
            const step2 = document.getElementById('upload-step-2');
            const btnSave = document.getElementById('btn-save-import');
            const summaryText = document.getElementById('summary-text');

            // Ambil mode import saat ini
            const importModeInput = document.querySelector('input[name="import_mode"]:checked');
            const importMode = importModeInput ? importModeInput.value : 'append';

            // Elemen penampung pesan error format (Gunakan ID ini di HTML nanti)
            const formatErrorContainer = document.getElementById('format-error-msg');
            if (formatErrorContainer) formatErrorContainer.classList.add('hidden');

            const roleKey = 'HAK AKSES SISTEM (Administrator/Author/Editor/Subscriber/Guest)';

            // 1. Validasi Struktur Kolom (Wajib sesuai template)
            const requiredColumns = [
                'EMAIL PENGGUNA',
                'NOMOR WHATSAPP',
                roleKey
            ];

            if (data.length > 0) {
                const firstRow = data[0];
                const missing = requiredColumns.filter(col => !(col in firstRow));

                if (missing.length > 0) {
                    // Tampilkan pesan error jika kolom tidak cocok
                    if (formatErrorContainer) {
                        formatErrorContainer.innerHTML = `<p class="font-bold text-[11px]">Kolom tidak cocok: ${missing.join(', ')}</p>`;
                        formatErrorContainer.classList.remove('hidden');
                    }
                    return;
                }
            }

            // Role valid sesuai database Anda
            const validRoles = ['administrator', 'author', 'editor', 'subscriber', 'guest'];

            tbody.innerHTML = '<tr><td colspan="4" class="p-8 text-center"><span class="animate-pulse text-slate-400 italic text-[11px]">Memvalidasi baris data...</span></td></tr>';

            step1.classList.add('hidden');
            step2.classList.remove('hidden');

            let html = '';
            let globalHasError = false;
            let countValid = 0;
            let countError = 0;

            const seenEmails = new Set();
            const seenPhones = new Set();

            for (const item of data) {
                const email = (item['EMAIL PENGGUNA'] || '').toString().trim();
                const phone = (item['NOMOR WHATSAPP'] || '').toString().trim();
                const rawRole = (item[roleKey] || '').toString().toLowerCase().trim();
                let errors = [];

                if (!email) errors.push('Email kosong');
                else if (!email.includes('@')) errors.push('Format email salah');
                if (!phone) errors.push('WhatsApp kosong');

                if (!rawRole) errors.push('Hak akses belum diisi');
                else if (!validRoles.includes(rawRole)) errors.push(`Role "${rawRole}" tidak valid`);

                // Validasi Internal (Wajib di semua mode)
                if (email && seenEmails.has(email)) errors.push('Email ganda di file ini');
                if (phone && seenPhones.has(phone)) errors.push('WhatsApp ganda di file ini');

                if (email) seenEmails.add(email);
                if (phone) seenPhones.add(phone);

                // Validasi Eksternal (Hanya jika mode "Tambah Data")
                if (errors.length === 0 && importMode === 'append') {
                    try {
                        const check = await fetch(`/admin/users/check-availability?email=${email}&phone=${phone}`).then(r => r.json());
                        if (check.email_duplicate) errors.push('Email sudah ada di sistem');
                        if (check.phone_duplicate) errors.push('WhatsApp sudah ada di sistem');
                    } catch (e) {
                        errors.push('Koneksi server gagal');
                    }
                }

                const isRowError = errors.length > 0;
                if (isRowError) {
                    globalHasError = true;
                    countError++;
                } else {
                    countValid++;
                }

                html += `
            <tr class="${isRowError ? 'bg-rose-50/50' : 'hover:bg-slate-50'} transition-all text-[12px]">
                <td class="p-3 border-b border-slate-100 text-slate-600 font-medium">${email || '-'}</td>
                <td class="p-3 border-b border-slate-100 text-slate-600 font-medium">${phone || '-'}</td>
                <td class="p-3 border-b border-slate-100 text-center font-bold text-slate-500 uppercase">${rawRole || '-'}</td>
                <td class="p-3 border-b border-slate-100">
                    ${isRowError 
                        ? `<div class="flex flex-col gap-0.5">${errors.map(msg => `<div class="flex items-center gap-1.5 text-rose-600 font-bold uppercase text-[9px]"><span>•</span> ${msg}</div>`).join('')}</div>` 
                        : '<div class="flex items-center gap-1.5 text-emerald-600 font-bold uppercase text-[10px]">✓ Siap Import</div>'
                    }
                </td>
            </tr>
        `;
            }

            tbody.innerHTML = html;
            document.getElementById('json_data').value = JSON.stringify(data);

            summaryText.innerHTML = `
        <div class="flex gap-3 items-center font-bold text-[10px] uppercase tracking-wider">
            <span class="bg-slate-100 text-slate-600 px-3 py-1.5 rounded-lg border border-slate-200">Total: ${data.length}</span>
            <span class="bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-lg border border-emerald-200">Valid: ${countValid}</span>
            <span class="bg-rose-100 text-rose-700 px-3 py-1.5 rounded-lg border border-rose-200">Bermasalah: ${countError}</span>
            <span class="ml-auto text-indigo-600">Mode: ${importMode === 'replace' ? 'Buat Ulang' : 'Tambah Data'}</span>
        </div>
    `;

            btnSave.disabled = (globalHasError || data.length === 0);
            btnSave.classList.toggle('opacity-50', btnSave.disabled);
            btnSave.classList.toggle('cursor-not-allowed', btnSave.disabled);
        }

        // Tambahkan ini di bagian DOMContentLoaded atau bawah script Anda
        document.querySelectorAll('input[name="import_mode"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Jika sudah ada file yang dipilih, jalankan ulang pratinjau
                const fileInput = document.getElementById('excel_file');
                if (fileInput.files.length > 0) {
                    previewExcel();
                }
            });
        });

        // Fungsi saat tombol "Simpan Ke Database" di klik
        document.addEventListener('DOMContentLoaded', function() {
            const finalForm = document.getElementById('final-upload-form');

            if (finalForm) {
                finalForm.addEventListener('submit', function(e) {
                    e.preventDefault(); // Stop form agar tidak langsung kirim

                    // Ambil mode yang dipilih (append/replace)
                    const modeInput = document.querySelector('input[name="import_mode"]:checked');
                    const importMode = modeInput ? modeInput.value : 'append';

                    if (importMode === 'replace') {
                        // Jika pilih buat ulang, tampilkan modal konfirmasi merah
                        document.getElementById('confirmReplaceModal').classList.remove('hidden');
                    } else {
                        // Jika tambah data, langsung eksekusi submit
                        this.submit();
                    }
                });
            }
        });

        // Fungsi untuk menutup modal konfirmasi
        window.closeConfirmModal = function() {
            document.getElementById('confirmReplaceModal').classList.add('hidden');
        }

        // Fungsi eksekusi final dari dalam modal konfirmasi
        window.finalSubmitImport = function() {
            const form = document.getElementById('final-upload-form');
            if (form) {
                // Berikan feedback visual pada tombol
                const btn = document.getElementById('btn-save-import');
                btn.disabled = true;
                btn.innerText = 'MEMPROSES PEMBERSIHAN DATA...';

                form.submit();
            }
        }


        // Fungsi Modal Tambah
        // Objek untuk melacak status validasi
        let validationStatus = {
            email: true,
            phone: true
        };

        window.openAddUserModal = function() {
            const modal = document.getElementById('addUserModal');
            const form = document.getElementById('formAddUser');
            form.reset();

            // Reset state
            validationStatus.email = false; // Set false agar harus divalidasi dulu
            validationStatus.phone = false;

            document.getElementById('error_email').classList.add('hidden');
            document.getElementById('error_phone').classList.add('hidden');
            // document.getElementById('copy_area').classList.add('hidden');

            validateSubmitButton(); // Kunci tombol saat awal buka
            modal.classList.remove('hidden');
        }

        // Fungsi untuk mengontrol tombol submit secara terpusat
        function validateSubmitButton() {
            const btnSubmit = document.getElementById('btn_submit_add');
            const emailVal = document.getElementById('add_email').value;
            const phoneVal = document.getElementById('add_phone').value;

            // Tombol hanya aktif jika:
            // 1. Email & Phone tidak kosong
            // 2. Status validasi dari server menunjukkan OK (true)
            if (emailVal !== '' && phoneVal !== '' && validationStatus.email && validationStatus.phone) {
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                btnSubmit.disabled = true;
                btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }

        let typingTimer;
        window.checkUniqueness = function(type, value) {
            clearTimeout(typingTimer);
            const errorEl = document.getElementById('error_' + type);
            const inputEl = document.getElementById('add_' + type);

            // Set status ke false setiap kali ada perubahan ketikan
            validationStatus[type] = false;
            validateSubmitButton();

            if (value.length < 3) return;

            typingTimer = setTimeout(() => {
                fetch(`/admin/users/check-availability?type=${type}&value=${value}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.exists) {
                            errorEl.innerText = `${type === 'email' ? 'Email' : 'Nomor HP'} ini sudah terdaftar!`;
                            errorEl.classList.remove('hidden');
                            inputEl.classList.add('border-rose-500', 'ring-rose-500');
                            validationStatus[type] = false;
                        } else {
                            errorEl.classList.add('hidden');
                            inputEl.classList.remove('border-rose-500', 'ring-rose-500');
                            validationStatus[type] = true;
                        }
                        validateSubmitButton(); // Cek ulang kondisi tombol
                    });
            }, 500);
        }

        // KUNCI TERAKHIR: Cek saat form akan dikirim (Submit Event)
        document.getElementById('formAddUser').addEventListener('submit', function(e) {
            if (!validationStatus.email || !validationStatus.phone) {
                e.preventDefault(); // Batalkan pengiriman jika ada yang tidak valid
                alert('Mohon pastikan email dan nomor HP unik dan tersedia!');
                return false;
            }
        });

        // --- Fungsi Password Tetap Sama ---
        // window.generateStrongPassword = function() {
        //     const uppercase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        //     const lowercase = "abcdefghijklmnopqrstuvwxyz";
        //     const numbers = "0123456789";
        //     const symbols = "!@#$%^&*()_+~}{[]";
        //     const allChars = uppercase + lowercase + numbers + symbols;
        //     let password = "";
        //     password += uppercase[Math.floor(Math.random() * uppercase.length)];
        //     password += lowercase[Math.floor(Math.random() * lowercase.length)];
        //     password += numbers[Math.floor(Math.random() * numbers.length)];
        //     password += symbols[Math.floor(Math.random() * symbols.length)];
        //     for (let i = 0; i < 8; i++) {
        //         password += allChars[Math.floor(Math.random() * allChars.length)];
        //     }
        //     password = password.split('').sort(() => 0.5 - Math.random()).join('');
        //     document.getElementById('add_password').value = password;
        //     document.getElementById('add_password_conf').value = password;
        //     document.getElementById('pass_display').value = password;
        //     document.getElementById('copy_area').classList.remove('hidden');
        // }

        // window.copyToClipboard = function() {
        //     const passVal = document.getElementById('pass_display');
        //     passVal.select();
        //     document.execCommand("copy");
        //     const btn = document.getElementById('btn_copy');
        //     btn.innerText = "Copied!";
        //     setTimeout(() => {
        //         btn.innerText = "Copy";
        //     }, 2000);
        // }

        // window.togglePass = function(inputId, iconId) {
        //     const input = document.getElementById(inputId);
        //     input.type = (input.type === "password") ? "text" : "password";
        // }

        window.closeAddUserModal = function() {
            document.getElementById('addUserModal').classList.add('hidden');
        }

        window.openMasterEditModal = function(user, person, isApprove) {
            const form = document.getElementById('masterForm');
            if (!form) return;

            // Helper function to safely set values without crashing on missing IDs
            const setVal = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.value = val || '';
            };

            const methodField = document.getElementById('method_field');
            form.action = isApprove ? `/admin/users/${user.id_user}/approve` : `/admin/users/${user.id_user}/update`;
            if (methodField) methodField.innerHTML = isApprove ? '' : '<input type="hidden" name="_method" value="PATCH">';

            // 1. Identitas Utama
            setVal('f_email', user.email);
            setVal('f_phone', user.phone);
            setVal('f_name', person?.name);
            setVal('f_nik', person?.nik);
            setVal('f_no_kk', person?.no_kk);
            setVal('f_bpjs_kes', person?.no_bpjs_kes);
            setVal('f_bpjs_tk', person?.no_bpjs_tk);

            // 2. Kerja & Penempatan
            setVal('f_wa', person?.id_work_assignment);
            setVal('f_batch', person?.batch);
            setVal('f_emp', person?.employment_status);
            setVal('f_role', user.id_ref_role);
            setVal('f_pos', person?.id_ref_position);

            // 3. Alamat KTP
            setVal('f_ktp_prov', person?.province_ktp);
            setVal('f_ktp_reg', person?.regency_ktp);
            setVal('f_ktp_dist', person?.district_ktp);
            setVal('f_ktp_vill', person?.village_ktp);
            setVal('f_ktp_address', person?.address_ktp);

            // 4. Alamat Domisili
            setVal('f_dom_prov', person?.province_domicile);
            setVal('f_dom_reg', person?.regency_domicile);
            setVal('f_dom_dist', person?.district_domicile);
            setVal('f_dom_vill', person?.village_domicile);
            setVal('f_dom_address', person?.address_domicile);

            // 5. GPS & Social Media
            setVal('f_dom_lat', person?.latitude_gps_domicile);
            setVal('f_dom_lng', person?.longitude_gps_domicile);
            setVal('f_fb', person?.social_media?.facebook_url);
            setVal('f_ig', person?.social_media?.instagram_url);
            setVal('f_tt', person?.social_media?.tiktok_url);

            // 6. Info Pribadi & Payroll
            setVal('f_last_edu', person?.last_education);
            setVal('f_title_edu', person?.title_education);
            setVal('f_major', person?.major_education);
            setVal('f_place', person?.place_birthday);
            setVal('f_date', person?.date_birthday);
            setVal('f_age', person?.age);
            setVal('f_gender', person?.gender);
            setVal('f_religion', person?.religion);
            setVal('f_marital', person?.marital_status);
            setVal('f_cloth', person?.clothing_size);
            setVal('f_shoe', person?.shoe_size);
            setVal('f_nip', person?.nip);
            setVal('f_npwp', person?.npwp);
            setVal('f_bank_name', person?.payroll_bank_name);
            setVal('f_bank_acc', person?.payroll_bank_account_number);
            setVal('f_bank_owner', person?.payroll_bank_account_name);

            // Image Logic
            const previewImg = document.getElementById('cropped-preview');
            const placeholderDiv = document.getElementById('initial-placeholder');
            if (person?.photo && previewImg) {
                previewImg.src = `/storage/${person.photo}`;
                previewImg.classList.remove('hidden');
                if (placeholderDiv) placeholderDiv.classList.add('hidden');
            } else if (placeholderDiv) {
                if (previewImg) previewImg.classList.add('hidden');
                placeholderDiv.innerText = (person?.name || user.email || '?').substring(0, 1).toUpperCase();
                placeholderDiv.classList.remove('hidden');
            }

            document.getElementById('masterModal').classList.remove('hidden');

            // Setup Map
            setTimeout(() => {
                const lat = parseFloat(person?.latitude_gps_domicile);
                const lng = parseFloat(person?.longitude_gps_domicile);
                setupMap(lat, lng);
                if (mapObj) mapObj.invalidateSize();
            }, 400);

            // Reset checkbox sinkronisasi alamat saat modal dibuka
            const syncBox = document.getElementById('sync_address_admin');
            if (syncBox) {
                syncBox.checked = false;
                // Panggil fungsi performSync untuk melepas status readOnly pada field domisili
                for (const domId of Object.values(addrMap)) {
                    const domEl = document.getElementById(domId);
                    if (domEl) {
                        domEl.classList.remove('input-disabled');
                        domEl.readOnly = false;
                    }
                }
            }
        };

        if (photoInputEl) {
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
                            viewMode: 1, // Memastikan kotak crop tidak bisa keluar dari area gambar
                            dragMode: 'move',
                            autoCropArea: 1, // NILAI 1: Membuat kotak crop otomatis menutupi area gambar semaksimal mungkin
                            responsive: true,
                            restore: false,
                            ready() {
                                // Memaksa cropper untuk menghitung ulang ukuran jika modal baru terbuka
                                this.cropper.crop();
                            }
                        });
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
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
            } else {
                form.action = `/admin/users/${userId}`;
                title.innerText = "Hapus Pengguna?";
                info.innerText = `Akun ${email} akan dipindahkan ke tempat sampah.`;
            }
            modal.classList.remove('hidden');
        }

        window.confirmDeleteAll = function() {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            const title = document.getElementById('delete_modal_title');
            const info = document.getElementById('delete_modal_info');
            form.action = `{{ route('admin.users.force-delete-all') }}`;
            title.innerText = "Kosongkan Sampah?";
            info.innerText = "Semua data di tempat sampah akan dihapus permanen.";
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

        document.getElementById('apply-crop-btn').onclick = function() {
            if (!cropperObj) return;
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
                if (photoInputEl) photoInputEl.files = dt.files;

                const previewImg = document.getElementById('cropped-preview');
                const placeholder = document.getElementById('initial-placeholder');
                previewImg.src = URL.createObjectURL(blob);
                previewImg.classList.remove('hidden');
                placeholder.classList.add('hidden');

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