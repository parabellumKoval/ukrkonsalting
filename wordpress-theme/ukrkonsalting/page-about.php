<?php
/**
 * Page template: About Us
 * Template Name: About Us
 */
get_header();
?>

<!-- Hero -->
<section style="background:var(--green-deep);padding:72px 48px;text-align:center;position:relative;overflow:hidden;">
  <div
    style="position:absolute;inset:0;background:radial-gradient(ellipse at 20% 80%,rgba(74,128,96,.2),transparent 55%);">
  </div>
  <div style="position:relative;z-index:1;max-width:800px;margin:0 auto;">
    <div
      style="display:inline-flex;align-items:center;gap:8px;background:rgba(201,168,76,.15);border:1px solid rgba(201,168,76,.3);border-radius:100px;padding:6px 16px;margin-bottom:20px;font-size:12px;font-weight:600;color:var(--gold-light);">
      Про нас</div>
    <h1
      style="font-family:var(--font-serif,'Lora',serif);color:#fff;font-size:clamp(32px,5vw,52px);font-weight:700;line-height:1.15;margin-bottom:14px;">
      КЦ «<em style="font-style:italic;color:var(--gold-light);">УкрКонсалтинг</em>»</h1>
    <p style="font-size:18px;color:rgba(255,255,255,.6);font-weight:300;max-width:600px;margin:0 auto;">Понад 22 роки ми
      допомагаємо фахівцям промислових підприємств України розбиратись у природоохоронному законодавстві та ефективно
      виконувати свою роботу.</p>
  </div>
</section>

<!-- Stats strip -->
<?php get_template_part('template-parts/global/stats-strip'); ?>

<?php
$about_mission_text = function_exists('get_field') ? get_field('about_mission_text') : '';
$values = function_exists('get_field') ? get_field('about_values') : '';
if (empty($values)) {
$values = [
['icon' => '🎯', 'title' => 'Практична спрямованість', 'text' => 'Тільки актуальні зміни законодавства та реальні
кейси.'],
['icon' => '🔬', 'title' => 'Наукова основа', 'text' => 'Партнерство з Харківським НДІ екологічних проблем.'],
['icon' => '🏆', 'title' => 'Офіційне підтвердження', 'text' => 'Сертифікат про підвищення кваліфікації після
навчання.'],
['icon' => '🤝', 'title' => 'Індивідуальний підхід', 'text' => 'Розбираємо питання конкретного вашого підприємства.'],
];
}
$speakers = function_exists('get_field') ? get_field('about_speakers') : '';
if (empty($speakers)) {
$speakers = [
[
'name' => 'Гутков Георгій Валентинович',
'org' => 'Завідувач сектору · Харківський НДІ екологічних проблем',
'bio' => 'Провідний фахівець у галузі охорони атмосферного повітря та технічної експлуатації газоочисного обладнання.
Багаторічний досвід проведення семінарів для інженерно-технічного персоналу промислових підприємств України.',
'tags' => ['🔬 НДІ екологічних проблем', '⚖️ Природоохоронне законодавство', '🏭 Газоочисне обладнання']
]
];
}
?>

<!-- Content: company story -->
<div style="max-width:1100px;margin:0 auto;padding:80px 48px;">
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:start;" class="reveal">
    <div>
      <div class="section-label">Наша місія</div>
      <h2 class="section-title">
        <?php echo esc_html(function_exists('get_field') && get_field('about_mission_title') ? get_field('about_mission_title') : 'Практичне навчання для реального виробництва'); ?>
      </h2>
      <div class="entry-content">
        <?php if ($about_mission_text): ?>
        <?php echo apply_filters('the_content', $about_mission_text); ?>
        <?php
else: ?>
        <?php the_content(); ?>
        <?php if (!have_posts() || get_the_content() == ''): // fallback content ?>
          <p>Консалтинговий центр «УкрКонсалтинг» спеціалізується на навчанні та підвищенні кваліфікації фахівців у сфері охорони навколишнього середовища. Ми проводимо онлайн-семінари (через Zoom) та корпоративне навчання з виїздом для промислових підприємств України.</p>
          <p>Наші програми орієнтовані на практичне застосування: кожен семінар — це конкретні відповіді на актуальні питання, реальні кейси з вашої галузі та розбір змін законодавства 2024–2026 рр.</p>
          <p>Понад 22 роки досвіду та більше 1000 проведених заходів свідчать про нашу репутацію надійного партнера для екологів, головних інженерів та відповідальних за охорону довкілля.</p>
          <?php
  endif; ?>
        <?php
endif; ?>
      </div>
    </div>
    <div>
      <!-- Values cards -->
      <div style="display:flex;flex-direction:column;gap:16px;">
        <?php foreach ($values as $v): ?>
        <div class="why-card" style="display:flex;align-items:flex-start;gap:14px;padding:20px 24px;">
          <div class="why-icon" style="flex-shrink:0;">
            <?php echo esc_html($v['icon']); ?>
          </div>
          <div>
            <h4 style="margin-bottom:4px;">
              <?php echo esc_html($v['title']); ?>
            </h4>
            <p>
              <?php echo esc_html($v['text']); ?>
            </p>
          </div>
        </div>
        <?php
endforeach; ?>
      </div>
    </div>
  </div>
</div>

<!-- Speaker / Team -->
<div style="background:var(--cream);padding:72px 0;">
  <div style="max-width:900px;margin:0 auto;padding:0 48px;">
    <div class="section-label reveal">Наші викладачі</div>
    <h2 class="section-title reveal">Хто проводить семінари</h2>
    <?php foreach ($speakers as $i => $s):
  $delay = 'reveal-delay-' . (($i % 4) + 1); ?>
    <div class="speaker-wrap reveal <?php echo esc_attr($delay); ?>">
      <div class="speaker-ava">
        <?php echo esc_html(mb_strtoupper(mb_substr($s['name'], 0, 1))); ?>
      </div>
      <div>
        <div class="speaker-name">
          <?php echo esc_html($s['name']); ?>
        </div>
        <div class="speaker-org">
          <?php echo esc_html($s['org']); ?>
        </div>
        <p class="speaker-bio">
          <?php echo esc_html($s['bio']); ?>
        </p>
        <?php
  $tags = $s['tags'] ?? [];
  if (!is_array($tags)) {
    $tags = explode(',', $tags);
  }
  foreach ($tags as $tag):
    $tag_text = is_array($tag) ? ($tag['tag'] ?? '') : $tag;
    if ($tag_text):
?>
        <span class="speaker-tag">
          <?php echo esc_html(trim($tag_text)); ?>
        </span>
        <?php
    endif;
  endforeach; ?>
      </div>
    </div>
    <?php
endforeach; ?>
  </div>
</div>

<!-- Testimonials -->
<?php get_template_part('template-parts/global/testimonials'); ?>

<!-- CTA -->
<?php get_template_part('template-parts/global/cta-banner'); ?>

<?php get_footer(); ?>
