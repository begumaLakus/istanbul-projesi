// AOS scroll animasyon başlatma
AOS.init({
    duration: 1000,
    once: true,
  });
  document.addEventListener('DOMContentLoaded', function () {
    const scrollText = document.querySelector('.scroll-text');
  
    function checkScroll() {
      const rect = scrollText.getBoundingClientRect();
      const windowHeight = window.innerHeight || document.documentElement.clientHeight;
  
      if (rect.top <= windowHeight * 0.9) {
        scrollText.classList.add('active');
        window.removeEventListener('scroll', checkScroll);
      }
    }
  
    window.addEventListener('scroll', checkScroll);
    checkScroll();
  });
  