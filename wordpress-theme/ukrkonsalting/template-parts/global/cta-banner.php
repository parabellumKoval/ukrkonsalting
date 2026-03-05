<?php
/**
 * Template part: CTA banner
 */
?>
<div class="cta-banner reveal">
  <div class="cta-inner">
    <div class="cta-text">
      <h2>Запишіться на наступний<br>семінар вже зараз</h2>
      <p>Залиште заявку — ми повідомимо про дату наступної групи та надішлемо програму</p>
    </div>
    <div class="cta-actions">
      <button class="btn-light"
        onclick="document.getElementById('registration-form').scrollIntoView({behavior:'smooth'})">
        Залишити заявку
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
          stroke-linecap="round" stroke-linejoin="round">
          <path d="M5 12h14M12 5l7 7-7 7" />
        </svg>
      </button>
      <a href="tel:+<?php echo preg_replace('/[^0-9]/', '', ukr_option('phone', '380972144205')); ?>"
        class="btn-outline-light">
        📞
        <?php echo esc_html(ukr_option('phone', '(097) 214-42-05')); ?>
      </a>
    </div>
  </div>
</div>

<!-- Registration form (shown after CTA on homepage) -->
<div id="registration-form" style="max-width:700px;margin:0 auto 96px;padding:0 48px;" class="reveal">
  <?php get_template_part('template-parts/forms/seminar-registration'); ?>
</div>