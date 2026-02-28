{{-- MODAL UBAH SK --}}
<div x-cloak x-show="showEditModal" class="fixed inset-0 z-[99] flex items-center justify-center p-4 sm:p-6"
    @init-edit-sk.window="
        setTimeout(() => {
            const formObj = document.getElementById('form-edit-sk');
            if (formObj && selectedDecree.id_assignment_decree) {
                formObj.action = '{{ route('admin.manage-assignment-decree.index') }}/' + selectedDecree.id_assignment_decree + '/update';
            }
        }, 50);
    ">
    
    <div x-show="showEditModal" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" 
         @click="showEditModal = false"></div>

    <div x-show="showEditModal" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
         class="relative bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-3xl max-h-[90vh] flex flex-col overflow-hidden font-sans text-sm transform transition-all">
        
        {{-- HEADER MODAL --}}
        <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center text-slate-800 shrink-0">
            <div class="flex items-center gap-3">
                <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </span>
                <h3 class="text-[14px] font-bold uppercase tracking-widest">Ubah Informasi SK</h3>
            </div>
            <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 text-2xl cursor-pointer">&times;</button>
        </div>

        {{-- BODY MODAL --}}
        <div class="p-8 overflow-y-auto custom-scrollbar flex-1 bg-white">
            <form method="POST" id="form-edit-sk" enctype="multipart/form-data"
                  x-data="{ isSubmitted: false, file_sk_edit: null }"
                  @submit.prevent="
                      isSubmitted = true;
                      if(selectedDecree.no_sk && selectedDecree.date_sk && selectedDecree.no_ba_verval && selectedDecree.date_ba_verval && selectedDecree.sppg_units && selectedDecree.sppg_units.length > 0) {
                          $el.submit();
                      }
                  " novalidate>
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Nomor SK <span class="text-rose-500">*</span></label>
                        <input type="text" name="no_sk" x-model="selectedDecree.no_sk" required class="w-full mt-2 px-4 py-2.5 bg-gray-50 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" :class="(isSubmitted && !selectedDecree.no_sk) ? 'border-rose-500 ring-rose-500 border ring-1' : 'border-none @error('no_sk') border-rose-500 ring-rose-500 border ring-1 @enderror'" placeholder="Masukkan Nomor SK">
                        <template x-if="isSubmitted && !selectedDecree.no_sk">
                            <p class="text-[11px] text-rose-500 mt-1.5 font-bold italic">* Wajib diisi</p>
                        </template>
                        @error('no_sk')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Ganti File SK (PDF)</label>
                        <input type="file" name="file_sk" accept=".pdf" @change="file_sk_edit = $event.target.files[0]" class="w-full mt-2 px-4 py-[7px] bg-gray-50 rounded-lg text-sm text-slate-600 focus:ring-2 focus:ring-indigo-500 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-[11px] file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" :class="'border-none @error('file_sk') border-rose-500 ring-rose-500 border ring-1 @enderror'">
                        <p class="text-[10px] text-slate-400 mt-1">* Kosongkan jika tidak ingin mengubah file saat ini.</p>
                        <template x-if="selectedDecree.file_sk_url">
                            <div class="mt-2 text-xs">
                                <span class="text-slate-500">File saat ini: </span>
                                <a :href="selectedDecree.file_sk_url" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-medium underline flex items-center inline-flex gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                    Lihat PDF
                                </a>
                            </div>
                        </template>
                        @error('file_sk')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Tanggal SK <span class="text-rose-500">*</span></label>
                        <input type="date" name="date_sk" x-model="selectedDecree.date_sk" required class="w-full mt-2 px-4 py-2.5 bg-gray-50 rounded-lg text-sm text-slate-600 focus:ring-2 focus:ring-indigo-500" :class="(isSubmitted && !selectedDecree.date_sk) ? 'border-rose-500 ring-rose-500 border ring-1' : 'border-none @error('date_sk') border-rose-500 ring-rose-500 border ring-1 @enderror'">
                        <template x-if="isSubmitted && !selectedDecree.date_sk">
                            <p class="text-[11px] text-rose-500 mt-1.5 font-bold italic">* Wajib diisi</p>
                        </template>
                        @error('date_sk')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Nomor BA Verval <span class="text-rose-500">*</span></label>
                        <input type="text" name="no_ba_verval" x-model="selectedDecree.no_ba_verval" required class="w-full mt-2 px-4 py-2.5 bg-gray-50 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" :class="(isSubmitted && !selectedDecree.no_ba_verval) ? 'border-rose-500 ring-rose-500 border ring-1' : 'border-none @error('no_ba_verval') border-rose-500 ring-rose-500 border ring-1 @enderror'" placeholder="Masukkan Nomor BA Verval">
                        <template x-if="isSubmitted && !selectedDecree.no_ba_verval">
                            <p class="text-[11px] text-rose-500 mt-1.5 font-bold italic">* Wajib diisi</p>
                        </template>
                        @error('no_ba_verval')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Tanggal BA Verval <span class="text-rose-500">*</span></label>
                        <input type="date" name="date_ba_verval" x-model="selectedDecree.date_ba_verval" required class="w-full mt-2 px-4 py-2.5 bg-gray-50 rounded-lg text-sm text-slate-600 focus:ring-2 focus:ring-indigo-500" :class="(isSubmitted && !selectedDecree.date_ba_verval) ? 'border-rose-500 ring-rose-500 border ring-1' : 'border-none @error('date_ba_verval') border-rose-500 ring-rose-500 border ring-1 @enderror'">
                        <template x-if="isSubmitted && !selectedDecree.date_ba_verval">
                            <p class="text-[11px] text-rose-500 mt-1.5 font-bold italic">* Wajib diisi</p>
                        </template>
                        @error('date_ba_verval')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4" x-data="{ 
                    searchSppgEdit: '', 
                    sppgData: {{ $sppgUnits->map(fn($u) => strtolower($u->name . ' ' . $u->id_sppg_unit))->values()->toJson() }},
                    assignedMap: {{ json_encode($assignedSppgsMap) }}
                }">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Tetapkan SPPG Terkait <span class="text-rose-500">*</span></label>
                        <span class="text-[10px] text-indigo-600 font-bold bg-indigo-50 px-2 py-0.5 rounded-md" x-text="selectedDecree.sppg_units ? selectedDecree.sppg_units.length + ' Dipilih' : '0 Dipilih'"></span>
                    </div>
                    
                    {{-- Search Box SPPG --}}
                    <div class="relative mb-3">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" x-model="searchSppgEdit" class="w-full px-4 py-2.5 pl-10 bg-gray-50 border-none rounded-lg text-sm text-slate-600 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Cari SPPG...">
                    </div>

                    {{-- List View SPPG --}}
                    <div class="bg-gray-50 border-none rounded-xl max-h-48 overflow-y-auto dropdown-sppg-scroll p-2 shadow-inner" :class="(isSubmitted && (!selectedDecree.sppg_units || selectedDecree.sppg_units.length === 0)) ? 'border-rose-500 ring-1 ring-rose-500' : 'border-slate-200'">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-1">
                            @foreach($sppgUnits as $unit)
                            <label x-show="searchSppgEdit === '' || '{{ strtolower($unit->name) }}'.includes(searchSppgEdit.toLowerCase()) || '{{ strtolower($unit->id_sppg_unit) }}'.includes(searchSppgEdit.toLowerCase())"
                                   class="flex items-start gap-3 p-2 rounded-lg cursor-pointer transition-colors border"
                                   :class="{
                                       'bg-slate-100/50 border-slate-200 cursor-not-allowed opacity-60': assignedMap['{{ $unit->id_sppg_unit }}'] && assignedMap['{{ $unit->id_sppg_unit }}'] !== selectedDecree.id_assignment_decree, 
                                       'bg-emerald-50/50 border-emerald-100 hover:bg-emerald-50': (!assignedMap['{{ $unit->id_sppg_unit }}'] || assignedMap['{{ $unit->id_sppg_unit }}'] === selectedDecree.id_assignment_decree) && selectedDecree.sppg_units && selectedDecree.sppg_units.includes('{{ $unit->id_sppg_unit }}'),
                                       'hover:bg-slate-50 border-transparent hover:border-slate-100': (!assignedMap['{{ $unit->id_sppg_unit }}'] || assignedMap['{{ $unit->id_sppg_unit }}'] === selectedDecree.id_assignment_decree) && (!selectedDecree.sppg_units || !selectedDecree.sppg_units.includes('{{ $unit->id_sppg_unit }}'))
                                   }">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="sppg_units[]" value="{{ $unit->id_sppg_unit }}" 
                                           x-model="selectedDecree.sppg_units"
                                           :disabled="assignedMap['{{ $unit->id_sppg_unit }}'] !== undefined && assignedMap['{{ $unit->id_sppg_unit }}'] !== selectedDecree.id_assignment_decree"
                                           class="w-4 h-4 rounded"
                                           :class="(assignedMap['{{ $unit->id_sppg_unit }}'] && assignedMap['{{ $unit->id_sppg_unit }}'] !== selectedDecree.id_assignment_decree) ? 'text-slate-400 border-slate-200 bg-slate-100 cursor-not-allowed' : 'text-indigo-600 border-slate-300 focus:ring-indigo-500 focus:ring-2'">
                                </div>
                                <div class="flex flex-col flex-1">
                                    <div class="flex justify-between items-start gap-2">
                                        <span class="text-[13px] font-bold capitalize leading-none pt-0.5" 
                                              :class="(assignedMap['{{ $unit->id_sppg_unit }}'] && assignedMap['{{ $unit->id_sppg_unit }}'] !== selectedDecree.id_assignment_decree) ? 'text-slate-500' : 'text-slate-700'">{{ $unit->name }}</span>
                                        <template x-if="assignedMap['{{ $unit->id_sppg_unit }}'] && assignedMap['{{ $unit->id_sppg_unit }}'] !== selectedDecree.id_assignment_decree">
                                            <span class="text-[9px] bg-slate-200 text-slate-500 font-bold px-1.5 py-0.5 rounded leading-none shrink-0 border border-slate-300">Telah Tertaut</span>
                                        </template>
                                    </div>
                                    <span class="text-[10px] mt-0.5 uppercase tracking-wider"
                                          :class="(assignedMap['{{ $unit->id_sppg_unit }}'] && assignedMap['{{ $unit->id_sppg_unit }}'] !== selectedDecree.id_assignment_decree) ? 'text-slate-400/80' : 'text-slate-400'">{{ $unit->id_sppg_unit }}</span>
                                </div>
                            </label>
                            @endforeach
                            @if($sppgUnits->isEmpty())
                            <div class="col-span-full py-4 text-center text-xs text-slate-400 italic">
                                Belum ada data SPPG
                            </div>
                            @endif

                            {{-- State Empty Search --}}
                            <div x-cloak x-show="searchSppgEdit !== '' && !sppgData.some(str => str.includes(searchSppgEdit.toLowerCase()))" class="col-span-full py-8 text-center text-slate-500">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">
                                    SPPG Tidak Ditemukan
                                </p>
                            </div>
                        </div>
                    </div>
                    <template x-if="isSubmitted && (!selectedDecree.sppg_units || selectedDecree.sppg_units.length === 0)">
                        <p class="text-[11px] text-rose-500 mt-1.5 font-bold italic">* Minimal 1 SPPG wajib dipilih</p>
                    </template>
                    @error('sppg_units')
                        <p class="text-xs text-rose-500 mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </form>
        </div>

        {{-- FOOTER MODAL --}}
        <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-4 shrink-0">
            <button type="button" @click="showEditModal = false" class="flex-1 py-4 text-[11px] font-bold uppercase text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition-all">
                Batal
            </button>
            <button type="submit" form="form-edit-sk" class="flex-1 py-4 text-[11px] font-bold uppercase text-white bg-slate-800 rounded-xl shadow-lg hover:bg-slate-900 transition-all active:scale-95">
                Simpan Perubahan
            </button>
        </div>
    </div>
</div>
