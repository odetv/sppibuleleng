<!doctype html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
        <title>SPPI Buleleng</title>
    </head>
    <body class="bg-white mx-auto max-w-11/12">
        <header class="absolute inset-x-0 top-0 z-50">
            <nav aria-label="Global" class="flex items-center justify-between p-6 lg:px-8">
            <div class="flex lg:flex-1">
                <a href="#" class="-m-1.5 p-1.5 flex items-center gap-3">
                    <img src="{{ asset('assets/images/logo-sppi.png') }}" alt="" class="h-12 w-12" />
                    <img src="{{ asset('assets/images/logo-bgn.png') }}" alt="" class="h-12 w-auto" />
                    <span class="sr-only font-semibold">SPPI Buleleng</span>
                </a>
            </div>
            <div class="flex lg:hidden">
                <button type="button" command="show-modal" commandfor="mobile-menu" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
                <span class="sr-only">Open main menu</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                    <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                </button>
            </div>
            <div class="hidden lg:flex lg:gap-x-12">
                <a href="#" class="text-sm/6 font-semibold text-gray-900">Beranda</a>
                <a href="#" class="text-sm/6 font-semibold text-gray-900">Dashboard</a>
                <a href="#" class="text-sm/6 font-semibold text-gray-900">FAQ</a>
                <a href="#" class="text-sm/6 font-semibold text-gray-900">Tentang</a>
            </div>
            <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                <a href="#" class="text-sm/6 font-semibold bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">Masuk</a>
            </div>
            </nav>
            <el-dialog>
            <dialog id="mobile-menu" class="backdrop:bg-transparent lg:hidden">
                <div tabindex="0" class="fixed inset-0 focus:outline-none">
                <el-dialog-panel class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white p-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                    <div class="flex items-center justify-between">
                    <a href="#" class="-m-1.5 p-1.5 flex items-center gap-3">
                        <img src="{{ asset('assets/images/logo-sppi.png') }}" alt="" class="h-12 w-12" />
                        <img src="{{ asset('assets/images/logo-bgn.png') }}" alt="" class="h-12 w-auto" />
                        <span class="sr-only font-semibold">SPPI Buleleng</span>
                    </a>
                    <button type="button" command="close" commandfor="mobile-menu" class="-m-2.5 rounded-md p-2.5 text-gray-700">
                        <span class="sr-only">Close menu</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                        <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    </div>
                    <div class="mt-6 flow-root">
                    <div class="-my-6 divide-y divide-gray-500/10">
                        <div class="space-y-2 py-6">
                        <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Beranda</a>
                        <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Dashboard</a>
                        <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">FAQ</a>
                        <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Tentang</a>
                        </div>
                        <div class="py-6">
                        <a href="#" class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Masuk</a>
                        </div>
                    </div>
                    </div>
                </el-dialog-panel>
                </div>
            </dialog>
            </el-dialog>
        </header>
        <section class="pt-20 max-w-7xl mx-auto">
            <div aria-hidden="true" class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80">
                <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" class="relative left-[calc(50%-11rem)] aspect-1155/678 w-144.5 -translate-x-1/2 rotate-30 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-288.75"></div>
            </div>
            <div class="py-12 sm:py-48">
                <div class="mb-8 sm:flex justify-center">
                    <div class="relative rounded-full px-4 py-2 text-xs sm:text-lg text-blue-600 ring-1 ring-gray-900/10 hover:ring-gray-900/20">
                    <p class="text-center font-semibold">Sarjana Penggerak Pembangunan Indonesia untuk Badan Gizi Nasional</p>
                </div>
            </div>
            <div class="text-center">
                <div class="flex flex-row justify-center items-center gap-3 sm:gap-6">
                    <img src="{{ asset('assets/images/logo-sppi.png') }}" alt="" class="h-20 w-20 sm:h-32 sm:w-32" />
                    <img src="{{ asset('assets/images/logo-bgn.png') }}" alt="" class="h-20 sm:h-32 w-auto" />
                    <span class="sr-only font-semibold">SPPI Buleleng</span>
                </div>
                <p class="mt-8 text-sm font-medium text-gray-800 sm:text-lg">SPPI untuk BGN merujuk pada program Sarjana Penggerak Pembangunan Indonesia, yang merekrut lulusan sarjana untuk menjadi ASN di Badan Gizi Nasional. Program ini bertujuan untuk memastikan setiap anak memiliki akses gizi yang layak dan menggarap aspek teknis, serta melibatkan peserta dalam pelatihan kepemimpinan dan dasar militer untuk penguatan ketahanan nasional.</p>
                <div class="mt-10 flex items-center justify-center gap-x-6">
                <a href="#info-quick-update-sppg" class="rounded-md bg-blue-500 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-blue-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Jelajahi <span aria-hidden="true">&rarr;</span></a>
                </div>
            </div>
            </div>
        </section>
        <section id="info-quick-update-sppg" class="pt-20 pb-20">
            <p class="text-center font-bold text-xl sm:text-3xl pb-2">Informasi SPPG Terkini</p>
            <p class="text-center font-medium text-sm sm:text-lg pb-14 text-gray-800">Sekilas informasi Operasional SPPG dan Penerima Manfaat di Kabupaten Buleleng</p>
            <div class="mx-auto max-w-4/5 px-6 lg:px-8">
                <dl class="flex sm:flex-row justify-center items-center text-center gap-10 flex-wrap">
                <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                    <dt class="text-sm sm:text-base text-gray-600">SPPG Telah Operasional</dt>
                    <dd class="order-first text-3xl font-semibold tracking-tight text-blue-600 sm:text-5xl">23</dd>
                </div>
                <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                    <dt class="text-sm sm:text-base text-gray-600">SPPG Tutup Sementara</dt>
                    <dd class="order-first text-3xl font-semibold tracking-tight text-blue-600 sm:text-5xl">0</dd>
                </div>
                <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                    <dt class="text-sm sm:text-base text-gray-600">Total Penerima Manfaat</dt>
                    <dd class="order-first text-3xl font-semibold tracking-tight text-blue-600 sm:text-5xl">51.649</dd>
                </div>
                <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                    <dt class="text-sm sm:text-base text-gray-600">Penerima Manfaat Siswa</dt>
                    <dd class="order-first text-3xl font-semibold tracking-tight text-blue-600 sm:text-5xl">50.552</dd>
                </div>
                <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                    <dt class="text-sm sm:text-base text-gray-600">Penerima Manfaat 3B</dt>
                    <dd class="order-first text-3xl font-semibold tracking-tight text-blue-600 sm:text-5xl">1.097</dd>
                </div>
                </dl>
            </div>
        </section>
    </body>
</html>