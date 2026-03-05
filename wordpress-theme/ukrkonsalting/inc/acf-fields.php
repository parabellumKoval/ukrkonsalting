<?php
if (!defined('ABSPATH'))
  exit;

add_action('acf/init', 'ukr_acf_init');
function ukr_acf_init()
{
  if (!function_exists('acf_add_local_field_group'))
    return;

  // 1. About Page Fields
  acf_add_local_field_group(array(
    'key' => 'group_about_page_fields',
    'title' => 'Налаштування сторінки "Про нас"',
    'fields' => array(
        array(
        'key' => 'field_about_mission_title',
        'label' => 'Заголовок місії',
        'name' => 'about_mission_title',
        'type' => 'text',
        'default_value' => 'Практичне навчання для реального виробництва',
      ),
        array(
        'key' => 'field_about_mission_text',
        'label' => 'Текст місії',
        'name' => 'about_mission_text',
        'type' => 'wysiwyg',
        'default_value' => '<p>Консалтинговий центр «УкрКонсалтинг» спеціалізується на навчанні та підвищенні кваліфікації фахівців у сфері охорони навколишнього середовища. Ми проводимо онлайн-семінари (через Zoom) та корпоративне навчання з виїздом для промислових підприємств України.</p><p>Наші програми орієнтовані на практичне застосування: кожен семінар — це конкретні відповіді на актуальні питання, реальні кейси з вашої галузі та розбір змін законодавства 2024–2026 рр.</p><p>Понад 22 роки досвіду та більше 1000 проведених заходів свідчать про нашу репутацію надійного партнера для екологів, головних інженерів та відповідальних за охорону довкілля.</p>',
      ),
        array(
        'key' => 'field_about_values',
        'label' => 'Наші цінності',
        'name' => 'about_values',
        'type' => 'repeater',
        'sub_fields' => array(
            array(
            'key' => 'field_about_value_icon',
            'label' => 'Іконка',
            'name' => 'icon',
            'type' => 'text',
          ),
            array(
            'key' => 'field_about_value_title',
            'label' => 'Заголовок',
            'name' => 'title',
            'type' => 'text',
          ),
            array(
            'key' => 'field_about_value_text',
            'label' => 'Текст',
            'name' => 'text',
            'type' => 'text',
          ),
        ),
      ),
        array(
        'key' => 'field_about_speakers',
        'label' => 'Наші викладачі',
        'name' => 'about_speakers',
        'type' => 'repeater',
        'sub_fields' => array(
            array(
            'key' => 'field_about_speaker_name',
            'label' => 'Ім\'я викладача',
            'name' => 'name',
            'type' => 'text',
          ),
            array(
            'key' => 'field_about_speaker_org',
            'label' => 'Організація/Посада',
            'name' => 'org',
            'type' => 'text',
          ),
            array(
            'key' => 'field_about_speaker_bio',
            'label' => 'Біографія',
            'name' => 'bio',
            'type' => 'textarea',
          ),
            array(
            'key' => 'field_about_speaker_tags',
            'label' => 'Теги',
            'name' => 'tags',
            'type' => 'text',
            'instructions' => 'Перелічіть через кому (наприклад: 🔬 НДІ, ⚖️ Законодавство, 🏭 Обладнання)'
          ),
        ),
      ),
    ),
    'location' => array(
        array(
          array(
          'param' => 'page_template',
          'operator' => '==',
          'value' => 'page-about.php',
        ),
      ),
    ),
  ));

  // 1.5 Contact Page Fields
  acf_add_local_field_group(array(
    'key' => 'group_contact_page_fields',
    'title' => 'Налаштування сторінки "Контакти"',
    'fields' => array(
        array(
        'key' => 'field_contact_title',
        'label' => 'Заголовок сторінки',
        'name' => 'contact_page_title',
        'type' => 'text',
        'default_value' => 'Зв\'яжіться з нами',
      ),
        array(
        'key' => 'field_contact_subtitle',
        'label' => 'Підзаголовок',
        'name' => 'contact_page_subtitle',
        'type' => 'text',
        'default_value' => 'Відповідаємо швидко',
      ),
    ),
    'location' => array(
        array(
          array(
          'param' => 'page_template',
          'operator' => '==',
          'value' => 'page-contacts.php',
        ),
      ),
    ),
  ));

  // 2. Seminar + Speaker + Global seminar settings from local JSON
  $json_groups = [
    UKR_DIR . '/acf-json/group_seminar.json',
    UKR_DIR . '/acf-json/group_speaker.json',
    UKR_DIR . '/acf-json/group_global_seminar_settings.json',
  ];

  foreach ($json_groups as $group_file) {
    if (!is_readable($group_file)) {
      continue;
    }

    $json = file_get_contents($group_file);
    $decoded = json_decode($json ?: '', true);

    if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
      continue;
    }

    // ACF local JSON is usually a single group object; support both single object and list.
    $groups_to_register = isset($decoded['key']) ? [$decoded] : $decoded;

    foreach ($groups_to_register as $group) {
      if (is_array($group) && !empty($group['key']) && !empty($group['fields'])) {
        acf_add_local_field_group($group);
      }
    }
  }
}

/**
 * ACF Free compatibility for seminar_program.
 * Old values may still be stored as repeater arrays.
 */
add_filter('acf/load_value/name=seminar_program', function ($value, $post_id, $field) {
  if (!is_array($value)) {
    return $value;
  }

  $lines = [];
  foreach ($value as $idx => $topic) {
    if (!is_array($topic)) {
      continue;
    }

    $n = (int) $idx + 1;
    $heading = trim((string) ($topic['prog_heading'] ?? ''));
    $lines[] = 'Тема ' . $n . ': ' . ($heading !== '' ? $heading : ('Тема ' . $n));

    $items = $topic['prog_items'] ?? [];
    if (is_array($items)) {
      foreach ($items as $item) {
        $text = is_array($item) ? ($item['item'] ?? '') : (string) $item;
        $text = trim((string) $text);
        if ($text !== '') {
          $lines[] = '- ' . $text;
        }
      }
    }

    $note = trim((string) ($topic['prog_note'] ?? ''));
    if ($note !== '') {
      $lines[] = 'Примітка: ' . $note;
    }

    $lines[] = '';
  }

  return trim(implode("\n", $lines));
}, 10, 3);
