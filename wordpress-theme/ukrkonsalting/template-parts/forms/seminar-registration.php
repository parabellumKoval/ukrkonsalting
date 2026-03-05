<?php
/**
 * Template part: Seminar registration form
 * Submitted via AJAX to admin-ajax.php → ukr_handle_registration
 */

// Build seminar list for select dropdown
$seminars = new WP_Query([
  'post_type' => 'seminar',
  'posts_per_page' => -1,
  'post_status' => 'publish',
]);

$id_suffix = '';
$selected_seminar = '';
if (isset($args) && is_array($args)) {
  $id_suffix = sanitize_html_class((string) ($args['id_suffix'] ?? ''));
  $selected_seminar = sanitize_text_field((string) ($args['selected_seminar'] ?? ''));
}

$id_suffix = $id_suffix !== '' ? '-' . $id_suffix : '';
$wrapper_id = 'seminar-registration-form' . $id_suffix;
$form_id = 'form-registration' . $id_suffix;
$status_id = 'reg-status' . $id_suffix;
$submit_id = 'reg-submit' . $id_suffix;
$seminar_field_id = 'reg-seminar' . $id_suffix;
?>
<div class="form-wrap" id="<?php echo esc_attr($wrapper_id); ?>">
  <div class="form-title">Записатись на семінар</div>
  <p class="form-subtitle">Залиште дані — ми підтвердимо реєстрацію і надішлемо деталі оплати</p>

  <form id="<?php echo esc_attr($form_id); ?>" class="js-ukr-registration-form"
    data-status-id="<?php echo esc_attr($status_id); ?>" data-submit-id="<?php echo esc_attr($submit_id); ?>" novalidate>
    <?php wp_nonce_field('ukr_forms_nonce', 'ukr_nonce', false); ?>
    <input type="hidden" name="action" value="ukr_registration">

    <div class="form-row">
      <div class="form-group">
        <label for="<?php echo esc_attr('reg-name' . $id_suffix); ?>">ПІБ *</label>
        <input type="text" id="<?php echo esc_attr('reg-name' . $id_suffix); ?>" name="full_name"
          placeholder="Іваненко Іван Іванович" required>
      </div>
      <div class="form-group">
        <label for="<?php echo esc_attr('reg-company' . $id_suffix); ?>">Підприємство *</label>
        <input type="text" id="<?php echo esc_attr('reg-company' . $id_suffix); ?>" name="company"
          placeholder="ТОВ «Назва підприємства»" required>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="<?php echo esc_attr('reg-position' . $id_suffix); ?>">Посада</label>
        <input type="text" id="<?php echo esc_attr('reg-position' . $id_suffix); ?>" name="position"
          placeholder="Головний еколог">
      </div>
      <div class="form-group">
        <label for="<?php echo esc_attr('reg-count' . $id_suffix); ?>">Кількість учасників</label>
        <input type="number" id="<?php echo esc_attr('reg-count' . $id_suffix); ?>" name="participants" value="1" min="1"
          max="100">
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="<?php echo esc_attr('reg-phone' . $id_suffix); ?>">Телефон *</label>
        <input type="tel" id="<?php echo esc_attr('reg-phone' . $id_suffix); ?>" name="phone"
          placeholder="+38 (097) 000-00-00" required>
      </div>
      <div class="form-group">
        <label for="<?php echo esc_attr('reg-email' . $id_suffix); ?>">Email *</label>
        <input type="email" id="<?php echo esc_attr('reg-email' . $id_suffix); ?>" name="email"
          placeholder="name@company.ua" required>
      </div>
    </div>

    <div class="form-group">
      <label for="<?php echo esc_attr($seminar_field_id); ?>">Семінар *</label>
      <select id="<?php echo esc_attr($seminar_field_id); ?>" name="seminar" required
        data-default-value="<?php echo esc_attr($selected_seminar); ?>">
        <option value="" <?php selected($selected_seminar, ''); ?>>— Оберіть семінар —</option>
        <?php if ($seminars->have_posts()):
  while ($seminars->have_posts()):
    $seminars->the_post();
    $option_value = (string) get_the_ID(); ?>
        <option value="<?php echo esc_attr($option_value); ?>" <?php selected($selected_seminar, $option_value); ?>>
          <?php the_title(); ?>
        </option>
        <?php
  endwhile;
  wp_reset_postdata();
else: ?>
        <option value="seminar-1" <?php selected($selected_seminar, 'seminar-1'); ?>>Семінар з охорони атмосферного
          повітря та ГОУ</option>
        <option value="seminar-2" <?php selected($selected_seminar, 'seminar-2'); ?>>Семінар з природоохоронної діяльності
        </option>
        <?php
endif; ?>
        <option value="corporate" <?php selected($selected_seminar, 'corporate'); ?>>Корпоративне навчання (на
          замовлення)</option>
      </select>
    </div>

    <div class="form-group">
      <label for="<?php echo esc_attr('reg-comment' . $id_suffix); ?>">Питання або коментар</label>
      <textarea id="<?php echo esc_attr('reg-comment' . $id_suffix); ?>" name="comment"
        placeholder="Можна надіслати питання заздалегідь — лектор відповість під час семінару"></textarea>
    </div>

    <div class="form-group">
      <label class="form-check">
        <input type="checkbox" name="consent" required>
        <span>Я погоджуюсь з <a href="<?php echo esc_url(home_url('/privacy/')); ?>" target="_blank">умовами
            використання</a> та обробкою персональних даних</span>
      </label>
    </div>

    <button type="submit" class="form-submit" id="<?php echo esc_attr($submit_id); ?>">
      Надіслати заявку →
    </button>
    <p class="form-notice">Відповідаємо протягом 1 робочого дня ·
      <?php echo esc_html(ukr_option('work_hours', 'Пн–Пт, 9:00–18:00')); ?>
    </p>

    <div class="form-status" id="<?php echo esc_attr($status_id); ?>"></div>
  </form>
</div>
