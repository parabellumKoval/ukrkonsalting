<?php
/**
 * Fallback index template
 */
get_header();
?>
<div style="max-width:1200px;margin:0 auto;padding:96px 48px;">
  <?php if (have_posts()):
  while (have_posts()):
    the_post(); ?>
  <article style="margin-bottom:40px;padding-bottom:40px;border-bottom:1px solid var(--border);">
    <h2 style="font-family:'Lora',serif;color:var(--green-deep);margin-bottom:8px;">
      <a href="<?php the_permalink(); ?>" style="text-decoration:none;color:inherit;">
        <?php the_title(); ?>
      </a>
    </h2>
    <p style="font-size:14px;color:var(--text-light);margin-bottom:12px;">
      <?php echo get_the_date(); ?>
    </p>
    <p style="color:var(--text-mid);">
      <?php the_excerpt(); ?>
    </p>
    <a href="<?php the_permalink(); ?>" style="color:var(--green-soft);font-weight:600;font-size:14px;">Читати далі
      →</a>
  </article>
  <?php
  endwhile;
  ukr_pagination();
else: ?>
  <p>Записів не знайдено.</p>
  <?php
endif; ?>
</div>
<?php get_footer(); ?>