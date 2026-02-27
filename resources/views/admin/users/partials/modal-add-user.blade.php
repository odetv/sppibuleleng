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
            <form action="{{ route('admin.users.store') }}" method="POST" autocomplete="off" id="formAddUser">
                @csrf
                <div class="p-8 space-y-5">
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

                <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-3">
                    <button type="button" onclick="closeAddUserModal()" class="flex-1 py-3 text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all">Batal</button>
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
            // document.getElementById('copy_area').classList.add('hidden');

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
        window.checkUniqueness = function(type, value) {
            clearTimeout(typingTimer);
            const errorEl = document.getElementById('error_' + type);
            const inputEl = document.getElementById('add_' + type);

            // Set status ke false setiap kali ada perubahan ketikan
            validationStatus[type] = false;
            validateSubmitButton();

            if (value.length < 3) return;

            typingTimer = setTimeout(() => {
                fetch(`/admin/manage-user/check-availability?type=${type}&value=${value}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.exists) {
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
        });

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
