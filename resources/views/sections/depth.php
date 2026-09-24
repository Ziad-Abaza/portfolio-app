<?php $items = (array) ($section['props']['items'] ?? []); ?>
<section id="depth" data-field-cluster="security" class="section-pad" aria-labelledby="depth-title">
    <div class="container-x">
        <div class="sec-index" aria-hidden="true"><span class="mono-label"><span class="idx">SEC/<?= sprintf('%02d', $secIndex) ?></span> · <?= e(strtoupper((string) ($section['key'] ?? ''))) ?></span></div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,28rem),1fr));gap:clamp(1.5rem,4vw,4rem);align-items:end;margin-block-end:clamp(2.5rem,6vw,5rem)">
            <h2 id="depth-title" class="display" style="font-size:clamp(2.25rem,5.5vw,4.5rem)" data-reveal><?= e(lf($section['name'])) ?></h2>
            <p class="lede" data-reveal style="--reveal-delay:90ms"><?= e(t('sections.depth_lede')) ?></p>
        </div>

        <div data-reveal-group style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,17rem),1fr));gap:1.25rem">
            <?php foreach ($items as $item): ?>
            <article class="card" style="padding:1.75rem">
                <span class="sheen" aria-hidden="true"></span>
                <p class="mono-label" style="color:var(--accent);margin-block-end:1rem"><?= e($item['index'] ?? '') ?></p>
                <h3 style="font-family:var(--font-display);font-size:1.25rem;font-weight:600;margin-block-end:.625rem"><?= e(lf($item['title'] ?? '')) ?></h3>
                <p style="color:var(--text-dim);font-size:.9rem;line-height:1.7"><?= e(lf($item['body'] ?? '')) ?></p>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
