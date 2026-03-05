{{-- MODAL UNLINK BENEFICIARY --}}
<div x-show="showUnlinkModal" class="fixed inset-0 z-[10000] flex items-center justify-center p-4 text-left" x-cloak>
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showUnlinkModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-sm w-full p-8 text-center border border-slate-100 transform transition-all"
        x-show="showUnlinkModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        
        <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-slate-800 uppercase tracking-widest mb-2">Lepas Tautan?</h3>
        <p class="text-[13px] text-slate-500 mb-8 leading-relaxed px-4">
            Apakah Anda yakin ingin melepas tautan PM <span class="font-bold text-slate-700" x-text="beneficiaryToUnlink ? beneficiaryToUnlink.name : ''"></span> dari unit ini?
        </p>
        <div class="flex gap-3">
            <button @click="showUnlinkModal = false" class="flex-1 py-3 text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">Batal</button>
            <button @click="confirmUnlink()" class="flex-1 py-3 text-[11px] font-bold uppercase tracking-wider text-white bg-rose-600 rounded-xl shadow-lg hover:bg-rose-700 transition-colors">Ya, Lepas</button>
        </div>
    </div>
</div>
