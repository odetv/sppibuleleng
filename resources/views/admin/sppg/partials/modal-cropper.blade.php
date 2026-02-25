{{-- MODAL CROPPER --}}
<div id="cropperModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" id="closeCropperOverlay"></div>

    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all">
        <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center text-slate-800">
            <div class="flex items-center gap-3">
                <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M7 21h10M12 3v18M3 7l18 10M3 17l18-10"></path>
                    </svg>
                </span>
                <h3 class="text-[14px] font-bold uppercase tracking-widest text-slate-700">Potong Foto Unit (4:3)</h3>
            </div>
            <button type="button" id="cancel-crop-btn" class="text-slate-400 hover:text-slate-600 text-2xl">&times;</button>
        </div>

        <div class="p-6 bg-slate-200 flex justify-center">
            <div class="max-w-full max-h-[60vh]">
                <img id="image-to-crop" class="block max-w-full">
            </div>
        </div>

        <div class="p-6 border-t border-slate-100 bg-slate-50 flex gap-4">
            <button id="cancel-crop" type="button" class="flex-1 py-3 text-[11px] font-bold uppercase text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition-all">Batal</button>
            <button id="apply-crop" type="button" class="flex-1 py-3 text-[11px] font-bold uppercase text-white bg-slate-800 rounded-xl shadow-lg hover:bg-slate-900 transition-all active:scale-95">Gunakan Potongan</button>
        </div>
    </div>
</div>

{{-- Library Cropper.js --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>