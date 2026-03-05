<?php
/**
 * AJAX form handlers for all 3 forms
 * Loaded via functions.php: require_once UKR_DIR . '/inc/forms.php';
 */

// ─── Shared: verify nonce ────────────────────────────────────────────────────

function ukr_verify_nonce(): void
{
  if (!check_ajax_referer('ukr_forms_nonce', 'ukr_nonce', false)) {
    wp_send_json_error(['message' => 'Помилка безпеки. Оновіть сторінку і спробуйте ще раз.']);
  }
}

function ukr_admin_email(): string
{
  return get_option('admin_email');
}

function ukr_site_name(): string
{
  return get_bloginfo('name');
}

// ─── 1. Seminar Registration ──────────────────────────────────────────────────

function ukr_handle_registration(): void
{
  ukr_verify_nonce();

  $full_name = sanitize_text_field($_POST['full_name'] ?? '');
  $company = sanitize_text_field($_POST['company'] ?? '');
  $position = sanitize_text_field($_POST['position'] ?? '');
  $phone = sanitize_text_field($_POST['phone'] ?? '');
  $email = sanitize_email($_POST['email'] ?? '');
  $seminar = sanitize_text_field($_POST['seminar'] ?? '');
  $participants = absint($_POST['participants'] ?? 1);
  $comment = sanitize_textarea_field($_POST['comment'] ?? '');

  if (empty($full_name) || empty($email) || empty($phone)) {
    wp_send_json_error(['message' => 'Заповніть обов\'язкові поля: ПІБ, телефон та email.']);
  }

  if (!is_email($email)) {
    wp_send_json_error(['message' => 'Вкажіть коректний email.']);
  }

  // Get seminar title
  $seminar_title = $seminar;
  if (is_numeric($seminar)) {
    $seminar_post = get_post((int)$seminar);
    if ($seminar_post)
      $seminar_title = $seminar_post->post_title;
  }

  // Email to admin
  $subject = sprintf('[%s] Нова заявка на семінар від %s', ukr_site_name(), $full_name);
  $body = "Нова заявка на семінар:\n\n"
    . "ПІБ: $full_name\n"
    . "Підприємство: $company\n"
    . "Посада: $position\n"
    . "Телефон: $phone\n"
    . "Email: $email\n"
    . "Семінар: $seminar_title\n"
    . "Учасників: $participants\n"
    . "Коментар: $comment\n";

  // Use Reply-To with applicant email
  $headers = ["Content-Type: text/plain; charset=UTF-8", "Reply-To: $email"];
  $admin_to = get_theme_mod('ukr_email', ukr_admin_email());

  wp_mail($admin_to, $subject, $body, $headers);

  // Auto-reply to applicant
  $reply_subject = 'Ваша заявка отримана — ' . ukr_site_name();
  $reply_body = "Дякуємо, $full_name!\n\n"
    . "Ваша заявка на семінар «$seminar_title» отримана.\n"
    . "Ми зв'яжемося з вами найближчим часом за вказаним контактом: $phone / $email.\n\n"
    . "З повагою,\n" . ukr_site_name();
  wp_mail($email, $reply_subject, $reply_body, ['Content-Type: text/plain; charset=UTF-8']);

  wp_send_json_success(['message' => '✅ Дякуємо! Ваша заявка отримана. Очікуйте підтвердження на email.']);
}
add_action('wp_ajax_ukr_registration', 'ukr_handle_registration');
add_action('wp_ajax_nopriv_ukr_registration', 'ukr_handle_registration');

// ─── 2. Contact / Feedback ────────────────────────────────────────────────────

function ukr_handle_contact(): void
{
  ukr_verify_nonce();

  $name = sanitize_text_field($_POST['name'] ?? '');
  $phone = sanitize_text_field($_POST['phone'] ?? '');
  $email = sanitize_email($_POST['email'] ?? '');
  $message = sanitize_textarea_field($_POST['message'] ?? '');

  if (empty($name) || empty($email) || empty($message)) {
    wp_send_json_error(['message' => 'Заповніть обов\'язкові поля: ім\'я, email та повідомлення.']);
  }

  $subject = sprintf('[%s] Питання від %s', ukr_site_name(), $name);
  $body = "Повідомлення від: $name\nТелефон: $phone\nEmail: $email\n\n$message";
  $headers = ['Content-Type: text/plain; charset=UTF-8', "Reply-To: $email"];
  $admin_to = get_theme_mod('ukr_email', ukr_admin_email());

  wp_mail($admin_to, $subject, $body, $headers);

  wp_send_json_success(['message' => '✅ Повідомлення надіслано! Відповімо найближчим часом.']);
}
add_action('wp_ajax_ukr_contact', 'ukr_handle_contact');
add_action('wp_ajax_nopriv_ukr_contact', 'ukr_handle_contact');

// ─── 3. Newsletter Subscription ───────────────────────────────────────────────

function ukr_handle_newsletter(): void
{
  ukr_verify_nonce();

  $email = sanitize_email($_POST['email'] ?? '');

  if (empty($email) || !is_email($email)) {
    wp_send_json_error(['message' => 'Вкажіть коректний email.']);
  }

  // Store email in a custom WP option list
  $subscribers = get_option('ukr_newsletter_subscribers', []);
  if (!in_array($email, $subscribers, true)) {
    $subscribers[] = $email;
    update_option('ukr_newsletter_subscribers', $subscribers);

    // Notify admin
    $admin_to = get_theme_mod('ukr_email', ukr_admin_email());
    wp_mail($admin_to, 'Нова підписка — ' . ukr_site_name(), "Новий підписник: $email");

    // Confirmation to subscriber
    $body = "Дякуємо за підписку на розсилку " . ukr_site_name() . "!\n\n"
      . "Ви будете отримувати інформацію про нові семінари, зміни законодавства та спеціальні пропозиції.\n\n"
      . "З повагою,\n" . ukr_site_name();
    wp_mail($email, 'Підтвердження підписки — ' . ukr_site_name(), $body);

    wp_send_json_success(['message' => '✅ Підписку оформлено! Перевірте email для підтвердження.']);
  }
  else {
    wp_send_json_success(['message' => '✅ Ви вже підписані. Дякуємо!']);
  }
}
add_action('wp_ajax_ukr_newsletter', 'ukr_handle_newsletter');
add_action('wp_ajax_nopriv_ukr_newsletter', 'ukr_handle_newsletter');

// ─── Admin: Subscribers export page ──────────────────────────────────────────

add_action('admin_menu', function () {
  add_submenu_page(
    'options-general.php',
    'Newsletter підписники',
    'Підписники',
    'manage_options',
    'ukr-subscribers',
    function () {
      $subscribers = get_option('ukr_newsletter_subscribers', []);
      echo '<div class="wrap"><h1>Newsletter підписники (' . count($subscribers) . ')</h1>';
      if (empty($subscribers)) {
        echo '<p>Підписників поки немає.</p>';
      }
      else {
        echo '<textarea rows="20" cols="50" style="font-family:monospace;">' . esc_textarea(implode("\n", $subscribers)) . '</textarea>';
        echo '<p><a href="?page=ukr-subscribers&export=1" class="button">Завантажити CSV</a></p>';
      }
      // CSV export
      if (isset($_GET['export']) && current_user_can('manage_options')) {
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename=subscribers.csv');
        echo "email\n" . implode("\n", $subscribers);
        exit;
      }
      echo '</div>';
    }
    );
  });