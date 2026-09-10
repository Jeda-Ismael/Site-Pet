document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.getElementById('navToggle');
  const menu = document.getElementById('navMenu');

  if (toggle && menu) {
    toggle.addEventListener('click', () => {
      const isOpen = menu.classList.toggle('open');
      toggle.setAttribute('aria-expanded', String(isOpen));
    });

    menu.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        menu.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
      });
    });
  }

  const navCat = document.getElementById('navCat');
  if (navCat && window.lottie) {
    lottie.loadAnimation({
      container: navCat,
      renderer: 'svg',
      loop: true,
      autoplay: true,
      path: 'loader-cat.json',
    });
  }

  const heroCat = document.getElementById('heroCat');
  if (heroCat && window.lottie) {
    lottie.loadAnimation({
      container: heroCat,
      renderer: 'svg',
      loop: true,
      autoplay: true,
      path: 'loader-cat.json',
    });
  }
});
