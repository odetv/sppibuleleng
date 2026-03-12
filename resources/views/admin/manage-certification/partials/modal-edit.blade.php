{{-- MODAL EDIT --}}
<div x-show="showEditModal" 
     class="fixed inset-0 z-[100] flex items-center justify-center p-4 text-left"
     x-cloak>
    
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showEditModal = false"></div>

    <div x-show="showEditModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="relative bg-white rounded-xl shadow-xl w-full max-w-xl overflow-hidden flex flex-col max-h-[90vh] border border-slate-200 transform transition-all font-sans text-sm"
         x-data="{ 
            errors: {},
            async updateCertification() {
                this.errors = {};
                const formData = new FormData();
                const fields = ['name_certification', 'certification_number', 'issued_by', 'issued_date', 'start_date', 'expiry_date'];
                fields.forEach(k => formData.append(k, selectedCertification[k] || ''));
                formData.append('status', selectedCertification.status ? 1 : 0);
                formData.append('_method', 'PATCH');

                const fileInput = document.getElementById('edit_file_input');
                if(fileInput.files[0]) formData.append('file_certification', fileInput.files[0]);

                try {
                    const res = await fetch(`/admin/manage-certification/${selectedCertification.id_certification}/update`, {
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
                <span class="p-2 bg-indigo-50 text-indigo-600 rounded-xl outline-4 outline-indigo-50/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </span>
                <h3 class="text-[14px] font-bold uppercase tracking-widest">Edit Sertifikasi</h3>
            </div>
            <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 text-2xl cursor-pointer transition-colors">&times;</button>
        </div>

        {{-- Body --}}
        <div class="p-8 overflow-y-auto custom-scrollbar space-y-6 bg-white flex-1 min-h-0">
            <div class="grid grid-cols-1 gap-6">
                {{-- Unit SPPG (Read Only) --}}
                <div class="space-y-2">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Unit SPPG</label>
                    <div class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-400 font-medium cursor-not-allowed" x-text="selectedCertification.sppg_unit?.name || '-'"></div>
                </div>

                {{-- Jenis Sertifikasi --}}
                <div class="space-y-2">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Jenis Sertifikasi <span class="text-rose-500">*</span></label>
                    <select x-model="selectedCertification.name_certification" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 py-3 px-4 transition-all">
                        <option value="SLHS" :disabled="isCertTypeUsed(selectedCertification.id_sppg_unit, 'SLHS', selectedCertification.original_type)">SLHS (Sertifikat Laik Higiene Sanitasi)</option>
                        <option value="Halal" :disabled="isCertTypeUsed(selectedCertification.id_sppg_unit, 'Halal', selectedCertification.original_type)">Halal</option>
                        <option value="HACCP" :disabled="isCertTypeUsed(selectedCertification.id_sppg_unit, 'HACCP', selectedCertification.original_type)">HACCP</option>
                        <option value="Chef" :disabled="isCertTypeUsed(selectedCertification.id_sppg_unit, 'Chef', selectedCertification.original_type)">Chef</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nomor Sertifikasi --}}
                    <div class="space-y-2">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Nomor Sertifikasi <span class="text-rose-500">*</span></label>
                        <input type="text" x-model="selectedCertification.certification_number" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 py-3 px-4 transition-all" :class="errors.certification_number ? 'border-rose-500 ring-rose-50 bg-rose-50' : ''">
                        <template x-if="errors.certification_number">
                            <p class="error-warning" x-text="'* Wajib diisi'"></p>
                        </template>
                    </div>
                    {{-- Diterbitkan Oleh --}}
                    <div class="space-y-2">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Diterbitkan Oleh <span class="text-rose-500">*</span></label>
                        <input type="text" x-model="selectedCertification.issued_by" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 py-3 px-4 transition-all" :class="errors.issued_by ? 'border-rose-500 ring-rose-50 bg-rose-50' : ''">
                        <template x-if="errors.issued_by">
                            <p class="error-warning" x-text="'* Wajib diisi'"></p>
                        </template>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Tgl Terbit --}}
                    <div class="space-y-2">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tgl Terbit <span class="text-rose-500">*</span></label>
                        <input type="date" x-model="selectedCertification.issued_date" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 py-3 px-4 transition-all" :class="errors.issued_date ? 'border-rose-500 ring-rose-50 bg-rose-50' : ''">
                        <template x-if="errors.issued_date">
                            <p class="error-warning" x-text="'* Wajib diisi'"></p>
                        </template>
                    </div>
                    {{-- Tgl Mulai --}}
                    <div class="space-y-2">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tgl Mulai</label>
                        <input type="date" x-model="selectedCertification.start_date" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 py-3 px-4 transition-all">
                    </div>
                    {{-- Tgl Berakhir --}}
                    <div class="space-y-2">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tgl Berakhir</label>
                        <input type="date" x-model="selectedCertification.expiry_date" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 py-3 px-4 transition-all">
                    </div>
                </div>

                {{-- File Input --}}
                <div class="space-y-2">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">File Baru (PDF - Opsional)</label>
                    <div class="relative flex items-center shadow-sm rounded-xl">
                        <input type="file" id="edit_file_input" accept="application/pdf" 
                               class="w-full text-[11px] bg-slate-50 border border-slate-200 rounded-xl py-2 px-4 focus:ring-2 focus:ring-indigo-500 transition-all cursor-pointer
                                      file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[11px] file:font-bold file:uppercase file:tracking-wider
                                      file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 shadow-sm">
                    </div>
                    <template x-if="selectedCertification.file_certification">
                        <div class="mt-2 flex items-center gap-2">
                            <span class="text-[10px] text-slate-400 font-medium">File saat ini:</span>
                            <a :href="'/storage/' + selectedCertification.file_certification" target="_blank" class="text-[10px] text-indigo-600 font-bold hover:underline flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Lihat File
                            </a>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="p-6 border-t border-slate-100 bg-slate-50 flex flex-col sm:flex-row justify-end gap-3 px-8">
            <button @click="showEditModal = false" class="px-8 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider hover:bg-slate-100/50 rounded-xl transition-all border border-slate-200 order-2 sm:order-1 font-sans">Batal</button>
            <button @click="updateCertification()" class="px-8 py-4 text-[11px] font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-all shadow-lg uppercase tracking-wider order-1 sm:order-2 active:scale-95 font-sans">Update Sertifikasi</button>
        </div>
    </div>

</div>
