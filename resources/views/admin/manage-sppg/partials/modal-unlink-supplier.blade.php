<div id="unlinkSupplierModal" x-show="showUnlinkSupplierModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showUnlinkSupplierModal = false"></div>
    <div x-show="showUnlinkSupplierModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="relative bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-md overflow-hidden transform transition-all">
        
        <div class="p-8 text-center">
            <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                </svg>
            </div>
            
            <h3 class="text-lg font-bold text-slate-800 uppercase tracking-wider mb-2">Lepas Tautan Supplier</h3>
            <p class="text-sm text-slate-500 leading-relaxed mb-8">
                Apakah Anda yakin ingin melepas tautan supplier <b class="text-slate-700" x-text="supplierToUnlink ? supplierToUnlink.name_supplier : ''"></b> dari unit SPPG ini?
            </p>
            
            <div class="flex gap-3">
                <button type="button" @click="showUnlinkSupplierModal = false" 
                        class="flex-1 px-4 py-3 text-xs font-bold uppercase tracking-widest text-slate-500 bg-slate-50 border border-slate-200 rounded-xl hover:bg-slate-100 transition-all cursor-pointer">
                    Batal
                </button>
                <button type="button" @click="confirmUnlinkSupplier()" 
                        class="flex-1 px-4 py-3 text-xs font-bold uppercase tracking-widest text-white bg-rose-600 rounded-xl shadow-lg shadow-rose-200 hover:bg-rose-700 transition-all active:scale-95 cursor-pointer">
                    Ya, Lepas
                </button>
            </div>
        </div>
    </div>
</div>
