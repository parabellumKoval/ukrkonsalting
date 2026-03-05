<?php
/**
 * Footer template
 */

$phone = ukr_option('phone', '(097) 214-42-05');
$email = ukr_option('email', 'uktc@ukr.net');
$work_hours = ukr_option('work_hours', 'Пн–Пт, 9:00–18:00');
?>

<!-- Newsletter strip -->
<?php get_template_part('template-parts/forms/newsletter'); ?>

<!-- Contact strip -->
<?php get_template_part('template-parts/global/contact-strip'); ?>

<div class="modal-registration" id="registration-modal" hidden aria-hidden="true">
  <div class="modal-registration__backdrop" data-modal-close></div>
  <div class="modal-registration__dialog" role="dialog" aria-modal="true" aria-label="Форма запису на семінар">
    <button type="button" class="modal-registration__close" data-modal-close aria-label="Закрити вікно">
      <span aria-hidden="true">×</span>
    </button>
    <?php get_template_part('template-parts/forms/seminar-registration', null, ['id_suffix' => 'modal']); ?>
  </div>
</div>

<footer class="site-footer">
  <div class="footer-inner">
    <!-- Brand -->
    <div class="footer-brand">
      <h4>КЦ «УкрКонсалтинг»</h4>
      <p>Консалтинговий центр. Спеціалізація — навчання та підвищення кваліфікації фахівців з охорони навколишнього
        середовища.</p>
    </div>

    <!-- Seminars col -->
    <div class="footer-col">
      <h5>Навчання</h5>
      <?php
$seminars = new WP_Query(['post_type' => 'seminar', 'posts_per_page' => 5, 'post_status' => 'publish']);
if ($seminars->have_posts()):
  while ($seminars->have_posts()):
    $seminars->the_post();
    echo '<a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a>';
  endwhile;
  wp_reset_postdata();
else:
  echo '<a href="' . esc_url(home_url('/seminars/')) . '">Всі семінари</a>';
endif;
?>
      <a href="<?php echo esc_url(home_url('/corporate/')); ?>">Корпоративне навчання</a>
    </div>

    <!-- Company col -->
    <div class="footer-col">
      <h5>Компанія</h5>
      <?php
wp_nav_menu([
  'theme_location' => 'footer',
  'container' => false,
  'items_wrap' => '%3$s',
  'fallback_cb' => function () {
    echo '<a href="' . esc_url(home_url('/about/')) . '">Про нас</a>';
    echo '<a href="' . esc_url(home_url('/blog/')) . '">Блог</a>';
    echo '<a href="' . esc_url(home_url('/contacts/')) . '">Контакти</a>';
  },
]);
?>
    </div>

    <!-- Contacts col -->
    <div class="footer-col">
      <h5>Контакти</h5>
      <p>
        <?php echo esc_html($phone); ?>
      </p>
      <p>
        <?php echo esc_html($email); ?>
      </p>
      <p style="margin-top:12px; font-size:12px; opacity:.5;">
        <?php echo esc_html($work_hours); ?>
      </p>
    </div>
  </div>

  <div class="footer-bottom">
    <span>©
      <?php echo date('Y'); ?> КЦ «УкрКонсалтинг». Всі права захищені.
    </span>
    <span>Навчання з охорони довкілля · Онлайн-семінари · Україна</span>
  </div>
</footer>

<?php wp_footer(); ?>
</body>

</html>
