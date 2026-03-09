<div x-show="showDeleteModal" 
    class="fixed inset-0 z-[1000] flex items-center justify-center p-4 text-left" 
    x-cloak>
    
    <div x-show="showDeleteModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showDeleteModal = false"></div>

    <div x-show="showDeleteModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative bg-white rounded-2xl shadow-2xl max-w-sm w-full p-8 text-center border border-slate-100 transform transition-all font-sans">
        
        <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        
        <h3 class="text-lg font-bold text-slate-800 uppercase tracking-widest mb-2">Hapus Supplier?</h3>
        <p class="text-[13px] text-slate-500 mb-8 leading-relaxed px-4">
            Apakah Anda yakin ingin menghapus <span class="font-bold text-slate-700" x-text="selectedSupplier?.name_supplier"></span>? Tindakan ini tidak dapat dibatalkan.
        </p>

        <div class="flex gap-3">
            <button type="button" @click="showDeleteModal = false" class="flex-1 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                Batal
            </button>
            <form x-bind:action="selectedSupplier ? `/admin/manage-supplier/${selectedSupplier.id_supplier}` : ''" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-4 text-[11px] font-bold uppercase tracking-wider text-white bg-rose-600 rounded-xl shadow-lg shadow-rose-100 hover:bg-rose-700 transition-colors">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
