    {{-- MODAL TAMBAH PENGGUNA --}}
    <div id="addUserModal" class="fixed inset-0 z-[99] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeAddUserModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-md overflow-hidden transform transition-all font-sans text-sm">

            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center text-slate-800">
                <div class="flex items-center gap-3">
                    <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                            <path d="M6.25 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM3.25 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM19.75 7.5a.75.75 0 0 0-1.5 0v2.25H16a.75.75 0 0 0 0 1.5h2.25v2.25a.75.75 0 0 0 1.5 0v-2.25H22a.75.75 0 0 0 0-1.5h-2.25V7.5Z" />
                        </svg>
                    </span>
                    <h3 class="text-[14px] font-bold uppercase tracking-widest">Tambah Akun Pengguna</h3>
                </div>
                <button type="button" onclick="closeAddUserModal()" class="text-slate-400 hover:text-slate-600 text-2xl cursor-pointer">&times;</button>
            </div>

            {{-- Form dengan autocomplete off dan id unik agar tidak bentrok --}}
            <form action="{{ route('admin.manage-user.store') }}" method="POST" autocomplete="off" id="formAddUser">
                @csrf
                <div class="p-8 space-y-5" id="add_user_form_body">
                    {{-- Email --}}
                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Email</label>
                        <input type="email" name="email" id="add_email" required
                            oninput="checkUniqueness('email', this.value)"
                            placeholder="Cth: user@email.com"
                            class="w-full mt-2 px-4 py-2.5 bg-gray-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        <p id="error_email" class="text-[10px] text-rose-600 mt-1 font-bold hidden uppercase tracking-tight"></p>
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nomor WhatsApp</label>
                        <input type="number" name="phone" id="add_phone" required
                            oninput="checkUniqueness('phone', this.value)"
                            placeholder="Cth: 085xxxxxxxxx"
                            class="w-full mt-2 px-4 py-2.5 bg-gray-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        <p id="error_phone" class="text-[10px] text-rose-600 mt-1 font-bold hidden uppercase tracking-tight"></p>
                    </div>

                    {{-- Role --}}
                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Hak Akses Sistem</label>
                        <select name="id_ref_role" required class="w-full mt-2 px-3 py-2.5 bg-gray-50 border border-slate-200 rounded-lg text-sm outline-none">
                            <option value="" disabled selected>Pilih Role</option>
                            @foreach($roles as $r)
                            <option value="{{$r->id_ref_role}}">{{$r->name_role}}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Password Section --}}
                    <!-- <div class="space-y-3 pt-4 border-t border-slate-50" disabled>
                        <div class="flex justify-between items-center">
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kata Sandi</label>
                            <button type="button" onclick="generateStrongPassword()" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800 uppercase bg-indigo-50 px-2 py-1 rounded transition-colors">Dapatkan Acak</button>
                        </div>

                        {{-- Area Copas Password (Muncul setelah klik generate) --}}
                        <div id="copy_area" class="hidden transform transition-all duration-300">
                            <div class="flex items-center gap-2 p-2 bg-emerald-50 border border-emerald-100 rounded-lg">
                                <input type="text" id="pass_display" readonly class="flex-1 bg-transparent border-none text-xs font-mono font-bold text-emerald-700 focus:ring-0 p-0">
                                <button type="button" onclick="copyToClipboard()" id="btn_copy" class="text-[10px] font-bold text-white bg-emerald-600 px-3 py-1 rounded hover:bg-emerald-700 transition-all">Salin</button>
                            </div>
                        </div>

                        <div class="relative group">
                            <input type="password" name="password" id="add_password" required placeholder="Buat Kata Sandi" autocomplete="new-password"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all pr-10">
                            <button type="button" onclick="togglePass('add_password', 'eye_add_1')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-indigo-600">
                                <svg class="h-5 w-5" fill="none" id="eye_add_1" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>

                        <div class="relative group">
                            <input type="password" name="password_confirmation" id="add_password_conf" required placeholder="Ulangi Kata Sandi" autocomplete="new-password"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all pr-10">
                            <button type="button" onclick="togglePass('add_password_conf', 'eye_add_2')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-indigo-600">
                                <svg class="h-5 w-5" fill="none" id="eye_add_2" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div> -->
                </div>

                {{-- PROGRESS BAR UI --}}
                <div id="add_user_progress" class="hidden p-8 flex flex-col justify-center items-center space-y-4 min-h-[300px]">
                    <div class="w-16 h-16 rounded-full border-4 border-slate-100 border-t-indigo-600 animate-spin mb-4"></div>
                    <h4 id="progress_text" class="text-sm font-bold text-slate-700 uppercase tracking-widest text-center">Inisialisasi...</h4>
                    <div class="w-full max-w-xs bg-slate-100 rounded-full h-2.5 overflow-hidden">
                      <div id="progress_bar_fill" class="bg-indigo-600 h-2.5 rounded-full transition-all duration-300 ease-out" style="width: 0%"></div>
                    </div>
                    <p class="text-[10px] text-slate-400 font-medium text-center italic mt-4 max-w-xs">Mohon jangan tutup jendela ini. Sistem sedang mendaftarkan akun dan mengirimkan pesan berisi tautan verifikasi keamanan ke email pengguna.</p>
                </div>

                <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-3">
                    <button type="button" id="btn_cancel_add" onclick="closeAddUserModal()" class="flex-1 py-3 text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all">Batal</button>
                    <button type="submit" id="btn_submit_add" disabled class="flex-1 py-3 text-[11px] font-bold uppercase tracking-wider text-white bg-indigo-600 rounded-xl shadow-lg hover:bg-indigo-700 transition-all active:scale-[0.98]">Buat Akun</button>
                </div>
            </form>
        </div>
    </div>
    </div>

<script>
        // Fungsi Modal Tambah
        // Objek untuk melacak status validasi
        let validationStatus = {
            email: true,
            phone: true
        };

        window.openAddUserModal = function() {
            const modal = document.getElementById('addUserModal');
            const form = document.getElementById('formAddUser');
            form.reset();

            // Reset state
            validationStatus.email = false; // Set false agar harus divalidasi dulu
            validationStatus.phone = false;

            document.getElementById('error_email').classList.add('hidden');
            document.getElementById('error_phone').classList.add('hidden');
            
            // Kembalikan UI Progress Bar ke Form
            document.getElementById('add_user_form_body').classList.remove('hidden');
            document.getElementById('add_user_progress').classList.add('hidden');
            document.getElementById('btn_cancel_add').classList.remove('hidden');
            document.getElementById('progress_bar_fill').style.width = '0%';
            document.getElementById('progress_text').innerText = 'Inisialisasi...';

            validateSubmitButton(); // Kunci tombol saat awal buka
            modal.classList.remove('hidden');
        }

        // Fungsi untuk mengontrol tombol submit secara terpusat
        function validateSubmitButton() {
            const btnSubmit = document.getElementById('btn_submit_add');
            const emailVal = document.getElementById('add_email').value;
            const phoneVal = document.getElementById('add_phone').value;

            // Tombol hanya aktif jika:
            // 1. Email & Phone tidak kosong
            // 2. Status validasi dari server menunjukkan OK (true)
            if (emailVal !== '' && phoneVal !== '' && validationStatus.email && validationStatus.phone) {
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                btnSubmit.disabled = true;
                btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }

        let typingTimer;
        const inputPatterns = {
            email: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
            phone: /^[0-9]{10,15}$/
        };

        window.checkUniqueness = function(type, value) {
            clearTimeout(typingTimer);
            const errorEl = document.getElementById('error_' + type);
            const inputEl = document.getElementById('add_' + type);

            // Set status ke false setiap kali ada perubahan ketikan
            validationStatus[type] = false;
            validateSubmitButton();

            if (value.length === 0) {
                errorEl.classList.add('hidden');
                inputEl.classList.remove('border-rose-500', 'ring-rose-500');
                return;
            }

            // Validasi Format Frontend
            if (!inputPatterns[type].test(value)) {
                errorEl.innerText = `Format ${type === 'email' ? 'Email' : 'Nomor HP'} tidak valid!`;
                errorEl.classList.remove('hidden');
                inputEl.classList.add('border-rose-500', 'ring-rose-500');
                return;
            }

            if (value.length < 3) return;

            typingTimer = setTimeout(() => {
                // Backend merespons cek berdasarkan spesifik query param 'email' atau 'phone'
                fetch(`/admin/manage-user/check-availability?${type}=${value}`)
                    .then(res => res.json())
                    .then(data => {
                        // Tentukan variabel mana yang jadi penanda error sesuai tipe
                        const isDuplicate = type === 'email' ? data.email_duplicate : data.phone_duplicate;
                        
                        if (isDuplicate) {
                            errorEl.innerText = `${type === 'email' ? 'Email' : 'Nomor HP'} ini sudah terdaftar!`;
                            errorEl.classList.remove('hidden');
                            inputEl.classList.add('border-rose-500', 'ring-rose-500');
                            validationStatus[type] = false;
                        } else {
                            errorEl.classList.add('hidden');
                            inputEl.classList.remove('border-rose-500', 'ring-rose-500');
                            validationStatus[type] = true;
                        }
                        validateSubmitButton(); // Cek ulang kondisi tombol
                    });
            }, 500);
        }

        // KUNCI TERAKHIR: Cek saat form akan dikirim (Submit Event)
        document.getElementById('formAddUser').addEventListener('submit', function(e) {
            if (!validationStatus.email || !validationStatus.phone) {
                e.preventDefault(); // Batalkan pengiriman jika ada yang tidak valid
                alert('Mohon pastikan email dan nomor HP unik dan tersedia!');
                return false;
            }

            // GANTI KE PENGIRIMAN AJAX
            e.preventDefault();
            
            // Sembunyikan form fields dan tombol batal, lalu nonaktifkan tombol submit
            document.getElementById('add_user_form_body').classList.add('hidden');
            document.getElementById('btn_cancel_add').classList.add('hidden');
            
            const btnSubmit = document.getElementById('btn_submit_add');
            btnSubmit.disabled = true;
            btnSubmit.innerText = "Memproses...";
            btnSubmit.classList.add('opacity-50', 'cursor-not-allowed', 'grayscale');

            const progressContainer = document.getElementById('add_user_progress');
            progressContainer.classList.remove('hidden');

            // Reset dan sembunyikan alert backend bila ada
            let backendAlert = document.getElementById('backend_error_alert');
            if (backendAlert) backendAlert.classList.add('hidden');

            // Simulasi Update Animasi Bar Progress Visually selama proses server berjalan
            let progress = 0;
            const bar = document.getElementById('progress_bar_fill');
            const statusText = document.getElementById('progress_text');

            const interval = setInterval(() => {
                if (progress >= 90) {
                    clearInterval(interval);
                    statusText.innerText = "Mengirim Pesan Verifikasi...";
                } else {
                    progress += 10;
                    bar.style.width = progress + '%';
                    
                    if (progress === 30) {
                        statusText.innerText = "Menyiapkan Entri Data...";
                    } else if (progress === 60) {
                        statusText.innerText = "Menghubungkan ke SMTP Gateway...";
                    }
                }
            }, 500);
            
            // Lakukan FETCH AJAX
            const form = e.target;
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(res => {
                clearInterval(interval);
                bar.style.width = '100%';
                
                if (res.status === 200 && res.body.success) {
                    statusText.innerText = "Berhasil!";
                    // Reload halaman atau redirect untuk menampilkan flash message Sukses
                    window.location.reload();
                } else {
                    // Tampilkan Error di Modal
                    showBackendError(res.body.message || "Terjadi kesalahan internal.");
                }
            })
            .catch(error => {
                clearInterval(interval);
                showBackendError("Koneksi terputus atau server tidak merespons.");
            });
        });

        // Tampilkan Error dari Backend & Kembalikan Kondisi Awal
        function showBackendError(message) {
            // Tampilkan kembali form input
            document.getElementById('add_user_form_body').classList.remove('hidden');
            document.getElementById('btn_cancel_add').classList.remove('hidden');
            document.getElementById('add_user_progress').classList.add('hidden');
            
            const btnSubmit = document.getElementById('btn_submit_add');
            btnSubmit.disabled = false;
            btnSubmit.innerText = "Buat Akun";
            btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed', 'grayscale');

            // Buat alert bila belum ada
            let alertBox = document.getElementById('backend_error_alert');
            if (!alertBox) {
                alertBox = document.createElement('div');
                alertBox.id = 'backend_error_alert';
                alertBox.className = 'p-4 mx-8 mb-4 mt-4 bg-rose-50 border border-rose-200 text-rose-600 rounded-lg text-xs font-bold uppercase tracking-widest text-center';
                const formBody = document.getElementById('add_user_form_body');
                formBody.parentNode.insertBefore(alertBox, formBody);
            }
            alertBox.innerText = message;
            alertBox.classList.remove('hidden');
        }

        // --- Fungsi Password Tetap Sama ---
        // window.generateStrongPassword = function() {
        //     const uppercase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        //     const lowercase = "abcdefghijklmnopqrstuvwxyz";
        //     const numbers = "0123456789";
        //     const symbols = "!@#$%^&*()_+~}{[]";
        //     const allChars = uppercase + lowercase + numbers + symbols;
        //     let password = "";
        //     password += uppercase[Math.floor(Math.random() * uppercase.length)];
        //     password += lowercase[Math.floor(Math.random() * lowercase.length)];
        //     password += numbers[Math.floor(Math.random() * numbers.length)];
        //     password += symbols[Math.floor(Math.random() * symbols.length)];
        //     for (let i = 0; i < 8; i++) {
        //         password += allChars[Math.floor(Math.random() * allChars.length)];
        //     }
        //     password = password.split('').sort(() => 0.5 - Math.random()).join('');
        //     document.getElementById('add_password').value = password;
        //     document.getElementById('add_password_conf').value = password;
        //     document.getElementById('pass_display').value = password;
        //     document.getElementById('copy_area').classList.remove('hidden');
        // }

        // window.copyToClipboard = function() {
        //     const passVal = document.getElementById('pass_display');
        //     passVal.select();
        //     document.execCommand("copy");
        //     const btn = document.getElementById('btn_copy');
        //     btn.innerText = "Copied!";
        //     setTimeout(() => {
        //         btn.innerText = "Copy";
        //     }, 2000);
        // }

        // window.togglePass = function(inputId, iconId) {
        //     const input = document.getElementById(inputId);
        //     input.type = (input.type === "password") ? "text" : "password";
        // }

        window.closeAddUserModal = function() {
            document.getElementById('addUserModal').classList.add('hidden');
        }
</script>
