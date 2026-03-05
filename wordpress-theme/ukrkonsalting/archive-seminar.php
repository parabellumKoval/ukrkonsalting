<?php
/**
 * Archive: Seminar CPT listing
 */
get_header();
?>

<section style="background:var(--green-deep);padding:72px 48px;text-align:center;position:relative;overflow:hidden;">
  <div
    style="position:absolute;inset:0;background:radial-gradient(ellipse at 30% 70%,rgba(74,128,96,.25),transparent 55%);">
  </div>
  <?php
$arch_title = function_exists('get_field') ? get_field('seminar_archive_title', 'options') : '';
$arch_subtitle = function_exists('get_field') ? get_field('seminar_archive_subtitle', 'options') : '';
if (!$arch_title)
  $arch_title = 'Всі семінари';
if (!$arch_subtitle)
  $arch_subtitle = 'Спеціалізовані онлайн-програми для фахівців з охорони навколишнього середовища';
?>
  <div style="position:relative;z-index:1;max-width:700px;margin:0 auto;">
    <div
      style="display:inline-flex;align-items:center;gap:8px;background:rgba(201,168,76,.15);border:1px solid rgba(201,168,76,.3);border-radius:100px;padding:6px 16px;margin-bottom:18px;font-size:12px;font-weight:600;color:var(--gold-light);">
      Навчання</div>
    <h1
      style="font-family:'Lora',serif;color:#fff;font-size:clamp(28px,4vw,48px);font-weight:700;margin-bottom:14px;line-height:1.15;">
      <?php echo esc_html($arch_title); ?>
    </h1>
    <p style="font-size:17px;color:rgba(255,255,255,.6);font-weight:300;">
      <?php echo esc_html($arch_subtitle); ?>
    </p>
  </div>
</section>

<?php get_template_part('template-parts/global/marquee'); ?>

<div style="max-width:1200px;margin:0 auto;padding:72px 48px;">
  <div class="seminars-grid">
    <?php
$count = 0;
while (have_posts()):
  the_post();
  $count++;
  $id = get_the_ID();
  $meta = ukr_seminar_meta($id);
  $price_fmt = $meta['price'] ? number_format((float)$meta['price'], 0, '.', ' ') : '2 000';
  $date_str = $meta['date'] ? ukr_format_date($meta['date']) : '';
  $program_topics = function_exists('ukr_get_program_topics') ? ukr_get_program_topics($id, 5) : [];
?>
    <div class="seminar-card reveal">
      <div class="seminar-card-top" <?php echo ($count % 2 === 0) ? 'style="background:#2D5A42"' : ''; ?>>
        <div class="seminar-num">
          <?php echo sprintf('%02d', $count); ?>
        </div>
        <?php if ($date_str): ?>
        <div class="seminar-tag">
          <?php echo esc_html($date_str); ?>
        </div>
        <?php
  else: ?>
        <div class="seminar-tag">
          <?php echo esc_html($meta['tag'] ?: 'Семінар'); ?>
        </div>
        <?php
  endif; ?>
        <h3>
          <?php the_title(); ?>
        </h3>
      </div>
      <div class="seminar-card-body">
        <ul class="seminar-topics">
          <?php if (!empty($program_topics)):
    foreach ($program_topics as $h):
      echo '<li><div class="dot"></div>' . esc_html($h) . '</li>';
    endforeach;
  else:
    $ex = get_the_excerpt();
    if ($ex)
      echo '<li><div class="dot"></div>' . esc_html(wp_trim_words($ex, 20)) . '</li>';
  endif; ?>
        </ul>
        <div class="seminar-footer">
          <div>
            <div class="seminar-price">
              <?php echo esc_html($price_fmt); ?> грн
            </div>
            <div style="font-size:12px;color:var(--text-light);">з матеріалами та сертифікатом</div>
          </div>
          <a href="<?php the_permalink(); ?>" class="seminar-btn">
            Детальніше
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M5 12h14M12 5l7 7-7 7" />
            </svg>
          </a>
        </div>
      </div>
    </div>
    <?php
endwhile; ?>
  </div>
  <?php ukr_pagination(); ?>
</div>

<!-- CTA -->
<?php get_template_part('template-parts/global/cta-banner'); ?>

<?php get_footer(); ?>