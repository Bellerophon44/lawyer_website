/* POC — Cabinet Coralie Schumpf
   Interactions minimales : scroll-driven reveal + tracking faux-clic. */

(() => {
  // Reveal au scroll
  if ('IntersectionObserver' in window) {
    const obs = new IntersectionObserver(
      (entries) => {
        entries.forEach((e) => {
          if (e.isIntersecting) {
            e.target.classList.add('is-visible');
            obs.unobserve(e.target);
          }
        });
      },
      { threshold: 0.12 }
    );
    document.querySelectorAll('.fade-up').forEach((el) => obs.observe(el));
  } else {
    document.querySelectorAll('.fade-up').forEach((el) => el.classList.add('is-visible'));
  }

  // Faux tracking pour la démo
  document.querySelectorAll('[data-track]').forEach((el) => {
    el.addEventListener('click', (e) => {
      const evt = el.dataset.track;
      console.log('[POC track]', evt);
    });
  });

  // Année footer
  document.querySelectorAll('[data-year]').forEach((el) => {
    el.textContent = new Date().getFullYear();
  });

  // Form diagnostic : préviens que c'est un POC
  const form = document.querySelector('[data-poc-form]');
  if (form) {
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      alert("POC — la prise de RDV serait envoyée ici (Wix Bookings ou Calendly).");
    });
  }
})();
