<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <title>SPPI Buleleng</title>
</head>

<body class="bg-white">
    <x-navbar />

    <section id="">
        <div class="relative min-h-[calc(100vh)] max-w-7xl mx-auto flex items-center mt-8 p-4">
            <div aria-hidden="true" class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80">
                <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" class="relative left-[calc(50%-11rem)] aspect-1155/678 w-144.5 -translate-x-1/2 rotate-30 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-288.75"></div>
            </div>
            <div>
                <div class="mb-8 sm:flex justify-center">
                    <div class="relative rounded-full px-4 py-2 text-sm sm:text-xl ring-1 text-gray-600 ring-gray-900/10 hover:ring-gray-900/20">
                        <p class="text-center font-semibold"><span class="text-red-600">Sarjana Penggerak Pembangunan Indonesia</span> untuk <span class="text-blue-600">Badan Gizi Nasional</span></p>
                    </div>
                </div>
                <div class="text-center">
                    <div class="flex flex-row justify-center items-center gap-3 sm:gap-6">
                        <img src="{{ asset('assets/images/logo-sppi.png') }}" alt="" class="h-20 w-20 sm:h-32 sm:w-32" />
                        <img src="{{ asset('assets/images/logo-bgn.png') }}" alt="" class="h-20 sm:h-32 w-auto" />
                        <span class="sr-only font-semibold">SPPI Buleleng</span>
                    </div>
                    <p class="mt-8 text-sm text-gray-800 sm:text-lg leading-loose">SPPI untuk BGN merujuk pada program Sarjana Penggerak Pembangunan Indonesia, yang merekrut lulusan sarjana untuk menjadi ASN di Badan Gizi Nasional. Program ini bertujuan untuk memastikan setiap anak memiliki akses gizi yang layak dan menggarap aspek teknis, serta melibatkan peserta dalam pelatihan kepemimpinan dan dasar militer untuk penguatan ketahanan nasional.</p>
                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        <a href="#overview" class="rounded-md bg-blue-500 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-blue-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Jelajahi <span aria-hidden="true">&rarr;</span></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="overview">
        <div class="relative bg-gray-900 pt-16 pb-16 sm:pt-26 sm:pb-26">
            <div class="absolute inset-0 z-0">
                <img
                    src="https://cms.disway.id/uploads/a2198da0fe5b4f03d57543eb83e80488.jpg"
                    alt="Background"
                    class="h-full w-full object-cover opacity-30" />
                <div class="absolute inset-0 bg-linear-to-r from-purple-900/40 to-black/60"></div>
            </div>
            <div class="relative z-10 mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center">
                    <p class="mt-2 text-4xl font-bold tracking-tight text-white sm:text-6xl">
                        Informasi SPPG Terkini
                    </p>
                    <p class="mt-6 text-lg text-gray-300">
                        Sekilas informasi Operasional SPPG dan Penerima Manfaat di Kabupaten Buleleng
                    </p>
                </div>

                <dl class="mt-16 grid grid-cols-1 gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-5">
                    <div class="flex flex-col gap-y-3 border-l border-white/10 pl-6">
                        <dd class="text-4xl font-bold tracking-tight text-white">23</dd>
                        <dt class="text-sm leading-6 text-gray-400">SPPG Telah Operasional</dt>
                    </div>
                    <div class="flex flex-col gap-y-3 border-l border-white/10 pl-6">
                        <dd class="text-4xl font-bold tracking-tight text-white">0</dd>
                        <dt class="text-sm leading-6 text-gray-400">SPPG Tutup Sementara</dt>
                    </div>
                    <div class="flex flex-col gap-y-3 border-l border-white/10 pl-6">
                        <dd class="text-4xl font-bold tracking-tight text-white">51.649</dd>
                        <dt class="text-sm leading-6 text-gray-400">Total Penerima Manfaat</dt>
                    </div>
                    <div class="flex flex-col gap-y-3 border-l border-white/10 pl-6">
                        <dd class="text-4xl font-bold tracking-tight text-white">50.552</dd>
                        <dt class="text-sm leading-6 text-gray-400">Penerima Manfaat Siswa</dt>
                    </div>
                    <div class="flex flex-col gap-y-3 border-l border-white/10 pl-6">
                        <dd class="text-4xl font-bold tracking-tight text-white">1.097</dd>
                        <dt class="text-sm leading-6 text-gray-400">Penerima Manfaat 3B</dt>
                    </div>
                </dl>
            </div>
        </div>
    </section>

    <section id="posko-sppi">
        <div class="mx-auto max-w-7xl pt-16 pb-16 sm:pt-26 sm:pb-26 p-4">
            <div class="text-center">
                <h2 class="text-xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                    Posko SPPI
                </h2>
                <p class="mt-2 text-sm sm:text-lg leading-8 text-gray-600">
                    Kodim 1609/Buleleng, Jl. Gajah Mada No.142, Banjar Jawa, Kecamatan Buleleng, Kabupaten Buleleng, Bali (81113)
                </p>
            </div>
            <div class="mt-16 grid grid-cols-1 overflow-hidden rounded-2xl border border-gray-100 bg-gray-50/50 sm:grid-cols-2">
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSf7dxVgO7Q-i5tugkslfZBkvQhAG9C5KJJ_ehVW2sDa_mrOGA/viewform" class="group flex flex-col items-center text-center bg-white p-10 border-b border-gray-100 sm:border-r sm:border-b-0 hover:bg-gray-50 transition-all duration-200">
                    <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-200">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                        </svg>
                    </div>
                    <span class="mt-4 text-base font-semibold leading-7 text-gray-900 group-hover:text-indigo-600 transition-colors">Buku Tamu</span>
                </a>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSfm3ySpxGaAIz0iigDbEbk9JCqJBOmMnIDnVMIXtYmaxekS6Q/viewform" class="group flex flex-col items-center text-center bg-white p-10 border-b border-gray-100 sm:border-r sm:border-b-0 hover:bg-gray-50 transition-all duration-200">
                    <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-200">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <span class="mt-4 text-base font-semibold leading-7 text-gray-900 group-hover:text-indigo-600 transition-colors">Laporan Kegiatan</span>
                </a>
            </div>
        </div>
    </section>

    <section id="administrasi-sppi">
        <div class="mx-auto max-w-7xl pt-16 pb-16 sm:pt-26 sm:pb-26 p-4">
            <div class="text-center">
                <h2 class="text-xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                    Administrasi SPPI
                </h2>
                <p class="mt-2 text-sm sm:text-lg leading-8 text-gray-600">
                    Akses cepat untuk manajemen administrasi dan pelaporan SPPI Buleleng.
                </p>
            </div>
            <div class="mt-16 grid grid-cols-1 overflow-hidden rounded-2xl border border-gray-100 bg-gray-50/50 sm:grid-cols-3">
                <a href="https://absensi-sppi-buleleng.vercel.app" class="group flex flex-col items-center text-center bg-white p-10 border-b border-gray-100 sm:border-r sm:border-b-0 hover:bg-gray-50 transition-all duration-200">
                    <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-200">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                        </svg>
                    </div>
                    <span class="mt-4 text-base font-semibold leading-7 text-gray-900 group-hover:text-indigo-600 transition-colors">Absensi SPPI</span>
                </a>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSd46l0mkUZbJtHlR4ylpLbqvxC8DRx79raB2DJf5cXiirHaqA/viewform" class="group flex flex-col items-center text-center bg-white p-10 border-b border-gray-100 sm:border-r sm:border-b-0 hover:bg-gray-50 transition-all duration-200">
                    <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-200">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <span class="mt-4 text-base font-semibold leading-7 text-gray-900 group-hover:text-indigo-600 transition-colors">Laporan Bulanan Magang</span>
                </a>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLScsOwFKy6HUK7JXmncyeq9TPrTe2CGbBriHzKoKkm82ElEgGQ/viewform" class="group flex flex-col items-center text-center bg-white p-10 hover:bg-gray-50 transition-all duration-200">
                    <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-200">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <span class="mt-4 text-base font-semibold leading-7 text-gray-900 group-hover:text-indigo-600 transition-colors">Laporan Khusus Magang</span>
                </a>
            </div>
        </div>
    </section>

    <section id="faq">
        <div class="mx-auto max-w-4xl pt-16 pb-16 sm:pt-26 sm:pb-26 p-4">
            <div class="text-center mb-8">
                <h2 class="text-xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                    Frequently Asked Questions
                </h2>
                <p class="mt-2 text-sm sm:text-lg leading-8 text-gray-600">
                    Akses cepat untuk manajemen administrasi dan pelaporan SPPI Buleleng.
                </p>
            </div>

            <div class="divide-y divide-gray-200 border-t border-gray-200">

                <div class="faq-item py-6">
                    <button class="faq-button flex w-full items-start justify-between text-left focus:outline-none">
                        <span class="text-base font-semibold leading-7 text-gray-900">What's the best thing about Switzerland?</span>
                        <span class="ml-6 flex h-7 items-center">
                            <svg class="faq-icon h-6 w-6 text-gray-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                            </svg>
                        </span>
                    </button>
                    <div class="faq-answer hidden mt-2 pr-12">
                        <p class="text-sm leading-6 text-gray-600">I don't know, but the flag is a big plus. Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                    </div>
                </div>

                <div class="faq-item py-6">
                    <button class="faq-button flex w-full items-start justify-between text-left focus:outline-none">
                        <span class="text-base font-semibold leading-7 text-gray-900">How do you make holy water?</span>
                        <span class="ml-6 flex h-7 items-center">
                            <svg class="faq-icon h-6 w-6 text-gray-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                            </svg>
                        </span>
                    </button>
                    <div class="faq-answer hidden mt-2 pr-12">
                        <p class="text-sm leading-6 text-gray-600">You boil the hell out of it. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>
                </div>

                <div class="faq-item py-6">
                    <button class="faq-button flex w-full items-start justify-between text-left focus:outline-none">
                        <span class="text-base font-semibold leading-7 text-gray-900">What do you call someone with no body and no nose?</span>
                        <span class="ml-6 flex h-7 items-center">
                            <svg class="faq-icon h-6 w-6 text-gray-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                            </svg>
                        </span>
                    </button>
                    <div class="faq-answer hidden mt-2 pr-12">
                        <p class="text-sm leading-6 text-gray-600">Nobody knows. A humorous take on a classic riddle to fill this space.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <x-footer />
</body>

</html>