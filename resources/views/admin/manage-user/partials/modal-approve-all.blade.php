    {{-- MODAL APPROVE ALL --}}
    <div id="approveAllModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeApproveAllModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 border border-slate-100">
            <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-10 h-10">
                    <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0 1 12 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 0 1 3.498 1.307 4.491 4.491 0 0 1 1.307 3.497A4.49 4.49 0 0 1 21.75 12a4.49 4.49 0 0 1-1.549 3.397 4.491 4.491 0 0 1-1.307 3.497 4.491 4.491 0 0 1-3.497 1.307A4.49 4.49 0 0 1 12 21.75a4.49 4.49 0 0 1-3.397-1.549 4.49 4.49 0 0 1-3.498-1.307 4.491 4.491 0 0 1-1.307-3.497A4.49 4.49 0 0 1 2.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 0 1 1.307-3.497 4.49 4.49 0 0 1 3.497-1.307Zm7.007 6.387a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 uppercase tracking-widest mb-2 text-center">Setujui Semua Pendaftar?</h3>
            <p class="text-sm text-center text-slate-500 mb-6 leading-relaxed">Tentukan penugasan, akses, dan jabatan untuk seluruh antrian pendaftar.</p>

            <form action="{{ route('admin.manage-user.approve-all') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1 block">Penugasan</label>
                    <select name="id_work_assignment" required class="w-full border-slate-200 rounded-xl text-sm py-3 px-4 focus:ring-emerald-500 outline-none font-sans shadow-sm">
                        <option value="" disabled selected>Pilih Penugasan</option>
                        <option value="none">Belum Penugasan</option>
                        @foreach($workAssignments as $wa)
                        <option value="{{$wa->id_work_assignment}}">{{$wa->sppgUnit?->name ?? 'SPPG Tidak Ditemukan'}} (SK: {{$wa->decree?->no_sk ?? 'SK Tidak Ditemukan'}})</option>
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
    </div>

<script>
        window.openApproveAllModal = function() {
            document.getElementById('approveAllModal').classList.remove('hidden');
        }

        window.closeApproveAllModal = function() {
            document.getElementById('approveAllModal').classList.add('hidden');
        }
</script>
