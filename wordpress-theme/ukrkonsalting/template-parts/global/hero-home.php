<?php
/**
 * Template: Hero section (homepage)
 */

// Get next/upcoming seminar for hero card
$next_seminar = new WP_Query([
  'post_type' => 'seminar',
  'posts_per_page' => 1,
  'post_status' => 'publish',
  'meta_key' => 'seminar_date',
  'orderby' => 'meta_value',
  'order' => 'ASC',
]);
$has_seminar = $next_seminar->have_posts();
if ($has_seminar) {
  $next_seminar->the_post();
  $sid = get_the_ID();
  $meta = ukr_seminar_meta($sid);
  $title = get_the_title();
  $link = get_permalink();
  // Get program items for hero card (first 4 from acf repeater or excerpt)
  $programs = function_exists('get_field') ? (get_field('seminar_program', $sid) ?: []) : [];
  $price = $meta['price'] ? number_format((float)$meta['price'], 0, '.', ' ') . ' грн' : '2 000 грн';
  wp_reset_postdata();
}
?>

<div style="max-width:1200px; margin:0 auto;">
  <div class="hero-home fade-up">
    <!-- Left: text -->
    <div>
      <div class="hero-eyebrow fade-up delay-1">
        <span></span>
        Онлайн-семінари · Zoom · Сертифікат
      </div>

      <h1 class="fade-up delay-1">
        Навчання з<br>
        <em>охорони довкілля</em><br>
        для фахівців
      </h1>

      <p class="hero-desc fade-up delay-2">
        Практичні семінари для екологів, головних інженерів та відповідальних за охорону навколишнього середовища. 22
        роки досвіду, 1000+ проведених заходів.
      </p>

      <div class="hero-actions fade-up delay-3">
        <a href="<?php echo esc_url(home_url('/seminars/')); ?>" class="btn-primary">
          Переглянути програми
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14M12 5l7 7-7 7" />
          </svg>
        </a>
        <a href="tel:+<?php echo preg_replace('/[^0-9]/', '', ukr_option('phone', '380972144205')); ?>"
          class="btn-ghost">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
            stroke-linecap="round" stroke-linejoin="round">
            <path
              d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.63 19.79 19.79 0 01.5 1.02 2 2 0 012.5 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.18 6.18l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z" />
          </svg>
          <?php echo esc_html(ukr_option('phone', '(097) 214-42-05')); ?>
        </a>
      </div>
    </div>

    <!-- Right: hero card (next seminar) -->
    <?php if ($has_seminar): ?>
    <div class="hero-card fade-up delay-2">
      <div class="hero-card-tag">Найближчий семінар</div>
      <h3>
        <?php echo esc_html($title); ?>
      </h3>

      <ul class="hero-card-list">
        <?php if (!empty($programs)):
    $items = [];
    foreach (array_slice($programs, 0, 4) as $prog) {
      $items[] = $prog['prog_heading'] ?? '';
    }
    foreach (array_filter($items) as $item): ?>
        <li>
          <div class="check">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#2D5A42" stroke-width="3"
              stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12" />
            </svg>
          </div>
          <?php echo esc_html($item); ?>
        </li>
        <?php
    endforeach;
  else: ?>
        <li>
          <div class="check"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#2D5A42"
              stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12" />
            </svg></div>Нормативно-правова база 2024–2025
        </li>
        <li>
          <div class="check"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#2D5A42"
              stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12" />
            </svg></div>Практичні рекомендації
        </li>
        <li>
          <div class="check"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#2D5A42"
              stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12" />
            </svg></div>Відповіді на питання
        </li>
        <li>
          <div class="check"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#2D5A42"
              stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12" />
            </svg></div>Методичні матеріали + сертифікат
        </li>
        <?php
  endif; ?>
      </ul>

      <div class="hero-card-price">
        <div>
          <div class="price-label">Вартість участі</div>
          <div class="price-value">
            <?php echo esc_html($price); ?>
          </div>
          <div class="price-sub">ПДВ не передбачений</div>
        </div>
        <?php if (!empty($meta['format'])): ?>
        <div style="background:rgba(255,255,255,.12);border-radius:10px;padding:8px 14px;text-align:center;">
          <div style="font-size:11px;color:var(--green-pale);margin-bottom:2px;">Формат</div>
          <div style="font-size:13px;color:white;font-weight:500;">
            <?php echo esc_html($meta['format']); ?>
          </div>
          <div style="font-size:11px;color:var(--green-pale);">онлайн</div>
        </div>
        <?php
  endif; ?>
      </div>
    </div>
    <?php
endif; ?>
  </div>
</div>