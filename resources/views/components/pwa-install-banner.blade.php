<style>
    /* Animasi putaran cahaya gold */
    @keyframes pwa-rotate-light {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    #pwa-install-banner {
        transform: translateX(-50%);
    }

    .pwa-border-wrapper {
        position: relative;
        padding: 2px;
        overflow: hidden;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pwa-border-wrapper::before {
        content: '';
        position: absolute;
        width: 180%;
        height: 180%;
        background: conic-gradient(transparent, transparent, transparent, #FFD700, #FFD700);
        animation: pwa-rotate-light 3s linear infinite;
    }

    .pwa-content-inner {
        position: relative;
        width: 100%;
        background: #003366;
        border-radius: 14px;
        z-index: 1;
    }
</style>

<div id="pwa-install-banner" class="hidden fixed bottom-5 left-1/2 z-9999 w-auto max-w-[95%] transition-all duration-500">
    <div class="pwa-border-wrapper">
        <div class="pwa-content-inner p-4 px-4 flex items-center justify-start gap-8 text-white">

            <div class="flex items-center gap-3">
                <div class="text-xl">🚀</div>
                <div class="whitespace-nowrap">
                    <p class="font-bold text-sm leading-tight">Install Aplikasi</p>
                    <p class="text-[10px] text-blue-100/80 leading-tight italic">Akses Mudah Portal SPPI Buleleng</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button id="pwa-btn-install" class="bg-white text-[#003366] px-2 py-2 rounded-lg text-xs font-black cursor-pointer hover:bg-gold transition-all active:scale-90">
                    OK
                </button>
                <button id="pwa-btn-close" class="text-blue-200 hover:text-gold text-lg cursor-pointer transition-colors leading-none">
                    ✕
                </button>
            </div>

        </div>
    </div>
</div>