<nav id="main-navbar" class="fixed inset-x-0 top-0 z-50 transition-colors duration-300 bg-transparent">
    <div id="nav-desktop">
        <div id="nav-content" class="flex items-center justify-between p-4 lg:px-8">
            <div class="flex lg:flex-1">
                <a href="#" class="-m-1.5 p-1.5 flex items-center gap-3">
                    <img src="{{ asset('assets/images/logo-sppi.png') }}" alt="" class="h-12 w-12" />
                    <img src="{{ asset('assets/images/logo-bgn.png') }}" alt="" class="h-12 w-auto" />
                    <span class="sr-only font-semibold">SPPI Buleleng</span>
                </a>
            </div>
            <div class="flex lg:hidden">
                <button type="button" command="show-modal" commandfor="mobile-menu" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700 cursor-pointer">
                    <span class="sr-only">Open main menu</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                        <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
            <div class="hidden lg:flex lg:gap-x-12">
                <a href="#" class="text-sm/6 font-semibold text-darkblue hover:text-gold transition-colors">Beranda</a>
                <a href="#overview" class="text-sm/6 font-semibold text-darkblue hover:text-gold transition-colors">Sasaran</a>
                <div class="relative group">
                    <button type="button" class="flex items-center gap-x-1 text-sm/6 font-semibold text-darkblue hover:text-gold transition-colors focus:outline-none cursor-pointer">
                        Informasi
                        <svg class="size-5 flex-none text-gray-400 transition-transform group-hover:rotate-180" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div class="absolute -left-8 top-full z-10 mt-3 w-56 overflow-hidden rounded-3xl bg-white shadow-lg ring-1 ring-gray-900/5 transition-all opacity-0 translate-y-1 invisible group-hover:opacity-100 group-hover:translate-y-0 group-hover:visible">
                        <div class="p-4">
                            <a href="#services" class="block rounded-lg px-3 py-2 text-sm font-semibold text-darkblue hover:text-gold transition-colors hover:bg-gray-50">Layanan BGN</a>
                            <a href="#quick-info" class="block rounded-lg px-3 py-2 text-sm font-semibold text-darkblue hover:text-gold transition-colors hover:bg-gray-50">Informasi SPPG</a>
                            <a href="#geospatial" class="block rounded-lg px-3 py-2 text-sm font-semibold text-darkblue hover:text-gold transition-colors hover:bg-gray-50">Peta Geospasial</a>
                        </div>
                    </div>
                </div>
                <div class="relative group">
                    <button type="button" class="flex items-center gap-x-1 text-sm/6 font-semibold text-darkblue hover:text-gold transition-colors focus:outline-none cursor-pointer">
                        SPPI
                        <svg class="size-5 flex-none text-gray-400 transition-transform group-hover:rotate-180" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div class="absolute -left-8 top-full z-10 mt-3 w-56 overflow-hidden rounded-3xl bg-white shadow-lg ring-1 ring-gray-900/5 transition-all opacity-0 translate-y-1 invisible group-hover:opacity-100 group-hover:translate-y-0 group-hover:visible">
                        <div class="p-4">
                            <a href="#base-camp" class="block rounded-lg px-3 py-2 text-sm font-semibold text-darkblue hover:text-gold transition-colors hover:bg-gray-50">Posko SPPI</a>
                            <a href="#administration" class="block rounded-lg px-3 py-2 text-sm font-semibold text-darkblue hover:text-gold transition-colors hover:bg-gray-50">Administrasi SPPI</a>
                        </div>
                    </div>
                </div>
                <a href="#faq" class="text-sm/6 font-semibold text-darkblue hover:text-gold transition-colors">FAQ</a>
            </div>
            <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                <a href="{{ route('login') }}" class="text-sm/6 font-semibold bg-blue-500 hover:bg-gold text-white px-4 py-2 rounded-md transition-colors">Masuk</a>
            </div>
        </div>
    </div>

    <div id="nav-mobile">
        <dialog id="mobile-menu" class="backdrop:bg-transparent lg:hidden">
            <div tabindex="0" class="fixed inset-0 focus:outline-none">
                <el-dialog-panel class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white p-4 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                    <div class="flex items-center justify-between">
                        <a href="#" class="-m-1.5 p-1.5 flex items-center gap-3">
                            <img src="{{ asset('assets/images/logo-sppi.png') }}" alt="" class="h-12 w-12" />
                            <img src="{{ asset('assets/images/logo-bgn.png') }}" alt="" class="h-12 w-auto" />
                            <span class="sr-only font-semibold">SPPI Buleleng</span>
                        </a>
                        <button type="button" command="close" commandfor="mobile-menu" class="-m-2.5 rounded-md p-2.5 text-gray-700 cursor-pointer">
                            <span class="sr-only">Close menu</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                                <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                    <div class="mt-6 flow-root">
                        <div class="-my-6 divide-y divide-gray-500/10">
                            <div class="space-y-2 py-6">
                                <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-darkblue hover:text-gold transition-colors hover:bg-gray-50">Beranda</a>
                                <a href="#overview" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-darkblue hover:text-gold transition-colors hover:bg-gray-50">Sasaran</a>
                                <div class="-mx-3">
                                    <button type="button"
                                        onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('svg').classList.toggle('rotate-180')"
                                        class="flex w-full items-center justify-between rounded-lg py-2 pl-3 pr-3.5 text-base/7 font-semibold text-darkblue hover:text-gold transition-colors hover:bg-gray-50 cursor-pointer">
                                        Informasi
                                        <svg class="size-5 flex-none text-gray-400 transition-transform" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div class="hidden mt-2 space-y-2 pl-6">
                                        <a href="#services" class="block rounded-lg py-2 pl-3 pr-3 text-sm font-semibold text-darkblue hover:text-gold transition-colors hover:bg-gray-50">Layanan BGN</a>
                                        <a href="#quick-info" class="block rounded-lg py-2 pl-3 pr-3 text-sm font-semibold text-darkblue hover:text-gold transition-colors hover:bg-gray-50">Informasi SPPG</a>
                                        <a href="#geospatial" class="block rounded-lg py-2 pl-3 pr-3 text-sm font-semibold text-darkblue hover:text-gold transition-colors hover:bg-gray-50">Peta Geospasial</a>
                                    </div>
                                </div>
                                <div class="-mx-3">
                                    <button type="button"
                                        onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('svg').classList.toggle('rotate-180')"
                                        class="flex w-full items-center justify-between rounded-lg py-2 pl-3 pr-3.5 text-base/7 font-semibold text-darkblue hover:text-gold transition-colors hover:bg-gray-50 cursor-pointer">
                                        SPPI
                                        <svg class="size-5 flex-none text-gray-400 transition-transform" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div class="hidden mt-2 space-y-2 pl-6">
                                        <a href="#base-camp" class="block rounded-lg py-2 pl-3 pr-3 text-sm font-semibold text-darkblue hover:text-gold transition-colors hover:bg-gray-50">Posko SPPI</a>
                                        <a href="#administration" class="block rounded-lg py-2 pl-3 pr-3 text-sm font-semibold text-darkblue hover:text-gold transition-colors hover:bg-gray-50">Administrasi SPPI</a>
                                    </div>
                                </div>
                                <a href="#faq" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-darkblue hover:text-gold transition-colors hover:bg-gray-50">FAQ</a>
                            </div>
                            <div class="py-6">
                                <a href="{{ route('login') }}" class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-darkblue hover:text-gold transition-colors hover:bg-gray-50">Masuk</a>
                            </div>
                        </div>
                    </div>
                </el-dialog-panel>
            </div>
        </dialog>
    </div>
</nav>