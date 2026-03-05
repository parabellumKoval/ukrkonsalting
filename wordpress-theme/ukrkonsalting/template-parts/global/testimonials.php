<?php
/**
 * Template part: Testimonials grid
 * If ACF repeater exists on front page options, uses that; otherwise uses hard-coded defaults.
 */
$testimonials = [];
if (function_exists('get_field')) {
  $page_id = get_option('page_on_front');
  $testimonials = get_field('testimonials', $page_id) ?: [];
}
if (empty($testimonials)) {
  $testimonials = [
    ['text' => 'Семінар дуже структурований і практичний. Всі зміни законодавства пояснені зрозумілою мовою. Матеріали залишаються актуальним довідником у роботі.',
      'name' => 'Оксана В.', 'role' => 'Еколог, металургійне підприємство', 'initial' => 'О'],
    ['text' => 'Нарешті семінар, де можна задати конкретні питання про своє підприємство і отримати реальну відповідь, а не загальні фрази. Буду рекомендувати колегам.',
      'name' => 'Андрій К.', 'role' => 'Головний інженер, хімічний завод', 'initial' => 'А'],
    ['text' => 'Зручний формат, не потрібно нікуди їхати. Ціна дуже доступна порівняно з іншими. Отримала сертифікат і свіжі матеріали одразу після семінару.',
      'name' => 'Наталія М.', 'role' => 'Відповідальна за охорону довкілля', 'initial' => 'Н'],
  ];
}
?>
<div class="testimonials-bg">
  <div class="testimonials-inner">
    <div class="section-label reveal">Відгуки учасників</div>
    <div class="section-title reveal">Що кажуть наші слухачі</div>

    <div class="testimonials-grid">
      <?php foreach ($testimonials as $i => $t):
  $delay = 'reveal-delay-' . (($i % 3) + 1);
  $initial = $t['initial'] ?? mb_strtoupper(mb_substr($t['name'] ?? '?', 0, 1));
?>
      <div class="testi-card reveal <?php echo esc_attr($delay); ?>">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">«
          <?php echo esc_html($t['text']); ?>»
        </p>
        <div class="testi-author">
          <div class="testi-avatar">
            <?php echo esc_html($initial); ?>
          </div>
          <div>
            <div class="testi-name">
              <?php echo esc_html($t['name']); ?>
            </div>
            <div class="testi-role">
              <?php echo esc_html($t['role']); ?>
            </div>
          </div>
        </div>
      </div>
      <?php
endforeach; ?>
    </div>
  </div>
</div>