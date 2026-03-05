<?php
/**
 * УкрКонсалтинг Theme Functions
 */

if (!defined('ABSPATH'))
    exit;

define('UKR_VERSION', '1.0.0');
define('UKR_DIR', get_template_directory());
define('UKR_URL', get_template_directory_uri());

// ─── 1. ENQUEUE STYLES & SCRIPTS ─────────────────────────────────────────────

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'ukr-main',
        UKR_URL . '/assets/css/main.css',
    [],
        UKR_VERSION
    );
    wp_enqueue_style(
        'google-fonts',
        'https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,500;1,600&family=Outfit:wght@300;400;500;600;700&display=swap',
    [],
        null
    );
    wp_enqueue_script(
        'ukr-main',
        UKR_URL . '/assets/js/main.js',
    [],
        UKR_VERSION,
        true
    );
    // Pass data to JS for AJAX forms
    wp_localize_script('ukr-main', 'ukrAjax', [
        'url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ukr_forms_nonce'),
    ]);
});

// ─── 2. THEME SETUP ──────────────────────────────────────────────────────────

add_action('after_setup_theme', function () {
    load_theme_textdomain('ukrkonsalting', UKR_DIR . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    add_theme_support('custom-logo', [
        'height' => 60,
        'width' => 200,
        'flex-height' => true,
        'flex-width' => true,
    ]);

    register_nav_menus([
        'primary' => __('Головне меню', 'ukrkonsalting'),
        'footer' => __('Футер меню', 'ukrkonsalting'),
    ]);
});

// ─── 3. WIDGET AREAS ─────────────────────────────────────────────────────────

add_action('widgets_init', function () {
    register_sidebar([
        'name' => __('Бічна панель блогу', 'ukrkonsalting'),
        'id' => 'blog-sidebar',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget__title">',
        'after_title' => '</h3>',
    ]);
    register_sidebar([
        'name' => __('Підвал — Контакти', 'ukrkonsalting'),
        'id' => 'footer-contact',
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h5>',
        'after_title' => '</h5>',
    ]);
});

// ─── 4. CUSTOM POST TYPE: SEMINAR ────────────────────────────────────────────

add_action('init', function () {
    register_post_type('speaker', [
        'labels' => [
            'name' => __('Викладачі', 'ukrkonsalting'),
            'singular_name' => __('Викладач', 'ukrkonsalting'),
            'add_new_item' => __('Додати викладача', 'ukrkonsalting'),
            'edit_item' => __('Редагувати викладача', 'ukrkonsalting'),
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-businessperson',
        'supports' => ['title', 'custom-fields'],
    ]);

    register_post_type('seminar', [
        'labels' => [
            'name' => __('Семінари', 'ukrkonsalting'),
            'singular_name' => __('Семінар', 'ukrkonsalting'),
            'add_new' => __('Додати семінар', 'ukrkonsalting'),
            'add_new_item' => __('Новий семінар', 'ukrkonsalting'),
            'edit_item' => __('Редагувати семінар', 'ukrkonsalting'),
            'view_item' => __('Переглянути семінар', 'ukrkonsalting'),
            'search_items' => __('Пошук семінарів', 'ukrkonsalting'),
            'not_found' => __('Семінарів не знайдено', 'ukrkonsalting'),
            'menu_name' => __('Семінари', 'ukrkonsalting'),
        ],
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'seminars'],
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-welcome-learn-more',
        'menu_position' => 5,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'taxonomies' => ['seminar_category'],
    ]);

    register_taxonomy('seminar_category', 'seminar', [
        'labels' => [
            'name' => __('Категорії семінарів', 'ukrkonsalting'),
            'singular_name' => __('Категорія', 'ukrkonsalting'),
            'add_new_item' => __('Додати категорію', 'ukrkonsalting'),
        ],
        'hierarchical' => true,
        'public' => true,
        'rewrite' => ['slug' => 'seminar-category'],
        'show_in_rest' => true,
    ]);
});

// ─── 5. ACF JSON SAVE/LOAD POINT ─────────────────────────────────────────────

// Save ACF field group JSON to theme folder
add_filter('acf/settings/save_json', function () {
    return UKR_DIR . '/acf-json';
});
// Load ACF field group JSON from theme folder
add_filter('acf/settings/load_json', function ($paths) {
    $paths[] = UKR_DIR . '/acf-json';
    return $paths;
});

// Add ACF Options Page
if (function_exists('acf_add_options_page')) {
    acf_add_options_page([
        'page_title' => __('Налаштування теми', 'ukrkonsalting'),
        'menu_title' => __('Налаштування теми', 'ukrkonsalting'),
        'menu_slug' => 'theme-settings',
        'capability' => 'edit_posts',
        'redirect' => false
    ]);
}

// ─── 6. THEME CUSTOMIZER ─────────────────────────────────────────────────────

add_action('customize_register', function ($wp_customize) {

    // Panel
    $wp_customize->add_panel('ukr_panel', [
        'title' => __('УкрКонсалтинг Налаштування', 'ukrkonsalting'),
        'priority' => 10,
    ]);

    // Section: Contacts
    $wp_customize->add_section('ukr_contacts', [
        'title' => __('Контактні дані', 'ukrkonsalting'),
        'panel' => 'ukr_panel',
    ]);

    $fields = [
        'phone' => ['label' => 'Телефон', 'default' => '(097) 214-42-05'],
        'email' => ['label' => 'Email', 'default' => 'uktc@ukr.net'],
        'telegram' => ['label' => 'Telegram', 'default' => ''],
        'viber' => ['label' => 'Viber', 'default' => ''],
        'address' => ['label' => 'Адреса', 'default' => 'Україна'],
        'work_hours' => ['label' => 'Години роботи', 'default' => 'Пн–Пт, 9:00–18:00'],
    ];

    foreach ($fields as $key => $args) {
        $wp_customize->add_setting("ukr_{$key}", ['default' => $args['default'], 'sanitize_callback' => 'sanitize_text_field']);
        $wp_customize->add_control("ukr_{$key}", [
            'label' => $args['label'],
            'section' => 'ukr_contacts',
            'type' => 'text',
        ]);
    }

    // Section: Stats
    $wp_customize->add_section('ukr_stats', [
        'title' => __('Статистика (стрічка цифр)', 'ukrkonsalting'),
        'panel' => 'ukr_panel',
    ]);

    $stats = [
        'years' => ['label' => 'Років на ринку', 'default' => '22+'],
        'seminars' => ['label' => 'Проведено семінарів', 'default' => '1000+'],
        'programs' => ['label' => 'Спеціалізовані програми', 'default' => '2'],
        'price' => ['label' => 'Доступна ціна (грн)', 'default' => '2000'],
    ];

    foreach ($stats as $key => $args) {
        $wp_customize->add_setting("ukr_stat_{$key}", ['default' => $args['default'], 'sanitize_callback' => 'sanitize_text_field']);
        $wp_customize->add_control("ukr_stat_{$key}", [
            'label' => $args['label'],
            'section' => 'ukr_stats',
            'type' => 'text',
        ]);
    }
});

// Helper to get customizer value
function ukr_option(string $key, string $fallback = ''): string
{
    return esc_html(get_theme_mod("ukr_{$key}", $fallback));
}

// ─── 7. AJAX — FORM HANDLERS ──────────────────────────────────────────────────

require_once UKR_DIR . '/inc/forms.php';
require_once UKR_DIR . '/inc/acf-fields.php';
require_once UKR_DIR . '/inc/seminar-migration.php';

// ─── 8. TEMPLATE HELPERS ─────────────────────────────────────────────────────

/**
 * Render pagination for archives.
 */
function ukr_pagination(?WP_Query $query = null): void
{
    global $wp_query;
    $q = $query ?? $wp_query;
    $big = 999999;
    echo '<nav class="pagination">';
    echo paginate_links([
        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $q->max_num_pages,
        'prev_text' => '← Попередня',
        'next_text' => 'Наступна →',
    ]);
    echo '</nav>';
}

/**
 * Return date, time, format, price from seminar ACF fields (or post meta).
 */
function ukr_seminar_meta(int $post_id): array
{
    if (function_exists('get_field')) {
        return [
            'date' => get_field('seminar_date', $post_id),
            'time' => get_field('seminar_time', $post_id),
            'format' => get_field('seminar_format', $post_id),
            'price' => get_field('seminar_price', $post_id),
            'tag' => get_field('seminar_tag', $post_id),
            'eyebrow' => get_field('seminar_eyebrow', $post_id),
            'description' => get_field('seminar_description', $post_id),
            'preamble' => get_field('seminar_preamble', $post_id),
        ];
    }
    // Fallback to post meta
    return [
        'date' => get_post_meta($post_id, 'seminar_date', true),
        'time' => get_post_meta($post_id, 'seminar_time', true),
        'format' => get_post_meta($post_id, 'seminar_format', true),
        'price' => get_post_meta($post_id, 'seminar_price', true),
        'tag' => get_post_meta($post_id, 'seminar_tag', true),
        'eyebrow' => get_post_meta($post_id, 'seminar_eyebrow', true),
        'description' => get_post_meta($post_id, 'seminar_description', true),
        'preamble' => get_post_meta($post_id, 'seminar_preamble', true),
    ];
}

/**
 * Format date from Y-m-d to Ukrainian readable.
 */
function ukr_format_date(string $date): string
{
    if (empty($date))
        return '';
    $months = [
        1 => 'січня', 2 => 'лютого', 3 => 'березня', 4 => 'квітня',
        5 => 'травня', 6 => 'червня', 7 => 'липня', 8 => 'серпня',
        9 => 'вересня', 10 => 'жовтня', 11 => 'листопада', 12 => 'грудня',
    ];
    $ts = strtotime($date);
    return intval(date('j', $ts)) . ' ' . $months[intval(date('n', $ts))] . ' ' . date('Y', $ts);
}

// ─── 9. FLUSH REWRITE RULES ON THEME SWITCH ──────────────────────────────────

add_action('after_switch_theme', 'flush_rewrite_rules');

// ─── 10. SEO ARTICLE UI SHORTCODES ─────────────────────────────────────────

/**
 * [ukr_checklist title="..."]
 * item one
 * item two
 * [/ukr_checklist]
 */
add_shortcode('ukr_checklist', function ($atts, $content = '') {
    $atts = shortcode_atts([
        'title' => 'Практичний чекліст',
    ], $atts, 'ukr_checklist');

    $lines = preg_split('/\r\n|\r|\n/', trim((string) $content));
    $items = [];

    foreach ($lines as $line) {
        $line = trim(wp_strip_all_tags($line));
        $line = preg_replace('/^[-•\d\.\)\s]+/u', '', $line);
        if ($line !== '') {
            $items[] = esc_html($line);
        }
    }

    if (empty($items)) {
        return '';
    }

    ob_start(); ?>
    <section class="ukr-seo-checklist" aria-label="<?php echo esc_attr($atts['title']); ?>">
        <h3 class="ukr-seo-checklist__title"><?php echo esc_html($atts['title']); ?></h3>
        <ul class="ukr-seo-checklist__list">
            <?php foreach ($items as $item): ?>
                <li class="ukr-seo-checklist__item"><?php echo $item; ?></li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php
    return ob_get_clean();
});

/**
 * [ukr_case title="Кейс" outcome="Наслідок"]Текст кейсу[/ukr_case]
 */
add_shortcode('ukr_case', function ($atts, $content = '') {
    $atts = shortcode_atts([
        'title' => 'Кейс з практики',
        'outcome' => '',
    ], $atts, 'ukr_case');

    $body = wp_kses_post(wpautop(trim((string) $content)));
    if ($body === '') {
        return '';
    }

    ob_start(); ?>
    <aside class="ukr-seo-case" role="note" aria-label="<?php echo esc_attr($atts['title']); ?>">
        <div class="ukr-seo-case__label">Реальна ситуація</div>
        <h3 class="ukr-seo-case__title"><?php echo esc_html($atts['title']); ?></h3>
        <div class="ukr-seo-case__body"><?php echo $body; ?></div>
        <?php if (!empty($atts['outcome'])): ?>
            <p class="ukr-seo-case__outcome"><strong>Наслідок:</strong> <?php echo esc_html($atts['outcome']); ?></p>
        <?php endif; ?>
    </aside>
    <?php
    return ob_get_clean();
});