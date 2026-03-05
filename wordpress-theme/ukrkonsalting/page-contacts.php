<?php
/**
 * Page template: Contacts
 * Template Name: Contacts
 */
get_header();

$phone = ukr_option('phone', '(097) 214-42-05');
$email = ukr_option('email', 'uktc@ukr.net');
$wh = ukr_option('work_hours', 'Пн–Пт, 9:00–18:00');
$address = ukr_option('address', 'Україна');
$page_title = function_exists('get_field') ? (get_field('contact_page_title') ?: 'Зв\'яжіться з нами') : 'Зв\'яжіться з нами';
$page_subtitle = function_exists('get_field') ? (get_field('contact_page_subtitle') ?: 'Відповідаємо швидко') : 'Відповідаємо швидко';
?>

<!-- Compact green hero -->
<section style="background:var(--green-deep);padding:64px 48px;text-align:center;position:relative;overflow:hidden;">
  <div
    style="position:absolute;inset:0;background:radial-gradient(ellipse at 70% 30%,rgba(74,128,96,.25),transparent 55%);">
  </div>
  <div style="position:relative;z-index:1;max-width:700px;margin:0 auto;">
    <div
      style="display:inline-flex;align-items:center;gap:8px;background:rgba(201,168,76,.15);border:1px solid rgba(201,168,76,.3);border-radius:100px;padding:6px 16px;margin-bottom:20px;font-size:12px;font-weight:600;color:var(--gold-light);letter-spacing:.5px;">
      Контакти</div>
    <h1
      style="font-family:var(--font-serif,'Lora',serif);font-size:clamp(32px,5vw,52px);font-weight:700;color:#fff;margin-bottom:14px;line-height:1.15;">
      <?php echo esc_html($page_title); ?>
    </h1>
    <p style="font-size:17px;color:rgba(255,255,255,.6);font-weight:300;">
      <?php echo esc_html($page_subtitle); ?> ·
      <?php echo esc_html($wh); ?>
    </p>
  </div>
</section>

<!-- Contact cards grid -->
<div style="max-width:900px;margin:0 auto;padding:64px 48px;">
  <div class="contact-cards reveal" style="grid-template-columns:repeat(3,1fr);">
    <a href="tel:+<?php echo preg_replace('/[^0-9]/', '', $phone); ?>" class="contact-card">
      <span class="cc-icon">📞</span>
      <div class="cc-label">Телефон</div>
      <div class="cc-val">
        <?php echo esc_html($phone); ?>
      </div>
    </a>
    <a href="mailto:<?php echo esc_attr($email); ?>" class="contact-card">
      <span class="cc-icon">✉️</span>
      <div class="cc-label">Email</div>
      <div class="cc-val">
        <?php echo esc_html($email); ?>
      </div>
    </a>
    <div class="contact-card">
      <span class="cc-icon">💬</span>
      <div class="cc-label">Месенджери</div>
      <div class="cc-val">Telegram · Viber</div>
    </div>
  </div>

  <div style="margin-top:16px;text-align:center;font-size:14px;color:var(--text-light);">
    📍
    <?php echo esc_html($address); ?> &nbsp;·&nbsp; ⏰
    <?php echo esc_html($wh); ?>
  </div>

  <!-- Contact form -->
  <div style="margin-top:56px;" class="reveal reveal-delay-1">
    <?php get_template_part('template-parts/forms/contact'); ?>
  </div>
</div>

<?php get_footer(); ?>