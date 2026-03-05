<?php
/**
 * Header template
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>

  <nav class="site-nav" id="site-nav">
    <!-- Logo -->
    <a href="<?php echo esc_url(home_url('/')); ?>" class="nav-logo">
      <?php if (has_custom_logo()): ?>
      <?php the_custom_logo(); ?>
      <?php
else: ?>
      <div class="nav-logo-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round">
          <path d="M12 2C6 2 3 7 3 12c0 3 2 6 5 8" />
          <path d="M12 2c6 0 9 5 9 10 0 3-2 6-5 8" />
          <path d="M7 20c1.5-2 3.5-3 5-3s3.5 1 5 3" />
          <circle cx="12" cy="12" r="3" />
        </svg>
      </div>
      <?php bloginfo('name'); ?>
      <?php
endif; ?>
    </a>

    <!-- Desktop nav links -->
    <div class="nav-links" id="nav-links">
      <?php
wp_nav_menu([
  'theme_location' => 'primary',
  'container' => false,
  'menu_class' => '',
  'fallback_cb' => function () {
    echo '<a href="' . esc_url(home_url('/')) . '">Головна</a>';
    echo '<a href="' . esc_url(home_url('/seminars/')) . '">Семінари</a>';
    echo '<a href="' . esc_url(home_url('/blog/')) . '">Блог</a>';
    echo '<a href="' . esc_url(home_url('/contacts/')) . '">Контакти</a>';
  },
  'items_wrap' => '%3$s',
]);
?>
      <a href="#registration-modal" class="nav-cta js-open-registration-modal" data-modal-target="registration-modal"
        aria-haspopup="dialog" aria-controls="registration-modal">
        Записатись
      </a>
    </div>

    <!-- Hamburger (mobile) -->
    <button class="nav-burger" id="nav-burger" aria-label="Меню" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </nav>
