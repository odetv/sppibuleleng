{{-- MODAL HAPUS SK --}}
<div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showDeleteModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-sm w-full p-8 text-center border border-slate-100"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
         
        <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-slate-800 uppercase tracking-widest mb-2">Hapus Dokumen SK?</h3>
        <p class="text-[13px] text-slate-500 mb-8 leading-relaxed px-4">
             Data SK beserta file dokumen fisik (PDF) dan seluruh riwayat ikatannya dengan Unit SPPG akan dihapus secara permanen. Aksi ini tidak dapat dibatalkan.
        </p>
        <div class="flex gap-3">
            <button type="button" @click="showDeleteModal = false" class="flex-1 py-3 text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors font-sans">Batal</button>
            <form :action="deleteUrl" method="POST" class="flex-1">
                @csrf @method('DELETE')
                <button type="submit" class="w-full py-3 text-[11px] font-bold uppercase tracking-wider text-white bg-rose-600 rounded-xl shadow-lg hover:bg-rose-700 transition-colors font-sans">Ya, Hapus SK</button>
            </form>
        </div>
    </div>
</div>
