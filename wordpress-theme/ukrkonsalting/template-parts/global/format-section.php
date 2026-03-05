<?php
/**
 * Template part: Format / how it works section
 */
$format_items = [
  ['icon' => '📋', 'title' => 'Методичні матеріали', 'desc' => 'Всі учасники отримують матеріали в електронному або друкованому вигляді (за бажанням)'],
  ['icon' => '🎓', 'title' => 'Сертифікат про участь', 'desc' => 'Видається після завершення навчання. Підтверджує підвищення кваліфікації'],
  ['icon' => '💬', 'title' => 'Консультації', 'desc' => 'Включаючи особисті консультації щодо питань конкретного підприємства'],
  ['icon' => '🏭', 'title' => 'Корпоративне навчання', 'desc' => 'Замовте семінар для своїх фахівців — онлайн або з виїздом на підприємство'],
  ['icon' => '📨', 'title' => 'Питання заздалегідь', 'desc' => 'Надсилайте питання на email до початку семінару — лектор розбере кожне'],
];
?>
<div class="section">
  <div class="section-label">Формат та умови</div>
  <div class="section-title">Як проходить навчання</div>
  <p class="section-sub">Все організовано для максимальної зручності учасників без зайвих витрат.</p>

  <div class="format-grid">
    <div class="format-visual">
      <div class="zoom-badge">
        <div class="zoom-icon">📹</div>
        <span>Проводимо через Zoom</span>
      </div>
      <h3>Онлайн-семінари в невеликих групах — зручно та ефективно</h3>
      <div class="format-pills">
        <span class="pill">🗓 По мірі набору групи</span>
        <span class="pill">📍 Вся Україна</span>
        <span class="pill">📎 Електронні матеріали</span>
        <span class="pill">🎓 Сертифікат</span>
        <span class="pill">💬 Q&A сесія</span>
        <span class="pill">🏢 Корпоративний формат</span>
      </div>
    </div>

    <ul class="format-list">
      <?php foreach ($format_items as $item): ?>
      <li>
        <div class="fi">
          <?php echo esc_html($item['icon']); ?>
        </div>
        <div class="ft">
          <strong>
            <?php echo esc_html($item['title']); ?>
          </strong>
          <span>
            <?php echo esc_html($item['desc']); ?>
          </span>
        </div>
      </li>
      <?php
endforeach; ?>
    </ul>
  </div>
</div>