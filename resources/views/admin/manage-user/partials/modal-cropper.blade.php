    {{-- CROPPER MODAL --}}
    <div id="cropperModal" class="fixed inset-0 z-[10000] hidden flex items-center justify-center p-4 bg-black/85 transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden font-sans">
            <div class="p-6 border-b bg-gray-50 flex justify-between items-center text-slate-800 font-bold uppercase tracking-widest text-sm">
                <h3>Potong Pas Foto</h3>
            </div>
            <div class="p-6 bg-gray-200 flex justify-center">
                <div class="max-w-full max-h-[60vh]"><img id="image-to-crop" class="block max-w-full"></div>
            </div>
            <div class="p-6 border-t flex justify-end space-x-3 text-[11px] font-bold uppercase">
                <button onclick="document.getElementById('cropperModal').classList.add('hidden')" type="button" class="px-6 py-2 text-gray-500 cursor-pointer hover:text-gray-400 transition-colors">Batal</button>
                <button id="apply-crop-btn" type="button" class="px-8 py-2 bg-slate-800 text-white rounded-lg shadow-lg cursor-pointer hover:bg-indigo-700 transition-colors">Gunakan</button>
            </div>
        </div>
    </div>
    </div>

<script>
        var editOriginalImageData = null;
        var lastEditCropData = null;

        if (photoInputEl) {
            photoInputEl.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = (ev) => {
                        editOriginalImageData = ev.target.result;
                        lastEditCropData = null; // Reset crop data if new image
                        openEditCropperModal(editOriginalImageData);
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        }

        const previewImg = document.getElementById('cropped-preview');
        // Buka cropper saat gambar yang BARU DIPILIH (base64) diklik
        if (previewImg) {
            previewImg.onclick = () => {
                 if (editOriginalImageData) {
                     openEditCropperModal(editOriginalImageData);
                 }
            };
        }

        function openEditCropperModal(src) {
            const imgToCrop = document.getElementById('image-to-crop');
            imgToCrop.src = src;
            document.getElementById('cropperModal').classList.remove('hidden');
            if (cropperObj) cropperObj.destroy();
            
            setTimeout(() => {
                cropperObj = new Cropper(imgToCrop, {
                    aspectRatio: 2 / 3,
                    viewMode: 1, 
                    dragMode: 'move',
                    autoCropArea: 1, 
                    responsive: true,
                    restore: false,
                    ready() {
                        if (lastEditCropData) {
                            this.cropper.setData(lastEditCropData);
                        } else {
                            this.cropper.crop();
                        }
                    }
                });
            }, 200);
        }

        document.getElementById('apply-crop-btn').onclick = function() {
            if (!cropperObj) return;
            
            lastEditCropData = cropperObj.getData(); // Simpan Data crop
            const canvas = cropperObj.getCroppedCanvas({
                width: 400,
                height: 600
            });
            canvas.toBlob((blob) => {
                const file = new File([blob], "photo.jpg", {
                    type: "image/jpeg"
                });
                const dt = new DataTransfer();
                dt.items.add(file);
                if (photoInputEl) photoInputEl.files = dt.files;

                const placeholder = document.getElementById('initial-placeholder');
                previewImg.src = URL.createObjectURL(blob);
                previewImg.classList.remove('hidden');
                placeholder.classList.add('hidden');

                document.getElementById('cropperModal').classList.add('hidden');
            }, 'image/jpeg');
        };
</script>
