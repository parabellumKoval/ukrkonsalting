/**
 * УкрКонсалтинг — Main JavaScript
 * Handles: mobile nav, scroll reveal, accordions, AJAX forms
 */

'use strict';

// ─── Mobile Nav Toggle ───────────────────────────────────────────────────────

(function () {
  const burger = document.getElementById('nav-burger');
  const links = document.getElementById('nav-links');
  if (!burger || !links) return;

  burger.addEventListener('click', function () {
    const isOpen = links.classList.toggle('is-open');
    burger.classList.toggle('is-open', isOpen);
    burger.setAttribute('aria-expanded', String(isOpen));
  });

  // Close on outside click
  document.addEventListener('click', function (e) {
    if (!burger.contains(e.target) && !links.contains(e.target)) {
      links.classList.remove('is-open');
      burger.classList.remove('is-open');
      burger.setAttribute('aria-expanded', 'false');
    }
  });
})();

// ─── Sticky Nav Background ────────────────────────────────────────────────────

(function () {
  const nav = document.getElementById('site-nav');
  if (!nav) return;
  const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 20);
  window.addEventListener('scroll', onScroll, { passive: true });
})();

// ─── Scroll Reveal ────────────────────────────────────────────────────────────

(function () {
  const els = document.querySelectorAll('.reveal');
  if (!els.length) return;

  const io = new IntersectionObserver(
    (entries) => {
      entries.forEach((e) => {
        if (e.isIntersecting) {
          e.target.classList.add('visible');
          io.unobserve(e.target);
        }
      });
    },
    { threshold: 0.1, rootMargin: '0px 0px -60px 0px' }
  );
  els.forEach((el) => io.observe(el));
})();

// ─── Program Accordion ────────────────────────────────────────────────────────

function toggleProg(btn) {
  const item = btn.closest('.prog-item');
  const isOpen = item.classList.contains('open');
  document.querySelectorAll('.prog-item').forEach((i) => i.classList.remove('open'));
  if (!isOpen) item.classList.add('open');
}

// ─── FAQ Accordion ────────────────────────────────────────────────────────────

function toggleFaq(btn) {
  btn.closest('.faq-item').classList.toggle('open');
}

// ─── Registration Modal ──────────────────────────────────────────────────────

(function () {
  const modal = document.getElementById('registration-modal');
  if (!modal) return;

  const closeModal = () => {
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('modal-open');
    window.setTimeout(() => {
      modal.hidden = true;
    }, 180);
  };

  const openModal = () => {
    modal.hidden = false;
    modal.setAttribute('aria-hidden', 'false');
    modal.classList.add('is-open');
    document.body.classList.add('modal-open');
  };

  document.addEventListener('click', function (e) {
    const opener = e.target.closest('.js-open-registration-modal');
    if (opener) {
      e.preventDefault();
      openModal();
      return;
    }

    if (modal.hidden) return;
    if (e.target.closest('[data-modal-close]')) {
      e.preventDefault();
      closeModal();
    }
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !modal.hidden) {
      closeModal();
    }
  });
})();

// ─── AJAX Form Submission ─────────────────────────────────────────────────────

function ukrSubmitForm(formOrId, statusId = null, btnId = null) {
  const form = typeof formOrId === 'string' ? document.getElementById(formOrId) : formOrId;
  const resolvedStatusId = statusId || form?.dataset?.statusId || null;
  const resolvedBtnId = btnId || form?.dataset?.submitId || null;
  const status = resolvedStatusId ? document.getElementById(resolvedStatusId) : null;
  const btn = resolvedBtnId ? document.getElementById(resolvedBtnId) : null;

  if (!form || !status) return;
  if (form.dataset.ukrBound === '1') return;
  form.dataset.ukrBound = '1';

  form.addEventListener('submit', async function (e) {
    e.preventDefault();

    // Simple validation
    const required = form.querySelectorAll('[required]');
    let valid = true;
    required.forEach((f) => {
      if (f.type === 'checkbox' && !f.checked) valid = false;
      if (f.type !== 'checkbox' && !f.value.trim()) valid = false;
    });
    if (!valid) {
      status.className = 'form-status error';
      status.textContent = '❌ Заповніть всі обов\'язкові поля.';
      return;
    }

    // Send
    if (btn) { btn.disabled = true; btn.textContent = 'Надсилаємо...'; }
    status.className = 'form-status';

    const data = new FormData(form);
    data.set('nonce', window.ukrAjax?.nonce || '');

    try {
      const res = await fetch(window.ukrAjax?.url || '/wp-admin/admin-ajax.php', {
        method: 'POST',
        body: data,
      });
      const json = await res.json();

      if (json.success) {
        status.className = 'form-status success';
        status.textContent = json.data?.message || '✅ Дякуємо!';
        form.reset();
        const seminarSelect = form.querySelector('select[name="seminar"][data-default-value]');
        if (seminarSelect) {
          seminarSelect.value = seminarSelect.dataset.defaultValue || '';
        }
      } else {
        status.className = 'form-status error';
        status.textContent = json.data?.message || '❌ Помилка. Спробуйте ще раз.';
      }
    } catch {
      status.className = 'form-status error';
      status.textContent = '❌ Помилка мережі. Перевірте з\'єднання і спробуйте ще раз.';
    } finally {
      if (btn) { btn.disabled = false; btn.textContent = 'Надіслати заявку →'; }
    }
  });
}

// ─── Init Forms ───────────────────────────────────────────────────────────────

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.js-ukr-registration-form').forEach((form) => {
    ukrSubmitForm(form);
  });
  ukrSubmitForm('form-contact', 'ct-status', null);
  ukrSubmitForm('form-newsletter', 'nl-status', null);
});
