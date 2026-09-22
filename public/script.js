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

  const profileTrigger = document.getElementById('profileTrigger');
  const profileDropdown = document.getElementById('profileDropdown');

  if (profileTrigger && profileDropdown) {
    profileTrigger.addEventListener('click', (e) => {
      e.stopPropagation();
      const isOpen = profileDropdown.classList.toggle('open');
      profileTrigger.setAttribute('aria-expanded', String(isOpen));
    });

    document.addEventListener('click', (e) => {
      if (!profileDropdown.contains(e.target) && e.target !== profileTrigger) {
        profileDropdown.classList.remove('open');
        profileTrigger.setAttribute('aria-expanded', 'false');
      }
    });
  }

  const overlayNavbar = document.querySelector('.navbar-overlay');
  if (overlayNavbar) {
    const onScroll = () => {
      overlayNavbar.classList.toggle('scrolled', window.scrollY > 40);
    };
    // adia a leitura inicial de scrollY pra depois do primeiro layout da página,
    // evitando forçar um reflow síncrono ainda durante o carregamento
    requestAnimationFrame(onScroll);
    window.addEventListener('scroll', onScroll, { passive: true });
  }
});
