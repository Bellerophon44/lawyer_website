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

  // Formulaire de rendez-vous : le POST est géré par api/rdv.php, sans JS.
  // Ici, seulement un garde-fou contre le double clic pendant l'envoi.
  const form = document.querySelector('form[action$="rdv.php"]');
  if (form) {
    form.addEventListener('submit', () => {
      const bouton = form.querySelector('button[type="submit"]');
      if (bouton) {
        bouton.disabled = true;
        bouton.textContent = 'Envoi en cours…';
      }
    });
  }
})();
