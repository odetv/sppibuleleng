{{-- MODAL CREATE --}}
<div x-show="showCreateModal" 
     class="fixed inset-0 z-[100] flex items-center justify-center p-4"
     x-cloak>
    
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showCreateModal = false"></div>

    <div x-show="showCreateModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="relative bg-white rounded-xl shadow-xl w-full max-w-xl overflow-hidden flex flex-col max-h-[90vh] border border-slate-200 transform transition-all font-sans text-sm"
         x-data="{ 
            form: { id_sppg_unit: '', name_certification: '', certification_number: '', issued_by: '', issued_date: '', start_date: '', expiry_date: '', status: true },
            errors: {},
            init() {
                window.addEventListener('reset-create-modal', () => {
                    this.form = { id_sppg_unit: '', name_certification: '', certification_number: '', issued_by: '', issued_date: '', start_date: '', expiry_date: '', status: true };
                    this.errors = {};
                    if(document.getElementById('create_file_input')) document.getElementById('create_file_input').value = '';
                });
            },
            async submit() {
                this.errors = {};
                const formData = new FormData();
                for(let k in this.form) {
                    formData.append(k, k === 'status' ? (this.form[k] ? 1 : 0) : (this.form[k] || ''));
                }
                const fileInput = document.getElementById('create_file_input');
                if(fileInput.files[0]) formData.append('file_certification', fileInput.files[0]);

                try {
                    const res = await fetch('{{ route('admin.manage-certification.store') }}', {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    if(res.ok) {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: data.message }).then(() => window.location.reload());
                    } else {
                        if(res.status === 422) this.errors = data.errors;
                        else Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Terjadi kesalahan' });
                    }
                } catch(e) { Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menghubungi server' }); }
            }
         }">
        
        {{-- Header --}}
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/80 flex justify-between items-center text-slate-800">
            <div class="flex items-center gap-3">
                <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </span>
                <h3 class="text-[14px] font-bold uppercase tracking-widest">Tambah Sertifikasi</h3>
            </div>
            <button @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600 text-2xl cursor-pointer">&times;</button>
        </div>

        {{-- Body --}}
        <div class="p-8 overflow-y-auto custom-scrollbar space-y-6 bg-white flex-1 min-h-0">
            {{-- Form content remains same --}}
            <div class="grid grid-cols-1 gap-6">
                {{-- Unit SPPG --}}
                <div class="space-y-2">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Unit SPPG <span class="text-rose-500">*</span></label>
                    <select x-model="form.id_sppg_unit" @change="form.name_certification = ''" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 py-3 px-4 transition-all" :class="errors.id_sppg_unit ? 'border-rose-500 ring-rose-50 bg-rose-50' : ''">
                        <option value="">Pilih Unit</option>
                        @foreach($sppgUnits as $unit)
                            <option value="{{ $unit->id_sppg_unit }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                    <template x-if="errors.id_sppg_unit">
                        <p class="error-warning" x-text="'* Wajib diisi'"></p>
                    </template>
                </div>

                {{-- Jenis Sertifikasi --}}
                <div class="space-y-2">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Jenis Sertifikasi <span class="text-rose-500">*</span></label>
                    <select x-model="form.name_certification" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 py-3 px-4 transition-all">
                        <option value="">Pilih Jenis</option>
                        <option value="SLHS" :disabled="isCertTypeUsed(form.id_sppg_unit, 'SLHS')">SLHS (Sertifikat Laik Higiene Sanitasi)</option>
                        <option value="Halal" :disabled="isCertTypeUsed(form.id_sppg_unit, 'Halal')">Halal</option>
                        <option value="HACCP" :disabled="isCertTypeUsed(form.id_sppg_unit, 'HACCP')">HACCP</option>
                        <option value="Chef" :disabled="isCertTypeUsed(form.id_sppg_unit, 'Chef')">Chef</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nomor Sertifikasi --}}
                    <div class="space-y-2">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Nomor Sertifikasi <span class="text-rose-500">*</span></label>
                        <input type="text" x-model="form.certification_number" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 py-3 px-4 transition-all" placeholder="Cth: 1234/ABC/2024" :class="errors.certification_number ? 'border-rose-500 ring-rose-50 bg-rose-50' : ''">
                        <template x-if="errors.certification_number">
                            <p class="error-warning" x-text="'* Wajib diisi'"></p>
                        </template>
                    </div>
                    {{-- Diterbitkan Oleh --}}
                    <div class="space-y-2">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Diterbitkan Oleh <span class="text-rose-500">*</span></label>
                        <input type="text" x-model="form.issued_by" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 py-3 px-4 transition-all" placeholder="Cth: Kemenag, Dinkes" :class="errors.issued_by ? 'border-rose-500 ring-rose-50 bg-rose-50' : ''">
                        <template x-if="errors.issued_by">
                            <p class="error-warning" x-text="'* Wajib diisi'"></p>
                        </template>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Tgl Terbit --}}
                    <div class="space-y-2">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tgl Terbit <span class="text-rose-500">*</span></label>
                        <input type="date" x-model="form.issued_date" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 py-3 px-4 transition-all" :class="errors.issued_date ? 'border-rose-500 ring-rose-50 bg-rose-50' : ''">
                        <template x-if="errors.issued_date">
                            <p class="error-warning" x-text="'* Wajib diisi'"></p>
                        </template>
                    </div>
                    {{-- Tgl Mulai --}}
                    <div class="space-y-2">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tgl Mulai</label>
                        <input type="date" x-model="form.start_date" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 py-3 px-4 transition-all">
                    </div>
                    {{-- Tgl Berakhir --}}
                    <div class="space-y-2">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tgl Berakhir</label>
                        <input type="date" x-model="form.expiry_date" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 py-3 px-4 transition-all">
                    </div>
                </div>

                {{-- File Input --}}
                <div class="space-y-2">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Berita Acara / Sertifikat (PDF) <span class="text-rose-500">*</span></label>
                    <div class="relative flex items-center shadow-sm rounded-xl">
                        <input type="file" id="create_file_input" accept="application/pdf" 
                               class="w-full text-[11px] bg-slate-50 border border-slate-200 rounded-xl py-2 px-4 focus:ring-2 focus:ring-indigo-500 transition-all cursor-pointer
                                      file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[11px] file:font-bold file:uppercase file:tracking-wider
                                      file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 shadow-sm"
                               :class="errors.file_certification ? 'border-rose-500 ring-rose-50 bg-rose-50' : ''">
                    </div>
                    <p class="text-[10px] text-slate-400 font-medium italic">Format file: PDF (Maks. 2MB)</p>
                    <template x-if="errors.file_certification">
                        <p class="error-warning" x-text="'* Wajib diisi'"></p>
                    </template>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="p-6 border-t border-slate-100 bg-slate-50 flex flex-col sm:flex-row justify-end gap-3 px-8">
            <button @click="showCreateModal = false" class="px-8 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider hover:bg-slate-100/50 rounded-xl transition-all border border-slate-200 order-2 sm:order-1 font-sans">Batal</button>
            <button @click="submit()" class="px-8 py-4 text-[11px] font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-all shadow-lg uppercase tracking-wider order-1 sm:order-2 active:scale-95 font-sans">Simpan Sertifikasi</button>
        </div>
    </div>

</div>
