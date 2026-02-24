<x-app-layout title="Pengaturan Sistem">
    <div class="p-4 sm:p-8">
        <div class="w-full mx-auto sm:px-6 lg:px-8 space-y-10">

            {{-- Header Halaman --}}
            <div class="flex flex-col gap-2">
                <h2 class="text-2xl font-bold text-slate-800">Pengaturan Sistem</h2>
                <p class="text-sm text-slate-500">Kelola konfigurasi global aplikasi SPPI Buleleng.</p>
            </div>

            <div class="grid grid-cols-1 gap-6">

                {{-- Card Maintenance Mode --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="p-2 bg-amber-50 rounded-lg text-amber-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-800">Mode Maintenance</h3>
                                </div>
                                <p class="text-sm text-slate-500 leading-relaxed max-w-xl">
                                    Aktifkan mode ini jika Anda sedang melakukan update data besar atau perbaikan sistem. Saat aktif, hanya akun dengan peran <strong>Administrator</strong> yang dapat mengakses dashboard. Pengguna lain akan diarahkan ke halaman pemeliharaan.
                                </p>
                            </div>

                            {{-- Toggle Switch menggunakan Alpine.js untuk Visual --}}
                            @php
                            $isMaintenance = \App\Models\Setting::get('is_maintenance', '0') === '1';
                            @endphp

                            <div x-data="{ enabled: {{ $isMaintenance ? 'true' : 'false' }} }">
                                <form id="maintenance-form" action="{{ route('admin.maintenance.toggle') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        @click="enabled = !enabled"
                                        :class="enabled ? 'bg-rose-500' : 'bg-slate-200'"
                                        class="relative inline-flex h-7 w-12 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">
                                        <span :class="enabled ? 'translate-x-5' : 'translate-x-0'"
                                            class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                                        </span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Alert Status --}}
                        <div class="mt-6 flex items-center gap-4 p-4 rounded-2xl {{ $isMaintenance ? 'bg-rose-50 border border-rose-100' : 'bg-emerald-50 border border-emerald-100' }}">
                            <div class="flex-shrink-0">
                                @if($isMaintenance)
                                <span class="relative flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                                </span>
                                @else
                                <span class="h-3 w-3 rounded-full bg-emerald-500 block"></span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-bold uppercase tracking-wider {{ $isMaintenance ? 'text-rose-600' : 'text-emerald-600' }}">
                                    Status Saat Ini: {{ $isMaintenance ? 'Sistem Sedang Ditutup' : 'Sistem Beroperasi Normal' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Card --}}
                    <div class="bg-slate-50 px-8 py-4 border-t border-slate-100 flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-[11px] text-slate-400 uppercase font-semibold">Terakhir diubah: {{ \App\Models\Setting::where('key', 'is_maintenance')->first()?->updated_at?->diffForHumans() ?? '-' }}</span>
                    </div>
                </div>

                {{-- Card Tambahan (Opsional) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 opacity-60">
                    <div class="bg-white p-6 rounded-3xl border border-dashed border-slate-200 flex items-center gap-4">
                        <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center text-slate-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm">Backup & Restore Database</h4>
                            <p class="text-[11px] text-slate-500">Fitur segera tersedia</p>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-3xl border border-dashed border-slate-200 flex items-center gap-4">
                        <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center text-slate-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm">Konfigurasi Email</h4>
                            <p class="text-[11px] text-slate-500">Fitur segera tersedia</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>