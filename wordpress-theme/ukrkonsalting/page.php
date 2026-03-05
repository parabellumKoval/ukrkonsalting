<?php
/**
 * Generic page template
 */
get_header();
?>
<div style="max-width:800px;margin:0 auto;padding:72px 48px 96px;">
  <?php while (have_posts()):
  the_post(); ?>
  <h1
    style="font-family:'Lora',serif;font-size:clamp(28px,4vw,42px);font-weight:700;color:var(--green-deep);margin-bottom:32px;line-height:1.15;">
    <?php the_title(); ?>
  </h1>
  <div class="entry-content">
    <?php the_content(); ?>
  </div>
  <?php
endwhile; ?>
</div>
<?php get_footer(); ?>