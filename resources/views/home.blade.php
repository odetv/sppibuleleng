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
        <div class="relative min-h-[calc(100vh)] max-w-7xl mx-auto flex items-center pt-12 pb-12 sm:pt-22 sm:pb-22 mt-8 p-4">
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
                    <p class="mt-8 text-sm text-gray-600 sm:text-lg leading-loose">SPPI untuk BGN merujuk pada program Sarjana Penggerak Pembangunan Indonesia, yang merekrut lulusan sarjana untuk menjadi ASN di Badan Gizi Nasional. Program ini bertujuan untuk memastikan setiap anak memiliki akses gizi yang layak dan menggarap aspek teknis, serta melibatkan peserta dalam pelatihan kepemimpinan dan dasar militer untuk penguatan ketahanan nasional.</p>
                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        <a href="#overview" class="rounded-md bg-blue-500 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-gold focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors">Jelajahi <span aria-hidden="true">&rarr;</span></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="overview">
        <div class="relative overflow-hidden py-16 sm:py-24">
            <div class="hidden lg:block absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/2 opacity-100 pointer-events-none">
                <img src="{{ asset('assets/images/pattern-dots.png') }}" alt=""
                    class="h-80 xl:h-120 2xl:h-150 w-auto object-contain transition-all duration-300">
            </div>

            <div class="container mx-auto px-4 lg:px-8 relative z-10">
                <div class="flex flex-col lg:grid lg:grid-cols-2 items-center gap-10 xl:gap-20">

                    <div class="flex justify-center order-1 lg:order-2">
                        <img src="{{ asset('assets/images/logo-bgn-circle.png') }}"
                            alt="Logo Badan Gizi Nasional"
                            class="h-36 w-36 sm:h-48 sm:w-48 lg:h-64 lg:w-64 xl:h-72 xl:w-72 drop-shadow-sm object-contain">
                    </div>

                    <div class="relative order-2 lg:order-1 text-center lg:text-left">
                        <div class="absolute -top-28 -left-28 lg:-top-16 lg:-left-16 xl:-top-25 xl:-left-25 opacity-10 -z-10 pointer-events-none">
                            <img src="{{ asset('assets/images/pattern-dots.png') }}"
                                alt=""
                                class="h-48 w-48 lg:h-48 lg:w-48 xl:h-56 xl:w-56 object-contain">
                        </div>

                        <h2 class="mb-4 text-sm lg:text-base xl:text-lg font-semibold uppercase tracking-wider text-gray-500">
                            Badan Gizi Nasional
                        </h2>
                        <h1 class="text-xl font-bold leading-tight text-darkblue sm:text-3xl xl:text-4xl">
                            Lembaga Non-Kementerian yang Berkomitmen pada Pemenuhan <span class="text-gold">Gizi Masyarakat Nasional</span>
                        </h1>

                        <p class="mt-4 text-sm text-gray-600 sm:text-base xl:text-lg leading-relaxed xl:leading-loose">
                            Badan Gizi Nasional (BGN) merupakan inisiatif strategis pemerintah Indonesia yang bertugas memastikan terpenuhinya kebutuhan gizi seluruh masyarakat. Kami berfokus pada peningkatan kualitas hidup melalui program yang terstruktur, terukur, dan berbasis data.
                        </p>

                        <div class="mt-8 flex justify-center lg:justify-start">
                            <div class="flex items-center gap-x-3 rounded-full bg-gray-50 px-4 py-2 ring-1 ring-inset ring-gray-200 hover:ring-gold text-gray-500 hover:text-gold transition-all">
                                <svg class="h-5 w-5 " fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                <a href="https://jdih.kemenkum.go.id/common/dokumen/2024perpres083.pdf" target="_blank" class="text-xs lg:text-sm font-medium">Perpres No. 83 Tahun 2024</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section id="target">
        <div class="relative overflow-hidden py-16 sm:py-24">
            <div class="absolute right-0 top-0 translate-x-1/3 -translate-y-1/4 opacity-10 lg:opacity-20 pointer-events-none">
                <img src="{{ asset('assets/images/pattern-double.png') }}"
                    alt=""
                    class="h-100 w-100 lg:h-200 lg:w-200 object-contain">
            </div>

            <div class="container mx-auto px-4 lg:px-8 relative z-10">
                <div class="mb-12">
                    <h2 class="mb-4 text-sm lg:text-lg font-semibold uppercase tracking-wider text-gray-500">Sasaran BGN</h2>
                    <h1 class="text-xl font-bold leading-tight text-darkblue sm:text-3xl">
                        Misi Badan Gizi Nasional (BGN) untuk <br class="hidden lg:block">
                        <span class="text-gold">Menuju Indonesia Emas</span>
                    </h1>
                    <p class="mt-4 max-w-3xl text-sm text-gray-600 sm:text-lg leading-loose">
                        Program komprehensif yang dirancang untuk memastikan setiap individu mendapatkan asupan gizi optimal.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">

                    <div class="group relative h-64 overflow-hidden rounded-3xl shadow-sm transition-all duration-300 hover:-translate-y-2">
                        <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=800&auto=format&fit=crop" alt="Peserta Didik" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-black/10 transition-colors group-hover:bg-black/20"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <div class="rounded-3xl p-4 text-center backdrop-blur-md shadow-lg">
                                <h4 class="text-lg font-bold text-white">Peserta Didik</h4>
                                <p class="text-xs font-medium text-white/90">SD, SMP, SMA Sederajat, Santri</p>
                            </div>
                        </div>
                    </div>

                    <div class="group relative h-64 overflow-hidden rounded-3xl shadow-sm transition-all duration-300 hover:-translate-y-2">
                        <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=800&auto=format&fit=crop" alt="Anak-anak" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-black/10 transition-colors group-hover:bg-black/20"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <div class="rounded-3xl p-4 text-center backdrop-blur-md shadow-lg">
                                <h4 class="text-lg font-bold text-white">Anak - Anak</h4>
                                <p class="text-xs font-medium text-white/90">Anak usia di bawah 5 tahun</p>
                            </div>
                        </div>
                    </div>

                    <div class="group relative h-64 overflow-hidden rounded-3xl shadow-sm transition-all duration-300 hover:-translate-y-2">
                        <img src="https://images.unsplash.com/flagged/photo-1551049215-23fd6d2ac3f1?q=80&w=876&auto=format&fit=crop" alt="Ibu Hamil & Menyusui" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-black/10 transition-colors group-hover:bg-black/20"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <div class="rounded-3xl p-4 text-center backdrop-blur-md shadow-lg">
                                <h4 class="text-lg font-bold text-white">Ibu Hamil & Menyusui</h4>
                                <p class="text-xs font-medium text-white/90">Gizi untuk Ibu Hamil & Menyusui</p>
                            </div>
                        </div>
                    </div>

                    <div class="group relative h-64 overflow-hidden rounded-3xl shadow-sm transition-all duration-300 hover:-translate-y-2">
                        <img src="https://images.unsplash.com/photo-1722963220475-979db2dbf216?q=80&w=1170&auto=format&fit=crop" alt="Kelompok Lainnya" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-black/10 transition-colors group-hover:bg-black/20"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <div class="rounded-3xl p-4 text-center backdrop-blur-md shadow-lg">
                                <h4 class="text-lg font-bold text-white">kelompok Lainnya</h4>
                                <p class="text-xs font-medium text-white/90">Pendidik & Tenaga Kependidikan</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section id="services">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-8">
                <h2 class="text-xl font-bold tracking-tight text-darkblue sm:text-3xl">
                    Layanan badan Gizi Nasional
                </h2>
                <p class="mt-2 text-sm sm:text-lg leading-8 text-gray-600">
                    Akses cepat ke portal atau web penting terkait Badan Gizi Nasional
                </p>
                <div class="mt-5 h-1.5 w-20 bg-gold mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6 text-center">

                <a href="https://bgn.go.id" target="_blank" class="group relative block overflow-hidden rounded-2xl bg-darkblue shadow-lg transition-all duration-300 hover:-translate-y-2">
                    <div class="aspect-2/1 w-full overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                    </div>

                    <div class="absolute inset-0 flex flex-col justify-end bg-linear-to-t from-darkblue from-0% via-darkblue/90 via-40% to-transparent to-70% p-6">

                        <div class="p-4 rounded-xl transition-all duration-300 group-hover:bg-darkblue/60">
                            <h3 class="text-lg font-bold text-white group-hover:text-gold transition-colors tracking-wide">
                                Website Resmi BGN
                            </h3>

                            <div class="grid grid-rows-[0fr] transition-all duration-500 group-hover:grid-rows-[1fr]">
                                <p class="overflow-hidden text-sm text-blue opacity-0 transition-all duration-500 group-hover:opacity-100 group-hover:mt-2">
                                    Sumber informasi tentang gizi dan kesehatan masyarakat Indonesia
                                </p>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="https://ppid.bgn.go.id" target="_blank" class="group relative block overflow-hidden rounded-2xl bg-darkblue shadow-lg transition-all duration-300 hover:-translate-y-2">
                    <div class="aspect-2/1 w-full overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                    </div>

                    <div class="absolute inset-0 flex flex-col justify-end bg-linear-to-t from-darkblue from-0% via-darkblue/90 via-40% to-transparent to-70% p-6">

                        <div class="p-4 rounded-xl transition-all duration-300 group-hover:bg-darkblue/60">
                            <h3 class="text-lg font-bold text-white group-hover:text-gold transition-colors tracking-wide">
                                PPID BGN
                            </h3>

                            <div class="grid grid-rows-[0fr] transition-all duration-500 group-hover:grid-rows-[1fr]">
                                <p class="overflow-hidden text-sm text-blue opacity-0 transition-all duration-500 group-hover:opacity-100 group-hover:mt-2">
                                    Sumber informasi mengenai Pejabat Pengelola Informasi & Dokumentasi di BGN
                                </p>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="https://mitra.bgn.go.id" target="_blank" class="group relative block overflow-hidden rounded-2xl bg-darkblue shadow-lg transition-all duration-300 hover:-translate-y-2">
                    <div class="aspect-2/1 w-full overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                    </div>

                    <div class="absolute inset-0 flex flex-col justify-end bg-linear-to-t from-darkblue from-0% via-darkblue/90 via-40% to-transparent to-70% p-6">

                        <div class="p-4 rounded-xl transition-all duration-300 group-hover:bg-darkblue/60">
                            <h3 class="text-lg font-bold text-white group-hover:text-gold transition-colors tracking-wide">
                                Portal Mitra BGN
                            </h3>

                            <div class="grid grid-rows-[0fr] transition-all duration-500 group-hover:grid-rows-[1fr]">
                                <p class="overflow-hidden text-sm text-blue opacity-0 transition-all duration-500 group-hover:opacity-100 group-hover:mt-2">
                                    Sumber informasi mengenai Pejabat Pengelola Informasi & Dokumentasi di BGN
                                </p>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="https://birosdmo.bgn.go.id" target="_blank" class="group relative block overflow-hidden rounded-2xl bg-darkblue shadow-lg transition-all duration-300 hover:-translate-y-2">
                    <div class="aspect-2/1 w-full overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                    </div>

                    <div class="absolute inset-0 flex flex-col justify-end bg-linear-to-t from-darkblue from-0% via-darkblue/90 via-40% to-transparent to-70% p-6">

                        <div class="p-4 rounded-xl transition-all duration-300 group-hover:bg-darkblue/60">
                            <h3 class="text-lg font-bold text-white group-hover:text-gold transition-colors tracking-wide">
                                Biro SDMO BGN
                            </h3>

                            <div class="grid grid-rows-[0fr] transition-all duration-500 group-hover:grid-rows-[1fr]">
                                <p class="overflow-hidden text-sm text-blue opacity-0 transition-all duration-500 group-hover:opacity-100 group-hover:mt-2">
                                    Sumber informasi mengenai Pejabat Pengelola Informasi & Dokumentasi di BGN
                                </p>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="https://dialur.bgn.go.id" target="_blank" class="group relative block overflow-hidden rounded-2xl bg-darkblue shadow-lg transition-all duration-300 hover:-translate-y-2">
                    <div class="aspect-2/1 w-full overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                    </div>

                    <div class="absolute inset-0 flex flex-col justify-end bg-linear-to-t from-darkblue from-0% via-darkblue/90 via-40% to-transparent to-70% p-6">

                        <div class="p-4 rounded-xl transition-all duration-300 group-hover:bg-darkblue/60">
                            <h3 class="text-lg font-bold text-white group-hover:text-gold transition-colors tracking-wide">
                                SMO BGN
                            </h3>

                            <div class="grid grid-rows-[0fr] transition-all duration-500 group-hover:grid-rows-[1fr]">
                                <p class="overflow-hidden text-sm text-blue opacity-0 transition-all duration-500 group-hover:opacity-100 group-hover:mt-2">
                                    Sistem Manajemen Operasional terintegrasi untuk mendukung operasional
                            </div>
                        </div>
                    </div>
                </a>

                <a href="https://tauwascare.tauwas.bgn.go.id" target="_blank" class="group relative block overflow-hidden rounded-2xl bg-darkblue shadow-lg transition-all duration-300 hover:-translate-y-2">
                    <div class="aspect-2/1 w-full overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                    </div>

                    <div class="absolute inset-0 flex flex-col justify-end bg-linear-to-t from-darkblue from-0% via-darkblue/90 via-40% to-transparent to-70% p-6">

                        <div class="p-4 rounded-xl transition-all duration-300 group-hover:bg-darkblue/60">
                            <h3 class="text-lg font-bold text-white group-hover:text-gold transition-colors tracking-wide">
                                Tauwas Care BGN
                            </h3>

                            <div class="grid grid-rows-[0fr] transition-all duration-500 group-hover:grid-rows-[1fr]">
                                <p class="overflow-hidden text-sm text-blue opacity-0 transition-all duration-500 group-hover:opacity-100 group-hover:mt-2">
                                    Platform untuk memantau dan mengelola data BGN
                            </div>
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </section>

    <section id="quick-info">
        <div class="relative bg-gray-900 mt-16 mb-16 pt-16 pb-16 sm:pt-26 sm:pb-26">
            <div class="absolute inset-0 z-0">
                <img
                    src={{ asset("assets/images/santap-mbg.jpg") }}
                    alt="Background"
                    class="h-full w-full object-cover opacity-30" />
                <div class="absolute inset-0 bg-linear-to-r from-purple-900/40 to-black/60"></div>
            </div>
            <div class="relative z-10 mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center">
                    <p class="text-xl font-bold tracking-tight text-white sm:text-3xl">
                        Informasi SPPG Terkini
                    </p>
                    <p class="mt-2 text-sm sm:text-lg leading-8 text-gray-300">
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

    <section id="geospatial">
        <div class="container relative bg-white mx-auto pt-16 pb-16 sm:pt-26 sm:pb-26 p-4">
            <div class="text-center">
                <h2 class="text-xl font-bold tracking-tight text-darkblue sm:text-3xl">
                    Peta Geospasial
                </h2>
                <p class="mt-2 text-sm sm:text-lg leading-8 text-gray-600">
                    Lokasi SPPG yang telah beroperasi di Kabupaten Buleleng
                </p>
                <div class="mt-5 h-1.5 w-20 bg-gold mx-auto rounded-full"></div>
            </div>

            <div class="mt-8 relative w-full h-64 sm:h-87.5 lg:h-128 overflow-hidden rounded-3xl bg-gray-100 shadow-lg ring-1 ring-gray-900/5">
                <iframe
                    src="https://www.google.com/maps/d/u/0/embed?mid=1IFi_W-d3Sl8eoFgKzJfcaJcjtLo2UfY&ehbc=2E312F"
                    width="100%"
                    height="100%"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>

    <section id="base-camp">
        <div class="mx-auto max-w-7xl pt-16 pb-16 sm:pt-26 sm:pb-26 p-4">
            <div class="text-center">
                <h2 class="text-xl font-bold tracking-tight text-darkblue sm:text-3xl">
                    Posko SPPI
                </h2>
                <p class="mt-2 text-sm sm:text-lg leading-8 text-gray-600">
                    Kodim 1609/Buleleng, Jl. Gajah Mada No.142, Banjar Jawa, Kecamatan Buleleng, Kabupaten Buleleng, Bali (81113)
                </p>
                <div class="mt-5 h-1.5 w-20 bg-gold mx-auto rounded-full"></div>
            </div>
            <div class="mt-8 grid grid-cols-1 overflow-hidden rounded-2xl border border-gray-100 bg-gray-50/50 sm:grid-cols-2">
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

    <section id="administration">
        <div class="mx-auto max-w-7xl pt-16 pb-16 sm:pt-26 sm:pb-26 p-4">
            <div class="text-center">
                <h2 class="text-xl font-bold tracking-tight text-darkblue sm:text-3xl">
                    Administrasi SPPI
                </h2>
                <p class="mt-2 text-sm sm:text-lg leading-8 text-gray-600">
                    Akses cepat untuk manajemen administrasi dan pelaporan SPPI Buleleng
                </p>
                <div class="mt-5 h-1.5 w-20 bg-gold mx-auto rounded-full"></div>
            </div>
            <div class="mt-8 grid grid-cols-1 overflow-hidden rounded-2xl border border-gray-100 bg-gray-50/50 sm:grid-cols-3">
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
                <h2 class="text-xl font-bold tracking-tight text-darkblue sm:text-3xl">
                    Frequently Asked Questions
                </h2>
                <p class="mt-2 text-sm sm:text-lg leading-8 text-gray-600">
                    Pertanyaan yang sering diajukan
                </p>
                <div class="mt-5 h-1.5 w-20 bg-gold mx-auto rounded-full"></div>
            </div>

            <div class="divide-y divide-gray-200 border-t border-gray-200">

                <div class="faq-item py-6">
                    <button class="cursor-pointer faq-button flex w-full items-start justify-between text-left focus:outline-none">
                        <span class="text-base font-semibold leading-7 text-gray-900">Apa itu BGN?</span>
                        <span class="ml-6 flex h-7 items-center">
                            <svg class="faq-icon h-6 w-6 text-gray-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                            </svg>
                        </span>
                    </button>
                    <div class="faq-answer hidden mt-2 pr-12">
                        <p class="text-sm leading-6 text-gray-600">Badan Gizi Nasional (BGN) adalah lembaga pemerintah yang dibentuk oleh Presiden untuk melaksanakan tugas pemenuhan gizi nasional, terutama bagi kelompok rentan seperti pelajar, balita, ibu hamil, dan ibu menyusui. Lembaga ini bertanggung jawab kepada Presiden dan memiliki tugas merumuskan serta menjalankan kebijakan gizi nasional untuk memastikan setiap warga mendapatkan asupan gizi yang aman, bergizi, dan seimbang.</p>
                    </div>
                </div>

                <div class="faq-item py-6">
                    <button class="cursor-pointer faq-button flex w-full items-start justify-between text-left focus:outline-none">
                        <span class="text-base font-semibold leading-7 text-gray-900">Apa itu SPPI?</span>
                        <span class="ml-6 flex h-7 items-center">
                            <svg class="faq-icon h-6 w-6 text-gray-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                            </svg>
                        </span>
                    </button>
                    <div class="faq-answer hidden mt-2 pr-12">
                        <p class="text-sm leading-6 text-gray-600">SPPI adalah Sarjana Penggerak Pembangunan Indonesia, sebuah program inisiatif dari Kementerian Pertahanan yang bertujuan untuk mencetak lulusan sarjana dengan kompetensi kepemimpinan dan teknis di bidang gizi masyarakat. Program ini dirancang untuk mendukung gerakan makan bergizi gratis, menyiapkan lulusan sebagai aparatur sipil negara (ASN), serta mengintegrasikan mereka ke dalam Komponen Cadangan (Komcad) untuk memperkuat pertahanan negara.</p>
                    </div>
                </div>

                <div class="faq-item py-6">
                    <button class="cursor-pointer faq-button flex w-full items-start justify-between text-left focus:outline-none">
                        <span class="text-base font-semibold leading-7 text-gray-900">Apa itu SPPG?</span>
                        <span class="ml-6 flex h-7 items-center">
                            <svg class="faq-icon h-6 w-6 text-gray-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                            </svg>
                        </span>
                    </button>
                    <div class="faq-answer hidden mt-2 pr-12">
                        <p class="text-sm leading-6 text-gray-600">SPPG adalah Satuan Pelayanan Pemenuhan Gizi, sebuah unit layanan yang bertugas mengolah dan mendistribusikan makanan bergizi sesuai standar, terutama untuk program pemerintah seperti Makan Bergizi Gratis (MBG). SPPG berfungsi sebagai dapur utama yang memastikan makanan yang disajikan memenuhi standar gizi, higienitas, dan keamanan pangan bagi kelompok sasaran seperti anak sekolah, ibu hamil, dan balita.</p>
                    </div>
                </div>

                <div class="faq-item py-6">
                    <button class="cursor-pointer faq-button flex w-full items-start justify-between text-left focus:outline-none">
                        <span class="text-base font-semibold leading-7 text-gray-900">Apa bedanya SPPG dan SPPI?</span>
                        <span class="ml-6 flex h-7 items-center">
                            <svg class="faq-icon h-6 w-6 text-gray-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                            </svg>
                        </span>
                    </button>
                    <div class="faq-answer hidden mt-2 pr-12">
                        <p class="text-sm leading-6 text-gray-600">SPPG adalah Satuan Pelayanan Pemenuhan Gizi, yaitu unit operasional yang menyediakan makanan bergizi gratis, sementara SPPI adalah Sarjana Penggerak Pembangunan Indonesia, yaitu program untuk merekrut sarjana yang perannya adalah memimpin dan mengelola SPPG. Perbedaannya adalah SPPG adalah unit kerjanya, sedangkan SPPI adalah orangnya yang menjadi kepala satuan tersebut.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <x-footer />
</body>

</html>