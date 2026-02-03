<footer class="bg-white text-gray-600 py-12 px-6 border-t border-gray-200">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8 mb-12">

            <div class="col-span-2 lg:col-span-1">
                <div class="flex flex-row items-center justify-start gap-2 pb-4">
                    <img src="{{ asset('assets/images/logo-sppi.png') }}" alt="SPPI Logo" class="w-12 h-12">
                    <img src="{{ asset('assets/images/logo-bgn.png') }}" alt="BGN Logo" class="w-28 h-full">
                </div>
                <p class="text-sm leading-relaxed mb-6 max-w-xs">
                    Website SPPI Kabupaten Buleleng Bali menyediakan informasi terkini mengenai
                    operasional SPPG dan data penerima manfaat.
                </p>
                <div class="flex gap-5 text-gray-400">
                    <a href="#" class="hover:text-black transition-colors">Fb</a>
                    <a href="#" class="hover:text-black transition-colors">Ig</a>
                    <a href="#" class="hover:text-black transition-colors">X</a>
                    <a href="#" class="hover:text-black transition-colors">Gh</a>
                    <a href="#" class="hover:text-black transition-colors">Yt</a>
                </div>
            </div>

            <div>
                <h3 class="text-gray-900 font-bold mb-6">Solutions</h3>
                <ul class="space-y-4 text-sm">
                    <li><a href="#" class="hover:text-black transition-colors">Marketing</a></li>
                    <li><a href="#" class="hover:text-black transition-colors">Analytics</a></li>
                    <li><a href="#" class="hover:text-black transition-colors">Automation</a></li>
                    <li><a href="#" class="hover:text-black transition-colors">Commerce</a></li>
                    <li><a href="#" class="hover:text-black transition-colors">Insights</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-gray-900 font-bold mb-6">Support</h3>
                <ul class="space-y-4 text-sm">
                    <li><a href="#" class="hover:text-black transition-colors">Submit ticket</a></li>
                    <li><a href="#" class="hover:text-black transition-colors">Documentation</a></li>
                    <li><a href="#" class="hover:text-black transition-colors">Guides</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-gray-900 font-bold mb-6">Company</h3>
                <ul class="space-y-4 text-sm">
                    <li><a href="#" class="hover:text-black transition-colors">About</a></li>
                    <li><a href="#" class="hover:text-black transition-colors">Blog</a></li>
                    <li><a href="#" class="hover:text-black transition-colors">Jobs</a></li>
                    <li><a href="#" class="hover:text-black transition-colors">Press</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-gray-900 font-bold mb-6">Legal</h3>
                <ul class="space-y-4 text-sm">
                    <li><a href="#" class="hover:text-black transition-colors">Terms of service</a></li>
                    <li><a href="#" class="hover:text-black transition-colors">Privacy policy</a></li>
                    <li><a href="#" class="hover:text-black transition-colors">License</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-8">
            <p class="text-xs text-gray-400">
                © {{ now()->format('Y') }} - Tim Data SPPI Buleleng Bali
            </p>
        </div>
    </div>
</footer>