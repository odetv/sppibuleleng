import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker
      .register('/sw.js')
      .then((reg) => console.log('PWA: Service Worker terdaftar', reg.scope))
      .catch((err) => console.error('PWA: Registrasi gagal', err));
  });
}

document.addEventListener('DOMContentLoaded', () => {
  let deferredPrompt;
  const banner = document.getElementById('pwa-install-banner');
  const btnInstall = document.getElementById('pwa-btn-install');
  const btnClose = document.getElementById('pwa-btn-close');
  const isInstalled = localStorage.getItem('pwaInstalled') === 'true';
  window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    if (!isInstalled && banner) {
      banner.classList.remove('hidden');
      setTimeout(() => banner.classList.add('opacity-100'), 10);
    }
  });
  if (btnInstall) {
    btnInstall.addEventListener('click', async () => {
      if (deferredPrompt) {
        deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;
        if (outcome === 'accepted') {
          localStorage.setItem('pwaInstalled', 'true');
          banner.classList.add('hidden');
        }
        deferredPrompt = null;
      }
    });
  }
  if (btnClose) {
    btnClose.addEventListener('click', () => {
      banner.classList.add('hidden');
      localStorage.setItem('pwaInstalled', 'true');
    });
  }
  window.addEventListener('appinstalled', () => {
    localStorage.setItem('pwaInstalled', 'true');
    if (banner) banner.classList.add('hidden');
  });
});

document.addEventListener('DOMContentLoaded', () => {
  const faqButtons = document.querySelectorAll('.faq-button');
  faqButtons.forEach((button) => {
    button.addEventListener('click', () => {
      const item = button.closest('.faq-item');
      const answer = item.querySelector('.faq-answer');
      const icon = item.querySelector('.faq-icon');
      // Cek apakah item ini sudah terbuka
      const isOpen = !answer.classList.contains('hidden');
      // Tutup semua FAQ lainnya (Opsional: hapus bagian ini jika ingin bisa buka banyak sekaligus)
      document
        .querySelectorAll('.faq-answer')
        .forEach((el) => el.classList.add('hidden'));
      document.querySelectorAll('.faq-icon').forEach((el) => {
        el.innerHTML =
          '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />';
      });
      // Toggle item yang diklik
      if (!isOpen) {
        answer.classList.remove('hidden');
        // Ubah ikon jadi Minus (-)
        icon.innerHTML =
          '<path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" />';
      } else {
        answer.classList.add('hidden');
        // Ubah ikon jadi Plus (+)
        icon.innerHTML =
          '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />';
      }
    });
  });
});

document.addEventListener('DOMContentLoaded', () => {
  const navbar = document.getElementById('main-navbar');
  const mobileMenu = document.getElementById('mobile-menu');
  // 1. Fungsi Toggle Background
  const toggleNavbarBg = () => {
    if (window.scrollY > 20) {
      navbar.classList.add(
        'bg-white/80',
        'backdrop-blur-md',
        'shadow-md',
        'border-b',
        'border-gray-100'
      );
      navbar.classList.remove('bg-transparent', 'border-transparent');
    } else {
      navbar.classList.remove(
        'bg-white/80',
        'backdrop-blur-md',
        'shadow-md',
        'border-b',
        'border-gray-100'
      );
      navbar.classList.add('bg-transparent', 'border-transparent');
    }
  };
  // 2. Logika Mobile Menu (Accordion & Reset)
  const mobileLinks = document.querySelectorAll('#nav-mobile a');
  const dropdownButtons = document.querySelectorAll(
    '#nav-mobile button[onclick*="nextElementSibling.classList.toggle"]'
  );
  // Fungsi Helper untuk Reset semua dropdown ke kondisi tertutup
  const resetDropdowns = () => {
    dropdownButtons.forEach((btn) => {
      const panel = btn.nextElementSibling;
      const icon = btn.querySelector('svg');
      if (panel) panel.classList.add('hidden');
      if (icon) icon.classList.remove('rotate-180');
    });
  };
  // Logika Accordion: Tutup menu lain saat satu dibuka
  dropdownButtons.forEach((btn) => {
    btn.addEventListener('click', () => {
      dropdownButtons.forEach((otherBtn) => {
        if (otherBtn !== btn) {
          const otherPanel = otherBtn.nextElementSibling;
          const otherIcon = otherBtn.querySelector('svg');
          if (otherPanel) otherPanel.classList.add('hidden');
          if (otherIcon) otherIcon.classList.remove('rotate-180');
        }
      });
    });
  });
  // RESET saat menu ditutup agar saat dibuka lagi sudah tertutup semua
  mobileMenu.addEventListener('close', () => {
    resetDropdowns();
  });
  // Penutup otomatis saat link (a) diklik
  mobileLinks.forEach((link) => {
    link.addEventListener('click', () => {
      if (mobileMenu.open) {
        mobileMenu.close();
      }
    });
  });
  window.addEventListener('scroll', toggleNavbarBg);
  toggleNavbarBg();
});
