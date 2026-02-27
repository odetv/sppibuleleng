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
