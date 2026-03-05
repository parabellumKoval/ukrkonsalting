<?php
/**
 * Template part: Contact / Feedback form
 */
?>
<div class="form-wrap">
  <div class="form-title">Зворотній зв'язок</div>
  <p class="form-subtitle">Маєте питання? Напишіть нам — обов'язково відповімо</p>

  <form id="form-contact" novalidate>
    <?php wp_nonce_field('ukr_forms_nonce', 'ukr_nonce', false); ?>
    <input type="hidden" name="action" value="ukr_contact">

    <div class="form-row">
      <div class="form-group">
        <label for="ct-name">Ваше ім'я *</label>
        <input type="text" id="ct-name" name="name" placeholder="Ваше ім'я" required>
      </div>
      <div class="form-group">
        <label for="ct-phone">Телефон</label>
        <input type="tel" id="ct-phone" name="phone" placeholder="+38 (097) 000-00-00">
      </div>
    </div>

    <div class="form-group">
      <label for="ct-email">Email *</label>
      <input type="email" id="ct-email" name="email" placeholder="name@company.ua" required>
    </div>

    <div class="form-group">
      <label for="ct-message">Повідомлення *</label>
      <textarea id="ct-message" name="message" placeholder="Опишіть ваше питання або запит..." required></textarea>
    </div>

    <div class="form-group">
      <label class="form-check">
        <input type="checkbox" name="consent" required>
        <span>Я погоджуюсь з обробкою персональних даних</span>
      </label>
    </div>

    <button type="submit" class="form-submit">
      Надіслати повідомлення →
    </button>

    <div class="form-status" id="ct-status"></div>
  </form>
</div>