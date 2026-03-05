<?php
if (!defined('ABSPATH')) {
  exit;
}

/**
 * One-time migration (v4):
 * - keep only individual seminar data on post #10
 * - move universal seminar UI/settings from post #10 into global options
 */
add_action('init', 'ukr_migrate_seminar_architecture_v4');
function ukr_migrate_seminar_architecture_v4(): void
{
  if (get_option('ukr_seminar_migration_v4_done')) {
    return;
  }

  $seminar_id = 10;
  $seminar = get_post($seminar_id);
  if (!$seminar || $seminar->post_type !== 'seminar') {
    return;
  }

  $speaker_id = ukr_ensure_default_speaker();

  // P1 individual fields that should remain on seminar posts.
  $individual_defaults = [
    'seminar_preamble' => 'Семінар для інженерно-технічного персоналу з практичним розбором актуальних нормативних змін і виробничих кейсів.',
    'seminar_date' => '',
    'seminar_time' => '',
    'seminar_format' => 'Zoom',
    'seminar_price' => 2000,
    'seminar_program' => [
      ['prog_theme' => 'Тема 1', 'prog_heading' => 'Нормативно-правові акти. Відповідальність підприємств', 'prog_items' => [['item' => 'Огляд чинного законодавства'], ['item' => 'Новації 2025–2026 рр.'], ['item' => 'Відповідальність за порушення'], ['item' => 'Повноваження контролюючих органів']], 'prog_note' => 'Орієнтовно 1.5–2 години з можливістю запитань'],
      ['prog_theme' => 'Тема 2 · Основна', 'prog_heading' => 'Правила технічної експлуатації ГОУ — Наказ №52', 'prog_items' => [['item' => 'Вимоги до суб\'єктів господарювання'], ['item' => 'Розробка паспорту ГОУ'], ['item' => 'Розробка інструкцій з експлуатації'], ['item' => 'Перевірка ефективності та оцінка технічного стану']], 'prog_note' => 'Основна практична тема. Рекомендується надіслати питання заздалегідь'],
    ],
    'seminar_speaker' => $speaker_id,
  ];

  foreach ($individual_defaults as $field_key => $default_value) {
    $existing = get_post_meta($seminar_id, $field_key, true);
    $value = ($existing !== '' && $existing !== null) ? $existing : $default_value;

    if (function_exists('update_field')) {
      update_field($field_key, $value, $seminar_id);
    }
    update_post_meta($seminar_id, $field_key, $value);
  }

  // Universal fields moved from seminar post to global options.
  $global_defaults = [
    'seminar_eyebrow' => 'Онлайн-семінар · Zoom · Сертифікат',
    'hero_primary_cta' => 'Записатись на семінар',
    'hero_secondary_cta' => 'Дивитись програму ↓',
    'hero_meta_certificate' => 'Сертифікат участі',
    'hero_meta_materials' => 'Методичні матеріали',
    'seminar_price_note' => 'ПДВ не передбачений · Документи за запитом',
    'seminar_price_cta' => 'Записатись →',
    'seminar_price_features' => [
      ['feature' => '✓ Матеріали включено'],
      ['feature' => '✓ Сертифікат'],
      ['feature' => '✓ Q&A сесія'],
      ['feature' => '✓ Консультація'],
    ],
    'benefits_kicker' => 'Про семінар',
    'benefits_title' => 'Навіщо цей семінар<br><em>вашому підприємству</em>',
    'benefits_lead' => 'Програма з максимально практичною спрямованістю — реальні кейси, актуальне законодавство і розбір питань конкретного підприємства.',
    'global_benefits' => [
      ['bc_icon' => '⚖️', 'title' => 'Новації законодавства', 'text' => 'Повний огляд останніх змін у нормативно-правових актах.'],
      ['bc_icon' => '🔧', 'title' => 'Практика вимог', 'text' => 'Детальний розбір вимог до суб\'єктів господарювання.'],
      ['bc_icon' => '💬', 'title' => 'Відповіді на питання', 'text' => 'Надсилайте питання заздалегідь або задавайте напряму під час Q&A.'],
      ['bc_icon' => '🏆', 'title' => 'Право перевірки знань', 'text' => 'Після навчання ваші спеціалісти можуть брати участь у комісіях.'],
    ],
    'seminar_legal' => 'Відповідно до <strong>п. 2.2.4 Правил технічної експлуатації ГОУ</strong> — технічне навчання та перевірка знань інженерно-технічного персоналу проводяться не рідше одного разу на 3 роки, обслуговуючого персоналу — не рідше одного разу на рік.',
    'who_kicker' => 'Для кого',
    'who_title' => 'Цільова <em>аудиторія</em>',
    'who_lead' => 'Семінар розроблений для фахівців промислових підприємств.',
    'global_target' => [
      ['num' => '01', 'title' => 'Служба головного інженера', 'text' => 'Відповідальні за технічний стан та документацію.'],
      ['num' => '02', 'title' => 'Служба головного енергетика', 'text' => 'Фахівці з технічної експлуатації обладнання.'],
      ['num' => '03', 'title' => 'Екологічна служба', 'text' => 'Екологи та відповідальні за охорону навколишнього середовища.'],
    ],
    'program_kicker' => 'Програма навчання',
    'program_title' => 'Теми — від закону<br><em>до практики</em>',
    'program_lead' => 'Натисніть на тему для перегляду деталей.',
    'speaker_kicker' => 'Викладач',
    'speaker_title' => 'Хто проводить<br><em>семінар</em>',
    'what_get_kicker' => 'Що включено',
    'what_get_title' => 'Що ви отримаєте<br><em>після семінару</em>',
    'what_get_lead' => 'Все необхідне для підвищення кваліфікації включено у вартість участі.',
    'global_what_get' => [
      ['get_icon' => '📂', 'title' => 'Методичні матеріали', 'text' => 'В електронному або друкованому вигляді.'],
      ['get_icon' => '🏅', 'title' => 'Сертифікат участі', 'text' => 'Офіційне підтвердження підвищення кваліфікації.'],
      ['get_icon' => '🖥', 'title' => 'Ілюстративні слайди', 'text' => 'Повна презентація лектора у цифровому форматі.'],
    ],
    'faq_kicker' => 'Часті запитання',
    'faq_title' => 'Відповіді на<br><em>популярні питання</em>',
    'global_faq' => [
      ['question' => 'Чи надається запис семінару після заходу?', 'answer' => 'Запис не надається — семінар проводиться у форматі живої взаємодії для Q&A і консультацій.'],
      ['question' => 'Як отримати посилання на Zoom?', 'answer' => 'Після реєстрації та підтвердження оплати посилання надсилається на email не пізніше ніж за один день до початку.'],
    ],
    'corp_title' => 'Потрібно навчити<br>цілу <em>команду?</em>',
    'corp_text' => 'Організуємо корпоративний семінар для вашого підприємства за фіксованою вартістю — онлайн або з виїздом спеціаліста.',
    'corp_pills' => [
      ['pill' => '📹 Zoom або виїзд'],
      ['pill' => '👥 Будь-яка кількість учасників'],
      ['pill' => '🗓 Зручна дата для вас'],
    ],
    'register_kicker' => 'Реєстрація',
    'register_title' => 'Готові записатись<br><em>на семінар?</em>',
    'register_lead' => 'Залиште заявку зручним для вас способом.',
    'register_note_intro' => 'Також доступні Telegram та Viber · Відповідаємо',
    'register_note_price_label' => 'Вартість:',
    'register_note_price_suffix' => 'без ПДВ · Документи за запитом',
  ];

  $post_to_global_map = [
    'seminar_benefits' => 'global_benefits',
    'seminar_target' => 'global_target',
    'what_you_get' => 'global_what_get',
    'seminar_faq' => 'global_faq',
  ];

  foreach ($global_defaults as $option_key => $default_value) {
    $post_key = array_search($option_key, $post_to_global_map, true);
    $post_value = $post_key ? get_post_meta($seminar_id, $post_key, true) : get_post_meta($seminar_id, $option_key, true);

    $current_global = function_exists('get_field') ? get_field($option_key, 'option') : get_option('options_' . $option_key, null);

    $value = $default_value;
    if ($post_value !== '' && $post_value !== null) {
      $value = $post_value;
    }
    if ($current_global !== '' && $current_global !== null && $current_global !== []) {
      $value = $current_global;
    }

    if (function_exists('update_field')) {
      update_field($option_key, $value, 'option');
    }
    update_option('options_' . $option_key, $value);
  }

  update_option('ukr_seminar_migration_v4_done', current_time('mysql'));
}

function ukr_ensure_default_speaker(): int
{
  $existing = get_page_by_title('Гутков Георгій Валентинович', OBJECT, 'speaker');
  if ($existing instanceof WP_Post) {
    return (int) $existing->ID;
  }

  $speaker_id = wp_insert_post([
    'post_type' => 'speaker',
    'post_status' => 'publish',
    'post_title' => 'Гутков Георгій Валентинович',
  ]);

  if (is_wp_error($speaker_id) || !$speaker_id) {
    return 0;
  }

  $speaker_meta = [
    'speaker_org' => 'Завідувач сектору · Харківський НДІ екологічних проблем',
    'speaker_bio' => 'Провідний фахівець у галузі охорони атмосферного повітря та технічної експлуатації газоочисного обладнання. Багаторічний досвід проведення семінарів для інженерно-технічного персоналу промислових підприємств України.',
    'speaker_tags' => [
      ['tag' => '🔬 НДІ екологічних проблем'],
      ['tag' => '⚖️ Природоохоронне законодавство'],
      ['tag' => '🏭 Газоочисне обладнання'],
    ],
  ];

  foreach ($speaker_meta as $key => $value) {
    if (function_exists('update_field')) {
      update_field($key, $value, $speaker_id);
    }
    update_post_meta($speaker_id, $key, $value);
  }

  return (int) $speaker_id;
}
