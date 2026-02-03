<nav class="absolute inset-x-0 top-0 z-50">
    <div id="nav-desktop">
        <div class="flex items-center justify-between p-6 lg:px-8">
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
        </div>
    </div>
    <div id="nav-mobile">
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
    </div>
</nav>