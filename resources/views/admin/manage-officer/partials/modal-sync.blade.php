{{-- MODAL SYNC --}}
<div x-show="showSyncModal" class="fixed inset-0 z-1000 flex items-center justify-center p-4 xl:p-8" x-cloak>
    <div x-show="showSyncModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
        @click="showSyncModal = false">
    </div>

    <div x-show="showSyncModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
        class="relative bg-white rounded-2xl shadow-2xl max-w-sm w-full p-8 text-center border border-slate-100">
        
        <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
            </svg>
        </div>
        
        <h3 class="text-lg font-bold text-slate-800 uppercase tracking-widest mb-2">Sinkronisasi Petugas</h3>
        <p class="text-[13px] text-slate-500 mb-8 leading-relaxed px-4">Proses ini akan memindai seluruh unit SPPG dan menyalin petugas (KaSPPG, Ahli Gizi, Akuntan) ke daftar ini. Lanjutkan?</p>
        
        <div class="flex gap-3">
            <button @click="showSyncModal = false" type="button" class="flex-1 py-3 text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors font-sans w-full">Batal</button>
            <form action="{{ route('admin.manage-officer.import-from-sppg') }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full py-3 text-[11px] font-bold uppercase tracking-wider text-white bg-emerald-600 rounded-xl shadow-lg hover:bg-emerald-700 transition-colors font-sans">Mulai Sinkron</button>
            </form>
        </div>
    </div>
</div>
