<?php
/**
 * Single blog post template
 */
get_header();

the_post();
?>

<article style="max-width:800px;margin:0 auto;padding:48px 48px 96px;" itemscope itemtype="https://schema.org/Article">
  <!-- Breadcrumb -->
  <nav class="bc" style="padding:20px 0 0;margin:0 0 32px;">
    <a href="<?php echo esc_url(home_url('/')); ?>">Головна</a>
    <span class="bc-sep"> › </span>
    <a href="<?php echo esc_url(home_url('/blog/')); ?>">Блог</a>
    <span class="bc-sep"> › </span>
    <span style="color:var(--text-mid)">
      <?php the_title(); ?>
    </span>
  </nav>

  <!-- Category & Date -->
  <div style="display:flex;align-items:center;gap:16px;margin-bottom:16px;font-size:13px;color:var(--text-light);">
    <span>
      <?php the_category(', '); ?>
    </span>
    <span>·</span>
    <time datetime="<?php echo esc_attr(get_the_date('Y-m-d')); ?>">
      <?php echo get_the_date('j F Y'); ?>
    </time>
  </div>

  <!-- Title -->
  <h1
    style="font-family:var(--font-serif,'Lora',serif);font-size:clamp(28px,4vw,42px);font-weight:700;color:var(--green-deep);line-height:1.15;margin-bottom:24px;"
    itemprop="headline">
    <?php the_title(); ?>
  </h1>

  <!-- Featured image -->
  <?php if (has_post_thumbnail()): ?>
  <div style="border-radius:20px;overflow:hidden;margin-bottom:40px;aspect-ratio:16/9;">
    <?php the_post_thumbnail('large', ['style' => 'width:100%;height:100%;object-fit:cover;']); ?>
  </div>
  <?php
endif; ?>

  <!-- Content -->
  <div class="entry-content" itemprop="articleBody">
    <?php the_content(); ?>
  </div>

  <!-- Tags -->
  <?php the_tags('<div style="margin-top:32px;display:flex;flex-wrap:wrap;gap:8px;">', '', '</div>'); ?>

  <!-- Author bio brief -->
  <div
    style="margin-top:48px;padding-top:32px;border-top:1px solid var(--border);display:flex;align-items:center;gap:16px;">
    <div
      style="width:48px;height:48px;border-radius:50%;background:var(--green-mist);display:flex;align-items:center;justify-content:center;font-family:'Lora',serif;font-size:18px;font-weight:700;color:var(--green-soft);flex-shrink:0;">
      <?php echo esc_html(mb_strtoupper(mb_substr(get_the_author(), 0, 1))); ?>
    </div>
    <div>
      <div style="font-size:14px;font-weight:600;color:var(--green-deep);">
        <?php the_author(); ?>
      </div>
      <div style="font-size:12px;color:var(--text-light);">КЦ «УкрКонсалтинг»</div>
    </div>
  </div>

  <!-- Related: CTA -->
  <div style="margin-top:64px;" class="reveal">
    <?php get_template_part('template-parts/global/cta-banner'); ?>
  </div>

  <?php comments_template(); ?>
</article>

<?php get_footer(); ?>