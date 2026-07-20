// ---------- Fade-in on scroll ----------
(function () {
  var els = document.querySelectorAll('.fade-in');
  if (!('IntersectionObserver' in window) || !els.length) {
    els.forEach(function (el) { el.classList.add('is-visible'); });
    return;
  }
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        io.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12 });
  els.forEach(function (el) { io.observe(el); });
})();

// ---------- Lightbox ----------
(function () {
  var lightbox = document.getElementById('lightbox');
  if (!lightbox) return;
  var imgEl = lightbox.querySelector('[data-lightbox-img]');
  var titleEl = lightbox.querySelector('[data-lightbox-title]');
  var authorEl = lightbox.querySelector('[data-lightbox-author]');
  var descEl = lightbox.querySelector('[data-lightbox-desc]');

  document.querySelectorAll('[data-open-lightbox]').forEach(function (card) {
    card.addEventListener('click', function () {
      imgEl.src = card.getAttribute('data-full');
      imgEl.alt = card.getAttribute('data-title') || '';
      titleEl.textContent = card.getAttribute('data-title') || '';
      authorEl.textContent = card.getAttribute('data-author') || '';
      descEl.textContent = card.getAttribute('data-desc') || '';
      lightbox.classList.add('is-open');
      document.body.style.overflow = 'hidden';
    });
  });

  function close() {
    lightbox.classList.remove('is-open');
    document.body.style.overflow = '';
  }
  lightbox.querySelector('[data-lightbox-close]').addEventListener('click', close);
  lightbox.addEventListener('click', function (e) {
    if (e.target === lightbox) close();
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') close();
  });
})();

// ---------- Contact form (AJAX -> /contact.php -> Telegram) ----------
(function () {
  var form = document.getElementById('contact-form');
  if (!form) return;
  var msgEl = form.querySelector('[data-form-msg]');
  var btn = form.querySelector('.submit-btn');

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    msgEl.textContent = '';
    msgEl.className = 'form-msg';
    btn.disabled = true;

    fetch('/contact.php', {
      method: 'POST',
      body: new FormData(form),
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
    })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (data.ok) {
          msgEl.textContent = data.message || 'Спасибо! Сообщение отправлено.';
          msgEl.classList.add('ok');
          form.reset();
        } else {
          msgEl.textContent = data.message || 'Что-то пошло не так, попробуйте ещё раз.';
          msgEl.classList.add('err');
        }
      })
      .catch(function () {
        msgEl.textContent = 'Не получилось отправить. Попробуйте ещё раз.';
        msgEl.classList.add('err');
      })
      .finally(function () {
        btn.disabled = false;
      });
  });
})();
