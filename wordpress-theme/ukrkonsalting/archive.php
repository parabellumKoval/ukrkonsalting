<?php
/**
 * Archive template — Blog posts
 */
get_header();
?>

<!-- Blog hero -->
<section style="background:var(--green-deep);padding:64px 48px;text-align:center;position:relative;overflow:hidden;">
  <div style="position:relative;z-index:1;max-width:700px;margin:0 auto;">
    <div
      style="display:inline-flex;align-items:center;gap:8px;background:rgba(201,168,76,.15);border:1px solid rgba(201,168,76,.3);border-radius:100px;padding:6px 16px;margin-bottom:18px;font-size:12px;font-weight:600;color:var(--gold-light);">
      Блог</div>
    <h1
      style="font-family:var(--font-serif,'Lora',serif);color:#fff;font-size:clamp(28px,4vw,46px);font-weight:700;margin-bottom:12px;line-height:1.15;">
      Статті та новини</h1>
    <p style="font-size:16px;color:rgba(255,255,255,.55);font-weight:300;">Зміни законодавства, практичні поради та
      новини з охорони навколишнього середовища</p>
  </div>
</section>

<div style="max-width:1200px;margin:0 auto;padding:64px 48px;">
  <?php if (have_posts()): ?>
  <div class="blog-grid">
    <?php while (have_posts()):
    the_post(); ?>
    <div class="blog-card reveal">
      <?php if (has_post_thumbnail()): ?>
      <div class="blog-card__img">
        <a href="<?php the_permalink(); ?>">
          <?php the_post_thumbnail('medium_large'); ?>
        </a>
      </div>
      <?php
    endif; ?>
      <div class="blog-card__body">
        <div class="blog-card__cat">
          <?php the_category(', '); ?>
        </div>
        <a href="<?php the_permalink(); ?>" class="blog-card__title">
          <?php the_title(); ?>
        </a>
        <p class="blog-card__excerpt">
          <?php the_excerpt(); ?>
        </p>
        <div class="blog-card__footer">
          <span class="blog-card__date">
            <?php echo get_the_date('j F Y'); ?>
          </span>
          <a href="<?php the_permalink(); ?>" class="blog-card__link">Читати далі →</a>
        </div>
      </div>
    </div>
    <?php
  endwhile; ?>
  </div>
  <?php ukr_pagination(); ?>
  <?php
else: ?>
  <p style="text-align:center;color:var(--text-light);padding:80px 0;">Статті поки не додані.</p>
  <?php
endif; ?>
</div>

<?php get_footer(); ?>