# ukrkonsalting SEO Agents (Autonomous Blog Pipeline)

Цель: полностью автономный SEO-блог с **жесткой проверкой фактов** и публикацией в WordPress.

## Модельная иерархия

- **Advanced (дорогая):** `openai-codex/gpt-5.3-codex`
- **Medium:** `openai/gpt-4.1-mini` (можно заменить на `openai/gpt-4.1`)
- **Low-cost:** `nvidia/moonshotai/kimi-k2.5`

## Агенты

1. **Chief SEO Strategist** (Advanced)
2. **Keyword Miner** (Low-cost)
3. **SERP Analyst** (Medium)
4. **Fact Researcher** (Medium)
5. **Fact Verifier** (Advanced)
6. **SEO Writer** (Medium)
7. **Editor E-E-A-T** (Advanced)
8. **On-page QA** (Low-cost)
9. **Publisher (WP)** (Low-cost)
10. **Performance Analyst** (Medium)

Подробная конфигурация: `agents.yaml`.

## Жесткое правило достоверности

Статья не публикуется, если:
- есть хотя бы 1 факт без источника,
- есть конфликт между источниками без явной пометки,
- Fact Verifier не дал `PASS`.

## Пайплайн статьи

`keyword_miner -> serp_analyst -> fact_researcher -> seo_writer -> fact_verifier -> editor_eeat -> onpage_qa -> publisher`

## Пилот на 3 статьях

См. `pilot-3-articles.md`.

## WordPress доступ

Сохраняй доступы только локально в `seo-agents/.env.local` (в проекте уже известны, но лучше сразу ротировать после настройки).

Шаблон:

```env
WP_BASE_URL=http://localhost:8888
WP_ADMIN_USER=...
WP_ADMIN_PASS=...
WP_CATEGORY=Blog
WP_DEFAULT_STATUS=draft
```

## Что уже готово

- Орг-структура агентов
- Контракты входа/выхода
- Шаблоны: brief, fact-check, publish checklist
- Базовые промпты ролей

Следующий шаг: подключить реальный раннер (OpenClaw subagents + scheduler) и прогнать 3 статьи end-to-end.
