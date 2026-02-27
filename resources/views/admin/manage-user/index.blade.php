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

    <div class="text-slate-800 text-[14px] py-8 w-full px-4 sm:px-6 lg:px-8 relative">
        <div class="max-w-full mx-auto space-y-6">

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
                            <span class="w-2 h-2 rounded-full bg-indigo-500 mr-2 animate-pulse"></span>
                            {{ $allUsersDisplay->count() }} Pengguna Terdaftar
                        </span>
                    </div>
                </div>
            </div>

            {{-- 2. STATS SECTION --}}
            {{-- 2. KARTU STATISTIK --}}
            @include('admin.manage-user.partials.statistics-cards')
            {{-- CONTAINER TABEL AJAX (Memuat ke-3 tabel) --}}
            <div id="user-table-container" class="space-y-6">

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
                            class="shrink-0 text-[11px] font-bold uppercase tracking-wider text-emerald-600 bg-white border border-emerald-200 px-4 py-2 rounded-lg hover:bg-emerald-600 hover:text-white transition-all cursor-pointer shadow-sm flex items-center gap-2">
                            {{-- Icon SVG Checklist --}}
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
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
                                    <form action="{{ route('admin.manage-user.approve', $user->id_user) }}" method="POST" class="flex justify-end items-center gap-2">
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
                                                <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0 1 12 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 0 1 3.498 1.307 4.491 4.491 0 0 1 1.307 3.497A4.49 4.49 0 0 1 21.75 12a4.49 4.49 0 0 1-1.549 3.397 4.491 4.491 0 0 1-1.307 3.497 4.491 4.491 0 0 1-3.497 1.307A4.49 4.49 0 0 1 12 21.75a4.49 4.49 0 0 1-3.397-1.549 4.49 4.49 0 0 1-3.498-1.307 4.491 4.491 0 0 1-1.307-3.497A4.49 4.49 0 0 1 2.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 0 1 1.307-3.497 4.49 4.49 0 0 1 3.497-1.307Zm7.007 6.387a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
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
                @if($pendingUsers->hasPages() || request('per_page_pending') > 5)
                <div class="px-6 py-4 bg-white border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 text-xs
    [&_nav]:flex [&_nav]:justify-between [&_nav]:items-center [&_nav]:w-full md:[&_nav]:w-auto
    [&_a]:bg-white [&_a]:text-slate-600 [&_a]:border-slate-200 [&_a]:rounded-lg [&_a]:hover:bg-slate-50
    [&_span]:bg-white [&_span]:text-slate-600 [&_span]:border-slate-200 [&_span]:rounded-lg
    [&_.bg-gray-800]:bg-emerald-600 [&_.bg-gray-800]:text-white [&_.bg-gray-800]:border-emerald-600
    [&_.dark\:bg-gray-800]:bg-white [&_.dark\:text-gray-400]:text-slate-600">
                    
                    {{-- DROPDOWN PER PAGE --}}
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-slate-600">Tampilkan</span>
                        <select id="user-pending-per-page" class="per-page-select-pending border-slate-200 rounded-lg text-sm py-1.5 pl-3 pr-8 focus:ring-emerald-500 text-slate-600 font-medium cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors">
                            <option value="5" {{ request('per_page_pending') == '5' ? 'selected' : '' }}>5</option>
                            <option value="15" {{ request('per_page_pending') == '15' ? 'selected' : '' }}>15</option>
                            <option value="50" {{ request('per_page_pending') == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page_pending') == '100' ? 'selected' : '' }}>100</option>
                        </select>
                        <span class="text-sm text-slate-600 hidden sm:inline">Baris</span>
                    </div>

                    {{-- LARAVEL PAGINATION --}}
                    <div class="w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
                        {{ $pendingUsers->appends(request()->query())->links() }}
                    </div>

                </div>
                @endif
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
                            <button type="button" onclick="openExportModal()" class="flex items-center justify-center p-2.5 md:px-4 text-[11px] font-bold uppercase tracking-wider text-emerald-600 bg-white border border-emerald-200 rounded-lg hover:bg-emerald-600 hover:text-white transition-all cursor-pointer shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                    <path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                </svg>
                                <span class="hidden md:inline ml-2">Export</span>
                            </button>

                            {{-- Tombol Import --}}
                            <button type="button" onclick="openImportModal()" class="flex items-center justify-center p-2.5 md:px-4 text-[11px] font-bold uppercase tracking-wider text-amber-600 bg-white border border-amber-200 rounded-lg hover:bg-amber-600 hover:text-white transition-all cursor-pointer shadow-sm">
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
                                <th class="px-6 py-4 text-center">Aksi</th>
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
                                    <div class="flex justify-center items-center gap-1">
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
                @if($allUsers->hasPages() || request('per_page_all') > 5)
                <div class="px-6 py-4 bg-white border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 text-xs
    [&_nav]:flex [&_nav]:justify-between [&_nav]:items-center [&_nav]:w-full md:[&_nav]:w-auto
    [&_a]:bg-white [&_a]:text-slate-600 [&_a]:border-slate-200 [&_a]:rounded-lg [&_a]:hover:bg-slate-50
    [&_span]:bg-white [&_span]:text-slate-600 [&_span]:border-slate-200 [&_span]:rounded-lg
    [&_.bg-gray-800]:bg-indigo-600 [&_.bg-gray-800]:text-white [&_.bg-gray-800]:border-indigo-600
    [&_.dark\:bg-gray-800]:bg-white [&_.dark\:text-gray-400]:text-slate-600">
                    
                    {{-- DROPDOWN PER PAGE --}}
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-slate-600">Tampilkan</span>
                        <select id="user-all-per-page" class="per-page-select-all border-slate-200 rounded-lg text-sm py-1.5 pl-3 pr-8 focus:ring-indigo-500 text-slate-600 font-medium cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors">
                            <option value="5" {{ request('per_page_all') == '5' ? 'selected' : '' }}>5</option>
                            <option value="15" {{ request('per_page_all') == '15' ? 'selected' : '' }}>15</option>
                            <option value="50" {{ request('per_page_all') == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page_all') == '100' ? 'selected' : '' }}>100</option>
                        </select>
                        <span class="text-sm text-slate-600 hidden sm:inline">Baris</span>
                    </div>

                    {{-- LARAVEL PAGINATION --}}
                    <div class="w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
                        {{ $allUsers->appends(request()->query())->links() }}
                    </div>

                </div>
                @endif
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
                                <th class="px-6 py-4 text-center">Aksi</th>
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
                                <td class="px-6 py-4 text-center flex justify-center items-center gap-2">
                                    <form action="{{ route('admin.manage-user.restore', $tu->id_user) }}" method="POST">
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
                @if($trashedUsers->hasPages() || request('per_page_trash') > 5)
                <div class="px-6 py-4 bg-rose-50/20 border-t border-rose-100 flex flex-col md:flex-row justify-between items-center gap-4 text-xs
    [&_nav]:flex [&_nav]:justify-between [&_nav]:items-center [&_nav]:w-full md:[&_nav]:w-auto
    [&_a]:bg-white [&_a]:text-slate-600 [&_a]:border-slate-200 [&_a]:rounded-lg [&_a]:hover:bg-rose-50
    [&_span]:bg-white [&_span]:text-slate-600 [&_span]:border-slate-200 [&_span]:rounded-lg
    [&_.bg-gray-800]:bg-rose-600 [&_.bg-gray-800]:text-white [&_.bg-gray-800]:border-rose-600
    [&_.dark\:bg-gray-800]:bg-white [&_.dark\:text-gray-400]:text-slate-600">
                    
                    {{-- DROPDOWN PER PAGE --}}
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-slate-600">Tampilkan</span>
                        <select id="user-trash-per-page" class="per-page-select-trash border-slate-200 rounded-lg text-sm py-1.5 pl-3 pr-8 focus:ring-rose-500 text-slate-600 font-medium cursor-pointer bg-white hover:bg-slate-50 transition-colors">
                            <option value="5" {{ request('per_page_trash') == '5' ? 'selected' : '' }}>5</option>
                            <option value="15" {{ request('per_page_trash') == '15' ? 'selected' : '' }}>15</option>
                            <option value="50" {{ request('per_page_trash') == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page_trash') == '100' ? 'selected' : '' }}>100</option>
                        </select>
                        <span class="text-sm text-slate-600 hidden sm:inline">Baris</span>
                    </div>

                    {{-- LARAVEL PAGINATION --}}
                    <div class="w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
                        {{ $trashedUsers->appends(request()->query())->links() }}
                    </div>

                </div>
                @endif
            </div>
            </div>
        </div>
    </div>

    {{-- MODALS --}}
    @include('admin.manage-user.partials.modal-export')
    @include('admin.manage-user.partials.modal-import')
    @include('admin.manage-user.partials.modal-confirm-replace')
    @include('admin.manage-user.partials.modal-add-user')
    @include('admin.manage-user.partials.modal-edit')
    @include('admin.manage-user.partials.modal-delete')
    @include('admin.manage-user.partials.modal-approve-all')
    @include('admin.manage-user.partials.modal-cropper')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        let searchTimer;

        // 0. Helper Penarik Data Modifier (Search Keyword + Per-Page Limit)
        function getCurrentUrlModifiersUser(tableType, inputEl = null) {
            let currentUrl = new URL(window.location.href);

            // 1. Ambil keyword search aktif 
            const activeSearch = document.getElementById('search-' + tableType);
            if (activeSearch) currentUrl.searchParams.set('search_' + tableType, activeSearch.value);

            // 2. Ambil limit dropdown per page
            const activePerPage = document.getElementById('user-' + tableType + '-per-page');
            if (activePerPage) currentUrl.searchParams.set('per_page_' + tableType, activePerPage.value);

            // 3. Batalkan nomor halaman (refresh ke halaman 1) saat pencarian atau ganti ukuran baris. 
            // Jika dipanggil oleh klik pagination link, inputEl itu dibiarkan nol (null).
            if (inputEl) currentUrl.searchParams.delete(tableType + '_page');

            return currentUrl.toString();
        }

        // 1. Fungsi Utama Refresh AJAX
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

                    container.innerHTML = newContent;
                    window.history.pushState({}, '', url);

                    if (focusId) {
                        requestAnimationFrame(() => {
                            const activeInput = document.getElementById(focusId);
                            if (activeInput) {
                                activeInput.focus();
                                const val = activeInput.value;
                                activeInput.value = '';
                                activeInput.value = val;
                            }
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // 2. Event Listener untuk Mengetik (Enter Key)
        document.addEventListener('keydown', function(e) {
            if (e.target.classList.contains('live-search-input')) {
                const inputEl = e.target;
                const type = inputEl.getAttribute('data-table');

                if (e.key === 'Enter') {
                    e.preventDefault(); 
                    clearTimeout(searchTimer); 
                    refreshTable(getCurrentUrlModifiersUser(type, inputEl), inputEl.id);
                }
            }
        });

        // 3. Event Listener untuk Timer Otomatis Mengetik
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('live-search-input')) {
                clearTimeout(searchTimer);
                const inputEl = e.target;
                const type = inputEl.getAttribute('data-table');

                searchTimer = setTimeout(() => {
                    refreshTable(getCurrentUrlModifiersUser(type, inputEl), inputEl.id);
                }, 700); 
            }
        });

        // 4. Listener untuk Mengganti Dropdown Limit Baris
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('per-page-select-pending')) refreshTable(getCurrentUrlModifiersUser('pending', e.target));
            if (e.target.classList.contains('per-page-select-all')) refreshTable(getCurrentUrlModifiersUser('all', e.target));
            if (e.target.classList.contains('per-page-select-trash')) refreshTable(getCurrentUrlModifiersUser('trash', e.target));
        });

        // 5. Listener Memencet Nomor Navigasi Halaman Pagination
        document.addEventListener('click', function(e) {
            let anchor = e.target.closest('#user-table-container nav a');
            if (anchor && anchor.getAttribute('href')) {
                let url = new URL(anchor.getAttribute('href'));
                
                // Cari tahu ia pagination dari mana (parameter yang ada kata "_page=")
                const urlParams = url.searchParams.keys();
                let type = null;
                for (let param of urlParams) {
                    if (param.endsWith('_page')) {
                        type = param.replace('_page', '');
                        break;
                    }
                }

                if (type) {
                    // Inject limit ukuran per data page pilihan sekarang ke var url parameter pagination
                    const activePerPage = document.getElementById('user-' + type + '-per-page');
                    if (activePerPage) url.searchParams.set('per_page_' + type, activePerPage.value);
                }

                if (url.toString().includes('page=') && !url.toString().startsWith('javascript')) {
                    e.preventDefault();
                    refreshTable(url.toString());
                }
            }
        });

    </script>
</x-app-layout>