<?php $items = (array) ($section['props']['items'] ?? []); ?>
<section id="ai" data-field-cluster="agents" class="section-pad" aria-labelledby="ai-title">
    <div class="container-x">
        <div class="sec-index" aria-hidden="true"><span class="mono-label"><span class="idx">SEC/<?= sprintf('%02d', $secIndex) ?></span> · <?= e(strtoupper((string) ($section['key'] ?? ''))) ?></span></div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,28rem),1fr));gap:clamp(1.5rem,4vw,4rem);align-items:end;margin-block-end:clamp(2.5rem,6vw,5rem)">
            <h2 id="ai-title" class="display" style="font-size:clamp(2.25rem,5.5vw,4.5rem)" data-reveal><?= e(lf($section['name'])) ?></h2>
            <p class="lede" data-reveal style="--reveal-delay:90ms"><?= e(t('sections.ai_lede')) ?></p>
        </div>

        <div data-reveal-group style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,22rem),1fr));gap:1.25rem">
            <?php foreach ($items as $item): ?>
            <article class="card" style="padding:1.75rem;display:flex;flex-direction:column;gap:1rem">
                <span class="sheen" aria-hidden="true"></span>
                <div style="display:flex;align-items:center;gap:.625rem">
                    <svg width="18" height="18" viewBox="0 0 18 18" aria-hidden="true" style="color:var(--accent)"><path d="M9 1l1.8 4.7L15.5 7l-4.7 1.8L9 13.5 7.2 8.8 2.5 7l4.7-1.3Z" fill="none" stroke="currentColor" stroke-width="1.4"/><circle cx="14.5" cy="14" r="2" fill="none" stroke="currentColor" stroke-width="1.4"/></svg>
                    <h3 style="font-family:var(--font-display);font-size:1.2rem;font-weight:600"><?= e(lf($item['title'] ?? '')) ?></h3>
                </div>
                <p style="color:var(--text-dim);font-size:.9rem;line-height:1.7;flex:1"><?= e(lf($item['body'] ?? '')) ?></p>
                <div style="display:flex;flex-wrap:wrap;gap:.4rem">
                    <?php foreach ((array) ($item['tags'] ?? []) as $tag): ?>
                    <span class="chip" style="font-size:.66rem"><?= e($tag) ?></span>
                    <?php endforeach; ?>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
