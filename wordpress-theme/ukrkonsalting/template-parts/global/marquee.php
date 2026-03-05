<?php
/**
 * Template part: Marquee ticker
 */
$items = [
  'Охорона атмосферного повітря',
  'Поводження з відходами',
  'Газоочисне обладнання ГОУ',
  'Природоохоронна діяльність',
  'Правове регулювання',
  'Корпоративне навчання',
  'Онлайн-семінари Zoom',
  'Підвищення кваліфікації',
];
?>
<div class="marquee-wrap">
  <div class="marquee-track">
    <?php
// Duplicate items for seamless loop
$all = array_merge($items, $items);
foreach ($all as $item):
?>
    <div class="marquee-item">
      <div class="marquee-dot"></div>
      <?php echo esc_html($item); ?>
    </div>
    <?php
endforeach; ?>
  </div>
</div>