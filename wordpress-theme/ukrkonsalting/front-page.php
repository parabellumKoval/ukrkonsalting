<?php
/**
 * Homepage template (front-page.php)
 * Displays: hero, stats, marquee, why-us, seminars grid, format, testimonials, CTA, contact strip, newsletter
 */
get_header();
?>

<!-- HERO -->
<?php get_template_part('template-parts/global/hero-home'); ?>

<!-- STATS STRIP -->
<?php get_template_part('template-parts/global/stats-strip'); ?>

<!-- MARQUEE -->
<?php get_template_part('template-parts/global/marquee'); ?>

<!-- WHY US -->
<?php get_template_part('template-parts/global/why-cards'); ?>

<!-- SEMINARS -->
<?php get_template_part('template-parts/global/seminars-grid'); ?>

<!-- FORMAT -->
<?php get_template_part('template-parts/global/format-section'); ?>

<!-- TESTIMONIALS -->
<?php get_template_part('template-parts/global/testimonials'); ?>

<!-- CTA BANNER -->
<?php get_template_part('template-parts/global/cta-banner'); ?>

<?php get_footer(); ?>