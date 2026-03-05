<?php
/**
 * Template part: Contact strip
 */
$phone = ukr_option('phone', '(097) 214-42-05');
$email = ukr_option('email', 'uktc@ukr.net');
$wh = ukr_option('work_hours', 'Пн–Пт, 9:00–18:00');
?>
<div class="contact-strip">
  <div class="contact-strip-inner">
    <div class="contact-info">
      <div class="contact-item">
        <div class="contact-item-icon">📞</div>
        <div>
          <div class="contact-item-label">Телефон</div>
          <div class="contact-item-value"><a href="tel:+<?php echo preg_replace('/[^0-9]/', '', $phone); ?>">
              <?php echo esc_html($phone); ?>
            </a></div>
        </div>
      </div>
      <div class="contact-item">
        <div class="contact-item-icon">✉️</div>
        <div>
          <div class="contact-item-label">Email</div>
          <div class="contact-item-value"><a href="mailto:<?php echo esc_attr($email); ?>">
              <?php echo esc_html($email); ?>
            </a></div>
        </div>
      </div>
      <div class="contact-item">
        <div class="contact-item-icon">💬</div>
        <div>
          <div class="contact-item-label">Месенджери</div>
          <div class="contact-item-value">Telegram · Viber</div>
        </div>
      </div>
    </div>
    <div style="font-size:13px; color:var(--text-light); text-align:right;">
      <div style="margin-bottom:4px;">Відповідаємо
        <?php echo esc_html($wh); ?>
      </div>
      <div style="color:var(--green-soft); font-weight:500;">Корпоративне навчання на замовлення</div>
    </div>
  </div>
</div>