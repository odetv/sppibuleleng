import './bootstrap';

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
  window.addEventListener('scroll', toggleNavbarBg);
  toggleNavbarBg();
});
