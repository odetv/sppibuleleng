    {{-- MODAL KONFIRMASI HAPUS DATA --}}
    <div id="confirmReplaceModal" class="fixed inset-0 z-[110] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-md"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl border border-rose-100 w-full max-w-md overflow-hidden font-sans">
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </div>
                <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight mb-2">Konfirmasi Buat Ulang?</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Tindakan ini akan <b>MENGHAPUS SEMUA DATA PENGGUNA</b> yang ada di database (kecuali akun Anda) dan menggantinya dengan data dari file ini. Data yang dihapus tidak dapat dikembalikan.</p>
            </div>
            <div class="p-6 bg-slate-50 border-t border-slate-100 flex gap-3">
                <button type="button" onclick="closeConfirmModal()" class="flex-1 py-3 text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition-all">Batalkan</button>
                <button type="button" onclick="finalSubmitImport()" class="flex-1 py-3 text-[11px] font-bold uppercase tracking-wider text-white bg-rose-600 rounded-xl shadow-lg shadow-rose-200 hover:bg-rose-700 transition-all">Ya, Hapus & Ganti</button>
            </div>
        </div>
    </div>
