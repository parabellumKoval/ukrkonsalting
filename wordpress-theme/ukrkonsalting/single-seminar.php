<?php
/**
 * Single Seminar post template
 */
get_header();

if (!have_posts()) {
  get_footer();
  exit;
}

the_post();
$id = get_the_ID();
$meta = ukr_seminar_meta($id);

// ACF fields
$benefits = function_exists('get_field') ? (get_field('seminar_benefits', $id) ?: []) : [];
$legal_note = function_exists('get_field') ? get_field('seminar_legal', $id) : '';
$who_list = function_exists('get_field') ? (get_field('seminar_target', $id) ?: []) : [];
$programs = function_exists('get_field') ? (get_field('seminar_program', $id) ?: []) : [];
$speaker_post = function_exists('get_field') ? get_field('seminar_speaker', $id) : null;
if (is_numeric($speaker_post)) {
  $speaker_post = get_post((int) $speaker_post);
}
if (is_array($speaker_post) && isset($speaker_post[0])) {
  $speaker_post = get_post((int) $speaker_post[0]);
}

$speaker_name = '';
$speaker_org = '';
$speaker_bio = '';
$speaker_tags = [];

if ($speaker_post instanceof WP_Post) {
  $speaker_name = get_the_title($speaker_post);
  $speaker_org = function_exists('get_field') ? (string) get_field('speaker_org', $speaker_post->ID) : (string) get_post_meta($speaker_post->ID, 'speaker_org', true);
  $speaker_bio = function_exists('get_field') ? (string) get_field('speaker_bio', $speaker_post->ID) : (string) get_post_meta($speaker_post->ID, 'speaker_bio', true);
  $speaker_tags = function_exists('get_field') ? (get_field('speaker_tags', $speaker_post->ID) ?: []) : (get_post_meta($speaker_post->ID, 'speaker_tags', true) ?: []);
}

if (!$speaker_name) {
  $speaker_name = function_exists('get_field') ? (string) get_field('speaker_name', $id) : (string) get_post_meta($id, 'speaker_name', true);
  $speaker_org = function_exists('get_field') ? (string) get_field('speaker_org', $id) : (string) get_post_meta($id, 'speaker_org', true);
  $speaker_bio = function_exists('get_field') ? (string) get_field('speaker_bio', $id) : (string) get_post_meta($id, 'speaker_bio', true);
  $speaker_tags = function_exists('get_field') ? (get_field('speaker_tags', $id) ?: []) : (get_post_meta($id, 'speaker_tags', true) ?: []);
}

$what_get = function_exists('get_field') ? (get_field('what_you_get', $id) ?: []) : [];
$faq = function_exists('get_field') ? (get_field('seminar_faq', $id) ?: []) : [];

$get_text_field = static function (int $post_id, string $field_name, string $default = ''): string {
  $value = '';

  if (function_exists('get_field')) {
    $field_value = get_field($field_name, $post_id);
    if (is_string($field_value)) {
      $value = trim($field_value);
    }
  }

  if ($value === '') {
    $meta_value = get_post_meta($post_id, $field_name, true);
    if (is_string($meta_value)) {
      $value = trim($meta_value);
    }
  }

  return $value !== '' ? $value : $default;
};

$get_repeater_field = static function (int $post_id, string $field_name, array $default = []): array {
  $value = [];

  if (function_exists('get_field')) {
    $field_value = get_field($field_name, $post_id);
    if (is_array($field_value)) {
      $value = $field_value;
    }
  }

  if (empty($value)) {
    $meta_value = get_post_meta($post_id, $field_name, true);
    if (is_array($meta_value)) {
      $value = $meta_value;
    }
  }

  return !empty($value) ? $value : $default;
};

$allowed_title_html = [
  'br' => [],
  'em' => [],
  'strong' => [],
  'span' => [],
];

$render_title = static function (string $value) use ($allowed_title_html): string {
  return wp_kses($value, $allowed_title_html);
};

// Fallback to Global Settings (Options Page)
if (empty($benefits) && function_exists('get_field')) {
  $benefits = get_field('global_benefits', 'options') ?: [];
}
if (empty($what_get) && function_exists('get_field')) {
  $what_get = get_field('global_what_get', 'options') ?: [];
}
if (empty($faq) && function_exists('get_field')) {
  $faq = get_field('global_faq', 'options') ?: [];
}

// Keep lists empty when not configured; avoid seminar-specific hardcoded content.

$date_str = $meta['date'] ? ukr_format_date($meta['date']) : '';
$price_fmt = $meta['price'] ? number_format((float)$meta['price'], 0, '.', ' ') : '2 000';
$hero_preamble = trim((string) ($meta['preamble'] ?? ''));
if ($hero_preamble === '') {
  $hero_preamble = trim((string) ($meta['description'] ?? ''));
}
if ($hero_preamble === '') {
  $hero_preamble = get_the_excerpt();
}

$hero_primary_cta = $get_text_field($id, 'hero_primary_cta', 'Записатись на семінар');
$hero_secondary_cta = $get_text_field($id, 'hero_secondary_cta', 'Дивитись програму ↓');
$hero_meta_certificate = $get_text_field($id, 'hero_meta_certificate', 'Сертифікат участі');
$hero_meta_materials = $get_text_field($id, 'hero_meta_materials', 'Методичні матеріали');

$price_note = $get_text_field($id, 'seminar_price_note', 'ПДВ не передбачений · Документи за запитом');
$price_cta = $get_text_field($id, 'seminar_price_cta', 'Записатись →');
$price_features = $get_repeater_field($id, 'seminar_price_features', [
  ['feature' => '✓ Матеріали включено'],
  ['feature' => '✓ Сертифікат'],
  ['feature' => '✓ Q&A сесія'],
  ['feature' => '✓ Консультація'],
]);

$benefits_kicker = $get_text_field($id, 'benefits_kicker', 'Про семінар');
$benefits_title = $get_text_field($id, 'benefits_title', 'Навіщо цей семінар<br><em>вашому підприємству</em>');
$benefits_lead = $get_text_field($id, 'benefits_lead', 'Програма з максимально практичною спрямованістю — реальні кейси, актуальне законодавство і розбір питань конкретного підприємства.');

$who_kicker = $get_text_field($id, 'who_kicker', 'Для кого');
$who_title = $get_text_field($id, 'who_title', 'Цільова <em>аудиторія</em>');
$who_lead = $get_text_field($id, 'who_lead', 'Семінар розроблений для фахівців промислових підприємств.');

$program_kicker = $get_text_field($id, 'program_kicker', 'Програма навчання');
$program_title = $get_text_field($id, 'program_title', 'Теми — від закону<br><em>до практики</em>');
$program_lead = $get_text_field($id, 'program_lead', 'Натисніть на тему для перегляду деталей.');

$speaker_kicker = $get_text_field($id, 'speaker_kicker', 'Викладач');
$speaker_title = $get_text_field($id, 'speaker_title', 'Хто проводить<br><em>семінар</em>');

$what_get_kicker = $get_text_field($id, 'what_get_kicker', 'Що включено');
$what_get_title = $get_text_field($id, 'what_get_title', 'Що ви отримаєте<br><em>після семінару</em>');
$what_get_lead = $get_text_field($id, 'what_get_lead', 'Все необхідне для підвищення кваліфікації включено у вартість участі.');

$faq_kicker = $get_text_field($id, 'faq_kicker', 'Часті запитання');
$faq_title = $get_text_field($id, 'faq_title', 'Відповіді на<br><em>популярні питання</em>');

$corp_title = $get_text_field($id, 'corp_title', 'Потрібно навчити<br>цілу <em>команду?</em>');
$corp_text = $get_text_field($id, 'corp_text', 'Організуємо корпоративний семінар для вашого підприємства за фіксованою вартістю — онлайн або з виїздом спеціаліста.');
$corp_pills = $get_repeater_field($id, 'corp_pills', [
  ['pill' => '📹 Zoom або виїзд'],
  ['pill' => '👥 Будь-яка кількість учасників'],
  ['pill' => '🗓 Зручна дата для вас'],
  ['pill' => '📍 Вся Україна'],
  ['pill' => '🏭 Під умови вашого виробництва'],
]);

$register_kicker = $get_text_field($id, 'register_kicker', 'Реєстрація');
$register_title = $get_text_field($id, 'register_title', 'Готові записатись<br><em>на семінар?</em>');
$register_lead = $get_text_field($id, 'register_lead', 'Залиште заявку зручним для вас способом.');
$register_note_intro = $get_text_field($id, 'register_note_intro', 'Також доступні Telegram та Viber · Відповідаємо');
$register_note_price_label = $get_text_field($id, 'register_note_price_label', 'Вартість:');
$register_note_price_suffix = $get_text_field($id, 'register_note_price_suffix', 'без ПДВ · Документи за запитом');
?>

<!-- Breadcrumb -->
<div class="bc-wrap">
  <div class="bc">
    <a href="<?php echo esc_url(home_url('/')); ?>">Головна</a><span class="bc-sep"> › </span>
    <a href="<?php echo esc_url(home_url('/seminars/')); ?>">Семінари</a><span class="bc-sep"> › </span>
    <span class="bc-current">
      <?php the_title(); ?>
    </span>
  </div>
</div>

<!-- HERO -->
<section class="hero--event">
  <div class="hero-inner">
    <div class="hero-eyebrow hero-eyebrow--event">
      <div class="hero-eyebrow-dot"></div>
      <span>
        <?php echo esc_html($meta['eyebrow'] ?: 'Онлайн-семінар · Zoom · Сертифікат'); ?>
      </span>
    </div>
    <h1>
      <?php the_title(); ?>
    </h1>
    <p class="hero-desc">
      <?php echo esc_html($hero_preamble); ?>
    </p>

    <div class="hero-meta">
      <?php if ($date_str): ?>
      <div class="hm-pill"><span>📅</span>
        <?php echo esc_html($date_str); ?>
      </div>
      <?php
endif; ?>
      <?php if ($meta['time']): ?>
      <div class="hm-pill"><span>🕙</span> Початок о
        <?php echo esc_html($meta['time']); ?>
      </div>
      <?php
endif; ?>
      <div class="hm-pill"><span>📹</span>
        <?php echo esc_html($meta['format'] ?: 'Zoom · Онлайн'); ?>
      </div>
      <div class="hm-pill"><span>🎓</span>
        <?php echo esc_html($hero_meta_certificate); ?>
      </div>
      <div class="hm-pill"><span>📄</span>
        <?php echo esc_html($hero_meta_materials); ?>
      </div>
    </div>

    <div class="hero-ctas">
      <a href="#register" class="btn-gold">
        <?php echo esc_html($hero_primary_cta); ?>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
          stroke-linecap="round">
          <path d="M5 12h14M12 5l7 7-7 7" />
        </svg>
      </a>
      <a href="#program" class="btn-ghost-w">
        <?php echo esc_html($hero_secondary_cta); ?>
      </a>
    </div>
  </div>
</section>

<!-- PRICE STRIP -->
<div class="price-strip reveal">
  <div class="price-strip-inner">
    <div>
      <div class="price-main">
        <div class="price-val">
          <?php echo esc_html($price_fmt); ?>
        </div>
        <div class="price-cur">грн</div>
      </div>
      <div class="price-note">
        <?php echo esc_html($price_note); ?>
      </div>
    </div>
    <div class="price-features">
      <?php foreach ($price_features as $feature):
  $feature_text = is_array($feature) ? ($feature['feature'] ?? '') : $feature;
  if (!$feature_text) {
    continue;
  }
?>
      <div class="pf">
        <?php echo esc_html($feature_text); ?>
      </div>
      <?php
endforeach; ?>
    </div>
    <a href="#register" class="btn-gold-sm">
      <?php echo esc_html($price_cta); ?>
    </a>
  </div>
</div>

<!-- BENEFITS -->
<section class="benefits-bg">
  <div class="sec-wrap wide">
    <div class="reveal">
      <div class="sec-kicker">
        <?php echo esc_html($benefits_kicker); ?>
      </div>
      <h2 class="sec-title">
        <?php echo $render_title($benefits_title); ?>
      </h2>
      <p class="sec-lead">
        <?php echo esc_html($benefits_lead); ?>
      </p>
    </div>
    <div class="benefits-grid">
      <?php foreach ($benefits as $i => $b):
  $delay = 'reveal-delay-' . (($i % 4) + 1);
?>
      <div class="benefit-card reveal <?php echo esc_attr($delay); ?>">
        <span class="bc-icon">
          <?php echo esc_html($b['bc_icon'] ?? $b['icon'] ?? '✅'); ?>
        </span>
        <h4>
          <?php echo esc_html($b['title']); ?>
        </h4>
        <p>
          <?php echo esc_html($b['text']); ?>
        </p>
      </div>
      <?php
endforeach; ?>
    </div>

    <?php if ($legal_note): ?>
    <div class="legal-box reveal reveal-delay-2">
      <span class="legal-icon">📋</span>
      <p>
        <?php echo wp_kses_post($legal_note); ?>
      </p>
    </div>
    <?php
else: ?>
    <div class="legal-box reveal reveal-delay-2">
      <span class="legal-icon">📋</span>
      <p><?php echo esc_html__('Деталі участі та нормативні вимоги уточнюйте у менеджера або в описі програми семінару.', 'ukrkonsalting'); ?></p>
    </div>
    <?php
endif; ?>
  </div>
</section>

<div class="sec-divider">
  <div class="sec-divider-line"></div>
</div>

<!-- WHO -->
<section class="who-bg">
  <div class="sec-wrap wide">
    <div class="reveal">
      <div class="sec-kicker">
        <?php echo esc_html($who_kicker); ?>
      </div>
      <h2 class="sec-title">
        <?php echo $render_title($who_title); ?>
      </h2>
      <p class="sec-lead">
        <?php echo esc_html($who_lead); ?>
      </p>
    </div>
    <div class="who-list">
      <?php foreach ($who_list as $i => $w):
  $delay = 'reveal-delay-' . (($i % 3) + 1);
?>
      <div class="who-card reveal <?php echo esc_attr($delay); ?>">
        <div class="who-num">
          <?php echo esc_html($w['num'] ?? sprintf('%02d', $i + 1)); ?>
        </div>
        <div>
          <h4>
            <?php echo esc_html($w['title']); ?>
          </h4>
          <p>
            <?php echo esc_html($w['text']); ?>
          </p>
        </div>
      </div>
      <?php
endforeach; ?>
    </div>
  </div>
</section>

<div class="sec-divider">
  <div class="sec-divider-line"></div>
</div>

<!-- PROGRAM -->
<section class="program-bg" id="program">
  <div class="sec-wrap wide">
    <div class="reveal">
      <div class="sec-kicker">
        <?php echo esc_html($program_kicker); ?>
      </div>
      <h2 class="sec-title">
        <?php echo $render_title($program_title); ?>
      </h2>
      <p class="sec-lead">
        <?php echo esc_html($program_lead); ?>
      </p>
    </div>
    <div class="program-stack reveal reveal-delay-1" id="programStack">
      <?php if (!empty($programs)):
  foreach ($programs as $i => $prog): ?>
      <div class="prog-item <?php echo ($i === 0) ? 'open' : ''; ?>">
        <button class="prog-trigger" onclick="toggleProg(this)">
          <div class="prog-badge">
            <?php echo esc_html($i + 1); ?>
          </div>
          <div class="prog-label">
            <div class="prog-theme">
              <?php echo esc_html($prog['prog_theme'] ?? 'Тема ' . ($i + 1)); ?>
            </div>
            <div class="prog-heading">
              <?php echo esc_html($prog['prog_heading'] ?? ''); ?>
            </div>
          </div>
          <div class="prog-chevron"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2.5" stroke-linecap="round">
              <path d="M6 9l6 6 6-6" />
            </svg></div>
        </button>
        <div class="prog-body">
          <ul>
            <?php
    $items = $prog['prog_items'] ?? [];
    if (is_array($items))
      foreach ($items as $item) {
        $text = is_string($item) ? $item : ($item['item'] ?? '');
        if ($text)
          echo '<li><div class="prog-dot"></div>' . esc_html($text) . '</li>';
      }
?>
          </ul>
          <?php if (!empty($prog['prog_note'])): ?>
          <div class="prog-note">
            <?php echo esc_html($prog['prog_note']); ?>
          </div>
          <?php
    endif; ?>
        </div>
      </div>
      <?php
  endforeach;
else: ?>
      <p class="sec-lead"><?php echo esc_html__('Програма семінару буде опублікована найближчим часом.', 'ukrkonsalting'); ?></p>
      <?php
endif; ?>
    </div>
  </div>
</section>

<div class="sec-divider">
  <div class="sec-divider-line"></div>
</div>

<!-- SPEAKER -->
<section>
  <div class="sec-wrap">
    <div class="reveal">
      <div class="sec-kicker">
        <?php echo esc_html($speaker_kicker); ?>
      </div>
      <h2 class="sec-title">
        <?php echo $render_title($speaker_title); ?>
      </h2>
    </div>
    <?php if ($speaker_name): ?>
    <div class="speaker-wrap reveal reveal-delay-1">
      <div class="speaker-ava">
        <?php echo esc_html(mb_strtoupper(mb_substr($speaker_name, 0, 1))); ?>
      </div>
      <div>
        <div class="speaker-name">
          <?php echo esc_html($speaker_name); ?>
        </div>
        <?php if ($speaker_org): ?>
        <div class="speaker-org">
          <?php echo esc_html($speaker_org); ?>
        </div>
        <?php endif; ?>
        <?php if ($speaker_bio): ?>
        <p class="speaker-bio">
          <?php echo esc_html($speaker_bio); ?>
        </p>
        <?php endif; ?>
        <?php if (!empty($speaker_tags)):
          foreach ($speaker_tags as $tag): ?>
        <span class="speaker-tag">
          <?php echo esc_html(is_array($tag) ? $tag['tag'] : $tag); ?>
        </span>
        <?php endforeach;
        endif; ?>
      </div>
    </div>
    <?php else: ?>
    <p class="sec-lead reveal reveal-delay-1"><?php echo esc_html__('Інформація про викладача буде додана найближчим часом.', 'ukrkonsalting'); ?></p>
    <?php endif; ?>
  </div>
</section>
<div class="sec-divider">
  <div class="sec-divider-line"></div>
</div>

<!-- WHAT YOU GET -->
<section class="get-bg">
  <div class="sec-wrap wide">
    <div class="reveal">
      <div class="sec-kicker">
        <?php echo esc_html($what_get_kicker); ?>
      </div>
      <h2 class="sec-title">
        <?php echo $render_title($what_get_title); ?>
      </h2>
      <p class="sec-lead">
        <?php echo esc_html($what_get_lead); ?>
      </p>
    </div>
    <div class="get-grid">
      <?php foreach ($what_get as $i => $g):
  $delay = 'reveal-delay-' . (($i % 3) + 1);
?>
      <div class="get-card reveal <?php echo esc_attr($delay); ?>">
        <span class="get-icon">
          <?php echo esc_html($g['get_icon'] ?? $g['icon'] ?? '✅'); ?>
        </span>
        <h4>
          <?php echo esc_html($g['title']); ?>
        </h4>
        <p>
          <?php echo esc_html($g['text']); ?>
        </p>
      </div>
      <?php
endforeach; ?>
    </div>
  </div>
</section>

<div class="sec-divider">
  <div class="sec-divider-line"></div>
</div>

<!-- FAQ -->
<section>
  <div class="sec-wrap">
    <div class="reveal">
      <div class="sec-kicker">
        <?php echo esc_html($faq_kicker); ?>
      </div>
      <h2 class="sec-title">
        <?php echo $render_title($faq_title); ?>
      </h2>
    </div>
    <div class="faq-stack reveal reveal-delay-1" id="faqList">
      <?php foreach ($faq as $i => $f): ?>
      <div class="faq-item <?php echo ($i === 0) ? 'open' : ''; ?>">
        <button class="faq-trigger" onclick="toggleFaq(this)">
          <span class="faq-q-text">
            <?php echo esc_html($f['question']); ?>
          </span>
          <div class="faq-icon">+</div>
        </button>
        <div class="faq-answer">
          <?php echo esc_html($f['answer']); ?>
        </div>
      </div>
      <?php
endforeach; ?>
    </div>
  </div>
</section>

<div class="sec-divider">
  <div class="sec-divider-line"></div>
</div>

<!-- CORPORATE -->
<section class="corp-bg">
  <div class="corp-inner">
    <div class="corp-box reveal">
      <h2>
        <?php echo $render_title($corp_title); ?>
      </h2>
      <p>
        <?php echo esc_html($corp_text); ?>
      </p>
      <div class="corp-pills">
        <?php foreach ($corp_pills as $pill):
  $pill_text = is_array($pill) ? ($pill['pill'] ?? '') : $pill;
  if (!$pill_text) {
    continue;
  }
?>
        <span class="corp-pill">
          <?php echo esc_html($pill_text); ?>
        </span>
        <?php
endforeach; ?>
      </div>
      <div class="corp-ctas">
        <a href="tel:+<?php echo preg_replace('/[^0-9]/', '', ukr_option('phone', '380972144205')); ?>"
          class="btn-gold-lg">
          📞
          <?php echo esc_html(ukr_option('phone', '(097) 214-42-05')); ?>
        </a>
        <a href="mailto:<?php echo esc_attr(ukr_option('email', 'uktc@ukr.net')); ?>" class="btn-outline-w">
          ✉
          <?php echo esc_html(ukr_option('email', 'uktc@ukr.net')); ?>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- REGISTER FORM -->
<section class="register-bg" id="register">
  <div class="sec-wrap" style="text-align:center;">
    <div class="reveal">
      <div class="sec-kicker">
        <?php echo esc_html($register_kicker); ?>
      </div>
      <h2 class="sec-title">
        <?php echo $render_title($register_title); ?>
      </h2>
      <p class="sec-lead" style="margin:0 auto 48px;">
        <?php echo esc_html($register_lead); ?>
      </p>
    </div>

    <div class="contact-cards reveal reveal-delay-1">
      <a href="tel:+<?php echo preg_replace('/[^0-9]/', '', ukr_option('phone', '380972144205')); ?>"
        class="contact-card">
        <span class="cc-icon">📞</span>
        <div class="cc-label">Телефон</div>
        <div class="cc-val">
          <?php echo esc_html(ukr_option('phone', '(097) 214-42-05')); ?>
        </div>
      </a>
      <a href="mailto:<?php echo esc_attr(ukr_option('email', 'uktc@ukr.net')); ?>" class="contact-card">
        <span class="cc-icon">✉️</span>
        <div class="cc-label">Email</div>
        <div class="cc-val">
          <?php echo esc_html(ukr_option('email', 'uktc@ukr.net')); ?>
        </div>
      </a>
    </div>

    <div style="margin-top:32px; text-align:left; max-width:640px; margin:32px auto 0;">
      <?php get_template_part('template-parts/forms/seminar-registration', null, ['id_suffix' => 'single', 'selected_seminar' => (string) $id]); ?>
    </div>

    <p class="register-note reveal reveal-delay-2" style="margin-top:24px;">
      <?php echo esc_html($register_note_intro); ?>
      <?php echo esc_html(' ' . ukr_option('work_hours', 'Пн–Пт')); ?><br>
      <?php echo esc_html(trim($register_note_price_label . ' ' . $price_fmt . ' грн ' . $register_note_price_suffix)); ?>
    </p>
  </div>
</section>

<?php get_footer(); ?>
