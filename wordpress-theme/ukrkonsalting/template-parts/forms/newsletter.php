<?php
/**
 * Template part: Newsletter subscription strip
 */
?>
<div class="newsletter-strip">
  <div class="newsletter-inner">
    <div class="newsletter-text">
      <h3>📬 Підписка на новини та знижки</h3>
      <p>Отримуйте інформацію про нові семінари, зміни законодавства та спеціальні пропозиції</p>
    </div>

    <form class="newsletter-form" id="form-newsletter" novalidate>
      <?php wp_nonce_field('ukr_forms_nonce', 'ukr_nonce', false); ?>
      <input type="hidden" name="action" value="ukr_newsletter">
      <input type="email" name="email" placeholder="Ваш email" required aria-label="Email для підписки">
      <button type="submit" class="btn-primary" style="white-space:nowrap;">
        Підписатись
      </button>
    </form>
  </div>
  <div id="nl-status" class="form-status" style="max-width:1200px;margin:12px auto 0;padding:0 48px;"></div>
</div>