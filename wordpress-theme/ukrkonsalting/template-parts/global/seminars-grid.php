<?php
/**
 * Template part: Seminars grid (homepage)
 * Displays all published seminars as cards
 */

$seminars = new WP_Query([
  'post_type' => 'seminar',
  'posts_per_page' => -1,
  'post_status' => 'publish',
  'orderby' => 'date',
  'order' => 'DESC',
]);

if (!$seminars->have_posts())
  return;

$num_map = [1 => '01', 2 => '02', 3 => '03', 4 => '04', 5 => '05', 6 => '06'];
$color_alt = '#2D5A42'; // lighter green for second card
$count = 0;
?>

<div class="seminars-bg">
  <div class="seminars-inner">
    <div class="section-label">Програми навчання</div>
    <div class="section-title">Спеціалізовані<br>семінари</div>
    <p class="section-sub">Кожна програма — поглиблений розгляд окремого напрямку природоохоронного законодавства та
      практики.</p>

    <div class="seminars-grid">
      <?php while ($seminars->have_posts()):
  $seminars->the_post();
  $count++;
  $id = get_the_ID();
  $meta = ukr_seminar_meta($id);
  $tag = $meta['tag'] ?: get_the_terms($id, 'seminar_category')[0]->name ?? 'Семінар';
  $price_raw = $meta['price'] ? number_format((float)$meta['price'], 0, '.', ' ') : '2 000';
  $num = $num_map[$count] ?? sprintf('%02d', $count);
  $card_bg = ($count % 2 === 0) ? $color_alt : '';
  $date_str = $meta['date'] ? ukr_format_date($meta['date']) : '';

  // Topics from seminar_program ACF repeater
  $programs = function_exists('get_field') ? (get_field('seminar_program', $id) ?: []) : [];
?>
      <div class="seminar-card reveal">
        <div class="seminar-card-top" <?php echo $card_bg ? 'style="background:' . esc_attr($card_bg) . '"' : '' ; ?>>
          <div class="seminar-num">
            <?php echo esc_html($num); ?>
          </div>
          <?php if ($date_str): ?>
          <div class="seminar-tag">
            <?php echo esc_html($date_str); ?>
          </div>
          <?php
  else: ?>
          <div class="seminar-tag">
            <?php echo esc_html($tag); ?>
          </div>
          <?php
  endif; ?>
          <h3>
            <?php the_title(); ?>
          </h3>
        </div>

        <div class="seminar-card-body">
          <ul class="seminar-topics">
            <?php if (!empty($programs)):
    foreach (array_slice($programs, 0, 5) as $prog):
      $heading = $prog['prog_heading'] ?? '';
      if ($heading):
?>
            <li>
              <div class="dot"></div>
              <?php echo esc_html($heading); ?>
            </li>
            <?php
      endif;
    endforeach;
  else:
    // Fallback: use post excerpt words as bullet points
    $excerpt = get_the_excerpt();
    $sentences = preg_split('/(?<=[.!?])\s+/', $excerpt, 5);
    foreach (array_slice(array_filter($sentences), 0, 4) as $s): ?>
            <li>
              <div class="dot"></div>
              <?php echo esc_html($s); ?>
            </li>
            <?php
    endforeach;
  endif; ?>
          </ul>

          <div class="seminar-footer">
            <div>
              <div class="seminar-price">
                <?php echo esc_html($price_raw); ?> грн
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
endwhile;
wp_reset_postdata(); ?>
    </div>
  </div>
</div>